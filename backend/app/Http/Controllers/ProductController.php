<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['galleries', 'categories', 'colors'])->latest()->paginate(5);

        $colorMap = [
            'Đỏ' => '#FF0000',
            'Đen' => '#000000',
            'Xanh dương' => '#0000FF',
            'Xanh lá' => '#00FF00',
            'Vàng' => '#FFFF00',
            'Cam' => '#FFA500',
            'Tím' => '#800080',
        ];

        return view('products.index', compact('products', 'colorMap'));
    }


    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        return view('products.createproduct', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image',
            'import_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'display' => 'required|boolean',
            'status' => 'required|in:0,1,2,3',
            'sizes' => 'array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'array',
            'colors.*' => 'exists:colors,id',
            'image_path' => 'required|array',
            'image_path.*' => 'nullable|image',
        ]);

        try {
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }


            $productData = $request->all();
            $productData['avatar'] = $avatarPath;


            $product = Product::create($productData);

            if ($request->has('sizes')) {
                $product->sizes()->attach($request->sizes);
            }

            if ($request->has('colors')) {
                $product->colors()->attach($request->colors);
            }

            if ($request->hasFile('image_path')) {
                foreach ($request->file('image_path') as $image) {
                    $imagePath = $image->store('galleries', 'public');
                    Gallery::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }


            return redirect()->route('products.index')->with('success', 'Thêm mới sản phẩm thành công');
        } catch (Throwable $e) {
            return back()->with('error', 'Thất bại lôi: ' . $e->getMessage());
        }
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        $product->load('galleries', 'sizes', 'colors');

        return view('products.editproduct', compact('product', 'categories', 'sizes', 'colors'));
    }


    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'import_price' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'array|nullable',
                'colors' => 'array|nullable',
                'display' => 'boolean',
                'status' => 'required|in:0,1,2,3',
                'avatar' => 'nullable|image',
                'images.*' => 'nullable|image',
                'delete_gallery' => 'array|nullable',
            ]);

            // Xử lý ảnh đại diện
            if ($request->hasFile('avatar')) {
                // Xóa ảnh cũ nếu có
                if ($product->avatar) {
                    Storage::disk('public')->delete($product->avatar);
                }
                // Lưu ảnh mới
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $product->update(['avatar' => $avatarPath]);
            }

            // Cập nhật các thông tin sản phẩm khác
            $product->update($request->except(['avatar', 'images', 'delete_gallery']));

            // Đồng bộ kích thước và màu sắc
            $product->sizes()->sync($request->sizes);
            $product->colors()->sync($request->colors);

            // Xử lý hình ảnh trong thư viện
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('images', 'public');
                    $product->galleries()->create(['image_path' => $imagePath]);
                }
            }

            // Xóa các hình ảnh cũ được chọn
            if ($request->delete_gallery) {
                foreach ($request->delete_gallery as $id) {
                    $gallery = $product->galleries()->find($id);
                    if ($gallery) {
                        Storage::disk('public')->delete($gallery->image_path);
                        $gallery->delete();
                    }
                }
            }

            return back()->with('success', 'cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Thất bại lỗi: ' . $e->getMessage()]);
        }
    }




    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->avatar);
        foreach ($product->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
            $gallery->delete();
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
