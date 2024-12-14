<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class tokenAuth

{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        // Kiểm tra xem request có chứa token không
        if ($request->has('token')) {
            // Lấy token từ request
            $token = $request->input('token');

            // Tìm token trong bảng personal_access_tokens
            $accessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();

            if ($accessToken) {
                // Lấy user từ token
                $user = $accessToken->tokenable;

                // Đăng nhập user vào hệ thống
                Auth::login($user);

                // Nếu token hợp lệ, tiếp tục xử lý request mà không chuyển hướng
                return $next($request);
            }
        }

        // Nếu không tìm thấy token hoặc token không hợp lệ, chuyển hướng về trang login
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
    }
}
