@include('header')
<style>
    .order-table {
        width: 100%;
        border-collapse: collapse;
    }
    .order-table th, .order-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .order-table th {
        background-color: #f2f2f2;
    }
    .btn-action {
        padding: 4px 12px;
        border: none;
        border-radius: 4px;
        margin-right: 4px;
        cursor: pointer;
    }
    .btn-view {
        background: #3498db;
        color: #fff;
    }
    .btn-delete {
        background: #e74c3c;
        color: #fff;
    }
</style>
  <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">

                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Tên khách hàng</th>
                                        <th>Địa chỉ</th>
                                        <th>Số điện thoại</th>
                                        <th>Phương thức thanh toán</th>
                                        <th>Trạng thái</th>
                                        <th>Tổng tiền</th>
                                        <th>Mã giao dịch</th>
                                        <th>Trạng thái thanh toán</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày cập nhật</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user_id }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->address }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            @if ($order->status == 'pending' || $order->status == 0)
                                                <span style="color: orange;">Chờ xử lý</span>
                                            @elseif ($order->status == 'processing' || $order->status == 1)
                                                <span style="color: green;">Đã xác nhận</span>
                                            @elseif ($order->status == 'completed' || $order->status == 3)
                                                <span style="color: gray;">Hoàn thành</span>
                                            @elseif ($order->status == 'cancelled' || $order->status == 4)
                                                <span style="color: red;">Đã hủy</span>
                                            @else
                                                <span>{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                        <td>{{ $order->transaction_id }}</td>
                                        <td>{{ $order->pay_status }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.order.show', $order->id) }}" class="btn-action btn-view">Xem</a>
                                            <a href="{{ route('admin.order.delete', $order->id) }}" class="btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('footer')
    </div>
</div>
