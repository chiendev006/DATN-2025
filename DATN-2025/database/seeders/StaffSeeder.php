<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name' => 'Nhân viên 1',
                'email' => 'bartender@example.com',
                'password' => Hash::make('123456'),
                'role' => '22',
                'salary_per_day' => 200000
            ],
            [
                'name' => 'Nhân viên 2',
                'email' => 'staff@example.com',
                'password' => Hash::make('123456'),
                'role' => '21',
                'salary_per_day' => 200000
            ]
        ];

        foreach ($staff as $member) {
            User::create($member);
        }
    }
}
