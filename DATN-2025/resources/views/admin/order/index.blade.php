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



    #copy-print-csv {
        table-layout: fixed;
        width: 100%;
    }
    #copy-print-csv th,
    #copy-print-csv td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    #copy-print-csv td:nth-child(11) {
        max-width: 100px;
    }
    .btn-danger{
    background-color: red;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}
</style>
  <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">

                                    <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success" style="margin-bottom: 16px;">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                        <div class="row" style="margin-bottom: 20px; display:flex; justify-content: space-between;" >
                                            <div class="col-md-7">
                                                <form method="GET" action="{{ route('admin.order.filter') }}" class="form-inline">
                                                  <div style="display: flex; align-items: center;">
                                                  <div  class="field-wrapper">
                                                        <div class="field-placeholder">Trạng thái đơn</div>
                                                    <select name="status" id="order_status" class="form-control" style="width: 150px; margin-right: 12px;">
                                                        <option value="">Tất cả</option>
                                                        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                        <option value="processing" {{ request('status')==='processing' ? 'selected' : '' }}>Đã xác nhận</option>
                                                        <option value="shipping" {{ request('status')==='shipping' ? 'selected' : '' }}>Đang giao</option>
                                                        <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                        <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                    </select></div>
                                                  <div  class="field-wrapper">    <div class="field-placeholder">Trạng thái thanh toán</div>
                                                    <select name="pay_status" id="pay_status" class="form-control" style="width: 150px; margin-right: 12px;">
                                                        <option value="">Tất cả</option>
                                                        <option value="0" {{ request('pay_status')==='0' ? 'selected' : '' }}>Chờ thanh toán</option>
                                                        <option value="1" {{ request('pay_status')==='1' ? 'selected' : '' }}>Đã thanh toán</option>
                                                        <option value="2" {{ request('pay_status')==='2' ? 'selected' : '' }}>Đã hủy</option>
                                                        <option value="3" {{ request('pay_status')==='3' ? 'selected' : '' }}>Hoàn tiền</option>
                                                    </select></div>
                                                    <button style="margin-top: -8px;" type="submit" class="btn btn-primary">Lọc</button>
                                                   <div  class="field-wrapper">
                                                   <div class="field-placeholder">Đơn/trang</div>
                                                    <select name="per_page" class="form-control" style="width: 80px; margin-left: 12px;" onchange="this.form.submit()">
                                                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 bản </option>
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
                                            <div class="col-md-5">
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
                                        <th width="5%">STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Trạng thái thanh toán</th>
                                        <th>Tổng tiền</th>
                                        <th>Ghi chú</th>
                                        <th>Lí do hủy</th>

                                        <th style="width:90px; text-align:center;" >Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if($orders->isEmpty())
                                  <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                                  </tr>
                                  @else
                                  @foreach ($orders as $key => $order)
                                    <tr>
                                       <td width="5%">{{ ($orders->currentPage()-1) * $orders->perPage() + $key + 1 }}</td>
                                        <td>{{ $order->name }}</td>
                                     @if($order->phone=='N/A')
                                     <td>Nhân viên</td>
                                     @else
                                     <td>{{ $order->phone }}</td>
                                     @endif
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span style="color: orange;">Chờ xử lý</span>
                                            @elseif ($order->status == 'processing')
                                                <span style="color: gray;">Đã xác nhận</span>
                                            @elseif ($order->status == 'shipping')
                                                <span style="color: blue;">Đang giao</span>
                                            @elseif ($order->status == 'completed')
                                                <span style="color: green;">Hoàn thành</span>
                                            @elseif ($order->status == 'cancelled')
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
                                            @elseif ($order->pay_status == 3)
                                                <span style="color: #e67e22;">Hoàn tiền</span>
                                            @else
                                                <span>{{ $order->pay_status }}</span>
                                            @endif
                                        </td>
                                         <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                        <td>{{ $order->note }}</td>
                                     @if($order->status == 'cancelled' || $order->pay_status == 2)
                                     <td>{{ $order->cancel_reason }}</td>
                                     @else
                                     <td></td>
                                     @endif

                                        <td style="width:100px; text-align:center;">
                                            <div style="display: flex; gap: 2px; justify-content: center;">
                                            <button style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;" type="button" class="btn-action btn-view"
                                                onclick="openOrderModal(this)"
                                                data-id="{{ $order->id }}"
                                                data-phone="{{ $order->phone }}"
                                                data-email="{{ $order->email }}"
                                                data-payment_method="{{ $order->payment_method }}"
                                                data-name="{{ $order->name }}"
                                                data-status="{{ $order->status }}"
                                                data-total="{{ number_format($order->total, 0, ',', '.') }} đ"
                                                data-pay_status="{{ $order->pay_status }}"
                                                data-created_at="{{ $order->created_at->format('d/m/Y') }}"
                                                data-shipping_fee="{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ"
                                                data-coupon_total_discount="{{ number_format($order->coupon_total_discount ?? 0, 0, ',', '.') }} đ"
                                                data-address_detail="{{ $order->district_name ? $order->district_name . ', ' : '' }}{{ $order->address_detail }}"
                                                data-product_total="{{ number_format(($order->total ?? 0) - ($order->shipping_fee ?? 0) + ($order->coupon_total_discount ?? 0), 0, ',', '.') }} đ"
                                                data-cancel_reason="{{ $order->cancel_reason }}"
                                            >Xem</button>

                                            </div>
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

