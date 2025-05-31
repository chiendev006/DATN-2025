<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_detail')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'product_name' => 'Trà sữa vải',
                'product_price' => 50000,
                'quantity' => 2,
                'total' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'product_name' => 'Cà phê sữa',
                'product_price' => 40000,
                'quantity' => 1,
                'total' => 40000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 3,
                'product_id' => 3,
                'product_name' => 'Nước cam',
                'product_price' => 30000,
                'quantity' => 3,
                'total' => 90000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 4,
                'product_id' => 4,
                'product_name' => 'Trà đào',
                'product_price' => 35000,
                'quantity' => 2,
                'total' => 70000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 5,
                'product_id' => 5,
                'product_name' => 'Sữa tươi trân châu',
                'product_price' => 45000,
                'quantity' => 1,
                'total' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
