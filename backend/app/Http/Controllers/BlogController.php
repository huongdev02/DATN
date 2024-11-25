<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Chỉ cho phép các định dạng ảnh
    ]);

    // Xử lý file upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('blog_images', 'public'); // Lưu ảnh trong thư mục storage/app/public/blog_images
    }

    // Tạo bản ghi
    Blog::create([
        'category_id' => $request->category_id,
        'title' => $request->title,
        'description' => $request->description,
        'content' => $request->content,
        'image' => $imagePath ?? null,
        'is_active' => $request->input('is_active', 1),
    ]);

    return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
}

}
