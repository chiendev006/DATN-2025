@include('header')

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Blog
    </h1>
  </div>
</section>

<div class="card-content">
  <form action="{{ route('blogs.update', ['id' => $blogs->id]) }}" method="post">
    @csrf
    <div class="field">
      <label class="label">Tiêu đề Blog</label>
      <div class="field-body">
        <div class="field">
          <div class="control icons-left">
            <input class="input" type="text" value="{{ $blogs->title }}" name="title" placeholder="Nhập tiêu đề">
            <span class="icon left"><i class="mdi mdi-note-text"></i></span>
            @error('title')
              <p style="color: red;">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
    </div>

    <hr>

    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9">
      <label class="label">Nội dung Blog</label>
      <div class="field-body">
        <div class="field">
          <div class="control icons-left">
            <textarea class="summernote" name="content" rows="5" placeholder="Nhập nội dung">{{ $blogs->content }}</textarea>
            <span class="icon left"><i class="mdi mdi-text"></i></span>
            @error('content')
              <p style="color: red;">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
    </div>
   
    <hr>

    <div class="field grouped">
      <div class="control">
        <button type="submit" class="button green">
          Cập nhật
        </button>
      </div>
    </div>
  </form>
</div>
<script>
      $(document).ready(function() {
        $('.summernote').summernote({
          height: 300,
          placeholder: 'Nhập nội dung blog tại đây...',
        });
      });
</script>
@include('footer')
