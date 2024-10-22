<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateUser($request);

        $user = User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']), // Sử dụng Hash::make()
            'email' => $validatedData['email'],
            'fullname' => $request->input('fullname'),
            'birth_day' => $request->input('birth_day'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'role' => $request->input('role', 0),
            'is_active' => $request->input('is_active', 1),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $this->validateUser($request, $id);

        $user->username = $validatedData['username'] ?? $user->username;
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']); // Sử dụng Hash::make()
        }
        $user->email = $validatedData['email'] ?? $user->email;
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->birth_day = $request->input('birth_day', $user->birth_day);
        $user->phone = $request->input('phone', $user->phone);
        $user->address = $request->input('address', $user->address);
        $user->role = $request->input('role', $user->role);
        $user->is_active = $request->input('is_active', $user->is_active);

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // Phương thức validate chung cho store và update
    private function validateUser(Request $request, $id = null)
    {
        $uniqueEmail = 'unique:users,email' . ($id ? ",$id" : '');
        $uniqueUsername = 'unique:users,username' . ($id ? ",$id" : '');

        return $request->validate([
            'username' => "required|max:255|$uniqueUsername",
            'password' => 'sometimes|required|min:6',
            'email' => "required|email|$uniqueEmail",
        ]);
    }

    public function changepass()
    {
        return view('user.changepass');
    }

    public function changepass_(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Yêu cầu phải xác nhận mật khẩu mới
        ]);

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có khớp không
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->shipAddresses;

        return view('user.update', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->fullname = $request->input('fullname');
        $user->birth_day = $request->input('birth_day');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            // Lưu ảnh mới và cập nhật đường dẫn vào cột avatar
            $user['avatar'] = Storage::put('avatar', $request->file('avatar'));
        }

        $addressId = $request->input('address_id'); // Nhận ID địa chỉ từ request
        if ($addressId) {
            DB::transaction(function () use ($user, $addressId) {
                // Đặt tất cả các địa chỉ khác về 0
                $user->shipAddresses()->update(['is_default' => 0]);

                // Cập nhật địa chỉ được chọn thành mặc định
                $address = $user->shipAddresses()->find($addressId);
                if ($address) {
                    $address->is_default = 1; // Đặt is_default thành 1
                    $address->save(); // Lưu lại thay đổi
                }
            });
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

   

}
