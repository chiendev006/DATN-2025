@include('header')
<section class="section main-section">
  <div class="card mb-6">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-image-edit"></i></span>
        Chỉnh sửa ảnh sản phẩm
      </p>
    </header>
    <div class="card-content">
      <form action="{{ route('product-images.update', ['id' => $productImage->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="field">
          <label class="label">Sản phẩm</label>
          <div class="control">
            <div class="select">
              <select name="product_id" required>
                @foreach ($sanpham as $item)
                  <option value="{{ $item->id }}" {{ $productImage->product_id == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <!-- Bỏ phần Size -->
        <!-- Bỏ phần Topping -->

        <div class="field">
          <label class="label">Ảnh sản phẩm</label>
          <div class="control">
            <input type="file" name="image">
            @if ($productImage->image_url)
              <p class="mt-2">
                <img src="{{ asset('storage/' . $productImage->image_url) }}" width="120">
              </p>
            @endif
          </div>
        </div>

        <div class="field">
          <label class="checkbox">
            <input type="checkbox" name="is_primary" {{ $productImage->is_primary ? 'checked' : '' }}>
            Ảnh chính
          </label>
        </div>
        <hr>
        <div class="field grouped">
          <div class="control">
            <button type="submit" class="button green">Cập nhật</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@include('footer')
