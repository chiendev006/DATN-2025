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
        background-color: #28a745; /* Màu xanh lá cây mặc định */
    }
    .btn-success:hover {
        background-color: #218838; /* Màu xanh đậm hơn khi hover */
    }

    /* Styles cho modal - đảm bảo modal hiển thị đúng cách */
    .custom-modal {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        display: flex;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.3);
        align-items: center;
        justify-content: center;
    }
    .custom-modal.show {
        opacity: 1;
        pointer-events: auto;
    }
    .custom-modal-content {
        transform: translateY(-40px) scale(0.95);
        opacity: 0;
        transition: all 0.3s cubic-bezier(.4,0,.2,1);
        background: #fff;
        border-radius: 10px;
        padding: 32px 24px 24px 24px;
        min-width: 320px;
        width: 700px;
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);
        position: relative;
    }
    .custom-modal.show .custom-modal-content {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    .custom-modal-close {
        position: absolute;
        top: 12px;
        right: 18px;
        font-size: 2rem;
        color: #888;
        cursor: pointer;
        font-weight: bold;
        z-index: 2;
    }
    .field-wrapper {
        margin-bottom: 15px; /* Thêm khoảng cách giữa các trường */
    }
    .field-placeholder {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .input {
        width: 100%; /* Đảm bảo input chiếm đủ chiều rộng */
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Tính toán padding và border vào width */
    }
    .select-single {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .field.grouped {
        margin-top: 20px;
        text-align: right; /* Căn nút submit sang phải */
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
                    <button type="button" id="btn-add-staff" class="btn-success">Thêm nhân viên</button>
                    <div class="card-body">
                        <div style="margin-bottom: 10px;">
                            <form method="GET" style="display:inline-block;">
                                <label for="per_page">Hiển thị</label>
                                <select name="per_page" id="per_page" class="form-control" style="width: 80px; display:inline-block;" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select> bản ghi/trang
                                @foreach(request()->except(['per_page','page']) as $key => $val)
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                @endforeach
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table id="copy-print-csv" class="table v-middle">
                                <thead>
                                    <tr>
                                        <th>Tên nhân viên</th>
                                        <th>Ảnh nhân viên</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>Lương/ngày</th>
                                        <th>Chức vụ</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($staffs->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @else
                                    @foreach ($staffs as $staff)
                                    <tr>
                                        <td>{{ $staff->name }}</td>
                                        <td>
                                            <img src="{{ url("/storage/uploads/$staff->image") }}" width="100px" alt="Chưa thêm">
                                        </td>
                                        <td>
                                            {{ $staff->phone ?? 'Chưa thêm' }}
                                        </td>
                                        <td>
                                            {{ $staff->email }}
                                        </td>
                                        <td>
                                            {{ number_format($staff->salary_per_day, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($staff->role == 21)
                                                Thu ngân
                                            @elseif ($staff->role == 22)
                                                Pha chế
                                            @endif
                                        </td>

                                        <td>
                                            <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                <button type="button" class="btn-edit-staff"
                                                    data-id="{{ $staff->id }}"
                                                    data-name="{{ $staff->name }}"
                                                    data-phone="{{ $staff->phone ?? '' }}"
                                                    data-image="{{ $staff->image ?? '' }}"
                                                    data-email="{{ $staff->email }}"
                                                    data-salary_per_day="{{ number_format($staff->salary_per_day, 0, ',', '.') }}"
                                                    data-role="{{ $staff->role }}" {{-- Đã thêm data-role ở đây --}}
                                                    style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;" >
                                                    Sửa
                                                </button>

                                                <form action="{{ route('admin.staff.delete', ['id' => $staff->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-muted mb-2" style="font-size:13px;">
    @php
        $from = $staffs->firstItem();
        $to = $staffs->lastItem();
        $total = $staffs->total();
        $currentPage = $staffs->currentPage();
        $lastPage = $staffs->lastPage();
    @endphp
    Trang {{ $currentPage }}/{{ $lastPage }},
    Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
</div>
<div style="margin-top: 10px;">
    {{ $staffs->appends(request()->except('page'))->links() }}
</div>
        </div>

        @include('footer')

        <div id="editStaffModal" class="custom-modal" style="display:none;">
            <div class="custom-modal-content">
                <span class="custom-modal-close" id="close-edit-staff-modal">&times;</span>
                <h3>Chỉnh sửa nhân viên</h3>
                <form id="editStaffForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-wrapper">
                                <div class="field-placeholder">Tên nhân viên</div>
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
                                <div class="field-placeholder">Ảnh nhân viên</div>
                                <div class="field-body">
                                    <div class="field" style="display: flex; align-items: center; gap: 12px;">
                                        <div class="control icons-left">
                                            <input class="input" type="file" id="edit-image" name="image" placeholder="Ảnh nhân viên" required>
                                            <span class="icon left"><i class="mdi mdi-image"></i></span>
                                        </div>
                                        <img id="edit-image-preview" src="{{ url("/storage/uploads/$staff->image") }}" alt="Ảnh hiện tại" style="max-width: 60px; max-height: 60px; display: none; border-radius: 6px; border: 1px solid #ccc;" />
                                    </div>
                                </div>
                            </div>

                            <div class="field-wrapper">
                                <div class="field-placeholder">Số điện thoại</div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control icons-left">
                                            <input class="input" type="text" id="edit-phone" name="phone" placeholder="Số điện thoại" required>
                                            <span class="icon left"><i class="mdi mdi-phone"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="field-wrapper">
                                <div class="field-placeholder">Email</div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control icons-left">
                                            <input class="input" type="email" id="edit-email" name="email" placeholder="Email">
                                            <span class="icon left"><i class="mdi mdi-email"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-wrapper">
                                <div class="field-placeholder">Chức vụ</div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control icons-left">
                                            <select name="role" class="input" id="edit-role-select">
                                                <option value="21">Thu ngân</option>
                                                <option value="22">Pha chế</option>
                                            </select>
                                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-wrapper">
                                <div class="field-placeholder">Lương/ngày</div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control icons-left">
                                            <input class="input" type="number" id="edit-salary_per_day" name="salary_per_day" placeholder="Lương/ngày">
                                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <div class="field-placeholder">Mật khẩu mới (bỏ trống nếu không đổi)</div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control icons-left">
                                    <input class="input" type="password" id="edit-password" name="password" placeholder="Mật khẩu mới">
                                    <span class="icon left"><i class="mdi mdi-lock"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="field-wrapper">
                        <div class="field-placeholder">Xác nhận mật khẩu mới</div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control icons-left">
                                    <input class="input" type="password" id="edit-password_confirmation" name="password_confirmation" placeholder="Xác nhận mật khẩu mới">
                                    <span class="icon left"><i class="mdi mdi-lock"></i></span>
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
    </div>
</div>

<div id="alert-popup" style="display:none; position: fixed; top: 40px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 320px;">
    <div id="alert-popup-content" style="padding: 16px 24px; border-radius: 8px; font-size: 16px; color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15);"></div>
</div>

{{-- Tạo biến JS từ PHP để truyền thông báo --}}
@if ($errors->any())
    <script>
        var popupErrorMsg = `{!! '<ul style="margin:0;padding-left:20px;">' . collect($errors->all())->map(function($e){return '<li>'.$e.'</li>'; })->implode('') . '</ul>' !!}`;
    </script>
@else
    <script>var popupErrorMsg = null;</script>
@endif
@if (session('success'))
    <script>var popupSuccessMsg = "{{ session('success') }}";</script>
@else
    <script>var popupSuccessMsg = null;</script>
@endif

<script>
function showAlertPopup(message, type = 'success') {
    const popup = document.getElementById('alert-popup');
    const content = document.getElementById('alert-popup-content');
    popup.style.display = 'block';
    content.innerHTML = message;
    if (type === 'success') {
        content.style.background = '#28a745';
    } else {
        content.style.background = '#dc3545';
    }
    setTimeout(() => {
        popup.style.display = 'none';
    }, 3500);
}

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

document.addEventListener('DOMContentLoaded', function() {
    if (typeof popupErrorMsg !== 'undefined' && popupErrorMsg) {
        showAlertPopup(popupErrorMsg, 'error');
    }
    if (typeof popupSuccessMsg !== 'undefined' && popupSuccessMsg) {
        showAlertPopup(popupSuccessMsg, 'success');
    }
    // Modal Thêm
    const addModal = document.getElementById('addStaffModal');
    const closeAddBtn = document.getElementById('close-add-staff-modal');
    const addNameInput = document.getElementById('add-name');
    const addEmailInput = document.getElementById('add-email');
    const addPasswordInput = document.getElementById('add-password');
    const addPasswordConfirmationInput = document.getElementById('add-password_confirmation');
    // const addRoleSelect = document.querySelector('#addStaffForm select[name="role"]'); // Lấy select trong form thêm
    const addForm = document.getElementById('addStaffForm');

    // Modal Sửa
    const editModal = document.getElementById('editStaffModal');
    const closeEditBtn = document.getElementById('close-edit-staff-modal');
    const editNameInput = document.getElementById('edit-name');
    const editEmailInput = document.getElementById('edit-email');
    const editPhoneInput = document.getElementById('edit-phone');
    const editImageInput = document.getElementById('edit-image');
    const editSalary = document.getElementById('edit-salary_per_day');
    const editPasswordInput = document.getElementById('edit-password');
    const editPasswordConfirmationInput = document.getElementById('edit-password_confirmation');
    const editRoleSelect = document.getElementById('edit-role-select'); // Lấy thẻ select role của modal SỬA
    const editForm = document.getElementById('editStaffForm');

    // --- Xử lý Modal Thêm nhân viên ---

    // Sự kiện cho nút "Thêm nhân viên"
    const btnAddStaff = document.getElementById('btn-add-staff');
    if (btnAddStaff) {
        btnAddStaff.addEventListener('click', function() {
            // Reset form về trạng thái rỗng
            if (addNameInput) addNameInput.value = '';
            if (addEmailInput) addEmailInput.value = '';
            if (addPasswordInput) addPasswordInput.value = '';
            if (addPasswordConfirmationInput) addPasswordConfirmationInput.value = '';
            // Reset select box về option đầu tiên hoặc mặc định (nếu có)
            // if (addRoleSelect) addRoleSelect.selectedIndex = 0;

            openModal(addModal);
        });
    }


    // Đóng popup Thêm
    if (closeAddBtn) {
        closeAddBtn.onclick = function() {
            closeModal(addModal);
        };
    }
    window.addEventListener('click', function(event) {
        if (addModal && event.target == addModal) {
            closeModal(addModal);
        }
    });

    // --- Xử lý Modal Sửa nhân viên ---

    // Sự kiện cho các nút sửa
    document.querySelectorAll('.btn-edit-staff').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemEmail = this.dataset.email;
            const itemSalary = this.dataset.salary_per_day;
            const itemPhone = this.dataset.phone;
            const itemImage = this.dataset.image;
            const itemRole = this.dataset.role; // Lấy giá trị role từ data-attribute

            // Cập nhật action của form trong modal
            editForm.action = `staff/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editNameInput) editNameInput.value = itemName;
            if (editEmailInput) editEmailInput.value = itemEmail;
            if (editPhoneInput) editPhoneInput.value = itemPhone;
            if (editImageInput) editImageInput.value = '';
            // Hiển thị ảnh preview nếu có
            const imagePreview = document.getElementById('edit-image-preview');
            if (imagePreview) {
                if (itemImage) {
                    imagePreview.src = '/storage/uploads/' + itemImage;
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }
            }
            if (editSalary) editSalary.value = itemSalary;
            if (editPasswordInput) editPasswordInput.value = ''; // Luôn xóa mật khẩu cũ
            if (editPasswordConfirmationInput) editPasswordConfirmationInput.value = ''; // Luôn xóa mật khẩu cũ

            // Cập nhật giá trị cho select box role
            if (editRoleSelect && itemRole) {
                editRoleSelect.value = itemRole; // Gán giá trị role đã lấy được
            }

            openModal(editModal);
        });
    });

    // Đóng popup Sửa
    if (closeEditBtn) {
        closeEditBtn.onclick = function() {
            closeModal(editModal);
        };
    }
    window.addEventListener('click', function(event) {
        if (editModal && event.target == editModal) {
            closeModal(editModal);
        }
    });
});
</script>

{{-- Lưu ý: Modal Thêm nhân viên được đặt sau script để JavaScript có thể truy cập nó ngay sau khi DOMContentLoaded --}}
<div id="addStaffModal" class="custom-modal" style="display:none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="close-add-staff-modal">&times;</span>
        <h3>Thêm nhân viên</h3>
        <form id="addStaffForm" method="post" action="{{ route('admin.staff.store') }}">
            @csrf
            <div class="field-wrapper ">
                <div class="field-placeholder">Tên nhân viên</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="text" id="add-name" name="name" placeholder="Name" required>
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="field-wrapper ">
                <div class="field-placeholder">Email</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="email" id="add-email" name="email" placeholder="Email" required>
                            <span class="icon left"><i class="mdi mdi-email"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            {{-- Đảm bảo select box này có id hoặc tên để dễ dàng truy cập nếu cần --}}
            <div class="field-wrapper">
                <div class="field-placeholder">Chức vụ</div>
                <div class="field-body">
                    <select name="role" class="input" id="add-role-select"> {{-- Đã thêm id cho select trong form thêm --}}
                        <option value="21">Thu ngân</option>
                        <option value="22">Pha chế</option>
                    </select>
                </div>
            </div>
            <br> <div class="field-wrapper">
                <div class="field-placeholder">Lương/ngày</div>
                <div class="field-body">
                <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="number" id="add-name" name="salary_per_day" placeholder="Lương/ngày" required>
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="field-wrapper ">
                <div class="field-placeholder">Mật khẩu</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="password" id="add-password" name="password" placeholder="Mật khẩu" required>
                            <span class="icon left"><i class="mdi mdi-lock"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="field-wrapper ">
                <div class="field-placeholder">Xác nhận mật khẩu</div>
                <div class="field-body">
                    <div class="field">
                        <div class="control icons-left">
                            <input class="input" type="password" id="add-password_confirmation" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                            <span class="icon left"><i class="mdi mdi-lock"></i></span>
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
