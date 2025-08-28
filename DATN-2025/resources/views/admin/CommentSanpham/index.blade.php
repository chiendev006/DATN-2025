@include('header')

<style>
    th, td {
        text-align: center;
    }

    .btn-danger {
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

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        padding: 0.75rem 1.25rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
</style>

<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <h2 style="margin: 30px;">Danh sách đánh giá sản phẩm</h2>
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table v-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sản phẩm</th>
                                        <th>Người dùng</th>
                                        <th>Nội dung</th>
                                        <th>Ngày gửi</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($comments as $key => $cmt)
                                        <tr>
                                            <td>{{ ($comments->currentPage() - 1) * $comments->perPage() + $key + 1 }}</td>
                                            <td>{{ $cmt->sanpham->name ?? 'Không tìm thấy' }}</td>
                                            <td>{{ $cmt->user_name ?? 'Ẩn danh' }}</td>
                                            <td>{{ $cmt->comment }}</td>
                                            <td>{{ $cmt->created_at }}</td>
                                            <td>
                                                <form action="{{ route('comments.delete', ['id' => $cmt->id]) }}" method="POST" onsubmit="return confirm('Xác nhận xóa đánh giá này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn-danger">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Không có đánh giá nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="text-muted mb-2" style="font-size:13px;">
                            Trang {{ $comments->currentPage() }}/{{ $comments->lastPage() }},
                            Hiển thị {{ $comments->firstItem() }}-{{ $comments->lastItem() }}/{{ $comments->total() }} bản ghi
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $comments->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('footer')
    </div>
</div>
