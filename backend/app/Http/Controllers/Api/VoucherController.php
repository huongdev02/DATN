<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return response()->json($vouchers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:vouchers',
            'type' => 'required|integer',
            'discount_value' => 'required|numeric',
            'description' => 'nullable|string',
            'discount_min' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'min_order_count' => 'required|integer|min:1',
            'max_order_count' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date|after:start_day',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $voucher = Voucher::create($request->all());
        return response()->json(['message' => 'Voucher created successfully!', 'voucher' => $voucher], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        return response()->json($voucher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'string|max:10|unique:vouchers,code,' . $voucher->id,
            'type' => 'integer',
            'discount_value' => 'numeric',
            'description' => 'nullable|string',
            'discount_min' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'min_order_count' => 'integer|min:1',
            'max_order_count' => 'integer|min:1',
            'quantity' => 'integer|min:1',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date|after:start_day',
            'status' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $voucher->update($request->all());
        return response()->json(['message' => 'Voucher updated successfully!', 'voucher' => $voucher]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted successfully!']);
    }
}
