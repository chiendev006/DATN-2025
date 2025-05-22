@include('admin/header')
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

  .form-flex {
    display: flex;
    gap: 40px;
}
.form-left, .form-right {
    flex: 1;
    min-width: 320px;
}
@media (max-width: 900px) {
    .form-flex { flex-direction: column; }
}

.product-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    max-width: 100%;
    overflow-x: auto;
    background: #f8f8f8;
    padding: 10px;
    border-radius: 8px;
}
.product-gallery img {
    max-width: 120px;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    position: relative;
    transition: filter 0.3s, box-shadow 0.3s;
    cursor: pointer;
}

.product-gallery img:hover {
    filter: brightness(0.5) sepia(1) hue-rotate(-50deg) saturate(5) brightness(0.8);
    box-shadow: 0 0 0 4px red;
}

/* Hiển thị chữ "Delete" khi hover ảnh */
.product-gallery img::after {
    content: "";
    display: none;
}

.product-gallery img:hover::after {
    content: "Delete";
    color: #fff;
    font-weight: bold;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color:red;
    padding: 8px 18px;
    border-radius: 6px;
    font-size: 18px;
    display: block;
    pointer-events: none;
}

/* Đảm bảo ảnh nằm trong relative container để ::after định vị đúng */
.product-gallery {
    position: relative;
}
.product-gallery img {
    position: relative;
    z-index: 1;
}

/* Cập nhật topping: gói các checkbox trong 1 ô, tự xuống dòng */
.update-topping-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    background: #f8f8f8;
    padding: 10px;
    border-radius: 8px;
    max-width: 100%;
    margin-bottom: 10px;
}
.update-topping-checkboxes label {
    font-size: 17px;
    min-width: 180px;
    display: flex;
    align-items: center; /* Canh giữa theo chiều dọc */
    height: 40px;        /* (Tùy chọn) Chiều cao cố định cho label nếu muốn */
    margin-bottom: 0;
}
</style>
<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Sửa sản phẩm
        </p>
      </header>
      <div class="card-content">
        <div class="form-flex">
            <!-- BÊN TRÁI: FORM -->
            <form action="{{ route('sanpham.update', ['id' => $sanpham->id]) }}" method="POST" enctype="multipart/form-data" class="form-left">
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
                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="button green">Cập nhật</button>
                    </div>
                </div>
            </form>
            <!-- BÊN PHẢI: NGOÀI FORM -->
            <div class="form-right">
                <!-- SIZE -->
                <div class="field">
                    <label class="label">Giá sản phẩm (Size)</label>
                    <div class="control icons-left">
                        @if(isset($size) && count($size) > 0)
                            @foreach ($size as $i )
                                <div class="product-info" data-id="{{ $i->id }}">
                                    <span class="original-content">{{ $i->size}}-{{ $i->price." VND" }}</span>
                                </div>
                            @endforeach
                            <br>
                        @else
                            <div style="color: #888; font-style: italic;">Sản phẩm này đang trống Size - Giá, hãy cập nhật thêm!</div>
                        @endif
                        <button type="button" class="btn-primary">
                            <a href="{{ route('size.add', ['sanpham_id' => $sanpham->id]) }}" style="color:white;text-decoration:none;">Thêm Size - Giá</a>
                        </button>
                    </div>
                </div>

                <!-- TOPPING -->
                @if(isset($role) && $role == 0)
                    <div class="field" id="topping-detail-field">
                        <label class="label">Topping có thể dùng</label>
                        <div class="control">
                            <div style="color: #888; font-style: italic;">Sản phẩm này không đi kèm Topping</div>
                        </div>
                    </div>
                @elseif(isset($role) && $role == 1 && isset($topping_detail))
                    <div class="field" id="topping-detail-field">
                        <label class="label">Topping có thể dùng</label>
                        <div class="control">
                            @if(count($topping_detail) > 0)
                                @foreach($topping_detail as $tp)
                                    <span class="product-info topping-info" data-id="{{ $tp->id }}" style="margin-right:8px; cursor:pointer;">
                                        <span class="original-content">{{ $tp->topping }} ({{ number_format($tp->price) }}đ)</span>
                                    </span>
                                @endforeach
                            @else
                                <div style="color: #888; font-style: italic;">Sản phẩm này đang trống Topping, hãy cập nhật thêm!</div>
                            @endif
                        </div>
                        <div class="field">
                        <form action="{{ route('topping_detail.add', ['id' => $sanpham->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $sanpham->id }}">
                            <div class="control update-topping-checkboxes">
                                @foreach($topping_list as $tp)
                                    <label>
                                        <input type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
                                        {{ $tp->name }} {{ number_format($tp->price)." VND" }}
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="btn-primary">Thêm Topping</button>
                        </form>
                        </div>
                    </div>
                @endif

                <!-- KHO ẢNH -->
                <div class="field">
                    <label class="label">Kho ảnh</label>
                    <div class="product-gallery control icons-left">
                        @if(isset($product_img) && count($product_img) > 0)
                            @foreach ($product_img as $i )
                                <img src="{{ asset('storage/uploads/' . $i->image_url) }}" width="120px" style="margin:5px;" alt="Ảnh sản phẩm">
                            @endforeach
                        @else
                            <div style="color: #888; font-style: italic;">Sản phẩm này đang trống Ảnh về sản phẩm, hãy cập nhật thêm!</div>
                        @endif
                    </div>
                    <br>
                    <button type="button" class="btn-primary">
                        <a href="{{ route("product-images.create") }}" style="color:white;text-decoration:none;">Thêm kho ảnh</a>
                    </button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
  @include('admin/footer')

  <script>
document.addEventListener('DOMContentLoaded', function() {
    // Xóa size
    document.querySelectorAll('.product-info:not(.topping-info)').forEach(function(div) {
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
                       location.reload();
                    } else {
                        alert('Xóa thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    // Xóa topping
    document.querySelectorAll('.topping-info').forEach(function(span) {
        span.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa topping này?')) {
                const toppingId = this.getAttribute('data-id');
                fetch(`/admin/topping_detail/delete/${toppingId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                       location.reload();
                    } else {
                        alert('Xóa topping thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });
});
</script>
