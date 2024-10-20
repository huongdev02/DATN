<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách người dùng.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8',
                'email' => 'required|string|email|max:255|unique:users,email',
                'fullname' => 'nullable|string|max:255',
                'birth_day' => 'nullable|date',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'role' => 'nullable|integer|in:0,1,2', // 0: user, 1: nhân viên, 2: admin
                'is_active' => 'nullable|boolean',
            ]);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'fullname' => $request->fullname,
                'birth_day' => $request->birth_day,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role ?? 0, // default role is user
                'is_active' => $request->is_active ?? 1, // default is active
            ]);

            return response()->json(['message' => 'Người dùng đã được thêm thành công.', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm người dùng: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin người dùng: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
                'password' => 'nullable|string|min:8',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'fullname' => 'nullable|string|max:255',
                'birth_day' => 'nullable|date',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'role' => 'nullable|integer|in:0,1,2',
                'is_active' => 'nullable|boolean',
            ]);

            $user->update($request->only(['username', 'fullname', 'birth_day', 'phone', 'address', 'role', 'is_active']));

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return response()->json(['message' => 'Người dùng đã được cập nhật thành công.', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật người dùng: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'Người dùng đã được xóa thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa người dùng: ' . $e->getMessage()], 500);
        }
    }
}
