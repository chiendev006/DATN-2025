@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<main>
    <div class="container mx-auto px-6 py-10">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tra Cứu Đơn Hàng</h2>
            <p class="text-center text-gray-600 mb-6">Nhập số điện thoại của bạn để xem lịch sử mua hàng.</p>

            <form action="{{ route('order.search') }}" method="GET" class="max-w-md mx-auto">
                <div class="flex items-center rounded-lg overflow-hidden">
                    <input style='margin-top:29px'
                        type="tel"
                        name="phone"
                        class="w-full px-4 py-2 text-gray-700 focus:outline-none"
                        placeholder="Nhập số điện thoại..."
                        value="{{ $phone ?? '' }}"
                        required>
                    <button style="margin-left:10px;margin-top:0px; width:150px ; padding: 12px; border-radius: 30px;" type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
                        Tìm kiếm
                    </button>
                </div>
                @error('phone')
                    <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                @enderror
            </form>
        </div>

        @if (isset($phone) && $orders->isNotEmpty())
            <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
                <div class="mb-4">
                    <p><strong>Số điện thoại tra cứu:</strong> <span id="displayPhone">{{ $phone }}</span></p>
                    <h3>
                        Đây là thông tin đơn hàng liên quan đến số điện thoại này.
                    </h3>
                </div>

                <div class="ml-auto grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto mt-4 md:mt-0">
                    <div class="bg-green-100 text-green-800 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold" id="stat-all">{{ $orderStats['all'] ?? 0 }}</div>
                        <div class="text-base">Tổng đơn</div>
                    </div>
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold" id="stat-pending">{{ $orderStats['pending'] ?? 0 }}</div>
                        <div class="text-base">Chờ xác nhận</div>
                    </div>
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold" id="stat-processing">{{ $orderStats['processing'] ?? 0 }}</div>
                        <div class="text-base">Đang xử lý</div>
                    </div>
                    <div class="bg-purple-100 text-purple-800 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold" id="stat-completed">{{ $orderStats['completed'] ?? 0 }}</div>
                        <div class="text-base">Đã hoàn thành</div>
                    </div>
                    <div class="bg-red-100 text-red-800 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold" id="stat-cancelled">{{ $orderStats['cancelled'] ?? 0 }}</div>
                        <div class="text-base">Đã hủy</div>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($phone))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-white rounded-lg shadow p-4 space-y-4">
                    <h3 class="text-lg font-semibold">Trạng thái đơn hàng</h3>
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

                <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
                    @if ($orders->isNotEmpty())
                        @foreach($orders as $order)
                            @php
                                $status = $order->status;
                                $badgeColor = match($status) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'processing' => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <div class="product-item flex flex-col border p-4 rounded-lg bg-white shadow-sm gap-4 mb-4" data-status="{{ $status }}" data-pay-status="{{ $order->pay_status }}">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold">Mã đơn hàng: {{ $order->id }}</h2>
                                    <span class="status-badge inline-block {{ $badgeColor }} text-sm font-semibold px-3 py-2 rounded-full">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>

                                @if ($status === 'cancelled' && $order->cancel_reason)
                                    <p class="text-sm text-red-700 mt-2"><strong>Lý do hủy:</strong> {{ $order->cancel_reason }}</p>
                                @endif

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

                                <div class="mt-4 pt-4 border-t">
                                    <h2 class="text-right ">Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</h2>
                                    <h2 class="text-right ">Giảm giá mã: -{{ number_format($order->coupon_total_discount, 0, ',', '.') }}đ</h2>
                                    <h2 class="text-right font-bold text-xl text-red-600">Tổng cộng: {{ number_format($order->total, 0, ',', '.') }}đ</h2>
                                </div>

                                <div class="mt-4 flex justify-end gap-2">
                                    @if($status === 'completed' || $status === 'cancelled')
                                    <form method="POST" action="{{ route('order.search.reorder', $order->id) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                            Mua lại
                                        </button>
                                    </form>
                                    @endif

                                    @if($status === 'pending')
                                    <button type="button" class="cancel-order bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                                data-id="{{ $order->id }}">
                                        Hủy đơn
                                    </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow" role="alert">
                            <p class="font-bold">Không tìm thấy kết quả</p>
                            <p>Không có đơn hàng nào được tìm thấy với số điện thoại <strong>{{ $phone }}</strong>.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
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
                    item.style.display = (selectedStatus === 'all' || itemStatus === selectedStatus) ? 'flex' : 'none';
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
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
@endsection