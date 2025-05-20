@include('header')
<style>
  /* Style for the div by default */
  .product-info {
    background-color: aqua;
    border-radius: 10px;
    margin-left: 10px;
    padding: 10px;
    cursor: pointer; /* Indicates it's clickable */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition */
    display: inline-block; /* To make sure it doesn't take full width if not needed */
    position: relative; /* Needed for positioning the "Delete" text or icon */
    overflow: hidden; /* To hide the "Delete" text initially if it's too long */
  }

  /* Style for the div on hover */
  .product-info:hover {
    background-color: red;
    color: white; /* Make text white for better contrast on red */
  }

  /* Add a "Delete" text or icon on hover */
  .product-info:hover::before {
    content: "Delete"; /* You can change this text */
    /* Alternatively, for an icon, you might use a unicode character or an icon font:
       content: "\f00d"; /* Example using Font Awesome trash icon (needs Font Awesome linked) */
       /* font-family: "Font Awesome 5 Free"; /* Example */
       /* font-weight: 900; /* Example */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
  }

  .btn-primary {
    background-color:rgb(65, 201, 70); /* Green */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
  /* Hide the original text on hover if you only want to show "Delete" */
  .product-info:hover .original-content {
    visibility: hidden;
  }
</style>
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
        <div  class="control icons-left">
           @foreach ($size as $i )
                <div class="product-info" data-id="{{ $i->id }}">
                    <span class="original-content">{{ $i->size}}-{{ $i->price }}</span>
                </div>
            @endforeach

            <br>
            <button type="button" class="btn-primary">
                <a href="{{ route('size.add', ['sanpham_id' => $sanpham->id]) }}" style="color:white;text-decoration:none;">Thêm Size - Giá</a>
            </button>
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
        <label class="label">Kho ảnh</label>
        <div style="display: flex;" class="control icons-left">
          @foreach ($product_img as $i )
                <img src="{{ asset('storage/uploads/' . $i->image_url) }}" width="150px" alt="Ảnh sản phẩm">
            @endforeach

        </div>
         <br>
         <button type="button" class="btn-primary">
                <a href="{{ route("product-images.create") }}" style="color:white;text-decoration:none;">Thêm kho ảnh</a>
            </button>
    </div>

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

  <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-info').forEach(function(div) {
        div.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa size này?')) {
                const sizeId = this.getAttribute('data-id');
                fetch(`/admin/size/delete/${sizeId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        div.remove();
                    } else {
                        alert('Xóa thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });
});
</script>
