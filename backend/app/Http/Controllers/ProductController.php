<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['galleries', 'categories', 'colors'])->get();
    
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
            'sizes' => 'array', // Mảng chứa các kích thước
            'sizes.*' => 'exists:sizes,id', // Các id kích thước phải tồn tại trong bảng sizes
            'images.*' => 'nullable|image',
        ]);

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

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('galleries', 'public');
                Gallery::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        return view('products.editproduct', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
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

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
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
