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
            $isWalkInCustomer = ($item->name == 'Khách Vãng Lai') || ($item->district_name == null && $item->shipping_fee == 0);
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
            // Lấy trạng thái thanh toán trực tiếp từ database, không đồng bộ với trạng thái đơn hàng
            if (is_numeric($item->pay_status)) {
                $currentPayStatus = (int)$item->pay_status;
            } else {
                $currentPayStatus = $payStatusMapping[$item->pay_status] ?? 0;
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
                        <tr @if($isWalkInCustomer) class="px-4 py-2 border" style="background-color: #ffe066;" @else class="px-4 py-2 border" @endif>
                            <td class="px-4 py-2 border text-gray-700 font-medium">{{ $item->name ?? 'Khách Vãng Lai' }}</td>
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
            
            // Cho Khách Vãng Lai, nếu trạng thái là pending thì chuyển thành processing
            if ($isWalkInCustomer && $currentStatusInt === 0) {
                $currentStatusInt = 1;
            }
                                    $statusDisplay = $statusLabels[$currentStatusInt] ?? ['Không xác định', 'black'];
                                @endphp
                                <span class="status-label" data-color="{{ $statusDisplay[1] }}">{{ $statusDisplay[0] }}</span>
                            </td>
                            <td class="px-4 py-2 border text-center text-sm font-semibold">
                                @php
                                    $payStatusLabels = [
                                        0 => ['Chờ thanh toán', 'orange'],
                                        1 => ['Đã thanh toán', 'green'],
                                        2 => ['Đã hủy', 'red'],
                                        3 => ['Hoàn tiền', '#e67e22']
                                    ];
                                    $payStatusDisplay = $payStatusLabels[$currentPayStatus] ?? ['Không xác định', 'black'];
                                @endphp
                                <span class="pay-status-label" data-color="{{ $payStatusDisplay[1] }}">{{ $payStatusDisplay[0] }}</span>
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
                                    <div class="col-md-3">
                                        <label class="text-primary">Tên khách hàng</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->name }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Số điện thoại</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->phone }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Email</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->email ?? 'Không có' }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Trạng thái đơn hàng</label>
                                        <select name="status" class="form-select equal-width-input status-select" id="statusSelect{{ $item->id }}" data-current="{{ $currentStatusInt }}" data-order-id="{{ $item->id }}" data-is-walk-in="{{ $isWalkInCustomer ? 'true' : 'false' }}">
                                            @if(!$isWalkInCustomer)
                                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                            @endif
                                            <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Đã xác nhận</option>
                                            @if(!$isWalkInCustomer)
                                                <option value="shipping" {{ $item->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                            @endif
                                            <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Trạng thái thanh toán</label>
                                        @if($isWalkInCustomer)
                                            <select name="pay_status" class="form-select equal-width-input pay-status-select" id="payStatusSelect{{ $item->id }}" data-current="{{ $currentPayStatus }}" data-order-id="{{ $item->id }}" data-status-int="{{ $currentStatusInt }}" disabled>
                                                <option value="0" {{ $currentPayStatus == 0 ? 'selected' : '' }}>Chờ thanh toán</option>
                                                <option value="1" {{ $currentPayStatus == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                                            </select>
                                        @else
                                            <select name="pay_status" class="form-select equal-width-input pay-status-select" id="payStatusSelect{{ $item->id }}" data-current="{{ $currentPayStatus }}" data-order-id="{{ $item->id }}" data-status-int="{{ $currentStatusInt }}">
                                                <option value="0" {{ $currentPayStatus == 0 ? 'selected' : '' }}>Chờ thanh toán</option>
                                                <option value="1" {{ $currentPayStatus == 1 ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="2" {{ $currentPayStatus == 2 ? 'selected' : '' }}>Đã hủy</option>
                                                <option value="3" {{ $currentPayStatus == 3 ? 'selected' : '' }}>Hoàn tiền</option>
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Phương thức thanh toán</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->payment_method === 'cash' ? 'Tiền mặt' : 'Thẻ' }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Địa chỉ</label>
                                        @if($item->district_name==null)
                                            <input type="text" class="form-control equal-width-input" value="Đặt tại quán" readonly>
                                        @else
                                            <input type="text" class="form-control equal-width-input" value="{{ $item->district_name }}{{ $item->address_detail }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Ghi chú</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->note}}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Lý do hủy hiện tại</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->cancel_reason ?? 'Không có' }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Mã đơn</label>
                                        <input type="text" class="form-control equal-width-input" value="{{ $item->id }}" readonly>
                                    </div>
                                    <div class="col-md-12" id="cancelReasonDiv{{ $item->id }}" style="display: none;">
                                        <label class="text-danger">Lý do hủy đơn hàng *</label>
                                        <input type="text" name="cancel_reason" class="form-control equal-width-input" placeholder="Nhập lý do hủy đơn hàng..." id="cancelReasonInput{{ $item->id }}">
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
                                                        <img src="{{ asset('storage/uploads/' . $ct->product->image) }}" width="100" height="70" alt="" class="rounded">  
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
                                            <span class="text-primary">Giảm giá điểm: <span class="fw-normal text-dark">{{ number_format($item->points_discount ?? 0, 0, ',', '.') }} đ</span></span>
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
                                <button type="submit" class="btn btn-success" id="updateBtn{{ $item->id }}">Cập nhật</button>
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
function disableInvalidStatusOptions(orderId, originalStatus, isWalkInCustomer) {
    const select = document.getElementById('statusSelect' + orderId);
    const statusOrder = { 'pending': 0, 'processing': 1, 'shipping': 2, 'completed': 3, 'cancelled': 4 };

    [...select.options].forEach(option => {
        const val = option.value;
        option.disabled = false;

        // Cho phép đơn hàng nhân viên chuyển từ "Hoàn thành" sang "Đã hủy"
        if (isWalkInCustomer && originalStatus === 'completed' && val === 'cancelled') {
            option.disabled = false;
            return;
        }

        // Final states: cannot be changed from (trừ đơn hàng nhân viên)
        if ((originalStatus === 'completed' || originalStatus === 'cancelled') && !isWalkInCustomer) {
            if (val !== originalStatus) {
                option.disabled = true;
            }
            return;
        }

        // Cho Khách Vãng Lai: chỉ cho phép chuyển từ processing -> completed hoặc cancelled
        if (isWalkInCustomer) {
            if (originalStatus === 'processing') {
                if (val !== 'completed' && val !== 'cancelled') {
                    option.disabled = true;
                }
            } else if (originalStatus === 'completed') {
                // Khách Vãng Lai đã hoàn thành thì không thể hủy nữa
                if (val !== 'completed') {
                    option.disabled = true;
                }
            } else if (originalStatus === 'cancelled') {
                if (val !== 'cancelled') {
                    option.disabled = true;
                }
            }
            return;
        }

        // Cho khách online: logic cũ
        if (statusOrder[val] < statusOrder[originalStatus] && val !== 'cancelled') {
            option.disabled = true;
        }

        // Cho phép chuyển sang trạng thái tiếp theo hoặc cancelled
        const isJumping = statusOrder[val] > statusOrder[originalStatus] + 1;
        if (isJumping && val !== 'cancelled') {
            option.disabled = true;
        }
    });
}

function disableInvalidPayStatusOptions(orderId, originalPayStatus, currentOrderStatus) {
    const select = document.getElementById('payStatusSelect' + orderId);
    const statusSelect = document.getElementById('statusSelect' + orderId);
    const isWalkIn = statusSelect.getAttribute('data-is-walk-in') === 'true';
    const original = parseInt(originalPayStatus);
    const status = currentOrderStatus;

    // Nếu là khách lẻ thì đã disable ở HTML rồi
    if (isWalkIn) return;

    // Nếu trạng thái đơn hàng là 'Đã hủy' thì chỉ cho phép chọn 'Đã hủy'
    if (status === 'cancelled') {
        const originalPayStatus = select.getAttribute('data-current');
        if (originalPayStatus === '1') {
            // Nếu đã thanh toán từ trước, chỉ cho chọn Hoàn tiền
            [...select.options].forEach(option => {
                if (option.value !== '3') {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        } else {
            // Nếu chưa thanh toán, chỉ cho chọn Đã hủy
            [...select.options].forEach(option => {
                if (option.value !== '2') {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        }
        select.setAttribute('name', 'pay_status');
        // Chỉ disable nếu trạng thái đơn hàng đã là cancelled từ trước và TTTT là 2 hoặc 3
        const originalStatus = statusSelect.getAttribute('data-current');
        if ((select.value === '2' && originalStatus === 'cancelled') || (select.value === '3' && originalStatus === 'cancelled')) {
            select.setAttribute('disabled', 'disabled');
        } else {
            select.removeAttribute('disabled');
        }
        return;
    }

    // Nếu trạng thái đơn hàng là 'Hoàn thành' thì chỉ cho phép chọn 'Đã thanh toán'
    if (status === 'completed') {
        [...select.options].forEach(option => {
            if (option.value !== '1') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
        select.setAttribute('name', 'pay_status');
        // Chỉ disable nếu trạng thái đơn hàng đã là completed từ trước và TTTT là 1
        const originalStatus = statusSelect.getAttribute('data-current');
        if (select.value === '1' && originalStatus === 'completed') {
            select.setAttribute('disabled', 'disabled');
        } else {
            select.removeAttribute('disabled');
        }
        return;
    }

    // Khi chuyển sang hoàn thành, dropdown TTTT luôn enable và cho phép chọn lại
    select.removeAttribute('disabled');
    [...select.options].forEach(option => {
        const val = parseInt(option.value);
        option.disabled = false;
        if (status === 'pending' || status === 'processing' || status === 'shipping') {
            option.disabled = true;
        }
    });
    if (status === 'pending' || status === 'processing' || status === 'shipping') {
        select.removeAttribute('name');
    } else {
        select.setAttribute('name', 'pay_status');
    }
}

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

    // For walk-in customers
    if (isWalkInCustomer) {
        // Logic cho Khách Vãng Lai: chỉ có 3 trạng thái (processing, completed, cancelled)
        if (currentStatus === 1) { // processing
            if (newStatus === 3) { // completed
                return true;
            } else if (newStatus === 4) { // cancelled
                cancelReasonDiv.style.display = 'block';
                cancelReasonInput.required = true;
                return true;
            } else {
                statusSelect.value = 'processing';
                showAlert('Khách Vãng Lai chỉ có thể chuyển từ "Đã xác nhận" sang "Hoàn thành" hoặc "Đã hủy"!', 'error');
                return false;
            }
        } else if (currentStatus === 3) { // completed
            // Khách Vãng Lai đã hoàn thành thì không thể hủy nữa
            statusSelect.value = 'completed';
            showAlert('Đơn hàng Khách Vãng Lai đã hoàn thành không thể hủy!', 'error');
            return false;
        } else if (currentStatus === 4) { // cancelled
            statusSelect.value = 'cancelled';
            showAlert('Đơn hàng đã hủy không thể thay đổi trạng thái!', 'error');
            return false;
        }

        return true;
    }

    // For online orders
    // Final states: cannot be changed from
    if ((currentStatus === 3 || currentStatus === 4) && newStatus !== currentStatus) {
        statusSelect.value = Object.keys(statusMapping).find(key => statusMapping[key] === currentStatus) || 'pending';
        showAlert('Đơn hàng đã hoàn thành hoặc hủy không thể thay đổi trạng thái!', 'error');
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
    
    // Validate completed orders must have paid status - chỉ kiểm tra khi submit form, không block việc chọn
    // Logic này sẽ được xử lý trong form validation
    
    // If choosing to cancel
    if (newStatus === 4) {
        cancelReasonDiv.style.display = 'block';
        cancelReasonInput.required = true;
        return true;
    }
    
    return true;
}

function handlePayStatusChange(orderId, newPayStatus, currentPayStatus, currentOrderStatus) {
    const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
    const statusSelect = document.getElementById('statusSelect' + orderId);
    const isWalkIn = statusSelect.getAttribute('data-is-walk-in') === 'true';
    newPayStatus = parseInt(newPayStatus);
    currentPayStatus = parseInt(currentPayStatus);

    // Nếu trạng thái hiện tại là Hoàn tiền thì không cho đổi nữa
    if (currentPayStatus === 3 && newPayStatus !== 3) {
        payStatusSelect.value = '3';
        showAlert('Đơn đã hoàn tiền, không thể thay đổi trạng thái thanh toán!', 'error');
        return false;
    }
    
    // Validate: Nếu đơn hàng online đã hoàn thành thì không thể đổi trạng thái thanh toán
    // Chỉ áp dụng khi đơn hàng đã hoàn thành từ trước, không block khi đang chuyển sang completed
    if (!isWalkIn && statusSelect.value === 'completed' && currentOrderStatus === 'completed' && newPayStatus !== 1) {
        payStatusSelect.value = currentPayStatus.toString();
        showAlert('Đơn hàng online đã hoàn thành không thể thay đổi trạng thái thanh toán!', 'error');
        return false;
    }
    
    // Nếu chọn Hoàn tiền thì hiện input lý do hủy
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
            const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
            const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);
            const isWalkIn = statusSelect.getAttribute('data-is-walk-in') === 'true';
            const currentPayStatus = parseInt(payStatusSelect.getAttribute('data-current'));

            // Validate cancel reason
            if ((statusSelect.value === 'cancelled' || payStatusSelect.value === '2') && (!cancelReasonInput.value || cancelReasonInput.value.trim() === '')) {
                e.preventDefault();
                showAlert('Vui lòng nhập lý do hủy đơn hàng!', 'error');
                cancelReasonInput.focus();
                return false;
            }

            // Validate status for walk-in orders
            if (isWalkIn && (statusSelect.value === 'shipping' || statusSelect.value === 'pending')) {
                e.preventDefault();
                showAlert('Khách Vãng Lai chỉ có thể có trạng thái "Đã xác nhận", "Hoàn thành" hoặc "Đã hủy"!', 'error');
                return false;
            }

            // Validate completed orders must have paid status
            if (!isWalkIn && statusSelect.value === 'completed' && payStatusSelect.value !== '1') {
                e.preventDefault();
                showAlert('Đơn hàng online hoàn thành phải có trạng thái thanh toán là "Đã thanh toán"!', 'error');
                payStatusSelect.focus();
                return false;
            }

            // Validate: Nếu chọn hủy mà TTTT ban đầu là đã thanh toán nhưng không chọn Hoàn tiền thì báo lỗi
            if (!isWalkIn && statusSelect.value === 'cancelled' && payStatusSelect.getAttribute('data-current') === '1' && payStatusSelect.value !== '3') {
                e.preventDefault();
                showAlert('Đơn đã thanh toán. Khi hủy, trạng thái thanh toán phải được chuyển thành "Hoàn tiền".', 'error');
                payStatusSelect.focus();
                return false;
            }
        });
    });
    
    // Gán sự kiện onchange cho các select trạng thái đơn hàng
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const isWalkIn = this.getAttribute('data-is-walk-in') === 'true';
            const currentStatusInt = parseInt(this.getAttribute('data-current'));
            handleStatusChange(orderId, this.value, currentStatusInt, isWalkIn);
            
            // Update payment status handling when order status changes
            const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
            if (payStatusSelect) {
                const currentPayStatus = parseInt(payStatusSelect.getAttribute('data-current'));
                const newOrderStatus = this.value;
                
                // Nếu là khách online, chọn Hoàn thành mà TTTT chưa phải Đã thanh toán thì cảnh báo và tự động chuyển
                if (!isWalkIn && newOrderStatus === 'completed' && payStatusSelect.value !== '1') {
                    showAlert('Đơn hàng online hoàn thành phải có trạng thái thanh toán là "Đã thanh toán"!', 'error');
                    payStatusSelect.value = '1';
                }
                
                handlePayStatusChange(orderId, payStatusSelect.value, currentPayStatus, newOrderStatus);
                disableInvalidPayStatusOptions(orderId, currentPayStatus, newOrderStatus);
                // Đóng băng cả hai select nếu đơn hàng hoàn thành và đã thanh toán
                // Chỉ áp dụng khi đơn hàng đã hoàn thành từ trước, không phải đang chuyển sang
                const originalStatus = this.getAttribute('data-current');
                if (newOrderStatus === 'completed' && payStatusSelect.value === '1' && originalStatus === 'completed') {
                    disableCompletedPaidOrder(orderId);
                }
            }
        });
    });
    
    // Gán sự kiện onchange cho các select trạng thái thanh toán
    document.querySelectorAll('.pay-status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const currentPayStatus = parseInt(this.getAttribute('data-current'));
            const statusSelect = document.getElementById('statusSelect' + orderId);
            const currentOrderStatus = statusSelect ? statusSelect.value : 'pending';
            handlePayStatusChange(orderId, this.value, currentPayStatus, currentOrderStatus);
            
            // Đóng băng cả hai select nếu đơn hàng hoàn thành và đã thanh toán
            // Chỉ áp dụng khi đơn hàng đã hoàn thành từ trước
            const originalPayStatus = this.getAttribute('data-current');
            if (currentOrderStatus === 'completed' && this.value === '1' && originalPayStatus === '1') {
                disableCompletedPaidOrder(orderId);
            }
        });
    });

    // Khởi tạo disable options khi trang load
    document.querySelectorAll('.status-select').forEach(function(select) {
        const orderId = select.getAttribute('data-order-id');
        const isWalkIn = select.getAttribute('data-is-walk-in') === 'true';
        const currentStatus = select.value;
        disableInvalidStatusOptions(orderId, currentStatus, isWalkIn);
        
        // Kiểm tra và đóng băng đơn hàng đã hoàn thành và đã thanh toán
        const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
        if (payStatusSelect && currentStatus === 'completed' && payStatusSelect.value === '1') {
            disableCompletedPaidOrder(orderId);
        }
    });

    document.querySelectorAll('.pay-status-select').forEach(function(select) {
        const orderId = select.getAttribute('data-order-id');
        const currentPayStatus = parseInt(select.getAttribute('data-current'));
        const statusSelect = document.getElementById('statusSelect' + orderId);
        const currentOrderStatus = statusSelect ? statusSelect.value : 'pending';
        disableInvalidPayStatusOptions(orderId, currentPayStatus, currentOrderStatus);
    });
});

