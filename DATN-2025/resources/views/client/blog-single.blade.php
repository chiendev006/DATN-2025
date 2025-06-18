@extends('layout2')
@section('main')
   <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
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
                                        <h5>Recent Posts</h5>
                                        <div class="recent-blog-list">
                                            <p><img src="images/img18.png" alt=""></p>
                                            <p><small>October 13, 2017</small></p>
                                            <h6>Disclosue - Real food here</h6>
                                        </div>
                                        <div class="recent-blog-list">
                                            <p><img src="images/img19.png" alt=""></p>
                                            <p><small>October 13, 2017</small></p>
                                            <h6>Disclosue - Real food here</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="blog-left-deal blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Best Deals</h5>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="images/img20.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="images/img21.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="images/img22.png" alt="">
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
