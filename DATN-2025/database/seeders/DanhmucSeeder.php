<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Danhmuc;

class DanhmucSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cà phê',
                'role' => 0,
            ],
            [
                'name' => 'Trà sữa',
                'role' => 1,
            ],
            [
                'name' => 'Nước ép',
                'role' => 0,
            ],
            [
                'name' => 'Sinh tố',
                'role' => 1,
            ]
        ];

        foreach ($categories as $category) {
            Danhmuc::create($category);
        }
    }
}
