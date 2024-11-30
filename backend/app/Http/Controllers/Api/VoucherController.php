<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!Auth::check()) {
            return response()->json(['message' => 'User not logged in.'], 401);
        }

        $userId = Auth::id();

        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            return response()->json(['message' => 'No cart found.'], 400);
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart.'], 400);
        }

        // Tính tổng giá trị đơn hàng
        $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->price);

        // Lấy tất cả các voucher hợp lệ
        $vouchers = Voucher::where('status', 1) // Voucher đang hoạt động
            ->where('quantity', '>', 'used_times') // Voucher còn số lượng
            ->where(function ($query) {
                $query->whereNull('start_day')
                    ->orWhere('start_day', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_day')
                    ->orWhere('end_day', '>=', now());
            })
            ->where('min_order_count', '<=', $cartItems->count()) // Kiểm tra số lượng sản phẩm trong giỏ
            ->where('max_order_count', '>=', $cartItems->count()) // Kiểm tra số lượng sản phẩm trong giỏ
            ->get();

        $applicableVouchers = [];

        foreach ($vouchers as $voucher) {
            // Tính toán giá trị giảm giá cho voucher
            $discountValue = 0;
            if ($voucher->type == 0) {
                // Nếu voucher là giảm giá theo giá trị cố định
                $discountValue = min($voucher->discount_value, $totalAmount);
            } elseif ($voucher->type == 1) {
                // Nếu voucher là giảm giá theo phần trăm
                $discountValue = min(($voucher->discount_value / 100) * $totalAmount, $voucher->max_discount);
            }

            // Kiểm tra xem số tiền giảm giá có đủ điều kiện không (phải lớn hơn hoặc bằng giá trị giảm tối thiểu)
            if ($discountValue >= $voucher->discount_min) {
                $applicableVouchers[] = [
                    'voucher' => $voucher,
                    'discount_value' => $discountValue,
                ];
            }
        }

        // Sắp xếp các voucher theo giá trị giảm giá từ cao đến thấp
        usort($applicableVouchers, function ($a, $b) {
            return $b['discount_value'] <=> $a['discount_value'];
        });

        return response()->json([
            'status' => true,
            'vouchers' => $applicableVouchers,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Voucher  $voucher)
    {
        try {
            return response()->json($voucher);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin voucher: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher  $voucher) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher  $voucher) {}
}
