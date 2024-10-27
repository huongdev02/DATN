<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //
    public function index()
    {
        try {
            $cartItems = Cart::all();
            return response()->json($cartItems);
        } catch (\Exception $e) {
            Log::error('Failed to fetch cart items:' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch cart items'], 500);
        }
    }

    public function show($id)
    {
        try {
            $cartItems = Cart::findOrFail($id);
            return response()->json($cartItems);
        } catch (\Exception $e) {
            Log::error('Failed to fetch cart items:' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch cart items'], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'user_id' => 'required|exists:users,id',
                'quantity' => 'required|integer|min:1',
                'total' => 'required|numeric',
            ]);
            $cart = Cart::create($validateData);
            return response()->json($cart, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to add item to cart: ' . $e->getMessage());
            return response()->json([
                'error' => 'failed to add item to cart'
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $validateData = $request->validate([
                'quantity' => 'required|integer|min:1',
                'total' => 'required|numeric',
            ]);
            $cart->update($validateData);
            return response()->json($cart);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Cart not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update cart item' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update cart item'
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Cart item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete cart item: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete cart item'
            ], 500);
        }
    }
}
