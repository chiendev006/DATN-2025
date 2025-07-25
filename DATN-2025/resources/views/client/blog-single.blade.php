@extends('layout2')
@section('main')
   <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li><a href="blog_2col.html">Blog</a></li>
                                <li class="active"><a href="#">Blog Single</a></li>
                            </ul>
                            <label class="now">BLOG</label>
                        </div>
                    </div>
                </section>

                <!-- Start Blog List -->

                <section class="default-section blog-main-section blog-single">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="blog-left-section">
                                    <div class="blog-left-search blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <input type="text" name="txt" placeholder="Search">
                                        <input type="submit" name="submit" value="&#xf002;">
                                    </div>
                                  <div
                                        class="blog-left-categories blog-common-wide wow fadeInDown"
                                        data-wow-duration="1000ms"
                                        data-wow-delay="300ms"
                                    >
                                        <h5>Categories</h5>
                                        <ul class="list">
                                            @foreach($categories as $category)
                                            <li>
                                                <a href="{{ route('blog.category', $category->id) }}"
                                                    class="{{ (isset($currentCategory) && $currentCategory->id == $category->id) ? 'active' : '' }}"
                                                >
                                                    {{ $category->name }}
                                                    ({{ $category->blogs->count() }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="blog-recent-post blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <h5 style="margin-bottom: 20px;">Recent Posts</h5>

    @foreach ($recentBlogs as $blog)
        <div class="recent-blog-list" style="text-align: center; margin-bottom: 20px;">
            {{-- Ngày --}}
            <p style="font-size: 13px; color: #999; margin-top: 8px;">
                {{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}
            </p>
            {{-- Ảnh lớn hơn --}}
            <a href="{{ route('blog.show', $blog->id) }}">
                <img src="{{ asset('storage/uploads/' . $blog->image) }}"
                     alt="{{ $blog->title }}"
                     style="width: 100%; max-width: 150px; height: auto; border-radius: 6px; object-fit: cover;">
            </a>

            {{-- Tiêu đề --}}
            <h6 style="font-size: 15px; margin-top: 5px;">
                <a href="{{ route('blog.show', $blog->id) }}" style="color: #333; text-decoration: none;">
                    {{ \Illuminate\Support\Str::limit($blog->title, 60) }}
                </a>
            </h6>
        </div>
    @endforeach
</div>

                                    <div class="blog-left-deal blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <h5>Best Deals</h5>

    @if(isset($bestDeals) && $bestDeals->count())
        @foreach($bestDeals as $deal)
            <div class="best-deal-blog">
                <div class="best-deal-left">
                    <img src="{{ asset('storage/uploads/' . $deal->image) }}" alt="{{ $deal->name }}">
                </div>
                <div class="best-deal-right">
                    <p>{{ $deal->name }}</p>
                    <p><strong>{{ number_format($deal->price, 0, ',', '.') }} đ</strong></p>
                </div>
            </div>
        @endforeach
    @else
        <p>Không có sản phẩm nào.</p>
    @endif
</div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="blog-right-section">
                                    <div class="blog-right-listing">
                                        <div class="feature-img wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                            <img style="border-radius:10px;" src="{{ asset('storage/'.$blog->image) }}" alt="">
                                            <div class="date-feature">{{ \Carbon\Carbon::parse($blog->created_at)->format('d/m') }} <br> </div>
                                        </div>
                                        <div class="feature-info wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">

                                            <h5>{{$blog->title}}</h5>
                                            <p>{!!$blog->content!!}</p>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End Blog List -->

            </div>
        </main>
@endsection
