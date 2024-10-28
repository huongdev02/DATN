<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_method;
use Illuminate\Http\Request;

class PaymentMethod extends Controller
{
    public function index()
    {
        $paymentMethods = Payment_method::all();
        
        return response()->json($paymentMethods);
    }
}
