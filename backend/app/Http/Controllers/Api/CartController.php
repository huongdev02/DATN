<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product_detail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //
    public function index()
    {
        try {
            $cartItems = Cart::with(['user', 'productDetails'])->get();
            if (!empty($cartItems)) {
                foreach ($cartItems as $cartKey => $cartItem) {
                    if (!empty($cartItem->productDetails)) {
                        foreach ($cartItem->productDetails as $key => $productDetail) {
                            $product = Product_detail::with(['product', 'color', 'size'])->find($productDetail['id']);
                            $cartItems[$cartKey]->productDetails[$key]['name'] = $product->product['name'];
                            $cartItems[$cartKey]->productDetails[$key]['color'] = $product->color['name_color'];
                            $cartItems[$cartKey]->productDetails[$key]['size'] = $product->size['size'];
                            $cartItems[$cartKey]->productDetails[$key]['price'] = $product->product['price'];
                        }
                    }
                }
            }

            return response()->json($cartItems);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage() . $e->getLine()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'product_detail_id' => 'required|exists:product_details,id',
                'user_id' => 'required|exists:users,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = $request->except('user_id');
            $data = $request->only('user_id');

            $isCart = Cart::where('user_id', $data['user_id'])->first();

            if (isset($isCart)) {
                $existingItem = $isCart->productDetails()->where('product_detail_id', $cartItem['product_detail_id'])->first();

                if ($existingItem) {
                    $existingQuantity = $existingItem->pivot->quantity;
                    $newQuantity = $existingQuantity + $cartItem['quantity'];
                    $isCart->productDetails()->updateExistingPivot($cartItem['product_detail_id'], ['quantity' => $newQuantity]);
                } else {
                    $isCart->productDetails()->attach($cartItem['product_detail_id'], ['quantity' => $cartItem['quantity']]);
                }
                return response()->json($isCart, 201);
            }

            $cart = Cart::create($data);
            $cart->productDetails()->attach($cartItem['product_detail_id'], ['quantity' => $cartItem['quantity']]);
            return response()->json($cart, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to add item to cart: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage() . ' on ' . $e->getLine()
            ], 500);
        }
    }

    public function show($userId)
    {
        try {
            // Tìm giỏ hàng của người dùng theo user_id
            $cart = Cart::with('productDetails')->where('user_id', $userId)->firstOrFail();

            // Kiểm tra và lấy thông tin chi tiết sản phẩm
            if (!empty($cart)) {
                foreach ($cart->productDetails as $key => $productDetail) {
                    $product = Product_detail::with(['product', 'color', 'size'])->find($productDetail['id']);

                    // Nếu sản phẩm không tồn tại, có thể xử lý hoặc báo lỗi
                    if ($product) {
                        $cart->productDetails[$key]['name'] = $product->product['name'];
                        $cart->productDetails[$key]['color'] = $product->color['name_color'];
                        $cart->productDetails[$key]['size'] = $product->size['size'];
                        $cart->productDetails[$key]['price'] = $product->product['price'];
                    } else {
                        // Nếu sản phẩm không tồn tại, có thể gán giá trị mặc định hoặc bỏ qua
                        $cart->productDetails[$key]['name'] = null; // Hoặc xử lý khác
                    }
                }
            }

            return response()->json([
                'message' => 'Lấy giỏ hàng thành công.',
                'data' => $cart
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Giỏ hàng không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy giỏ hàng.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $validateData = $request->validate([
                'product_detail_id' => 'required|exists:product_details,id',
                'quantity' => 'required|integer|min:1',
            ]);
            $data = $request->all();
            $cart->productDetails()->updateExistingPivot($data['product_detail_id'], ['quantity' => $data['quantity']]);;
            return response()->json($cart);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update cart item' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->productDetails()->detach();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Cart item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete cart item: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyByProductId($id, $cartId)
    {
        try {
            $cart = Cart::findOrFail($cartId);
            $cart->productDetails()->detach($id);
            return $cart;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Cart item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete cart item: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyByProductIdLogin($id)
    {
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            $cart->productDetails()->detach($id);
            return $cart;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Cart item not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete cart item: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
