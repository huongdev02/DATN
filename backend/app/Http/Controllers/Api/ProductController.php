<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('categories:id,name')->get();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sản phẩm.' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:products,name',
                'avatar' => 'required|image',
                'import_price' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'display' => 'required|boolean',
                'status' => 'required|integer|min:0|max:3',
                'images.*' => 'image'
            ]);

            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $product = Product::create([
                'name' => $request->name,
                'avatar' => $avatarPath,
                'import_price' => $request->import_price,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'display' => $request->display,
                'status' => $request->status,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('galleries', 'public');
                    Gallery::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            return response()->json(['message' => 'Sản phẩm được thêm thành công.', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    public function show(Product $product)
    {
        try {
            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
            }
    
            return response()->json($product->load('categories', 'galleries'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin sản phẩm: ' . $e->getMessage()], 500);
        }
    }
    

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:products,name,' . $product->id,
                'avatar' => 'image',
                'import_price' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'display' => 'required|boolean',
                'status' => 'required|integer|min:0|max:3',
                'images.*' => 'image'
            ]);

            if ($request->hasFile('avatar')) {
                Storage::disk('public')->delete($product->avatar);
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $product->avatar = $avatarPath;
            }

            $product->update([
                'name' => $request->name,
                'import_price' => $request->import_price,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'display' => $request->display,
                'status' => $request->status,
            ]);

            if ($request->hasFile('images')) {
                foreach ($product->galleries as $gallery) {
                    Storage::disk('public')->delete($gallery->image_path);
                    $gallery->delete();
                }

                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('galleries', 'public');
                    Gallery::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            return response()->json(['message' => 'Sản phẩm được cập nhật thành công.', 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Xóa avatar
            Storage::disk('public')->delete($product->avatar);

            // Xóa các ảnh trong gallery
            foreach ($product->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }

            // Xóa sản phẩm
            $product->delete();

            return response()->json(['message' => 'Sản phẩm đã được xóa thành công.'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage()], 500);
        }
    }
}
