<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class UservoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('user.voucher', compact('vouchers'));
    }

    }
