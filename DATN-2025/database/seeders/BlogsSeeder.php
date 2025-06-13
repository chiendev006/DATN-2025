<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blogs;

class BlogsSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Cách pha cà phê ngon',
                'content' => 'Hướng dẫn cách pha cà phê ngon tại nhà',
                'image' => 'blog1.jpg',

            ],
            [
                'title' => 'Công thức trà sữa',
                'content' => 'Công thức pha trà sữa thơm ngon',
                'image' => 'blog2.jpg',

            ]
        ];

        foreach ($blogs as $blog) {
            Blogs::create($blog);
        }
    }
}
