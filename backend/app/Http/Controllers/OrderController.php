<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $orders = $query->latest()->paginate(5);

        if ($request->has('order_id') && $request->has('status')) {
            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');

            $order = Order::find($orderId);

            if ($order) {
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
            'orderDetails.color', // Lấy màu sắc của từng chi tiết đơn hàng
            'orderDetails.size',  // Lấy kích cỡ của từng chi tiết đơn hàng
        ])->findOrFail($id);
        
        $payment = DB::table('payments')
        ->where('order_id', $order->id)
        ->first();

        return view('order.show', compact('order', 'payment'));
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
