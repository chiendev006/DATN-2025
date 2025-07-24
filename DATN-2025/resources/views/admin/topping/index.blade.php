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
    background-color: #007bff;  
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
.custom-modal {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    display: flex;
}
.custom-modal.show {
    opacity: 1;
    pointer-events: auto;
}
.custom-modal-content {
    transform: translateY(-40px) scale(0.95);
    opacity: 0;
    transition: all 0.3s cubic-bezier(.4,0,.2,1);
}
.custom-modal.show .custom-modal-content {
    transform: translateY(0) scale(1);
    opacity: 1;
}
</style>
    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">
                                <button type="button" id="btn-add-topping" class="btn-success">Thêm topping</button>
                                             <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 15px;">
                            <!-- Bên trái: chọn số bản ghi -->


                            <!-- Bên phải: các form tìm kiếm -->
                            <div class="d-flex align-items-center" style="gap: 15px;">
                                <!-- Tìm theo tên -->
                                <form method="GET" action="{{ url()->current() }}" class="field-wrapper" style="display: flex; align-items: center;">
                                                       <div class="field-placeholder">Bản/trang</div>
                                                        <select name="per_page" class="form-control" style="width: 80px; margin-left: 12px;" onchange="this.form.submit()">
                                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bản </option>
                                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản  </option>
                                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 bản</option>
                                                        </select>
                                                        @foreach(request()->except(['per_page','page']) as $key => $val)
                                                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                                        @endforeach
                                                    </form>



                                    <form class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" action="{{ route('topping.search') }}" method="GET">
                                    <div class="field-wrapper" style="display:flex;">
                                    <div class="field-placeholder">Tìm kiếm theo tên</div>

                                        <input type="text" name="name" placeholder="Nhập tên..." value="{{ request('name') }}">

                                        <button style="border-radius:0px 5px 5px 0px;"  class="btn btn-primary" type="submit">
                                            <span class="icon-search"></span>
                                        </button>
                                    </div>
                                    </form>


                                    <form class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" action="{{ route('topping.search') }}" method="GET">
                                    <div class="input-group" style="max-width: 400px;">
                                    <div class="field-wrapper" style="display:flex;">
                                           <div class="field-placeholder">Lọc theo giá</div>
                                        <input type="number" name="min_price" class="form-control" placeholder="Giá từ" value="{{ request('min_price') }}">
                                        <input type="number" name="max_price" class="form-control" placeholder="Đến" value="{{ request('max_price') }}" >

                                        {{-- Giữ lại tên tìm kiếm nếu có --}}
                                        <input type="hidden" name="q" value="{{ request('q') }}">

                                        <button style="border-radius:0px 5px 5px 0px;"  class="btn btn-primary" type="submit">
                                            <span class="icon-search"></span>
                                        </button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Tên topping</th>
                                                        <th>Giá</th>
                                                        <th style="width:90px; text-align:center;">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($topping->isEmpty())
                                                    <tr>
                                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                    @else
                                                    @foreach ($topping as $key => $item)
                                                    <tr>
                                                         <td>{{ ($topping->currentPage()-1) * $topping->perPage() + $key + 1 }}</td>
                                                        <td>
                                                         {{ $item['name'] }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                                                        </td>
                                                        <td>
                                                            <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                            <button type="button" class="btn-edit-danhmuc"
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-role="{{ $item->role }}"
                                                                data-price="{{ $item->price }}"
                                                                style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;">
                                                                Sửa
                                                                </button>

                                                                 <form action="{{ route('topping.delete', ['id' => $item->id]) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa topping này?')">Xóa</button>
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
                                            Trang {{ $topping->currentPage() }}/{{ $topping->lastPage() }},
                                            Hiển thị {{ $topping->firstItem() }}-{{ $topping->lastItem() }}/{{ $topping->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $topping->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @include('footer')
                    <!-- Modal popup edit topping, chỉ 1 lần duy nhất ngoài vòng lặp -->
                    <div id="editToppingModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-topping-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa topping</h3>
                            <form id="editToppingForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper ">
                                    <div class="field-placeholder">Tên topping</div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input" type="text" id="edit-name" name="name" placeholder="Name">
                                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <div class="field-placeholder">Giá </div>
                                  <input class="input" type="number" id="edit-price" name="price" placeholder="Giá">
                                    @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
    const addModal = document.getElementById('addToppingModal');
    const closeAddBtn = document.getElementById('close-add-topping-modal');
    const addNameInput = document.getElementById('add-name');
    const addHasToppingRadio1 = document.getElementById('addInlineRadio1');
    const addHasToppingRadio2 = document.getElementById('addInlineRadio2');
    const addForm = document.getElementById('addToppingForm');

    // Modal Sửa
    const editModal = document.getElementById('editToppingModal');
    const closeEditBtn = document.getElementById('close-edit-topping-modal');
    const editNameInput = document.getElementById('edit-name');
    const editHasToppingRadio1 = document.getElementById('editInlineRadio1');
    const editHasToppingRadio2 = document.getElementById('editInlineRadio2');
    const editForm = document.getElementById('editToppingForm');

    // Sự kiện cho nút "Thêm topping"
    document.getElementById('btn-add-topping').addEventListener('click', function() {
        // Reset form về trạng thái rỗng
        if (addNameInput) addNameInput.value = '';
        if (addHasToppingRadio1) addHasToppingRadio1.checked = false;
        if (addHasToppingRadio2) addHasToppingRadio2.checked = false;
        // Hiển thị modal
        openModal(addModal);
    });

    // Đóng popup Thêm
    closeAddBtn.onclick = function() {
        closeModal(addModal);
    };
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            closeModal(addModal);
        }
    });

    // Sự kiện cho các nút sửa
    document.querySelectorAll('.btn-edit-danhmuc').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemHasTopping = this.dataset.role ;
            const itemPrice = this.dataset.price;

            // Cập nhật action của form trong modal
            editForm.action = `topping/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editNameInput) editNameInput.value = itemName;
            if (document.getElementById('edit-price')) document.getElementById('edit-price').value = itemPrice;
            if (editHasToppingRadio1) editHasToppingRadio1.checked = (itemHasTopping === '1');
            if (editHasToppingRadio2) editHasToppingRadio2.checked = (itemHasTopping === '0');

            // Hiển thị modal
            openModal(editModal);
        });
    });

    // Đóng popup Sửa
    closeEditBtn.onclick = function() {
        closeModal(editModal);
    };
    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            closeModal(editModal);
        }
    });
});

function openModal(modal) {
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}
function closeModal(modal) {
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}
</script>

<div id="addToppingModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-add-topping-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3>Thêm topping</h3>
        <form id="addToppingForm" method="post" action="{{ route('topping.store') }}">
            @csrf
            <div class="field-wrapper ">
                <div class="field-placeholder">Tên topping</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="add-name" name="name" placeholder="Name">
                        </div>
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                    </div>
                </div>
            </div>
            <div class="field-wrapper">
                <div class="field-placeholder">Giá</div>

                <div class="control" style="margin-top: 8px;">
                  <input class="input" type="number" id="add-price" name="price" placeholder="Giá">
                </div>
                @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
            </div>
            <div class="field grouped">
                <div class="control">
                    <button type="submit" class="btn-success">Thêm mới</button>
                </div>
            </div>
        </form>
    </div>
</div>
