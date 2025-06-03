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
        background-color: #28a745; /* Màu xanh lá cây mặc định */
    }
    .btn-success:hover {
        background-color: #218838; /* Màu xanh đậm hơn khi hover */
    }

    /* Styles cho modal - đảm bảo modal hiển thị đúng cách */
    .custom-modal {
        display: none; /* Mặc định ẩn */
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.3);
        display: flex; /* Dùng flex để căn giữa nội dung */
        align-items: center;
        justify-content: center;
    }
    .custom-modal-content {
        background: #fff;
        border-radius: 10px;
        padding: 32px 24px 24px 24px;
        min-width: 320px;
        max-width: 90vw;
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);
        position: relative;
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
                                        <th>Chức vụ</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                            @if ($staff->role == 21)
                                                Thu ngân
                                            @elseif ($staff->role == 22)
                                                Pha chế
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <button type="button" class="btn-edit-staff"
                                                    data-id="{{ $staff->id }}"
                                                    data-name="{{ $staff->name }}"
                                                    data-email="{{ $staff->email }}"
                                                    data-role="{{ $staff->role }}" {{-- Đã thêm data-role ở đây --}}
                                                    style="background:none;border:none;cursor:pointer;">
                                                    <i class="icon-edit1 text-info"></i>
                                                </button>

                                                <a href="{{ route('admin.staff.delete', ['id' => $staff->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                                    <i class="icon-x-circle text-danger"></i>
                                                </a>
                                            </div>
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

        <div id="editStaffModal" class="custom-modal" style="display:none;">
            <div class="custom-modal-content">
                <span class="custom-modal-close" id="close-edit-staff-modal">&times;</span>
                <h3>Chỉnh sửa nhân viên</h3>
                <form id="editStaffForm" method="post">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
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
                    <br>
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
                    <br>
                    <div class="field-wrapper">
                        <div class="field-placeholder">Chức vụ</div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control icons-left">
                                    {{-- Đây là phần select box cần được cập nhật JavaScript --}}
                                    <select class="input" id="edit-role" name="role" placeholder="Role">
                                        <option value="21">Thu ngân</option>
                                        <option value="22">Pha chế</option>
                                    </select>
                                    {{-- Icon này có vẻ không phù hợp với select box, có thể bỏ nếu không cần --}}
                                    <span class="icon left"><i class="mdi mdi-account-card-details"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    const editPasswordInput = document.getElementById('edit-password');
    const editPasswordConfirmationInput = document.getElementById('edit-password_confirmation');
    const editRoleSelect = document.getElementById('edit-role'); // Lấy thẻ select role của modal SỬA
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

            if (addModal) addModal.style.display = 'flex'; // Hiển thị modal
        });
    }


    // Đóng popup Thêm
    if (closeAddBtn) {
        closeAddBtn.onclick = function() {
            if (addModal) addModal.style.display = 'none';
        };
    }
    window.addEventListener('click', function(event) {
        if (addModal && event.target == addModal) {
            addModal.style.display = "none";
        }
    });

    // --- Xử lý Modal Sửa nhân viên ---

    // Sự kiện cho các nút sửa
    document.querySelectorAll('.btn-edit-staff').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemEmail = this.dataset.email;
            const itemRole = this.dataset.role; // Lấy giá trị role từ data-attribute

            // Cập nhật action của form trong modal
            editForm.action = `staff/update/${itemId}`;
            document.getElementById('edit-id').value = itemId;

            // Điền dữ liệu vào các trường input trong modal
            if (editNameInput) editNameInput.value = itemName;
            if (editEmailInput) editEmailInput.value = itemEmail;
            if (editPasswordInput) editPasswordInput.value = ''; // Luôn xóa mật khẩu cũ
            if (editPasswordConfirmationInput) editPasswordConfirmationInput.value = ''; // Luôn xóa mật khẩu cũ

            // Cập nhật giá trị cho select box role
            if (editRoleSelect && itemRole) {
                editRoleSelect.value = itemRole; // Gán giá trị role đã lấy được
            }

            if (editModal) editModal.style.display = 'flex'; // Hiển thị modal
        });
    });

    // Đóng popup Sửa
    if (closeEditBtn) {
        closeEditBtn.onclick = function() {
            if (editModal) editModal.style.display = 'none';
        };
    }
    window.addEventListener('click', function(event) {
        if (editModal && event.target == editModal) {
            editModal.style.display = "none";
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