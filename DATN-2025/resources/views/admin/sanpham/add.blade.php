@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Sản phẩm
    </h1>
  </div>
</section>
 <section class="section main-section">
    <div class="card mb-6 custom-card">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Thêm sản phẩm
        </p>
      </header>
      <div class="card-content">
        <form action="{{ route('sanpham.store') }}" method="post" enctype="multipart/form-data">
            @csrf
             <div class="field">
                <label class="label">Danh mục sản phẩm</label>
                <div class="control">
                <div class="select">
                    <select name="id_danhmuc" id="id_danhmuc">
    @foreach($danhmuc as $dm)
        <option value="{{ $dm->id }}" data-role="{{ $dm->role }}">{{ $dm->name }}</option>
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
                  <input class="input" type="text" name="name" placeholder="Tên" required>
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <div class="field">
                <label class="label">Ảnh bìa</label>
                <div class="field-body">
                    <div class="field">
                    <div class="control icons-left">
                        <input class="input" type="file" name="image" placeholder="Ảnh bìa" required >
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                    </div>
                    </div>
                </div>
                </div>
          <hr>

          <div id="sizes-container">
            <div class="size-entry mb-4 p-4 border rounded">
                <div class="field">
                    <label class="label">Tên Size</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control icons-left">
                                <input class="input" type="text" name="sizes[0][name]" placeholder="Tên" value="{{ old('sizes.0.name') }}">
                                <span class="icon left"><i class="mdi mdi-account"></i></span>
                                @error('sizes.0.name')
                                    <p style="color: red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Giá Size</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control icons-left">
                                <input class="input" type="number" name="sizes[0][price]" placeholder="Giá" value="{{ old('sizes.0.price') }}" min="0">
                                <span class="icon left"><i class="mdi mdi-cash"></i></span>
                                @error('sizes.0.price')
                                    <p style="color: red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
        <hr>
        <div class="field">
          <label class="label">Ảnh sản phẩm</label>
          <div class="field-body">
            <div class="field">
              <div class="control icons-left">
                <input class="input" type="file" name="hasFile[]" placeholder="Ảnh sản phẩm" required multiple accept="image/*">
                <span class="icon left"><i class="mdi mdi-account"></i></span>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="field">
            <label class="label">Mô tả sản phẩm</label>
            <div class="field-body">
                <div class="field">
                <div class="control">
                    <textarea id="editor" class="textarea" name="mota">{{ old('mota') }}</textarea>
                    @error('mota')
                    <p style="color: red;">Bạn chưa nhập mô tả sản phẩm !!!</p>
                    @enderror
                </div>
                </div>
            </div>
            </div>
        <hr>
        <div class="field" id="topping-field" style="display:none;">
            <label class="label">Chọn topping</label>
            <div class="control custom-topping">
                @foreach($topping as $tp)
                <label class="custom-topping-label">
                    <input type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
                    {{ $tp->name }}
                </label>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="field grouped">
            <div class="control">
              <button type="submit" class="button green custom-submit">
                Submit
              </button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </section>
  @include('footer')
  <style>
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
  </style>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('id_danhmuc');
    const toppingField = document.getElementById('topping-field');
    function checkTopping() {
        const selected = select.options[select.selectedIndex];
        if (selected.getAttribute('data-role') == '1') {
            toppingField.style.display = '';
        } else {
            toppingField.style.display = 'none';
            toppingField.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
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
        newSizeEntry.classList.add('size-entry', 'mb-4', 'p-4', 'border', 'rounded', 'relative');

        newSizeEntry.innerHTML = `
        <br>
        <div class="field">
            <label class="label">Tên Size ${sizeIndex + 1}</label>
            <div class="field-body">
                <div class="field">
                    <div class="control icons-left">
                        <input class="input" type="text" name="sizes[${sizeIndex}][name]" placeholder="Tên">
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="field">
            <label class="label">Giá Size ${sizeIndex + 1}</label>
            <div class="field-body">
                <div class="field">
                    <div class="control icons-left">
                        <input class="input" type="text" name="sizes[${sizeIndex}][price]" placeholder="Giá">
                        <span class="icon left"><i class="mdi mdi-cash"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="button is-danger is-small remove-size-button" style="position: absolute; background-color: red; color: aliceblue; top: 10px; right: 10px;">
            <span class="icon is-small"><i class="mdi mdi-delete"></i></span> Xóa
        </button>
        <hr class="mt-3 mb-0">
    `;

        sizesContainer.appendChild(newSizeEntry);
        sizeIndex++;

        // Sự kiện xóa form vừa thêm
        newSizeEntry.querySelector('.remove-size-button').addEventListener('click', function() {
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

            // Giảm biến sizeIndex
            sizeIndex = allEntries.length;
        });
    });
});

</script>

