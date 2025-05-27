@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Sản phẩm
    </h1>
  </div>
</section>
  <div class="control">
    <button type="submit" class="button green">
        <a href="{{ route('sanpham.create') }}">Thêm sản phẩm</a>
    </button>
            </di>
  <div class="card has-table">

      <div class="card-content">
        @if (session('success'))
				<div style="color: green; background-color: #e6ffe6; padding: 10px; margin-bottom: 10px;">
					{{ session('success') }}
				</div>
			@endif
        <table>
          <thead>
          <tr>
            <th class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </th>
            <th class="image-cell"></th>
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
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
            </td>
            <td data-label="Company">{{ $item['name'] }}</td>
            <td data-label="Company"><img src="{{ url("/storage/uploads/$item->image") }}"  width="100px" alt=""></td>
            <td data-label="Company">{{ $item['mota'] }}</td>
            <td data-label="Company">{{ $item->danhmuc->name ?? 'Không có danh mục' }}</td>
            <td class="actions-cell">
                <a href="{{ route('sanpham.edit', ['id' => $item->id]) }}" class="button small blue">
                    <span class="icon"><i class="mdi mdi-pencil"></i></span>
                </a>
               <a href="{{ route('sanpham.delete', ['id' => $item->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')" class="button small red">
                  <span class="icon">
                      <i class="mdi mdi-trash-can"></i>
                  </span>
              </a>
            </td>
            @endforeach
          </tbody>
        </table>
          <div class="table-pagination mt-4">
          <div class="flex items-center justify-between">
            <div class="buttons">
              @for ($i = 1; $i <= $sanpham->lastPage(); $i++)
                <a href="{{ $sanpham->url($i) }}">
                  <button type="button" class="button {{ $i == $sanpham->currentPage() ? 'active' : '' }}">
                    {{ $i }}
                  </button>
                </a>
              @endfor
            </div>
            <small>Page {{ $sanpham->currentPage() }} of {{ $sanpham->lastPage() }}</small>
         </div>
        </div>
      </div>
    </div>

@include('footer')
