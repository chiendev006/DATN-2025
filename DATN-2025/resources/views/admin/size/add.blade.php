@include('admin/header')
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

                {{-- Container for all size entries --}}
                <div id="sizes-container">
                    {{-- Initial Size Entry --}}
                    <div class="size-entry mb-4 p-4 border rounded">
                        <div class="field">
                            <label class="label">Tên Size</label>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control icons-left">
                                        {{-- Note the name attribute: sizes[0][name] --}}
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
                                        {{-- Note the name attribute: sizes[0][price] --}}
                                        <input class="input" type="text" name="sizes[0][price]" placeholder="Giá" value="{{ old('sizes.0.price') }}">
                                        <span class="icon left"><i class="mdi mdi-cash"></i></span>
                                        @error('sizes.0.price')
                                            <p style="color: red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        {{-- No remove button for the first entry, or add one if you like --}}
                    </div>
                </div>

                <hr>

                <div class="field">
                    <div class="control">
                        <button type="button" id="add-size-button" class="button blue mb-3">
                            <span class="icon"><i class="mdi mdi-plus"></i></span>
                            Thêm Size Khác
                        </button>
                    </div>
                </div>

                <hr>

                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="button green">
                            Submit All Sizes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .size-entry {
        position: relative;


    }
</style>

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

@include('admin/footer')
