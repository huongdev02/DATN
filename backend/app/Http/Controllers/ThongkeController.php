<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        $timeframe = $request->input('timeframe', 'this_week');
        $query = User::where('role', 0);
        $count = 0;
        $change = 0;
    
        switch ($timeframe) {
            case 'this_week':
                $count = $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()])->count();
                $lastCount = $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $change = $count - $lastCount;
                break;
            case 'this_month':
                $count = $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count();
                $lastCount = $query->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $change = $count - $lastCount;
                break;
            case 'last_week':
                $count = $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                break;
            case 'last_month':
                $count = $query->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                break;
        }
    
        // Trả về dữ liệu
        return [
            'count' => $count,
            'change' => $change
        ];
    }
    

    public function order(Request $request)
    {
        $timeframe = $request->input('timeframe', 'this_week'); // Mặc định là 'this_week' nếu không chọn
        
        // Chỉ tính cho các đơn hàng có status = 3 (đã hoàn thành)
        $query = Order::where('status', 3); 
        $revenue = 0;
        $change = 0;
    
        $today = now();
    
        switch ($timeframe) {
            case 'today':
                // Tính doanh thu hôm nay
                $revenue = $query->whereDate('created_at', $today)->sum('total_amount');
                // Tính doanh thu hôm qua để so sánh
                $yesterdayRevenue = $query->whereDate('created_at', $today->subDay())->sum('total_amount');
                $change = $revenue - $yesterdayRevenue;
                break;
    
            case 'this_week':
                // Tính doanh thu từ đầu tuần đến hôm nay
                $revenue = $query->whereBetween('created_at', [$today->startOfWeek(), $today])->sum('total_amount');
                // Tính doanh thu của tuần trước để so sánh
                $lastWeekRevenue = $query->whereBetween('created_at', [$today->subWeek()->startOfWeek(), $today->subWeek()->endOfWeek()])->sum('total_amount');
                $change = $revenue - $lastWeekRevenue;
                break;
    
            case 'this_month':
                // Tính doanh thu từ đầu tháng đến hôm nay
                $revenue = $query->whereMonth('created_at', $today->month)->sum('total_amount');
                // Tính doanh thu của tháng trước để so sánh
                $lastMonthRevenue = $query->whereMonth('created_at', $today->subMonth()->month)->sum('total_amount');
                $change = $revenue - $lastMonthRevenue;
                break;
    
            case 'last_week':
                // Tính doanh thu của tuần trước
                $revenue = $query->whereBetween('created_at', [$today->subWeek()->startOfWeek(), $today->subWeek()->endOfWeek()])->sum('total_amount');
                break;
    
            case 'last_month':
                // Tính doanh thu của tháng trước
                $revenue = $query->whereMonth('created_at', $today->subMonth()->month)->sum('total_amount');
                break;
    
            default:
                break;
        }
    
        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'total_amount' => $revenue,
            'order_count' => $query->count(), // Cập nhật số lượng đơn hàng đã hoàn thành
            'change' => $change // Trả về giá trị thay đổi
        ]);
    }
    
    
    
    

    public function review()
    {
        $choxuli = Review::where('status', 0)->count(); // Đang chờ xử lý
        $daduyet = Review::where('status', 1)->count(); // Đã được duyệt
        $tuchoi = Review::where('status', 2)->count(); // Bị từ chối

        $theosp = Review::select('product_id', DB::raw('count(*) as total_reviews'))
            ->groupBy('product_id')
            ->get(); //theo sp
        $toprate = Review::select('product_id', DB::raw('avg(rating) as average_rating'))
            ->groupBy('product_id')
            ->orderBy('average_rating', 'desc')
            ->first(); //rate tb cao nhat
        $minrate = Review::select('product_id', DB::raw('avg(rating) as average_rating'))
            ->groupBy('product_id')
            ->orderBy('average_rating', 'asc')
            ->first(); //rate tb thap nhat
    }

    public function voucher()
    {
        $total = Voucher::count();

        $active = Voucher::where('status', 1)->count(); // Đang hoạt động
        $inactive= Voucher::where('status', 0)->count(); // Không hoạt động

        $fixed_voucher = Voucher::where('type', 0)->count(); // Giá trị cố định
        $percentage_voucher = Voucher::where('type', 1)->count(); // Triết khấu %

        $conhieuluc = Voucher::where('start_day', '<=', now())
            ->where('end_day', '>=', now())
            ->count(); //con hieu luc
        $hethan = Voucher::where('end_day', '<', now())->count(); // het han

        $dasudung = Voucher::where('used_times', '>', 0)->count(); // da su dung

        $homnay = Voucher::whereDate('created_at', now())->count(); // hom nay
        $tuannay = Voucher::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(); // tuan nay
        $thangnay = Voucher::whereMonth('created_at', now()->month)->count(); // thang nay


    }

 
    public function test() {
        return view('account.account');
    }
    public function b() {}
}
