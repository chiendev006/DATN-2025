<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Topping extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('toppings')->insert([
            ['name' => 'Trân châu đen', 'price' => 5000],
            ['name' => 'Trân châu trắng', 'price' => 5000],
            ['name' => 'Thạch rau câu', 'price' => 7000],
            ['name' => 'Pudding', 'price' => 8000],
            ['name' => 'Kem cheese', 'price' => 10000],
            ['name' => 'Thạch dừa', 'price' => 7000],
        ]);
    }
}
