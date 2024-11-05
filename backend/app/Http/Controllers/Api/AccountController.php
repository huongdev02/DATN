<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Throwable;

class AccountController extends Controller
{


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
                    'status' => true,
                    'message' => 'Đăng nhập thành công', 
                    'data' => $user
                ], 200);
            }

            return response()->json([
                'error' => 'Tài khoản không tồn tại hoặc sai tài khoản, mật khẩu'
            ], 401);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($userId)
    {
        try {
            // Retrieve the user with related ship addresses
            $user = User::with('shipAddresses')->find($userId);
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            // Generate token for the user and remove the first three characters
            $token = substr($user->createToken('API Token')->plainTextToken, 3);
    
            // Full path for the avatar
            $user->avatar = asset('storage/' . $user->avatar);
    
            $filePath = storage_path('app/user_' . $userId . '.txt');
    
            // Check if the file exists
            if (file_exists($filePath)) {
                $data = json_decode(file_get_contents($filePath), true);
                $data['token'] = $token;
                $data['user'] = $user;
    
                return response()->json($data);
            }
    
            // Return user data with token
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
    
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 404);
        }
    }
    

public function checkAuth(Request $request)
{
    // Lấy token từ cookie
    $tokenFromCookie = $request->cookie('token');
    
    // Xóa 4 ký tự đầu nếu token từ cookie tồn tại
    if ($tokenFromCookie && strlen($tokenFromCookie) > 4) {
        $tokenFromCookie = substr($tokenFromCookie, 4); // Xóa 4 ký tự đầu tiên
    }

    // Lấy token từ header
    $tokenFromHeader = $request->header('Authorization');

    // Nếu token từ header có dạng "Bearer token", tách ra để lấy token
    if ($tokenFromHeader && preg_match('/Bearer\s(\S+)/', $tokenFromHeader, $matches)) {
        $tokenFromHeader = $matches[1]; // Lấy phần token sau "Bearer "
    }

    // Chọn token từ cookie nếu có, nếu không thì lấy từ header
    $token = $tokenFromCookie ?: $tokenFromHeader;

    Log::info('Received token: ' . $token);

    if (!$token) {
        return response()->json(['authenticated' => false, 'message' => 'Token not provided.'], 401);
    }

    // Mã hóa token để so sánh
    $hashedToken = hash('sha256', $token);
    Log::info('Hashed token: ' . $hashedToken);
    
    // Kiểm tra token trong bảng personal_access_tokens
    $tokenRecord = PersonalAccessToken::where('token', $hashedToken)->first();

    if ($tokenRecord) {
        $user = $tokenRecord->tokenable;
        return response()->json([
            'authenticated' => true,
            'user' => $user,
            'role' => $user->role,
        ]);
    }

    return response()->json(['authenticated' => false, 'message' => 'Invalid token.'], 401);
}


}
