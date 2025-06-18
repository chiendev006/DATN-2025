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
                                <button type="button" id="btn-add-Coupon" class="btn-success">Thêm Coupon</button>
                                    <div class="card-body">
                                        <form method="GET" action="" style="margin-bottom: 20px;">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="code" style="font-weight: 500;">Mã coupon</label>
                                                    <input type="text" name="code" id="code" class="form-control" value="{{ request('code') }}" placeholder="Nhập mã coupon...">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="type" style="font-weight: 500;">Loại giảm giá</label>
                                                    <select name="type" id="type" class="form-control">
                                                        <option value="">Tất cả</option>
                                                        <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>%</option>
                                                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>VNĐ</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="status" style="font-weight: 500;">Trạng thái</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="">Tất cả</option>
                                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Còn hạn</option>
                                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                                        <option value="used_up" {{ request('status') == 'used_up' ? 'selected' : '' }}>Đã dùng hết</option>
                                                        <option value="not_used" {{ request('status') == 'not_used' ? 'selected' : '' }}>Chưa dùng</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="starts_at" style="font-weight: 500;">Ngày bắt đầu từ</label>
                                                    <input type="date" name="starts_at" id="starts_at" class="form-control" value="{{ request('starts_at') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="expires_at" style="font-weight: 500;">Ngày kết thúc đến</label>
                                                    <input type="date" name="expires_at" id="expires_at" class="form-control" value="{{ request('expires_at') }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="usage_left" style="font-weight: 500;">Số lượng &ge;</label>
                                                    <input type="number" name="usage_left" id="usage_left" class="form-control" value="{{ request('usage_left') }}" min="0">
                                                </div>
                                                <div class="col-md-1" style="display: flex; align-items: end; gap: 10px;">
                                                    <button type="submit" class="btn btn-primary" style="height: 38px;">Lọc</button>
                                                    <a href="{{ route('coupon.index') }}" class="btn btn-secondary" style="height: 38px;">Làm mới</a>
                                                </div>
                                            </div>
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
                                                        <th>Ngày bắt đầu</th>
                                                        <th>Ngày kết thúc</th>
                                                        <th style="width:90px; text-align:center;">Hành động</th>
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
                                                                   @if($item['usage_limit'] ==0)
                                                                    Hết lượt sử dụng
                                                                   @else
                                                                   {{ $item['usage_limit'] }}
                                                                   @endif
                                                                </td>
                                                                <td>
                                                                    {{ $item['used'] }}
                                                                </td>
                                                                <td>                                                   
                                                                        {{ $item->starts_at->format('d/m/Y') }}
                                                                </td>
                                                                <td>
                                                                @if ( $item->expires_at!=null)
                                                                {{ $item->expires_at->format('d/m/Y') }}
                                                                @else
                                                              Vô thời hạn

                                                                @endif
                                                                </td>
                                                                <td>
                                                                    <div  class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                                    <button type="button" class="btn-edit-coupon"
                                                                        data-id="{{ $item->id }}"
                                                                        data-code="{{ $item->code }}"
                                                                        data-discount="{{ $item->discount }}"
                                                                        data-type="{{ $item->type }}"
                                                                        data-starts_at="{{ $item->starts_at->format('d/m/Y') }}"
                                                                        data-min_order_value="{{ $item->min_order_value }}"
                                                                        data-usage_limit="{{ $item->usage_limit }}"
                                                                        data-expires_at="{{ $item->expires_at ? $item->expires_at->format('Y-m-d') : '' }}"
                                                                        style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;">
                                                                        Sửa

                                                                        </button>



                                                                        <form action="{{ route('coupon.delete', ['id' => $item->id]) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa Coupon này?')">Xóa</button>
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
                    <div id="editCouponModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-Coupon-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa Coupon</h3>
                            <form id="editCouponForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper ">
                                    <div class="field-placeholder">Mã Code <span class="text-danger">*</span></div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input @error('code') is-invalid @enderror" type="text" id="edit-code" name="code" placeholder="Code" value="{{ old('code') }}">
                                            </div>
                                            @error('code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Giảm giá <span class="text-danger">*</span></div>
                                    <div class="control" style="display: flex; align-items: center; gap: 10px;">
                                        <input class="input @error('discount') is-invalid @enderror" type="number" id="edit-discount" name="discount" placeholder="Trị giá" value="{{ old('discount') }}">
                                        <select name="type" id="edit-type" class="form-control @error('type') is-invalid @enderror">
                                            <option id="edit-type-percent" value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                                            <option id="edit-type-fixed" value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Cố định (VNĐ)</option>
                                        </select>
                                    </div>
                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Giá trị đơn tối thiểu <span class="text-danger">*</span></div>
                                    <input class="input @error('min_order_value') is-invalid @enderror" type="number" id="edit-min_order_value" name="min_order_value" placeholder="Giá trị đơn tối thiểu" value="{{ old('min_order_value') }}">
                                    @error('min_order_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Số lượng <span class="text-danger">*</span></div>
                                    <input class="input @error('usage_limit') is-invalid @enderror" type="number" id="edit-usage_limit" name="usage_limit" placeholder="Số lượng" value="{{ old('usage_limit') }}">
                                    @error('usage_limit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div style="display:flex; justify-content:space-around">
                                    <div class="field-wrapper">
                                        <div class="field-placeholder">Ngày Bắt đầu <span class="text-danger">*</span></div>
                                        <input class="input @error('starts_at') is-invalid @enderror" type="date" id="edit-starts_at" name="starts_at" placeholder="Ngày bắt đầu" value="{{ old('starts_at') }}">
                                        @error('starts_at')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="field-wrapper">
                                        <div class="field-placeholder">Ngày kết thúc</div>
                                        <input class="input @error('expires_at') is-invalid @enderror" type="date" id="edit-expires_at" name="expires_at" placeholder="Ngày kết thúc" value="{{ old('expires_at') }}">
                                        @error('expires_at')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
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
    const addModal = document.getElementById('addCouponModal');
    const closeAddBtn = document.getElementById('close-add-Coupon-modal');
    const addNameInput = document.getElementById('add-name'); 
    const addForm = document.getElementById('addCouponForm');

    const editModal = document.getElementById('editCouponModal');
    const closeEditBtn = document.getElementById('close-edit-Coupon-modal');
    const editCodeInput = document.getElementById('edit-code');
    const editDiscountInput = document.getElementById('edit-discount');
    const editMinOrderValueInput = document.getElementById('edit-min_order_value');
    const editTypeInput = document.getElementById('edit-type');
    const editUsageLimitInput = document.getElementById('edit-usage_limit');
    const editStartAtInput = document.getElementById('edit-starts_at');
    const editExpiresAtInput = document.getElementById('edit-expires_at');
    const editForm = document.getElementById('editCouponForm');

    function showAddCouponModalWithOldData() {
        if (document.getElementById('add-name')) { 
            document.getElementById('add-name').value = "{{ old('code') }}";
        }
        if (document.getElementById('add-price')) { 
            document.getElementById('add-price').value = "{{ old('discount') }}";
        }
        if (document.querySelector('#addCouponForm select[name="type"]')) {
            document.querySelector('#addCouponForm select[name="type"]').value = "{{ old('type', 'percent') }}"; // Default to percent
        }
        if (document.getElementById('add-min_order_value')) {
            document.getElementById('add-min_order_value').value = "{{ old('min_order_value') }}";
        }
        if (document.getElementById('add-usage_limit')) {
            document.getElementById('add-usage_limit').value = "{{ old('usage_limit') }}";
        }
        if (document.getElementById('add-starts_at')) {
            document.getElementById('add-starts_at').value = "{{ old('starts_at') }}";
        }
        if (document.getElementById('add-expires_at')) {
            document.getElementById('add-expires_at').value = "{{ old('expires_at') }}";
        }
        addModal.style.display = 'flex';
    }

    function showEditCouponModalWithOldData(couponId) {
        editForm.action = `coupon/update/${couponId}`;
        document.getElementById('edit-id').value = couponId;

        if (editCodeInput) editCodeInput.value = "{{ old('code') }}";
        if (editDiscountInput) editDiscountInput.value = "{{ old('discount') }}";
        if (editMinOrderValueInput) editMinOrderValueInput.value = "{{ old('min_order_value') }}";
        if (editTypeInput) editTypeInput.value = "{{ old('type', 'percent') }}"; 
        if (editUsageLimitInput) editUsageLimitInput.value = "{{ old('usage_limit') }}";
        if (editStartAtInput) editStartAtInput.value = "{{ old('starts_at') }}";
        if (editExpiresAtInput) editExpiresAtInput.value = "{{ old('expires_at') }}";

        editModal.style.display = 'flex';
    }


    @if (session('showAddCouponModal'))
        showAddCouponModalWithOldData();
    @endif

    @if (session('showEditCouponModal'))
        const couponIdForEditError = "{{ session('showEditCouponModal') }}";
        showEditCouponModalWithOldData(couponIdForEditError);
    @endif


    // Sự kiện cho nút "Thêm Coupon"
    document.getElementById('btn-add-Coupon').addEventListener('click', function() {
        // Reset form và clear lỗi
        addForm.reset();
        addModal.querySelectorAll('.text-danger').forEach(function(element) {
            element.textContent = '';
        });
        addModal.querySelectorAll('.is-invalid').forEach(function(element) {
            element.classList.remove('is-invalid');
        });

        addModal.style.display = 'flex';
    });

    closeAddBtn.onclick = function() {
        addModal.style.display = 'none';
        addModal.querySelectorAll('.text-danger').forEach(function(element) {
            element.textContent = '';
        });
        addModal.querySelectorAll('.is-invalid').forEach(function(element) {
            element.classList.remove('is-invalid');
        });
    };
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
            addModal.querySelectorAll('.text-danger').forEach(function(element) {
                element.textContent = '';
            });
            addModal.querySelectorAll('.is-invalid').forEach(function(element) {
                element.classList.remove('is-invalid');
            });
        }
    });

    document.querySelectorAll('.btn-edit-coupon').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemCode = this.dataset.code;
            const itemDiscount = this.dataset.discount;
            const itemType = this.dataset.type;
            const itemMinOrderValue = this.dataset.min_order_value;
            const itemUsageLimit = this.dataset.usage_limit;
            const itemStartAt = this.dataset.starts_at;
            const itemExpiresAt = this.dataset.expires_at;

            editModal.querySelectorAll('.text-danger').forEach(function(element) {
                element.textContent = '';
            });
            editModal.querySelectorAll('.is-invalid').forEach(function(element) {
                element.classList.remove('is-invalid');
            });

            editForm.action = `coupon/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            if (editCodeInput) editCodeInput.value = itemCode;
            if (editDiscountInput) editDiscountInput.value = Number(itemDiscount);
            if (editTypeInput) {
                editTypeInput.value = itemType;
            }
            if (editMinOrderValueInput) editMinOrderValueInput.value = Number(itemMinOrderValue);
            if (editUsageLimitInput) editUsageLimitInput.value = itemUsageLimit;
            if (editExpiresAtInput) editExpiresAtInput.value = itemExpiresAt;
            if (editStartAtInput) editStartAtInput.value = itemStartAt;

            editModal.style.display = 'flex';
        });
    });

    closeEditBtn.onclick = function() {
        editModal.style.display = 'none';
        editModal.querySelectorAll('.text-danger').forEach(function(element) {
            element.textContent = '';
        });
        editModal.querySelectorAll('.is-invalid').forEach(function(element) {
            element.classList.remove('is-invalid');
        });
    };
    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
            editModal.querySelectorAll('.text-danger').forEach(function(element) {
                element.textContent = '';
            });
            editModal.querySelectorAll('.is-invalid').forEach(function(element) {
                element.classList.remove('is-invalid');
            });
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
                <div class="field-placeholder">Mã Code <span class="text-danger">*</span></div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input @error('code') is-invalid @enderror" type="text" id="add-name" name="code" placeholder="Code" value="{{ old('code') }}">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="field-wrapper">
                <div class="field-placeholder">Giảm giá <span class="text-danger">*</span></div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input @error('discount') is-invalid @enderror" type="number" id="add-price" name="discount" placeholder="Trị giá " value="{{ old('discount') }}">
                  <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Cố định (VNĐ)</option>
                  </select>
                </div>
                @error('discount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                @error('type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Giá trị đơn tối thiểu <span class="text-danger">*</span></div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input @error('min_order_value') is-invalid @enderror" type="number" id="add-min_order_value" name="min_order_value" placeholder="Giá trị đơn tối thiểu" value="{{ old('min_order_value') }}">
                </div>
                @error('min_order_value')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Số lượng <span class="text-danger">*</span></div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input @error('usage_limit') is-invalid @enderror" type="number" id="add-usage_limit" name="usage_limit" placeholder="Số lượng" value="{{ old('usage_limit') }}">
                </div>
                @error('usage_limit')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

           <div style="display:flex; justify-content:space-around">
           <div class="field-wrapper ">
                <div class="field-placeholder">Ngày bắt đầu <span class="text-danger">*</span></div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input class="input @error('starts_at') is-invalid @enderror" type="date" id="add-starts_at" name="starts_at" placeholder="Ngày bắt đầu" value="{{ old('starts_at') }}">
                </div>
                @error('starts_at')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-wrapper">
                <div class="field-placeholder">Ngày kết thúc</div>
                <div style="display: flex; align-items: center; gap: 10px;" class="control" >
                  <input  class="input @error('expires_at') is-invalid @enderror" type="date" id="add-expires_at" name="expires_at" placeholder="Ngày kết thúc" value="{{ old('expires_at') }}">
                </div>
                @error('expires_at')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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