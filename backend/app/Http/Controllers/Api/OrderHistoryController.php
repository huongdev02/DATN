<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function getOrderHistory($userId)
    {
        try {
            $orders = Order::with([
                'orderDetails.product',
                'shipAddress'   
            ])
            ->where('user_id', $userId)
            ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch sử đặt hàng.',
                    'data' => []
                ], 404);
            }

            $formattedOrders = $orders->map(function ($order) {
                return [
                    'orderId' => $order->id,
                    'totalAmount' => $order->total_amount,
                    'createdAt' => $order->created_at,
                    'shipAddress' => [
                        'userId' => $order->shipAddress->user_id,
                        'phoneNumber' => $order->shipAddress->phone_number,
                        'shipAddress' => $order->shipAddress->ship_address,
                    ],
                    'orderDetails' => $order->orderDetails->map(function ($detail) {
                        return [
                            'productId' => $detail->product_id,
                            'productName' => $detail->product->name ?? 'Unknown',
                            'quantity' => $detail->quantity,
                            'price' => $detail->price,
                            'total' => $detail->quantity * $detail->price,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Lấy lịch sử đặt hàng thành công.',
                'data' => $formattedOrders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy lịch sử đặt hàng.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
