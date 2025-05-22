@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Sản phẩm
    </h1>
  </div>
</section>
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
                  <input class="input" type="text" name="name" placeholder="Tên">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên sản phẩm !!!</p>
                  @enderror
                </div>
              </div>
          </div>
          <hr>

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
          <div class="field" id="topping-field" style="display:none;">
    <label class="label">Chọn topping</label>
    <div class="control">
        @foreach($topping as $tp)
    <label style="margin-right:10px;">
        <input type="checkbox" name="topping_ids[]" value="{{ $tp->id }}">
        {{ $tp->name }}
    </label>
@endforeach
    </div>
</div>
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
<style>
input[type="checkbox"] {
    display: inline-block !important;
}
</style>
