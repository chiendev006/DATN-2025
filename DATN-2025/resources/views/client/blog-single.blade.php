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
                                            <span><i class="icon-user-1"></i> Admin</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>{{$blog->title}}</h5>
                                            <p>{!!$blog->content!!}</p>

                                        </div>
                                        <div class="comment-blog wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                            <h3>2 Comment</h3>
                                            <div class="comment-inner-list">
                                                <div class="comment-img">
                                                    <img src="images/comment-img1.png" alt="">
                                                </div>
                                                <div class="comment-info">
                                                    <h5>Anna Taylor</h5>
                                                    <span class="comment-date">AUGUST 9, 2016 10:57 AM</span>
                                                    <p>Aqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                </div>
                                            </div>
                                            <div class="comment-inner-list">
                                                <div class="comment-img">
                                                    <img src="images/comment-img1.png" alt="">
                                                </div>
                                                <div class="comment-info">
                                                    <h5>Anna Taylor</h5>
                                                    <span class="comment-date">AUGUST 9, 2016 10:57 AM</span>
                                                    <p>Aqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                </div>
                                            </div>
                                            <h3>Leave a Reply</h3>
                                            <form class="form" method="post" name="form">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <textarea placeholder="Comment"></textarea>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="txt" placeholder="Name">
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="email" name="email" placeholder="Email">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" name="txt" placeholder="Web site">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <input type="submit" name="submit" value="POST COMMENT" class="submit-btn">
                                                    </div>
                                                </div>
                                            </form>
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
