<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Hiển thị danh sách promotion
    public function index()
    {
        $promotions = Promotion::all();
        return view('promotion.index', compact('promotions'));
    }

    // Hiển thị form tạo mới promotion
    public function create()
    {
        $products = Product::all();
        return view('promotion.create', compact('products'));
    }

    // Xử lý lưu promotion mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_day' => 'required|date',
            'end_day' => 'required|date',
            'price_discount' => 'required|numeric',
        ]);

        Promotion::create($validated);
        return redirect()->route('promotion.index')->with('success', 'Promotion created successfully');
    }

    // Hiển thị form chỉnh sửa promotion
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $products = Product::all();
        return view('promotion.edit', compact('promotion', 'products'));    
    }

    // Xử lý cập nhật promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_day' => 'required|date',
            'end_day' => 'required|date',
            'price_discount' => 'required|numeric',
        ]);

        $promotion->update($validated);
        return redirect()->route('promotion.index')->with('success', 'Promotion updated successfully');
    }

    // Xóa promotion
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('promotion.index')->with('success', 'Promotion deleted successfully');
    }
}
