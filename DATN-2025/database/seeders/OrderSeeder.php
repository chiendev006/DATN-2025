<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order')->insert([
            [
                'user_id' => 1,
                'name' => 'Nguyen Van A',
                'address' => 'Hanoi',
                'phone' => '0123456789',
                'payment_method' => 'cash',
                'status' => 'pending',
                'total' => 100000,
                'transaction_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Tran Thi B',
                'address' => 'HCM',
                'phone' => '0987654321',
                'payment_method' => 'banking',
                'status' => 'processing',
                'total' => 200000,
                'transaction_id' => 'TXN123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Le Van C',
                'address' => 'Da Nang',
                'phone' => '0111222333',
                'payment_method' => 'cash',
                'status' => 'completed',
                'total' => 150000,
                'transaction_id' => 'TXN124',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'name' => 'Pham Thi D',
                'address' => 'Can Tho',
                'phone' => '0222333444',
                'payment_method' => 'banking',
                'status' => 'cancelled',
                'total' => 50000,
                'transaction_id' => 'TXN125',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'name' => 'Hoang Van E',
                'address' => 'Hai Phong',
                'phone' => '0333444555',
                'payment_method' => 'cash',
                'status' => 'pending',
                'total' => 120000,
                'transaction_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
