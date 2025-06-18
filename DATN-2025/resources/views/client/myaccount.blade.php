@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<main class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-8xl">

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Tài khoản của tôi</h1>
            <p class="text-gray-600">Quản lý thông tin cá nhân và đơn hàng</p>
        </div>

        <!-- Profile and Stats Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <img id="displayAvatar"
                                 src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-avatar.png') }}"
                                 class="w-32 h-32 object-cover rounded-full border-4 border-gray-200 shadow-lg {{ $user->image ? '' : 'hidden' }}"
                                 alt="Avatar">
                            <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 cursor-pointer hover:bg-blue-600 transition-colors"
                                 onclick="document.getElementById('editProfileBtn').click()">
                                <i class="fas fa-camera text-white text-sm"></i>
                            </div>
                        </div>
                        <button id="editProfileBtn" class="mt-4 text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-user text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Họ tên</p>
                                <p class="font-semibold text-gray-800" id="displayName">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-phone text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Số điện thoại</p>
                                <p class="font-semibold text-gray-800" id="displayPhone">{{ $user->phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-map-marker-alt text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Địa chỉ</p>
                                <p class="font-semibold text-gray-800" id="displayAddress">{{ $user->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6 rounded-xl shadow-sm text-center transform hover:scale-105 transition-transform">
                        <div class="text-3xl font-bold mb-2" id="stat-all">{{ $orderStats['all'] }}</div>
                        <div class="text-sm opacity-90">Tổng đơn</div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white p-6 rounded-xl shadow-sm text-center transform hover:scale-105 transition-transform">
                        <div class="text-3xl font-bold mb-2" id="stat-pending">{{ $orderStats['pending'] }}</div>
                        <div class="text-sm opacity-90">Chờ xác nhận</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-6 rounded-xl shadow-sm text-center transform hover:scale-105 transition-transform">
                        <div class="text-3xl font-bold mb-2" id="stat-processing">{{ $orderStats['processing'] }}</div>
                        <div class="text-sm opacity-90">Đang xử lý</div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 text-white p-6 rounded-xl shadow-sm text-center transform hover:scale-105 transition-transform">
                        <div class="text-3xl font-bold mb-2" id="stat-completed">{{ $orderStats['completed'] }}</div>
                        <div class="text-sm opacity-90">Đã hoàn thành</div>
                    </div>

                    <div class="bg-gradient-to-br from-red-400 to-red-600 text-white p-6 rounded-xl shadow-sm text-center transform hover:scale-105 transition-transform">
                        <div class="text-3xl font-bold mb-2" id="stat-cancelled">{{ $orderStats['cancelled'] }}</div>
                        <div class="text-sm opacity-90">Đã hủy</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Filter Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-filter mr-3 text-blue-500"></i>
                        Lọc đơn hàng
                    </h3>

                    <div class="space-y-3">
                        <button class="order-tab active-tab w-full text-left p-4 rounded-lg bg-blue-50 border-2 border-blue-200 text-blue-700 font-semibold transition-all hover:bg-blue-100" data-status="all">
                            <div class="flex items-center justify-between">
                                <span>Tất cả</span>
                                <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs">{{ $orderStats['all'] }}</span>
                            </div>
                        </button>

                        <button class="order-tab w-full text-left p-4 rounded-lg bg-gray-50 border-2 border-gray-200 text-gray-600 font-semibold transition-all hover:bg-gray-100" data-status="pending">
                            <div class="flex items-center justify-between">
                                <span>Chờ xác nhận</span>
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">{{ $orderStats['pending'] }}</span>
                            </div>
                        </button>

                        <button class="order-tab w-full text-left p-4 rounded-lg bg-gray-50 border-2 border-gray-200 text-gray-600 font-semibold transition-all hover:bg-gray-100" data-status="processing">
                            <div class="flex items-center justify-between">
                                <span>Đang xử lý</span>
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">{{ $orderStats['processing'] }}</span>
                            </div>
                        </button>

                        <button class="order-tab w-full text-left p-4 rounded-lg bg-gray-50 border-2 border-gray-200 text-gray-600 font-semibold transition-all hover:bg-gray-100" data-status="completed">
                            <div class="flex items-center justify-between">
                                <span>Đã hoàn thành</span>
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">{{ $orderStats['completed'] }}</span>
                            </div>
                        </button>

                        <button class="order-tab w-full text-left p-4 rounded-lg bg-gray-50 border-2 border-gray-200 text-gray-600 font-semibold transition-all hover:bg-gray-100" data-status="cancelled">
                            <div class="flex items-center justify-between">
                                <span>Đã hủy</span>
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">{{ $orderStats['cancelled'] }}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-shopping-bag mr-3 text-blue-500"></i>
                        Danh sách đơn hàng
                    </h3>

                    <div class="space-y-6">
                        @foreach($orders as $orderId => $items)
                            @php
                                $firstItem = $items->first();
                                $order = $firstItem ? $firstItem->order : null;
                                $status = $order ? $order->status : 'unknown';
                                $payStatus = $order ? $order->pay_status : 'unknown';

                                $statusConfig = match($status) {
                                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'fas fa-check-circle'],
                                    'processing' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'icon' => 'fas fa-clock'],
                                    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fas fa-times-circle'],
                                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => 'fas fa-info-circle'],
                                };
                            @endphp

                            <div class="product-item border-2 border-gray-200 rounded-xl bg-white shadow-sm hover:shadow-md transition-shadow" data-status="{{ $status }}" data-pay-status="{{ $payStatus }}">

                                <!-- Order Header -->
                                <div class="p-6 border-b border-gray-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-receipt text-2xl text-blue-500"></i>
                                                <div>
                                                    <h4 class="text-lg font-bold text-gray-800">Đơn hàng #{{ $orderId }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $order ? $order->created_at->format('d/m/Y H:i') : '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <span class="status-badge inline-flex items-center gap-2 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} px-4 py-2 rounded-full text-sm font-semibold">
                                                <i class="{{ $statusConfig['icon'] }}"></i>
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>
                                    </div>

                                    @if ($status === 'cancelled' && $order && $order->cancel_reason)
                                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-sm text-red-700">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <strong>Lý do hủy:</strong> {{ $order->cancel_reason }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Products -->
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        @foreach($items as $item)
                                            @php
                                                $image = $item->product->image
                                                    ? asset('storage/uploads/' . $item->product->image)
                                                    : 'https://via.placeholder.com/150x150.png?text=Product';
                                                $toppingNames = [];
                                                if (!empty($item->topping_id)) {
                                                    $toppingIds = explode(',', trim($item->topping_id));
                                                    foreach ($toppingIds as $id) {
                                                        $id = (int)$id;
                                                        if (isset($toppings[$id])) {
                                                            $toppingNames[] = $toppings[$id]->topping;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                <img src="{{ $image }}" alt="{{ $item->product_name }}" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="font-semibold text-gray-800 truncate">{{ $item->product_name }}</h5>
                                                    <div class="text-sm text-gray-600 space-y-1 mt-2">
                                                        <p><i class="fas fa-hashtag mr-1"></i>Số lượng: {{ $item->quantity }}</p>
                                                        <p><i class="fas fa-tag mr-1"></i>Giá: {{ number_format($item->product_price, 0, ',', '.') }}đ</p>
                                                        <p><i class="fas fa-ruler mr-1"></i>Size: {{ $item->size->size ?? 'Không có' }}</p>
                                                        <p><i class="fas fa-plus mr-1"></i>Topping: {{ !empty($toppingNames) ? implode(', ', $toppingNames) : 'Không có' }}</p>
                                                        <p class="font-semibold text-blue-600"><i class="fas fa-calculator mr-1"></i>Tổng: {{ number_format($item->total, 0, ',', '.') }}đ</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Order Summary -->
                                    @if($order)
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <div class="space-y-2 text-right">
                                                <p class="text-gray-700">
                                                    <i class="fas fa-truck mr-2"></i>
                                                    Phí vận chuyển: <span class="font-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                                                </p>
                                                <p class="text-gray-700">
                                                    <i class="fas fa-tag mr-2"></i>
                                                    Giảm giá mã: <span class="font-semibold text-green-600">-{{ number_format($order->coupon_total_discount, 0, ',', '.') }}đ</span>
                                                </p>
                                                <p class="text-xl font-bold text-red-600 border-t border-blue-200 pt-2">
                                                    <i class="fas fa-calculator mr-2"></i>
                                                    Tổng cộng: {{ number_format($order->total, 0, ',', '.') }}đ
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                                    <div class="flex flex-wrap gap-3 justify-end">
                                        @if($status === 'completed' || $status === 'cancelled')
                                            <form method="POST" action="{{ route('client.order.reorder', $orderId) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                                    <i class="fas fa-redo"></i>
                                                    Mua lại
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" class="view-details inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                                                data-id="{{ $orderId }}">
                                            <i class="fas fa-eye"></i>
                                            Chi tiết đơn hàng
                                        </button>

                                        @if($status === 'pending')
                                            <button type="button" class="cancel-order inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                                                    data-id="{{ $orderId }}">
                                                <i class="fas fa-times"></i>
                                                Hủy đơn
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-lg shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-edit mr-3 text-blue-500"></i>
                Chỉnh sửa thông tin
            </h3>
        </div>

        <form id="editProfileForm" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Họ tên
                    </label>
                    <input type="text" name="name" value="{{ $user->name }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2"></i>Số điện thoại
                    </label>
                    <input type="text" name="phone" value="{{ $user->phone }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Địa chỉ
                    </label>
                    <input type="text" name="address" value="{{ $user->address }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-2"></i>Ảnh đại diện
                    </label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           id="previewImageInput">
                    <div class="mt-3 text-center">
                        <img id="previewImage" src="{{ $user->image ? asset('storage/' . $user->image) : '' }}"
                             class="w-24 h-24 object-cover rounded-full border-4 border-gray-200 {{ $user->image ? '' : 'hidden' }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" id="cancelEdit"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-times mr-2"></i>Hủy
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Lưu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Order Detail Modal -->
<div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-receipt mr-3 text-blue-500"></i>
                    Chi tiết đơn hàng
                </h2>
                <button id="closeOrderDetailModal" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Customer Information -->
            <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-blue-500"></i>
                    Thông tin khách hàng
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Họ tên</p>
                                <p class="font-semibold text-gray-800" id="detail-name"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Số điện thoại</p>
                                <p class="font-semibold text-gray-800" id="detail-phone"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-800" id="detail-email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Địa chỉ</p>
                                <p class="font-semibold text-gray-800" id="detail-address"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Phương thức thanh toán</p>
                                <p class="font-semibold text-gray-800" id="detail-payment"></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-gray-500 w-5 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Trạng thái thanh toán</p>
                                <p class="font-semibold text-gray-800" id="detail-pay-status"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shopping-bag mr-3 text-blue-500"></i>
                    Chi tiết sản phẩm
                </h3>
                <div id="detail-products" class="space-y-4">
                    <!-- Products will be added here by JavaScript -->
                </div>
            </div>

            <!-- Total Summary -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                <div class="text-right space-y-3">
                    <p class="text-gray-700">
                        <i class="fas fa-truck mr-2"></i>
                        Phí vận chuyển: <span id="detail-shipping-fee" class="font-semibold"></span>
                    </p>
                    <p class="text-gray-700">
                        <i class="fas fa-tag mr-2"></i>
                        Giảm giá: <span id="detail-discount" class="font-semibold text-green-600"></span>
                    </p>
                    <p class="text-2xl font-bold text-red-600 border-t border-green-200 pt-3">
                        <i class="fas fa-calculator mr-2"></i>
                        Tổng cộng: <span id="detail-total"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md shadow-2xl">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-red-600 flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                Xác nhận hủy đơn
            </h2>
        </div>

        <div class="p-6">
            <p class="mb-4 text-gray-700">Vui lòng nhập lý do hủy đơn hàng:</p>
            <textarea id="cancelReasonInput" rows="4"
                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Nhập lý do hủy đơn..."></textarea>
        </div>

        <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
            <button id="cancelModalClose"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                <i class="fas fa-times mr-2"></i>Hủy
            </button>
            <button id="confirmCancelBtn"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-check mr-2"></i>Xác nhận hủy
            </button>
        </div>
    </div>
</div>

<script>
    // Order Tab Filtering
    document.querySelectorAll('.order-tab').forEach(button => {
        button.addEventListener('click', function () {
            // Update active tab styling
            document.querySelectorAll('.order-tab').forEach(btn => {
                btn.classList.remove('active-tab', 'bg-blue-50', 'border-blue-200', 'text-blue-700');
                btn.classList.add('bg-gray-50', 'border-gray-200', 'text-gray-600');
            });

            this.classList.add('active-tab', 'bg-blue-50', 'border-blue-200', 'text-blue-700');
            this.classList.remove('bg-gray-50', 'border-gray-200', 'text-gray-600');

            // Filter products
            const selectedStatus = this.getAttribute('data-status');
            document.querySelectorAll('.product-item').forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                item.style.display = (selectedStatus === 'all' || itemStatus === selectedStatus) ? 'block' : 'none';
            });
        });
    });

    // Cancel Order Functionality
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-id');

            fetch(`/check-order-status/${orderId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success || data.status !== 'pending') {
                        const statusLabel = {
                            'processing': 'Đang xử lý',
                            'completed': 'Đã hoàn thành',
                            'cancelled': 'Đã hủy',
                            'not_found': 'Không tồn tại',
                            'delivering': 'Đang giao'
                        };
                        const statusText = statusLabel[data.status] || data.status;

                        alert(`Đơn hàng này đã được cập nhật sang trạng thái "${statusText}". Không thể hủy được!!!`);
                        location.reload();
                        return;
                    }

                    document.getElementById('cancelModal').classList.remove('hidden');
                    document.getElementById('cancelReasonInput').value = '';
                    document.getElementById('confirmCancelBtn').setAttribute('data-id', orderId);
                })
                .catch(error => {
                    console.error('Lỗi kiểm tra trạng thái:', error);
                    alert('Không thể kiểm tra trạng thái đơn hàng.');
                });
        });
    });

    // Cancel Modal Controls
    document.getElementById('cancelModalClose').addEventListener('click', () => {
        document.getElementById('cancelModal').classList.add('hidden');
    });

    document.getElementById('confirmCancelBtn').addEventListener('click', () => {
        const reason = document.getElementById('cancelReasonInput').value.trim();
        const orderId = document.getElementById('confirmCancelBtn').getAttribute('data-id');

        if (!reason) {
            alert("Vui lòng nhập lý do hủy đơn.");
            return;
        }

        const checkUrl = `/check-order-status/${orderId}`;
        fetch(checkUrl)
            .then(res => res.json())
            .then(data => {
                if (!data.success || data.status !== 'pending') {
                    const statusLabel = {
                        'processing': 'Đang xử lý',
                        'completed': 'Đã hoàn thành',
                        'cancelled': 'Đã hủy',
                        'not_found': 'Không tồn tại',
                        'delivering': 'Đang giao'
                    };

                    const statusText = statusLabel[data.status] || data.status;
                    alert(`Đơn hàng này đang ở trạng thái "${statusText}". Không thể hủy.`);
                    document.getElementById('cancelModal').classList.add('hidden');
                    return;
                }

                const url = "{{ route('client.order.cancel', ['id' => ':id']) }}".replace(':id', orderId);

                return fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'PATCH', cancel_reason: reason })
                });
            })
            .then(response => response?.json?.())
            .then(data => {
                if (data?.success) {
                    alert(data.message);
                    document.getElementById('cancelModal').classList.add('hidden');
                    location.reload();
                } else if (data) {
                    alert(data.message || 'Hủy đơn hàng thất bại.');
                    document.getElementById('cancelModal').classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra: ' + error.message);
                document.getElementById('cancelModal').classList.add('hidden');
            });
    });

    // Profile Edit Functionality
    document.getElementById('editProfileBtn').addEventListener('click', function () {
        document.getElementById('editProfileModal').classList.remove('hidden');
    });

    document.getElementById('cancelEdit').addEventListener('click', function () {
        document.getElementById('editProfileModal').classList.add('hidden');
    });

    document.getElementById('previewImageInput').addEventListener('change', function (e) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('previewImage');
            img.src = e.target.result;
            img.classList.remove('hidden');
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    // Profile Update AJAX
    if (typeof jQuery !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#editProfileForm').on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ route('myaccount.ajax-update') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    alert(res.message);

                    $('#displayName').text(res.data.name);
                    $('#displayPhone').text(res.data.phone);
                    $('#displayAddress').text(res.data.address);

                    if (res.data.image_url) {
                        $('#displayAvatar').attr('src', res.data.image_url).removeClass('hidden');
                    }

                    $('#editProfileModal').addClass('hidden');
                },
                error: function (err) {
                    alert('Lỗi khi cập nhật thông tin.');
                    console.error(err.responseJSON);
                }
            });
        });
    }

    // Order Detail Modal
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');
                const modal = document.getElementById('orderDetailModal');
                modal.classList.remove('hidden');

                fetch(`/order-detail/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update customer information
                            document.getElementById('detail-name').textContent = data.order.name;
                            document.getElementById('detail-phone').textContent = data.order.phone;
                            document.getElementById('detail-email').textContent = data.order.email || 'Không có';
                            document.getElementById('detail-address').textContent = data.order.address_detail;
                            document.getElementById('detail-payment').textContent = data.order.payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản';
                            document.getElementById('detail-pay-status').textContent = data.order.pay_status === '1' ? 'Đã thanh toán' : 'Chưa thanh toán';

                            // Update products
                            const productsContainer = document.getElementById('detail-products');
                            productsContainer.innerHTML = '';
                            data.orderDetails.forEach(item => {
                                const productHtml = `
                                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="w-24 h-24 flex-shrink-0">
                                            <img src="${item.product_image || 'https://via.placeholder.com/150x150.png?text=Product'}"
                                                 alt="${item.product_name}"
                                                 class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-800 mb-2">${item.product_name}</h4>
                                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                                <p><i class="fas fa-hashtag mr-1"></i>Số lượng: ${item.quantity}</p>
                                                <p><i class="fas fa-tag mr-1"></i>Giá: ${new Intl.NumberFormat('vi-VN').format(item.product_price)}đ</p>
                                                <p><i class="fas fa-ruler mr-1"></i>Size: ${item.size_name || 'Không có'}</p>
                                                <p><i class="fas fa-plus mr-1"></i>Topping: ${item.topping_names || 'Không có'}</p>
                                            </div>
                                            <p class="font-semibold text-blue-600 mt-2">
                                                <i class="fas fa-calculator mr-1"></i>Tổng: ${new Intl.NumberFormat('vi-VN').format(item.total)}đ
                                            </p>
                                        </div>
                                    </div>
                                `;
                                productsContainer.innerHTML += productHtml;
                            });

                            // Update totals
                            document.getElementById('detail-shipping-fee').textContent =
                                new Intl.NumberFormat('vi-VN').format(data.order.shipping_fee) + 'đ';
                            document.getElementById('detail-discount').textContent =
                                '-' + new Intl.NumberFormat('vi-VN').format(data.order.coupon_total_discount) + 'đ';
                            document.getElementById('detail-total').textContent =
                                new Intl.NumberFormat('vi-VN').format(data.order.total) + 'đ';
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        alert('Không thể tải thông tin chi tiết đơn hàng');
                    });
            });
        });

        // Close order detail modal
        document.getElementById('closeOrderDetailModal').addEventListener('click', () => {
            document.getElementById('orderDetailModal').classList.add('hidden');
        });
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
@endsection