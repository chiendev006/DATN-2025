<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCommentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_comment')->insert([
            [
                'product_id' => 1,
                'user_id' => 1,
                'comment' => 'Ngon tuyệt!',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'user_id' => 2,
                'comment' => 'Rất thích!',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'user_id' => 3,
                'comment' => 'Ổn áp!',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 4,
                'user_id' => 4,
                'comment' => 'Bình thường!',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 5,
                'user_id' => 5,
                'comment' => 'Không thích lắm!',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
