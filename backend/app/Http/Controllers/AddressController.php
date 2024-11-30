<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship_address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Ship_address::where('user_id', Auth::id())->get();
        return view('user.address', compact('addresses'));
    }

    public function create()
    {
        return view('user.addresscreate');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'ship_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'recipient_name' => 'required|string|max:255',
            'is_default' => Rule::in([0, 1]),
        ]);

        $data['is_default'] = $request->filled('is_default') ? 1 : 0;

        Ship_address::create(array_merge($data, [
            'user_id' => Auth::id(),
        ]));


        return redirect()->route('address.index')->with('success', 'Địa chỉ giao hàng đã được thêm mới thành công.');
    }
    public function setDefault($id)
    {
        $address = Ship_address::findOrFail($id);

        Ship_address::where('user_id', Auth::id())->update(['is_default' => 0]);

        $address->is_default = 1;
        $address->save();

        return back()->with('success', 'Địa chỉ đã được đặt làm mặc định.');
    }
    public function edit($id)
    {
        $address = Ship_address::findOrFail($id);
        return view('user.editAddress', compact('address'));
    }

    // Xử lý cập nhật địa chỉ
    public function update(Request $request, $id)
{
    $address = Ship_address::findOrFail($id);
    $data = $request->validate([
        'recipient_name' => 'required|string|max:255',
        'ship_address' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
    ]);

    $data['is_default'] = $request->has('is_default') ? 1 : 0; 

    $address->update($data);

    return back()->with('success', 'Cập nhật địa chỉ thành công.');
}

}
