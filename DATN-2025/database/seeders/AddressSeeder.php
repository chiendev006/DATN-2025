<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
              "name" => "Kiến Thụy",
              "shipping_fee" => 10000
            ],
            [
                "name" => "Tiên Lãng",
                "shipping_fee" => 15000
            ],
            [
                "name" => "Ngô Quyền",
                "shipping_fee" => 20000
            ],
            [
                "name" => "Hải An",
                "shipping_fee" => 25000
            ],
            [
                "name" => "Ngô Quyền",
                "shipping_fee" => 30000
            ]
        ];

        foreach ($addresses as $address) {
            Address::create($address);
        }
    }
}
