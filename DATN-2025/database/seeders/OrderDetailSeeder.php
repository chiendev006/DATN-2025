<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu muốn chạy lại seeder nhiều lần mà không bị trùng
        // DB::table('order_details')->truncate();

        // Lấy tất cả các sản phẩm để có product_id, product_name và giả định price
        $sanphams = DB::table('sanphams')->select('id', 'name', 'title')->get();

        if ($sanphams->isEmpty()) {
            echo "Vui lòng chạy SanphamSeeder trước để có dữ liệu sản phẩm.\n";
            return;
        }

        $orderIds = DB::table('orders')->pluck('id')->toArray();

        if (empty($orderIds)) {
            echo "Vui lòng chạy OrderSeeder trước để có dữ liệu đơn hàng.\n";
            return;
        }

        $statuses = ['pending', 'completed', 'cancelled'];

        // Lặp qua từng order_id đã tạo
        foreach ($orderIds as $orderId) {
            // Mỗi đơn hàng sẽ có từ 1 đến 5 sản phẩm khác nhau
            $numberOfItems = rand(1, 5);
            $selectedSanphamIds = []; // Để tránh thêm cùng một sản phẩm nhiều lần vào một đơn hàng

            for ($j = 0; $j < $numberOfItems; $j++) {
                $randomSanpham = $sanphams->random(); // Lấy ngẫu nhiên một sản phẩm
                $sanphamId = $randomSanpham->id;
                $sanphamName = $randomSanpham->name;

                // Đảm bảo không trùng sản phẩm trong cùng một order_id
                while (in_array($sanphamId, $selectedSanphamIds)) {
                    $randomSanpham = $sanphams->random();
                    $sanphamId = $randomSanpham->id;
                    $sanphamName = $randomSanpham->name;
                }
                $selectedSanphamIds[] = $sanphamId;

                $quantity = rand(1, 3); // Số lượng từ 1 đến 3

                // Giả định giá sản phẩm dựa trên loại hoặc một phạm vi ngẫu nhiên
                $productPrice = 0;
                // Đây là ví dụ về cách gán giá giả định dựa trên ID sản phẩm hoặc loại sản phẩm
                // Trong thực tế, bạn sẽ cần một cột giá trong bảng 'sanphams' hoặc cơ chế tính giá khác
                // (Tôi sẽ sử dụng 'title' làm một cách để phân loại tạm thời để gán giá)
                if (stripos($randomSanpham->title, 'Cafe') !== false) {
                    $productPrice = rand(25000, 50000);
                } elseif (stripos($randomSanpham->title, 'nước ngọt') !== false || stripos($randomSanpham->title, 'Trà xanh') !== false || stripos($randomSanpham->title, 'Nước suối') !== false || stripos($randomSanpham->title, 'tăng lực') !== false) {
                    $productPrice = rand(15000, 25000);
                } elseif (stripos($randomSanpham->title, 'Trà sữa') !== false) {
                    $productPrice = rand(30000, 55000);
                } elseif (stripos($randomSanpham->title, 'Sinh tố') !== false) {
                    $productPrice = rand(35000, 60000);
                } else {
                    $productPrice = rand(20000, 50000); // Giá mặc định nếu không khớp
                }

                $totalDetail = $productPrice * $quantity;
                $statusDetail = $statuses[array_rand($statuses)];

                DB::table('order_detail')->insert([
                    'order_id' => $orderId,
                    'product_id' => $sanphamId,
                    'product_name' => $sanphamName,
                    'product_price' => $productPrice,
                    'quantity' => $quantity,
                    'total' => $totalDetail,
                    'size_id' => (rand(0,1) ? rand(1, 3) : null), // Giả định 3 size (S, M, L) hoặc null
                    'topping_id' => (rand(0,1) ? json_encode(['TP' . rand(1,5)]) : null), // Giả định topping ID ngẫu nhiên
                    'status' => $statusDetail,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
