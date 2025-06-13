<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact; // Đảm bảo import model Contact
use Carbon\Carbon; // Import Carbon để dễ dàng làm việc với ngày tháng

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả các bản ghi cũ trong bảng contact (tùy chọn)
        Contact::truncate();

        // Định nghĩa 10 bản ghi liên hệ cụ thể
        $contactsToInsert = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'phone' => '0901234567',
                'message' => 'Tôi có câu hỏi về sản phẩm cà phê đen. Xin hãy liên hệ lại sớm nhất.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'phone' => '0902345678',
                'message' => 'Dịch vụ giao hàng của bạn rất tuyệt vời, tôi rất hài lòng!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'phone' => null, // Không có số điện thoại
                'message' => 'Tôi muốn biết thêm thông tin về các chương trình khuyến mãi.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'phone' => '0903456789',
                'message' => 'Sinh tố dâu tây của bạn rất ngon. Tôi sẽ giới thiệu cho bạn bè.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@example.com',
                'phone' => null,
                'message' => 'Có thể cung cấp bảng giá sỉ cho các quán cà phê không?',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Võ Thị F',
                'email' => 'vothif@example.com',
                'phone' => '0904567890',
                'message' => 'Tôi gặp một vấn đề nhỏ với đơn hàng #12345. Mong bạn hỗ trợ.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Đặng Văn G',
                'email' => 'dangvang@example.com',
                'phone' => '0905678901',
                'message' => 'Đề xuất thêm topping pudding vào trà sữa.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bùi Thị H',
                'email' => 'buithih@example.com',
                'phone' => null,
                'message' => 'Xin chào, tôi là đối tác tiềm năng và muốn tìm hiểu thêm về thương hiệu của bạn.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Đỗ Văn I',
                'email' => 'dovani@example.com',
                'phone' => '0906789012',
                'message' => 'Nước ép cam ép rất tươi và ngon miệng!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Huỳnh Thị K',
                'email' => 'huynhthik@example.com',
                'phone' => '0907890123',
                'message' => 'Tôi muốn hỏi về chính sách đổi trả sản phẩm.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Chèn tất cả 10 bản ghi vào database chỉ với một truy vấn duy nhất
        Contact::insert($contactsToInsert);
    }
}