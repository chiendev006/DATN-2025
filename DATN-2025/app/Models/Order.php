<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'payment_method',
        'status',
        'total',
        'transaction_id',
    ];
    public function details()
    {
        return $this->hasMany(Orderdetail::class);
    }
    public function orderDetails()
{
    return $this->hasMany(OrderDetail::class, 'order_id');
}

}
