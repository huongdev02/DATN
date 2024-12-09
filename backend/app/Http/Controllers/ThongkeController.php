<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Voucher;
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

    public function tonkho()
    {
        // Lấy ngày hiện tại trừ đi 3 tháng
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        // Lấy danh sách sản phẩm thỏa mãn điều kiện
        $products = Product::where('quantity', '>', 50)
            ->where('created_at', '<', $threeMonthsAgo)
            ->get();

        // Truyền dữ liệu sang view
        return view('thongke.tonkho', compact('products'));
    }

    public function khachhang()
    {

        //thong ke cac user da tung mua hang, user mua nhieu, user chua tung mua
        return view('thongke.khachhang', compact(''));
    }

    public function voucher()
    {
        //so voucher da su dung, tong so tien da giam gia, voucher duoc dung nhieu nhat
        return view('thongke.voucher', compact(''));
    }

    public function doanhthu()
    {
        //doanh thu tu thanh toan onl, off, loi nhuan, chi phi
        return view('thongke.doanhthu', compact(''));
    }
}
