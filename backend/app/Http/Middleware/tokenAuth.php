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
    if ($request->has('token')) {
        // Lấy token từ bảng personal_access_tokens
        $accessToken = PersonalAccessToken::findToken($request->token);

        if ($accessToken) {
            // Lấy user từ token và đăng nhập
            $user = $accessToken->tokenable;
            Auth::login($user);

            return redirect()->to('/user/dashboard'); // Chuyển vào dashboard
        }
    }

    return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại');
}

}
