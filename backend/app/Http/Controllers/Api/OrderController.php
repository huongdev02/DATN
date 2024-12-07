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
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                ->orderByDesc('is_default')
                ->orderByDesc('created_at')
                ->first();

            if (!$shippingAddress) {
                return response()->json([
                    'message' => 'No shipping address found. Please add a new address.',
                    'redirect_url' => route('address.create')
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
            $voucherId = $request->input('voucher_id');
            $discountValue = 0;

            if ($voucherId) {
                $voucher = Voucher::find($voucherId);

                if ($voucher && $voucher->is_active == 1 && $voucher->quantity > 0) {
                    $currentDate = now();

                    // Kiểm tra nếu người dùng đã sử dụng voucher này
                    $voucherUsageExists = DB::table('voucher_usages')
                        ->where('user_id', auth()->id())
                        ->where('voucher_id', $voucherId)
                        ->exists();

                    if ($voucherUsageExists) {
                        return response()->json(['message' => 'Bạn đã sử dụng voucher này rồi.'], 400);
                    }

                    // Kiểm tra ngày bắt đầu và ngày kết thúc của voucher
                    if ($currentDate < $voucher->start_day || $currentDate > $voucher->end_day) {
                        return response()->json(['message' => 'Phiếu giảm giá đã hết hạn hoặc chưa có hiệu lực.'], 400);
                    }

                    // Kiểm tra tổng tiền đơn hàng
                    if ($totalAmount <= $voucher->total_min) {
                        return response()->json(['message' => 'Tổng số tiền đặt hàng thấp hơn mức tối thiểu bắt buộc để được hưởng ưu đãi.'], 400);
                    }

                    if ($totalAmount >= $voucher->total_max) {
                        return response()->json(['message' => 'Tổng số tiền đặt hàng vượt quá mức tối đa được phép hưởng ưu đãi.'], 400);
                    }

                    // Tính giá trị giảm giá và cập nhật số lượng voucher
                    $discountValue = min($voucher->discount_value, $totalAmount);
                    $voucher->increment('used_times');
                    $voucher->decrement('quantity');
                } else {
                    return response()->json(['message' => 'Phiếu mua hàng không hợp lệ'], 400);
                }
            }


            $totalAmount -= $discountValue;


            $order = Order::create([
                'user_id' => $userId,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'payment_method' => $request->input('payment_method', 1),
                'ship_method' => $request->input('ship_method', 1),
                'voucher_id' => $voucherId,
                'ship_address_id' => $shippingAddress->id,
                'discount_value' => $discountValue,
                'status' => 0, // Đang chờ xử lý
            ]);

            $orderDetails = [];
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

                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->quantity -= $cartItem->quantity;
                    $product->sell_quantity += $cartItem->quantity;
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

            // Xóa các item trong giỏ hàng
            CartItem::where('cart_id', $cart->id)->delete();

            // Kiểm tra phương thức thanh toán: nếu là Online Payment (payment_method = 2)
            if ($request->input('payment_method') == 2) {
                $vnpayUrl = $this->createVnpayPaymentUrl($order); // Hàm để tạo URL thanh toán VNPay
                return response()->json([
                    'status' => true,
                    'message' => 'Order created successfully, please complete your payment.',
                    'payment_url' => $vnpayUrl,
                ], 201);
            }

            // Nếu là COD, trả về kết quả đơn hàng đã được tạo
            return response()->json([
                'status' => true,
                'message' => 'Order created successfully, please await delivery.',
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'order_details' => $orderDetails,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // Hàm để tạo URL thanh toán VNPay
    private function createVnpayPaymentUrl($order)
    {
        // Cấu hình thông tin VNPay từ file .env
        $vnpUrl = env('VNP_URL');
        $vnpTmnCode = env('VNP_TMN_CODE'); // Merchant ID
        $vnpHashSecret = env('VNP_HASH_SECRET'); // Secret Key
        $vnpReturnUrl = env('VNP_RETURN_URL');  // URL trả về sau khi thanh toán

        // Số tiền thanh toán (VNPay yêu cầu tính theo đơn vị đồng)
        $vnpAmount = (int) round($order->total_amount * 100); // Amount in VND

        // Kiểm tra số tiền hợp lệ
        if ($vnpAmount <= 0) {
            Log::error('Invalid order amount.', ['order_id' => $order->id, 'amount' => $vnpAmount]);
            throw new \Exception('Invalid order amount.');
        }

        // Tạo các tham số VNPay
        $vnpParams = [
            'vnp_TmnCode' => $vnpTmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_OrderInfo' => 'Payment for Order #' . $order->id,
            'vnp_OrderType' => 'billpayment', // Loại đơn hàng
            'vnp_ReturnUrl' => $vnpReturnUrl,  // URL trả về sau khi thanh toán
            'vnp_TxnRef' => $order->id, // Mã đơn hàng (TxnRef)
            'vnp_CreateDate' => now()->format('YmdHis'), // Thời gian tạo giao dịch
            'vnp_CurrCode' => 'VND', // Mã đơn vị tiền tệ
            'vnp_Locale' => 'vn', // Ngôn ngữ hiển thị
        ];

        // Sắp xếp các tham số theo thứ tự
        ksort($vnpParams);

        // Tạo chuỗi query không bao gồm vnp_SecureHash
        $query = urldecode(http_build_query($vnpParams));

        // Tạo mã bảo mật (Secure Hash)
        $vnpSecureHash = hash_hmac('sha512', $query, $vnpHashSecret);
        $vnpParams['vnp_SecureHash'] = $vnpSecureHash;

        // Ghi log URL và tham số
        Log::info('Generated VNPay URL', ['url' => $vnpUrl, 'params' => $vnpParams]);

        // Trả về URL hoàn chỉnh
        return $vnpUrl . '?' . http_build_query($vnpParams);
    }
}
