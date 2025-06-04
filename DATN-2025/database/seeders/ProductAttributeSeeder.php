<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['size' => 'S', 'price' => 30000],
            ['size' => 'M', 'price' => 35000],
            ['size' => 'L', 'price' => 40000],
        ];
        $attributes = [];
        for ($productId = 1; $productId <= 100; $productId++) {
            foreach ($sizes as $size) {
                $attributes[] = [
                    'product_id' => $productId,
                    'size' => $size['size'],
                    'price' => $size['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('product_attributes')->insert($attributes);
    }
}
