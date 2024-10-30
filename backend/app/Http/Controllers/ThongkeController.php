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
    public function account()
    {
        $homnay = User::whereDate('created_at', Carbon::today())->count();
        $tuannay = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $thangnay = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $tuantruoc = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
        $thangtruoc = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        return view('admin.dashboard', compact('homnay', 'tuannay', 'thangnay', 'tuantruoc', 'thangtruoc'));
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

    public function order()
    {
        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 0)->count(); // Đang chờ xử lý
        $processedOrders = Order::where('status', 1)->count(); // Đã xử lý
        $shippingOrders = Order::where('status', 2)->count(); // Đang vận chuyển
        $deliveredOrders = Order::where('status', 3)->count(); // Giao hàng thành công
        $canceledOrders = Order::where('status', 4)->count(); // Đơn hàng đã bị hủy
        $returnedOrders = Order::where('status', 5)->count(); // Đơn hàng đã được trả lại

        $totalAmountReceived = Order::sum('total_amount'); //tong tien cac don

        $cashPaymentOrders = Order::where('payment_method', 0)->count(); // Tiền mặt
        $bankTransferOrders = Order::where('payment_method', 1)->count(); // Chuyển khoản ngân hàng
        $cardPaymentOrders = Order::where('payment_method', 2)->count(); // Thanh toán qua thẻ ATM

        $standardShippingOrders = Order::where('ship_method', 0)->count(); // Giao hàng tiêu chuẩn
        $expressShippingOrders = Order::where('ship_method', 1)->count(); // Giao hàng hỏa tốc

        $ordersToday = Order::whereDate('created_at', now())->count(); //don hang hom nay
        $ordersThisWeek = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(); // tuan nay
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)->count(); // thang nay
        $tuantruoc = Order::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count(); // tuan trc
        $thangtruoc = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count(); // thang trc

    }
    public function test() {
        return view('account.account');
    }
    public function b() {}
}
