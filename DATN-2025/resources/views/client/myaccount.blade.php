@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<main>
<div class="container mx-auto px-6 py-10">
    <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row items-center md:items-start gap-6">

    <div class="mb-4">
        <img id="displayAvatar" src="{{ $user->image ? asset('storage/' . $user->image) : '' }}" class="mt-2 w-24 h-24 object-cover rounded-full {{ $user->image ? '' : 'hidden' }}"><br>
        <button id="editProfileBtn" class="text-blue-600 hover:underline mt-2">Chỉnh sửa thông tin</button>
        <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-xl w-full max-w-lg">
                <h3 class="text-xl font-bold mb-4">Chỉnh sửa thông tin</h3>
                <form id="editProfileForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="block">Họ tên:</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block">Số điện thoại:</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full border rounded p-2">
                    </div>
                    <div class="mb-3">
                        <label class="block">Địa chỉ:</label>
                        <input type="text" name="address" value="{{ $user->address }}" class="w-full border rounded p-2">
                    </div>
                    <div class="mb-3">
                        <label class="block">Ảnh đại diện:</label>
                        <input type="file" name="image" accept="image/*" class="w-full" id="previewImageInput">
                        <img id="previewImage" src="{{ $user->image ? asset('storage/' . $user->image) : '' }}" class="mt-2 w-24 h-24 object-cover rounded-full {{ $user->image ? '' : 'hidden' }}">
                    </div>
                    <div class="text-right">
                        <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
        <p><strong>Họ tên:</strong> <span id="displayName">{{ $user->name }}</span></p>
        <p><strong>SĐT:</strong> <span id="displayPhone">{{ $user->phone }}</span></p>
        <p><strong>Địa chỉ:</strong> <span id="displayAddress">{{ $user->address }}</span></p>
    </div>

        <div class="ml-auto grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto mt-4 md:mt-0">
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
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
        
       <div class="md:col-span-2 bg-white rounded-lg shadow p-4">

    @foreach($orders as $orderId => $items)
        @php
            $firstItem = $items->first();
            $order = $firstItem ? $firstItem->order : null;

            $status = $order ? $order->status : 'unknown'; 
            $payStatus = $order ? $order->pay_status : 'unknown'; 
            $badgeColor = match($status) {
                'completed' => 'bg-green-100 text-green-700',
                'processing' => 'bg-yellow-100 text-yellow-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };
        @endphp

        <div class="product-item flex flex-col border p-4 rounded-lg bg-white shadow-sm gap-4 mb-4" data-status="{{ $status }}" data-pay-status="{{ $payStatus }}">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Mã đơn hàng: {{ $orderId }}</h2>
                <span class="status-badge inline-block {{ $badgeColor }} text-sm font-semibold px-3 py-2 rounded-full">
                    {{ ucfirst($status) }}
                </span>
            </div>

            @if ($status === 'cancelled' && $order && $order->cancel_reason)
                <p class="text-sm text-red-700 mt-2"><strong>Lý do hủy:</strong> {{ $order->cancel_reason }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

            @if($order)
                <div class="mt-4 pt-4 border-t">
                    <h2 class="text-right text-base">Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</h2>
                    <h2 class="text-right text-base">Giảm giá mã: -{{ number_format($order->coupon_total_discount, 0, ',', '.') }}đ</h2>
                    <h2 class="text-right font-bold text-xl text-red-600">Tổng cộng: {{ number_format($order->total, 0, ',', '.') }}đ</h2>
                </div>
            @endif

            <div class="mt-4 flex justify-end gap-2">
                @if($status === 'completed' || $status === 'cancelled')
                <form method="POST" action="{{ route('client.order.reorder', $orderId) }}"> 
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Mua lại
                    </button>
                </form>
                @endif

                <button type="button" class="view-details bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        data-id="{{ $orderId }}">
                    Chi tiết đơn hàng
                </button>

                @if($status === 'pending')
                <button type="button" class="cancel-order bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                            data-id="{{ $orderId }}">
                    Hủy đơn
                </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
    </div>
</div>

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

</script>

<script>
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

    // Cần jQuery cho đoạn code ajax này
    // Đảm bảo bạn đã nhúng jQuery vào layout chính
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
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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