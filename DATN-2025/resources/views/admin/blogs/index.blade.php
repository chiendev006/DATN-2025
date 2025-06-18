@include('header')

<style>
       th{
            text-align: center;
        }
        td{
            text-align: center;
        }
    .btn-success,.btn-success1 {
        width: 130px;
        border: none;
        color: white;
        padding: 5px 10px;
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
        background-color: blue; /* Màu xanh đậm hơn khi hover */
    }
    .btn-success1:hover{
        background-color: red;
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
                    <a href="{{ route('blogs.create') }}" class="btn-success">Thêm bài viết</a>
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
                                        <th>Tên bài</th>
                                        <th style="width:30%">Nội dung</th>
                                        <th>Ảnh bìa</th>
                                        <th>Danh mục bài viết</th>
                                        <th>Ngày tạo</th>
                                        <th style="width:90px; text-align:center;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                               @if($blogs->isEmpty())
                               <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                               </tr>
                               @else
                               @foreach ($blogs as $item)
                                    <tr>
                                    <td>{{ $item->title }}</td>
                                    <td style="max-width:400px; word-break:break-word;">{!!  $item->content !!}</td>
                                    <td><img src="{{ asset('storage/'.$item->image) }}" alt="Ảnh bìa" style="width: 100px; height: 100px;"></td>
                                    <td>{{$item->danhmucBlog->name}}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td style="width:90px; text-align:center;">
                                            <a style="color: white; width: 60px; margin-bottom:4px;" href="{{ route('blogs.edit', $item->id) }}" class="btn-success">Sửa</a>
                                            <a style="color: white; width: 60px;" href="{{ route('blogs.destroy', $item->id) }}" onclick="return confirm('  Xác nhận xóa bài viết?')" class="btn-success1">Xóa</a>
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
        </div>

        @include('footer')


    </div>
</div>

<script>
$(function() {
    $('#btn-attendance').on('click', function() {
        $('#attendanceModal').show();
    });
    $('#close-attendance-modal').on('click', function() {
        $('#attendanceModal').hide();
    });
    // Đóng modal khi click ra ngoài
    $(window).on('click', function(e) {
        if ($(e.target).is('#attendanceModal')) {
            $('#attendanceModal').hide();
        }
    });
});
</script>

{{-- Lưu ý: Modal Thêm nhân viên được đặt sau script để JavaScript có thể truy cập nó ngay sau khi DOMContentLoaded --}}


<div class="text-muted mb-2" style="font-size:13px;">
    @php
        $from = $blogs->firstItem();
        $to = $blogs->lastItem();
        $total = $blogs->total();
        $currentPage = $blogs->currentPage();
        $lastPage = $blogs->lastPage();
    @endphp
    Trang {{ $currentPage }}/{{ $lastPage }},
    Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
</div>
<div style="margin-top: 10px;">
    {{ $blogs->links() }}
</div>


