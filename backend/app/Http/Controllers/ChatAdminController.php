<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatAdminController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with('user')->get();
        return response()->json($conversations);
    }

    public function show($id)
    {
        $conversation = Conversation::with(['messages.sender'])->findOrFail($id);
        return response()->json($conversation);
    }
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userId = auth()->id(); // Lấy ID người dùng hiện tại

        // Tìm hoặc tạo hội thoại giữa user và admin
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $userId],
            ['staff_id' => 2] // Giả sử admin có role là 2
        );

        // Lưu tin nhắn mới vào bảng messages
        $message = $conversation->messages()->create([
            'sender_id' => $userId,
            'message' => $request->message,
        ]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'sender_name' => auth()->user()->fullname ?? auth()->user()->email,
            'created_at' => $message->created_at->format('H:i d-m-Y'),
        ]);
    }
}
