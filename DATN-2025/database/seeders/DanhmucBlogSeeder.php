<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DanhmucBlog;

class DanhmucBlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Đồ uống'],
            ['name' => 'Công thức'],
            ['name' => 'Sức khỏe']
        ];

        foreach ($categories as $category) {
            DanhmucBlog::firstOrCreate(['name' => $category['name']]);
        }
    }
}
