 @extends('layout2')
@section('main')
 <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
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
                                        <input type="text" name="txt" placeholder="Search">
                                        <input type="submit" name="submit" value="&#xf002;">
                                    </div>
                                    <div class="blog-left-categories blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Categories</h5>
                                        <ul class="list">
                                            <li><a href="#">Catering</a></li>
                                            <li><a href="#">Community</a></li>
                                            <li><a href="#">Employment</a></li>
                                            <li><a href="#">Franchise</a></li>
                                            <li><a href="#">Kids Corner</a></li>
                                            <li><a href="#">Our Recipes</a></li>
                                        </ul>
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
                                    <div class="popular-tag blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Popular Tags</h5>
                                        <a href="#">Audio</a> <a href="#">Service</a> <a href="#">Cupcake</a> <a href="#">Online Order</a> <a href="#">Contact</a>
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
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <img src="{{ url('asset') }}/images/img35.png" alt="">
                                            <div class="date-feature">27 <br> <small>may</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>How Do You Like Your Sausage?</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero. In nec enim nisi, in ultricies quam. Sed lacinia feugiat velit, cursus molestie lectus.</p>
                                            <a href="blog_single.html">Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <img src="{{ url('asset') }}/images/img36.png" alt="">
                                            <div class="date-feature">27 <br> <small>may</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>There are many variations of passages</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero. In nec enim nisi, in ultricies quam. Sed lacinia feugiat velit, cursus molestie lectus.</p>
                                            <a href="blog_single.html">Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <img src="{{ url('asset') }}/images/img37.png" alt="">
                                            <div class="date-feature">27 <br> <small>may</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>There are many variations of passages</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero. In nec enim nisi, in ultricies quam. Sed lacinia feugiat velit, cursus molestie lectus.</p>
                                            <a href="blog_single.html">Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <img src="{{ url('asset') }}/images/img38.png" alt="">
                                            <div class="date-feature">27 <br> <small>may</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>How Do You Like Your Sausage?</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero. In nec enim nisi, in ultricies quam. Sed lacinia feugiat velit, cursus molestie lectus.</p>
                                            <a href="blog_single.html">Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="feature-img">
                                            <img src="{{ url('asset') }}/images/img35.png" alt="">
                                            <div class="date-feature">27 <br> <small>may</small></div>
                                        </div>
                                        <div class="feature-info">
                                            <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                                            <span><i class="icon-comment-5"></i> 5 Comments</span>
                                            <h5>How Do You Like Your Sausage?</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero. In nec enim nisi, in ultricies quam. Sed lacinia feugiat velit, cursus molestie lectus.</p>
                                            <a href="blog_single.html">Read More <i class="icon-right-4"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-pagination">
                                        <div class="gallery-pagination-inner">
                                            <ul>
                                                <li><a href="#" class="pagination-prev"><i class="icon-left-4"></i> <span>PREV page</span></a></li>
                                                <li class="active"><a href="#"><span>1</span></a></li>
                                                <li><a href="#"><span>2</span></a></li>
                                                <li><a href="#"><span>3</span></a></li>
                                                <li><a href="#" class="pagination-next"><span>next page</span> <i class="icon-right-4"></i></a></li>
                                            </ul>
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
