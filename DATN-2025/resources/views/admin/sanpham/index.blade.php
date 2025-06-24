@include('header')



<div class="content-wrapper-scroll">
					<!-- Content wrapper start -->
					<div class="content-wrapper">
						<!-- Row start -->
						<div class="row gutters">

							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

								<div class="card">
                                <div style='display: flex; justify-content: space-between; margin-top: 30px;'><strong><h2 style="margin-left: 30px;">Sản phẩm</h2> </strong> 	<button style="margin-right: 30px;">
											<a href="{{ route('sanpham.create') }}">Thêm sản phẩm</a>
										</button></div>
                                <br>
									<div class="card-body">
                                   <div style="display: flex; justify-content: space-between;">

                                                   <form method="GET" action="{{ url()->current() }}" class="field-wrapper" style="display: flex; align-items: center;">
                                                       <div class="field-placeholder">Bản/trang</div>
                                                        <select name="per_page" class="form-control" style="width: 80px; margin-left: 12px;" onchange="this.form.submit()">
                                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bản </option>
                                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản  </option>
                                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản</option>
                                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 bản</option>
                                                        </select>
                                                        @foreach(request()->except(['per_page','page']) as $key => $val)
                                                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                                        @endforeach
                                                    </form>

                                    <div style="display: flex; justify-content: space-between; margin-left: 10px;" class="field-wrapper col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">

                                    <form action="{{ route('sanpham.filterCategory') }}" method="GET" class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2" style="gap: 5px;">
                                    <div class="field-placeholder">Lọc theo danh mục</div>
                                    <select name="category_id" class="form-select" style="min-width: 180px;" onchange="this.form.submit()">
                                            <option value="allproduct">-- Tất cả --</option>
                                            @foreach($danhmucs as $danhmuc)
                                                <option value="{{ $danhmuc->id }}" {{ (isset($selectedCategory) && $selectedCategory == $danhmuc->id) ? 'selected' : '' }}>{{ $danhmuc->name }}</option>
                                            @endforeach
                                        </select>

                                    </form>
                                    @if ($errors->any())
                                        <div class="alert alert-danger" style="margin-bottom: 20px;">
                                            <ul style="margin: 0;">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" action="{{ route('sanpham.search') }}" method="GET">
                                <div class="input-group" style="max-width: 400px;">
                                       <div class="field-placeholder">Tìm sản phẩm</div>
                                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm theo tên..." value="{{ request('q') }}">

                                    {{-- Giữ lại giá nếu có --}}
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                                    <button class="btn btn-primary" type="submit">
                                        <span class="icon-search"></span>
                                    </button>
                                </div>
                            </form>

                                <form class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" action="{{ route('sanpham.search') }}" method="GET">
                                    <div class="input-group" style="max-width: 400px;">
                                           <div class="field-placeholder">Lọc theo giá</div>
                                        <input type="number" name="min_price" class="form-control" placeholder="Giá từ" value="{{ request('min_price') }}">
                                        <input type="number" name="max_price" class="form-control" placeholder="Đến" value="{{ request('max_price') }}" >

                                        {{-- Giữ lại tên tìm kiếm nếu có --}}
                                        <input type="hidden" name="q" value="{{ request('q') }}">

                                        <button class="btn btn-primary" type="submit">
                                            <span class="icon-search"></span>
                                        </button>
                                    </div>
                                </form>

                                </div>
                                   </div>


										<div class="table-responsive">

											<table id="copy-print-csv" class="table v-middle">
												<thead>
													<tr>
                                                        <th>STT</th>
													    <th>Tên sản phẩm</th>
                                                        <th>Ảnh sản phẩm</th>
                                                        <th>Size - Giá (nhỏ nhất)</th>
                                                        <th>Mô tả</th>
                                                        <th>Tên danh mục</th>
                                                        <th>Hành động</th>
													</tr>
												</thead>
												<tbody>
                                                    @if($sanpham->count() > 0)
                                                        @foreach($sanpham as $key => $sp)
                                                            <tr>
                                                                <td>{{ ($sanpham->currentPage()-1) * $sanpham->perPage() + $key + 1 }}</td>
                                                               <td>
                                                               	{{ $sp['name'] }}
                                                               </td>
                                                               <td><img src="{{ url("/storage/uploads/$sp->image") }}"  width="100px" alt=""></td>
                                                               <td>
                                                               	@php
                                                               		$minSize = $sp->attributes->sortBy('price')->first();
                                                               	@endphp
                                                               	@if($minSize)
                                                               		{{ $minSize->size }} - {{ number_format($minSize->price) }}đ
                                                               	@else
                                                               		Không có size
                                                               	@endif
                                                               </td>
                                                               <td>{!! $sp['mota'] !!}</td>
                                                               <td>{{ $sp->danhmuc->name ?? 'Không có danh mục' }}</td>
                                                               <td>
                                                               	<div class="actions" style="display: flex; gap: 10px; justify-content: center;">
                                                               		<a style=" background-color: rgb(76, 106, 175); color: white; border: none; border-radius: 5px; cursor: pointer;font-size: 12px;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;" href="{{ route('sanpham.edit', ['id' => $sp->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                               			Sửa
                                                               		</a>
                                                               		 <form action="{{ route('sanpham.delete', ['id' => $sp->id]) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</button>
                                                                    </form>
                                                               	</div>
                                                               </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6">Không có sản phẩm nào trong danh mục này.</td>
                                                        </tr>
                                                    @endif
												</tbody>
								    	</table>

										</div>
@php
    $from = $sanpham->firstItem();
    $to = $sanpham->lastItem();
    $total = $sanpham->total();
    $currentPage = $sanpham->currentPage();
    $lastPage = $sanpham->lastPage();
@endphp
<div class="text-muted mb-2" style="font-size:13px;">
    Trang {{ $currentPage }}/{{ $lastPage }},
    Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
</div>
<div class="d-flex justify-content-center mt-3">
    {{ $sanpham->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>

									</div>
								</div>

							</div>
						</div>
						<!-- Row end -->

					</div>
<style>
       th{
            text-align: center;
        }
        td{
            text-align: center;
        }
    button {
        background-color:rgb(76, 106, 175); /* Màu nền */
        color: white; /* Màu chữ */
        padding: 10px 20px; /* Khoảng cách xung quanh chữ */
        border: none; /* Loại bỏ viền */
        border-radius: 5px; /* Bo tròn góc */
        cursor: pointer; /* Hiển thị con trỏ là nút bấm */
        font-size: 16px; /* Kích thước chữ */
        text-align: center; /* Canh giữa chữ */
        transition: background-color 0.3s; /* Hiệu ứng khi di chuột */
    }

    button a {
        color: white; /* Màu chữ của link */
        text-decoration: none; /* Loại bỏ gạch chân */
    }

    button:hover {
        background-color:rgb(67, 89, 170); /* Màu nền khi hover */
    }

    button:active {
        background-color:rgb(50, 100, 144); /* Màu nền khi nhấn */
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
@include('footer')
