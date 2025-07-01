@include('header')
<style>
    th, td { text-align: center; }
    .btn-info { background: #17a2b8; color: #fff; border: none; border-radius: 5px; padding: 5px 12px; min-width: 60px; }
    .btn-info:hover { background: #138496; }
</style>
<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="" style="margin-bottom: 20px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="user_id" style="font-weight: 500;">Lọc theo user</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="type" style="font-weight: 500;">Lọc theo loại</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="earn" {{ request('type')=='earn' ? 'selected' : '' }}>Tích điểm</option>
                                        <option value="spend" {{ request('type')=='spend' ? 'selected' : '' }}>Sử dụng điểm</option>
                                        <option value="expire" {{ request('type')=='expire' ? 'selected' : '' }}>Hết hạn</option>
                                        <option value="adjust" {{ request('type')=='adjust' ? 'selected' : '' }}>Điều chỉnh</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="per_page" style="font-weight: 500;">Bản/trang:</label>
                                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bản</option>
                                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 bản</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 bản</option>
                                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 bản</option>
                                    </select>
                                </div>
                                <div class="col-md-2" style="display: flex; align-items: end; gap: 10px;">
                                    <button type="submit" class="btn btn-primary" style="height: 38px;">Lọc</button>
                                    <a href="{{ route('admin.point_transactions.index') }}" class="btn btn-secondary" style="height: 38px;">Làm mới</a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table v-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Điểm</th>
                                        <th>Loại</th>
                                        <th>Mô tả</th>
                                        <th>Đơn hàng</th>
                                        <th>Thời gian</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->points }}</td>
                                        <td>{{ $item->type_text }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->order_id ? ('#'.$item->order_id) : '-' }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm btn-detail-transaction"
                                                data-id="{{ $item->id }}"
                                                data-user="{{ $item->user->name ?? '-' }}"
                                                data-points="{{ $item->points }}"
                                                data-type="{{ $item->type_text }}"
                                                data-description="{{ $item->description }}"
                                                data-order="{{ $item->order_id ? ('#'.$item->order_id) : '-' }}"
                                                data-time="{{ $item->created_at->format('d/m/Y') }}"
                                            >Chi tiết</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-muted mb-2" style="font-size:13px;">
                            Trang {{ $transactions->currentPage() }}/{{ $transactions->lastPage() }},
                            Hiển thị {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }}/{{ $transactions->total() }} bản ghi
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@include('footer')
<!-- Modal chi tiết giao dịch điểm -->
<div id="detailTransactionModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:40px 32px 32px 32px;min-width:500px;max-width:700px;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
        <span class="custom-modal-close" id="close-detail-transaction-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
        <h4 style="font-size: 1.5rem;">Chi tiết giao dịch điểm</h4>
        <table class="table table-bordered mt-3" style="font-size: 1.15rem;">
            <tr><th>ID</th><td id="detail-id"></td></tr>
            <tr><th>User</th><td id="detail-user"></td></tr>
            <tr><th>Điểm</th><td id="detail-points"></td></tr>
            <tr><th>Loại</th><td id="detail-type"></td></tr>
            <tr><th>Mô tả</th><td id="detail-description"></td></tr>
            <tr><th>Đơn hàng</th><td id="detail-order"></td></tr>
            <tr><th>Thời gian</th><td id="detail-time"></td></tr>
        </table>
        <button class="btn btn-secondary mt-2" id="close-detail-transaction-btn">Đóng</button>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var loading = document.getElementById('loading-wrapper');
    if (loading) loading.style.display = 'none';
    // Modal chi tiết giao dịch điểm
    const detailModal = document.getElementById('detailTransactionModal');
    const closeDetailBtn = document.getElementById('close-detail-transaction-modal');
    const closeDetailBtn2 = document.getElementById('close-detail-transaction-btn');
    document.querySelectorAll('.btn-detail-transaction').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('detail-id').textContent = this.dataset.id;
            document.getElementById('detail-user').textContent = this.dataset.user;
            document.getElementById('detail-points').textContent = this.dataset.points;
            document.getElementById('detail-type').textContent = this.dataset.type;
            document.getElementById('detail-description').textContent = this.dataset.description;
            document.getElementById('detail-order').textContent = this.dataset.order;
            document.getElementById('detail-time').textContent = this.dataset.time;
            detailModal.style.display = 'flex';
            setTimeout(() => { detailModal.classList.add('show'); }, 10);
        });
    });
    function closeModal(modal) {
        modal.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
    closeDetailBtn.onclick = function() { closeModal(detailModal); };
    closeDetailBtn2.onclick = function() { closeModal(detailModal); };
    window.addEventListener('click', function(event) {
        if (event.target == detailModal) closeModal(detailModal);
    });
});
</script>