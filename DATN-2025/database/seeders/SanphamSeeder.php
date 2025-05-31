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
            [
                'name' => 'Trà sữa vải',
                'image' => 'travai.jpg',
                'title' => 'Trà sữa vải',
                'mota' => 'Tra vai ngon vai cac ban oi',
                'id_danhmuc' => 1 // id 1: trà sữa
            ],
            [
                'name' => 'Trà sữa trân châu đường đen',
                'image' => 'tranchauden.jpg',
                'title' => 'Trà sữa trân châu đường đen',
                'mota' => 'Trân châu đường đen dẻo dai, ngọt ngào',
                'id_danhmuc' => 1 // id 1: trà sữa
            ],
            [
                'name' => 'Trà sữa matcha',
                'image' => 'matcha.jpg',
                'title' => 'Trà sữa matcha',
                'mota' => 'Hương vị matcha Nhật Bản thanh mát, đậm đà',
                'id_danhmuc' => 1 // id 1: trà sữa
            ],
            [
                'name' => 'Cafe đen đá',
                'image' => 'cafedenda.jpg',
                'title' => 'Cafe đen đá',
                'mota' => 'Cafe đậm đà, sảng khoái',
                'id_danhmuc' => 2 // id 2: cafe
            ],
            [
                'name' => 'Cafe sữa đá',
                'image' => 'cafesuada.jpg',
                'title' => 'Cafe sữa đá',
                'mota' => 'Hương vị cafe sữa truyền thống Việt Nam',
                'id_danhmuc' => 2 // id 2: cafe
            ],
            [
                'name' => 'Coca Cola',
                'image' => 'cocacola.jpg',
                'title' => 'Coca Cola',
                'mota' => 'Nước ngọt giải khát có ga',
                'id_danhmuc' => 3 // id 3: nước ngọt
            ],
            [
                'name' => 'Pepsi',
                'image' => 'pepsi.jpg',
                'title' => 'Pepsi',
                'mota' => 'Nước ngọt giải khát phổ biến',
                'id_danhmuc' => 3 // id 3: nước ngọt
            ],
            [
                'name' => 'Sinh tố bơ',
                'image' => 'sinhtobo.jpg',
                'title' => 'Sinh tố bơ',
                'mota' => 'Sinh tố bơ sánh mịn, bổ dưỡng',
                'id_danhmuc' => 4 // id 4: sinh tố
            ],
            [
                'name' => 'Sinh tố xoài',
                'image' => 'sinhtoxoai.jpg',
                'title' => 'Sinh tố xoài',
                'mota' => 'Sinh tố xoài tươi mát, thơm ngon',
                'id_danhmuc' => 4 // id 4: sinh tố
            ]
        ]);
    }
}
