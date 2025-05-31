<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('topping')->insert([
            [
                'name' => 'Trân châu đen',
                'price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thạch dừa',
                'price' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pudding trứng',
                'price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trân châu trắng',
                'price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thạch trái cây',
                'price' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
