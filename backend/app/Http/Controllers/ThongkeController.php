<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Voucher_usage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongkeController extends Controller
{
    public function account(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query cơ bản
        $query = User::where('role', 0);

        // Số lượng người dùng mới tháng này (luôn độc lập)
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentCount = User::where('role', 0)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Số lượng người dùng mới tháng trước (luôn độc lập)
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $lastCount = User::where('role', 0)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        // Số lượng người dùng mới theo bộ lọc (chỉ khi có form lọc)
        $filteredCount = 0;
        if ($startDate && $endDate) {
            $filteredCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();
        }

        // Chuẩn bị dữ liệu để đổ vào view
        $data = [
            'current_count' => $currentCount,    // Tháng này
            'last_count' => $lastCount,          // Tháng trước
            'filtered_count' => $filteredCount,  // Theo bộ lọc
        ];

        // Nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.account', compact('data'))->render();
        }

        // Trả về view
        return view('thongke.account', compact('data'));
    }
    public function orders(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query cho đơn hàng đã hoàn thành (tất cả các đơn hàng)
        $query = Order::where('status', 3);

        // Tính toán doanh thu và số lượng đơn hàng cho tháng này, không bị ảnh hưởng bởi bộ lọc
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentRevenue = Order::where('status', 3)->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->sum('total_amount');
        $currentOrderCount = Order::where('status', 3)->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();

        // Tính toán doanh thu và số lượng đơn hàng cho tháng trước, không bị ảnh hưởng bởi bộ lọc
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $lastRevenue = Order::where('status', 3)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total_amount');
        $lastOrderCount = Order::where('status', 3)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        // Tính sự thay đổi giữa tháng hiện tại và tháng trước
        $changeRevenue = $currentRevenue - $lastRevenue;
        $orderCountChange = $currentOrderCount - $lastOrderCount;

        // Tính toán doanh thu và số lượng đơn hàng theo bộ lọc (nếu có)
        if ($startDate && $endDate) {
            // Nếu có bộ lọc, sử dụng whereBetween() để lấy dữ liệu trong khoảng thời gian đã chọn
            $filteredRevenue = $query->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $filteredOrderCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();
        } else {
            // Nếu không có bộ lọc thì mặc định là 0
            $filteredRevenue = 0;
            $filteredOrderCount = 0;
        }

        // Dữ liệu trả về view
        $data = [
            'current_revenue' => $currentRevenue, // Doanh thu tháng này
            'current_order_count' => $currentOrderCount, // Số lượng đơn hàng tháng này
            'last_revenue' => $lastRevenue, // Doanh thu tháng trước
            'last_order_count' => $lastOrderCount, // Số lượng đơn hàng tháng trước
            'change_revenue' => $changeRevenue, // Sự thay đổi doanh thu
            'order_count_change' => $orderCountChange, // Sự thay đổi số lượng đơn hàng
            'filtered_revenue' => $filteredRevenue, // Doanh thu theo bộ lọc
            'filtered_order_count' => $filteredOrderCount, // Số lượng đơn theo bộ lọc
        ];

        // Kiểm tra AJAX để trả về phần view cụ thể
        if ($request->ajax()) {
            return view('thongke.orders', compact('data'))->render();
        }

        // Trả về view đầy đủ nếu không phải AJAX
        return view('thongke.orders', compact('data'));
    }
    public function topproduct(Request $request)
    {
        // Get the start and end dates from the filter, or use default values
        $startDate = $request->input('start_date', now()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        // Retrieve the top products based on the date range
        $topProducts = Order_detail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('COUNT(order_id) as sales_count'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $product ? $product->name : 'Unknown Product',
                    'image' => $product ? $product->avatar : null,
                    'total_quantity' => $item->total_quantity,
                    'sales_count' => $item->sales_count,
                    'total_revenue' => $item->total_revenue,
                ];
            })
            ->toArray();

        // Check if there are no products data
        if (empty($topProducts)) {
            $topProducts = null; // Or any message indicating no data available
        }

        // If this is an AJAX request, return the rendered view for the top products
        if ($request->ajax()) {
            return view('thongke.topproduct', compact('topProducts'))->render();
        }

        // Return the full view
        return view('thongke.topproduct', compact('topProducts'));
    }

    public function tonkho(Request $request)
    {
        // Time 3 months ago
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        // Total stock across the system (independent of filters)
        $totalStock = Product::whereRaw('quantity + sell_quantity > 0') // Check stock
            ->where('created_at', '<', $threeMonthsAgo) // Check creation date
            ->whereRaw('quantity >= (quantity + sell_quantity) * 0.5') // 50% stock condition
            ->get(); // Retrieve all products that satisfy the condition

        // Products that are nearly sold out (created within the last 3 months and stock < 50%)
        $nearlySoldOut = Product::whereRaw('quantity + sell_quantity > 0') // Check stock
            ->where('created_at', '>=', $threeMonthsAgo) // Created within the last 3 months
            ->whereRaw('quantity < (quantity + sell_quantity) * 0.5') // Stock less than 50%
            ->get();

        // Prepare data for the view
        $data = [
            'total_stock' => $totalStock,        // Full system stock (detailed)
            'nearly_sold_out' => $nearlySoldOut, // Nearly sold out products
        ];

        // If it's an AJAX request
        if ($request->ajax()) {
            return view('thongke.tonkho', compact('data'))->render();
        }

        // Return the view
        return view('thongke.tonkho', compact('data'));
    }

    public function voucher(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Tổng số voucher trên toàn hệ thống
        $totalVouchers = Voucher::selectRaw('SUM(quantity + used_times) as total')->value('total') ?? 0;

        // Tổng số voucher đã sử dụng
        $usedVouchers = Voucher::sum('used_times');

        // Tổng số voucher còn lại
        $remainingVouchers = Voucher::sum('quantity');

        // Tổng số tiền đã giảm giá
        $totalDiscounted = Voucher::selectRaw('SUM(used_times * discount_value) as total_discount')
            ->value('total_discount') ?? 0;

        // Tính voucher được dùng nhiều nhất trên hệ thống
        $mostUsedVoucher = Voucher::orderBy('used_times', 'desc')->first();

        // Tính số ngày còn hạn sử dụng của các voucher (đang hoạt động)
        $vouchersWithDaysLeft = Voucher::select('id', 'code', 'end_day', 'used_times')
            ->where('is_active', 1)
            ->whereNotNull('end_day')
            ->get()
            ->map(function ($voucher) {
                $voucher->days_left = Carbon::now()->diffInDays(Carbon::parse($voucher->end_day), false);
                return $voucher;
            });

        // Dữ liệu theo bộ lọc (chỉ áp dụng khi có startDate và endDate)
        $filteredVouchers = collect();
        $filteredUsedCount = 0;
        $filteredDiscountedTotal = 0;
        $mostUsedVoucherFiltered = null;

        if ($startDate && $endDate) {
            // Lọc các voucher được sử dụng trong khoảng thời gian
            $filteredVouchers = Voucher_usage::whereBetween('created_at', [$startDate, $endDate])
                ->with('voucher')
                ->get();

            // Tổng số voucher được sử dụng trong khoảng thời gian
            $filteredUsedCount = $filteredVouchers->count();

            // Tổng số tiền giảm giá trong khoảng thời gian
            $filteredDiscountedTotal = $filteredVouchers->sum('discount_value');

            // Voucher được sử dụng nhiều nhất trong khoảng thời gian
            $mostUsedVoucherFilteredId = Voucher_usage::whereBetween('created_at', [$startDate, $endDate])
                ->select('voucher_id', DB::raw('COUNT(*) as usage_count'))
                ->groupBy('voucher_id')
                ->orderByDesc('usage_count')
                ->value('voucher_id');

            $mostUsedVoucherFiltered = $mostUsedVoucherFilteredId
                ? Voucher::find($mostUsedVoucherFilteredId)
                : null;
        }

        // Chuẩn bị dữ liệu để truyền vào view
        $data = [
            'total_vouchers' => $totalVouchers,
            'used_vouchers' => $usedVouchers,
            'remaining_vouchers' => $remainingVouchers,
            'total_discounted' => $totalDiscounted,
            'most_used_voucher' => $mostUsedVoucher,
            'vouchers_with_days_left' => $vouchersWithDaysLeft,
            'filtered_vouchers' => $filteredVouchers,
            'filtered_used_count' => $filteredUsedCount,
            'filtered_discounted_total' => $filteredDiscountedTotal,
            'most_used_voucher_filtered' => $mostUsedVoucherFiltered,
        ];

        // Nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.voucher', compact('data'))->render();
        }

        // Trả về view
        return view('thongke.voucher', compact('data'));
    }

    public function tiledon(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query cơ bản
        $query = Order::query();

        // Tổng số đơn hàng
        $totalOrders = $query->count();

        // Tổng số đơn hàng đã hủy
        $canceledOrders = $query->where('status', 4)->count();

        // Tỉ lệ hủy đơn
        $cancelRate = $totalOrders > 0 ? round(($canceledOrders / $totalOrders) * 100, 2) : 0;

        // Lý do hủy đơn, tìm lý do phổ biến nhất
        $cancelReasons = [
            'Tôi không muốn đặt hàng nữa',
            'Mặt hàng quá đắt',
            'Thời gian giao hàng quá lâu',
            'Đơn hàng của bạn đã bị hủy do thanh toán thất bại',
            'Khác'
        ];
        $reasonCounts = [];
        foreach ($cancelReasons as $reason) {
            $reasonCounts[$reason] = $query->where('status', 4)->where('message', $reason)->count();
        }
        $mostCommonReason = collect($reasonCounts)->sortDesc()->keys()->first();

        // Tổng số đơn hoàn thành
        $completedOrders = $query->where('status', 3)->count();

        // Tỉ lệ hoàn thành đơn
        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0;

        // Thống kê phương thức thanh toán
        $paymentMethodCounts = [
            'COD' => $query->where('payment_method', 0)->count(),
            'Online Payment' => $query->where('payment_method', 1)->count(),
        ];

        // Tổng đơn theo phương thức thanh toán
        $paymentTotals = array_sum($paymentMethodCounts);
        $paymentRates = [];
        foreach ($paymentMethodCounts as $method => $count) {
            $paymentRates[$method] = $paymentTotals > 0 ? round(($count / $paymentTotals) * 100, 2) : 0;
        }

        // Chuẩn bị dữ liệu để đổ vào view
        $data = [
            'total_orders' => $totalOrders,
            'canceled_orders' => $canceledOrders,
            'cancel_rate' => $cancelRate,
            'most_common_reason' => $mostCommonReason,
            'completed_orders' => $completedOrders,
            'completion_rate' => $completionRate,
            'payment_rates' => $paymentRates,
            'reason_counts' => $reasonCounts,
        ];

        // Nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.tiledon', compact('data'))->render();
        }

        // Trả về view
        return view('thongke.tiledon', compact('data'));
    }

    public function khachhang()
    {
        //thong ke cac user da tung mua hang, user mua nhieu, user chua tung mua
        return view('thongke.khachhang');
    }
}
