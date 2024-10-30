<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Payment;
use App\Models\Ship_address;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            // Check if user is logged in
            if (!Auth::check()) {
                return response()->json(['message' => 'User not logged in.'], 401);
            }
    
            $userId = Auth::id();
    
            // Get the default or most recent shipping address
            $shippingAddress = Ship_address::where('user_id', $userId)
                ->orderByDesc('is_default') // Prioritize default address if it exists
                ->orderByDesc('created_at') // Otherwise, get the most recent address
                ->first();
    
            // Check if a shipping address is found
            if (!$shippingAddress) {
                return response()->json([
                    'message' => 'No shipping address found. Please add a new address.',
                    'redirect_url' => route('address.create') // Replace with your route
                ], 400);
            }
    
            // Retrieve user's cart
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }
    
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }
    
            // Calculate total quantity and amount
            $totalQuantity = $cartItems->sum('quantity');
            $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->price);
    
            // Create a new order
            $order = Order::create([
                'user_id' => $userId,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'payment_method' => $request->input('payment_method', 1), // default bank transfer
                'ship_method' => $request->input('ship_method', 1), // default express shipping
                'ship_address_id' => $shippingAddress->id,
                'status' => 0, // default to pending
            ]);
    
            $orderDetails = [];
    
            // Create order details for each cart item
            foreach ($cartItems as $cartItem) {
                $orderDetail = Order_detail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price, // Calculate total for each item
                ]);
    
                // Add order detail to the array for response
                $orderDetails[] = $orderDetail;
            }
    
            // Clear the cart items for this user
            CartItem::where('cart_id', $cart->id)->delete();
    
            // Prepare response data including order details
            $responseData = [
                'status' => true,
                'message' => 'Thêm mới đơn hàng thành công, giỏ hàng đã được clear',
                'order_id' => $order->id,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'order_details' => $orderDetails,
            
            ];
    
            return response()->json($responseData, 201);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    

    

    public function show($orderId)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::check()) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }
    
            // Fetch the order with the provided ID, including order details
            $order = Order::with('orderDetails.product') // Load related OrderDetails and Product info
                           ->where('id', $orderId)
                           ->where('user_id', Auth::id()) // Ensure it's the authenticated user's order
                           ->first();
    
            if (!$order) {
                return response()->json(['message' => 'Order not found.'], 404);
            }
    
            // Prepare the response data
            $responseData = [
                'order_id' => $order->id,
                'total_quantity' => $order->quantity,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'ship_method' => $order->ship_method,
                'status' => $order->status,
                'order_details' => $order->orderDetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name, // Assuming Product model has a name field
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'total' => $detail->total,
                    ];
                }),
                'message' => 'Order details retrieved successfully.'
            ];
    
            return response()->json($responseData, 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
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
