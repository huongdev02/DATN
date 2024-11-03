<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tất cả các đơn hàng
        $orders = Order::with('user', 'product', 'shipAddress')->get();
    
        // Kiểm tra nếu có yêu cầu cập nhật trạng thái
        if ($request->has('order_id') && $request->has('status')) {
            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');
    
            // Tìm đơn hàng theo ID
            $order = Order::find($orderId);
    
            if ($order) {
                // Kiểm tra nếu trạng thái mới không phải là "Chờ xử lý" (0) và trạng thái hiện tại là "Đã xử lý" (1) hoặc cao hơn
                if ($newStatus == 0 && $order->status >= 1) {
                    return redirect()->back()->with('error', 'Không thể quay lại trạng thái "Chờ xử lý" khi đơn hàng đã được xử lý.');
                }
    
                // Cập nhật trạng thái
                $order->status = $newStatus;
                $order->save();
    
                // Thông báo thành công
                return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
            }
    
            // Nếu không tìm thấy đơn hàng, trả về thông báo lỗi
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }
    
        // Trả về view với danh sách đơn hàng
        return view('order.index', compact('orders'));
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('user', 'product', 'shipAddress', 'orderDetails.product')->findOrFail($id);

        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $order)
    {
        
    }
}
