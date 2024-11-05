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
    public function cancelOrder(Request $request, $id)
{
    $order = Order::findOrFail($id);
    // Kiểm tra trạng thái đơn hàng có thể hủy hay không
    if ($order->status == 0 || $order->status == 1) {
        // Cập nhật trạng thái đơn hàng thành đã hủy
        $order->status = 4; // Giả sử 4 là trạng thái đã hủy
        $order->save();

        // Bạn có thể thêm logic ghi lại lý do hủy nếu cần
        // $order->cancel_reason = $request->reason;
        // $order->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Đơn hàng không thể hủy']);
}

public function confirmReceiveOrder(Request $request, $id)
{
    $order = Order::findOrFail($id);
    // Kiểm tra trạng thái đơn hàng có thể xác nhận hay không
    if ($order->status == 3) { // Giả sử 3 là trạng thái đã hoàn thành
        // Cập nhật trạng thái đơn hàng thành đã nhận
        // $order->status = ...; // Cập nhật trạng thái nếu cần
        // $order->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Đơn hàng không thể xác nhận']);
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
