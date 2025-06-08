@extends('layout2')
@section('main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<main>
<div class="container mx-auto px-6 py-10">
        <!-- User Header -->
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
  @foreach($orders as $orderId => $items)
    @php
        $firstItem = $items->first(); // dùng để lấy thông tin đơn hàng chung
        $status = $firstItem->order->status;
        $payStatus = $firstItem->order->pay_status;
        $badgeColor = match($status) {
            'completed' => 'bg-green-100 text-green-700',
            'processing' => 'bg-yellow-100 text-yellow-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    @endphp

    <div class="product-item flex flex-col border p-4 rounded-lg bg-white shadow-sm gap-4" data-status="{{ $status }}" data-pay-status="{{ $payStatus }}">
       <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">Mã đơn hàng: {{ $orderId }}</h2>
            <span class="status-badge inline-block {{ $badgeColor }} text-sm font-semibold px-3 py-2 rounded-full">
                {{ ucfirst($status) }}
            </span>
        </div>

        @if ($status === 'cancelled' && $firstItem->order->cancel_reason)
            <p class="text-sm text-red-700 mt-2"><strong>Lý do hủy:</strong> {{ $firstItem->order->cancel_reason }}</p>
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

        <div class="mt-4 flex justify-end gap-2">
           <form method="POST" action="{{ route('client.order.reorder', $firstItem->order_id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Mua lại
                </button>
            </form>
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
</form>

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

                item.style.display = (selectedStatus === 'all' || itemStatus === selectedStatus) ? 'block' : 'none';
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

                    alert(`❌ Đơn hàng này đã được cập nhật sang trạng thái "${statusText}". Không thể hủy nữa.`);
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
                alert(`❌ Đơn hàng này đang ở trạng thái "${statusText}". Không thể hủy.`);
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
</script>

<script src="https://cdn.tailwindcss.com"></script>
@endsection