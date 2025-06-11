<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        DB::table('coupons')->insert([
            [
                'code' => 'GIAM50K',
                'discount' => 50000,
                'type' => 'fixed',
                'usage_limit' => 100,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 200000,
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GIAM10PHANTRAM',
                'discount' => 10,
                'type' => 'percent',
                'usage_limit' => null,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 100000,
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(60),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CHIENDACBIET',
                'discount' => 20,
                'type' => 'percent',
                'usage_limit' => 1,
                'used' => 0,
                'user_id' => 1, // Áp dụng riêng cho user ID 1
                'min_order_value' => null,
                'is_active' => true,
                'expires_at' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VOHIEU',
                'discount' => 100000,
                'type' => 'fixed',
                'usage_limit' => 10,
                'used' => 10,
                'user_id' => null,
                'min_order_value' => 0,
                'is_active' => false,
                'expires_at' => Carbon::now()->subDays(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
