@include('header')
  <div class="control">
    <button type="submit" class="button green">
        <a href="{{ route('size.create') }}">Thêm Size</a>
    </button>
            </di>
  <div class="card has-table">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
          Clients
        </p>
        <a href="/admin/danhmuc" class="card-header-icon">
          <span class="icon"><i class="mdi mdi-reload"></i></span>
        </a>
      </header>
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
            <th>Tên Size</th>
            <th>Giá Size</th>
            <th>Hành động</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($size as $item)
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
            </td>
            <td data-label="Name">{{ $item['name'] }}</td>
           <td data-label="Company">{{ number_format($item['price']) }} VND</td>
            <td class="actions-cell">
                <a href="{{ route('size.edit', ['id' => $item->id]) }}" class="button small blue">
                    <span class="icon"><i class="mdi mdi-pencil"></i></span>
                </a>
               <a href="{{ route('size.delete', ['id' => $item->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')" class="button small red">
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
              @for ($i = 1; $i <= $size->lastPage(); $i++)
                <a href="{{ $size->url($i) }}">
                  <button type="button" class="button {{ $i == $size->currentPage() ? 'active' : '' }}">
                    {{ $i }}
                  </button>
                </a>
              @endfor
            </div>
            <small>Page {{ $size->currentPage() }} of {{ $size->lastPage() }}</small>
         </div>
        </div>
      </div>
    </div>
@include('footer')