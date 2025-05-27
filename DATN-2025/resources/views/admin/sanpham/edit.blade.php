@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Sản phẩm
    </h1>
  </div>
</section>
<div id="alert-container">
@if(session('success'))
    <div class="custom-alert-success" id="custom-alert-success">
        <span class="custom-alert-icon">✔</span> {{ session('success') }}
    </div>
@endif
</div>
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

.custom-modal {
    position: fixed;
    z-index: 9999;
    left: 0; top: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.3);
    display: flex; align-items: center; justify-content: center;
}
.custom-modal-content {
    background: #fff;
    border-radius: 10px;
    padding: 32px 24px 24px 24px;
    min-width: 320px;
    max-width: 95vw;
    box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08), 0 1.5px 4px 0 rgba(0,0,0,0.03);
    position: relative;
}
.custom-modal-close {
    position: absolute;
    top: 12px; right: 18px;
    font-size: 2rem;
    color: #888;
    cursor: pointer;
    font-weight: bold;
    z-index: 2;
}
.custom-modal-close:hover { color: #e11d48; }

.custom-alert-success {
    background: #e6ffe6;
    color: #1a7f37;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    padding: 12px 24px;
    margin: 18px auto 18px auto;
    max-width: 420px;
    font-size: 1.08rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(34,197,94,0.08);
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
    animation: fadeInDown 0.5s;
    z-index: 10000;
}
.custom-alert-icon {
    font-size: 1.4em;
    color: #22c55e;
    margin-right: 8px;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Sửa sản phẩm
        </p>
        <a href="{{ route('sanpham.index') }}" class="btn-primary" style="min-width:110px;padding:6px 12px;font-size:15px;margin-right:12px;margin-top:8px;height:36px;display:flex;align-items:center;">
            <span class="icon" style="margin-right:6px;"><i class="mdi mdi-arrow-left"></i></span>
            List sản phẩm
        </a>
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
                    <label class="label">Ảnh bìa</label>
                    <div class="control icons-left">
                        <input class="input" type="file" name="image" id="cover-image-input" style="display:none" accept="image/*">
                        <div id="cover-image-preview" class="cover-image-preview" style="width: 140px; height: 140px; border: 2px dashed #ccc; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; background: #fafafa;">
                            @if ($sanpham->image)
                                <img src="{{ asset('storage/uploads/' . $sanpham->image) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;"/>
                            @else
                                <span class="cover-image-placeholder" style="color: #aaa;">Chọn ảnh bìa</span>
                            @endif
                        </div>
                        @error('image')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label class="label">Mô tả sản phẩm</label>
                    <div class="field-body">
                        <div class="field">
                        <div class="control">
                        <textarea id="editor" class="textarea" name="mota" required>{{ old('mota', $sanpham->mota) }}</textarea>
                        @error('mota')
                            <p style="color: red;">Bạn chưa nhập mô tả sản phẩm !!!</p>
                            @enderror
                        </div>
                        </div>
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
                        <button type="button" class="btn-primary" id="open-size-modal">Thêm Size - Giá</button>
                    </div>
                </div>
                <!-- MODAL SIZE -->
                <div id="size-modal" class="custom-modal" style="display:none;">
                    <div class="custom-modal-content">
                        <span class="custom-modal-close" id="close-size-modal">&times;</span>
                        <h3>Thêm Size - Giá</h3>
                        <form action="{{ route('size.store', ['sanpham_id' => $sanpham->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $sanpham->id }}">
                            <div class="field">
                                <label class="label">Tên Size</label>
                                <input class="input" type="text" name="size_name" placeholder="Tên size" required>
                            </div>
                            <div class="field">
                                <label class="label">Giá</label>
                                <input class="input" type="number" name="size_price" placeholder="Giá" min="0" required>
                            </div>
                            <div class="field grouped">
                                <button type="submit" class="button green">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END MODAL SIZE -->

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
                            <button type="button" class="btn-primary" id="open-topping-modal">Thêm Topping</button>
                        </div>
                    </div>
                    <!-- MODAL TOPPING -->
                    <div id="topping-modal" class="custom-modal" style="display:none;">
                        <div class="custom-modal-content">
                            <span class="custom-modal-close" id="close-topping-modal">&times;</span>
                            <h3>Thêm Topping</h3>
                            <form action="{{ route('topping_detail.add', ['id' => $sanpham->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $sanpham->id }}">
                                <div class="control update-topping-checkboxes" style="margin-bottom:16px;">
                                    @foreach($topping_list as $tp)
                                        <label>
                                            <input type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
                                            {{ $tp->name }} {{ number_format($tp->price)." VND" }}
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="button green">Thêm Topping</button>
                            </form>
                        </div>
                    </div>
                    <!-- END MODAL TOPPING -->
                @endif

                <!-- KHO ẢNH -->
                <div class="field">
                    <label class="label">Kho ảnh</label>
                    <div class="product-gallery control icons-left">
                        @if(isset($product_img) && count($product_img) > 0)
                            @foreach ($product_img as $i )
                                <img src="{{ asset('storage/uploads/' . $i->image_url) }}" width="120px" style="margin:5px;"
                                     alt="Ảnh sản phẩm"
                                     class="product-gallery-img"
                                     data-id="{{ $i->id }}">
                            @endforeach
                        @else
                            <div style="color: #888; font-style: italic;">Sản phẩm này đang trống Ảnh về sản phẩm, hãy cập nhật thêm!</div>
                        @endif
                    </div>
                    <br>
                    <button type="button" class="btn-primary" id="open-img-modal">Thêm ảnh</button>
                </div>
               <div id="img-modal" class="custom-modal" style="display:none;">
                    <div class="custom-modal-content">
                        <span class="custom-modal-close" id="close-img-modal">&times;</span>
                        <h3>Thêm ảnh sản phẩm</h3> {{-- Changed heading for clarity --}}
                        <form action="{{ route('product-images.store', ['id' => $sanpham->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $sanpham->id }}">
                            <div class="field">
                                <label class="label">Chọn ảnh</label>
                                <div class="control">
                                    <input class="input" type="file" name="hasFile[]" placeholder="Ảnh sản phẩm" required multiple accept="image/*">
                                </div>
                            </div>
                            <div class="field grouped">
                                <button type="submit" class="button green">Thêm ảnh</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
  @include('footer')

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
                       localStorage.setItem('successMessage', 'Xóa Size-Giá thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Size-Giá thất bại!');
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
                       localStorage.setItem('successMessage', 'Xóa Topping thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Topping thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    // Xóa ảnh sản phẩm
    document.querySelectorAll('.product-gallery-img').forEach(function(img) {
        img.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
                const imgId = this.getAttribute('data-id');
                fetch(`/admin/product_img/delete/${imgId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                       localStorage.setItem('successMessage', 'Xóa Ảnh thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Ảnh thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    const coverInput = document.getElementById('cover-image-input');
    const coverPreview = document.getElementById('cover-image-preview');
    coverPreview.addEventListener('click', function() {
        coverInput.click();
    });
    coverInput.addEventListener('change', function(e) {
        if (coverInput.files && coverInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.innerHTML = `<img src='${e.target.result}' style='width:100%;height:100%;object-fit:cover;border-radius:10px;'/>`;
            }
            reader.readAsDataURL(coverInput.files[0]);
        }
    });

    // Modal Size
    const sizeModal = document.getElementById('size-modal');
    const openSizeBtn = document.getElementById('open-size-modal');
    const closeSizeBtn = document.getElementById('close-size-modal');
    openSizeBtn && openSizeBtn.addEventListener('click', ()=>{ sizeModal.style.display = 'flex'; });
    closeSizeBtn && closeSizeBtn.addEventListener('click', ()=>{ sizeModal.style.display = 'none'; });
    window.addEventListener('click', function(e) {
        if (e.target === sizeModal) sizeModal.style.display = 'none';
    });
    // Modal Topping
    const toppingModal = document.getElementById('topping-modal');
    const openToppingBtn = document.getElementById('open-topping-modal');
    const closeToppingBtn = document.getElementById('close-topping-modal');
    openToppingBtn && openToppingBtn.addEventListener('click', ()=>{ toppingModal.style.display = 'flex'; });
    closeToppingBtn && closeToppingBtn.addEventListener('click', ()=>{ toppingModal.style.display = 'none'; });
    window.addEventListener('click', function(e) {
        if (e.target === toppingModal) toppingModal.style.display = 'none';
    });

     // Modal Image Gallery (Corrected)
    const imgGalleryModal = document.getElementById('img-modal'); // Correct ID
    const openImgGalleryBtn = document.getElementById('open-img-modal'); // Correct ID from the button
    const closeImgGalleryBtn = document.getElementById('close-img-modal'); // Correct ID

    openImgGalleryBtn && openImgGalleryBtn.addEventListener('click', ()=>{
        imgGalleryModal.style.display = 'flex';
    });
    closeImgGalleryBtn && closeImgGalleryBtn.addEventListener('click', ()=>{
        imgGalleryModal.style.display = 'none';
    });
    window.addEventListener('click', function(e) {
        if (e.target === imgGalleryModal) imgGalleryModal.style.display = 'none';
    })

    const alert = document.getElementById('custom-alert-success');
    if(alert) {
        setTimeout(()=>{ alert.style.display = 'none'; }, 2500);
    }

    // Hiển thị alert khi xóa thành công qua localStorage
    const msg = localStorage.getItem('successMessage');
    if (msg) {
        showCustomSuccess(msg);
        localStorage.removeItem('successMessage');
    }
});

function showCustomSuccess(msg) {
    let alert = document.createElement('div');
    alert.className = 'custom-alert-success';
    alert.innerHTML = '<span class="custom-alert-icon">✔</span> ' + msg;
    alert.id = 'custom-alert-success';
    var container = document.getElementById('alert-container');
    if(container) {
        container.appendChild(alert);
    } else {
        document.body.prepend(alert);
    }
    setTimeout(()=>{ alert.style.display = 'none'; }, 2500);
}
</script>
