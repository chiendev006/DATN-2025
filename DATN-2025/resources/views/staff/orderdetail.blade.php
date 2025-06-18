@extends('staff.layout')

@section('main-content')

<h2 class="text-3xl font-extrabold text-indigo-800 mb-8 flex items-center gap-2">
    🧾 Hóa đơn hôm nay
</h2>

@if ($donhangs->isEmpty())
    <div class="bg-gray-50 p-6 rounded-xl shadow text-gray-500 italic text-center border border-dashed">
        Chưa có hóa đơn nào hôm nay.
    </div>
@else
    <div class="space-y-8">
        @foreach ($donhangs as $item)
        <br>
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <!-- Header hóa đơn -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-indigo-200">
                    <div>
                        <h3 class="text-3xl font-extrabold text-indigo-700">#{{ $item->id }}</h3>
                        <p class="text-sm text-gray-500">Mã hóa đơn</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-gray-700">{{ $item->created_at->format('H:i') }}</div>
                        <div class="text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Bảng thông tin -->
                <div class="overflow-x-auto">
                    <table style="width:100%" class="w-full text-sm border border-gray-200 rounded-lg">
                        <thead class="bg-indigo-100 text-indigo-800 uppercase">
                            <tr>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Khách hàng</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Số điện thoại</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Trạng thái</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Trạng thái thanh toán</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Tổng tiền</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Ghi chú</th>
                                <th style="font-size: 15px;" class="px-4 py-2 border text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border text-gray-700 font-medium">{{ $item->name ?? 'Khách vãng lai' }}</td>
                                <td class="px-4 py-2 border">{{ $item->phone ?? 'không có' }}</td>
                                <td class="px-4 py-2 border text-center text-sm font-semibold">
                                    @php
                                        $statusMap = ['pending' => ['Chờ xử lý', 'orange'],
                                            'processing' => ['Đã xác nhận', 'green'],
                                            'completed' => ['Hoàn thành', 'gray'],
                                            'cancelled' => ['Đã hủy', 'red'],
                                            0 => ['Chờ xử lý', 'orange'],
                                            1 => ['Đã xác nhận', 'green'],
                                            3 => ['Hoàn thành', 'gray'],
                                            4 => ['Đã hủy', 'red'],
                                        ];
                                        $display = $statusMap[$item->status] ?? [$item->status, 'black'];
                                        
                                    @endphp
                                    <span style="color: {{ $display[1] }}">{{ $display[0] }}</span>
                                </td>
                                <td class="px-4 py-2 border text-center text-sm font-semibold">
                                    @php
                                        $statusMap = [
                                            '0' => ['Chưa thanh toán', 'orange'],
                                            '1' => ['Đã thanh toán', 'green'],
                                            0 => ['Chưa thanh toán', 'orange'],
                                            1 => ['Đã thanh toán', 'green'],
                                        ];
                                        $display = $statusMap[$item->pay_status] ?? [$item->pay_status, 'black'];
                                    @endphp
                                    <span style="color: {{ $display[1] }}">{{ $display[0] }}</span>
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
                    <div class="modal-dialog modal-xl modal-dialog-centered"><form action="{{ route('orders.updateStatus', $item->id) }}" method="POST" id="orderForm{{ $item->id }}">
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
                                        @if($item->phone=='N/A')
                                        <input type="text" class="form-control" value="Nhân viên đặt" readonly>
                                        @else
                                        <input type="text" class="form-control" value="{{ $item->phone }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <label class="text-primary">Email</label>
                                        @if($item->email==null)
                                        <input type="text" class="form-control" value="Nhân viên đặt" readonly>
                                        @else
                                        <input type="text" class="form-control" value="{{ $item->email }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Trạng thái</label>
                                        @php
                                            // Xác định trạng thái hiện tại
                                            $currentStatus = $item->status;
                                            if (is_numeric($currentStatus)) {
                                                $currentStatusValue = (int)$currentStatus;
                                            } else {
                                                $statusMapping = [
                                                    'pending' => 0,
                                                    'processing' => 1,'completed' => 3,
                                                    'cancelled' => 4
                                                ];
                                                $currentStatusValue = $statusMapping[$currentStatus] ?? 0;
                                            }
                                        @endphp
                                        
                                        <select name="status" class="form-select" id="statusSelect{{ $item->id }}" 
                                                onchange="handleStatusChange({{ $item->id }}, this.value, {{ $currentStatusValue }})" required>
                                            @if($currentStatusValue <= 0)
                                                <option value="pending" {{ ($item->status == 'pending' || $item->status == 0) ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="processing">Đã xác nhận</option>
                                                <option value="cancelled">Đã hủy</option>
                                            @elseif($currentStatusValue == 1)
                                                <option value="processing" selected>Đã xác nhận</option>
                                                <option value="completed">Hoàn thành</option>
                                                <option value="cancelled">Đã hủy</option>
                                            @elseif($currentStatusValue == 3)
                                                <option value="completed" selected>Hoàn thành</option>
                                            @elseif($currentStatusValue == 4)
                                                <option value="cancelled" selected>Đã hủy</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="text-primary">Ghi chú</label>
                                        <input type="text" class="form-control" value="{{ $item->note}}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="text-primary">Mã đơn</label>
                                        <input type="text" class="form-control" value="{{ $item->id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Thanh toán</label>
                                        <input type="text" class="form-control" value="{{ $item->payment_method === 'cash' ? 'Tiền mặt' : 'Thẻ' }}" readonly>
                                    </div><div class="col-md-5">
                                        <label class="text-primary">Địa chỉ</label>
                                        @if($item->district_name==null)
                                        <input type="text" class="form-control" value="Đặt tại quán" readonly>
                                        @else
                                        <input type="text" class="form-control" value="{{ $item->district_name }}{{ $item->address_detail }}" readonly>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Lý do hủy</label>
                                        <input type="text" class="form-control" value="{{ $item->cancel_reason}}" readonly>
                                    </div>
                                    
                                    <!-- Input ẩn cho lý do hủy -->
                                    <div class="col-md-12" id="cancelReasonDiv{{ $item->id }}" style="display: none;">
                                        <label class="text-danger">Lý do hủy đơn hàng *</label>
                                        <input type="text" name="cancel_reason" class="form-control" 
                                               placeholder="Nhập lý do hủy đơn hàng..." required id="cancelReasonInput{{ $item->id }}">
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
                                                    @if($ct->product && $ct->product->image)<img src="{{ asset('uploads/sanpham/' . $ct->product->image) }}" width="40" height="40" alt="" class="rounded">
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

                                <div class="row g-3 mt-3">
                                    <div class="col-md-3">
                                        <label class="text-primary">Tiền ship</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->shipping_fee ?? 0, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    <div class="col-md-3"><label class="text-primary">Tiền sản phẩm</label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format(($item->total ?? 0) + ($item->coupon_total_discount ?? 0) - ($item->shipping_fee ?? 0), 0, ',', '.') }} đ"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Tiền giảm giá</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->coupon_total_discount ?? 0, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-primary">Tổng tiền thanh toán</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->total, 0, ',', '.') }} đ" readonly>
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
function handleStatusChange(orderId, newStatus, currentStatus) {
    const cancelReasonDiv = document.getElementById('cancelReasonDiv' + orderId);
    const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);
    const statusSelect = document.getElementById('statusSelect' + orderId);
    
    // Định nghĩa thứ tự trạng thái
    const statusOrder = {
        'pending': 0,
        'processing': 1,
        'completed': 3,
        'cancelled': 4
    };
    
    const newStatusValue = statusOrder[newStatus];
    
    // Kiểm tra logic trạng thái
    if (newStatus === 'cancelled') {
        // Nếu chọn hủy, hiển thị input lý do
        cancelReasonDiv.style.display = 'block';
        cancelReasonInput.required = true;
        console.log('Showing cancel reason input for order:', orderId); // Debug
    } else {
        // Ẩn input lý do hủy
        cancelReasonDiv.style.display = 'none';
        cancelReasonInput.required = false;
        cancelReasonInput.value = '';
        
        // Kiểm tra logic không được lùi trạng thái và không nhảy cóc
        if (currentStatus === 4) {// Nếu đã hủy thì không được chọn trạng thái khác
            alert('Đơn hàng đã hủy không thể thay đổi trạng thái!');
            statusSelect.value = 'cancelled';
            return false;
        }
        
        if (currentStatus === 3 && newStatusValue !== 3) {
            // Nếu đã hoàn thành thì không được chọn trạng thái khác
            alert('Đơn hàng đã hoàn thành không thể thay đổi trạng thái!');
            statusSelect.value = 'completed';
            return false;
        }
        
        if (newStatusValue < currentStatus && currentStatus !== 4) {
            // Không được lùi trạng thái (trừ trường hợp hủy)
            alert('Không thể lùi trạng thái đơn hàng!');
            // Reset về trạng thái hiện tại
            const currentStatusText = {
                0: 'pending',
                1: 'processing', 
                3: 'completed',
                4: 'cancelled'
            };
            statusSelect.value = currentStatusText[currentStatus];
            return false;
        }
        
        // Kiểm tra không nhảy cóc trạng thái
        // Logic: 0 -> 1 -> 3 (hoặc hủy từ bất kỳ đâu)
        if (newStatusValue !== 4) { // Không phải hủy
            if (currentStatus === 0 && newStatusValue === 3) {
                // Từ "Chờ xử lý" không thể nhảy thẳng "Hoàn thành"
                alert('Không thể nhảy cóc trạng thái! Trạng thái tiếp theo phải là: Đã xác nhận');
                statusSelect.value = 'pending';
                return false;
            }
        }
    }
    
    return true;
}

// Validate form trước khi submit
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="orderForm"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const orderId = this.id.replace('orderForm', '');
            const statusSelect = document.getElementById('statusSelect' + orderId);
            const cancelReasonInput = document.getElementById('cancelReasonInput' + orderId);
            
            if (statusSelect.value === 'cancelled' && !cancelReasonInput.value.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập lý do hủy đơn hàng!');
                cancelReasonInput.focus();
                return false;
            }
        });
    });
});
</script>

@endsection