<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Str;
use Throwable;

class AccountController extends Controller
{
    public function register()
    {
        return view('account.dangki');
    }

    public function register_(Request $request)
    {
        $user = $request->validate([
            'username'      => 'required|string|unique:users',
            'password'      => 'required|string|min:6',
            'fullname'      => 'nullable|string|max:255',
            'birth_day'     => 'nullable|date',
            'phone'         => 'nullable|string|max:15',
            'email'         => 'nullable|email|unique:users',
            'address'       => 'nullable|string|max:255',
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
        return view('account.dangnhap');
    }

    public function login_(Request $request)
    {
        $credentials = $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);

        // dd  ($credentials);

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
            if ($user->type == 0) {
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
        return view('account.resetpass');
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
        return view('account.resetform', ['token' => $token]);
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
                ])->setRememberToken(str()::random(60));

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

    public function verify()
    {
        return view('account.verifyemailform');
    }

    public function verify_(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->hasVerifiedEmail()) {
            Mail::to($user->email)->send(($user));
            return redirect()->back()->with('success', 'vui lòng kiểm tra email để tiến hành xác minh');
        }

        return redirect()->back()->with('info', 'email của bạn đã xác minh rồi');
    }

    public function verifydone($id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->email . $user->created_at))) {
            return redirect('/user')->with('error', 'liên kết không hợp lệ');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/user')->with('info', 'tài khoản của bạn đã xác thực rồi');
        }

        $user->markEmailAsVerified();

        return redirect('/user')->with('success', 'xác minh email thành công');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('account.update', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image', // Giới hạn kích thước ảnh
        ]);

        // Cập nhật thông tin người dùng
        $user->fullname = $request->input('fullname');
        $user->birth_day = $request->input('birth_day');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');

        // Xử lý ảnh đại diện (avatar)
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }

            // Lưu ảnh mới và cập nhật đường dẫn vào cột avatar
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        // Lưu thông tin đã cập nhật
        $user->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    public function changepass(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // Yêu cầu phải xác nhận mật khẩu mới
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
