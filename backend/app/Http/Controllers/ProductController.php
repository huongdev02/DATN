<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('galleries')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.createproduct', compact('categories'));
    }

    public function store(Request $request)
    {
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $productData = $request->all();
        $productData['avatar'] = $avatarPath;

        $product = Product::create($productData);

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
        return view('products.editproduct', compact('product'));
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
