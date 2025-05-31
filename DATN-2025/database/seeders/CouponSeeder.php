<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('coupons')->insert([
            [
                'code' => 'SALE10',
                'discount' => 10,
                'type' => 'percent',
                'expires_at' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SALE20',
                'discount' => 20000,
                'type' => 'fixed',
                'expires_at' => now()->addDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FREESHIP',
                'discount' => 0,
                'type' => 'fixed',
                'expires_at' => now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WELCOME',
                'discount' => 5,
                'type' => 'percent',
                'expires_at' => now()->addDays(20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUMMER',
                'discount' => 15000,
                'type' => 'fixed',
                'expires_at' => now()->addDays(25),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
