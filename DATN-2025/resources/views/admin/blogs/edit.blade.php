@include('header')

<div class="content-wrapper-scroll">
    <div class="content-wrapper">

        <section class="is-hero-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
                <h1 class="title">
                    Sửa blogs
                </h1>
            </div>
        </section>

        <section class="section main-section">
            <div class="card mb-6">
                <div class="card-content">
                    <form style="margin-left: 40px;" action="{{ route('blogs.update', ['id' => $blogs->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                       


                        <div style="display: flex; margin-top: 20px; justify-content: space-around;" class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9">
                            <div class="field-wrapper col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                <div class="field-placeholder">Tên bài viết</div>
                                <div class="control icons-left">
                                    <input class="input" type="text" name="title" placeholder="Nhập tên bài viết" required value="{{ old('title', $blogs->title) }}">
                                    <span class="icon left"><i class="mdi mdi-note-text"></i></span>
                                    @error('title')
                                        <p style="color: red;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="field-wrapper col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                <div class="field-placeholder">Ảnh bìa</div>
                                <div class="control">
                                    <input class="input" type="file" name="image" accept="image/*">
                                    @error('image')
                                        <p style="color: red;">{{ $message }}</p>
                                    @enderror
                                    @if($blogs->image)
                                        <p class="mt-2"><img src="{{ asset('storage/' . $blogs->image) }}" alt="Current Image" style="max-width: 100px; height: auto;"></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field-wrapper col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9" style="margin-top: 20px;">
                            <div class="field-placeholder">Danh mục Blog</div>
                            <div class="control">
                                <select name="blog_id" id="blog_id" class="input" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('blog_id', $blogs->blog_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blog_id')
                                    <p style="color: red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="field-wrapper col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9" style="margin-top: 20px;">
                            <div class="field-placeholder">Nội dung</div>
                            <div class="control icons-left">
                                <textarea class="summernote" name="content" rows="5" placeholder="Nhập nội dung" required>{{ old('content', $blogs->content) }}</textarea>
                                <span class="icon left"><i class="mdi mdi-text"></i></span>
                                @error('content')
                                    <p style="color: red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div style="margin-top: 20px; margin-bottom: 20px; display: flex; justify-content: space-around;" class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9">
                            <div class="control">
                                <button style="border-radius: 10px; width: 120px; padding: 10px;" type="submit" class="btn-success">
                                    Sửa bài viết
                                </button>
                            </div>
                            <div class="control">
                                <a href="{{ route('blogs.index') }}" style="color: white; text-decoration: none;">
                                    <button style="border-radius: 10px; width: 120px; padding: 10px;" type="button" class="btn-danger">
                                        Hủy
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <script>
            $(document).ready(function() {
                $('.summernote').summernote({
                    height: 180,
                    placeholder: 'Nhập nội dung blogs tại đây...',
                });
            });
        </script>


    </div>
</div>

@include('footer')