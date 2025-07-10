@echo off
echo ========================================
echo    TEST EMAIL CONFIGURATION
echo ========================================
echo.

echo 1. Checking email configuration...
php artisan email:check
echo.

echo 2. Clearing cache...
php artisan config:clear
php artisan cache:clear
echo.

echo 3. Testing email...
set /p EMAIL="Enter your test email: "
php artisan email:test %EMAIL%
echo.

echo 4. Test completed!
pause 