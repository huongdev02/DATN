<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array', // Thay đổi để yêu cầu một mảng các sản phẩm
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1', // Số lượng cho từng sản phẩm
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|integer', // Chuyển sang số cho payment_method
            'ship_method' => 'required|integer', // Chuyển sang số cho ship_method
            'ship_address_id' => 'required|exists:ship_addresses,id',
            'status' => 'required|integer', // Thay đổi thành integer
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Có lỗi trong dữ liệu đầu vào.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Bắt đầu giao dịch
        DB::beginTransaction();

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'ship_method' => $request->ship_method,
            'ship_address_id' => $request->ship_address_id,
            'status' => $request->status,
        ]);

        // Tạo chi tiết đơn hàng
        foreach ($request->items as $item) {
            Order_detail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Giá sản phẩm, có thể lấy từ DB nếu cần
                'total' => $item['quantity'] * $item['price'], // Tính tổng cho từng sản phẩm
            ]);
        }

        // Cam kết giao dịch
        DB::commit();

        return response()->json([
            'message' => 'Thêm đơn hàng thành công.',
            'data' => $order
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack(); // Hoàn tác giao dịch nếu có lỗi xảy ra
        return response()->json([
            'message' => 'Có lỗi xảy ra khi thêm đơn hàng.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    

public function show($id)
{
    try {
        $order = Order::with('orderDetails.product')->findOrFail($id); // Tìm đơn hàng cùng với chi tiết sản phẩm

        return response()->json([
            'message' => 'Lấy thông tin đơn hàng thành công.',
            'data' => $order
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Đơn hàng không tồn tại.'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Có lỗi xảy ra khi lấy thông tin đơn hàng.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'sometimes|numeric',
            'payment_method' => 'sometimes|integer',
            'ship_method' => 'sometimes|integer',
            'ship_address_id' => 'sometimes|exists:ship_addresses,id',
            'status' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Có lỗi trong dữ liệu đầu vào.',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::findOrFail($id); // Tìm đơn hàng
        $order->update($request->only(['total_amount', 'payment_method', 'ship_method', 'ship_address_id', 'status']));

        return response()->json([
            'message' => 'Cập nhật đơn hàng thành công.',
            'data' => $order
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Đơn hàng không tồn tại.'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Có lỗi xảy ra khi cập nhật đơn hàng.',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function destroy($id)
{
    try {
        $order = Order::findOrFail($id); // Tìm đơn hàng
        $order->delete(); // Xóa đơn hàng

        return response()->json([
            'message' => 'Xóa đơn hàng thành công.'
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Đơn hàng không tồn tại.'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Có lỗi xảy ra khi xóa đơn hàng.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
}
