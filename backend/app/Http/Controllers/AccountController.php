<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Str;
use Throwable;

class AccountController extends Controller
{
    public function register()
    {
        return view('account.login', ['activeTab' => 'signup']);
    }

    public function register_(Request $request)
    {
        $user = $request->validate([
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed', // Use 'confirmed' for password confirmation
        ]);
        // dd($user);

        try {
            $user['password'] = Hash::make($request->input('password'));

            $user['role'] = $request->filled('role') ? $request->input('role') : 0;

            $user = User::query()->create($user);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('user.dashboard')->with('success', 'Đăng kí thành công');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function login()
    {
        return view('account.login', ['activeTab' => 'signup']);
    }

    public function login_(Request $request)
    {
        $credentials = $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);
    
        $loginType = filter_var($credentials['account'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        $loginCredentials = [
            $loginType => $credentials['account'],
            'password' => $credentials['password'],
        ];
    
        if (Auth::attempt($loginCredentials, true)) {
            $request->session()->regenerate();
    
            /**
             * @var User $user
             */
            $user = Auth::user();
    
            // check is_active
            if ($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors([
                    'account' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ])->onlyInput('account');
            }
    
            if ($user->role == 0) {
                return redirect()->route('user.dashboard')->with('success', 'Đăng nhập thành công');
            } else {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công');
            }
        }
    
        return back()->withErrors([
            'account' => 'Tài khoản không tồn tại hoặc sai tài khoản, mật khẩu',
        ])->onlyInput('account');
    }
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đã đăng xuất thành công');;
    }

    public function rspassword()
    {
        return view('account.login', ['activeTab' => 'forgot']);
    }

    public function rspassword_(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Thành công, vui lòng mở hòm thư trong địa chỉ email đã nhập'])
            : back()->withErrors(['errors' => 'Thất bại, không tìm thấy địa chỉ email này']);
    }

    public function updatepassword($token)
    {
        $email = request()->query('email');
        return view('account.resetpass', ['token' => $token, 'email' => $email]);
    }

    public function updatepassword_(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(\Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Cập nhật mật khẩu thành công, xin mời đăng nhập');
        } else {
            return back()->withErrors(['errors' => $status]);
        }
    }

    public function verify(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra xem email đã được xác thực chưa
        if ($user->email_verified_at != null) {
            return redirect()->back()->with('success', 'Email của bạn đã được xác thực');
        }
    
        // Gửi email xác minh
        $user->sendEmailVerificationNotification();
    

        return redirect()->back()->with('success', 'Email xác minh đã được gửi tới hòm thư của bạn');
    }

  public function verifydone(Request $request, $id, $hash)
{

    $user = User::findOrFail($id);

    // Kiểm tra mã hash với email đã được băm
    if (! hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
        return redirect()->route('home')->withErrors(['email' => 'Invalid verification link.']);
    }

    // Xác thực email
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('edit')->with('success', 'Xác minh email thành công');
}

    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->shipAddresses;

        return view('user.update', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->fullname = $request->input('fullname');
        $user->birth_day = $request->input('birth_day');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            // Lưu ảnh mới và cập nhật đường dẫn vào cột avatar
            $user['avatar'] = Storage::put('avatar', $request->file('avatar'));
        }

        $addressId = $request->input('address_id'); // Nhận ID địa chỉ từ request
        if ($addressId) {
            DB::transaction(function () use ($user, $addressId) {
                // Đặt tất cả các địa chỉ khác về 0
                $user->shipAddresses()->update(['is_default' => 0]);

                // Cập nhật địa chỉ được chọn thành mặc định
                $address = $user->shipAddresses()->find($addressId);
                if ($address) {
                    $address->is_default = 1; // Đặt is_default thành 1
                    $address->save(); // Lưu lại thay đổi
                }
            });
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    public function changepass()
    {
        return view('account.changepass');
    }

    public function changepass_(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Yêu cầu phải xác nhận mật khẩu mới
        ]);

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có khớp không
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