<div id="orderModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
  <div  style="background:#fff; margin:2% auto; padding:20px; border-radius:8px; width:800px; position:relative;">
    <span onclick="closeOrderModal()" style="position:absolute; top:10px; right:20px; font-size:24px; cursor:pointer;">&times;</span>
    <h3>Thông tin hóa đơn</h3>
    <form id="orderForm" method="POST" action="{{ route('admin.order.update', ['id' => 0]) }}" onsubmit="return validateForm()">
      @csrf
      <div class="row">

        <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
        <input type="hidden" name="id" id="modal_id">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tên khách hàng - mã đơn </div>
        <input type="text" class="form-control" name="name" id="modal_name" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Trạng thái</div>
        <select name="status" id="modal_status" class="form-control">
         <option value="pending">Chờ xử lý</option>
         <option value="processing">Đã xác nhận</option>
         <option value="shipping">Đang giao</option>
         <option value="completed">Hoàn thành</option>
         <option value="cancelled">Đã hủy</option>
        </select>
      </div>
      </div>



      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Số điện thoại</div>
        <input type="text" class="form-control" name="phone" id="modal_phone" readonly />
      </div>
      </div>
      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Email</div>
        <input type="text" class="form-control" name="email" id="modal_email" readonly />
      </div>
      </div>

      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Hình thức thanh toán</div>
        <input type="text" class="form-control" name="payment_method" id="modal_payment_method" readonly />
      </div>
      </div>
      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3" id="pay_status_container">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Trạng thái thanh toán</div>
        <select class="form-control" name="pay_status" id="modal_pay_status">
          <option value="0">Chờ thanh toán</option>
          <option value="1">Đã thanh toán</option>
          <option value="2">Đã hủy</option>
          <option value="3">Hoàn tiền</option>
        </select>
      </div>
      </div>

      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Địa chỉ</div>
                <input type="text" class="form-control" name="address_detail" id="modal_address_detail" readonly />
      </div>
      </div>
      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Ngày tạo</div>
                <input type="text" class="form-control" name="created_at" id="modal_created_at" readonly />
      </div>
      </div>

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

                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>


      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tổng tiền</div>
        <input type="text" class="form-control" name="total" id="modal_total" readonly />
      </div>
      </div>
    <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tiền sản phẩm</div>
            <input type="text" class="form-control" name="product_total" id="modal_product_total" readonly />
      </div>
      </div>
      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
      <div style="margin-bottom:10px;">
        <div class="field-placeholder">Tiền ship</div>
        <input type="text" class="form-control" name="shipping_fee" id="modal_shipping_fee" readonly />
      </div>
      </div>



      <div class="field-wrapper col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
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

function resetDisableOptions() {
    const statusSelect = document.getElementById('modal_status');
    if (statusSelect) {
        [...statusSelect.options].forEach(option => {
            option.disabled = false;
            option.style.display = '';
        });
    }

    const payStatusSelect = document.getElementById('modal_pay_status');
    if (payStatusSelect) {
        [...payStatusSelect.options].forEach(option => {
            option.disabled = false;
            option.style.display = '';
        });
    }
    
    const payStatusContainer = document.getElementById('pay_status_container');
    if (payStatusContainer) {
        payStatusContainer.style.display = '';
    }
}

