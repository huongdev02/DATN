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

    public function tiledon(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
    
        // Phần 1: Số liệu theo form (dữ liệu động theo thời gian lọc)
        $ordersQuery = Order::query();
    
        // Thêm điều kiện lọc theo khoảng thời gian nếu có
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Tính tổng đơn hàng, các đơn đã hủy, các đơn đã hoàn thành, phương thức thanh toán
        $totalOrders = $ordersQuery->count();
        $canceledOrders = $ordersQuery->where('status', 4)->count();
        $completedOrders = $ordersQuery->where('status', 3)->count();
        $onlinePaymentOrders = $ordersQuery->where('payment_method', 2)->count();
        $codPaymentOrders = $ordersQuery->where('payment_method', 1)->count();
    
        // Tính tỉ lệ hoàn thành, hủy đơn, tỉ lệ thanh toán online và COD
        $completionRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;
        $cancelRate = $totalOrders > 0 ? ($canceledOrders / $totalOrders) * 100 : 0;
        $onlinePaymentRate = $totalOrders > 0 ? ($onlinePaymentOrders / $totalOrders) * 100 : 0;
        $codPaymentRate = $totalOrders > 0 ? ($codPaymentOrders / $totalOrders) * 100 : 0;
    
        // Phần 2: Dữ liệu tháng này (cố định theo tháng hiện tại)
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
    
        $monthlyOrders = Order::whereBetween('created_at', [$monthStart, $monthEnd])->get();
    
        $totalOrdersThisMonth = $monthlyOrders->count();
        $canceledOrdersThisMonth = $monthlyOrders->where('status', 4)->count();
        $completedOrdersThisMonth = $monthlyOrders->where('status', 3)->count();
        $onlinePaymentOrdersThisMonth = $monthlyOrders->where('payment_method', 2)->count();
        $codPaymentOrdersThisMonth = $monthlyOrders->where('payment_method', 1)->count();
    
        $completionRateThisMonth = $totalOrdersThisMonth > 0 ? ($completedOrdersThisMonth / $totalOrdersThisMonth) * 100 : 0;
        $cancelRateThisMonth = $totalOrdersThisMonth > 0 ? ($canceledOrdersThisMonth / $totalOrdersThisMonth) * 100 : 0;
        $onlinePaymentRateThisMonth = $totalOrdersThisMonth > 0 ? ($onlinePaymentOrdersThisMonth / $totalOrdersThisMonth) * 100 : 0;
        $codPaymentRateThisMonth = $totalOrdersThisMonth > 0 ? ($codPaymentOrdersThisMonth / $totalOrdersThisMonth) * 100 : 0;
    
        // Phần 3: Dữ liệu toàn hệ thống (cố định không thay đổi theo form)
        $totalOrdersSystem = Order::count();
        $canceledOrdersSystem = Order::where('status', 4)->count();
        $completedOrdersSystem = Order::where('status', 3)->count();
        $onlinePaymentOrdersSystem = Order::where('payment_method', 2)->count();
        $codPaymentOrdersSystem = Order::where('payment_method', 1)->count();
    
        // Đếm các đơn hàng có trạng thái khác ngoài hoàn thành (3) và hủy (4)
        $otherStatusOrdersSystem = Order::whereNotIn('status', [3, 4])->count(); // Đơn hàng không phải hoàn thành và hủy
    
        $completionRateSystem = $totalOrdersSystem > 0 ? ($completedOrdersSystem / $totalOrdersSystem) * 100 : 0;
        $cancelRateSystem = $totalOrdersSystem > 0 ? ($canceledOrdersSystem / $totalOrdersSystem) * 100 : 0;
        $onlinePaymentRateSystem = $totalOrdersSystem > 0 ? ($onlinePaymentOrdersSystem / $totalOrdersSystem) * 100 : 0;
        $codPaymentRateSystem = $totalOrdersSystem > 0 ? ($codPaymentOrdersSystem / $totalOrdersSystem) * 100 : 0;
    
        // Lý do hủy đơn
        $cancelReasons = Order::where('status', 4)
            ->select('message', DB::raw('count(*) as count'))
            ->groupBy('message')
            ->get();
    
        // Tìm lý do hủy phổ biến nhất
        $mostCommonCancelReason = $cancelReasons->sortByDesc('count')->first();
    
        // Dữ liệu trả về view
        $data = [
            'total_orders' => $totalOrders,
            'canceled_orders' => $canceledOrders,
            'completed_orders' => $completedOrders,
            'online_payment_orders' => $onlinePaymentOrders,
            'cod_payment_orders' => $codPaymentOrders,
            'completion_rate' => $completionRate,
            'cancel_rate' => $cancelRate,
            'online_payment_rate' => $onlinePaymentRate,
            'cod_payment_rate' => $codPaymentRate,
            'total_orders_this_month' => $totalOrdersThisMonth,
            'canceled_orders_this_month' => $canceledOrdersThisMonth,
            'completed_orders_this_month' => $completedOrdersThisMonth,
            'online_payment_orders_this_month' => $onlinePaymentOrdersThisMonth,
            'cod_payment_orders_this_month' => $codPaymentOrdersThisMonth,
            'completion_rate_this_month' => $completionRateThisMonth,
            'cancel_rate_this_month' => $cancelRateThisMonth,
            'online_payment_rate_this_month' => $onlinePaymentRateThisMonth,
            'cod_payment_rate_this_month' => $codPaymentRateThisMonth,
            'total_orders_system' => $totalOrdersSystem,
            'canceled_orders_system' => $canceledOrdersSystem,
            'completed_orders_system' => $completedOrdersSystem,
            'online_payment_orders_system' => $onlinePaymentOrdersSystem,
            'cod_payment_orders_system' => $codPaymentOrdersSystem,
            'completion_rate_system' => $completionRateSystem,
            'cancel_rate_system' => $cancelRateSystem,
            'online_payment_rate_system' => $onlinePaymentRateSystem,
            'cod_payment_rate_system' => $codPaymentRateSystem,
            'other_status_orders_system' => $otherStatusOrdersSystem, // Thêm biến đếm các đơn hàng có trạng thái ngoài hoàn thành và hủy
            'cancel_reasons' => $cancelReasons,
            'most_common_cancel_reason' => $mostCommonCancelReason ? $mostCommonCancelReason->message : null,
        ];
    
        // Nếu yêu cầu AJAX, trả về dữ liệu JSON
        if ($request->ajax()) {
            return view('thongke.tiledon', compact('data'))->render();
        }
    
        // Trả về view
        return view('thongke.tiledon', compact('data'));
    }
    



    public function voucher(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Khởi tạo query cho Voucher Usages (dùng để đếm số voucher đã dùng và tổng tiền giảm giá)
        $voucherUsageQuery = Voucher_usage::join('vouchers', 'voucher_usages.voucher_id', '=', 'vouchers.id')
            ->select('voucher_usages.voucher_id', 'vouchers.code')
            ->where('vouchers.is_active', 1); // Chỉ lấy voucher đang hoạt động

        // Thêm điều kiện lọc theo khoảng thời gian nếu có
        if ($startDate && $endDate) {
            $voucherUsageQuery->whereBetween('voucher_usages.created_at', [$startDate, $endDate]);
        }

        // Tính số lượng voucher đã dùng, tổng tiền giảm giá trong khoảng thời gian lọc
        $voucherUsedCount = $voucherUsageQuery->count();
        $totalDiscountValue = $voucherUsageQuery->sum('voucher_usages.discount_value');

        // Lấy 5 voucher đã được sử dụng nhiều nhất trong khoảng thời gian lọc
        $top5Vouchers = $voucherUsageQuery
            ->selectRaw('voucher_usages.voucher_id, vouchers.code, COUNT(voucher_usages.voucher_id) as usage_count, SUM(voucher_usages.discount_value) as total_discount_value')
            ->groupBy('voucher_usages.voucher_id', 'vouchers.code') // Group by the necessary columns
            ->orderByRaw('usage_count DESC')
            ->limit(5)
            ->get();

        // Dữ liệu cứng (hoạt động độc lập với form lọc)
        $totalVouchers = Voucher::where('is_active', 1)->count();
        $totalUsedVouchers = Voucher_usage::count();
        $totalDiscountApplied = Voucher_usage::sum('discount_value');
        $validVouchers = Voucher::where('end_day', '>', Carbon::now())->get();

        // Chuẩn bị dữ liệu để đổ vào view
        $data = [
            'voucher_used_count' => $voucherUsedCount,
            'total_discount_value' => $totalDiscountValue,
            'top_5_vouchers' => $top5Vouchers,
            'total_vouchers' => $totalVouchers,
            'total_used_vouchers' => $totalUsedVouchers,
            'total_discount_applied' => $totalDiscountApplied,
            'valid_vouchers' => $validVouchers,
        ];

        // Nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.voucher', compact('data'))->render();
        }

        // Trả về view
        return view('thongke.voucher', compact('data'));
    }
    public function khachhang(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query khách hàng đã mua hàng nhiều nhất tháng này
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $topCustomersThisMonth = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$currentMonthStart, $currentMonthEnd])
            ->select('users.id', 'users.email', 'users.avatar', DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('users.id', 'users.email', 'users.avatar')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();

        // Query khách hàng đã mua hàng nhiều nhất toàn hệ thống
        $topCustomersAllTime = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.id', 'users.email', 'users.avatar', DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('users.id', 'users.email', 'users.avatar')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();

        // Query theo bộ lọc nếu có
        $filteredCustomers = collect();
        if ($startDate && $endDate) {
            $filteredCustomers = User::join('orders', 'users.id', '=', 'orders.user_id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select('users.id', 'users.email', 'users.avatar', DB::raw('COUNT(orders.id) as order_count'))
                ->groupBy('users.id', 'users.email', 'users.avatar')
                ->orderByDesc('order_count')
                ->get();
        }

        // Dữ liệu để gửi vào view
        $data = [
            'top_customers_this_month' => $topCustomersThisMonth,
            'top_customers_all_time' => $topCustomersAllTime,
            'filtered_customers' => $filteredCustomers,
        ];

        return view('thongke.khachhang', compact('data'));
    }
}
