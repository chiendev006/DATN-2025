@include('header')
<section class="section main-section">
  <div class="card mb-6">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-ballot"></i></span>
        Thêm ảnh biến thể
      </p>
    </header>
    <div class="card-content">
      <form action="{{ route('product-images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="field">
          <label class="label">Sản phẩm</label>
          <div class="control">
            <div class="select">
              <select name="product_id" required>
                <option value="">Chọn sản phẩm</option>
                @foreach($sanpham as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <label class="label">Ảnh sản phẩm</label>
          <div class="field-body">
            <div class="field">
              <div class="control icons-left">
                <input class="input" type="file" name="image" placeholder="Ảnh" required>
                <span class="icon left"><i class="mdi mdi-account"></i></span>
              </div>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="label">Ảnh chính</label>
          <div class="field-body">
            <div class="field">
              <div class="control icons-left">
                <input class="input" type="checkbox" name="is_primary" value="1" placeholder="Ảnh chính">
                <span class="icon left"><i class="mdi mdi-account"></i></span>
              </div>
            </div>
          </div>
        </div>

        <div class="field grouped">
          <div class="control">
            <button type="submit" class="button green">
              Submit
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@include('footer')
