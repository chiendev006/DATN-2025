<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\User; // Import model User

Route::middleware('auth:sanctum')->group(function () {

    // --- CLIENT API ---
    Route::post('/client/chat/send', function (Request $request) {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
            'type' => 'nullable|in:text,image',
        ]);

        $senderId = $request->user()->id; // ID của người gửi (client)
        $receiverId = $request->receiver_id; // ID của người nhận (admin hoặc user khác)

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->message_content,
            'type' => $request->type ?? 'text',
        ]);

        // Broadcast tin nhắn
        broadcast(new MessageSent($message))->toOthers(); // Chỉ broadcast đến người khác (người nhận)

        return response()->json([
            'message' => 'Message sent!',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    });

    Route::get('/client/chat/history/{partnerId}', function (Request $request, $partnerId) {
        $userId = $request->user()->id;

        // Lấy tin nhắn giữa userId và partnerId
        $messages = Message::where(function($query) use ($userId, $partnerId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $partnerId);
        })->orWhere(function($query) use ($userId, $partnerId) {
            $query->where('sender_id', $partnerId)
                  ->where('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->with('sender:id,name') // Load thông tin người gửi để hiển thị tên
        ->get()
        ->map(function($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'is_edited' => $message->is_edited,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $message->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json(['messages' => $messages]);
    });


    // --- ADMIN API ---
    Route::post('/admin/chat/send', function (Request $request) {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
            'type' => 'nullable|in:text,image',
        ]);

        $senderId = $request->user()->id; // ID của người gửi (admin)
        $receiverId = $request->receiver_id; // ID của người nhận (client)

        // Đảm bảo người gửi là admin
        if ($request->user()->role != 1) { // Sửa: Dùng role
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->message_content,
            'type' => $request->type ?? 'text',
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => 'Message sent!',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    });

    // Lấy danh sách user mà admin có thể chat (ví dụ: tất cả client)
    Route::get('/admin/chat/users', function (Request $request) { // Sửa: đổi tên route
        if ($request->user()->role != 1) { // Sửa: Dùng role
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $adminId = $request->user()->id;

        // Lấy ID của tất cả các user đã chat với admin
        $senderIds = Message::where('receiver_id', $adminId)->pluck('sender_id');
        $receiverIds = Message::where('sender_id', $adminId)->pluck('receiver_id');

        $userIds = $senderIds->merge($receiverIds)->unique();

        // Lấy thông tin user từ các ID đã tìm được
        $users = User::whereIn('id', $userIds)->where('id', '!=', $adminId)->select('id', 'name')->get();

        return response()->json(['users' => $users]);
    });

    Route::get('/admin/chat/history/{partnerId}', function (Request $request, $partnerId) {
        $adminId = $request->user()->id;

        // Đảm bảo người yêu cầu là admin
        if ($request->user()->role != 1) { // Sửa: Dùng role
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Lấy tin nhắn giữa adminId và partnerId
        $messages = Message::where(function($query) use ($adminId, $partnerId) {
            $query->where('sender_id', $adminId)
                  ->where('receiver_id', $partnerId);
        })->orWhere(function($query) use ($adminId, $partnerId) {
            $query->where('sender_id', $partnerId)
                  ->where('receiver_id', $adminId);
        })
        ->orderBy('created_at', 'asc')
        ->with('sender:id,name')
        ->get()
        ->map(function($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'is_edited' => $message->is_edited,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $message->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json(['messages' => $messages]);
    });

    Route::post('/chat/mark-read', function (Request $request) {
        $userId = $request->user()->id;
        $partnerId = $request->partner_id;
        \App\Models\Message::where('receiver_id', $userId)
            ->where('sender_id', $partnerId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    Route::get('/chat/unread-count', function (Request $request) {
        $userId = $request->user()->id;
        $count = \App\Models\Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
        return response()->json(['unread' => $count]);
    });

    // Route upload ảnh cho chat
    Route::post('/chat/upload', function (Request $request) {
        $request->validate([
            'image' => 'required|image|max:4096' // Max 4MB
        ]);

        $path = $request->file('image')->store('chat_images', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    });

    // Sửa tin nhắn (chỉ cho phép người gửi, chỉ sửa text)
    Route::patch('/chat/message/{id}', function (Request $request, $id) {
        $request->validate([
            'content' => 'required|string',
        ]);
        $userId = $request->user()->id;
        $message = Message::findOrFail($id);
        if ($message->sender_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($message->type !== 'text') {
            return response()->json(['message' => 'Chỉ được sửa tin nhắn dạng text'], 422);
        }
        // Nếu thu hồi thì không set is_edited
        if ($request->content === '*đã thu hồi*') {
            $message->content = $request->content;
            $message->is_edited = false;
        } else {
            $message->content = $request->content;
            $message->is_edited = true;
        }
        $message->save();
        broadcast(new MessageSent($message))->toOthers();
        return response()->json(['success' => true, 'message' => [
            'id' => $message->id,
            'content' => $message->content,
            'type' => $message->type,
            'is_edited' => $message->is_edited,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name,
            'receiver_id' => $message->receiver_id,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $message->updated_at->format('Y-m-d H:i:s'),
        ]]);
    });

    // Xóa tin nhắn (chỉ cho phép người gửi)
    Route::delete('/chat/message/{id}', function (Request $request, $id) {
        $userId = $request->user()->id;
        $message = Message::findOrFail($id);
        if ($message->sender_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $message->delete();
        return response()->json(['success' => true]);
    });

});
