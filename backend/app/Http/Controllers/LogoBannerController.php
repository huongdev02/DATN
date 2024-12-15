<?php

namespace App\Http\Controllers;

use App\Models\LogoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoBannerController extends Controller
{
    public function index()
    {
        $logoBanners = LogoBanner::latest('id')->paginate(10);
        return view('logo_banners.index', compact('logoBanners'));
    }

    public function create()
    {
        return view('logo_banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:8192',
            'image' => 'required|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('logo_banner', 'public'); // Lưu file vào storage/app/public/logo_banner
        }

        LogoBanner::create($data);
        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner added successfully.');
    }

    public function edit($id)
    {
        $logoBanner = LogoBanner::findOrFail($id);
        return view('logo_banners.edit', compact('logoBanner'));
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra dữ liệu gửi lên
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:8192',
            'image' => 'nullable|image', // Không bắt buộc phải có hình ảnh
            'is_active' => 'required|boolean', // Đảm bảo is_active được gửi đúng
        ]);

        $logoBanner = LogoBanner::findOrFail($id);

        // Nếu có hình ảnh mới, lưu và cập nhật
        if ($request->hasFile('image')) {
            // Xóa hình cũ nếu có
            if ($logoBanner->image) {
                Storage::delete('public/' . $logoBanner->image);
            }

            // Lưu hình ảnh mới
            $data['image'] = $request->file('image')->store('logo_banner', 'public');
        }

        // Cập nhật logoBanner
        $logoBanner->update($data);

        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner updated successfully.');
    }



    public function destroy(LogoBanner $logoBanner)
    {
        // Kiểm tra nếu trạng thái is_active của logoBanner là 1 (hoạt động)
        if ($logoBanner->is_active == 1) {
            return redirect()->route('logo_banners.index')->with('error', 'Không thể xóa Logo/Banner đang hoạt động.');
        }

        // Kiểm tra nếu không còn bản ghi nào cùng type
        $countSameType = LogoBanner::where('type', $logoBanner->type)->count();

        if ($countSameType <= 1) {
            return redirect()->route('logo_banners.index')->with('error', 'Không thể xóa vì không còn bản ghi nào cùng loại.');
        }

        // Xóa Logo/Banner
        $logoBanner->delete();

        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner deleted successfully.');
    }
}
