<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['product', 'user']); 
    
        if ($request->has('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }
    
        $orders = $query->latest()->paginate(5);
    
        return view('user.order', compact('orders'));
    }

    public function show($id)
{
    // Retrieve the order by ID, including product and user relationships
    $order = Order::with(['product', 'user'])->find($id);

    // Check if the order exists
    if (!$order) {
        return back()->with('error', 'Đơn hàng không tồn tại.');
    }

    // Flash the order data to the session
    session(['selected_order' => $order]);

    // Redirect to the index view to display the selected order details
    return redirect()->route('userorder.show')->with('message', 'Chi tiết đơn hàng đã được hiển thị.');
}

}
