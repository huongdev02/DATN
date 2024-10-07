<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
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
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'total_amount' => 'required|numeric',
                'payment_method' => 'required|string',
                'ship_method' => 'required|string',
                'ship_address_id' => 'required|exists:ship_addresses,id',
                'status' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Có lỗi trong dữ liệu đầu vào.',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $order = Order::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'ship_method' => $request->ship_method,
                'ship_address_id' => $request->ship_address_id,
                'status' => $request->status,
            ]);
    
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
            $order = Order::findOrFail($id);
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
                'product_id' => 'sometimes|exists:products,id',
                'quantity' => 'sometimes|integer|min:1',
                'total_amount' => 'sometimes|numeric',
                'payment_method' => 'sometimes|string',
                'ship_method' => 'sometimes|string',
                'status' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Có lỗi trong dữ liệu đầu vào.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $order = Order::findOrFail($id);
            $order->update($request->all());

            return response()->json([
                'message' => 'Cập nhật đơn hàng thành công.',
                'data' => $order
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại.',
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
