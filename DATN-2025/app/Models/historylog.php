<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historylog extends Model
{
    use HasFactory;
    protected  $table = 'historylogs';
    protected $fillable = ['user_id', 'role', 'content'];

    public function userlog()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
