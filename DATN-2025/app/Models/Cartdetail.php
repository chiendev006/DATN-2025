<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cartdetail extends Model
{
    use HasFactory;
    protected $table = 'cart_detail';
     protected $fillable = [
        'cart_id',
        'product_id',
        'size_id',
        'topping_id',
        'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Sanpham::class, 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function toppings()
    {
        return $this->belongsToMany(Product_topping::class, 'topping_id');
    }
}
