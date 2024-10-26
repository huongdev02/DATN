<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'product', 'shipAddress')->paginate(5);
        return view('user.order', compact('orders'));
    }
}
