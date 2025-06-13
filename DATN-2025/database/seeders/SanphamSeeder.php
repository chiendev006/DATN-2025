<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sanpham;

class SanphamSeeder extends Seeder
{

        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Xóa tất cả các sản phẩm cũ trước khi thêm mới (tùy chọn)
            Sanpham::truncate();

            // Định nghĩa tất cả các sản phẩm cụ thể
            $products = [
                // Danh mục 1: Cà phê (12 sản phẩm)
                ['name' => 'Cà phê đen 01', 'image' => 'ca-phe-den.jpg', 'title' => 'Cà phê đen truyền thống', 'mota' => 'Cà phê đen truyền thống Việt Nam, đậm đà và thơm lừng.', 'id_danhmuc' => 1],
                ['name' => 'Cà phê đen 02', 'image' => 'ca-phe-den.jpg', 'title' => 'Cà phê đen truyền thống', 'mota' => 'Cà phê đen truyền thống Việt Nam, đậm đà và thơm lừng.', 'id_danhmuc' => 1],
                ['name' => 'Cà phê đen 03', 'image' => 'ca-phe-den.jpg', 'title' => 'Cà phê đen truyền thống', 'mota' => 'Cà phê đen truyền thống Việt Nam, đậm đà và thơm lừng.', 'id_danhmuc' => 1],
                ['name' => 'Cà phê sữa 01', 'image' => 'ca-phe-sua-da.jpg', 'title' => 'Cà phê sữa đá chuẩn vị', 'mota' => 'Sự kết hợp hoàn hảo giữa cà phê và sữa đặc, mát lạnh sảng khoái.', 'id_danhmuc' => 1],
                ['name' => 'Cà phê sữa 02', 'image' => 'ca-phe-sua-da.jpg', 'title' => 'Cà phê sữa đá chuẩn vị', 'mota' => 'Sự kết hợp hoàn hảo giữa cà phê và sữa đặc, mát lạnh sảng khoái.', 'id_danhmuc' => 1],
                ['name' => 'Cà phê sữa 03', 'image' => 'ca-phe-sua-da.jpg', 'title' => 'Cà phê sữa đá chuẩn vị', 'mota' => 'Sự kết hợp hoàn hảo giữa cà phê và sữa đặc, mát lạnh sảng khoái.', 'id_danhmuc' => 1],
                ['name' => 'Bạc xỉu 01', 'image' => 'bac-xiu.jpg', 'title' => 'Bạc xỉu thơm ngon', 'mota' => 'Hương vị nhẹ nhàng, dễ chịu của bạc xỉu.', 'id_danhmuc' => 1],
                ['name' => 'Bạc xỉu 02', 'image' => 'bac-xiu.jpg', 'title' => 'Bạc xỉu thơm ngon', 'mota' => 'Hương vị nhẹ nhàng, dễ chịu của bạc xỉu.', 'id_danhmuc' => 1],
                ['name' => 'Latte 01', 'image' => 'latte.jpg', 'title' => 'Latte nghệ thuật', 'mota' => 'Latte với lớp bọt sữa mịn màng và hương vị cà phê nhẹ.', 'id_danhmuc' => 1],
                ['name' => 'Latte 02', 'image' => 'latte.jpg', 'title' => 'Latte nghệ thuật', 'mota' => 'Latte với lớp bọt sữa mịn màng và hương vị cà phê nhẹ.', 'id_danhmuc' => 1],
                ['name' => 'Espresso 01', 'image' => 'espresso.jpg', 'title' => 'Espresso đậm đặc', 'mota' => 'Espresso shot đậm đặc, khởi đầu ngày mới đầy năng lượng.', 'id_danhmuc' => 1],
                ['name' => 'Espresso 02', 'image' => 'espresso.jpg', 'title' => 'Espresso đậm đặc', 'mota' => 'Espresso shot đậm đặc, khởi đầu ngày mới đầy năng lượng.', 'id_danhmuc' => 1],

                // Danh mục 2: Trà sữa (10 sản phẩm)
                ['name' => 'Trà sữa trân châu 01', 'image' => 'tra-sua-tran-chau.jpg', 'title' => 'Trà sữa trân châu đường đen', 'mota' => 'Trà sữa thơm ngon với trân châu đường đen dẻo dai.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa trân châu 02', 'image' => 'tra-sua-tran-chau.jpg', 'title' => 'Trà sữa trân châu đường đen', 'mota' => 'Trà sữa thơm ngon với trân châu đường đen dẻo dai.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Thái xanh 01', 'image' => 'tra-sua-thai-xanh.jpg', 'title' => 'Trà sữa Thái xanh mát lạnh', 'mota' => 'Hương vị trà thái xanh đặc trưng, thanh mát và hấp dẫn.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Thái xanh 02', 'image' => 'tra-sua-thai-xanh.jpg', 'title' => 'Trà sữa Thái xanh mát lạnh', 'mota' => 'Hương vị trà thái xanh đặc trưng, thanh mát và hấp dẫn.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Oolong 01', 'image' => 'tra-sua-oolong.jpg', 'title' => 'Trà sữa Oolong thơm', 'mota' => 'Trà sữa Oolong với hương thơm tự nhiên và vị trà đậm đà.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Oolong 02', 'image' => 'tra-sua-oolong.jpg', 'title' => 'Trà sữa Oolong thơm', 'mota' => 'Trà sữa Oolong với hương thơm tự nhiên và vị trà đậm đà.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Matcha 01', 'image' => 'tra-sua-matcha.jpg', 'title' => 'Trà sữa Matcha đậm vị', 'mota' => 'Trà sữa Matcha với vị trà xanh Nhật Bản đặc trưng.', 'id_danhmuc' => 2],
                ['name' => 'Trà sữa Matcha 02', 'image' => 'tra-sua-matcha.jpg', 'title' => 'Trà sữa Matcha đậm vị', 'mota' => 'Trà sữa Matcha với vị trà xanh Nhật Bản đặc trưng.', 'id_danhmuc' => 2],
                ['name' => 'Hồng trà sữa 01', 'image' => 'hong-tra-sua.jpg', 'title' => 'Hồng trà sữa truyền thống', 'mota' => 'Hồng trà sữa với hương vị thơm lừng, dễ uống.', 'id_danhmuc' => 2],
                ['name' => 'Hồng trà sữa 02', 'image' => 'hong-tra-sua.jpg', 'title' => 'Hồng trà sữa truyền thống', 'mota' => 'Hồng trà sữa với hương vị thơm lừng, dễ uống.', 'id_danhmuc' => 2],

                // Danh mục 3: Nước ép (15 sản phẩm)
                ['name' => 'Nước cam ép 01', 'image' => 'nuoc-cam-ep.jpg', 'title' => 'Nước cam ép tươi', 'mota' => 'Nước cam ép tươi 100%, giàu vitamin C, tốt cho sức khỏe.', 'id_danhmuc' => 3],
                ['name' => 'Nước cam ép 02', 'image' => 'nuoc-cam-ep.jpg', 'title' => 'Nước cam ép tươi', 'mota' => 'Nước cam ép tươi 100%, giàu vitamin C, tốt cho sức khỏe.', 'id_danhmuc' => 3],
                ['name' => 'Nước cam ép 03', 'image' => 'nuoc-cam-ep.jpg', 'title' => 'Nước cam ép tươi', 'mota' => 'Nước cam ép tươi 100%, giàu vitamin C, tốt cho sức khỏe.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép ổi hồng 01', 'image' => 'nuoc-ep-oi-hong.jpg', 'title' => 'Nước ép ổi hồng tự nhiên', 'mota' => 'Nước ép ổi hồng thơm ngon, giúp giải khát và đẹp da.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép ổi hồng 02', 'image' => 'nuoc-ep-oi-hong.jpg', 'title' => 'Nước ép ổi hồng tự nhiên', 'mota' => 'Nước ép ổi hồng thơm ngon, giúp giải khát và đẹp da.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép táo 01', 'image' => 'nuoc-ep-tao.jpg', 'title' => 'Nước ép táo xanh', 'mota' => 'Nước ép táo xanh nguyên chất, giải nhiệt hiệu quả.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép táo 02', 'image' => 'nuoc-ep-tao.jpg', 'title' => 'Nước ép táo xanh', 'mota' => 'Nước ép táo xanh nguyên chất, giải nhiệt hiệu quả.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép dứa 01', 'image' => 'nuoc-ep-dua.jpg', 'title' => 'Nước ép dứa tươi', 'mota' => 'Nước ép dứa tươi mát, vị chua ngọt hài hòa.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép dứa 02', 'image' => 'nuoc-ep-dua.jpg', 'title' => 'Nước ép dứa tươi', 'mota' => 'Nước ép dứa tươi mát, vị chua ngọt hài hòa.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép cà rốt 01', 'image' => 'nuoc-ep-ca-rot.jpg', 'title' => 'Nước ép cà rốt bổ dưỡng', 'mota' => 'Nước ép cà rốt giàu vitamin A, tốt cho mắt.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép cà rốt 02', 'image' => 'nuoc-ep-ca-rot.jpg', 'title' => 'Nước ép cà rốt bổ dưỡng', 'mota' => 'Nước ép cà rốt giàu vitamin A, tốt cho mắt.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép dưa hấu 01', 'image' => 'nuoc-ep-dua-hau.jpg', 'title' => 'Nước ép dưa hấu giải khát', 'mota' => 'Nước ép dưa hấu ngọt mát, giải nhiệt tức thì.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép dưa hấu 02', 'image' => 'nuoc-ep-dua-hau.jpg', 'title' => 'Nước ép dưa hấu giải khát', 'mota' => 'Nước ép dưa hấu ngọt mát, giải nhiệt tức thì.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép bưởi 01', 'image' => 'nuoc-ep-buoi.jpg', 'title' => 'Nước ép bưởi thanh mát', 'mota' => 'Nước ép bưởi chua nhẹ, thanh mát.', 'id_danhmuc' => 3],
                ['name' => 'Nước ép bưởi 02', 'image' => 'nuoc-ep-buoi.jpg', 'title' => 'Nước ép bưởi thanh mát', 'mota' => 'Nước ép bưởi chua nhẹ, thanh mát.', 'id_danhmuc' => 3],

                // Danh mục 4: Sinh tố (12 sản phẩm)
                ['name' => 'Sinh tố bơ 01', 'image' => 'sinh-to-bo.jpg', 'title' => 'Sinh tố bơ thơm ngon', 'mota' => 'Sinh tố bơ béo ngậy, mịn màng, bổ dưỡng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố bơ 02', 'image' => 'sinh-to-bo.jpg', 'title' => 'Sinh tố bơ thơm ngon', 'mota' => 'Sinh tố bơ béo ngậy, mịn màng, bổ dưỡng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố xoài 01', 'image' => 'sinh-to-xoai.jpg', 'title' => 'Sinh tố xoài tươi mát', 'mota' => 'Sinh tố xoài ngọt thanh, mát lạnh.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố xoài 02', 'image' => 'sinh-to-xoai.jpg', 'title' => 'Sinh tố xoài tươi mát', 'mota' => 'Sinh tố xoài ngọt thanh, mát lạnh.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố dâu 01', 'image' => 'sinh-to-dau.jpg', 'title' => 'Sinh tố dâu tây', 'mota' => 'Sinh tố dâu tây chua ngọt hài hòa, thơm lừng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố dâu 02', 'image' => 'sinh-to-dau.jpg', 'title' => 'Sinh tố dâu tây', 'mota' => 'Sinh tố dâu tây chua ngọt hài hòa, thơm lừng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố chuối 01', 'image' => 'sinh-to-chuoi.jpg', 'title' => 'Sinh tố chuối bổ dưỡng', 'mota' => 'Sinh tố chuối sánh mịn, cung cấp năng lượng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố chuối 02', 'image' => 'sinh-to-chuoi.jpg', 'title' => 'Sinh tố chuối bổ dưỡng', 'mota' => 'Sinh tố chuối sánh mịn, cung cấp năng lượng.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố thập cẩm 01', 'image' => 'sinh-to-thap-cam.jpg', 'title' => 'Sinh tố trái cây tổng hợp', 'mota' => 'Sự kết hợp của nhiều loại trái cây tươi ngon.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố thập cẩm 02', 'image' => 'sinh-to-thap-cam.jpg', 'title' => 'Sinh tố trái cây tổng hợp', 'mota' => 'Sự kết hợp của nhiều loại trái cây tươi ngon.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố sapoche 01', 'image' => 'sinh-to-sapoche.jpg', 'title' => 'Sinh tố sapoche ngọt mát', 'mota' => 'Sinh tố sapoche béo ngậy, ngọt dịu tự nhiên.', 'id_danhmuc' => 4],
                ['name' => 'Sinh tố sapoche 02', 'image' => 'sinh-to-sapoche.jpg', 'title' => 'Sinh tố sapoche ngọt mát', 'mota' => 'Sinh tố sapoche béo ngậy, ngọt dịu tự nhiên.', 'id_danhmuc' => 4],
            ];

            // Chèn tất cả sản phẩm vào cơ sở dữ liệu bằng một truy vấn duy nhất
            Sanpham::insert($products);
        }
    }

