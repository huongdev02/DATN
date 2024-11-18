<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Throwable;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        // Validate the incoming request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        dd($request->all());

        try {
            // Find the order to ensure it exists and is valid for review
            $order = Order::findOrFail($orderId);

            // Check if the order is completed
            if ($order->status != 3) {
                return redirect()->back()->withErrors('Bạn chỉ có thể đánh giá đơn hàng đã hoàn thành.');
            }

            // Check if the order has already been reviewed
            if ($order->reviews()->exists()) {
                return redirect()->back()->withErrors('Đơn hàng này đã được đánh giá.');
            }

            // Handle file upload if image is provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('reviews', 'public');
            }

            // Save the review
            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $order->orderDetails->first()->product_id, // Assuming 1 product per order for simplicity
                'image_path' => $imagePath,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'status' => 0, // Default status: Pending approval
                'order_id' => $order->id,
            ]);

            // Mark the order as reviewed
            $order->update(['is_reviewed' => true]);

            return back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ phê duyệt.');
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi' . $e->getMessage());
        }
    }
}
