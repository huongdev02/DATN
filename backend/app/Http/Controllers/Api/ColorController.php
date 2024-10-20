<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $colors = Color::all();
            return response()->json($colors);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách colors.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name_color' => 'required|string|max:25|unique:colors,name_color',
            ]);

            $color = Color::create([
                'name_color' => $request->name_color,
            ]);

            return response()->json(['message' => 'Color được thêm thành công.', 'color' => $color], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra khi thêm color: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        try {
            return response()->json($color);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy thông tin color: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
{
    try {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name_color' => 'required|string|max:25|unique:colors,name_color,' . $color->id,
        ]);

        // Cập nhật thông tin màu
        $color->update([
            'name_color' => $request->name_color,
        ]);

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Color được cập nhật thành công.', 'color' => $color], 200);
    } catch (\Exception $e) {
        // Trả về phản hồi lỗi nếu có lỗi xảy ra
        return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật color: ' . $e->getMessage()], 500);
    }
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
{
    try {
        // Xóa màu
        $color->delete();

        // Trả về phản hồi với mã 200 và thông báo thành công
        return response()->json(['message' => 'Color đã được xóa thành công.'], 200);
    } catch (\Exception $e) {
        // Trả về phản hồi lỗi nếu có lỗi xảy ra
        return response()->json(['message' => 'Có lỗi xảy ra khi xóa color: ' . $e->getMessage()], 500);
    }
}

}
