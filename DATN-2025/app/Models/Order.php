<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address_id',
        'address_detail',
        'district_name',
        'shipping_fee',
        'payment_method',
        'status',
        'cancel_reason',
        'total',
        'transaction_id',
        'pay_status',
        'coupon_summary',
        'coupon_total_discount',
        'points_used',
        'points_discount',
        'note',
        'created_at',
        'updated_at',
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

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
