<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error-page {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
    <div class="error-page flex flex-col items-center justify-center min-h-screen bg-gray-100 text-center">
        <h1 class="text-9xl font-extrabold text-red-500 animate-pulse">404</h1>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-4">Không có trang nào phù hợp với yêu cầu của bạn!</h2>
        <p class="text-lg text-gray-600 mt-2 mb-8">Có vẻ bạn đã lạc đường rồi. Trang này không tồn tại hoặc đã bị xóa.</p>
        <a href="{{ route("home.index") }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
            Quay về trang chủ
        </a>
    </div>
</body>
</html>