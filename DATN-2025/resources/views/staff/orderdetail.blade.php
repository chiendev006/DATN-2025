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
                    <table class="w-full text-sm border border-gray-200 rounded-lg">
                        <thead class="bg-indigo-100 text-indigo-800 uppercase">
                            <tr>
                                <th class="px-4 py-2 border text-left">👤 Khách hàng</th>
                                <th class="px-4 py-2 border text-left">Email</th>
                                <th class="px-4 py-2 border text-left">📞 SĐT</th>
                                <th class="px-4 py-2 border text-left">🏠 Địa chỉ</th>
                                <th class="px-4 py-2 border text-left">💳 Thanh toán</th>
                                <th class="px-4 py-2 border text-left">📦 Sản phẩm</th>
                                <th class="px-4 py-2 border text-center">Số lượng</th>
                                <th class="px-4 py-2 border text-right">Tổng tiền</th>
                                <th class="px-4 py-2 border text-center">Trạng thái</th>
                                <th class="px-4 py-2 border text-center">Ghi chú</th>
                                <th class="px-4 py-2 border text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border text-gray-700 font-medium">{{ $item->name ?? 'Khách vãng lai' }}</td>
                                <td class="px-4 py-2 border text-gray-600">{{ $item->email ?? 'không có' }}</td>
                                <td class="px-4 py-2 border">{{ $item->phone ?? 'không có' }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $item->address_detail }}{{ $item->district_name ? ', ' . $item->district_name : 'không có' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $item->payment_method === 'cash' ? 'Tiền mặt 💵' : 'Thẻ 💳' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                                        @foreach ($item->details as $ct)
                                            <li>{{ $ct->product_name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <ul class="space-y-1 text-gray-700 font-semibold">
                                        @foreach ($item->details as $ct)
                                            <li>x{{ $ct->quantity }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border text-right font-bold text-green-700">
                                    {{ number_format($item->total, 0, ',', '.') }} đ
                                </td>
                                <td class="px-4 py-2 border text-center text-sm font-semibold">
                                    @php
                                        $statusMap = [
                                            'pending' => ['Chờ xử lý', 'orange'],
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

                <!-- Modal -->
                <div class="modal fade" id="modalOrderDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <form action="{{ route('orders.updateStatus', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content shadow border-0 rounded-3">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Thông tin hóa đơn #{{ $item->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label>Tên khách hàng</label>
                                        <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                                    </div>
                                   <div class="col-md-3">
                                        <label>Trạng thái</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" {{ $item->status == 'pending' || $item->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                                            <option value="processing" {{ $item->status == 'processing' || $item->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                                            <option value="completed" {{ $item->status == 'completed' || $item->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="cancelled" {{ $item->status == 'cancelled' || $item->status == 4 ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Tổng tiền</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->total, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Mã giao dịch</label>
                                        <input type="text" class="form-control" value="{{ $item->id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Thanh toán</label>
                                        <input type="text" class="form-control" value=" {{ $item->payment_method === 'cash' ? 'Tiền mặt' : 'Thẻ' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Địa chỉ</label>
                                        <input type="text" class="form-control" value="{{ $item->address_detail }}, {{ $item->district_name }}" readonly>
                                    </div>
                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tên sản phẩm</th>
                                                <th>Ảnh</th>
                                                <th>Size</th>
                                                <th>Topping</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
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

                                <div class="row g-3 mt-3">
                                    <div class="col-md-4">
                                        <label>Tiền ship</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->shipping_fee ?? 0, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Tổng tiền thanh toán</label>
                                        <input type="text" class="form-control" 
                                            value="{{ number_format(($item->total ?? 0) + ($item->discount ?? 0) - ($item->shipping_fee ?? 0), 0, ',', '.') }} đ" 
                                            readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Tiền giảm giá</label>
                                        <input type="text" class="form-control" value="{{ number_format($item->discount ?? 0, 0, ',', '.') }} đ" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-sm btn-success mt-2">Cập nhật</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection