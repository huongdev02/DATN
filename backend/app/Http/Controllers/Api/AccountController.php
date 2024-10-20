<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = $request->validate([
                'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user['password'] = Hash::make($request->input('password'));
            $user['role'] = $request->filled('role') ? $request->input('role') : 0;

            $user = User::create($user);

            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Đăng kí thành công',
                'data' => $user
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'account' => 'required',
                'password' => 'required',
            ]);

            $loginType = filter_var($credentials['account'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            if (Auth::attempt([$loginType => $credentials['account'], 'password' => $credentials['password']], true)) {
                $request->session()->regenerate();

                $user = Auth::user();
                if ($user->is_active == 0) {
                    Auth::logout();
                    return response()->json([
                        'error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                    ], 403);
                }

                return response()->json([
                    'message' => 'Đăng nhập thành công', 
                    'data' => $user
                ], 200);
            }

            return response()->json([
                'error' => 'Tài khoản không tồn tại hoặc sai tài khoản, mật khẩu'
            ], 401);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function rspassword(Request $request)
{
    try {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Thành công, vui lòng mở hòm thư trong địa chỉ email đã nhập'], 200)
            : response()->json(['error' => 'Thất bại, không tìm thấy địa chỉ email này'], 404);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

public function update(Request $request)
{
    try {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->update($request->only('fullname', 'birth_day', 'phone', 'email', 'address'));

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $user['avatar'] = Storage::put('avatar', $request->file('avatar'));
        }

        $user->save();

        return response()->json(['message' => 'Cập nhật thông tin thành công', 'user' => $user], 200);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

public function changepass(Request $request)
{
    try {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không đúng.'], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->update();

        return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công'], 200);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

}
