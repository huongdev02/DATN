<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách categories.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            $category = Category::create([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Category được thêm thành công.', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm category: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin category: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            ]);

            $category->update([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Category được cập nhật thành công.', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật category: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return response()->json(['message' => 'Category đã được xóa thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi xóa category: ' . $e->getMessage()], 500);
        }
    }
}
