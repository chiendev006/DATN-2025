@include('header')
 <section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Thêm danh mục
        </p>
      </header>
      <div class="card-content">
        <form action="{{ route('sanpham.store') }}" method="post" enctype="multipart/form-data">
            @csrf
             <div class="field">
                <label class="label">Danh mục sản phẩm</label>
                <div class="control">
                <div class="select">
                    <select name="id_danhmuc">
                    <option value="">Chọn danh mục</option>
                    @foreach($danhmuc as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->name }}</option>
                    @endforeach
                    </select>
                </div>
                @error('id_danhmuc')
                     <p style="color: red;">Bạn chưa chọn danh mục sản phẩm !!!</p>
                @enderror
                </div>
            </div>
          <div class="field">
            <label class="label">Tên sản phẩm</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" name="name" placeholder="Tên">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>
           <div class="field">
            <label class="label">Giá sản phẩm</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" name="price" placeholder="Giá">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('price')
                    <p style="color: red;">Bạn chưa nhập giá sản phẩm !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>
           <div class="field">
            <label class="label">Ảnh sản phẩm</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="file" name="image" placeholder="Ảnh">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('image')
                    <p style="color: red;">Bạn chưa nhập ảnh sản phẩm !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>
           <div class="field">
            <label class="label">   Mô tả sản phẩm</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" name="mota" placeholder="Mô tả">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('mota')
                    <p style="color: red;">Bạn chưa nhập mô tả  sản phẩm !!!</p>
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