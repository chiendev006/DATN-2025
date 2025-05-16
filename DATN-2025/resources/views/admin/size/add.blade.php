@include('header')
 <section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Thêm Size
        </p>
      </header>
      <div class="card-content">
        <form action="{{ route('size.store') }}" method="post">
            @csrf
          <div class="field">
            <label class="label">Tên Size</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" name="name" placeholder="Tên">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên danh mục !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>
          <div class="field">
            <label class="label">Giá Size</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" name="price" placeholder="Giá">
                  <span class="icon left"><i class="mdi mdi-cash"></i></span>
                  @error('price')
                    <p style="color: red;">Bạn chưa nhập tên danh mục !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>
          <div class="field grouped">
            <div class="control">
              <button type="submit" class="button green">
                Submit
              </button>
            </div>
          </div>
        </form>
        <!-- <div class="control">
              <button type="reset" class="button red">
                <a href="/admin/danhmuc/create">Reset</a>
              </button>
        </div> -->
      </div>
    </div>
  </section>
  @include('footer')