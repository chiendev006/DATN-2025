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
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 4px 2px;
    }
    .btn-secondary:hover {
        background-color: #545b62;
        border-color: #545b62;
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
</style>

<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Sửa danh mục blog</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('danhmucblog.update', $danhmucBlog->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục blog <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $danhmucBlog->name) }}" 
                                       placeholder="Nhập tên danh mục blog">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn-success">Cập nhật</button>
                                <a href="{{ route('danhmucblog.index') }}" class="btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer') 