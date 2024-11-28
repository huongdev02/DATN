<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Product_detail;
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
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // Query người dùng mới trong khoảng thời gian đã chọn
        $query = User::where('role', 0);

        $currentCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();

        // Lấy ngày bắt đầu và kết thúc của tháng trước
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();

        // Query người dùng mới trong tháng trước
        $lastCount = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Tính sự thay đổi giữa tháng hiện tại và tháng trước
        $change = $lastCount > 0
            ? (($currentCount - $lastCount) / $lastCount) * 100
            : 0;

        // Chuẩn bị dữ liệu để đổ vào view
        $data = [
            'count' => $currentCount,
            'change' => $change,
            'last_count' => $lastCount,
        ];

     
        if (empty($data)) {
            $data = null; // Or any message indicating no data available
        }
        
        if ($request->ajax()) {
            return view('thongke.account', compact('data'))->render();
        }

        return view('thongke.account', compact('data'));
    }

    public function orders(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ form lọc (nếu có)
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // Query thống kê cho đơn hàng trong khoảng thời gian đã chọn
        $query = Order::where('status', 3);

        $currentRevenue = $query->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $currentOrderCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();



        // Lấy ngày bắt đầu và kết thúc của tháng trước
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();

        // Query thống kê cho tháng trước
        $lastRevenue = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->sum('total_amount');
        $lastOrderCount = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Tính sự thay đổi giữa tháng hiện tại và tháng trước
        $changeRevenue = $currentRevenue - $lastRevenue;
        $orderCountChange = ($lastOrderCount > 0)
            ? (($currentOrderCount - $lastOrderCount) / $lastOrderCount) * 100
            : 0;

        $data = [
            'total_amount' => $currentRevenue,
            'order_count' => $currentOrderCount,
            'change' => $changeRevenue,
            'order_count_change' => $orderCountChange,
            'last_total_amount' => $lastRevenue,
            'last_order_count' => $lastOrderCount,
        ];
        
        if (empty($data)) {
            $data = null; // Or any message indicating no data available
        }

        if ($request->ajax()) {
            return view('thongke.orders', compact('data'))->render();
        }

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
    
}
