@include('header')

<style>
    th{ text-align: center; }
    td{ text-align: center; }
    .btn-danger{
        background-color: red; color: white; border: none; border-radius: 5px;
        cursor: pointer; font-size: 12px; padding: 5px 10px; text-align: center;
        text-decoration: none; display: inline-block;
    }
</style>

<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
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
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Role</th>
                                        <th>Ảnh</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($users->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @else
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? 'Chưa thêm' }}</td>
                                        <td>
                                            @if($user->role == 1)
                                                Admin
                                            @elseif($user->role == 0)
                                                User
                                            @elseif($user->role == 21)
                                                Thu ngân
                                            @elseif($user->role == 22)
                                                Pha chế
                                            @else
                                                {{ $user->role }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($user->image))
                                                <img src="{{ url('/storage/uploads/'.$user->image) }}" width="80px" alt="Ảnh">
                                            @else
                                                Chưa thêm
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-danger" type="submit" onclick="return confirm('Xóa tài khoản này?')">Xóa</button>
                                            </form>
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
                    $from = $users->firstItem();
                    $to = $users->lastItem();
                    $total = $users->total();
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                @endphp
                Trang {{ $currentPage }}/{{ $lastPage }},
                Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
            </div>
            <div style="margin-top: 10px;">
                {{ $users->appends(request()->except('page'))->links() }}
            </div>
        </div>

        @include('footer')
    </div>
</div>
