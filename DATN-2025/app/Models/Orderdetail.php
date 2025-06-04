<?php

// app/Models/Orderdetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetail extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'total',
        'size_id',
        'topping_id',
        'note',
        'status'
    ];

   public function sanpham()
{
    return $this->belongsTo(sanpham::class, 'product_id');
}
public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Sanpham::class, 'product_id');
    }
    
public function toppings()
{
    return $this->belongsToMany(Topping::class, 'order_detail_topping', 'order_detail_id', 'topping_id');
}
public function size()
{
    return $this->belongsTo(Size::class, 'size_id');
}

}
