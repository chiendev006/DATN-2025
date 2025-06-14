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
                                <button type="button" id="btn-add-danhmuc" class="btn-success">Thêm danh mục</button>
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
                                                        <th>Tên danh mục</th>
                                                        <th>Loại danh mục</th>
                                                        <th style="width:90px; text-align:center;">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  @if($danhmuc->isEmpty())
                                                  <tr>
                                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                  </tr>
                                                  @else
                                                  @foreach ($danhmuc as $key => $item)
                                                    <tr>
                                                        <td>{{ ($danhmuc->currentPage()-1) * $danhmuc->perPage() + $key + 1 }}</td>
                                                        <td>
                                                         {{ $item['name'] }}
                                                        </td>
                                                        <td>
                                                            @if ($item['role'] == 1)
                                                                Có sử dụng topping
                                                            @else
                                                                Không sử dụng topping
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                            <button type="button" class="btn-edit-danhmuc" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-role="{{ $item->role }}" style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;">
                                                                Sửa
                                                                </button>

                                                              <form action="{{ route('danhmuc.delete', ['id' => $item->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</button>
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
                                            Trang {{ $danhmuc->currentPage() }}/{{ $danhmuc->lastPage() }},
                                            Hiển thị {{ $danhmuc->firstItem() }}-{{ $danhmuc->lastItem() }}/{{ $danhmuc->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $danhmuc->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @include('footer')
                    <!-- Modal popup edit danh mục, chỉ 1 lần duy nhất ngoài vòng lặp -->
                    <div id="editDanhMucModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-danhmuc-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa danh mục</h3>
                            <form id="editDanhMucForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper ">
                                    <div class="field-placeholder">Tên danh mục</div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input" type="text" id="edit-name" name="name" placeholder="Name">
                                                <span class="icon left"><i class="mdi mdi-account"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Có topping?</div>
                                    <br>
                                    <div class="control" style="margin-top: 8px;">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_topping" id="editInlineRadio1" value="1">
                                            <label class="form-check-label" for="editInlineRadio1">Có</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="has_topping" id="editInlineRadio2" value="0">
                                            <label class="form-check-label" for="editInlineRadio2">Không</label>
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
                    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Thêm
    const addModal = document.getElementById('addDanhMucModal');
    const closeAddBtn = document.getElementById('close-add-danhmuc-modal');
    const addNameInput = document.getElementById('add-name');
    const addHasToppingRadio1 = document.getElementById('addInlineRadio1');
    const addHasToppingRadio2 = document.getElementById('addInlineRadio2');
    const addForm = document.getElementById('addDanhMucForm');

    // Modal Sửa
    const editModal = document.getElementById('editDanhMucModal');
    const closeEditBtn = document.getElementById('close-edit-danhmuc-modal');
    const editNameInput = document.getElementById('edit-name');
    const editHasToppingRadio1 = document.getElementById('editInlineRadio1');
    const editHasToppingRadio2 = document.getElementById('editInlineRadio2');
    const editForm = document.getElementById('editDanhMucForm');

    // Sự kiện cho nút "Thêm danh mục"
    document.getElementById('btn-add-danhmuc').addEventListener('click', function() {
        // Reset form về trạng thái rỗng
        if (addNameInput) addNameInput.value = '';
        if (addHasToppingRadio1) addHasToppingRadio1.checked = false;
        if (addHasToppingRadio2) addHasToppingRadio2.checked = false;
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
    document.querySelectorAll('.btn-edit-danhmuc').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemHasTopping = this.dataset.role ;

            // Cập nhật action của form trong modal
            editForm.action = `danhmuc/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editNameInput) editNameInput.value = itemName;
            if (editHasToppingRadio1) editHasToppingRadio1.checked = (itemHasTopping === '1');
            if (editHasToppingRadio2) editHasToppingRadio2.checked = (itemHasTopping === '0');

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

<div id="addDanhMucModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-add-danhmuc-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3>Thêm danh mục</h3>
        <form id="addDanhMucForm" method="post" action="{{ route('danhmuc.store') }}">
            @csrf
            <div class="field-wrapper ">
                <div class="field-placeholder">Tên danh mục</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="add-name" name="name" placeholder="Name">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="field-wrapper">
                <div class="field-placeholder">Có topping?</div>
                <br>
                <div class="control" style="margin-top: 8px;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="has_topping" id="addInlineRadio1" value="1">
                        <label class="form-check-label" for="addInlineRadio1">Có</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="has_topping" id="addInlineRadio2" value="0">
                        <label class="form-check-label" for="addInlineRadio2">Không</label>
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
