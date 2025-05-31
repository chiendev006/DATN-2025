@include('header')

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

  .btn-success {
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
  .btn-success:hover {
    background-color: rgb(0, 0, 217);
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
  padding: 10px;
}

/* Thẻ bọc ảnh */
.product-item {

  border-radius: 18px;
  padding: 1px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
  display: inline-block;
  position: relative;
  overflow: hidden;
}

.product-item:hover {
  background-color: red;
  color: white;
}

/* Ảnh bên trong */
.product-item img {
  width: 100px;
  height: 70px;
  border-radius: 18px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.07);
  display: block;
}

/* Chữ Delete hiển thị khi hover */
.product-item::after {
  content: "Delete";
  color: #fff;
  font-weight: bold;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: red;
  padding: 6px 16px;
  border-radius: 6px;
  font-size: 16px;
  display: none;
  pointer-events: none;
  z-index: 2;
}

.product-item:hover::after {
  display: block;
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
    max-width: 320px;
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
    position: fixed;
    top: 32px;
    right: 32px;
    left: auto;
    background: #e6ffe6;
    color: #1a7f37;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    padding: 12px 24px;
    max-width: 420px;
    font-size: 1.08rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(34,197,94,0.18);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 10000;
    animation: fadeInDown 0.5s;
    /* Thêm hiệu ứng biến mất */
    transition: opacity 0.4s, transform 0.4s;
    opacity: 1;
}
.custom-alert-success.hide {
    opacity: 0;
    transform: translateY(-30px);
    pointer-events: none;
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
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95);}
    to { opacity: 1; transform: scale(1);}
}
@keyframes modalFadeOut {
    from { opacity: 1; transform: scale(1);}
    to { opacity: 0; transform: scale(0.95);}
}
.custom-modal-content.modal-animate-in {
    animation: modalFadeIn 0.35s cubic-bezier(.4,0,.2,1);
}
.custom-modal-content.modal-animate-out {
    animation: modalFadeOut 0.25s cubic-bezier(.4,0,.2,1);
}
</style>
<div class="content-wrapper">



    <section class="section main-section">
<div class="card mb-6 custom-card">
<div style='margin-left: 30px; margin-top: 30px;'><strong><h2>Cập nhật sản phẩm</h2> </strong></div>
<br>
<div class="card-content" style="display: flex; justify-content: space-around;">

