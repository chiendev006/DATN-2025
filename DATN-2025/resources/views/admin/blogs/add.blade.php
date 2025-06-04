@include('header')

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Thêm Blog
    </h1>
  </div>
</section>

<section class="section main-section">
  <div class="card mb-6">
    <div class="card-content">
      <form action="{{ route('blogs.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="field">
          <label class="label">Tiêu đề Blog</label>
          <div class="control icons-left">
            <input class="input" type="text" name="title" placeholder="Nhập tiêu đề" required>
            <span class="icon left"><i class="mdi mdi-note-text"></i></span>
            @error('title')
              <p style="color: red;">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="field">
          <label class="label">Nội dung Blog</label>
          <div class="control icons-left">
            <textarea class="input" name="content" rows="5" placeholder="Nhập nội dung" required></textarea>
            <span class="icon left"><i class="mdi mdi-text"></i></span>
            @error('content')
              <p style="color: red;">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="field">
          <label class="label">Hình ảnh</label>
          <div class="control">
            <input class="input" type="file" name="image" accept="image/*">
            @error('image')
              <p style="color: red;">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="field grouped mt-4">
          <div class="control">
            <button type="submit" class="button green">
              Thêm Blog
            </button>
          </div>
          <div class="control">
            <a href="{{ route('blogs.index') }}" class="button red">Hủy</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

@include('footer')
