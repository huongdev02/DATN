<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'shipAddress', 'orderDetails');

        if ($request->has('status') && $request->input('status') !== '') {
            // Lọc theo trạng thái nếu có giá trị trong input
            $status = $request->input('status');
            if ($status !== '') {
                $query->where('status', $status);
            }
        }

        // Lấy danh sách đơn hàng (nếu không có trạng thái, sẽ lấy tất cả)
        $orders = $query->orderByRaw("CASE WHEN status = 0 THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(5);

        if ($request->has('order_id') && $request->has('status')) {
            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');

            $order = Order::find($orderId);

            if ($order) {
                $oldStatus = $order->status; // Lưu trạng thái cũ để so sánh

                // Điều kiện gửi email nếu trạng thái thay đổi từ 0 sang 1
                if ($oldStatus == 0 && $newStatus == 1) {
                    // Gửi email thông báo cho người dùng
                    Mail::to($order->user->email)->send(new OrderStatusChanged($order));
                }

                // Điều kiện gửi email nếu trạng thái thay đổi từ 2 sang 3
                if ($oldStatus == 2 && $newStatus == 3) {
                    // Gửi email thông báo giao hàng thành công
                    Mail::to($order->user->email)->send(new OrderDelivered($order)); // Bạn có thể tạo một Mailable mới OrderDelivered
                }

                // Cập nhật trạng thái mới của đơn hàng
                $order->status = $newStatus;
                $order->save();

                return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
            }

            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('order.index', compact('orders'));
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with([
            'user',
            'product',
            'shipAddress',
            'orderDetails.product',
            'orderDetails.color',
            'orderDetails.size',
            'payment' // Load thông tin thanh toán của đơn hàng
        ])->findOrFail($id);

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
}
