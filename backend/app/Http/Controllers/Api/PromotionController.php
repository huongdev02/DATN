<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromotionController extends Controller
{
    public function index()
    {
        try {
            $promotions = Promotion::all();
            return response()->json($promotions, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi lấy danh sách khuyến mãi: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            return response()->json(['message' => 'Lấy thành công', 'data' => $promotion], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Khuyến mãi không tồn tại'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi lấy thông tin khuyến mãi: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'start_day' => 'required|date',
                'end_day' => 'required|date|after:start_day',
                'price_discount' => 'required|numeric',
            ]);

            $promotion = Promotion::create($validated);
            return response()->json(['message' => 'Khuyến mãi đã được tạo thành công', 'data' => $promotion], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Xác thực không thành công', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi tạo khuyến mãi: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $promotion = Promotion::findOrFail($id);

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'start_day' => 'required|date',
                'end_day' => 'required|date|after:start_day',
                'price_discount' => 'required|numeric',
            ]);

            $promotion->update($validated);
            return response()->json(['message' => 'Khuyến mãi đã được cập nhật thành công', 'data' => $promotion], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Khuyến mãi không tồn tại'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Xác thực không thành công', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi cập nhật khuyến mãi: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();

            return response()->json(['message' => 'Khuyến mãi đã được xóa thành công'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Khuyến mãi không tồn tại'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi xóa khuyến mãi: ' . $e->getMessage()], 500);
        }
    }
}
