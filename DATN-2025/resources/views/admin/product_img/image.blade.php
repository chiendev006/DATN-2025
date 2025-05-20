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
          <label class="label">Ảnh sản phẩm</label>
          <div class="field-body">
            <div class="field">
              <div class="control icons-left">
                <input class="input" type="file" name="image[]" placeholder="Ảnh" required multiple>
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
