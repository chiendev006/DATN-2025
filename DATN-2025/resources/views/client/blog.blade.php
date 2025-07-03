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
                                        <h5>Recent Posts</h5>
                                        <div class="recent-blog-list">
                                            <p><img src="{{ url('asset') }}/images/img18.png" alt=""></p>
                                            <p><small>October 13, 2017</small></p>
                                            <h6>Disclosue - Real food here</h6>
                                        </div>
                                        <div class="recent-blog-list">
                                            <p><img src="{{ url('asset') }}/images/img19.png" alt=""></p>
                                            <p><small>October 13, 2017</small></p>
                                            <h6>Disclosue - Real food here</h6>
                                        </div>
                                    </div>

                                    <div class="blog-left-deal blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Best Deals</h5>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img20.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img21.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img22.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="blog-right-section">
                                  @foreach ($blogs as $item)
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <a href="{{route('client.blogsingle',$item->id)}}">
                                                 <img src="{{ asset('storage/'.$item->image) }} " width="300" height="350" alt="">
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
                        </div>
                    </div>
                </section>

                <!-- End Blog List -->

            </div>
        </main>
        @endsection
