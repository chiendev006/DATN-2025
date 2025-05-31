<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductToppingSeeder extends Seeder
{
    public function run(): void
    {
        // Sản phẩm trà sữa có id từ 1 đến 3
        $toppings = [
            ['topping' => 'Trân châu đen', 'price' => 5000],
            ['topping' => 'Thạch dừa', 'price' => 6000],
            ['topping' => 'Pudding trứng', 'price' => 7000],
        ];
        $data = [];
        for ($productId = 1; $productId <= 3; $productId++) {
            foreach ($toppings as $topping) {
                $data[] = [
                    'product_id' => $productId,
                    'topping' => $topping['topping'],
                    'price' => $topping['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('product_topping')->insert($data);
    }
}
