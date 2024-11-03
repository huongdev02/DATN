<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\StatisticsService;

class AdminController extends Controller
{
    public function admin(Request $request) {
        // Create an instance of ThongkeController
        $thongkeController = app(ThongkeController::class);
        
        // Call the methods from ThongkeController
        $accountData = $thongkeController->account($request);
        $orderData = $thongkeController->order($request);

        // Return the view with the retrieved data
        return view('admin.dashboard', [
            'account' => $accountData,
            'order' => $orderData,
        ]);
    }
    
    public function edit()
    {
        $user = Auth::user();
       
        return view('admin.update', compact('user'));
    }

    public function update(Request $request)
    {
           /**
             * @var User $user
             */
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
            $user['avatar'] = Storage::put('AdminAvatar', $request->file('avatar'));
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    public function changepass()
    {
        return view('admin.changepass');
    }

    public function changepass_(Request $request)
    {
    
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', 
        ]);

           /**
             * @var User $user
             */
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
