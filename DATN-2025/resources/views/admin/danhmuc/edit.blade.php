@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Danh mục
    </h1>
  </div>
</section>
 <section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Chỉnh sửa danh mục
        </p>
      </header>
      <div class="card-content">
        <form action="{{ route('danhmuc.update',['id'=>$danhmuc->id]) }}" method="post">
            @csrf
          <div class="field">
            <label class="label">Tên danh mục</label>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" value="{{ $danhmuc['name'] }}" name="name" placeholder="Name">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên danh mục !!!</p>
                  @enderror
                </div>
            <div class="field">
            <label class="label">Có topping?</label>
            <div class="control" style="margin-top: 8px;">
                <label style="margin-right: 15px;">
                    <input type="radio" style="accent-color:rgb(0, 0, 0);" name="has_topping" value="1"
                        {{ $danhmuc->has_topping == 1 ? 'checked' : '' }}>
                    Có
                </label>
                <label>
                    <input type="radio" style="accent-color:rgb(0, 0, 0);" name="has_topping" value="0"
                        {{ $danhmuc->has_topping == 0 ? 'checked' : '' }}>
                    Không
                </label>
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
      </div>
    </div>
  </section>
  @include('footer')
