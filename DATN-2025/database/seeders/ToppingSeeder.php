<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topping;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        $toppings = [
            [
                'name' => 'Trân châu',
                'price' => 5000,

            ],
            [
                'name' => 'Thạch rau câu',
                'price' => 5000,

            ],
            [
                'name' => 'Kem cheese',
                'price' => 10000,

            ],
            [
                'name' => 'Kem phô mai',
                'price' => 10000,

            ],
            [
                'name' => 'Kem phô mai',
                'price' => 10000,
            ],
            [
                'name'=> 'Kem muối',
                'price' => 10000,
            ]
        ];

        foreach ($toppings as $topping) {
            Topping::create($topping);
        }
    }
}