function openOrderModal(btn) {
    resetDisableOptions();
    document.getElementById('orderModal').style.display = 'block';
    document.getElementById('modal_id').value = btn.getAttribute('data-id');
    document.getElementById('modal_name').value = btn.getAttribute('data-name') + ' - ' + btn.getAttribute('data-id');
    const statusSelect = document.getElementById('modal_status');

    if( btn.getAttribute('data-phone')=='N/A'){
        document.getElementById('modal_phone').value = 'Nhân viên thu ngân';
        const shippingOption = statusSelect.querySelector('option[value="shipping"]');
        if (shippingOption) {
            shippingOption.style.display = 'none';
        }
        const pendingOption = statusSelect.querySelector('option[value="pending"]');
        const processingOption = statusSelect.querySelector('option[value="processing"]');
        if (pendingOption) {
            pendingOption.style.display = 'none';
        }
        if (processingOption) {
            processingOption.style.display = 'none';
        }
        const payStatusSelect = document.getElementById('modal_pay_status');
        const pendingPayOption = payStatusSelect.querySelector('option[value="0"]');
        if (pendingPayOption) {
            pendingPayOption.style.display = 'none';
        }
    } else {
        document.getElementById('modal_phone').value = btn.getAttribute('data-phone');
        const shippingOption = statusSelect.querySelector('option[value="shipping"]');
        const pendingOption = statusSelect.querySelector('option[value="pending"]');
        const processingOption = statusSelect.querySelector('option[value="processing"]');
        if (shippingOption) {
            shippingOption.style.display = '';
        }
        if (pendingOption) {
            pendingOption.style.display = '';
        }
        if (processingOption) {
            processingOption.style.display = '';
        }
        const payStatusSelect = document.getElementById('modal_pay_status');
        const pendingPayOption = payStatusSelect.querySelector('option[value="0"]');
        if (pendingPayOption) {
            pendingPayOption.style.display = '';
        }
    }
    document.getElementById('modal_email').value =  btn.getAttribute('data-email') ||'Nhân viên thu ngân';
    document.getElementById('modal_payment_method').value = btn.getAttribute('data-payment_method');
    const payStatusSelect = document.getElementById('modal_pay_status');
    const initialStatusValue = btn.getAttribute('data-status');
    const originalPayStatusFromButton = btn.getAttribute('data-pay_status');
    document.getElementById('orderForm').setAttribute('data-original-pay-status', originalPayStatusFromButton);

    statusSelect.value = initialStatusValue;
    payStatusSelect.value = originalPayStatusFromButton;
    statusSelect.setAttribute('data-original-status', initialStatusValue);
    if (originalPayStatusFromButton === '2' || originalPayStatusFromButton === '3') {
        payStatusSelect.setAttribute('disabled', 'disabled');
        if (!document.getElementById('hidden_pay_status')) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'pay_status';
            hidden.id = 'hidden_pay_status';
            hidden.value = originalPayStatusFromButton;
            payStatusSelect.parentNode.appendChild(hidden);
        } else {
            document.getElementById('hidden_pay_status').value = originalPayStatusFromButton;
        }
    } else {
        payStatusSelect.removeAttribute('disabled');
        const hidden = document.getElementById('hidden_pay_status');
        if (hidden) hidden.remove();
    }
    disableInvalidStatusOptions(initialStatusValue);
    disableInvalidPayStatusOptions(originalPayStatusFromButton);
    
    if (initialStatusValue === 'pending' || initialStatusValue === 'processing' || initialStatusValue === 'shipping') {
        payStatusSelect.removeAttribute('name');
    } else {
        payStatusSelect.setAttribute('name', 'pay_status');
    }
    
    document.getElementById('modal_total').value = btn.getAttribute('data-total');
    document.getElementById('modal_shipping_fee').value = btn.getAttribute('data-shipping_fee') || '0 đ';
    document.getElementById('modal_coupon_total_discount').value = btn.getAttribute('data-coupon_total_discount') || '0 đ';
    document.getElementById('modal_address_detail').value = btn.getAttribute('data-address_detail') || 'Nhân viên thu ngân';
    document.getElementById('modal_product_total').value = btn.getAttribute('data-product_total') || '0 đ';
    document.getElementById('modal_created_at').value = btn.getAttribute('data-created_at') || '';
    document.getElementById('orderForm').action = "{{ url('admin/order/update') }}/" + btn.getAttribute('data-id');

    const cancelReasonInput = document.getElementById('modal_cancel_reason');
    cancelReasonInput.removeAttribute('disabled');
    if (btn.getAttribute('data-cancel_reason')) {
        cancelReasonInput.value = btn.getAttribute('data-cancel_reason');
        cancelReasonInput.setAttribute('disabled', 'disabled');
    } else {
        cancelReasonInput.value = '';
        cancelReasonInput.removeAttribute('disabled');
    }

    checkCancelFields();

    var orderId = btn.getAttribute('data-id');
    fetch('/admin/order/json/' + orderId)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#order_products_table tbody');
            tbody.innerHTML = '';
            if (data.details && Array.isArray(data.details) && data.details.length > 0) {
                data.details.forEach(product => {
                    const row = `<tr>
                        <td>${product.product_name ?? ''}</td>
                        <td>${product.product_image ? `<img src='/storage/uploads/${product.product_image}' width='50'>` : ''}</td>
                        <td>${product.size ?? ''}</td>
                        <td>${product.topping ? `<p>${product.topping}</p>` : `<span style=\"color: red;\">Không chọn</span>`}</td>
                        <td>${product.quantity ?? ''}</td>
                        <td>${product.total !== undefined ? parseInt(product.total).toLocaleString('vi-VN') + ' đ' : ''}</td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Không có sản phẩm</td></tr>';
            }
        })
        .catch(() => {
            document.querySelector('#order_products_table tbody').innerHTML =
                '<tr><td colspan="8" style="text-align:center; color:red;">Lỗi lấy dữ liệu</td></tr>';
        });
}

