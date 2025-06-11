<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartdetail extends Model
{
    use HasFactory;

    protected $table = 'cart_detail';

    protected $fillable = [
        'cart_id',
        'product_id',
        'size_id',
        'topping_id',
        'quantity',
    ];

    // Quan hệ: chi tiết giỏ hàng thuộc về 1 giỏ hàng
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    // Quan hệ: sản phẩm
    public function product()
    {
        return $this->belongsTo(Sanpham::class, 'product_id');
    }

    // Quan hệ: size
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    // Quan hệ: topping (nên là hasOne hoặc belongsTo nếu chỉ 1 topping)
    public function topping()
    {
        return $this->belongsTo(Product_topping::class, 'topping_id');
    }

    // ✅ Lấy user thông qua quan hệ cart
    public function user()
    {
        return $this->hasOneThrough(
            User::class,   // Model cuối
            Cart::class,   // Model trung gian
            'id',          // Khóa chính của bảng cart
            'id',          // Khóa chính của bảng user
            'cart_id',     // Khóa ngoại ở cart_detail
            'user_id'      // Khóa ngoại ở cart
        );
    }
}
