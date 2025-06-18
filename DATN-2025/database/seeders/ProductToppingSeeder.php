<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product_topping; // Assuming your model is ProductTopping, not Product_topping

class ProductToppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records in the product_toppings table (optional)
        Product_topping::truncate();

        // Define available toppings data
        $toppingsData = [
            [ 'name' => 'Trân châu', 'price' => 5000 ],
            [ 'name' => 'Thạch rau câu', 'price' => 5000 ],
            [ 'name' => 'Kem cheese', 'price' => 10000 ],
            [ 'name' => 'Kem phô mai', 'price' => 10000 ],
            [ 'name' => 'Kem muối', 'price' => 10000 ]
        ];

        // This array contains all ProductTopping records, manually generated.
        // It covers product_id 1 (3 records) and product_id 2-49 (3 records each).
        // NO LOOPS or RANDOM functions are used directly here.
        $productToppingsToInsert = [
            // Product ID 1 (3 records)
            ['product_id' => 1, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 1, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 1, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 2 (3 records)
            ['product_id' => 2, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 2, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 2, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 3 (3 records)
            ['product_id' => 3, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 3, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 3, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 4 (3 records)
            ['product_id' => 4, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 4, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 4, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 5 (3 records)
            ['product_id' => 5, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 5, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 5, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 6 (3 records)
            ['product_id' => 6, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 6, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 6, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 7 (3 records)
            ['product_id' => 7, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 7, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 7, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 8 (3 records)
            ['product_id' => 8, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 8, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 8, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 9 (3 records)
            ['product_id' => 9, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 9, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 9, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 10 (3 records)
            ['product_id' => 10, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 10, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 10, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 11 (3 records)
            ['product_id' => 11, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 11, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 11, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 12 (3 records)
            ['product_id' => 12, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 12, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 12, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 13 (3 records)
            ['product_id' => 13, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 13, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 13, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 14 (3 records)
            ['product_id' => 14, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 14, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 14, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 15 (3 records)
            ['product_id' => 15, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 15, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 15, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 16 (3 records)
            ['product_id' => 16, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 16, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 16, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 17 (3 records)
            ['product_id' => 17, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 17, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 17, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 18 (3 records)
            ['product_id' => 18, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 18, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 18, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 19 (3 records)
            ['product_id' => 19, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 19, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 19, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 20 (3 records)
            ['product_id' => 20, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 20, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 20, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 21 (3 records)
            ['product_id' => 21, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 21, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 21, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 22 (3 records)
            ['product_id' => 22, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 22, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 22, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 23 (3 records)
            ['product_id' => 23, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 23, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 23, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 24 (3 records)
            ['product_id' => 24, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 24, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 24, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 25 (3 records)
            ['product_id' => 25, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 25, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 25, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 26 (3 records)
            ['product_id' => 26, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 26, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 26, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 27 (3 records)
            ['product_id' => 27, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 27, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 27, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 28 (3 records)
            ['product_id' => 28, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 28, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 28, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 29 (3 records)
            ['product_id' => 29, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 29, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 29, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 30 (3 records)
            ['product_id' => 30, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 30, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 30, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 31 (3 records)
            ['product_id' => 31, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 31, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 31, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 32 (3 records)
            ['product_id' => 32, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 32, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 32, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 33 (3 records)
            ['product_id' => 33, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 33, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 33, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 34 (3 records)
            ['product_id' => 34, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 34, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 34, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 35 (3 records)
            ['product_id' => 35, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 35, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 35, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 36 (3 records)
            ['product_id' => 36, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 36, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 36, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 37 (3 records)
            ['product_id' => 37, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 37, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 37, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 38 (3 records)
            ['product_id' => 38, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 38, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 38, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 39 (3 records)
            ['product_id' => 39, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 39, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 39, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 40 (3 records)
            ['product_id' => 40, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 40, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 40, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 41 (3 records)
            ['product_id' => 41, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 41, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 41, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 42 (3 records)
            ['product_id' => 42, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 42, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 42, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 43 (3 records)
            ['product_id' => 43, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 43, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 43, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 44 (3 records)
            ['product_id' => 44, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 44, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 44, 'topping' => 'Thạch rau câu', 'price' => 5000],

            // Product ID 45 (3 records)
            ['product_id' => 45, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 45, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 45, 'topping' => 'Kem muối', 'price' => 10000],

            // Product ID 46 (3 records)
            ['product_id' => 46, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 46, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 46, 'topping' => 'Kem cheese', 'price' => 10000],

            // Product ID 47 (3 records)
            ['product_id' => 47, 'topping' => 'Kem phô mai', 'price' => 10000],
            ['product_id' => 47, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 47, 'topping' => 'Trân châu', 'price' => 5000],

            // Product ID 48 (3 records)
            ['product_id' => 48, 'topping' => 'Thạch rau câu', 'price' => 5000],
            ['product_id' => 48, 'topping' => 'Kem cheese', 'price' => 10000],
            ['product_id' => 48, 'topping' => 'Kem phô mai', 'price' => 10000],

            // Product ID 49 (3 records)
            ['product_id' => 49, 'topping' => 'Kem muối', 'price' => 10000],
            ['product_id' => 49, 'topping' => 'Trân châu', 'price' => 5000],
            ['product_id' => 49, 'topping' => 'Thạch rau câu', 'price' => 5000],
        ];

        // Insert all data into the database with a single query
        Product_topping::insert($productToppingsToInsert);
    }
}