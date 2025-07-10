# Hướng dẫn cấu hình Email cho hệ thống

## 1. Cấu hình Email trong file .env

Thêm các cấu hình sau vào file `.env` của bạn:

```env
# Cấu hình Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Tên cửa hàng của bạn"
```

## 2. Cấu hình Gmail (Khuyến nghị)

### Bước 1: Bật xác thực 2 yếu tố
- Đăng nhập vào tài khoản Gmail
- Vào Settings > Security
- Bật "2-Step Verification"

### Bước 2: Tạo App Password
- Vào Settings > Security > 2-Step Verification
- Chọn "App passwords"
- Tạo password mới cho ứng dụng
- Sử dụng password này trong `MAIL_PASSWORD`

## 3. Cấu hình Mailgun (Tùy chọn)

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
```

## 4. Cấu hình SendGrid (Tùy chọn)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## 5. Test Email

Để test email, bạn có thể sử dụng lệnh Artisan:

```bash
php artisan tinker
```

Sau đó chạy:

```php
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});
```

## 6. Các tính năng Email đã được tích hợp

### 6.1. Email xác nhận đơn hàng
- Tự động gửi khi đơn hàng được tạo thành công
- Bao gồm thông tin chi tiết đơn hàng
- Template đẹp và responsive

### 6.2. Email cập nhật trạng thái đơn hàng
- Gửi khi admin cập nhật trạng thái đơn hàng
- Hiển thị trạng thái cũ và mới
- Thông báo phù hợp với từng trạng thái

### 6.3. Logging
- Tất cả email đều được log
- Dễ dàng debug khi có lỗi
- Không ảnh hưởng đến quá trình xử lý đơn hàng

## 7. Troubleshooting

### Lỗi thường gặp:

1. **"SMTP connect() failed"**
   - Kiểm tra lại MAIL_HOST và MAIL_PORT
   - Đảm bảo firewall không chặn port 587

2. **"Authentication failed"**
   - Kiểm tra MAIL_USERNAME và MAIL_PASSWORD
   - Với Gmail, sử dụng App Password thay vì password thường

3. **"Connection refused"**
   - Kiểm tra kết nối internet
   - Thử đổi MAIL_HOST sang smtp.gmail.com

### Debug:

Thêm vào file `.env`:
```env
MAIL_LOG_CHANNEL=mail
```

Và trong `config/logging.php`:
```php
'channels' => [
    'mail' => [
        'driver' => 'single',
        'path' => storage_path('logs/mail.log'),
        'level' => 'debug',
    ],
],
```

## 8. Tùy chỉnh Template

Các template email được lưu tại:
- `resources/views/emails/order-confirmation.blade.php`
- `resources/views/emails/order-status-update.blade.php`

Bạn có thể chỉnh sửa để phù hợp với brand của mình. 