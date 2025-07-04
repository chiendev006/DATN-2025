<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'type',
        'is_edited',
    ];

    // Định nghĩa mối quan hệ với User (người gửi)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Định nghĩa mối quan hệ với User (người nhận)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
