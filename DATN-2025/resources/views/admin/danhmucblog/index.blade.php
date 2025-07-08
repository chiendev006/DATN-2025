@include('header')
<style>
       th{
            text-align: center;
        }
        td{
            text-align: center;
        }
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
    font-size: 12px;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

/* Validation styles */
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

.input.is-invalid {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='m5.8 4.6 2.4 2.4m0-2.4L5.8 7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* Form control styles */
.form-control {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    padding: 0.5rem 0.75rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: 0;
}

/* Search form styles */
.search-form {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.search-form label {
    font-weight: 500;
    color: #495057;
}

/* Button styles */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #545b62;
    border-color: #545b62;
}

/* Modal styles */
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
    max-height: 90vh;
    overflow-y: auto;
}
.custom-modal.show .custom-modal-content {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.field-wrapper {
    margin-bottom: 1rem;
}

.field-placeholder {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #495057;
}

/* Success message */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 0.75rem 1.25rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

/* Empty state */
.text-center {
    text-align: center;
}

.no-data {
    padding: 2rem;
    color: #6c757d;
    font-style: italic;
}
</style>
    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">
                                <button type="button" id="btn-add-danhmucblog" class="btn-success">Thêm danh mục blog</button>
                                    <div class="card-body">
                                        <!-- Form tìm kiếm -->
                                        <form method="GET" action="" style="margin-bottom: 20px;">
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <label for="per_page" style="margin-bottom: 5px; font-weight: 500;">Bản/trang:</label>
                                                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                                                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 bản</option>
                                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 bản</option>
                                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản</option>
                                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3" style="display: flex; align-items: end; gap: 10px;">
                                                    <button type="submit" class="btn btn-primary" style="height: 38px;">Tìm kiếm</button>
                                                    <a href="{{ route('danhmucblog.index') }}" class="btn btn-secondary" style="height: 38px;">Làm mới</a>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Tên danh mục blog</th>
                                                        <th style="width:90px; text-align:center;">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  @if($danhmucBlog->isEmpty())
                                                  <tr>
                                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                  </tr>
                                                  @else
                                                  @foreach ($danhmucBlog as $key => $item)
                                                    <tr>
                                                        <td>{{ ($danhmucBlog->currentPage()-1) * $danhmucBlog->perPage() + $key + 1 }}</td>
                                                        <td>
                                                         {{ $item['name'] }}
                                                        </td>

                                                        <td>
                                                            <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                            <button type="button" class="btn-edit-danhmucblog" data-id="{{ $item->id }}" data-name="{{ $item->name }}" style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;">
                                                                Sửa
                                                                </button>

                                                              <form action="{{ route('danhmucblog.delete', ['id' => $item->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục blog này?')">Xóa</button>
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
                                            Trang {{ $danhmucBlog->currentPage() }}/{{ $danhmucBlog->lastPage() }},
                                            Hiển thị {{ $danhmucBlog->firstItem() }}-{{ $danhmucBlog->lastItem() }}/{{ $danhmucBlog->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $danhmucBlog->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @include('footer')
                    <!-- Modal popup edit danh mục blog -->
                    <div id="editDanhMucBlogModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-danhmucblog-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa danh mục blog</h3>
                            <form id="editDanhMucBlogForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Tên danh mục blog <span class="text-danger"></span></div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input @error('name') is-invalid @enderror" type="text" id="edit-name" name="name" placeholder="Nhập tên danh mục blog" value="{{ old('name') }}">
                                                <span class="icon left"><i class="mdi mdi-account"></i></span>
                                            </div>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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


