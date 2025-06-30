<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DanhmucBlog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
             DanhmucSeeder::class,
             SanphamSeeder::class,
             ToppingSeeder::class,
             ProductAttributeSeeder::class,
             ProductToppingSeeder::class,
             CouponSeeder::class,
             AddressSeeder::class,
             ContactSeeder::class,
             BlogsSeeder::class,
             StaffSeeder::class,

             ProductCommentSeeder::class,
             DanhmucBlogSeeder::class
        ]);
    }
}
