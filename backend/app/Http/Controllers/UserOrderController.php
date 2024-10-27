<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['product', 'user']); 
    
        if ($request->has('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }
    
        $orders = $query->latest()->paginate(5);
    
        return view('user.order', compact('orders'));
    }
}
