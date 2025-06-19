@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<main>
    <div class="container mx-auto px-6 py-10">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 style="    margin-top: 120px;" class="text-2xl font-bold mb-4 text-center">Tra cứu đơn hàng</h2>
            <p class="text-center text-gray-600 mb-6">Nhập số điện thoại của bạn để xem lịch sử mua hàng.</p>

            <form action="{{ route('order.search') }}" method="GET" class="max-w-md mx-auto">
                <div class="flex items-center rounded-lg overflow-hidden">
                    <input style='margin-top:29px'
                        type="tel"
                        name="phone"
                        class="w-full px-4 py-2 text-gray-700 focus:outline-none"
                        placeholder="Nhập số điện thoại..."
                        value="{{ $phone ?? '' }}">
                    <button style="margin-left:10px;margin-top:0px; width:150px ; padding: 12px; border-radius: 30px;" type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
                        Tìm kiếm
                    </button>
                </div>
                @error('phone')
                    <h3 class="text-red-500 text-sm mt-2 text-center">{{ $message }}</h3>
                @enderror
            </form>
        </div>

        @if (isset($phone) && $orders->isNotEmpty())
          <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">


            <!-- Stats Cards -->
            <div width="100%" style=" " class="lg:col-span-2">
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
        @endif

        @if (isset($phone))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
              <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-36">
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
                <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
                    @if ($orders->isNotEmpty())
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-shopping-bag mr-3 text-blue-500"></i>
                                Danh sách đơn hàng
                            </h3>
                            <div class="space-y-6">
                                @foreach($orders as $order)
                                    @php
                                        $status = $order->status;
                                        $payStatus = $order->pay_status;
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
                                                            <h4 class="text-lg font-bold text-gray-800">Đơn hàng #{{ $order->id }}</h4>
                                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
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
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach($order->orderDetails as $item)
                                                @php
                                                    $image = $item->product && $item->product->image
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
                                                <div class="flex gap-4 border rounded p-3">
                                                    <img src="{{ $image }}" alt="{{ $item->product_name }}" class="w-24 h-24 rounded object-cover">
                                                    <div class="flex-1 space-y-1">
                                                        <h3 class="font-semibold">{{ $item->product_name }}</h3>
                                                        <p>Số lượng: {{ $item->quantity }}</p>
                                                        <p>Giá: {{ number_format($item->product_price, 0, ',', '.') }}đ</p>
                                                        <p>Tổng: {{ number_format($item->total, 0, ',', '.') }}đ</p>
                                                        <p>Size: {{ $item->size->size ?? 'Không có' }}</p>
                                                        <p>Topping: {{ !empty($toppingNames) ? implode(', ', $toppingNames) : 'Không có' }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Order Summary -->
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
                                        <!-- Action Buttons -->
                                        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                                            <div class="flex flex-wrap gap-3 justify-end">
                                                <button type="button" class="view-details inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                                                        data-id="{{ $order->id }}">
                                                    <i class="fas fa-eye"></i>
                                                    Chi tiết đơn hàng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow" role="alert">
                            <p class="font-bold">Không tìm thấy kết quả</p>
                            <p>Không có đơn hàng nào được tìm thấy với số điện thoại <strong>{{ $phone }}</strong>.</p>
                        </div>
                    @endif
                </div>
        </div>
        @endif

    <!-- Modal Chi tiết đơn hàng -->
<div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999] flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Chi tiết đơn hàng</h2>
                <button id="closeOrderDetailModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold mb-3">Thông tin khách hàng</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Họ tên:</strong> <span id="detail-name"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="detail-phone"></span></p>
                        <p><strong>Email:</strong> <span id="detail-email"></span></p>
                    </div>
                    <div>
                        <p><strong>Địa chỉ:</strong> <span id="detail-address"></span></p>
                        <p><strong>Phương thức thanh toán:</strong> <span id="detail-payment"></span></p>
                        <p><strong>Trạng thái thanh toán:</strong> <span id="detail-pay-status"></span></p>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">Chi tiết sản phẩm</h3>
                <div id="detail-products" class="space-y-4">
                    <!-- Sản phẩm sẽ được thêm vào đây bằng JavaScript -->
                </div>
            </div>

            <!-- Tổng tiền -->
            <div class="border-t pt-4">
                <div class="text-right space-y-2">
                    <p>Phí vận chuyển: <span id="detail-shipping-fee"></span></p>
                    <p>Giảm giá: <span id="detail-discount"></span></p>
                    <p class="text-xl font-bold text-red-600">Tổng cộng: <span id="detail-total"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-red-600">Xác nhận hủy đơn</h2>
            <p class="mb-2 text-gray-700">Vui lòng nhập lý do hủy đơn hàng:</p>
            <textarea id="cancelReasonInput" rows="3" class="w-full border rounded p-2 mb-4" placeholder="Nhập lý do..."></textarea>
            <div class="flex justify-end gap-2">
                <button id="cancelModalClose" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Hủy</button>
                <button id="confirmCancelBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Xác nhận hủy</button>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.order-tab').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.order-tab').forEach(btn => {
                    btn.classList.remove('text-green-600', 'active-tab');
                    btn.classList.add('text-gray-600');
                });

                this.classList.add('text-green-600', 'active-tab');
                this.classList.remove('text-gray-600');

                const selectedStatus = this.getAttribute('data-status');

                document.querySelectorAll('.product-item').forEach(item => {
                    const itemStatus = item.getAttribute('data-status');
                    item.style.display = (selectedStatus === 'all' || itemStatus === selectedStatus) ? '' : 'none';
                });
            });
        });

        document.querySelectorAll('.cancel-order').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-id');
                const checkUrl = "{{ route('order.search.checkStatus', ['id' => ':id']) }}".replace(':id', orderId);

                fetch(checkUrl)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success || data.status !== 'pending') {
                            const statusLabel = {
                                'processing': 'Đang xử lý',
                                'completed': 'Đã hoàn thành',
                                'cancelled': 'Đã hủy',
                                'delivering': 'Đang giao',
                                'not_found': 'Không tồn tại',
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

            const checkUrl = "{{ route('order.search.checkStatus', ['id' => ':id']) }}".replace(':id', orderId);
            fetch(checkUrl)
                .then(res => res.json())
                .then(data => {
                    if (!data.success || data.status !== 'pending') {
                        const statusLabel = {
                            'processing': 'Đang xử lý',
                            'completed': 'Đã hoàn thành',
                            'cancelled': 'Đã hủy',
                            'delivering': 'Đang giao',
                            'not_found': 'Không tồn tại',
                        };
                        const statusText = statusLabel[data.status] || data.status;
                        alert(`Đơn hàng này đang ở trạng thái "${statusText}". Không thể hủy.`);
                        document.getElementById('cancelModal').classList.add('hidden');
                        return;
                    }

                    const url = "{{ route('order.search.cancel', ['id' => ':id']) }}".replace(':id', orderId);

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

        // Xử lý nút xem chi tiết
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');
                const modal = document.getElementById('orderDetailModal');
                modal.classList.remove('hidden');

                // Lấy thông tin đơn hàng
                fetch(`/order-detail/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật thông tin khách hàng
                            document.getElementById('detail-name').textContent = data.order.name;
                            document.getElementById('detail-phone').textContent = data.order.phone;
                            document.getElementById('detail-email').textContent = data.order.email || 'Không có';
                            document.getElementById('detail-address').textContent = data.order.address_detail;
                            document.getElementById('detail-payment').textContent = data.order.payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản';
                            document.getElementById('detail-pay-status').textContent = data.order.pay_status === '1' ? 'Đã thanh toán' : 'Chưa thanh toán';

                            // Cập nhật thông tin sản phẩm
                            const productsContainer = document.getElementById('detail-products');
                            productsContainer.innerHTML = '';
                            data.orderDetails.forEach(item => {
                                const productHtml = `
                                    <div class="flex gap-4 border rounded p-4">
                                        <div class="w-24 h-24">
                                            <img src="${item.product_image || 'https://via.placeholder.com/150x150.png?text=Product'}"
                                                 alt="${item.product_name}"
                                                 class="w-full h-full object-cover rounded">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold">${item.product_name}</h4>
                                            <p>Số lượng: ${item.quantity}</p>
                                            <p>Giá: ${new Intl.NumberFormat('vi-VN').format(item.product_price)}đ</p>
                                            <p>Size: ${item.size_name || 'Không có'}</p>
                                            <p>Topping: ${item.topping_names || 'Không có'}</p>
                                            <p class="font-semibold">Tổng: ${new Intl.NumberFormat('vi-VN').format(item.total)}đ</p>
                                        </div>
                                    </div>
                                `;
                                productsContainer.innerHTML += productHtml;
                            });

                            // Cập nhật tổng tiền
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

        // Đóng modal chi tiết
        document.getElementById('closeOrderDetailModal').addEventListener('click', () => {
            document.getElementById('orderDetailModal').classList.add('hidden');
        });
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
@endsection
