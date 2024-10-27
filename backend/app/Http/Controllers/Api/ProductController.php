<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;


class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['categories:id,name', 'colors:id,name_color', 'sizes:id,size'])->get();
    
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sản phẩm.' . $e->getMessage()], 500);
        }
    }


    public function show(Product $product)
    {
        try {
            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
            }
    
            // Tải các quan hệ cần thiết
            $product->load(['categories:id,name', 'colors:id,name_color', 'sizes:id,size', 'galleries']);
    
            // Map qua galleries để lấy các thông tin cần thiết
            $product->galleries = $product->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'product_id' => $gallery->product_id,
                    'image_path' => $gallery->image_path,
                    'image_url' => $gallery->image_url,
                    'created_at' => $gallery->created_at,
                    'updated_at' => $gallery->updated_at,
                ];
            });
    
            // Tạo phản hồi với avatar_url
            $response = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                // Giả sử avatar_url được lưu trữ trong thư mục storage
                'avatar_url' => $product->avatar ? asset('storage/avatars/' . basename($product->avatar)) : null,
                'categories' => $product->categories,
                'colors' => $product->colors,
                'sizes' => $product->sizes,
                'galleries' => $product->galleries,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin sản phẩm: ' . $e->getMessage()], 500);
        }
    }
    
    
    
}
