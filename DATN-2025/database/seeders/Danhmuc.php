<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Danhmuc extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('danhmucs')->insert([
            ['name' => 'Trà sửa','role'=>1],
            ['name' => 'Caffee','role'=>0],
            ['name' => 'Nước ngọt','role'=>0],
            ['name' => 'Sinh tố','role'=>1],
        ]);
    }
}
