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
    public function index(Request $request)
    {
        // Initialize query with relations
        $query = Product::with(['galleries', 'categories', 'colors']);

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            if ($request->price_range == 'under_200k') {
                $query->where('price', '<', 200000);
            } elseif ($request->price_range == '200k_500k') {
                $query->whereBetween('price', [200000, 500000]);
            } elseif ($request->price_range == 'over_500k') {
                $query->where('price', '>', 500000);
            }
        }

        // Sort by price order
        if ($request->filled('price_order')) {
            $query->orderBy('price', $request->price_order);
        }

        // Check if there's an 'is_active' action
        if ($request->has('toggle_is_active')) {
            $product = Product::findOrFail($request->input('product_id'));
            $product->is_active = !$product->is_active; // Toggle the status
            $product->save();

            // Redirect back with success message
            return redirect()->route('products.index')->with('success', 'Trạng thái sản phẩm đã được cập nhật.');
        }

        // Get paginated results
        $products = $query->latest()->paginate(5);

        // Define color mapping
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
            'quantity' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|boolean', // Đảm bảo is_active là boolean
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
                $avatarPath = $request->file('avatar')->store('ProductAvatars', 'public');
            }

            $productData = $request->all();
            $productData['avatar'] = $avatarPath;
            $productData['status'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0; // Chuyển thành status 1 hoặc 0

            $product = Product::create($productData);

            if ($request->has('sizes')) {
                $product->sizes()->attach($request->sizes);
            }

            if ($request->has('colors')) {
                $product->colors()->attach($request->colors);
            }

            if ($request->hasFile('image_path')) {
                foreach ($request->file('image_path') as $image) {
                    $imagePath = $image->store('ProductGalleries', 'public');
                    Gallery::create(['product_id' => $product->id, 'image_path' => $imagePath]);
                    Gallery::create(['product_id' => $product->id, 'image_path' => $imagePath]);
                }
            }

            return redirect()->route('products.index')->with('success', 'Thêm mới sản phẩm thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
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
                'quantity' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'array|nullable',
                'colors' => 'array|nullable',
                'is_active' => 'boolean',
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
                $avatarPath = $request->file('avatar')->store('ProductAvatars', 'public');
                $product->update(['avatar' => $avatarPath]);
            }

            // Cập nhật các thông tin sản phẩm khác, bao gồm `is_active`
            $product->update($request->except(['avatar', 'images', 'delete_gallery']));

            // Đồng bộ kích thước và màu sắc
            $product->sizes()->sync($request->sizes);
            $product->colors()->sync($request->colors);

            // Xử lý hình ảnh trong thư viện
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('ProductImages', 'public');
                    $product->galleries()->create(['image_path' => $imagePath]);
                }
            }

            // Xóa hình ảnh được chọn
            if ($request->has('delete_gallery')) {
                $product->galleries()->whereIn('id', $request->delete_gallery)->delete();
            }

            return redirect()->route('products.index')->with('success', 'Sản phẩm được cập nhật thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại.');
        }
    }





    public function destroy(Product $product)
    {
        // Kiểm tra điều kiện: is_active phải là 0 và updated_at ít nhất 7 ngày trước
        if ($product->is_active == 0 && $product->updated_at <= now()->subDays(7)) {
            // Xóa avatar
            Storage::disk('public')->delete($product->avatar);

            // Xóa gallery
            foreach ($product->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image_path);
                $gallery->delete();
            }

            // Xóa sản phẩm
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Thao tác thành công');
        }

        // Nếu không thỏa mãn điều kiện, trả về thông báo lỗi
        return redirect()->route('products.index')->with('error', 'Product cannot be deleted. Either it is active or has not reached the 7-day threshold.');
    }
}
