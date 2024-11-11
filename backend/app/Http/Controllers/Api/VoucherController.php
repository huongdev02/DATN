<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vouchers = Voucher::all();
            return response()->json($vouchers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách vouchers.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher  $voucher)
    {
        try {
            return response()->json($voucher);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin voucher: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher  $voucher)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher  $voucher)
    {
    }
}
