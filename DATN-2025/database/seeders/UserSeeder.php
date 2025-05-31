<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         DB::table('users')->insert(['name'=>'admin','email'=>"admin@admin.com",'password'=>bcrypt('123456'),'role'=>1]);
        User::factory(5)->create();
    }
}
