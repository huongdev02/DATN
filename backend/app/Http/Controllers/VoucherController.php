<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;



class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest('id')->paginate(5);
        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:vouchers',
            'type' => 'required|integer',
            'discount_value' => 'required|numeric',
            'description' => 'nullable|string',
            'discount_min' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'min_order_count' => 'required|integer',
            'max_order_count' => 'required|integer',
            'quantity' => 'required|integer',
            'used_times' => 'nullable|integer|min:0',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date',
            'status' => 'required|integer',
        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:vouchers,code,' . $id,
            'type' => 'required|integer',
            'discount_value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'discount_min' => 'required|numeric|min:0',
            'max_discount' => 'required|numeric|min:0',
            'min_order_count' => 'required|integer|min:1',
            'max_order_count' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'used_times' => 'nullable|integer|min:0',
            'start_day' => 'nullable|date',
            'end_day' => 'nullable|date',
            'status' => 'required|integer',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->only([
            'code',
            'type',
            'discount_value',
            'description',
            'discount_min',
            'max_discount',
            'min_order_count',
'max_order_count',
            'quantity',
            'used_times',
            'start_day',
            'end_day',
            'status',
        ]));

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
}