function closeOrderModal() {
    document.getElementById('orderModal').style.display = 'none';
}

function checkCancelFields() {
    const status = document.getElementById('modal_status').value;
    const payStatus = document.getElementById('modal_pay_status').value;
    const cancelReasonContainer = document.getElementById('cancel_reason_container');
    const cancelReasonInput = document.getElementById('modal_cancel_reason');

    cancelReasonContainer.style.display = 'none';
    cancelReasonInput.required = false;

    if (status === 'cancelled' || payStatus === '2') {
        cancelReasonContainer.style.display = 'block';
        cancelReasonInput.required = true;
    }
}

function disableInvalidStatusOptions(originalStatus) {
    const select = document.getElementById('modal_status');
    const statusOrder = { 'pending': 0, 'processing': 1, 'shipping': 2, 'completed': 3, 'cancelled': 4 };
    const phone = document.getElementById('modal_phone').value;
    const isStaffOrder = (phone === 'Nhân viên thu ngân');

    [...select.options].forEach(option => {
        const val = option.value;
        option.disabled = false;

        if (originalStatus === 'completed' || originalStatus === 'cancelled') {
            if (val !== originalStatus) {
                option.disabled = true;
            }
            return;
        }

        if (statusOrder[val] < statusOrder[originalStatus] && val !== 'cancelled') {
            option.disabled = true;
        }

        const isJumping = statusOrder[val] > statusOrder[originalStatus] + 1;
        const isAllowedJump = isStaffOrder && originalStatus === 'processing' && val === 'completed';

        if (isJumping && !isAllowedJump && val !== 'cancelled') {
            option.disabled = true;
        }
    });
}

function disableInvalidPayStatusOptions(originalPayStatus) {
    const select = document.getElementById('modal_pay_status');
    const original = parseInt(originalPayStatus);
    const status = document.getElementById('modal_status').value;

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

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modal_status').addEventListener('change', function() {
        checkCancelFields();
        disableInvalidPayStatusOptions(document.getElementById('modal_pay_status').value);
    });

    document.getElementById('modal_pay_status').addEventListener('change', function() {
        checkCancelFields();
    });
});

function validateForm() {
    const status = document.getElementById('modal_status').value;
    const payStatus = document.getElementById('modal_pay_status').value;
    const cancelReason = document.getElementById('modal_cancel_reason').value;
    const phone = document.getElementById('modal_phone').value;
    const originalPayStatus = document.getElementById('orderForm').getAttribute('data-original-pay-status');

    if ((status === 'pending' || status === 'processing' || status === 'shipping') && payStatus !== originalPayStatus) {
        alert('Không thể thay đổi trạng thái thanh toán khi đơn hàng đang ở trạng thái "Chờ xử lý", "Đã xác nhận" hoặc "Đang giao".');
        return false;
    }

    if (phone === 'Nhân viên thu ngân' && status === 'shipping') {
        alert('Đơn hàng tại quầy không thể có trạng thái "Đang giao".');
        return false;
    }

    if (status === 'cancelled') {
        if (originalPayStatus === '0' && payStatus !== '2') { 
            alert('Đơn chưa thanh toán. Khi hủy, trạng thái thanh toán phải được chuyển thành "Đã hủy".');
            return false;
        }
        if (originalPayStatus === '1' && payStatus !== '3') { 
            alert('Đơn đã thanh toán. Khi hủy, trạng thái thanh toán phải được chuyển thành "Hoàn tiền".');
            return false;
        }
    }

    if ((status === 'cancelled' || payStatus === '2') && !cancelReason.trim()) {
        alert('Vui lòng nhập lý do hủy đơn hàng');
        document.getElementById('modal_cancel_reason').focus();
        return false;
    }

    if (phone === 'Nhân viên thu ngân' && status === 'completed' && payStatus !== '1') {
        alert('Đơn tại quầy hoàn thành phải có trạng thái thanh toán là Đã thanh toán.');
        return false;
    }

    if (status === 'completed' && payStatus !== '1') {
        alert('Đơn hoàn thành phải có trạng thái thanh toán là Đã thanh toán.');
        return false;
    }

    return true;
}
</script>


