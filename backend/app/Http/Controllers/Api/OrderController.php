<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Ship_address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

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

                // Cập nhật số lượng sản phẩm trong bảng products
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->quantity -= $cartItem->quantity; // Trừ số lượng sản phẩm
                    $product->sell_quantity += $cartItem->quantity; // Tăng số lượng đã bán
                    $product->save(); // Lưu lại thay đổi
                }

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
}
