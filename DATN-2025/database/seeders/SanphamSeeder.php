<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanphamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('sanphams')->insert([
            // --- Caffee (id_danhmuc = 0) ---
            [
                'name' => 'Cafe Đen Đá',
                'image' => 'cafe_den_da.jpg',
                'title' => 'Cafe đen đá đậm chất Việt Nam',
                'mota' => 'Hương vị cafe Robusta nguyên chất, đắng nhẹ, hậu vị sâu.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Sữa Đá',
                'image' => 'cafe_sua_da.jpg',
                'title' => 'Cafe sữa đá truyền thống',
                'mota' => 'Cafe đậm đà hòa quyện với sữa đặc béo ngậy, ngọt ngào.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Bạc Xỉu',
                'image' => 'bac_xiu.jpg',
                'title' => 'Bạc xỉu dịu nhẹ',
                'mota' => 'Sự kết hợp tinh tế giữa cafe, sữa đặc và sữa tươi, thích hợp cho người mới uống cafe.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Latte',
                'image' => 'latte.jpg',
                'title' => 'Latte nghệ thuật',
                'mota' => 'Cafe espresso pha với sữa nóng và lớp bọt sữa mịn màng, ấm áp.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cappuccino',
                'image' => 'cappuccino.jpg',
                'title' => 'Cappuccino cổ điển',
                'mota' => 'Hòa quyện giữa espresso, sữa nóng và lớp bọt sữa dày, rắc bột ca cao.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Americano',
                'image' => 'americano.jpg',
                'title' => 'Americano sảng khoái',
                'mota' => 'Espresso pha loãng với nước nóng, mang lại hương vị mạnh mẽ, thuần khiết.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Mocha',
                'image' => 'mocha.jpg',
                'title' => 'Mocha ngọt ngào',
                'mota' => 'Sự kết hợp quyến rũ giữa cafe, chocolate và sữa tươi.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Espresso',
                'image' => 'espresso.jpg',
                'title' => 'Espresso nguyên bản',
                'mota' => 'Tách cafe đậm đặc, thơm lừng, là khởi đầu hoàn hảo.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Trứng',
                'image' => 'cafe_trung.jpg',
                'title' => 'Cafe trứng đặc sản Hà Nội',
                'mota' => 'Độc đáo với lớp kem trứng béo ngậy, sánh mịn trên nền cafe thơm lừng.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Dừa',
                'image' => 'cafe_dua.jpg',
                'title' => 'Cafe dừa mát lạnh',
                'mota' => 'Hương vị cafe kết hợp với cốt dừa béo ngậy, giải khát cực đỉnh.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cold Brew',
                'image' => 'cold_brew.jpg',
                'title' => 'Cold Brew ủ lạnh',
                'mota' => 'Cafe được ủ lạnh trong nhiều giờ, vị dịu nhẹ, ít chua và đắng.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Macchiato',
                'image' => 'macchiato.jpg',
                'title' => 'Macchiato tinh tế',
                'mota' => 'Espresso chấm nhẹ bọt sữa, hương vị cafe được giữ nguyên vẹn.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Kem Muối',
                'image' => 'cafe_kem_muoi.jpg',
                'title' => 'Cafe kem muối độc đáo',
                'mota' => 'Vị cafe truyền thống kết hợp lớp kem muối béo mặn, lạ miệng.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Caramel Macchiato',
                'image' => 'caramel_macchiato.jpg',
                'title' => 'Caramel Macchiato quyến rũ',
                'mota' => 'Sự kết hợp giữa cafe, sữa tươi, vanilla và sốt caramel.',
                'id_danhmuc' => 2

            ],
            [
                'name' => 'Cafe Hạt Dẻ',
                'image' => 'cafe_hat_de.jpg',
                'title' => 'Cafe hạt dẻ thơm bùi',
                'mota' => 'Hương cafe hòa quyện vị bùi của hạt dẻ, ấm áp và thư giãn.',
                'id_danhmuc' => 2

            ],


            // --- NƯỚC NGỌT (id_danhmuc = 0) ---
            [
                'name' => 'Coca Cola',
                'image' => 'coca_cola.jpg',
                'title' => 'Coca Cola giải khát tức thì',
                'mota' => 'Nước ngọt có ga phổ biến toàn cầu, sảng khoái mọi lúc.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Pepsi',
                'image' => 'pepsi.jpg',
                'title' => 'Pepsi bùng nổ hương vị',
                'mota' => 'Thức uống có ga quen thuộc, giải nhiệt mùa hè nóng bức.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Sprite',
                'image' => 'sprite.jpg',
                'title' => 'Sprite chanh thanh mát',
                'mota' => 'Nước ngọt có ga vị chanh, không caffeine, sảng khoái bất ngờ.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Fanta Cam',
                'image' => 'fanta_cam.jpg',
                'title' => 'Fanta Cam tươi vui',
                'mota' => 'Nước ngọt có ga vị cam, sôi động và tràn đầy năng lượng.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Mirinda Xá Xị',
                'image' => 'mirinda_xaxi.jpg',
                'title' => 'Mirinda Xá Xị thơm lừng',
                'mota' => 'Nước ngọt có ga vị xá xị đặc trưng, khó quên.',
                'id_danhmuc' => 3
            ],
            [
                'name' => '7 Up',
                'image' => '7up.jpg',
                'title' => '7 Up chanh và chanh dây',
                'mota' => 'Nước ngọt có ga hương chanh và chanh dây, tươi mới.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Sting Dâu',
                'image' => 'sting_dau.jpg',
                'title' => 'Sting Dâu tăng lực',
                'mota' => 'Nước tăng lực hương dâu, giúp tỉnh táo và tràn đầy năng lượng.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Trà Xanh Không Độ',
                'image' => 'traxanh_khongdo.jpg',
                'title' => 'Trà xanh Không Độ',
                'mota' => 'Trà xanh đóng chai, giải khát và thanh lọc cơ thể.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Nước Suối Aquafina',
                'image' => 'aquafina.jpg',
                'title' => 'Nước suối tinh khiết Aquafina',
                'mota' => 'Nước uống đóng chai tinh khiết, giải khát cơ bản.',
                'id_danhmuc' => 3
            ],
            [
                'name' => 'Red Bull',
                'image' => 'red_bull.jpg',
                'title' => 'Red Bull tăng cường năng lượng',
                'mota' => 'Nước tăng lực nổi tiếng thế giới, giúp tập trung và tỉnh táo.',
                'id_danhmuc' => 3
            ],


            // --- TRÀ SỮA (id_danhmuc = 1) ---
            [
                'name' => 'Trà Sữa Trân Châu Đen',
                'image' => 'ts_tranchauden.jpg',
                'title' => 'Trà sữa trân châu đường đen dẻo dai',
                'mota' => 'Trà sữa truyền thống với trân châu đường đen dai ngon, đậm vị đường đen.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Matcha',
                'image' => 'ts_matcha.jpg',
                'title' => 'Trà sữa Matcha Nhật Bản',
                'mota' => 'Hương vị Matcha nguyên chất từ Nhật Bản, thanh mát và có lợi cho sức khỏe.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Khoai Môn',
                'image' => 'ts_khoaimon.jpg',
                'title' => 'Trà sữa khoai môn béo ngậy',
                'mota' => 'Vị khoai môn tự nhiên thơm lừng, béo ngậy và ngọt dịu.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Oolong',
                'image' => 'ts_oolong.jpg',
                'title' => 'Trà sữa Oolong thanh tao',
                'mota' => 'Hương vị trà Oolong tinh tế, dịu nhẹ và thanh khiết.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Socola',
                'image' => 'ts_socola.jpg',
                'title' => 'Trà sữa Socola ngọt ngào',
                'mota' => 'Vị Socola thơm lừng hòa quyện cùng trà sữa béo, hấp dẫn mọi tín đồ.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Pudding Trứng',
                'image' => 'ts_puddingtrung.jpg',
                'title' => 'Trà sữa Pudding trứng mềm mịn',
                'mota' => 'Pudding trứng mềm mại, thơm ngon kết hợp trà sữa hoàn hảo.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Caramel',
                'image' => 'ts_caramel.jpg',
                'title' => 'Trà sữa Caramel thơm lừng',
                'mota' => 'Vị caramel ngọt ngào, thơm béo, tạo điểm nhấn cho ly trà sữa.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Hạt Dẻ',
                'image' => 'ts_hatde.jpg',
                'title' => 'Trà sữa hạt dẻ độc đáo',
                'mota' => 'Vị bùi bùi của hạt dẻ tạo nên hương vị trà sữa mới lạ và cuốn hút.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Sương Sáo',
                'image' => 'ts_suongsao.jpg',
                'title' => 'Trà sữa Sương Sáo giải nhiệt',
                'mota' => 'Sương sáo thanh mát kết hợp cùng trà sữa, dịu nhẹ và dễ uống.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Kem Cheese',
                'image' => 'ts_kemcheese.jpg',
                'title' => 'Trà sữa Kem Cheese béo ngậy',
                'mota' => 'Lớp kem cheese mặn mặn béo béo phủ trên trà sữa, bùng nổ hương vị.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Vải Lài',
                'image' => 'ts_vailai.jpg',
                'title' => 'Trà sữa vải lài thơm mát',
                'mota' => 'Hương thơm nhẹ nhàng của hoa lài kết hợp với vị ngọt thanh của vải.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Dâu Tây',
                'image' => 'ts_dautay.jpg',
                'title' => 'Trà sữa dâu tây ngọt ngào',
                'mota' => 'Vị dâu tây tươi mọng, chua ngọt hài hòa, tạo màu sắc đẹp mắt.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Bạc Hà',
                'image' => 'ts_bacha.jpg',
                'title' => 'Trà sữa bạc hà thanh mát',
                'mota' => 'Hương bạc hà the mát, sảng khoái tức thì, rất phù hợp mùa nóng.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Khoai Môn Kem Trứng',
                'image' => 'ts_khoaimon_kemtrung.jpg',
                'title' => 'Trà sữa khoai môn kem trứng',
                'mota' => 'Sự kết hợp giữa khoai môn béo ngậy và lớp kem trứng mềm mịn.',
                'id_danhmuc' => 1
            ],
            [
                'name' => 'Trà Sữa Lục Trà',
                'image' => 'ts_luctra.jpg',
                'title' => 'Trà sữa lục trà thanh đạm',
                'mota' => 'Trà sữa với nền lục trà thơm mát, dịu nhẹ, không quá béo.',
                'id_danhmuc' => 1
            ],


            // --- SINH TỐ (id_danhmuc = 1) ---
            [
                'name' => 'Sinh Tố Bơ',
                'image' => 'sinh_to_bo.jpg',
                'title' => 'Sinh tố bơ sánh mịn, bổ dưỡng',
                'mota' => 'Bơ tươi xay nhuyễn, cung cấp năng lượng và vitamin dồi dào.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Xoài',
                'image' => 'sinh_to_xoai.jpg',
                'title' => 'Sinh tố xoài tươi mát',
                'mota' => 'Xoài chín ngọt lịm xay cùng đá, giải nhiệt ngày hè nóng bức.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Mãng Cầu',
                'image' => 'sinh_to_mang_cau.jpg',
                'title' => 'Sinh tố mãng cầu đặc biệt',
                'mota' => 'Hương vị mãng cầu độc đáo, ngọt thanh và hấp dẫn.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Dâu Tây',
                'image' => 'sinh_to_dau_tay.jpg',
                'title' => 'Sinh tố dâu tây ngọt ngào',
                'mota' => 'Dâu tây tươi mọng, chua ngọt hài hòa, màu sắc đẹp mắt.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Việt Quất',
                'image' => 'sinh_to_viet_quat.jpg',
                'title' => 'Sinh tố việt quất thanh mát',
                'mota' => 'Việt quất giàu vitamin, tốt cho sức khỏe và giải khát.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Chuối',
                'image' => 'sinh_to_chuoi.jpg',
                'title' => 'Sinh tố chuối bổ dưỡng',
                'mota' => 'Chuối tươi xay nhuyễn, cung cấp năng lượng nhanh chóng.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Dứa',
                'image' => 'sinh_to_dua.jpg',
                'title' => 'Sinh tố dứa thanh nhiệt',
                'mota' => 'Dứa tươi ép, ngọt dịu và tốt cho tiêu hóa, giải nhiệt.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Cà Rốt',
                'image' => 'sinh_to_carot.jpg',
                'title' => 'Sinh tố cà rốt bổ mắt',
                'mota' => 'Cà rốt tươi, giàu vitamin A, tốt cho thị lực và sức khỏe.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Thanh Long',
                'image' => 'sinh_to_thanh_long.jpg',
                'title' => 'Sinh tố thanh long đẹp da',
                'mota' => 'Thanh long tươi mát, giàu chất xơ, tốt cho da và dáng.',
                'id_danhmuc' => 4
            ],
            [
                'name' => 'Sinh Tố Dưa Hấu',
                'image' => 'sinh_to_dua_hau.jpg',
                'title' => 'Sinh tố dưa hấu giải khát',
                'mota' => 'Dưa hấu tươi mọng, ngọt mát, giải nhiệt tức thì, cực sảng khoái.',
                'id_danhmuc' => 4
            ],
        ]);
    }
}
