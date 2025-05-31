<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cart')->insert([
            [
                'user_id' => 1,
                'session_id' => null,
                'total' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'session_id' => null,
                'total' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'session_id' => null,
                'total' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'session_id' => null,
                'total' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'session_id' => null,
                'total' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
