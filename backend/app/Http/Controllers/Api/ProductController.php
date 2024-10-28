<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;


class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['categories:id,name', 'colors:id,name_color', 'sizes:id,size'])->get();
            
            $products = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'avatar_url' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null, // Cập nhật đường dẫn avatar ở đây
                    'categories' => $product->categories,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'sell_quantity' => $product->sell_quantity,
                    'view' => $product->view,
                    'colors' => $product->colors,
                    'sizes' => $product->sizes,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });
    
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sản phẩm. ' . $e->getMessage()], 500);
        }
    }

    public function show(Product $product)
    {
        try {
            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
            }
    
            $product->load(['categories:id,name', 'colors:id,name_color', 'sizes:id,size', 'galleries']);
    
            $product->galleries = $product->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'product_id' => $gallery->product_id,
                    'image_path' => $gallery->image_path,
                    'image_url' => asset('storage/ProductAvatars/' . basename($gallery->image_path)), // Cập nhật đường dẫn hình ảnh ở đây
                    'created_at' => $gallery->created_at,
                    'updated_at' => $gallery->updated_at,
                ];
            });
    
            $response = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'avatar_url' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null, // Cập nhật đường dẫn avatar ở đây
                'categories' => $product->categories,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'sell_quantity' => $product->sell_quantity,
                'view' => $product->view,
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
    
    public function getProductsByCategory($categoryId)
    {
        try {
            // Lấy danh mục theo ID
            $category = Category::with('products.colors:id,name_color', 'products.sizes:id,size', 'products.galleries')
                ->findOrFail($categoryId);
    
            // Chuyển đổi dữ liệu sản phẩm
            $products = $category->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'avatar_url' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'sell_quantity' => $product->sell_quantity,
                    'view' => $product->view,
                    'colors' => $product->colors,
                    'sizes' => $product->sizes,
                    'galleries' => $product->galleries->map(function ($gallery) {
                        return [
                            'id' => $gallery->id,
                            'image_url' => asset('storage/ProductAvatars/' . basename($gallery->image_path)),
                        ];
                    }),
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });
    
            $response = [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'products' => $products,
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy sản phẩm: ' . $e->getMessage()], 500);
        }
    }
    

}
