<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Lấy danh sách khuyến mãi
    public function index()
    {
        try {
            $promotions = Promotion::paginate(5);  
            return response()->json($promotions);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Lỗi khi truy xuất khuyến mãi: ' . $e->getMessage()], 500);
        }
    }

    // Tạo mới khuyến mãi
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'start_day' => 'required|date',
                'end_day' => 'required|date',
                'price_discount' => 'required|numeric',
            ]);

            $promotion = Promotion::create($request->all());

            return response()->json($promotion, 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Lỗi khi tạo khuyến mãi: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    // Xem chi tiết khuyến mãi
    public function show($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            return response()->json($promotion);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Lỗi khi truy xuất chi tiết khuyến mãi: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khuyến mãi không tồn tại.'], 404);
        }
    }

    // Cập nhật khuyến mãi
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'start_day' => 'required|date',
                'end_day' => 'required|date',
                'price_discount' => 'required|numeric',
            ]);

            $promotion = Promotion::findOrFail($id);
            $promotion->update($request->all());

            return response()->json($promotion);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Lỗi khi cập nhật khuyến mãi: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khuyến mãi không tồn tại.'], 404);
        }
    }

    // Xóa khuyến mãi
    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();

            return response()->json(['message' => 'Promotion deleted successfully']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Lỗi khi xóa khuyến mãi: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khuyến mãi không tồn tại.'], 404);
        }
    }
}
