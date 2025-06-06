@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<main>
  @if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
  @endif

    <div class="max-w-8xl mx-auto px-10 py-10">
        <!-- User Header -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-3xl text-gray-400">
                <i class="fas fa-user"></i>
            </div>
            <div class="flex-1 space-y-2">
               @php
                    $user = Auth::user();
                @endphp

                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <div class="text-gray-600 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-gray-500"></i>
                    {{ $user->address ?? 'Chưa cập nhật địa chỉ' }}
                </div>
                <div class="text-gray-600 flex items-center gap-2">
                    <i class="fas fa-phone text-gray-500"></i>
                    {{ $user->phone ?? 'Chưa cập nhật số điện thoại' }}
                </div>
                <div class="text-gray-600 flex items-center gap-2">
                    <i class="fas fa-envelope text-gray-500"></i>
                    {{ $user->email }}
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto mt-4 md:mt-0">
                <div class="bg-green-100 text-green-800 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold" id="stat-all">{{ $orderStats['all'] }}</div>
                    <div class="text-base">Tổng đơn</div>
                </div>
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold" id="stat-pending">{{ $orderStats['pending'] }}</div>
                    <div class="text-base">Chờ xác nhận</div>
                </div>
                <div class="bg-blue-100 text-blue-800 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold" id="stat-processing">{{ $orderStats['processing'] }}</div>
                    <div class="text-base">Đang xử lý</div>
                </div>
                <div class="bg-purple-100 text-purple-800 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold" id="stat-completed">{{ $orderStats['completed'] }}</div>
                    <div class="text-base">Đã hoàn thành</div>
                </div>
                <div class="bg-red-100 text-red-800 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold" id="stat-cancelled">{{ $orderStats['cancelled'] }}</div>
                    <div class="text-base">Đã hủy</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <!-- Sidebar -->
            <div class="bg-white rounded-lg shadow p-4 space-y-4">
                <h3 class="text-lg font-semibold">Sản phẩm đã đặt</h3>
                <ul id="order_status" class="space-y-2 text-gray-700">
                    <li>
                        <button class="order-tab active-tab text-lg font-medium text-green-600 hover:underline" data-status="all">
                            <h1>Tất cả</h1>
                        </button>
                    </li>
                    <li>
                        <button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="pending">
                            <h1>Chờ xác nhận</h1>
                        </button>
                    </li>
                    <li>
                        <button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="processing">
                            <h1>Đang xử lý</h1>
                        </button>
                    </li>
                    <li>
                        <button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="completed">
                            <h1>Đã hoàn thành</h1>
                        </button>
                    </li>
                    <li>
                        <button class="order-tab text-lg font-medium text-gray-600 hover:underline" data-status="cancelled">
                            <h1>Đã hủy</h1>
                        </button>
                    </li>
                </ul>
            </div>
              
            <!-- Order Filter & Product Display -->
            <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
                <!-- Product List -->
             <form id="bulk-cancel-form" action="{{ route('client.order.cancelMultiple') }}" method="POST">
    @csrf
    @method('PATCH')

    <div id="product-list" class="grid grid-cols-1 gap-6 text-gray-700">
        @foreach($orders as $item)
            @php
                $status = $item->order->status;
                $payStatus = $item->order->pay_status;
                $badgeColor = match($status) {
                    'completed' => 'bg-green-100 text-green-700',
                    'processing' => 'bg-yellow-100 text-yellow-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
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

            <div class="product-item flex border p-4 rounded-lg items-center gap-4 bg-white shadow-sm" data-status="{{ $status }}" data-pay-status="{{ $payStatus }}">
                <input type="checkbox" name="order_ids[]" value="{{ $item->order_id }}"
                    class="mt-2 h-5 w-5 text-red-500 focus:ring-red-500"
                    {{ $status !== 'pending' ? 'disabled' : '' }}>
                
                <img src="{{ $image }}" alt="{{ $item->product_name }}" class="w-32 h-32 rounded object-cover">

                <div class="flex-1 space-y-1">
                    <div class="flex justify-between items-center">
                        <h1 class="font-semibold text-lg">{{ $item->product_name }}</h1>
                        <span class="status-badge inline-block {{ $badgeColor }} text-sm font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <p>Số lượng: {{ $item->quantity }}</p>
                    <p>Giá: {{ number_format($item->product_price, 0, ',', '.') }}đ</p>
                    <p><strong>Tổng giá:</strong> {{ number_format($item->total, 0, ',', '.') }}đ</p>
                    <p>Size: {{ $item->size->size ?? 'Không có' }}</p>
                    <p>Topping: {{ !empty($toppingNames) ? implode(', ', $toppingNames) : 'Không có' }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <a href="{{ route('client.product.detail', $item->product_id) }}"
                           class="px-4 py-1 bg-green-500 text-white rounded hover:bg-green-600">Mua lại</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 text-right">
        <button type="submit"
                onclick="return confirm('Bạn có chắc muốn hủy tất cả các đơn đã chọn?')"
                class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 disabled:opacity-50"
                id="bulk-cancel-btn">
            Hủy các đơn đã chọn
        </button>
    </div>
</form>

            </div>
        </div>
    </div>
</main>

<script>
    // Tab switching logic
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

                if (selectedStatus === 'all' || itemStatus === selectedStatus) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Cancel order logic
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-id');
            if (!confirm('Bạn có chắc muốn hủy đơn hàng này không?')) return;

            const url = "{{ route('client.order.cancel', ['id' => ':id']) }}".replace(':id', orderId);

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ _method: 'PATCH' })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    // Update the order item status in DOM
                    const orderItem = button.closest('.product-item');
                    orderItem.setAttribute('data-status', 'cancelled');
                    const badge = orderItem.querySelector('.status-badge');
                    badge.textContent = 'Cancelled';
                    badge.className = 'status-badge inline-block bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full';

                    // Remove cancel button
                    button.remove();

                    // Update order stats
                    const statAll = document.getElementById('stat-all');
                    const statPending = document.getElementById('stat-pending');
                    const statCancelled = document.getElementById('stat-cancelled');

                    statPending.textContent = parseInt(statPending.textContent) - 1;
                    statCancelled.textContent = parseInt(statCancelled.textContent) + 1;

                    // If current tab is not 'all' or 'cancelled', hide the item
                    const activeTab = document.querySelector('.order-tab.active-tab').getAttribute('data-status');
                    if (activeTab !== 'all' && activeTab !== 'cancelled') {
                        orderItem.style.display = 'none';
                    }
                } else {
                    alert(data.message || 'Hủy đơn hàng thất bại.');
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        });
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
@endsection