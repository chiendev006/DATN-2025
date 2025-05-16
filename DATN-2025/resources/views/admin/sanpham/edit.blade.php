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
       <form action="{{ route('sanpham.update', ['id' => $sanpham->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="field">
        <label class="label">Danh mục sản phẩm</label>
        <div class="control">
            <div class="select">
                <select name="id_danhmuc">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($danhmuc as $dm)
                        <option value="{{ $dm->id }}" {{ $sanpham->id_danhmuc == $dm->id ? 'selected' : '' }}>
                            {{ $dm->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('id_danhmuc')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="field">
        <label class="label">Tên sản phẩm</label>
        <div class="control icons-left">
            <input class="input" type="text" name="name" value="{{ old('name', $sanpham->name) }}" placeholder="Tên sản phẩm">
            <span class="icon left"><i class="mdi mdi-account"></i></span>
            @error('name')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <hr>
    <div class="field">
        <label class="label">Giá sản phẩm</label>
        <div class="control icons-left">
            <input class="input" type="text" name="price" value="{{ old('price', $sanpham->price) }}" placeholder="Giá">
            <span class="icon left"><i class="mdi mdi-cash"></i></span>
            @error('price')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <hr>
    <div class="field">
        <label class="label">Ảnh sản phẩm</label>
        <div class="control icons-left">
            <input class="input" type="file" name="image">
            <span class="icon left"><i class="mdi mdi-image"></i></span>
            @error('image')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        @if ($sanpham->image)
            <p>Ảnh hiện tại:</p>
            <img src="{{ asset('storage/uploads/' . $sanpham->image) }}" width="150px" alt="Ảnh sản phẩm">
        @endif
    </div>
    <hr>
    <div class="field">
        <label class="label">Mô tả sản phẩm</label>
        <div class="control icons-left">
            <input class="input" type="text" name="mota" value="{{ old('mota', $sanpham->mota) }}" placeholder="Mô tả">
            <span class="icon left"><i class="mdi mdi-text"></i></span>
            @error('mota')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <hr>
    <div class="field grouped">
        <div class="control">
            <button type="submit" class="button green">Cập nhật</button>
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