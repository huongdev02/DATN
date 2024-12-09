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


    public function register(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,username'],  // Username validation
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|same:password', // Ensure confirm_password matches password
        ]);
    
        try {
            // Prepare user data
            $userData = [
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),  // Hash the password
                'role' => $request->filled('role') ? $request->input('role') : 0,  // Default role is 0
            ];
    
            // Create user
            $user = User::create($userData);
    
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Đăng kí thành công',
                'data' => [
                    'email' => $user->email,
                    'username' => $user->username,
                ]
            ], 200);
    
        } catch (\Throwable $e) {
            // Handle error
            return back()->with('error', $e->getMessage());
        }
    }
    

    public function login(Request $request)
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Kiểm tra xem có tồn tại tài khoản với email và mật khẩu không
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], true)) {
                $request->session()->regenerate();

                // Lấy thông tin người dùng đã đăng nhập
                $user = Auth::user();

                // Kiểm tra trạng thái tài khoản
                if ($user->is_active == 0) {
                    Auth::logout();
                    return response()->json([
                        'error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                    ], 403);
                }

                /**
                 * @var User $user
                 */

                // Tạo token cho người dùng
                $token = $user->createToken('API Token')->plainTextToken;

                // Trả về dữ liệu người dùng và token
                return response()->json([
                    'status' => true,
                    'message' => 'Đăng nhập thành công',
                    'data' => [
                        'user' => [
                            'id'        => $user->id,
                            'email'     => $user->email,
                            'username'  => $user->username,
                            'fullname'  => $user->fullname,
                            'birth_day' => $user->birth_day,
                            'phone'     => $user->phone,
                            'address'   => $user->address,
                            'role'      => $user->role,
                            'is_active' => $user->is_active,
                           'avatar' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,

                        ],
                        'token' => $token
                    ]
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
            $user = User::with('shipAddresses')->find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $token = substr($user->createToken('API Token')->plainTextToken, 3);

            $user->avatar = asset('storage/' . $user->avatar);

            $filePath = storage_path('app/user_' . $userId . '.txt');

            if (file_exists($filePath)) {
                $data = json_decode(file_get_contents($filePath), true);
                $data['token'] = $token;
                $data['user'] = $user;

                return response()->json($data);
            }
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 404);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Revoke all of the user's tokens
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'status' => true,
                'message' => 'Đăng xuất thành công'
            ], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
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
