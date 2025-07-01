@include('header')
<style>
    .btn-success {
        width: 200px;
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
        font-size: 14px;
        padding: 5px 12px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        height: 32px;
        min-width: 60px;
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
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
    .is-invalid:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
</style>
<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <button type="button" id="btn-add-point-setting" class="btn-success">Thêm cấu hình điểm</button>
                    <div class="card-body">
                        <form method="GET" action="" style="margin-bottom: 20px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="key" style="font-weight: 500;">Tìm theo key</label>
                                    <input type="text" name="key" id="key" class="form-control" value="{{ request('key') }}" placeholder="Nhập key...">
                                </div>
                                <div class="col-md-1" style="display: flex; align-items: end; gap: 10px;">
                                    <button type="submit" class="btn btn-primary" style="height: 38px;">Lọc</button>
                                    <a href="{{ route('admin.point_settings.index') }}" class="btn btn-secondary" style="height: 38px;">Resets</a>
                                </div>
                            </div>
                        </form>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table v-middle">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th style="width:90px; text-align:center;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pointSettings->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                    @else
                                        @foreach ($pointSettings as $key => $item)
                                        <tr>
                                            <td>{{ ($pointSettings->currentPage()-1) * $pointSettings->perPage() + $key + 1 }}</td>
                                            <td>{{ $item->key }}</td>
                                            <td>{{ number_format($item->value) }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                    <button type="button" class="btn-edit-point-setting btn btn-sm btn-primary"
                                                        data-id="{{ $item->id }}"
                                                        data-key="{{ $item->key }}"
                                                        data-value="{{ number_format($item->value) }}"
                                                        data-description="{{ $item->description }}"
                                                    >Sửa</button>
                                                    <form action="{{ route('admin.point_settings.delete', $item->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa cấu hình này?')">Xóa</button>
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
                            Trang {{ $pointSettings->currentPage() }}/{{ $pointSettings->lastPage() }},
                            Hiển thị {{ $pointSettings->firstItem() }}-{{ $pointSettings->lastItem() }}/{{ $pointSettings->total() }} bản ghi
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pointSettings->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa cấu hình điểm -->
<div id="pointSettingModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-point-setting-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h3 id="modalTitle">Thêm cấu hình điểm</h3>
        <form id="pointSettingForm" method="post" action="{{ route('admin.point_settings.store') }}">
            @csrf
            <input type="hidden" name="id" id="point-setting-id">
            <div class="mb-3">
                <label for="modal-key" class="form-label">Key <span style="color:red">*</span></label>
                <input type="text" class="form-control" id="modal-key" name="key" required>
                <div class="text-danger" id="error-key"></div>
            </div>
            <div class="mb-3">
                <label for="modal-value" class="form-label">Value <span style="color:red">*</span></label>
                <input type="text" class="form-control" id="modal-value" name="value" required>
                <div class="text-danger" id="error-value"></div>
            </div>
            <div class="mb-3">
                <label for="modal-description" class="form-label">Description</label>
                <input type="text" class="form-control" id="modal-description" name="description">
            </div>
            <button type="submit" class="btn btn-success" id="modalSubmitBtn">Thêm mới</button>
        </form>
    </div>
</div>

<script>
// Modal helpers
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

const pointSettingModal = document.getElementById('pointSettingModal');
const closePointSettingBtn = document.getElementById('close-point-setting-modal');
const pointSettingForm = document.getElementById('pointSettingForm');
const modalTitle = document.getElementById('modalTitle');
const modalSubmitBtn = document.getElementById('modalSubmitBtn');
const idInput = document.getElementById('point-setting-id');
const keyInput = document.getElementById('modal-key');
const valueInput = document.getElementById('modal-value');
const descInput = document.getElementById('modal-description');
const errorKey = document.getElementById('error-key');
const errorValue = document.getElementById('error-value');

// Open add modal
    document.getElementById('btn-add-point-setting').addEventListener('click', function() {
        modalTitle.textContent = 'Thêm cấu hình điểm';
        modalSubmitBtn.textContent = 'Thêm mới';
        pointSettingForm.action = "{{ route('admin.point_settings.store') }}";
        idInput.value = '';
        keyInput.value = '';
        valueInput.value = '';
        descInput.value = '';
        errorKey.textContent = '';
        errorValue.textContent = '';
        openModal(pointSettingModal);
    });

// Open edit modal
    document.querySelectorAll('.btn-edit-point-setting').forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function() {
            modalTitle.textContent = 'Sửa cấu hình điểm';
            modalSubmitBtn.textContent = 'Cập nhật';
            const id = this.dataset.id;
            const key = this.dataset.key;
            const value = this.dataset.value;
            const desc = this.dataset.description;
            pointSettingForm.action = `/admin/point-settings/update/${id}`;
            idInput.value = id;
            keyInput.value = key;
            valueInput.value = value;
            descInput.value = desc;
            errorKey.textContent = '';
            errorValue.textContent = '';
            openModal(pointSettingModal);
        });
    });

// Close modal
    closePointSettingBtn.onclick = function() {
        closeModal(pointSettingModal);
    };
    window.addEventListener('click', function(event) {
        if (event.target == pointSettingModal) {
            closeModal(pointSettingModal);
        }
    });

// Simple client-side validation
    pointSettingForm.addEventListener('submit', function(e) {
        let hasErrors = false;
        if (!keyInput.value.trim()) {
            errorKey.textContent = 'Key không được để trống';
            keyInput.classList.add('is-invalid');
            hasErrors = true;
        } else {
            errorKey.textContent = '';
            keyInput.classList.remove('is-invalid');
        }
        if (!valueInput.value.trim()) {
            errorValue.textContent = 'Value không được để trống';
            valueInput.classList.add('is-invalid');
            hasErrors = true;
        } else {
            errorValue.textContent = '';
            valueInput.classList.remove('is-invalid');
        }
        if (hasErrors) {
            e.preventDefault();
        }
    });
</script>
@include('footer') 