function disableCompletedPaidOrder(orderId) {
    const statusSelect = document.getElementById('statusSelect' + orderId);
    const payStatusSelect = document.getElementById('payStatusSelect' + orderId);
    
    if (statusSelect) {
        // Disable tất cả options trạng thái đơn hàng
        [...statusSelect.options].forEach(option => {
            option.disabled = true;
        });
        statusSelect.setAttribute('disabled', 'disabled');
    }
    
    if (payStatusSelect) {
        // Disable tất cả options trạng thái thanh toán
        [...payStatusSelect.options].forEach(option => {
            option.disabled = true;
        });
        payStatusSelect.setAttribute('disabled', 'disabled');
    }
}
</script>

<style>
    .equal-width-input {
        min-width: 0;
        width: 100%;
        max-width: 100%;
    }
    
    .status-label[data-color="orange"] { color: orange; }
    .status-label[data-color="gray"] { color: gray; }
    .status-label[data-color="blue"] { color: blue; }
    .status-label[data-color="green"] { color: green; }
    .status-label[data-color="red"] { color: red; }
    .status-label[data-color="black"] { color: black; }
    
    .pay-status-label[data-color="orange"] { color: orange; }
    .pay-status-label[data-color="green"] { color: green; }
    .pay-status-label[data-color="red"] { color: red; }
    .pay-status-label[data-color="purple"] { color: #e67e22; }
    .pay-status-label[data-color="black"] { color: black; }

    /* Làm mờ option bị disabled trong select */
    select:disabled, select option:disabled {
        color: #b0b0b0 !important;
        background: #f5f5f5 !important;
    }
    /* Option đang được chọn trong select bị disabled vẫn đậm và rõ */
    select:disabled option:checked,
    select:disabled option[selected] {
        color: #222 !important;
        font-weight: bold;
        background: #f5f5f5 !important;
    }

    /* Hiển thị select bị disabled rõ ràng hơn */
    select:disabled {
        background: #fff !important;
        color: #222 !important;
        opacity: 1 !important;
        cursor: not-allowed;
    }
</style>

@endsection