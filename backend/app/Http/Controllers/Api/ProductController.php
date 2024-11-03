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
            // Tải danh sách sản phẩm kèm theo categories, sizes và colors
            $products = Product::with('categories:id,name', 'sizes', 'colors')
                ->orderBy('id', 'desc')
                ->get();
            $products->each(function ($product) {
                $product->avatar = $product->avatar ? asset('storage/' . $product->avatar) : null;

                $product->galleries->each(function ($gallery) {
                    $gallery->image_path = asset('storage/' . $gallery->image_path);
                });
            });
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sản phẩm.' . $e->getMessage()], 500);
        }
    }




    public function store(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string|unique:products,name',
                'avatar' => 'required|image',
                'import_price' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'display' => 'required|boolean',
                'status' => 'required|integer|min:0|max:3',
                'images.*' => 'image',
                'sizes' => 'array',
                'colors' => 'array',
            ]);

            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Tạo sản phẩm mới
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

            // Lưu các hình ảnh vào gallery
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('galleries', 'public');
                    Gallery::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            // Gán các sizes và colors cho sản phẩm
            if ($request->sizes) {
                $product->sizes()->attach($request->sizes);
            }
            if ($request->colors) {
                $product->colors()->attach($request->colors);
            }

            return response()->json(['message' => 'Sản phẩm được thêm thành công.', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    public function show(Product $product)
    {
        try {
            // Tải thông tin sản phẩm kèm theo các mối quan hệ
            $productDetails = $product->productDetails; // Lấy tất cả product_details

            // Kiểm tra nếu sản phẩm không tồn tại
            if (!$product) {
                return response()->json(['message' => 'Sản phẩm không tồn tại.'], 404);
            }

            // Trả về thông tin sản phẩm cùng với product_details
            return response()->json([
                'product' => $product->load('categories', 'galleries', 'sizes', 'colors'),
                'product_details' => $productDetails // Thêm product_details vào response
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin sản phẩm: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, Product $product)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string|unique:products,name,' . $product->id,
                'avatar' => 'image',
                'import_price' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'display' => 'required|boolean',
                'status' => 'required|integer|min:0|max:3',
                'images.*' => 'image',
                'sizes' => 'array',
                'colors' => 'array',
            ]);

            // Cập nhật avatar nếu có
            if ($request->hasFile('avatar')) {
                Storage::disk('public')->delete($product->avatar);
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $product->avatar = $avatarPath;
            }

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $request->name,
                'import_price' => $request->import_price,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'display' => $request->display,
                'status' => $request->status,
            ]);

            // Xóa và cập nhật sizes và colors
            $product->sizes()->sync($request->sizes);
            $product->colors()->sync($request->colors);

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
