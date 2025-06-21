<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Bạn có thể thêm logic báo cáo lỗi tại đây, ví dụ: gửi đến Sentry, Bugsnag.
            // Điều này sẽ không ảnh hưởng đến việc hiển thị trang lỗi cho người dùng.
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // --- Xử lý lỗi 404 cho khu vực Admin ---
        // Nếu URL yêu cầu bắt đầu bằng '/admin' VÀ lỗi là loại 404 (route không tồn tại
        // hoặc tài nguyên trong DB không tìm thấy).
        if ($request->is('admin/*') && ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException)) {
            return response()->view('admin.errors404admin', [], 404);
        }

        // --- Xử lý lỗi 404 cho khu vực Client ---
        // Nếu lỗi là loại 404 (route không tồn tại HOẶC ID sản phẩm/blog/etc. không tìm thấy trong DB)
        // và yêu cầu KHÔNG phải dành cho khu vực admin.
        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            return response()->view('client.errors404', [], 404);
        }

        // --- Xử lý các loại lỗi khác ---
        // Đối với tất cả các loại ngoại lệ (exception) khác (ví dụ: lỗi server 500, lỗi xác thực, v.v.),
        // chúng ta sẽ sử dụng trình xử lý lỗi mặc định của Laravel.
        return parent::render($request, $exception);
    }
}
