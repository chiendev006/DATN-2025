@include('header')
<style>
    .btn-success {
    width: 180px;
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
  .btn-success:hover {
    background-color: rgb(0, 0, 217);
  }
</style>
    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">
                                <button type="button" id="btn-add-Coupon" class="btn-success">Thêm Coupon</button>
                                    <div class="card-body">
                                        <form method="GET" action="" style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                                            <label for="per_page" style="margin-bottom:0;">Bản/trang:</label>
                                            <select name="per_page" id="per_page" class="form-control" style="width: 80px;" onchange="this.form.submit()">
                                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 bản</option>
                                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 bản</option>
                                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản</option>
                                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                            </select>
                                            @foreach(request()->except(['per_page','page']) as $key => $val)
                                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                            @endforeach
                                        </form>
                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Mã Coupon</th>
                                                        <th>Giảm giá</th>
                                                        <th>Giá trị đơn tối thiểu</th>
                                                        <th>Số lượng</th>
                                                        <th>Đã dùng</th>
                                                        <th>Ngày kết thúc</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($coupons->isEmpty())
                                                    <tr>
                                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                    @else
                                                            @foreach ($coupons as $key => $item)
                                                            <tr>
                                                                <td>{{ ($coupons->currentPage()-1) * $coupons->perPage() + $key + 1 }}</td>
                                                                <td>
                                                                {{ $item['code'] }}
                                                                </td>
                                                                <td>
                                                                 @if($item['type'] == 'percent')
                                                                {{ number_format($item['discount'], 0, ',', '.') }} %
                                                                 @else
                                                                 {{ number_format($item['discount'], 0, ',', '.') }} VNĐ
                                                                 @endif
                                                                </td>
                                                                <td>
                                                                    {{ number_format($item['min_order_value'], 0, ',', '.') }} VNĐ
                                                                </td>
                                                                <td>
                                                                   @if($item['usage_limit'] == 0)
                                                                   Không giới hạn
                                                                   @else
                                                                   {{ $item['usage_limit'] }}
                                                                   @endif
                                                                </td>
                                                                <td>
                                                                    {{ $item['used'] }}
                                                                </td>
                                                                 <td>
                                                                    {{ $item['expires_at'] }}
                                                                </td>
                                                                <td>
                                                                    <div class="actions">
                                                                    <button type="button" class="btn-edit-coupon"
                                                                        data-id="{{ $item->id }}"
                                                                        data-code="{{ $item->code }}"
                                                                        data-discount="{{ $item->discount }}"
                                                                        data-type="{{ $item->type }}"
                                                                        data-min_order_value="{{ $item->min_order_value }}"
                                                                        data-usage_limit="{{ $item->usage_limit }}"
                                                                        data-expires_at="{{ $item->expires_at }}"
                                                                        style="background:none;border:none;cursor:pointer;">
                                                                            <i class="icon-edit1 text-info"></i>
                                                                        </button>

                                                                        </button>

                                                                        <form action="{{ route('coupon.delete', ['id' => $item->id]) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa Coupon này?')">Xóa</button>
                                                                        </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-muted mb-2" style="font-size:13px;">
                                            Trang {{ $coupons->currentPage() }}/{{ $coupons->lastPage() }},
                                            Hiển thị {{ $coupons->firstItem() }}-{{ $coupons->lastItem() }}/{{ $coupons->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $coupons->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @include('footer')
                    <!-- Modal popup edit Coupon, chỉ 1 lần duy nhất ngoài vòng lặp -->
                    <div id="editCouponModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-Coupon-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa Coupon</h3>
                            <form id="editCouponForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper ">
                                    <div class="field-placeholder">Code </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input" type="text" id="edit-code" name="code" placeholder="Code">
                                                <span class="icon left"><i class="mdi mdi-account"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <div class="field-placeholder">Giảm giá </div>
                                <div class="control" style="display: flex; align-items: center; gap: 10px;">
                                <input class="input" type="number" id="edit-discount" name="discount" placeholder="Trị giá">
                                  <select name="type" name="type" id="edit-type">
                                    <option id="edit-type-percent" value="percent">Phần trăm (%)</option>
                                    <option id="edit-type-fixed" value="fixed">Cố định (VNĐ)</option>
                                  </select>
                                </div>
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Giá trị đơn tối thiểu </div>
                                  <input class="input" type="number" id="edit-min_order_value" name="min_order_value" placeholder="Giá trị đơn tối thiểu">
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Số lượng </div>
                                  <input class="input" type="number" id="edit-usage_limit" name="usage_limit" placeholder="Số lượng">
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Ngày kết thúc </div>
                                  <input class="input" type="date" id="edit-expires_at" name="expires_at" placeholder="Ngày kết thúc">
                                </div>
                                <div class="field grouped">
                                    <div class="control">
                                        <button type="submit" class="btn-success">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Thêm
    const addModal = document.getElementById('addCouponModal');
    const closeAddBtn = document.getElementById('close-add-Coupon-modal');
    const addNameInput = document.getElementById('add-name');
    const addHasCouponRadio1 = document.getElementById('addInlineRadio1');
    const addHasCouponRadio2 = document.getElementById('addInlineRadio2');
    const addForm = document.getElementById('addCouponForm');

    // Modal Sửa
    const editModal = document.getElementById('editCouponModal');
    const closeEditBtn = document.getElementById('close-edit-Coupon-modal');
    const editCodeInput = document.getElementById('edit-code');
    const editDiscountInput = document.getElementById('edit-discount');
    const editMinOrderValueInput = document.getElementById('edit-min_order_value');
    const editTypeInput = document.getElementById('edit-type');
    const editUsageLimitInput = document.getElementById('edit-usage_limit');
    const editExpiresAtInput = document.getElementById('edit-expires_at');
    const editForm = document.getElementById('editCouponForm');

    // Sự kiện cho nút "Thêm Coupon"
    document.getElementById('btn-add-Coupon').addEventListener('click', function() {
        // Reset form về trạng thái rỗng
        if (addNameInput) addNameInput.value = '';
        if (addHasCouponRadio1) addHasCouponRadio1.checked = false;
        if (addHasCouponRadio2) addHasCouponRadio2.checked = false;
        // Hiển thị modal
        addModal.style.display = 'flex';
    });

    // Đóng popup Thêm
    closeAddBtn.onclick = function() {
        addModal.style.display = 'none';
    };
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
    });

    // Sự kiện cho các nút sửa
    document.querySelectorAll('.btn-edit-coupon').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemCode = this.dataset.code;
            const itemDiscount = this.dataset.discount;
            const itemType = this.dataset.type;
            const itemMinOrderValue = this.dataset.min_order_value;
            const itemUsageLimit = this.dataset.usage_limit;
            const itemExpiresAt = this.dataset.expires_at;

            // Cập nhật action của form trong modal

            editForm.action = `coupon/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editCodeInput) editCodeInput.value = itemCode;
            if (editDiscountInput) editDiscountInput.value = Number(itemDiscount);
            if (editTypeInput) {
                if (itemType == 'percent') {
                    document.getElementById('edit-type-percent').selected = true;
                } else if (itemType == 'fixed') {
                    document.getElementById('edit-type-fixed').selected = true;
                }
            }
            if (editMinOrderValueInput) editMinOrderValueInput.value = Number(itemMinOrderValue);
            if (editUsageLimitInput) editUsageLimitInput.value = itemUsageLimit;
            if (editExpiresAtInput) editExpiresAtInput.value = itemExpiresAt;

            // Hiển thị modal
            editModal.style.display = 'flex';
        });
    });

    // Đóng popup Sửa
    closeEditBtn.onclick = function() {
        editModal.style.display = 'none';
    };
    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    });
});
</script>

<div id="addCouponModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-add-Coupon-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3>Thêm mã giảm giá</h3>
        <form id="addCouponForm" method="post" action="{{ route('coupon.store') }}">
            @csrf
            <div class="field-wrapper ">
                <div class="field-placeholder">Mã Code</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="add-name" name="code" placeholder="Code">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field-wrapper">
                <div class="field-placeholder">Giảm giá</div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input" type="number" id="add-price" name="discount" placeholder="Trị giá ">
                  <select name="type" name="type" id="type">
                    <option value="percent">Phần trăm (%)</option>
                    <option value="fixed">Cố định (VNĐ)</option>
                  </select>
                </div>
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Giá trị đơn tối thiểu</div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input" type="number" id="add-price" name="min_order_value" placeholder="Giá trị đơn tối thiểu">
                </div>
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Số lượng</div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input" type="number" id="add-
                  " name="usage_limit" placeholder="Số lượng">
                </div>
            </div>

            <div class="field-wrapper">
                <div class="field-placeholder">Ngày kết thúc</div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input" type="date" id="add-price" name="expires_at" placeholder="Ngày kết thúc">
                </div>
            </div>

            <div class="field grouped">
                <div class="control">
                    <button type="submit" class="btn-success">Thêm mới</button>
                </div>
            </div>
        </form>
    </div>
</div>
