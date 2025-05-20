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
        'quantity',
        'topping_id',
        'size_id',
    ];

    public function product()
    {
        return $this->belongsTo(Sanpham::class, 'product_id');
    }

public function size()
{   
    return $this->belongsTo(Size::class, 'size_id');
}
public function toppings()
{
    return Topping::whereIn('id', explode(',', $this->topping_id))->get();
}
public function cart()
{
    return $this->belongsTo(Cart::class);
}

}
