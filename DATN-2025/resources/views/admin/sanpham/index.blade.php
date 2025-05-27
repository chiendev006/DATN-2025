@include('header')

<div class="content-wrapper-scroll">

					<!-- Content wrapper start -->
					<div class="content-wrapper">

						<!-- Row start -->
						<div class="row gutters">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

								<div class="card">
									<div class="card-body">

										<div class="table-responsive">
											<table id="copy-print-csv" class="table v-middle">
												<thead>
													<tr>
													    <th>Tên sản phẩm</th>
                                                        <th>Ảnh sản phẩm</th>
                                                        <th>Mô tả</th>
                                                        <th>Tên danh mục</th>
                                                        <th>Hành động</th>
													</tr>
												</thead>
												<tbody>
                                                    @foreach ($sanpham as $item)
													<tr>
                                                       <td>
													  	{{ $item['name'] }}
													  </td>
													  <td><img src="{{ url("/storage/uploads/$item->image") }}"  width="100px" alt=""></td>
													  <td>{{ $item['mota'] }}</td>
													  <td>{{ $item->danhmuc->name ?? 'Không có danh mục' }}</td>
													  <td>
													  	<div class="actions">
													  		<a href="{{ route('sanpham.edit', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
													  			<i class="icon-edit1 text-info"></i>
													  		</a>
													  		<a href="{{ route('sanpham.delete', ['id' => $item->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
													  			<i class="icon-x-circle text-danger"></i>
													  		</a>
													  	</div>
													  </td>
                                                    </tr>
                                                     @endforeach
												</tbody>
								    	</table>
										</div>

									</div>
								</div>

							</div>
						</div>
						<!-- Row end -->

					</div>

@include('footer')
