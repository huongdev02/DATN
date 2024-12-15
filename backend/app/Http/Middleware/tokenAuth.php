<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        // Log toàn bộ request nhận được
        Log::info('Middleware triggered. Request data: ', $request->all());

        // Kiểm tra nếu user đã đăng nhập qua session
        if (Auth::check()) {
            Log::info('User is already authenticated via session. User ID: ' . Auth::id());
            return $next($request);
        }

        // Kiểm tra xem request có chứa token không
        if ($request->has('token')) {
            // Lấy token từ request
            $token = $request->input('token');
            Log::info('Token received: ' . $token);

            // Hash token và tìm trong bảng personal_access_tokens
            $hashedToken = hash('sha256', $token);
            Log::info('Hashed token: ' . $hashedToken);

            $accessToken = PersonalAccessToken::where('token', $hashedToken)->first();

            if ($accessToken) {
                Log::info('Token found in database. AccessToken ID: ' . $accessToken->id);

                // Lấy user từ token
                $user = $accessToken->tokenable;

                if ($user) {
                    Log::info('User retrieved from token. User ID: ' . $user->id . ', Role: ' . $user->role);

                    // Đăng nhập user vào hệ thống
                    Auth::login($user);
                    Log::info('User logged in successfully.');

                    // Lưu token vào session để sử dụng cho các request tiếp theo
                    session(['auth_token' => $token]);

                    return $next($request); // Chuyển đến request tiếp theo
                } else {
                    Log::error('Failed to retrieve user from tokenable relation.');
                }
            } else {
                Log::warning('Token not found in database.');
            }
        } elseif (session()->has('auth_token')) {
            // Nếu không có token trong request nhưng có trong session
            $token = session('auth_token');
            Log::info('Token retrieved from session: ' . $token);

            // Hash token và tìm trong bảng personal_access_tokens
            $hashedToken = hash('sha256', $token);
            $accessToken = PersonalAccessToken::where('token', $hashedToken)->first();

            if ($accessToken) {
                Log::info('Session token validated. AccessToken ID: ' . $accessToken->id);

                // Lấy user từ token
                $user = $accessToken->tokenable;

                if ($user) {
                    Log::info('User retrieved from session token. User ID: ' . $user->id . ', Role: ' . $user->role);

                    // Đăng nhập user vào hệ thống
                    Auth::login($user);
                    Log::info('User logged in successfully from session token.');

                    return $next($request); // Chuyển đến request tiếp theo
                } else {
                    Log::error('Failed to retrieve user from tokenable relation for session token.');
                }
            } else {
                Log::warning('Session token not found in database. Clearing session.');
                session()->forget('auth_token');
            }
        } else {
            Log::warning('Token not found in request or session.');
        }

        // Nếu không tìm thấy token hoặc token không hợp lệ
        Log::info('Redirecting to login due to invalid or missing token.');
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại.');
    }
}
