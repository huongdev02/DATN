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
use Str;
use Throwable;

class AccountController extends Controller
{
    public function register(){
        return view('account.dangki');
    }

    public function register_(Request $request){
        try{
            $user = $request->validate([
                'username'      => 'required|string|unique:users',
                'password'      => 'required|string|min:6',
                'fullname'      => 'nullable|string|max:255',
                'birth_day'     => 'nullable|date',
                'phone'         => 'nullable|string|max:15',
                'email'         => 'nullable|email|unique:users',
                'address'       => 'nullable|string|max:255',
            ]);
    
            // Hash password
            $user['password'] = Hash::make($request->input('password'));
    
            // Default role
            $user['role'] = $request->filled('role') ? $request->input('role') : 0;
    
            // Create the user
            $user = User::query()->create($user);
    
            // Optional: login the user and regenerate session
            // Auth::login($user);
            // $request->session()->regenerate();
    
            return back()->with('success', 'Đăng kí thành công');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    

    public function login(){
        return view('account.dangnhap');
    }

    public function login_(Request $request) {
        $credentials = $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);
    
        // Kiểm tra xem giá trị nhập vào là email hay username
        $loginType = filter_var($credentials['account'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Tạo mảng thông tin đăng nhập dựa trên loại (email hoặc username)
        $loginCredentials = [
            $loginType => $credentials['account'],
            'password' => $credentials['password'],
        ];
    
        // Thử đăng nhập với thông tin đã kiểm tra
        if (Auth::attempt($loginCredentials, true)) {
            $request->session()->regenerate();
    
            /**
             * @var User $user
             */
            $user = Auth::user();
            if ($user->type == 1) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }
    
        return back()->withErrors([
            'account' => 'The provided credentials do not match our records.',
        ])->onlyInput('account');
    }
    



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function rspassword(){
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

    public function updatepassword($token){
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
        return view('account.verify');
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
}
