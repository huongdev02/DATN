<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kiểm tra nếu có yêu cầu cập nhật trạng thái is_active
        if ($request->has('toggle_active')) {
            $category = Category::findOrFail($request->toggle_active);
            $category->is_active = !$category->is_active; // Đổi trạng thái
            $category->save();
            return back()->with('success', 'Trạng thái danh mục đã được cập nhật.');
        }

        // Lấy danh sách danh mục
        $categories = Category::latest('id')->paginate(5);
        return view('categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
                }
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
            'is_active' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Cập nhật thành công');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm nào không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan.');
        }

        // Nếu không có sản phẩm liên quan, thực hiện xóa
        $category->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
