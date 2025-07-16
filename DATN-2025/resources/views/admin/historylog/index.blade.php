@include('header')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif


    <div class="content-wrapper-scroll">

                    <div class="content-wrapper">
                    <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="card">
                                    <div class="card-body">
                                        <!-- Form tìm kiếm và lọc -->
                                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="display: flex;">
                                       <form class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10" method="GET" action="" style="margin-bottom: 20px;">
                                            <div class="row">
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                                <div class="field-wrapper">
                                                <div class="field-placeholder">Từ ngày</div>
                                                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                                </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                                <div class="field-wrapper">
                                                <div class="field-placeholder">Đến ngày</div>
                                                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                                </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                                <div class="field-wrapper">
                                                <div class="field-placeholder">Bản/trang</div>
                                                    <select name="role" id="role" class="form-control">
                                                        <option value="">Tất cả</option>
                                                        <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                                                        <option value="0" {{ request('role') == '0' ? 'selected' : '' }}>Khách</option>
                                                        <option value="21" {{ request('role') == '21' ? 'selected' : '' }}>Nhân viên</option>
                                                        <!-- Thêm các role khác nếu có -->
                                                    </select>
                                                </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                                <div class="field-wrapper">
                                                <div class="field-placeholder">Bản/trang</div>
                                                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bản</option>
                                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản</option>
                                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 bản</option>
                                                    </select>
                                                </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                                                <div class="field-wrapper">

                                                    <button type="submit" class="btn btn-primary" style="margin-top: 0px;">Lọc</button>
                                                    <a href="{{ route('historylog.index') }}" class="btn btn-secondary" style="margin-top: 0px;">Reset</a>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                        <div class="field-wrapper">
                                            <form method="POST" action="{{ route('historylog.deleteByTime') }}" style="display:inline;">
                                                @csrf
                                                <div class="field-placeholder">Xóa khoảng</div>
                                                <select name="range" id="delete-range" class="form-control" style="width:auto;display:inline-block;margin-right:8px;">
                                                    <option value="1hour">1 giờ qua</option>
                                                    <option value="1day">1 ngày qua</option>
                                                    <option value="1week">1 tuần qua</option>
                                                    <option value="1month">1 tháng qua</option>
                                                    <option value="all">Toàn bộ</option>
                                                </select>
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </div>
                                        </div>
                                       </div>
                                        <div class="table-responsive">
                                            <table id="copy-print-csv" class="table v-middle">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Tên</th>
                                                        <th>Hành động</th>
                                                        <th>Thời gian</th>
                                                        <th style="width:90px; text-align:center;">Xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  @if($historylog->isEmpty())
                                                  <tr>
                                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                  </tr>
                                                  @else
                                                  @foreach ($historylog as $key => $item)
                                                    <tr>
                                                        <td>{{ ($historylog->currentPage()-1) * $historylog->perPage() + $key + 1 }}</td>
                                                        <td>
                                                        {{ $item->userlog->name ?? 'Không xác định' }}
                                                        </td>
                                                        <td>
                                                          {!!  $item->content  !!}
                                                        </td>
                                                        <td>
                                                            {{ ($item->created_at)->format('H:i d/m/Y') }}
                                                        </td>
                                                        <td>
                                                            <div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                                <form action="{{ route('historylog.delete', ['id' => $item->id]) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa Coupon này?')">Xóa</button>
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
                                            Trang {{ $historylog->currentPage() }}/{{ $historylog->lastPage() }},
                                            Hiển thị {{ $historylog->firstItem() }}-{{ $historylog->lastItem() }}/{{ $historylog->total() }} bản ghi
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $historylog->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @include('footer')
                    <!-- Modal popup edit danh mục, chỉ 1 lần duy nhất ngoài vòng lặp -->
                    <div id="edithistorylogModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
                        <div class="custom-modal-content" style="background:#fff;border-radius:10px;padding:32px 24px 24px 24px;min-width:320px;max-width:90vw;box-shadow:0 4px 24px 0 rgba(0,0,0,0.08),0 1.5px 4px 0 rgba(0,0,0,0.03);position:relative;">
                            <span class="custom-modal-close" id="close-edit-historylog-modal" style="position:absolute;top:12px;right:18px;font-size:2rem;color:#888;cursor:pointer;font-weight:bold;z-index:2;">&times;</span>
                            <h3>Chỉnh sửa danh mục</h3>
                            <form id="edithistorylogForm" method="post">
                                @csrf
                                <input type="hidden" name="id" id="edit-id">
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Tên danh mục <span class="text-danger"></span></div>
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control icons-left">
                                                <input class="input @error('name') is-invalid @enderror" type="text" id="edit-name" name="name" placeholder="Nhập tên danh mục" value="{{ old('name') }}">
                                                <span class="icon left"><i class="mdi mdi-account"></i></span>
                                            </div>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="field-wrapper">
                                    <div class="field-placeholder">Loại danh mục <span class="text-danger"></span></div>
                                    <br>
                                    <div class="control" style="margin-top: 8px;">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('has_topping') is-invalid @enderror" type="radio" name="has_topping" id="editInlineRadio1" value="1" {{ old('has_topping') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="editInlineRadio1">Có sử dụng topping</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('has_topping') is-invalid @enderror" type="radio" name="has_topping" id="editInlineRadio2" value="0" {{ old('has_topping') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="editInlineRadio2">Không sử dụng topping</label>
                                        </div>
                                        @error('has_topping')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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



