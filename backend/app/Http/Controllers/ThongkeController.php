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
    public function account(Request $request) {
        // Retrieve the timeframe from the request
        $timeframe = $request->input('timeframe', 'today'); // Default to 'today' if not set
        $query = User::where('role', 0);
        $count = 0;
        $lastCount = 0;
    
        switch ($timeframe) {
            case 'today':
                $count = $query->whereDate('created_at', now())->count();
                $lastCount = $query->whereDate('created_at', now()->subDay())->count();
                break;
            case 'this_week':
                $count = $query->whereBetween('created_at', [now()->startOfWeek(), now()])->count();
                $lastCount = $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
                break;
            case 'this_month':
                $count = $query->whereBetween('created_at', [now()->startOfMonth(), now()])->count();
                $lastCount = $query->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
                break;
            case 'this_quarter':
                $count = $query->whereBetween('created_at', [now()->firstOfQuarter(), now()])->count();
                $lastCount = $query->whereBetween('created_at', [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()->lastOfQuarter()])->count();
                break;
        }
    
        $change = $count - $lastCount;
    
        return [
            'count' => $count,
            'change' => $change
        ];
    }
    
    public function order(Request $request) {
        $query = Order::where('status', 3); // Only completed orders
        $revenue = 0;
        $orderCount = 0;
        $lastRevenue = 0;
        $lastOrderCount = 0; // To store the previous order count
    
        // Get the timeframe from the request, default to 'today' if not provided
        $timeframe = $request->input('timeframe', 'today');
    
        switch ($timeframe) {
            case 'today':
                $revenue = $query->whereDate('created_at', now())->sum('total_amount');
                $orderCount = $query->whereDate('created_at', now())->count();
                $lastRevenue = $query->whereDate('created_at', now()->subDay())->sum('total_amount');
                $lastOrderCount = $query->whereDate('created_at', now()->subDay())->count(); // Previous day count
                break;
            case 'this_week':
                $revenue = $query->whereBetween('created_at', [now()->startOfWeek(), now()])->sum('total_amount');
                $orderCount = $query->whereBetween('created_at', [now()->startOfWeek(), now()])->count();
                $lastRevenue = $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->sum('total_amount');
                $lastOrderCount = $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count(); // Previous week count
                break;
            case 'this_month':
                $revenue = $query->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('total_amount');
                $orderCount = $query->whereBetween('created_at', [now()->startOfMonth(), now()])->count();
                $lastRevenue = $query->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('total_amount');
                $lastOrderCount = $query->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count(); // Previous month count
                break;
            case 'this_quarter':
                $revenue = $query->whereBetween('created_at', [now()->firstOfQuarter(), now()])->sum('total_amount');
                $orderCount = $query->whereBetween('created_at', [now()->firstOfQuarter(), now()])->count();
                $lastRevenue = $query->whereBetween('created_at', [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()->lastOfQuarter()])->sum('total_amount');
                $lastOrderCount = $query->whereBetween('created_at', [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()->lastOfQuarter()])->count(); // Previous quarter count
                break;
        }
    
        $changeRevenue = $revenue - $lastRevenue; // Calculate revenue change
        $changeOrderCount = $orderCount - $lastOrderCount; // Calculate order count change
    
        return [
            'total_amount' => $revenue,
            'order_count' => $orderCount,
            'change' => $changeRevenue,
            'lastOrderCount' => $lastOrderCount, // Return the previous order count
            'order_count_change' => $changeOrderCount // Optional, if you want to track changes
        ];
    }
    
    public function topproduct(Request $request)
    {
        $productTimeframe = $request->input('product_timeframe', 'this_week');
    
        // Determine the start date based on the selected timeframe
        switch ($productTimeframe) {
            case 'this_week':
                $startDate = now()->startOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                break;
            case 'this_quarter':
                $startDate = now()->firstOfQuarter();
                break;
            default: // 'today'
                $startDate = now()->startOfDay();
                break;
        }
    
        // Fetch top products based on the selected timeframe from order_detail
        $topProducts = Order_detail::select('product_id', 
            DB::raw('SUM(quantity) as total_quantity'), 
            DB::raw('COUNT(order_id) as sales_count'),
            DB::raw('SUM(total) as total_revenue')
        )
        ->whereHas('order', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate); // Filter orders based on the timeframe
        })
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->take(3)
        ->get();
    
        // Prepare product details
        $topProducts = $topProducts->map(function ($item) {
            $product = Product::find($item->product_id);
            return [
                'product_id' => $item->product_id,
                'product_name' => $product ? $product->name : 'Unknown Product',
                'image' => $product ? $product->avatar : null, // Assuming there's an 'avatar' field
                'total_quantity' => $item->total_quantity,
                'sales_count' => $item->sales_count,
                'total_revenue' => $item->total_revenue, // Sum of revenue from order_details
            ];
        })->toArray(); // Chuyển đổi thành mảng
    
        return response()->json($topProducts); // Return the top products
    }
    
    
    
    
    

}
