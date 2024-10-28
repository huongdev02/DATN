<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Thêm dòng này

class ReviewController extends Controller
{
    // Lấy tất cả các đánh giá
    public function index()
    {
        try {
            $reviews = Review::all();
            return response()->json([
                'message' => 'Lấy tất cả đánh giá thành công.',
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy danh sách đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Xác thực dữ liệu
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'image_path' => 'nullable|string',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:255',
                // 'status' => 'required|string',
            ]);

            // Nếu có lỗi xác thực, trả về lỗi 422
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Có lỗi trong dữ liệu đầu vào.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tạo mới một đánh giá
            $review = Review::create([
                'user_id' => $request->user_id,
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'image_path' => $request->image_path,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => $request->status,
            ]);

            // Trả về phản hồi thành công
            return response()->json([
                'message' => 'Thêm đánh giá thành công.',
                'data' => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi thêm đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Lấy một đánh giá theo ID
    public function show($id)
    {
        try {
            $review = Review::findOrFail($id);
            return response()->json([
                'message' => 'Lấy đánh giá thành công.',
                'data' => $review
            ]);
        } catch (ModelNotFoundException $e) { // Sử dụng ModelNotFoundException
            return response()->json([
                'message' => 'Đánh giá không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật một đánh giá
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'order_id' => 'sometimes|exists:orders,id',
                'product_id' => 'sometimes|exists:products,id',
                'rating' => 'sometimes|integer|min:1|max:5',
                'comment' => 'sometimes|string',
                'status' => 'sometimes|string',
            ]);

            $review = Review::findOrFail($id);
            $review->update($request->all());

            return response()->json([
                'message' => 'Cập nhật đánh giá thành công.',
                'data' => $review
            ]);
        } catch (ModelNotFoundException $e) { // Sử dụng ModelNotFoundException
            return response()->json([
                'message' => 'Đánh giá không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'message' => 'Xóa đánh giá thành công.',
                'data' => $review
            ], 204);
        } catch (ModelNotFoundException $e) { 
            return response()->json([
                'message' => 'Đánh giá không tồn tại.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
