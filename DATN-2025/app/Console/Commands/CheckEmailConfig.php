<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CheckEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra cấu hình email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Kiểm tra cấu hình Email...');
        $this->newLine();

        // Kiểm tra các cấu hình cần thiết
        $configs = [
            'MAIL_MAILER' => config('mail.default'),
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'MAIL_PORT' => config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
            'MAIL_PASSWORD' => config('mail.mailers.smtp.password'),
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
            'MAIL_FROM_ADDRESS' => config('mail.from.address'),
            'MAIL_FROM_NAME' => config('mail.from.name'),
        ];

        $this->table(
            ['Cấu hình', 'Giá trị', 'Trạng thái'],
            collect($configs)->map(function ($value, $key) {
                $status = $value ? '✅' : '❌';
                $displayValue = $key === 'MAIL_PASSWORD' ? 
                    (strlen($value) > 0 ? str_repeat('*', min(16, strlen($value))) : 'Chưa cấu hình') : 
                    ($value ?: 'Chưa cấu hình');
                
                return [$key, $displayValue, $status];
            })->toArray()
        );

        $this->newLine();

        // Kiểm tra cấu hình Gmail
        if (config('mail.mailers.smtp.host') === 'smtp.gmail.com') {
            $this->info('📧 Cấu hình Gmail được phát hiện');
            
            if (!config('mail.mailers.smtp.username') || !config('mail.mailers.smtp.password')) {
                $this->error('❌ Thiếu thông tin đăng nhập Gmail');
                $this->line('💡 Hãy cấu hình MAIL_USERNAME và MAIL_PASSWORD trong file .env');
            } else {
                $this->info('✅ Thông tin đăng nhập Gmail đã được cấu hình');
            }

            if (config('mail.mailers.smtp.port') !== 587 && config('mail.mailers.smtp.port') !== 465) {
                $this->warn('⚠️  Port Gmail nên là 587 hoặc 465');
            }

            if (config('mail.mailers.smtp.encryption') !== 'tls' && config('mail.mailers.smtp.encryption') !== 'ssl') {
                $this->warn('⚠️  Encryption Gmail nên là tls hoặc ssl');
            }
        }

        // Kiểm tra cấu hình Mailgun
        if (config('mail.default') === 'mailgun') {
            $this->info('📧 Cấu hình Mailgun được phát hiện');
            
            if (!config('services.mailgun.domain') || !config('services.mailgun.secret')) {
                $this->error('❌ Thiếu thông tin Mailgun');
                $this->line('💡 Hãy cấu hình MAILGUN_DOMAIN và MAILGUN_SECRET trong file .env');
            } else {
                $this->info('✅ Thông tin Mailgun đã được cấu hình');
            }
        }

        $this->newLine();
        $this->info('💡 Để test email, chạy lệnh:');
        $this->line('   php artisan email:test your-email@example.com');
        
        $this->newLine();
        $this->info('📖 Xem hướng dẫn chi tiết tại: GMAIL_SETUP.md');
    }
} 