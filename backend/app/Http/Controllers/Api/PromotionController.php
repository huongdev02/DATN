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
    }

    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {
    }
}
