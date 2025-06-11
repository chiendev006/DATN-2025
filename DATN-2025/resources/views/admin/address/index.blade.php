@include('header')
<style>
       th{
            text-align: center;
        }
        td{
            text-align: center;
        }
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
                                    <button type="button" id="btn-add-diachi" class="btn-success">Thêm Khu vực</button>
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
                                                        <th>Tên Khu vực</th>
                                                        <th>Giá ship</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  @if($address->isEmpty())
                                                  <tr>
                                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                  </tr>
                                                  @else
                                                  @foreach ($address as $item)
                                                    <tr>
                                                        <td>
                                                         {{ $item['name'] }}
                                                        </td>
                                                        <td>
                                                         {{ $item['shipping_fee']  }} VNĐ
                                                        </td>
                                                        <td>
                                                            <div class="actions">
                                                            <button type="button" class="btn-edit-diachi" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-shipping_fee="{{ $item->shipping_fee }}" style="background:none;border:none;cursor:pointer;">
                                                                    <i class="icon-edit1 text-info"></i>
                                                                </button>

                                                                <a href="javascript:void(0)" onclick="deleteViaPost('{{ route('address.delete', ['id' => $item->id]) }}', 'Bạn có chắc chắn muốn xóa?')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                                                    <i class="icon-x-circle text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                  @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-muted mb-2" style="font-size:13px;">
                                            Trang {{ $address->currentPage() }}/{{ $address->lastPage() }},
                                            Hiển thị {{ $address->firstItem() }}-{{ $address->lastItem() }}/{{ $address->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                                {{ $address->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
</div>

<!-- Modal popup thêm Khu vực -->
<div id="adddiachiModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-add-diachi-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3>Thêm Khu vực</h3>
        <form id="adddiachiForm" method="post" action="{{ route('address.store') }}">
            @csrf
            <div class="field-wrapper ">
                <div class="field-placeholder">Tên Khu vực</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="add-name" name="name" placeholder="Name">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Giá ship</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="number" id="add-shipping_fee" name="shipping_fee" placeholder="Shipping Fee">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
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

<!-- Modal popup edit Khu vực -->
<div id="editdiachiModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-edit-diachi-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3>Chỉnh sửa Khu vực</h3>
        <form id="editdiachiForm" method="post">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="field-wrapper ">
                <div class="field-placeholder">Tên Khu vực</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="edit-name" name="name" placeholder="Name">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field-wrapper">
                <div class="field-placeholder">Giá ship</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="number" id="edit-shipping_fee" name="shipping_fee" placeholder="Shipping Fee">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
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

@include('footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Thêm
    const addModal = document.getElementById('adddiachiModal');
    const closeAddBtn = document.getElementById('close-add-diachi-modal');
    const addNameInput = document.getElementById('add-name');
    const addShippingFeeInput = document.getElementById('add-shipping_fee');
    const addForm = document.getElementById('adddiachiForm');

    // Modal Sửa
    const editModal = document.getElementById('editdiachiModal');
    const closeEditBtn = document.getElementById('close-edit-diachi-modal');
    const editNameInput = document.getElementById('edit-name');
    const editShippingFeeInput = document.getElementById('edit-shipping_fee');
    const editForm = document.getElementById('editdiachiForm');

    // Sự kiện cho nút "Thêm Khu vực"
    document.getElementById('btn-add-diachi').addEventListener('click', function() {
        // Reset form về trạng thái rỗng
        if (addNameInput) addNameInput.value = '';
        if (addShippingFeeInput) addShippingFeeInput.value = '';
        // Hiển thị modal
        addModal.style.display = 'flex';
    });

    // Đóng popup Thêm
    if (closeAddBtn) {
        closeAddBtn.onclick = function() {
            addModal.style.display = 'none';
        };
    }

    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    });

    // Sự kiện cho các nút sửa
    document.querySelectorAll('.btn-edit-diachi').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemShippingFee = this.dataset.shipping_fee;

            // Cập nhật action của form trong modal
            editForm.action = `address/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editNameInput) editNameInput.value = itemName;
            if (editShippingFeeInput) editShippingFeeInput.value = itemShippingFee;

            // Hiển thị modal
            editModal.style.display = 'flex';
        });
    });

    // Đóng popup Sửa
    if (closeEditBtn) {
        closeEditBtn.onclick = function() {
            editModal.style.display = 'none';
        };
    }
});
</script>
