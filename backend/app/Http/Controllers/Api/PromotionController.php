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
            // Lấy toàn bộ bản ghi từ bảng promotions cùng thông tin sản phẩm liên quan
            $promotions = Promotion::with(['product:id,name,avatar,price'])
                ->get();

            // Chuyển đổi dữ liệu
            $promotions = $promotions->map(function ($promotion) {
                return [
                    'id' => $promotion->id,
                    'product' => [
                        'id' => $promotion->product->id,
                        'name' => $promotion->product->name,
                        'avatar_url' => $promotion->product->avatar
                            ? asset('storage/ProductAvatars/' . basename($promotion->product->avatar))
                            : null,
                        'price' => $promotion->product->price,
                    ],
                    'start_day' => $promotion->start_day,
                    'end_day' => $promotion->end_day,
                    'price_discount' => $promotion->price_discount,
                    'created_at' => $promotion->created_at,
                    'updated_at' => $promotion->updated_at,
                ];
            });

            return response()->json(['promotions' => $promotions]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không thể lấy danh sách khuyến mãi. ' . $e->getMessage()], 500);
        }
    }




    public function store(Request $request) {}

    public function update(Request $request, $id) {}


    public function destroy($id) {}
}
