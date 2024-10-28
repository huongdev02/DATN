<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with('orderDetails')->get();
            return response()->json([
                'message' => 'Lấy tất cả đơn hàng thành công.',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy danh sách đơn hàng.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'ship_method' => 'required|integer|min:0|max:3',
            'status' => 'required|integer|min:0|max:3',
            'ship_address_id' => 'required|exists:ship_addresses,id',
            'order_details.*.product_detail_id' => 'required|exists:product_details,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric',
            'order_details.*.name_product' => 'required|string|max:250',
            'order_details.*.size' => 'required|string|max:50',
            'order_details.*.color' => 'required|string|max:50',
            'order_details.*.total' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Có lỗi trong dữ liệu đầu vào.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'user_id',
            'total_amount',
            'payment_method_id',
            'ship_method',
            'status',
            'ship_address_id'
        ]);
        
        $order = Order::create($data);
        
        foreach ($request->order_details as $detail) {
            $detail['order_id'] = $order->id; 
            Order_detail::create($detail);
        }

        return response()->json([
            'message' => 'Thêm đơn hàng thành công.',
            'data' => $order
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Có lỗi xảy ra khi thêm đơn hàng.',
            'error' => $e->getMessage()
        ], 500);
    }
}



    public function show($id)
    {
        try {
            $order = Order::with('orderDetails')->findOrFail($id);
            return response()->json([
                'message' => 'Lấy đơn hàng thành công.',
                'data' => $order
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy đơn hàng.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'sometimes|exists:users,id',
                'order_id' => 'sometimes|exists:orders,id',
                'amount' => 'sometimes|numeric',
                'payment_method' => 'sometimes|string',
                'status' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Có lỗi trong dữ liệu đầu vào.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $payment = Payment::findOrFail($id);
            $payment->update($request->all());

            return response()->json([
                'message' => 'Cập nhật thanh toán thành công.',
                'data' => $payment
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Thanh toán không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật thanh toán.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Tìm đơn hàng cần xóa
            $order = Order::findOrFail($id);

            // Xóa tất cả các đánh giá liên quan đến đơn hàng
            $order->reviews()->delete();

            // Xóa đơn hàng
            $order->delete();

            return response()->json([
                'message' => 'Xóa đơn hàng thành công.'
            ], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa đơn hàng.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
