<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa tất cả các bản ghi cũ trong bảng product_attributes (tùy chọn)
        Size::truncate();

        // Mảng chứa tất cả các bản ghi Product Attribute sẽ được chèn
        // Các product_id được giả định tuần tự từ 1 đến 49
        $productAttributesToInsert = [
            // Danh mục 1: Cà phê (Sản phẩm ID 1-12) - có các size S, M, L
            ['product_id' => 1, 'size' => 'S', 'price' => 30000],
            ['product_id' => 1, 'size' => 'M', 'price' => 35000],
            ['product_id' => 1, 'size' => 'L', 'price' => 40000],
            ['product_id' => 2, 'size' => 'S', 'price' => 30000],
            ['product_id' => 2, 'size' => 'M', 'price' => 35000],
            ['product_id' => 2, 'size' => 'L', 'price' => 40000],
            ['product_id' => 3, 'size' => 'S', 'price' => 30000],
            ['product_id' => 3, 'size' => 'M', 'price' => 35000],
            ['product_id' => 3, 'size' => 'L', 'price' => 40000],
            ['product_id' => 4, 'size' => 'S', 'price' => 30000],
            ['product_id' => 4, 'size' => 'M', 'price' => 35000],
            ['product_id' => 4, 'size' => 'L', 'price' => 40000],
            ['product_id' => 5, 'size' => 'S', 'price' => 30000],
            ['product_id' => 5, 'size' => 'M', 'price' => 35000],
            ['product_id' => 5, 'size' => 'L', 'price' => 40000],
            ['product_id' => 6, 'size' => 'S', 'price' => 30000],
            ['product_id' => 6, 'size' => 'M', 'price' => 35000],
            ['product_id' => 6, 'size' => 'L', 'price' => 40000],
            ['product_id' => 7, 'size' => 'S', 'price' => 30000],
            ['product_id' => 7, 'size' => 'M', 'price' => 35000],
            ['product_id' => 7, 'size' => 'L', 'price' => 40000],
            ['product_id' => 8, 'size' => 'S', 'price' => 30000],
            ['product_id' => 8, 'size' => 'M', 'price' => 35000],
            ['product_id' => 8, 'size' => 'L', 'price' => 40000],
            ['product_id' => 9, 'size' => 'S', 'price' => 30000],
            ['product_id' => 9, 'size' => 'M', 'price' => 35000],
            ['product_id' => 9, 'size' => 'L', 'price' => 40000],
            ['product_id' => 10, 'size' => 'S', 'price' => 30000],
            ['product_id' => 10, 'size' => 'M', 'price' => 35000],
            ['product_id' => 10, 'size' => 'L', 'price' => 40000],
            ['product_id' => 11, 'size' => 'S', 'price' => 30000],
            ['product_id' => 11, 'size' => 'M', 'price' => 35000],
            ['product_id' => 11, 'size' => 'L', 'price' => 40000],
            ['product_id' => 12, 'size' => 'S', 'price' => 30000],
            ['product_id' => 12, 'size' => 'M', 'price' => 35000],
            ['product_id' => 12, 'size' => 'L', 'price' => 40000],

            // Danh mục 2: Trà sữa (Sản phẩm ID 13-22) - có các size M, L, XL
            ['product_id' => 13, 'size' => 'M', 'price' => 35000],
            ['product_id' => 13, 'size' => 'L', 'price' => 40000],
            ['product_id' => 13, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 14, 'size' => 'M', 'price' => 35000],
            ['product_id' => 14, 'size' => 'L', 'price' => 40000],
            ['product_id' => 14, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 15, 'size' => 'M', 'price' => 35000],
            ['product_id' => 15, 'size' => 'L', 'price' => 40000],
            ['product_id' => 15, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 16, 'size' => 'M', 'price' => 35000],
            ['product_id' => 16, 'size' => 'L', 'price' => 40000],
            ['product_id' => 16, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 17, 'size' => 'M', 'price' => 35000],
            ['product_id' => 17, 'size' => 'L', 'price' => 40000],
            ['product_id' => 17, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 18, 'size' => 'M', 'price' => 35000],
            ['product_id' => 18, 'size' => 'L', 'price' => 40000],
            ['product_id' => 18, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 19, 'size' => 'M', 'price' => 35000],
            ['product_id' => 19, 'size' => 'L', 'price' => 40000],
            ['product_id' => 19, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 20, 'size' => 'M', 'price' => 35000],
            ['product_id' => 20, 'size' => 'L', 'price' => 40000],
            ['product_id' => 20, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 21, 'size' => 'M', 'price' => 35000],
            ['product_id' => 21, 'size' => 'L', 'price' => 40000],
            ['product_id' => 21, 'size' => 'XL', 'price' => 45000],
            ['product_id' => 22, 'size' => 'M', 'price' => 35000],
            ['product_id' => 22, 'size' => 'L', 'price' => 40000],
            ['product_id' => 22, 'size' => 'XL', 'price' => 45000],

            // Danh mục 3: Nước ép (Sản phẩm ID 23-37) - có các size M, L
            ['product_id' => 23, 'size' => 'M', 'price' => 30000],
            ['product_id' => 23, 'size' => 'L', 'price' => 35000],
            ['product_id' => 24, 'size' => 'M', 'price' => 30000],
            ['product_id' => 24, 'size' => 'L', 'price' => 35000],
            ['product_id' => 25, 'size' => 'M', 'price' => 30000],
            ['product_id' => 25, 'size' => 'L', 'price' => 35000],
            ['product_id' => 26, 'size' => 'M', 'price' => 30000],
            ['product_id' => 26, 'size' => 'L', 'price' => 35000],
            ['product_id' => 27, 'size' => 'M', 'price' => 30000],
            ['product_id' => 27, 'size' => 'L', 'price' => 35000],
            ['product_id' => 28, 'size' => 'M', 'price' => 30000],
            ['product_id' => 28, 'size' => 'L', 'price' => 35000],
            ['product_id' => 29, 'size' => 'M', 'price' => 30000],
            ['product_id' => 29, 'size' => 'L', 'price' => 35000],
            ['product_id' => 30, 'size' => 'M', 'price' => 30000],
            ['product_id' => 30, 'size' => 'L', 'price' => 35000],
            ['product_id' => 31, 'size' => 'M', 'price' => 30000],
            ['product_id' => 31, 'size' => 'L', 'price' => 35000],
            ['product_id' => 32, 'size' => 'M', 'price' => 30000],
            ['product_id' => 32, 'size' => 'L', 'price' => 35000],
            ['product_id' => 33, 'size' => 'M', 'price' => 30000],
            ['product_id' => 33, 'size' => 'L', 'price' => 35000],
            ['product_id' => 34, 'size' => 'M', 'price' => 30000],
            ['product_id' => 34, 'size' => 'L', 'price' => 35000],
            ['product_id' => 35, 'size' => 'M', 'price' => 30000],
            ['product_id' => 35, 'size' => 'L', 'price' => 35000],
            ['product_id' => 36, 'size' => 'M', 'price' => 30000],
            ['product_id' => 36, 'size' => 'L', 'price' => 35000],
            ['product_id' => 37, 'size' => 'M', 'price' => 30000],
            ['product_id' => 37, 'size' => 'L', 'price' => 35000],

            // Danh mục 4: Sinh tố (Sản phẩm ID 38-49) - có các size M, L
            ['product_id' => 38, 'size' => 'M', 'price' => 40000],
            ['product_id' => 38, 'size' => 'L', 'price' => 45000],
            ['product_id' => 39, 'size' => 'M', 'price' => 40000],
            ['product_id' => 39, 'size' => 'L', 'price' => 45000],
            ['product_id' => 40, 'size' => 'M', 'price' => 40000],
            ['product_id' => 40, 'size' => 'L', 'price' => 45000],
            ['product_id' => 41, 'size' => 'M', 'price' => 40000],
            ['product_id' => 41, 'size' => 'L', 'price' => 45000],
            ['product_id' => 42, 'size' => 'M', 'price' => 40000],
            ['product_id' => 42, 'size' => 'L', 'price' => 45000],
            ['product_id' => 43, 'size' => 'M', 'price' => 40000],
            ['product_id' => 43, 'size' => 'L', 'price' => 45000],
            ['product_id' => 44, 'size' => 'M', 'price' => 40000],
            ['product_id' => 44, 'size' => 'L', 'price' => 45000],
            ['product_id' => 45, 'size' => 'M', 'price' => 40000],
            ['product_id' => 45, 'size' => 'L', 'price' => 45000],
            ['product_id' => 46, 'size' => 'M', 'price' => 40000],
            ['product_id' => 46, 'size' => 'L', 'price' => 45000],
            ['product_id' => 47, 'size' => 'M', 'price' => 40000],
            ['product_id' => 47, 'size' => 'L', 'price' => 45000],
            ['product_id' => 48, 'size' => 'M', 'price' => 40000],
            ['product_id' => 48, 'size' => 'L', 'price' => 45000],
            ['product_id' => 49, 'size' => 'M', 'price' => 40000],
            ['product_id' => 49, 'size' => 'L', 'price' => 45000],
        ];

        // Chèn tất cả dữ liệu vào database chỉ với một truy vấn duy nhất
        Size::insert($productAttributesToInsert);
    }
}
