<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Ship_address;
use App\Models\Voucher;
use App\Models\Voucher_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        try {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            if (!Auth::check()) {
                return response()->json(['message' => 'User not logged in.'], 401);
            }

            $userId = Auth::id();

            // Lấy địa chỉ giao hàng mặc định hoặc mới nhất
            $shippingAddress = Ship_address::where('user_id', $userId)
                ->orderByDesc('is_default') // Ưu tiên địa chỉ mặc định nếu có
                ->orderByDesc('created_at') // Nếu không có thì lấy địa chỉ mới nhất
                ->first();

            if (!$shippingAddress) {
                return response()->json([
                    'message' => 'No shipping address found. Please add a new address.',
                    'redirect_url' => route('address.create') // Thay bằng route của bạn
                ], 400);
            }

            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'No items in the cart.'], 400);
            }

            // Tính tổng số lượng và tổng giá trị đơn hàng
            $totalQuantity = $cartItems->sum('quantity');
            $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->price);

            // Kiểm tra voucher và tính toán giảm giá (nếu có)
            $voucherId = $request->input('voucher_id'); // Lấy voucher ID từ yêu cầu (nếu có)
            $discountValue = 0;

            if ($voucherId) {
                $voucher = Voucher::find($voucherId);

                if ($voucher && $voucher->status == 1 && $voucher->quantity > $voucher->used_times) {
                    // Kiểm tra điều kiện voucher hợp lệ

                    // Tính toán giảm giá dựa trên voucher
                    if ($voucher->type == 0) {
                        // Giảm giá theo giá trị cố định
                        $discountValue = min($voucher->discount_value, $totalAmount);
                    } elseif ($voucher->type == 1) {
                        // Giảm giá theo phần trăm
                        $discountValue = min(($voucher->discount_value / 100) * $totalAmount, $voucher->max_discount);
                    }

                    // Giảm giá không thể lớn hơn tổng đơn hàng
                    $discountValue = max($discountValue, $voucher->discount_min);

                    // Cập nhật voucher đã được sử dụng
                    $voucher->increment('used_times');
                }
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $userId,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'payment_method' => $request->input('payment_method', 1), // Mặc định là chuyển khoản ngân hàng
                'ship_method' => $request->input('ship_method', 1), // Mặc định là giao hàng hỏa tốc
                'voucher_id' => $voucherId, // Ghi ID voucher nếu có
                'ship_address_id' => $shippingAddress->id,
                'discount_value' => $discountValue, // Lưu số tiền giảm giá
                'status' => 0, // Mặc định là đang chờ xử lý
            ]);

            $orderDetails = [];

            // Tạo chi tiết đơn hàng cho từng item trong giỏ hàng
            foreach ($cartItems as $cartItem) {
                $orderDetail = Order_detail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                    'size_id' => $cartItem->size_id,
                    'color_id' => $cartItem->color_id,
                ]);

                // Cập nhật số lượng sản phẩm trong bảng products
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->quantity -= $cartItem->quantity; // Trừ số lượng sản phẩm
                    $product->sell_quantity += $cartItem->quantity; // Tăng số lượng đã bán
                    $product->save();
                }

                $orderDetails[] = $orderDetail;
            }

            // Ghi thông tin vào bảng voucher_usages nếu có voucher
            if ($voucherId && $voucher) {
                Voucher_usage::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'voucher_id' => $voucherId,
                    'discount_value' => $discountValue,
                ]);
            }

            // Xóa các item trong giỏ hàng của người dùng
            CartItem::where('cart_id', $cart->id)->delete();

            // Chuẩn bị dữ liệu trả về
            $responseData = [
                'status' => true,
                'message' => 'Thêm mới đơn hàng thành công, giỏ hàng đã được clear',
                'order_id' => $order->id,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'discount_value' => $discountValue, // Số tiền đã giảm giá
                'order_details' => $orderDetails,
            ];

            return response()->json($responseData, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