<!-- BÊN TRÁI: FORM -->
            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-7"><form action="{{ route('sanpham.update', ['id' => $sanpham->id]) }}" method="POST" enctype="multipart/form-data" class="form-left">
                @csrf
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div style="display: flex; justify-content: space-between;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" >
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                        <div class="field-wrapper">
                            <select name="id_danhmuc" class="select-single js-states" id="id_danhmuc" title="Select Product Category" data-live-search="true">
                            @foreach($danhmuc as $dm)
                                <option value="{{ $dm->id }}" {{ $sanpham->id_danhmuc == $dm->id ? 'selected' : '' }}>
                                     {{ $dm->name }}
                                </option>
                            @endforeach
                            </select>
                            <div class="field-placeholder">Danh mục</div>
				        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                        <div class="field-wrapper">
                            <input class="input" type="text" name="name" value="{{ old('name', $sanpham->name) }}"required />
                            <div class="field-placeholder">Tên sản phẩm</div>
                            @error('name')
                            <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                              @enderror
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                        <div class="field-wrapper">
                            <input class="input" type="text" name="title" value="{{ old('name', $sanpham->title) }}"required />
                            <div class="field-placeholder">Tiêu đề</div>
                        </div>
                    </div>
                </div>
                <div style="display: flex; ">
                <div class="field-wrapper col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                     <div  class="field-placeholder">Ảnh bìa</div>
                    <div class="control icons-left ">
                        <input  class="input " type="file" name="image" id="cover-image-input" style="display:none" accept="image/*">
                        <div id="cover-image-preview" class="cover-image-preview " style="width: 140px; height: 140px; border: 2px dashed #ccc; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; background: #fafafa;">
                            @if ($sanpham->image)
                                <img  src="{{ asset('storage/uploads/' . $sanpham->image) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;"/>
                            @else
                                <span class="cover-image-placeholder" style="color: #aaa;">Chọn ảnh bìa</span>
                            @endif
                        </div>
                        @error('image')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="field-wrapper col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8">

                               <div class="field-placeholder">Mô tả sản phẩm</div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <textarea id="editor" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" name="mota" required >{{  $sanpham->mota }}</textarea>
                                @error('mota')
                                <p style="color: red;">Bạn chưa nhập mô tả sản phẩm !!!</p>
                                @enderror

                            </div>

                </div>
                </div>

                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="btn-success">Cập nhật </button>
                    </div>
                </div>
                </div>

            </form></div>

            <!-- BÊN PHẢI: NGOÀI FORM -->
           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                <!-- SIZE -->
                <div class="field-wrapper">
                      <div class="field-placeholder">Size - Giá</div>
                    <div class="control icons-left">
                        @if(isset($size) && count($size) > 0)
                            @foreach ($size as $i )
                                <div class="product-info" data-id="{{ $i->id }}">
                                    <span class="original-content">{{ $i->size}} - {{ number_format($i->price) }} VND</span>
                                </div>
                            @endforeach
                            <br>
                        @else
                            <div style="color: red; font-style: italic; margin-top: 20px;"><br><p>Sản phẩm này đang trống Size - Giá, hãy cập nhật thêm!</p><br></div>
                        @endif
                        <button type="button" class="btn-success" id="open-size-modal">Thêm Size - Giá</button>
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
                                <div class="field-wrapper">
                                    <input type="text" name="size_name"  placeholder="Tên" required />
                                    <div class="field-placeholder">Tên Size</div>

                                </div>
                              <div class="field-wrapper">
                                    <input type="text" name="size_price" placeholder="Tên" required />
                                    <div class="field-placeholder">Giá</div>
                                </div>
                            <div class="field grouped">
                                 <button type="submit" class="btn-success">Thêm Size - Giá</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END MODAL SIZE -->

                <!-- TOPPING -->
                @if(isset($role) && $role == 0)
                    <div class="field-wrapper" id="topping-detail-field">
                          <div class="field-placeholder">Topping</div>
                        <div class="control">
                            <div style="color: #888; font-style: italic;margin-top: 20px;"><br><p>Sản phẩm này không đi kèm Topping!</p><br></div>
                        </div>
                    </div>
                @elseif(isset($role) && $role == 1 && isset($topping_detail))
                    <div class="field-wrapper" id="topping-detail-field">
                          <div class="field-placeholder">Topping</div>
                        <div class="control">
                            @if(count($topping_detail) > 0)
                                @foreach($topping_detail as $tp)
                                    <span class="product-info topping-info" data-id="{{ $tp->id }}" style="margin-right:8px; cursor:pointer;">
                                        <span class="original-content">{{ $tp->topping }} ({{ number_format($tp->price) }}đ)</span>
                                    </span>
                                @endforeach
                            @else
                                <div style="color: red; font-style: italic;"><br><p>Sản phẩm này đang trống Topping, hãy cập nhật thêm!</p><br></div>
                            @endif
                        </div>
                        <div class="field">
                            <button type="button" class="btn-success" id="open-topping-modal">Thêm Topping</button>
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
                                             <input class="form-check-input" type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
                                                <span style="margin-left: 5px;">{{ $tp->name }}</span>
                                        </label>
                                    @endforeach
                                    <div><button style="border-radius: 3px;"  type="button" id="btn-select-all" class="btn-primary">Chọn tất cả</button>
                                        <button style="border-radius: 3px;"   type="button" id="btn-deselect-all" class="btn-danger">Bỏ chọn tất cả</button>
                                        </div>
                                </div>
                                  <button type="submit" class="btn-success" >Thêm Topping</button>
                            </form>
                        </div>
                    </div>
                    <!-- END MODAL TOPPING -->
                @endif

                <!-- KHO ẢNH -->
                <div class="field-wrapper">
                     <div class="field-placeholder">Ảnh sản phẩm</div>
                    <div style="border: 2px dashed #ccc; border-radius: 10px; width: 390px;  "  class="product-gallery control icons-left">
                        @if(isset($product_img) && count($product_img) > 0)
                            @foreach ($product_img as $i )
                               <div class="product-item"> <img src="{{ asset('storage/uploads/' . $i->image_url) }}" width="120px" style="margin:5px;"
                                     alt="Ảnh sản phẩm"
                                     class="product-gallery-img"
                                     data-id="{{ $i->id }}"></div>
                            @endforeach
                        @else
                            <div style="color: red; font-style: italic;">Sản phẩm này đang trống Ảnh về sản phẩm, hãy cập nhật thêm!</div>
                        @endif
                    </div>
                    <br>
                    <button type="button" class="btn-success" id="open-img-modal">Thêm ảnh</button>
                </div>
               <div id="img-modal" class="custom-modal" style="display:none;">
                    <div class="custom-modal-content">
                        <span class="custom-modal-close" id="close-img-modal">&times;</span>
                        <h3>Thêm ảnh sản phẩm</h3> {{-- Changed heading for clarity --}}
                        <form action="{{ route('product-images.store', ['id' => $sanpham->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $sanpham->id }}">
                            <div class="field-wrapper">
                            <div class="input-group">
                            <input name="hasFile[]" placeholder="Ảnh sản phẩm" required multiple accept="image/*" type="file" class="form-control" id="inputGroupFile01">
                            <div class="field-placeholder">Ảnh sản phẩm</div>
                            </div>
                            </div>
                            <div class="field grouped">
                                 <button type="submit" class="btn-success" >Thêm ảnh</button>
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
  </div>





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
    openSizeBtn && openSizeBtn.addEventListener('click', ()=>{ openModal(sizeModal); });
    closeSizeBtn && closeSizeBtn.addEventListener('click', ()=>{ closeModal(sizeModal); });
    window.addEventListener('click', function(e) {
        if (e.target === sizeModal) closeModal(sizeModal);
    });
    // Modal Topping
    const toppingModal = document.getElementById('topping-modal');
    const openToppingBtn = document.getElementById('open-topping-modal');
    const closeToppingBtn = document.getElementById('close-topping-modal');
    openToppingBtn && openToppingBtn.addEventListener('click', ()=>{ openModal(toppingModal); });
    closeToppingBtn && closeToppingBtn.addEventListener('click', ()=>{ closeModal(toppingModal); });
    window.addEventListener('click', function(e) {
        if (e.target === toppingModal) closeModal(toppingModal);
    });

     // Modal Image Gallery (Corrected)
    const imgGalleryModal = document.getElementById('img-modal'); // Correct ID
    const openImgGalleryBtn = document.getElementById('open-img-modal'); // Correct ID from the button
    const closeImgGalleryBtn = document.getElementById('close-img-modal'); // Correct ID

    openImgGalleryBtn && openImgGalleryBtn.addEventListener('click', ()=>{ openModal(imgGalleryModal); });
    closeImgGalleryBtn && closeImgGalleryBtn.addEventListener('click', ()=>{ closeModal(imgGalleryModal); });
    window.addEventListener('click', function(e) {
        if (e.target === imgGalleryModal) closeModal(imgGalleryModal);
    })

    const alert = document.getElementById('custom-alert-success');
    if(alert) {
        setTimeout(()=>{
            alert.classList.add('hide');
            setTimeout(()=>{ alert.remove(); }, 400);
        }, 2500);
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
    setTimeout(()=>{
        alert.classList.add('hide');
        setTimeout(()=>{ alert.remove(); }, 400);
    }, 2500);
}

