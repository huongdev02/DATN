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
        try {
            $request->validate([
                'code' => 'required|string|max:10|unique:vouchers,code',
                'type' => 'required|integer',
                'discount_value' => 'required|numeric',
                'discount_min' => 'required|numeric',
                'max_discount' => 'required|numeric',
                'min_order_count' => 'required|integer',
                'max_order_count' => 'required|integer',
                'quantity' => 'required|integer',
                'start_day' => 'nullable|date',
                'end_day' => 'nullable|date',
                'status' => 'required|integer',
            ]);

            $voucher = Voucher::create($request->all());

            return response()->json(['message' => 'Voucher được thêm thành công.', 'voucher' => $voucher], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm voucher: ' . $e->getMessage()], 500);
        }
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
        try {
            $request->validate([
                'code' => 'required|string|max:10|unique:vouchers,code,' . $voucher->id,
                'type' => 'required|integer',
                'discount_value' => 'required|numeric',
                'discount_min' => 'required|numeric',
                'max_discount' => 'required|numeric',
                'min_order_count' => 'required|integer',
                'max_order_count' => 'required|integer',
                'quantity' => 'required|integer',
                'start_day' => 'nullable|date',
                'end_day' => 'nullable|date',
                'status' => 'required|integer',
            ]);

            $voucher->update($request->all());

            return response()->json(['message' => 'Voucher được cập nhật thành công.', 'voucher' => $voucher], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật voucher: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher  $voucher)
    {
        try {
            $voucher->delete();
            return response()->json(['message' => 'Voucher đã được xóa thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa voucher: ' . $e->getMessage()], 500);
        }
    }
}
