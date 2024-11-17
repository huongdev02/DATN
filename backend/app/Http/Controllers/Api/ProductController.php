<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;

class ProductController extends Controller
{
    public function index()
    {
        try {
            // Lấy tất cả sản phẩm với thông tin liên quan
            $products = Product::with(['categories:id,name', 'colors:id,name_color', 'sizes:id,size'])->get();
            
            // Lấy tất cả màu sắc và kích thước từ bảng colors và sizes
            $allColors = Color::all(); // Tất cả các màu sắc
            $allSizes = Size::all();   // Tất cả các kích thước
    
            // Chuyển đổi dữ liệu sản phẩm
            $products = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'avatar_url' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null,
                    'categories' => $product->categories,
                    'price' => $product->price,
                    'avatar' => $product->avatar,
                    'quantity' => $product->quantity,
                    'sell_quantity' => $product->sell_quantity,
                    'view' => $product->view,
                    'colors' => $product->colors, // Màu sắc liên quan đến sản phẩm
                    'sizes' => $product->sizes,   // Kích thước liên quan đến sản phẩm
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });
    
            $response = [
                'products' => $products,   // Danh sách sản phẩm
                'all_colors' => $allColors, // Tất cả các màu sắc
                'all_sizes' => $allSizes,   // Tất cả các kích thước
            ];
    
            return response()->json($response);
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
                'avatar' => $product->avatar ? asset('storage/ProductAvatars/' . basename($product->avatar)) : null, // Cập nhật đường dẫn avatar ở đây
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
            // Lấy danh mục theo ID và tất cả sản phẩm kèm theo màu sắc và kích thước
            $category = Category::with([
                'products.colors:id,name_color', 
                'products.sizes:id,size',
                'products.galleries'
            ])->findOrFail($categoryId);
    
            // Lấy tất cả màu sắc và kích thước từ bảng colors và sizes
            $allColors = Color::all(); // Lấy tất cả các bản ghi từ bảng colors
            $allSizes = Size::all();   // Lấy tất cả các bản ghi từ bảng sizes
    
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
                    'colors' => $product->colors, // Màu sắc liên quan đến sản phẩm
                    'sizes' => $product->sizes,   // Kích thước liên quan đến sản phẩm
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
                'all_colors' => $allColors, // Tất cả các màu sắc
                'all_sizes' => $allSizes,   // Tất cả các kích thước
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy sản phẩm: ' . $e->getMessage()], 500);
        }
    }
    
    
    

}
