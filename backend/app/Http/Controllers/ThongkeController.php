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
        $startDate = $request->input('start_date', now()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());
    
        $query = User::where('role', 0);
        $count = $query->whereBetween('created_at', [$startDate, $endDate])->count();
    
        $previousStartDate = Carbon::parse($startDate)->subDays(Carbon::parse($endDate)->diffInDays($startDate) + 1);
        $previousEndDate = Carbon::parse($startDate)->subDay();
        $lastCount = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
    
        $change = $lastCount > 0
            ? (($count - $lastCount) / $lastCount) * 100
            : 0;
    
        $data = [
            'count' => $count,
            'change' => $change,
        ];
    
        // Kiểm tra nếu request là AJAX
        if ($request->ajax()) {
            return view('thongke.account', compact('count', 'change'))->render();
        }
    
        return view('thongke.account', compact('count', 'change'));
    }
    

public function orders(Request $request)
{
    $startDate = $request->input('start_date', now()->startOfDay());
    $endDate = $request->input('end_date', now()->endOfDay());

    $query = Order::where('status', 3);

    $revenue = $query->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
    $orderCount = $query->whereBetween('created_at', [$startDate, $endDate])->count();

    $previousStartDate = Carbon::parse($startDate)->subDays(Carbon::parse($endDate)->diffInDays($startDate) + 1);
    $previousEndDate = Carbon::parse($startDate)->subDay();

    $lastRevenue = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->sum('total_amount');
    $lastOrderCount = $query->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

    $changeRevenue = $revenue - $lastRevenue;
    $orderCountChange = ($lastOrderCount > 0)
        ? (($orderCount - $lastOrderCount) / $lastOrderCount) * 100
        : 0;

    $data = [
        'total_amount' => $revenue,
        'order_count' => $orderCount,
        'change' => $changeRevenue,
        'order_count_change' => $orderCountChange,
    ];

    if ($request->ajax()) {
        return view('thongke.orders', compact('data'))->render();
    }

    return view('thongke.orders', compact('data'));
}

public function topproduct(Request $request)
{
    $startDate = $request->input('start_date', now()->startOfDay());
    $endDate = $request->input('end_date', now()->endOfDay());

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
        ->take(3)
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
        })->toArray();

    if ($request->ajax()) {
        return view('thongke.topproduct', compact('topProducts'))->render();
    }

    return view('thongke.topproduct', compact('topProducts'));
}

}
