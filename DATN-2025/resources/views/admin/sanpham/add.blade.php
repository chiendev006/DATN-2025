@include('header')

 <div class="content-wrapper">

						<!-- Row start -->
						<div class="row gutters">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

								<!-- Card start -->
								<div class="card">
							<section class="section main-section">
    <div class="card mb-6 custom-card">
    <div style='margin-left: 30px; ;'><strong><h2>Thêm sản phẩm</h2> </strong></div>
    <br>
      <div class="card-content">
        <form action="{{ route('sanpham.store') }}" method="post" enctype="multipart/form-data">
            @csrf

                <div id="genCollapse" class="accordion-collapse collapse show" aria-labelledby="genInfo" data-bs-parent="#settingsAccordion">
                    <div style="display: flex; justify-content: space-between;" class="accordion-body">
                        <div style="display: flex;  justify-content: space-between;" class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                        <div class="field-wrapper">
                            <select name="id_danhmuc" class="select-single js-states" id="id_danhmuc" title="Select Product Category" data-live-search="true">
                            @foreach($danhmuc as $dm)
                            <option value="{{ $dm->id }}" data-role="{{ $dm->role }}">{{ $dm->name }}</option>
                            @endforeach
                            </select>
                            <div class="field-placeholder">Danh mục</div>
				        </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                        <div class="field-wrapper">
                            <input type="text"   name="name" placeholder="Tên" required />
                            <div class="field-placeholder">Tên sản phẩm</div>
                            @error('name')
                            <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                              @enderror
                        </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="field-wrapper">
                            <input type="text" name="title" placeholder="Tên" value="{{ old('sizes.0.name') }}"   name="name" placeholder="Tên" required />
                            <div class="field-placeholder">Tiêu đề sản phẩm</div>
                            @error('sizes.0.name')
                            <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                            @enderror
                            </div>
                            </div>

                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="field-wrapper">
                            <div class="input-group">
                            <input name="image" type="file" class="form-control" id="inputGroupFile01">
                            <div class="field-placeholder">Ảnh bìa</div>
                            </div>
                        </div>
                </div>
                    </div>
                </div>


    <div style="margin-top: -20px;" id="genCollapse" class="accordion-collapse collapse show" aria-labelledby="genInfo" data-bs-parent="#settingsAccordion">
        <div style="display: flex; justify-content: space-between;" class="accordion-body">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8">
                            <div class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">
                            <div class="field-wrapper">
                                <div class="input-group">
                                <input name="hasFile[]" placeholder="Ảnh sản phẩm" required multiple accept="image/*" type="file" class="form-control" id="inputGroupFile01">
                                <div class="field-placeholder">Ảnh sản phẩm</div>
                                </div>
                            </div>
                            </div>

                        <div class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11">

                        <div class="field-wrapper">
                               <div class="field-placeholder">Mô tả sản phẩm</div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <textarea id="editor" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" name="mota" placeholder="Nhập mô tả sản phẩm ở đây..."    required></textarea>
                                @error('mota')
                                <p style="color: red;">Bạn chưa nhập mô tả sản phẩm !!!</p>
                                @enderror

                            </div>
                        </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Save Changes</button>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
           <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="display: flex; justify-content: space-between;" class="accordion-body">
           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="field-wrapper">
                <p style="margin-top: -12px; padding-bottom: 5px;" class="topping"><strong>Size - Giá</strong></p>
                    <div id="sizes-container">
                                <div class="size-entry mb-4 p-4 border rounded relative"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <select name="sizes[0][size]" class="select-single js-states" id="sizes[0][size]" title="Select Product Category" data-live-search="true">
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    </select>
                                    <div class="field-placeholder">Tên Size</div>
                                    @error('sizes.0.name')
                                    <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                                    @enderror
                                </div>
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="field-wrapper">
                                    <input type="number" name="sizes[0][price]" placeholder="Điền giá" value="{{ old('sizes.0.price') }}"  required />
                                    <div class="field-placeholder">Giá</div>
                                    @error('sizes.0.price')
                                    <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                                    @enderror
                                </div>
                                </div></div>

                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                <p class="topping"><strong>Topping</strong></p>
                                <p id="noToppingMsg" class="text-danger hide">Danh mục này không đi kèm topping</p>

                                <div style="display: flex;" class="form-check d-flex flex-wrap gap-2 topping-fade" id="topping-field">
                                        @foreach($topping as $tp)
                                        <div class="d-flex align-items-center" style="margin-left: 10px;">
                                            <label class="form-check-label d-flex align-items-center">
                                                <input class="form-check-input" type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
                                                <span style="margin-left: 5px;">{{ $tp->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach

                                    <br>
                                     <div><button style="border-radius: 3px;"  type="button" id="btn-select-all" class="btn-primary">Chọn tất cả</button>
                                        <button style="border-radius: 3px;"   type="button" id="btn-deselect-all" class="btn-danger">Bỏ chọn tất cả</button>
                                        </div>
                                </div>


                            </div>
           </div>
            <div class="field">
            <div class="control">
                <button type="button" id="add-size-button" class="button blue mb-3 custom-add-size">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    Thêm Size Khác
                </button>
            </div>
            </div>
            </div>

    </div>






        </form>
      </div>
    </div>
  </section>
  @include('footer')
         <script>
  $(document).ready(function() {
    $('#editor').summernote({
      height: 150,             // Chiều cao khung soạn thảo
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
   <style>
    .topping{
            margin-top: -5px;
            font-size:.7rem;
            font-family: "Open Sans";
            color:rgb(105, 163, 235);
    }
    .custom-card {
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08), 0 1.5px 4px 0 rgba(0,0,0,0.03);
        border-radius: 16px;
        padding: 32px 24px;
        background: #fff;
    }
    .field {
        margin-bottom: 1.5rem;
    }
    .input, .select select {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 10px 14px;
        font-size: 1rem;
        transition: border 0.2s;
    }
    .input:focus, .select select:focus {
        border: 1.5px solid #2563eb;
        outline: none;
    }
    .custom-add-size {
        background: #2563eb;
        color: #fff;
        border-radius: 8px;
        border: none;
        transition: background 0.2s;
    }
    .custom-add-size:hover {
        background: #1d4ed8;
    }
    .size-entry {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 1rem;
        padding: 1.5rem 1rem 1rem 1rem;
        position: relative;
    }
    .remove-size-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444 !important;
        color: #fff !important;
        border-radius: 6px;
        border: none;
        font-size: 0.95rem;
        padding: 4px 12px;
        transition: background 0.2s;
    }
    .remove-size-button:hover {
        background: #dc2626 !important;
    }
    .custom-topping {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
    }
    .custom-topping-label {
        background: #f3f4f6;
        border-radius: 6px;
        padding: 6px 14px;
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 1rem;
        border: 1px solid #e5e7eb;
        transition: background 0.2s, border 0.2s;
    }
    .custom-topping-label input[type="checkbox"] {
        margin-right: 8px;
        accent-color: #2563eb;
        width: 18px;
        height: 18px;
    }
    .custom-topping-label:hover {
        background: #e0e7ff;
        border: 1.5px solid #2563eb;
    }

    .custom-submit {
        background: #22c55e;
        color: #fff;
        border-radius: 8px;
        border: none;
        font-size: 1.1rem;
        padding: 10px 32px;
        transition: background 0.2s;
    }
    .custom-submit:hover {
        background: #16a34a;
    }
    @media (max-width: 600px) {
        .custom-card { padding: 12px 4px; }
        .size-entry { padding: 1rem 0.5rem; }
    }
    @keyframes fadeInSize {
        from { opacity: 0; transform: translateY(30px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .size-entry-animate {
        animation: fadeInSize 0.8s ease;
    }
    @keyframes fadeOutSize {
        from { opacity: 1; transform: translateY(0);}
        to { opacity: 0; transform: translateY(30px);}
    }
    .size-entry-fadeout {
        animation: fadeOutSize 0.5s ease;
    }
    .topping-fade {
        transition: opacity 0.4s, max-height 0.4s;
        overflow: hidden;
        opacity: 1;
        max-height: 500px;
    }
    .topping-fade.hide {
        opacity: 0;
        max-height: 0;
        pointer-events: none;
    }
    #noToppingMsg {
        transition: opacity 0.4s, max-height 0.4s;
        overflow: hidden;
        opacity: 1;
        max-height: 100px;
    }
    #noToppingMsg.hide {
        opacity: 0;
        max-height: 0;
        pointer-events: none;
    }
  </style>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('id_danhmuc');
    const noToppingMsg = document.getElementById('noToppingMsg');
    const toppingField = document.getElementById('topping-field');
    function checkTopping() {
        const selected = select.options[select.selectedIndex];
        if (selected.getAttribute('data-role') == '1') {
            toppingField.classList.remove('hide');
            noToppingMsg.classList.add('hide');
        } else {
            toppingField.classList.add('hide');
            toppingField.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
            noToppingMsg.classList.remove('hide');
        }
    }
    select.addEventListener('change', checkTopping);
    checkTopping(); // Gọi khi load trang
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addSizeButton = document.getElementById('add-size-button');
    const sizesContainer = document.getElementById('sizes-container');
    let sizeIndex = 1; // Start index for dynamically added sizes (0 is for the initial one)

    addSizeButton.addEventListener('click', function () {
        const newSizeEntry = document.createElement('div');
        newSizeEntry.classList.add('size-entry', 'mb-4', 'p-4', 'border', 'rounded', 'relative', 'size-entry-animate');
        newSizeEntry.addEventListener('animationend', function() {
            newSizeEntry.classList.remove('size-entry-animate');
        });
        newSizeEntry.innerHTML = `
        <br>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="field-wrapper">
                            <select name="sizes[${sizeIndex}][size]" class="select-single js-states" id="sizes[${sizeIndex}][size]" title="Select Product Category" data-live-search="true">
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    </select>
                            <div class="field-placeholder">Tên Size ${sizeIndex + 1}</div>
                            @error('sizes.${sizeIndex}.name')
                            <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                              @enderror
                        </div>
                        </div>


                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="field-wrapper">
                            <input type="number" name="sizes[${sizeIndex}][price]" placeholder="Điền giá" value="{{ old('sizes.0.price') }}"   required />
                            <div class="field-placeholder">Giá ${sizeIndex + 1}</div>
                            @error('sizes.${sizeIndex}.price')
                            <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                              @enderror
                        </div>
                        </div>
        <button type="button" class="button is-danger is-small remove-size-button" style="position: absolute; background-color: red; color: aliceblue; top: 10px; right: 10px;">
            <span class="icon is-small"><i class="mdi mdi-delete"></i></span> Xóa
        </button>

    `;

        sizesContainer.appendChild(newSizeEntry);
        sizeIndex++;

        // Sự kiện xóa form vừa thêm
        newSizeEntry.querySelector('.remove-size-button').addEventListener('click', function() {
            newSizeEntry.classList.add('size-entry-fadeout');
            newSizeEntry.addEventListener('animationend', function handler() {
                newSizeEntry.remove();
                // Cập nhật lại số thứ tự cho tất cả các size-entry
                const allEntries = sizesContainer.querySelectorAll('.size-entry');
                allEntries.forEach((entry, idx) => {
                    const labels = entry.querySelectorAll('label.label');
                    if (labels[0]) labels[0].textContent = `Tên Size ${idx + 1}`;
                    if (labels[1]) labels[1].textContent = `Giá Size ${idx + 1}`;
                    // Cập nhật lại name cho input
                    const allInputs = entry.querySelectorAll('input');
                    if (allInputs[0]) allInputs[0].name = `sizes[${idx}][name]`;
                    if (allInputs[1]) allInputs[1].name = `sizes[${idx}][price]`;
                });
                sizeIndex = allEntries.length;
                newSizeEntry.removeEventListener('animationend', handler);
            });
        });
    });
});

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
