<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatUserController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // Lấy ID người dùng hiện tại

        // Lấy tất cả các hội thoại của user
        $conversations = Conversation::with(['messages.sender'])
            ->where('user_id', $userId)
            ->get();

        $data = $conversations->map(function ($conversation) {
            return [
                'id' => $conversation->id,
                'messages' => $conversation->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender->fullname ?? $message->sender->email,
                        'created_at' => $message->created_at->format('H:i d-m-Y'),
                    ];
                }),
            ];
        });

        return response()->json($data);
    }




    public function show($id)
    {
        $userId = auth()->id(); // ID người dùng hiện tại

        // Tìm đoạn hội thoại liên quan đến user hiện tại
        $conversation = Conversation::with(['messages.sender'])
            ->where('user_id', $userId)
            ->findOrFail($id); // Tìm hội thoại theo ID hoặc báo lỗi 404 nếu không tìm thấy

        // Chuẩn bị dữ liệu trả về
        $data = [
            'id' => $conversation->id,
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->fullname ?? $message->sender->email,
                    'created_at' => $message->created_at->format('H:i d-m-Y'),
                ];
            }),
        ];

        return response()->json($data);
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
