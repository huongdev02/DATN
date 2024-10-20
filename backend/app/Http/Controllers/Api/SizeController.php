<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sizes = Size::all();
            return response()->json($sizes);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách sizes.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'size' => 'required|string|max:25|unique:sizes,size',
        ]);

        // Tạo một bản ghi size mới
        $size = Size::create([
            'size' => $request->size,
        ]);

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Size được thêm thành công.', 'size' => $size], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Xử lý lỗi xác thực
        return response()->json(['message' => 'Dữ liệu không hợp lệ.', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        // Xử lý lỗi khác
        return response()->json(['message' => 'Có lỗi xảy ra khi thêm size: ' . $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        try {
            return response()->json($size);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin size: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
{
    try {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'size' => 'required|string|max:25|unique:sizes,size,' . $size->id,
        ]);

        // Cập nhật thông tin kích thước
        $size->update([
            'size' => $request->size,
        ]);

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Size được cập nhật thành công.', 'size' => $size], 200);
    } catch (\Exception $e) {
        // Trả về phản hồi lỗi nếu có lỗi xảy ra
        return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật size: ' . $e->getMessage()], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
{
    try {
        $size->delete();
        // Trả về mã trạng thái 200 kèm theo thông báo thành công
        return response()->json(['message' => 'Size đã được xóa thành công.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Có lỗi xảy ra khi xóa size: ' . $e->getMessage()], 500);
    }
}
}
