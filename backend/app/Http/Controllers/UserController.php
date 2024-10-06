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

class UserController extends Controller
{
    public function user(){
        return view('user.dashboard');
    }

    public function admin(){
        return view('admin.dashboard');
    }
    public function dangki(){
        return view('auth.dangki');
    }

    public function dangki_(Request $request){
        $user = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::query()->create($user);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('user.dashboard')->with('success', 'Dang ki thanh cong');
    }

    public function dangnhap(){
        return view('auth.dangnhap');
    }

    public function dangnhap_(Request $request){
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, true)) {
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
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function reset(){
        return view('auth.resetpass');
    }

    public function reset_(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        $status = Password::sendResetLink(
            $request->only('email') 
        );
    
        return $status === Password::RESET_LINK_SENT
                ? back()->with(['success' => 'Thành công, vui lòng mở hòm thư trong địa chỉ email đã nhập'])
                : back()->withErrors(['errors' => 'Thất bại, không tìm thấy địa chỉ email này']);
    }

    public function resetform($token){
        return view('auth.resetform', ['token' => $token]);
    }

    public function resetupdate(Request $request)
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
                ])->setRememberToken(Str::random(60));
        
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
        return view('auth.verify');
    }

    public function verify_(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->hasVerifiedEmail()) {
            Mail::to($user->email)->send(new VerifyEmail($user));
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
