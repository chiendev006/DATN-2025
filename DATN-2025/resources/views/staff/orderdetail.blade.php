@extends('staff.layout')

@section('main-content')

<h2 class="text-3xl font-extrabold text-indigo-800 mb-8 flex items-center gap-2">
    Hóa đơn hôm nay
</h2>

@if ($donhangs->isEmpty())
    <div class="bg-gray-50 p-6 rounded-xl shadow text-gray-500 italic text-center border border-dashed">
        Chưa có hóa đơn nào hôm nay.
    </div>
@else
    <div class="space-y-8">
        @foreach ($donhangs as $item)
        @php
            $isWalkInCustomer = ($item->name == 'Khách lẻ') || ($item->district_name == null && $item->shipping_fee == 0);
            $statusMapping = [
                'pending' => 0,
                'processing' => 1,
                'shipping' => 2,
                'completed' => 3,
                'cancelled' => 4
            ];
            $payStatusMapping = [
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3
            ];
            if (is_numeric($item->status)) {
                $currentStatusInt = (int)$item->status;
            } else {
                $currentStatusInt = $statusMapping[$item->status] ?? 0;
            }
            if ($isWalkInCustomer) {
                if ($item->pay_status == 3 || $item->pay_status === '3') {
                    $currentPayStatus = 3; // Hoàn tiền
                } else if ($item->status === 'cancelled' || $item->status === 4) {
                    $currentPayStatus = 2; // Đã hủy
                } else {
                    $currentPayStatus = 1; // Đã thanh toán
                }
            } else {
                if ($item->status === 'completed') {
                    $currentPayStatus = 1; // Đơn online hoàn thành thì luôn là đã thanh toán
                } else if (is_numeric($item->pay_status)) {
                    $currentPayStatus = (int)$item->pay_status;
                } else {
                    $currentPayStatus = $payStatusMapping[$item->pay_status] ?? 0;
                }
            }
        @endphp
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <!-- Header hóa đơn -->
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-indigo-200">
                <div>
                    <h1 class="text-3xl font-extrabold text-indigo-700">#{{ $item->id }}</h1>
                    <h4 class="text-sm text-gray-500">Mã hóa đơn</h4>
                </div>
                <div class="text-right">
                    <div class="text-xl font-semibold text-gray-1000">{{ $item->created_at->format('H:i') }}</div>
                    <div class="text-sm text-gray-1000">{{ $item->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Bảng thông tin -->
            <div class="overflow-x-auto">
                <table style="width:100%" class="w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-indigo-100 text-indigo-800 uppercase">
                        <tr>
                            <th class="px-4 py-2 border text-center">Khách hàng</th>
                            <th class="px-4 py-2 border text-center">Số điện thoại</th>
                            <th class="px-4 py-2 border text-center">Trạng thái</th>
                            <th class="px-4 py-2 border text-center">Trạng thái thanh toán</th>
                            <th class="px-4 py-2 border text-center">Tổng tiền</th>
                            <th class="px-4 py-2 border text-center">Ghi chú</th>
                            <th class="px-4 py-2 border text-center">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="{{ $isWalkInCustomer ? 'background-color: #ffe066;' : '' }}">
                            <td class="px-4 py-2 border text-gray-700 font-medium">{{ $item->name ?? 'Khách lẻ' }}</td>
                            <td class="px-4 py-2 border">{{ $item->phone ?? 'không có' }}</td>
                            <td class="px-4 py-2 border text-center text-sm font-semibold">
                                @php
                                    $statusLabels = [
                                        0 => ['Chờ xử lý', 'orange'],
                                        1 => ['Đã xác nhận', 'gray'],
                                        2 => ['Đang giao hàng', 'blue'],
                                        3 => ['Hoàn thành', 'green'],
                                        4 => ['Đã hủy', 'red']
                                    ];
                                    $statusDisplay = $statusLabels[$currentStatusInt] ?? ['Không xác định', 'black'];
                                @endphp
                                <span style="color: {{ $statusDisplay[1] }}">{{ $statusDisplay[0] }}</span>
                            </td>
                            <td class="px-4 py-2 border text-center text-sm font-semibold">
                                @php
                                    $payStatusLabels = [
                                        0 => ['Chờ thanh toán', 'orange'],
                                        1 => ['Đã thanh toán', 'green'],
                                        2 => ['Đã hủy', 'red'],
                                        3 => ['Hoàn tiền', 'purple']
                                    ];
                                    $payStatusDisplay = $payStatusLabels[$currentPayStatus] ?? ['Không xác định', 'black'];
                                @endphp
                                <span style="color: {{ $payStatusDisplay[1] }}">{{ $payStatusDisplay[0] }}</span>
                            </td>
                            <td class="px-4 py-2 border text-right font-bold text-green-700">
                                {{ number_format($item->total, 0, ',', '.') }} đ
                            </td>
                            <td class="px-4 py-2 border text-center text-gray-600 italic">
                                {{ $item->note ?? 'không có' }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalOrderDetail{{ $item->id }}">
                                    Xem
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Chi tiết đơn hàng -->
            <div class="modal fade" id="modalOrderDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form action="{{ route('orders.updateStatus', $item->id) }}" method="POST" id="orderForm{{ $item->id }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content shadow border-0 rounded-3">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Thông tin hóa đơn #{{ $item->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-2">
                                        <label class="text-primary">Tên khách hàng</label>
                                        <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Số điện thoại</label>
                                        <input type="text" class="form-control" value="{{ $item->phone }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Email</label>
                                        <input type="text" class="form-control" value="{{ $item->email ?? ($isWalkInCustomer ? 'Khách lẻ' : 'Không có') }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Trạng thái đơn hàng</label>
                                        @if($isWalkInCustomer)
                                            <select name="status" class="form-select" id="statusSelect{{ $item->id }}" data-current="{{ $currentStatusInt }}" required onchange="handleStatusChange({{ $item->id }}, this.value, '{{ $item->status }}', true)">
                                                <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Đã xác nhận</option>
                                                <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        @else
                                            <select name="status" class="form-select" id="statusSelect{{ $item->id }}" data-current="{{ $currentStatusInt }}" required onchange="handleStatusChange({{ $item->id }}, this.value, '{{ $item->status }}', false)">
                                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Đã xác nhận</option>
                                                <option value="shipping" {{ $item->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                                <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Trạng thái thanh toán</label>
                                        @if($isWalkInCustomer)
                                            <select name="pay_status" class="form-select" id="payStatusSelect{{ $item->id }}" data-current="{{ $currentPayStatus }}" required onchange="handlePayStatusChange({{ $item->id }}, this.value, {{ $currentPayStatus }}, {{ $currentStatusInt }})">
                                                <option value="1" {{ $currentPayStatus == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="2" {{ $currentPayStatus == 2 ? 'selected' : '' }}>Đã hủy</option>
                                                <option value="3" {{ $currentPayStatus == 3 ? 'selected' : '' }}>Hoàn tiền</option>
                                            </select>
                                        @else
                                            <select name="pay_status" class="form-select" id="payStatusSelect{{ $item->id }}" data-current="{{ $currentPayStatus }}" required onchange="handlePayStatusChange({{ $item->id }}, this.value, {{ $currentPayStatus }}, {{ $currentStatusInt }})">
                                                <option value="0" {{ $currentPayStatus == 0 ? 'selected' : '' }}>Chờ thanh toán</option>
                                                <option value="1" {{ $currentPayStatus == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="2" {{ $currentPayStatus == 2 ? 'selected' : '' }}>Đã hủy</option>
                                                <option value="3" {{ $currentPayStatus == 3 ? 'selected' : '' }}
                                                    @if(!(($item->pay_status == '1' || $item->pay_status == 1) && ($item->status == 'pending' || $item->status == 0 || $item->status == '0'))) disabled @endif
                                                >Hoàn tiền</option>
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Phương thức thanh toán</label>
                                        <input type="text" class="form-control" value="{{ $item->payment_method === 'cash' ? 'Tiền mặt' : 'Thẻ' }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="text-primary">Mã đơn</label>
                                        <input type="text" class="form-control" value="{{ $item->id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Địa chỉ</label>
                                        @if($item->district_name==null)
                                            <input type="text" class="form-control" value="Đặt tại quán" readonly>
                                        @else
                                            <input type="text" class="form-control" value="{{ $item->district_name }}{{ $item->address_detail }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-primary">Ghi chú</label>
                                        <input type="text" class="form-control" value="{{ $item->note}}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-primary">Lý do hủy hiện tại</label>
                                        <input type="text" class="form-control" value="{{ $item->cancel_reason ?? 'Không có' }}" readonly>
                                    </div>
                                    <!-- Input ẩn cho lý do hủy -->
                                    <div class="col-md-12" id="cancelReasonDiv{{ $item->id }}" style="display: none;">
                                        <label class="text-danger">Lý do hủy đơn hàng *</label>
                                        <input type="text" name="cancel_reason" class="form-control" placeholder="Nhập lý do hủy đơn hàng..." id="cancelReasonInput{{ $item->id }}">
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-primary">Tên sản phẩm</th>
                                                <th class="text-primary">Ảnh</th>
                                                <th class="text-primary">Size</th>
                                                <th class="text-primary">Topping</th>
                                                <th class="text-primary">Số lượng</th>
                                                <th class="text-primary">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->details as $ct)
                                            <tr>
                                                <td>{{ $ct->product_name }}</td>
                                                <td>
                                                    @if($ct->product && $ct->product->image)
                                                        <img src="{{ asset('uploads/sanpham/' . $ct->product->image) }}" width="40" height="40" alt="" class="rounded">
                                                    @else
                                                        <div class="bg-gray-200 w-10 h-10 rounded flex items-center justify-center">
                                                            <span class="text-gray-400 text-xs">No img</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ct->size)
                                                        {{ $ct->size->size ?? 'N/A' }} - {{ number_format($ct->size->price ?? 0, 0, ',', '.') }} đ
                                                    @else
                                                        <span class="text-gray-500">Không có size</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($ct->topping_list))
                                                        @foreach($ct->topping_list as $topping)
                                                            <div class="mb-1">
                                                                {{ $topping->topping }} - {{ number_format($topping->price, 0, ',', '.') }} đ
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-gray-500">Không chọn</span>
                                                    @endif
                                                </td>
                                                <td>{{ $ct->quantity }}</td>
                                                <td>{{ number_format($ct->total, 0, ',', '.') }} đ</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row g-3 mt-3 border-bottom pb-2 mb-2">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-4">
                                            @if(!$isWalkInCustomer)
                                                <span class="text-primary">Tiền ship: <span class="fw-normal text-dark">{{ number_format($item->shipping_fee ?? 0, 0, ',', '.') }} đ</span></span>
                                            @endif
                                            <span class="text-primary">Tiền sản phẩm: <span class="fw-normal text-dark">{{ number_format(($item->total ?? 0) + ($item->coupon_total_discount ?? 0) - ($item->shipping_fee ?? 0), 0, ',', '.') }} đ</span></span>
                                            <span class="text-primary">Tiền giảm giá: <span class="fw-normal text-dark">{{ number_format($item->coupon_total_discount ?? 0, 0, ',', '.') }} đ</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-12 d-flex justify-content-end">
                                        <span class="text-primary fw-bold" style="font-size: 1.1rem;">Tổng tiền thanh toán: <span class="fw-bold" style="font-size: 1.2rem; color: #d32f2f;">{{ number_format($item->total, 0, ',', '.') }} đ</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-sm btn-success mt-2" id="updateBtn{{ $item->id }}">Cập nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<script>
function handleStatusChange(orderId, newStatusValue, currentStatusInt, isWalkInCustomer = false) {
    const cancelReasonDiv = document.getElementById('cancelReasonDiv' + orderId);
    const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);
    const statusSelect = document.getElementById('statusSelect' + orderId);
    const payStatusSelect = document.getElementById('payStatusSelect' + orderId);

    // Reset cancel reason display
    cancelReasonDiv.style.display = 'none';
    cancelReasonInput.required = false;
    cancelReasonInput.value = '';

    // Convert status values to integers for comparison
    const statusMapping = {
        'pending': 0,
        'processing': 1,
        'shipping': 2,
        'completed': 3,
        'cancelled': 4
    };

    let currentStatus = currentStatusInt;
    let newStatus = newStatusValue;

    // Convert string status to integer if needed
    if (typeof currentStatus === 'string') {
        currentStatus = statusMapping[currentStatus] || 0;
    }
    if (typeof newStatus === 'string') {
        newStatus = statusMapping[newStatus] || 0;
    }

    // Convert to integers for comparison
    currentStatus = parseInt(currentStatus);
    newStatus = parseInt(newStatus);

    // Nếu đơn đã hoàn thành, không cho phép đổi trạng thái nào nữa, kể cả hủy
    if (currentStatus === 3) {
        statusSelect.value = 'completed';
        showAlert('Đơn hàng đã hoàn thành không thể thay đổi trạng thái!', 'error');
        return false;
    }

    // For walk-in customers
    if (isWalkInCustomer) {
        // Nếu đơn đã hủy thì không cho đổi trạng thái
        if (currentStatus === 4) {
            statusSelect.value = 'cancelled';
            showAlert('Đơn hàng đã hủy không thể thay đổi trạng thái!', 'error');
            return false;
        }
        // Nếu chọn hủy
        if (newStatus === 4) {
            cancelReasonDiv.style.display = 'block';
            cancelReasonInput.required = true;
            if (payStatusSelect) payStatusSelect.value = '2'; // Đã hủy
            return true;
        }
        // Chỉ cho phép chọn các trạng thái: Đã xác nhận (1), Hoàn thành (3), Đã hủy (4)
        if (![1, 3, 4].includes(newStatus)) {
            statusSelect.value = currentStatus === 3 ? 'completed' : (currentStatus === 1 ? 'processing' : 'processing');
            showAlert('Khách lẻ chỉ có thể chọn "Đã xác nhận", "Hoàn thành" hoặc "Đã hủy"!', 'error');
            return false;
        }
        return true;
    }

    // For online orders
    // If order is already cancelled, don't allow status change
    if (currentStatus === 4) {
        statusSelect.value = 'cancelled';
        showAlert('Đơn hàng đã hủy không thể thay đổi trạng thái!', 'error');
        return false;
    }

    // Prevent going backwards (except for cancellation)
    if (newStatus < currentStatus && newStatus !== 4) {
        statusSelect.value = Object.keys(statusMapping).find(key => statusMapping[key] === currentStatus) || 'pending';
        showAlert('Không thể lùi trạng thái đơn hàng!', 'error');
        return false;
    }

    // Prevent skipping steps (must go one step at a time, except for cancellation)
    if (newStatus > currentStatus + 1 && newStatus !== 4) {
        statusSelect.value = Object.keys(statusMapping).find(key => statusMapping[key] === currentStatus) || 'pending';
        showAlert('Chỉ có thể chuyển sang trạng thái tiếp theo!', 'error');
        return false;
    }

    // If choosing to cancel
    if (newStatus === 4) {
        cancelReasonDiv.style.display = 'block';
        cancelReasonInput.required = true;
        if (payStatusSelect) payStatusSelect.value = '2';
        return true;
    }

    // If choosing completed, set payment status to paid
    if (newStatus === 3 && payStatusSelect) {
        payStatusSelect.value = '1';
    }

    return true;
}

function handlePayStatusChange(orderId, newPayStatus, currentPayStatus, currentOrderStatus) {
    const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
    const statusSelect = document.getElementById('statusSelect' + orderId);
    newPayStatus = parseInt(newPayStatus);
    currentPayStatus = parseInt(currentPayStatus);
    currentOrderStatus = parseInt(currentOrderStatus);

    // Nếu trạng thái hiện tại là Hoàn tiền thì không cho đổi nữa
    if (currentPayStatus === 3 && newPayStatus !== 3) {
        payStatusSelect.value = '3';
        showAlert('Đơn đã hoàn tiền, không thể thay đổi trạng thái thanh toán!', 'error');
        return false;
    }
    // Nếu trạng thái hiện tại là Đã thanh toán, không cho quay lại Chờ thanh toán
    if (currentPayStatus === 1 && newPayStatus === 0) {
        payStatusSelect.value = '1';
        showAlert('Không thể chuyển từ Đã thanh toán về Chờ thanh toán!', 'error');
        return false;
    }
    // Nếu đơn hàng đã hủy, chỉ cho phép trạng thái thanh toán "Đã hủy" hoặc "Hoàn tiền"
    if (currentOrderStatus === 4) {
        if (newPayStatus !== 2 && newPayStatus !== 3) {
            payStatusSelect.value = currentPayStatus.toString();
            showAlert('Đơn hàng đã hủy chỉ có thể có trạng thái thanh toán "Đã hủy" hoặc "Hoàn tiền"!', 'error');
            return false;
        }
    }
    // Nếu đơn hàng đã hoàn thành, không cho phép chuyển về "Chờ thanh toán"
    if (currentOrderStatus === 3 && newPayStatus === 0) {
        payStatusSelect.value = currentPayStatus.toString();
        showAlert('Đơn hàng đã hoàn thành không thể chuyển về "Chờ thanh toán"!', 'error');
        return false;
    }
    // Không cho phép chuyển từ "Chờ thanh toán" trực tiếp sang "Hoàn tiền"
    if (currentPayStatus === 0 && newPayStatus === 3) {
        payStatusSelect.value = currentPayStatus.toString();
        showAlert('Không thể chuyển từ "Chờ thanh toán" trực tiếp sang "Hoàn tiền"!', 'error');
        return false;
    }
    // Chỉ cho phép hoàn tiền khi đã thanh toán trước đó
    if (newPayStatus === 3 && currentPayStatus !== 1) {
        payStatusSelect.value = currentPayStatus.toString();
        showAlert('Chỉ có thể hoàn tiền khi đã thanh toán!', 'error');
        return false;
    }
    // Không cho phép chuyển trạng thái thanh toán thành "Đã hủy" nếu đơn hàng chưa hủy
    if (newPayStatus === 2 && currentOrderStatus !== 4) {
        payStatusSelect.value = currentPayStatus.toString();
        showAlert('Chỉ có thể chuyển trạng thái thanh toán thành "Đã hủy" khi đơn hàng đã hủy!', 'error');
        return false;
    }
    // Nếu chọn Hoàn tiền thì chỉ hiện input lý do hủy, không đổi trạng thái đơn
    if (newPayStatus === 3) {
        const cancelReasonDiv = document.getElementById('cancelReasonDiv' + orderId);
        const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);
        cancelReasonDiv.style.display = 'block';
        cancelReasonInput.required = true;
    }
    return true;
}

function showAlert(message, type = 'info') {
    const alertClass = type === 'error' ? 'alert-danger' : 'alert-info';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    const modalBody = document.querySelector('.modal.show .modal-body');
    if (modalBody) {
        modalBody.insertAdjacentHTML('afterbegin', alertHtml);
        setTimeout(() => {
            const alert = modalBody.querySelector('.alert');
            if (alert) alert.remove();
        }, 3000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="orderForm"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const orderId = this.id.replace('orderForm', '');
            const statusSelect = document.getElementById('statusSelect' + orderId);
            const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);

            if (statusSelect.value === '4' && (!cancelReasonInput.value || cancelReasonInput.value.trim() === '')) {
                e.preventDefault();
                showAlert('Vui lòng nhập lý do hủy đơn hàng!', 'error');
                cancelReasonInput.focus();
                return false;
            }
        });
    });
});
</script>

@endsection