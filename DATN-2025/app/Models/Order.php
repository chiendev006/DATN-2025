<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address_id',
        'address_detail',
        'district_name',
        'payment_method',
        'status',
        'cancel_reason',
        'total',
        'transaction_id',
    ];
    public function orderDetails()
{
    return $this->hasMany(OrderDetail::class, 'order_id');
}
public function details()
{
    return $this->hasMany(OrderDetail::class, 'order_id');
}
public function coupons()
{
    return $this->belongsToMany(Coupon::class, 'coupon_order');
}

}
