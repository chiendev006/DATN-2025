@include('header')
<div class="control">
  <a href="{{ route('product-images.create') }}" class="button green">Thêm ảnh sản phẩm</a>
</div>

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
          <th class="checkbox-cell"><input type="checkbox"></th>
          <th>Sản phẩm</th>
          <th>Ảnh</th>
          <!-- Bỏ cột Size và Topping -->
          <th>Ảnh chính?</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($product_images as $item)
        <tr>
          <td class="checkbox-cell"><input type="checkbox"></td>
          <td>{{ $item->sanpham->name ?? 'Không rõ' }}</td>
          <td><img src="{{ asset('storage/' . $item->image_url) }}" width="100px" alt="Ảnh sản phẩm"></td>
          <!-- Bỏ hiển thị size và topping -->
          <td>{{ $item->is_primary ? '✔️' : '' }}</td>
          <td class="actions-cell">
            <a href="{{ route('product-images.edit', ['id' => $item->id]) }}" class="button small blue">
              <span class="icon"><i class="mdi mdi-pencil"></i></span>
            </a>

            <form action="{{ route('product-images.delete', ['id' => $item->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa ảnh này?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="button small red">
                <span class="icon"><i class="mdi mdi-trash-can"></i></span>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="table-pagination mt-4">
      <div class="flex items-center justify-between">
        <div class="buttons">
          @for ($i = 1; $i <= $product_images->lastPage(); $i++)
            <a href="{{ $product_images->url($i) }}">
              <button type="button" class="button {{ $i == $product_images->currentPage() ? 'active' : '' }}">
                {{ $i }}
              </button>
            </a>
          @endfor
        </div>
        <small>Page {{ $product_images->currentPage() }} of {{ $product_images->lastPage() }}</small>
      </div>
    </div>
  </div>
</div>
@include('footer')
