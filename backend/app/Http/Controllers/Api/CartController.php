<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //
    public function index()
    {
        try {
            // Lấy giỏ hàng của người dùng hiện tại, bao gồm các sản phẩm trong giỏ
            $cart = Cart::where('user_id', Auth::id())
                         ->with(['items.product']) // Đảm bảo có quan hệ giữa cart_items và products
                         ->first();
    
            // Kiểm tra xem giỏ hàng có tồn tại không
            if (!$cart) {
                return response()->json(['message' => 'Giỏ hàng rỗng.'], 200);
            }
    
            // Dữ liệu trả về
            return response()->json([
                'cart' => $cart,
                'message' => 'Lấy giỏ hàng thành công.'
            ], 200); // Trả về mã 200 OK
    
        } catch (\Exception $e) {
            Log::error('Failed to fetch cart items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch cart items' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }
    

    public function store(Request $request)
    {
        try {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!Auth::check()) {
                return response()->json(['message' => 'Người dùng chưa đăng nhập.'], 401);
            }
            
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);
    
            // Tìm hoặc tạo giỏ hàng cho người dùng hiện tại
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
    
            // Lấy thông tin sản phẩm từ cơ sở dữ liệu
            $product = Product::findOrFail($request->product_id);
    
            // Kiểm tra xem số lượng thêm vào giỏ hàng có vượt quá số lượng trong bảng products không
            if ($request->quantity > $product->quantity) {
                return response()->json(['message' => 'Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.'], 400); // Trả về mã 400 Bad Request
            }
    
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $cartItem = CartItem::where('cart_id', $cart->id)
                                 ->where('product_id', $product->id)
                                 ->first();
    
            if ($cartItem) {
                // Cập nhật số lượng và tổng giá trị
                if (($cartItem->quantity + $request->quantity) > $product->quantity) {
                    return response()->json(['message' => 'Số lượng tổng cộng sau khi thêm vượt quá số lượng có sẵn trong kho.'], 400); // Trả về mã 400 Bad Request
                }
                $cartItem->quantity += $request->quantity; // Cộng thêm số lượng
                $cartItem->total = $cartItem->quantity * $product->price; // Tính lại tổng
                $cartItem->save();
            } else {
                // Thêm sản phẩm mới vào giỏ hàng
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'total' => $request->quantity * $product->price, // Tính tổng ngay khi thêm
                ]);
            }
    
            // Dữ liệu trả về
            $responseData = [
                'id' => $cartItem->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'total' => $cartItem->total,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng.'
            ];
    
            return response()->json($responseData, 201); // Trả về mã 201 Created
            
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông điệp lỗi
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }
    
    

    public function show($itemId)
    {
        try {
            $cartItem = CartItem::with(['product'])->findOrFail($itemId);
    
            $responseData = [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total' => $cartItem->total,
                'message' => 'Thông tin sản phẩm trong giỏ hàng.'
            ];
    
            return response()->json($responseData, 200); // Trả về mã 200 OK
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }
    
    public function update(Request $request, $itemId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
    
            $cartItem = CartItem::findOrFail($itemId);
            $product = Product::findOrFail($cartItem->product_id); // Lấy sản phẩm để tính giá
    
            $cartItem->quantity = $request->quantity;
            $cartItem->total = $cartItem->quantity * $product->price; // Cập nhật total
            $cartItem->save();
    
            $responseData = [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $product->name,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'total' => $cartItem->total,
                'message' => 'Giỏ hàng đã được cập nhật.'
            ];
    
            return response()->json($responseData, 200); // Trả về mã 200 OK
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
        }
    }
    
    public function destroy($itemId)
{
    try {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'], 200); // Trả về mã 200 OK
    } catch (\Exception $e) {
        return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500); // Trả về mã 500 Internal Server Error
    }
}

}
