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
                                    <div style="display: flex; justify-content: space-between;" class="field-wrapper">

                                    <form action="{{ route('sanpham.filterCategory') }}" method="GET" class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2" style="gap: 5px;">
                                    <div class="field-placeholder">Lọc theo danh mục</div>
                                    <select name="category_id" class="form-select" style="min-width: 180px;" onchange="this.form.submit()">
                                            <option value="allproduct">-- Tất cả --</option>
                                            @foreach($danhmucs as $danhmuc)
                                                <option value="{{ $danhmuc->id }}" {{ (isset($selectedCategory) && $selectedCategory == $danhmuc->id) ? 'selected' : '' }}>{{ $danhmuc->name }}</option>
                                            @endforeach
                                        </select>

                                    </form>
                                    <form class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" action="{{ route('sanpham.search') }}" method="GET" class="mb-3">
                                    <div class="input-group" style="max-width: 400px;">
                                        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm theo tên..." value="{{ isset($search) ? $search : '' }}">
                                        <button class="btn btn-primary" type="submit"><span class="icon-search"></span></button>
                                    </div>
                                </form>
                                </div>


										<div class="table-responsive">
											<table id="copy-print-csv" class="table v-middle">
												<thead>
													<tr>
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
                                                        @foreach($sanpham as $sp)
                                                            <tr>
                                                               <td>
                                                               	{{ $sp['name'] }}
                                                               </td>
                                                               <td><img src="{{ url("/storage/uploads/$sp->image") }}"  width="100px" alt=""></td>
                                                               <td>
                                                               	@php
                                                               		$minSize = $sp->sizes->sortBy('price')->first();
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
                                                               	<div class="actions">
                                                               		<a href="{{ route('sanpham.edit', ['id' => $sp->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                               			<i class="icon-edit1 text-info"></i>
                                                               		</a>
                                                               		<a href="{{ route('sanpham.delete', ['id' => $sp->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                                               			<i class="icon-x-circle text-danger"></i>
                                                               		</a>
                                                               	</div>
                                                               </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="SỐ_CỘT">Không có sản phẩm nào trong danh mục này.</td>
                                                        </tr>
                                                    @endif
												</tbody>
								    	</table>

										</div>

									</div>
								</div>

							</div>
						</div>
						<!-- Row end -->

					</div>
<style>
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
</style>
@include('footer')
