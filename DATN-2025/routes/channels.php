<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given class instance will be automatically
| bound to a channel listener when the user authenticates.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Kênh chat riêng tư giữa các user
Broadcast::channel('chat.user.{userId}', function ($user, $userId) {
    // Chỉ cho phép user có ID khớp với userId truy cập kênh này
    // Hoặc nếu bạn muốn admin có thể truy cập kênh của user, thêm logic ở đây
    return (int) $user->id === (int) $userId || $user->role == 1; // Sửa: Dùng role
});
