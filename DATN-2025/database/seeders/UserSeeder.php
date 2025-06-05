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

        DB::table('users')->insert(['name'=>'staff','email'=>"staff@admin.comcom",'password'=>bcrypt('123456'),'role'=>21,'salary_per_day'=>200000]);

        DB::table('users')->insert(['name'=>'bartender','email'=>"bartender@admin.com",'password'=>bcrypt('123456'),'role'=>22,'salary_per_day'=>210000]);

        User::factory(5)->create();
    }
}
