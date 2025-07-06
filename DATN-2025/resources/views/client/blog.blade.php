 @extends('layout2')
@section('main')
 <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li class="active"><a href="#">Blog</a></li>
                            </ul>
                            <label class="now">BLOG</label>
                        </div>
                    </div>
                </section>

                <!-- Start Blog List -->

                <section class="default-section blog-main-section blog-list-outer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="blog-left-section">
                                    <div class="blog-left-search blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <form action="{{ route('blog.search') }}" method="GET">
                                            <input type="text" name="keyword" placeholder="Search..." value="{{ request('keyword') }}">
                                            <input type="submit" name="submit" value="&#xf002;" style="font-family: FontAwesome;">
                                        </form>
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

                                    <div class="blog-posts-area">
                                        @if(isset($currentCategory))
                                        @endif

                                        @if($blogs->isEmpty())
                                        <p>Chưa có bài viết nào để hiển thị.</p>
                                        @else @foreach($blogs as $blogItem)
                                        @endforeach

                                        <div class="mt-8">
                                            {{ $blogs->links() }}
                                        </div>
                                        @endif
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
                                  @foreach ($blogs as $item)
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <a href="{{route('client.blogsingle',$item->id)}}">
                                                 <img src="{{ asset('storage/uploads/'.$item->image) }} " width="300" height="350" alt="">
                                            </a>

                                            <div class="date-feature">{{ \Carbon\Carbon::parse($item->created_at)->diffInDays(now()) }}<br> <small>Ngày</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> Admin</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>{{$item->title}}</h5>
                                            <p>{!!$item->content!!}</p>
                                            <a href="{{route('client.blogsingle',$item->id)}}" >Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div style="text-align: center;" class="gallery-pagination">
   <div style="text-align: center;" class="gallery-pagination">
    <div class="gallery-pagination-inner">
        <ul>
            {{-- Nút PREV --}}
            <li>
                <a href="{{ $blogs->onFirstPage() ? '#' : $blogs->previousPageUrl() }}"
                   class="pagination-prev {{ $blogs->onFirstPage() ? 'disabled' : '' }}">
                    <i class="icon-left-4"></i>
                    <span style="font-family: system-ui">PREV PAGE</span>
                </a>
            </li>

            {{-- Hiển thị tối đa 3 trang gần nhất --}}
            @php
                $start = max(1, $blogs->currentPage() - 1);
                $end = min($blogs->lastPage(), $blogs->currentPage() + 1);

                // Nếu đang ở đầu -> dịch phải
                if ($blogs->currentPage() == 1) {
                    $end = min(3, $blogs->lastPage());
                }

                // Nếu đang ở cuối -> dịch trái
                if ($blogs->currentPage() == $blogs->lastPage()) {
                    $start = max($blogs->lastPage() - 2, 1);
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                <li class="{{ $i == $blogs->currentPage() ? 'active' : '' }}">
                    <a href="{{ $blogs->url($i) }}"><span>{{ $i }}</span></a>
                </li>
            @endfor

            {{-- Nút NEXT --}}
            <li>
                <a href="{{ $blogs->hasMorePages() ? $blogs->nextPageUrl() : '#' }}"
                   class="pagination-next {{ $blogs->hasMorePages() ? '' : 'disabled' }}">
                    <span style="font-family: system-ui">NEXT PAGE</span>
                    <i class="icon-right-4"></i>
                </a>
            </li>
        </ul>
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
