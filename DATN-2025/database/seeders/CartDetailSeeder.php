<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cart_detail')->insert([
            [
                'cart_id' => 1,
                'product_id' => 1,
                'size_id' => 1,
                'topping_id' => null,
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 2,
                'product_id' => 2,
                'size_id' => 2,
                'topping_id' => null,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 3,
                'product_id' => 3,
                'size_id' => 3,
                'topping_id' => null,
                'quantity' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 4,
                'product_id' => 4,
                'size_id' => 4,
                'topping_id' => null,
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => 5,
                'product_id' => 5,
                'size_id' => 5,
                'topping_id' => null,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
