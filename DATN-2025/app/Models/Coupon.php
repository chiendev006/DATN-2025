<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupons';

    protected $casts=[
        'starts_at' => 'datetime',
        'expires_at'=>'datetime'
    ];
    protected $fillable = [ 'code', 'discount', 'min_order_value','starts_at', 'expires_at', 'type', 'usage_limit'];
    public function orders()
{
    return $this->belongsToMany(Order::class, 'coupon_order');
}

}