function openModal(modal) {
    modal.style.display = 'flex';
    const content = modal.querySelector('.custom-modal-content');
    content.classList.remove('modal-animate-out');
    content.classList.add('modal-animate-in');
}

function closeModal(modal) {
    const content = modal.querySelector('.custom-modal-content');
    content.classList.remove('modal-animate-in');
    content.classList.add('modal-animate-out');
    content.addEventListener('animationend', function handler() {
        modal.style.display = 'none';
        content.classList.remove('modal-animate-out');
        content.removeEventListener('animationend', handler);
    });
}
</script>
<script>
document.getElementById('btn-select-all').addEventListener('click', function () {
  document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
    checkbox.checked = true;
  });

  // Đồng bộ trạng thái với checkbox "Chọn tất cả" nếu có
  const checkAll = document.getElementById('check-all-toppings');
  if (checkAll) checkAll.checked = true;
});

document.getElementById('btn-deselect-all').addEventListener('click', function () {
  document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
    checkbox.checked = false;
  });

  // Đồng bộ trạng thái với checkbox "Chọn tất cả" nếu có
  const checkAll = document.getElementById('check-all-toppings');
  if (checkAll) checkAll.checked = false;
});
</script>
 <script>
  $(document).ready(function() {
    $('#editor').summernote({
      height: 230,             // Chiều cao khung soạn thảo
      placeholder: 'Nhập mô tả sản phẩm ở đây...',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>
