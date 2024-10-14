<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::paginate(5);
        return view('promotions.index', compact('promotions'));
    }
    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id', 
            'start_day' => 'required|date',
            'end_day' => 'required|date',
            'price_discount' => 'required|numeric',
        ]);

        Promotion::create($request->all());

        return redirect()->route('promotions.index')
                         ->with('success', 'Promotion created successfully.');
    }
    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'product_id' => 'required',
            'start_day' => 'required|date',
            'end_day' => 'required|date',
            'price_discount' => 'required|numeric',
        ]);

        $promotion->update($request->all());

        return redirect()->route('promotions.index')
                         ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')
                         ->with('success', 'Promotion deleted successfully.');
    }
}
