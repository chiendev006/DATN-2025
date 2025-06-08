<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_detail'; 

    protected $fillable = [
        'cart_id', 'product_id', 'size_id', 'topping_id', 'quantity',
    ];

    public $timestamps = true;
}
