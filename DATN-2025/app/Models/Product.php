<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'sanphams';

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'category_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toppings()
    {
        return $this->hasMany(Product_topping::class);
    }

    public function sizes()
    {
        return $this->hasMany(Product_size::class);
    }

    public function cartDetails()
    {
        return $this->hasMany(Cartdetail::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
} 