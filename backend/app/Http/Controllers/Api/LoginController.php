<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;
use Throwable;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'id' => $user->id,
                        'fullname' => $user->fullname,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'role' => $user->role,
                    ],
                    'message' => 'Đăng nhập thành công',
                ], HttpResponse::HTTP_OK); 
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thông tin tài khoản không chính xác'
                ], HttpResponse::HTTP_UNAUTHORIZED); 
            }
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
