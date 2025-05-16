<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanphamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sanphams')->insert([
            'name' => 'Trà sữa vải',
            'price' => 50000,
            'image' => 'travai.jpg',
            'mota' => 'Tra vai ngon vai cac ban oi',
            'id_danhmuc' => 1,
        ]);
    }
}
