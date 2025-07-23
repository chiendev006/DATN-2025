<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentSanpham extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'user_name', 'content'];
    protected $table = 'commentsanpham';
    public function sanpham()
    {
        return $this->belongsTo(sanpham::class, 'product_id');
    }
}
