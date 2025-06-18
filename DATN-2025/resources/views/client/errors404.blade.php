@extends('layout2')

@section('main')
<div class="error-page flex flex-col items-center justify-center min-h-screen bg-gray-100 text-center">
    <h1 class="text-9xl font-extrabold text-red-500 animate-pulse">404</h1>
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-4"> Không có trang nào phù hợp với yêu cầu của bạn!</h2>
    <p class="text-lg text-gray-600 mt-2 mb-8">Có vẻ bạn đã lạc đường rồi. Trang này không tồn tại hoặc đã bị xóa.</p>
    <a style="color:black" href="{{ url('/') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
        Quay về trang chủ
    </a>
</div>

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
@endsection