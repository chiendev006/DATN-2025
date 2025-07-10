# 🔧 Hướng dẫn cấu hình Gmail cho Email

## ❌ Lỗi hiện tại
```
Failed to authenticate on SMTP server with username "your-email@gmail.com"
Username and Password not accepted
```

## ✅ Cách khắc phục

### Bước 1: Bật xác thực 2 yếu tố
1. Đăng nhập vào [Gmail](https://mail.google.com)
2. Click vào **Settings** (⚙️) → **See all settings**
3. Chọn tab **Security**
4. Bật **"2-Step Verification"**
5. Làm theo hướng dẫn để xác thực

### Bước 2: Tạo App Password
1. Sau khi bật 2-Step Verification, quay lại **Security**
2. Tìm **"App passwords"** (Mật khẩu ứng dụng)
3. Click **"Generate"** hoặc **"Create"**
4. Chọn:
   - **Select app**: "Other (Custom name)"
   - **Enter name**: "Laravel Website" hoặc "My Store"
5. Click **"Generate"**
6. **Copy mật khẩu 16 ký tự** được tạo ra

### Bước 3: Cập nhật file .env
Thay thế thông tin trong file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-actual-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-actual-email@gmail.com
MAIL_FROM_NAME="Tên cửa hàng của bạn"
```

### Bước 4: Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Bước 5: Test lại
```bash
php artisan email:test your-test-email@example.com
```

## 🔍 Troubleshooting

### Nếu vẫn lỗi:

1. **Kiểm tra App Password**
   - Đảm bảo copy đúng 16 ký tự
   - Không có khoảng trắng thừa

2. **Kiểm tra Gmail Settings**
   - Vào Gmail → Settings → Accounts and Import
   - Đảm bảo "Less secure app access" được bật (nếu có)

3. **Thử port khác**
   ```env
   MAIL_PORT=465
   MAIL_ENCRYPTION=ssl
   ```

4. **Kiểm tra firewall**
   - Đảm bảo port 587 hoặc 465 không bị chặn

## 🚀 Alternative: Sử dụng Mailgun (Miễn phí)

Nếu Gmail vẫn gặp vấn đề, bạn có thể dùng Mailgun:

1. Đăng ký tại [Mailgun](https://www.mailgun.com/)
2. Tạo domain
3. Cấu hình:
   ```env
   MAIL_MAILER=mailgun
   MAILGUN_DOMAIN=your-domain.mailgun.org
   MAILGUN_SECRET=your-mailgun-secret
   ```

## 📧 Test với đơn hàng thực tế

Sau khi cấu hình thành công, test với đơn hàng có sẵn:

```bash
# Tìm ID đơn hàng trong database
php artisan tinker
>>> App\Models\Order::first()->id

# Test với đơn hàng thực tế
php artisan email:test your-email@example.com --order-id=1
```

## ✅ Kết quả mong đợi

Khi thành công, bạn sẽ thấy:
```
✅ Email test đã được gửi thành công!
```

hoặc

```
✅ Email xác nhận đơn hàng đã được gửi thành công!
``` 