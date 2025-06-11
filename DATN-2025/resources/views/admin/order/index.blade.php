@include('header')
<style>
       th{
            text-align: center;
        }
        td{
            text-align: center;
        }
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
    .pagination {
        font-size: 12px;
    }
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        min-width: 28px;
    }

</style>
  <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">

                                    <div class="card-body">

                                        <div class="row" style="margin-bottom: 20px; display:flex; justify-content: space-between;" >
                                            <div class="col-md-6">
                                                <form method="GET" action="{{ route('admin.order.filter') }}" class="form-inline">
                                                  <div style="display: flex; align-items: center;">
                                                  <div  class="field-wrapper">    <div class="field-placeholder">Trạng thái thanh toán</div>
                                                    <select name="pay_status" id="pay_status" class="form-control" style="width: 150px; margin-right: 12px;">
                                                        <option value="">Tất cả</option>
                                                        <option value="0" {{ request('pay_status')==='0' ? 'selected' : '' }}>Chờ thanh toán</option>
                                                        <option value="1" {{ request('pay_status')==='1' ? 'selected' : '' }}>Đã thanh toán</option>
                                                        <option value="2" {{ request('pay_status')==='2' ? 'selected' : '' }}>Đã hủy</option>
                                                    </select></div>
                                                    <div  class="field-wrapper">
                                                        <div class="field-placeholder">Trạng thái đơn</div>
                                                    <select name="status" id="status" class="form-control" style="width: 150px; margin-right: 12px;">
                                                        <option value="">Tất cả</option>
                                                        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                        <option value="processing" {{ request('status')==='processing' ? 'selected' : '' }}>Đã xác nhận</option>
                                                        <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                        <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                    </select></div>
                                                    <button style="margin-top: -8px;" type="submit" class="btn btn-primary">Lọc</button>
                                                   <div  class="field-wrapper">
                                                   <div class="field-placeholder">Đơn/trang</div>
                                                    <select name="per_page" class="form-control" style="width: 80px; margin-left: 12px;" onchange="this.form.submit()">
                                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bản </option>
                                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản  </option>
                                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 bản</option>
                                                    </select>
                                                    @foreach(request()->except(['per_page','page','pay_status','status']) as $key => $val)
                                                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                                    @endforeach
                                                    </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form method="GET" action="{{ route('admin.order.search') }}" class="form-inline" style="float: right; display: flex; align-items: center;">
                                                    <input type="text" name="transaction_id" class="form-control" placeholder="Tìm kiếm tên và số điện thoại ..." value="{{ request('transaction_id') }}" style="width: 220px; margin-right: 8px;">
                                                    <button type="submit" class="btn btn-success">Tìm kiếm</button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Trạng thái</th>
                                        <th>Trạng thái thanh toán</th>
                                        <th>Ngày tạo</th>
                                        <th>Lí do hủy</th>
                                        <th>Tổng tiền</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if($orders->isEmpty())
                                  <tr>
                                    <td colspan="10" class="text-center">Không có dữ liệu</td>
                                  </tr>
                                  @else
                                  @foreach ($orders as $key => $order)
                                    <tr>
                                       <td>{{ ($orders->currentPage()-1) * $orders->perPage() + $key + 1 }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->address_detail }}, {{ $order->district_name }}</td>
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
                                       
                                        <td>
                                            @if ($order->pay_status == 0)
                                                <span style="color: orange;">Chờ thanh toán</span>
                                            @elseif ($order->pay_status == 1)
                                                <span style="color: green;">Đã thanh toán</span>
                                            @elseif ($order->pay_status == 2)
                                                <span style="color: red;">Đã hủy</span>
                                            @else
                                                <span>{{ $order->pay_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        
                                     @if($order->status == 'cancelled' || $order->pay_status == 2)
                                     <td>{{ $order->cancel_reason }}</td>
                                     @else
                                     <td></td>
                                     @endif
                                      <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                        <td>
                                            <button type="button" class="btn-action btn-view"
                                                onclick="openOrderModal(this)"
                                                data-id="{{ $order->id }}"
                                                data-name="{{ $order->name }}"
                                                data-status="@if ($order->status == 'pending' || $order->status == 0)Chờ xử lý@elseif ($order->status == 'processing' || $order->status == 1)Đã xác nhận @elseif ($order->status == 'completed' || $order->status == 3)Hoàn thành @elseif ($order->status == 'cancelled' || $order->status == 4)Đã hủy @else{{ $order->status }}@endif"
                                                data-total="{{ number_format($order->total, 0, ',', '.') }} đ"
                                                data-pay_status="{{ $order->pay_status }}"
                                                data-created_at="{{ $order->created_at }}"
                                                data-shipping_fee="{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ"
                                                data-coupon_total_discount="{{ number_format($order->coupon_total_discount ?? 0, 0, ',', '.') }} đ"
                                                data-address_detail="{{ $order->district_name ? $order->district_name . ', ' : '' }}{{ $order->address_detail }}"
                                                data-product_total="{{ number_format(($order->total ?? 0) - ($order->shipping_fee ?? 0) - ($order->coupon_total_discount ?? 0), 0, ',', '.') }} đ"
                                                data-cancel_reason="{{ $order->cancel_reason }}"
                                            >Xem</button>
                                            <a href="{{ route('admin.order.delete', $order->id) }}" class="btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                            </table>
                        </div>
                        @php
                            $from = $orders->firstItem();
                            $to = $orders->lastItem();
                            $total = $orders->total();
                            $currentPage = $orders->currentPage();
                            $lastPage = $orders->lastPage();
                        @endphp
                        <div class="text-muted mb-2" style="font-size:13px;">
                            Trang {{ $currentPage }}/{{ $lastPage }},
                            Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $orders->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('footer')
    </div>
</div>

<!-- Modal Popup -->
<div id="orderModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
  <div  style="background:#fff; margin:2% auto; padding:20px; border-radius:8px; width:800px; position:relative;">
    <span onclick="closeOrderModal()" style="position:absolute; top:10px; right:20px; font-size:24px; cursor:pointer;">&times;</span>
    <h3>Thông tin hóa đơn</h3>
    <form id="orderForm" method="POST" action="{{ route('admin.order.update', ['id' => 0]) }}" onsubmit="return validateForm()">
      @csrf
      <div class="row">

        <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
        <input type="hidden" name="id" id="modal_id">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tên khách hàng</div>
        <input type="text" class="form-control" name="name" id="modal_name" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Trạng thái</div>
        <select name="status" id="modal_status" class="form-control">
         <option value="pending">Chờ xử lý</option>
         <option value="processing">Đã xác nhận</option>
         <option value="completed">Hoàn thành</option>
         <option value="cancelled">Đã hủy</option>
        </select>
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tổng tiền</div>
        <input type="text" class="form-control" name="total" id="modal_total" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Mã giao dịch</div>
        <input type="text" class="form-control" name="transaction_id" id="modal_transaction_id" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Trạng thái thanh toán</div>
        <select class="form-control" name="pay_status" id="modal_pay_status">
          <option value="0">Chờ thanh toán</option>
          <option value="1">Đã thanh toán</option>
          <option value="2">Đã hủy</option>
        </select>
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Địa chỉ</div>
                <input type="text" class="form-control" name="address_detail" id="modal_address_detail" readonly />
      </div>
      </div>

      <!-- Bảng sản phẩm -->
      <div class="field-wrapper col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12  ">
        <div style="margin-bottom:10px;">
          <div class="field-placeholder">Danh sách sản phẩm</div>
          <div id="order_products_container">
            <table class="order-table" id="order_products_table">
              <thead>
                <tr>

                  <th>Tên sản phẩm</th>
                  <th>Ảnh</th>
                  <th>Size</th>
                  <th>Topping</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                  <th>Ghi chú</th>
                </tr>
              </thead>
              <tbody>
                <!-- Sản phẩm sẽ được render ở đây -->
              </tbody>
            </table>
          </div>
        </div>
      </div>



      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tiền ship</div>
        <input type="text" class="form-control" name="shipping_fee" id="modal_shipping_fee" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tiền sản phẩm</div>
            <input type="text" class="form-control" name="product_total" id="modal_product_total" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tiền giảm giá</div>
        <input type="text" class="form-control" name="coupon_total_discount" id="modal_coupon_total_discount" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="cancel_reason_container" style="display:none;">
        <div style="margin-bottom:10px;">
          <div class="field-placeholder">Lý do hủy <span style="color:red;">*</span></div>
          <input type="text" class="form-control" name="cancel_reason" id="modal_cancel_reason" />
        </div>
      </div>

      <button type="submit" class="btn-action btn-view">Cập nhật đơn hàng</button>
        </div>

    </form>
  </div>
</div>
<script>
function openOrderModal(btn) {
  document.getElementById('orderModal').style.display = 'block';
  document.getElementById('modal_id').value = btn.getAttribute('data-id');
  document.getElementById('modal_name').value = btn.getAttribute('data-name');

  // Đặt giá trị cho dropdown status
  const statusText = btn.getAttribute('data-status');
  const statusSelect = document.getElementById('modal_status');
  if (statusText.includes('Chờ xử lý')) {
    statusSelect.value = 'pending';
  } else if (statusText.includes('Đã xác nhận')) {
    statusSelect.value = 'processing';
  } else if (statusText.includes('Hoàn thành')) {
    statusSelect.value = 'completed';
  } else if (statusText.includes('Đã hủy')) {
    statusSelect.value = 'cancelled';
  }

  document.getElementById('modal_total').value = btn.getAttribute('data-total');
  document.getElementById('modal_transaction_id').value = btn.getAttribute('data-transaction_id');
  document.getElementById('modal_pay_status').value = btn.getAttribute('data-pay_status');
  document.getElementById('modal_cancel_reason').value = btn.getAttribute('data-cancel_reason') || '';

  // Thêm các trường mới
  document.getElementById('modal_shipping_fee').value = btn.getAttribute('data-shipping_fee') || '0 đ';
  document.getElementById('modal_coupon_total_discount').value = btn.getAttribute('data-coupon_total_discount') || '0 đ';
  document.getElementById('modal_address_detail').value = btn.getAttribute('data-address_detail') || '';
  document.getElementById('modal_product_total').value = btn.getAttribute('data-product_total') || '0 đ';

  document.getElementById('orderForm').action = "{{ url('admin/order/update') }}/" + btn.getAttribute('data-id');

  // Kiểm tra trạng thái để hiển thị ô lí do hủy
  checkCancelFields();

  // Thêm event listeners cho các select
  document.getElementById('modal_status').addEventListener('change', checkCancelFields);
  document.getElementById('modal_pay_status').addEventListener('change', checkCancelFields);

  // Lấy sản phẩm của đơn hàng
  var orderId = btn.getAttribute('data-id');
  fetch('/admin/order/json/' + orderId)
    .then(function(response) { return response.json(); })
    .then(function(data) {
      var tbody = document.querySelector('#order_products_table tbody');
      tbody.innerHTML = '';
      // Kiểm tra có details không
      if (data.details && Array.isArray(data.details) && data.details.length > 0) {
        data.details.forEach(function(product) {
          var row = `<tr>
            <td>${product.product_name ?? ''}</td>
            <td>${product.product_image ? `<img src='/storage/uploads/${product.product_image}' width='50'>` : ''}</td>
            <td>${product.size ?? ''}</td>
            <td>
            ${product.topping ?
                product.topping :
                `<span style="color: red;">Không chọn</span>`
            }
            </td>
            <td>${product.quantity ?? ''}</td>
            <td>${product.total !== undefined ? parseInt(product.total).toLocaleString('vi-VN') + ' đ' : ''}</td>
            <td>${product.note ?? ''}</td>
          </tr>`;
          tbody.innerHTML += row;
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Không có sản phẩm</td></tr>';
      }
    })
    .catch(function(err) {
      var tbody = document.querySelector('#order_products_table tbody');
      tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; color:red;">Lỗi lấy dữ liệu</td></tr>';
    });
}
function closeOrderModal() {
  document.getElementById('orderModal').style.display = 'none';
}

// Kiểm tra và hiển thị ô lí do hủy
function checkCancelFields() {
  const status = document.getElementById('modal_status').value;
  const payStatus = document.getElementById('modal_pay_status').value;
  const cancelReasonContainer = document.getElementById('cancel_reason_container');

  if (status === 'cancelled' || payStatus === '2') {
    cancelReasonContainer.style.display = 'block';
    document.getElementById('modal_cancel_reason').required = true;
  } else {
    cancelReasonContainer.style.display = 'none';
    document.getElementById('modal_cancel_reason').required = false;
  }
}

// Validate form trước khi submit
function validateForm() {
  const status = document.getElementById('modal_status').value;
  const payStatus = document.getElementById('modal_pay_status').value;
  const cancelReason = document.getElementById('modal_cancel_reason').value;

  if ((status === 'cancelled' || payStatus === '2') && !cancelReason.trim()) {
    alert('Vui lòng nhập lý do hủy đơn hàng');
    document.getElementById('modal_cancel_reason').focus();
    return false;
  }
  return true;
}
</script>
