<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatUserController extends Controller
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
    public function store(Request $request, $conversationId)
    {
        // Xác minh yêu cầu có chứa các trường cần thiết
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Lấy hội thoại theo ID
        $conversation = Conversation::findOrFail($conversationId);

        // Lấy người gửi từ auth (hoặc có thể từ user_id)
        $senderId = auth()->id(); // Lấy ID của người dùng hiện tại

        // Tạo và lưu tin nhắn mới
        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->sender_id = $senderId;
        $message->message = $request->message;
        $message->save();

        // Trả về thông tin hội thoại sau khi thêm tin nhắn
        return response()->json($conversation->load(['messages.sender']));
    }
}
