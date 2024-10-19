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

    public function product()
    {
        $homnay = Product_detail::whereDate('created_at', Carbon::today())
            ->sum('sell_quantity'); //hom nay
        $tuannay = Product_detail::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('sell_quantity'); //tuan nay
        $thangnay = Product_detail::whereMonth('created_at', Carbon::now()->month)
            ->sum('sell_quantity'); //thang nay
        $tuantruoc = Product_detail::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->sum('sell_quantity'); //tuan trc
        $thangtruoc = Product_detail::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('sell_quantity'); //thang trc

        $total = Product::count();

        $active = Product::where('status', 1)->count();
        $inactive = Product::where('status', 0)->count();

        $product_hien = Product::where('display', 1)->count(); // Sản phẩm hiển thị
        $product_an = Product::where('display', 0)->count(); // Sản phẩm không hiển thị

        $danhmuc = Product::selectRaw('COUNT(*) as count, category_id')
            ->groupBy('category_id')
            ->get(); //theo danh muc

        $gialoai1 = Product::where('price', '<', 100)->count(); // Sản phẩm có giá dưới 100
        $gialoai2 = Product::whereBetween('price', [100, 500])->count(); // Sản phẩm có giá từ 100 đến 500
        $gialoai3 = Product::where('price', '>', 500)->count(); // Sản phẩm có giá trên 500

        $productnew_thang = Product::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->groupBy('month')
            ->get(); //sp new theo tung thang
        $conhang = Product_detail::where('quantity', '>', 0)->count(); // Còn hàng
        $hethang = Product_detail::where('quantity', 0)->count(); // Hết hàng

        $topsell = Product_detail::orderBy('sell_quantity', 'desc')->first(); // ban chay nhat

        $tonkho = Product_detail::where('quantity', '>', 50)
            ->where('created_at', '<', Carbon::now()->subMonths(2))
            ->get(); // ton kho 2 thang va co soluong>50 

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
