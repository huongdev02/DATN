<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Throwable;

class AccountController extends Controller
{

    public function register(Request $request)
    {
    $validatedData = $request->validate([
        'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
        'password' => 'required|string|min:6|confirmed', // Use 'confirmed' for password confirmation
    ]);

    try {
        $validatedData['password'] = Hash::make($request->input('password'));
        $validatedData['role'] = $request->filled('role') ? $request->input('role') : 0;

        $user = User::query()->create($validatedData);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng kí thành công',
            'data' => $user
        ], 201);

    } catch (Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi khi đăng kí',
            'error' => $e->getMessage()
        ], 500);
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
                    'status' => true,
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

    public function show($userId)
    {
        try{
            $user = User::with('shipAddresses')->find($userId);
            return response()->json($user);
        }catch(Throwable $e){
            return response()->json(['message' => 'User not found'] . $e->getMessage(), 404);
        }
    }

    public function logout(Request $request)
{
    /**
     * @var User $user
     */
    $user = Auth::user();

    if ($user) {
        // Xóa tất cả các token của người dùng
        $user->tokens()->delete();
    }

    // Đăng xuất người dùng
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Xóa cookie chứa token
    $cookie = Cookie::forget('token');

    // Chuyển hướng về frontend và gửi một thông điệp qua query parameter
    return redirect('/login?logout=success')->withCookie($cookie);
}

    public function checkAuth(Request $request)
    {
        try {
            // Lấy token từ header (hoặc từ cookie nếu đã được lưu)
            $token = $request->bearerToken(); // Hoặc bạn có thể lấy từ cookie nếu cần
    
            // Nếu không có token, trả về thông báo chưa đăng nhập
            if (!$token) {
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Token not provided.',
                ], 401);
            }
    
            // Xác thực token và lấy thông tin người dùng
            $user = Auth::guard('sanctum')->user(); // Sử dụng guard của Sanctum để lấy người dùng
    
            if ($user) {
                return response()->json([
                    'authenticated' => true,
                    'user' => $user, // Trả về thông tin người dùng
                    'role' => $user->role, // Trả về vai trò của người dùng
                ]);
            }
    
            // Nếu không tìm thấy người dùng, token không hợp lệ
            return response()->json([
                'authenticated' => false,
                'message' => 'Invalid token.',
            ], 401);
    
        } catch (\Exception $e) {
            // Xử lý các lỗi và trả về thông tin lỗi
            return response()->json([
                'authenticated' => false,
                'error' => 'An error occurred while checking authentication status.',
                'message' => $e->getMessage(),
            ], 500); // 500 error cho các vấn đề phía server
        }
    }
    
}
