 @extends('layout2')
@section('main')
 <main>
        <div class="main-part">
          <!-- Slider Part -->

          <section class="home-slider">
            <div class="tp-banner-container">
              <div class="tp-banner">
                <ul>
                  <li
                    data-transition="fade"
                    data-slotamount="2"
                    data-masterspeed="500"
                    data-thumb=""
                    data-saveperformance="on"
                    data-title="Slide"
                  >
                    <img
                      src="{{ url('asset') }}/images/dummy.png"
                      alt="slidebg1"
                      data-lazyload="{{ url('asset') }}/images/bg1.jpg"
                      data-bgposition="center top"
                      data-bgfit="cover"
                      data-bgrepeat="no-repeat"
                    />

                    <!-- LAYER NR. 1 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 left-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="0"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img1.png"
                      />
                    </div>

                    <!-- LAYER NR. 2 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 right-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="right"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img2.png"
                      />
                    </div>

                    <!-- LAYER NR. 3 -->
                    <div
                      class="tp-caption lft very_large_text text-center"
                      data-x="center"
                      data-y="320"
                      data-speed="900"
                      data-start="1000"
                      data-easing="Power4.easeOut"
                      data-endspeed="350"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      SPECIAL COFFEE <br />
                      <span class="v-light">BEANS</span>
                    </div>

                    <!-- LAYER NR. 4 -->
                    <div
                      class="tp-caption lft text-uppercase large_text text-center best-after"
                      data-x="center"
                      data-y="220"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      best quaITY
                    </div>

                    <!-- LAYER NR. 5 -->
                    <div
                      class="tp-caption lft text-uppercase medium_text text-center"
                      data-x="center"
                      data-y="270"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      Established in 1991
                    </div>
                  </li>

                  <li
                    data-transition="fade"
                    data-slotamount="2"
                    data-masterspeed="500"
                    data-thumb=""
                    data-saveperformance="on"
                    data-title="Slide"
                  >
                    <img
                      src="{{ url('asset') }}/images/dummy.png"
                      alt="slidebg1"
                      data-lazyload="{{ url('asset') }}/images/bg1.jpg"
                      data-bgposition="center top"
                      data-bgfit="cover"
                      data-bgrepeat="no-repeat"
                    />

                    <!-- LAYER NR. 1 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 left-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="0"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img1.png"
                      />
                    </div>

                    <!-- LAYER NR. 2 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 right-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="right"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img2.png"
                      />
                    </div>

                    <!-- LAYER NR. 3 -->
                    <div
                      class="tp-caption lft very_large_text text-center"
                      data-x="center"
                      data-y="320"
                      data-speed="900"
                      data-start="1000"
                      data-easing="Power4.easeOut"
                      data-endspeed="350"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      SPECIAL COFFEE <br />
                      <span class="v-light">BEANS</span>
                    </div>

                    <!-- LAYER NR. 4 -->
                    <div
                      class="tp-caption lft text-uppercase large_text text-center best-after"
                      data-x="center"
                      data-y="220"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      best quaITY
                    </div>

                    <!-- LAYER NR. 5 -->
                    <div
                      class="tp-caption lft text-uppercase medium_text text-center"
                      data-x="center"
                      data-y="270"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      Established in 1991
                    </div>
                  </li>

                  <li
                    data-transition="fade"
                    data-slotamount="2"
                    data-masterspeed="500"
                    data-thumb=""
                    data-saveperformance="on"
                    data-title="Slide"
                  >
                    <img
                      src="{{ url('asset') }}/images/dummy.png"
                      alt="slidebg1"
                      data-lazyload="{{ url('asset') }}/images/bg1.jpg"
                      data-bgposition="center top"
                      data-bgfit="cover"
                      data-bgrepeat="no-repeat"
                    />

                    <!-- LAYER NR. 1 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 left-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="0"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img1.png"
                      />
                    </div>

                    <!-- LAYER NR. 2 -->
                    <div
                      class="tp-caption lft customout rs-parallaxlevel-0 right-slot"
                      data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                      data-x="right"
                      data-hoffset="0"
                      data-y="bottom"
                      data-speed="500"
                      data-start="500"
                      data-easing="Power3.easeInOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      <img
                        src="{{ url('asset') }}/images/dummy.png"
                        alt=""
                        data-lazyload="{{ url('asset') }}/images/img2.png"
                      />
                    </div>

                    <!-- LAYER NR. 3 -->
                    <div
                      class="tp-caption lft very_large_text text-center"
                      data-x="center"
                      data-y="320"
                      data-speed="900"
                      data-start="1000"
                      data-easing="Power4.easeOut"
                      data-endspeed="350"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      SPECIAL COFFEE <br />
                      <span class="v-light">BEANS</span>
                    </div>

                    <!-- LAYER NR. 4 -->
                    <div
                      class="tp-caption lft text-uppercase large_text text-center best-after"
                      data-x="center"
                      data-y="220"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      best quaITY
                    </div>

                    <!-- LAYER NR. 5 -->
                    <div
                      class="tp-caption lft text-uppercase medium_text text-center"
                      data-x="center"
                      data-y="270"
                      data-speed="800"
                      data-start="900"
                      data-easing="Power4.easeOut"
                      data-endspeed="300"
                      data-endeasing="Power1.easeIn"
                      data-captionhidden="off"
                    >
                      Established in 1991
                    </div>
                  </li>
                </ul>
                <div class="tp-bannertimer"></div>
              </div>
            </div>
          </section>

          <!-- End Slider Part -->

          <!-- Default Section -->

          <section class="default-section text-white">
            <div class="container">
              <div class="row">
                <div
                  class="col-md-7 col-sm-7 col-xs-12 wow fadeInDown"
                  data-wow-duration="1000ms"
                  data-wow-delay="300ms"
                >
                  <div class="blog-list dp-animation">
                    <img src="{{ url('asset') }}/images/img3.png" alt="" class="animated" />
                    <div class="blog-over-info">
                      <h3>New coffee flavours</h3>
                      <a
                        href="about.html"
                        class="button-default button-default-white"
                        >Read More</a
                      >
                    </div>
                  </div>
                </div>
                <div
                  class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown"
                  data-wow-duration="1000ms"
                  data-wow-delay="700ms"
                >
                  <div class="blog-list dp-animation">
                    <img src="{{ url('asset') }}/images/img4.png" alt="" class="animated" />
                    <div class="blog-over-info">
                      <h3>
                        Thıs frıday <span class="round-price">25%</span> off
                      </h3>
                    </div>
                  </div>
                  <div class="blog-list dp-animation">
                    <img src="{{ url('asset') }}/images/img5.png" alt="" class="animated" />
                    <div class="blog-over-info">
                      <h3>ENJOYING GREAT</h3>
                      <a
                        href="about.html"
                        class="button-default button-default-white"
                        >Read More</a
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- End Default Section -->

          <!-- Start Menu Item List -->

          <section
            class="default-section discover-menu parallax text-white"
            data-stellar-offset-parent="true"
            data-stellar-background-ratio="0.5"
            style="background-image: url('{{ url('asset') }}/images/banner1.jpg')"
          >
            <div class="container">
              <div class="title text-center">
                <h2 class="text-primary">Discover Menu</h2>
                <h6>What Happens Here</h6>
              </div>
              <div class="item-list">
                <div class="row">
                  @foreach($sanpham as $sp)
					<div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
					<div class="item-wrap dp-animation">
						<div class="item-left">
            <img src="{{ asset('storage/' . ltrim($sp->image, '/')) }}" width="250px" alt="{{ $sp->name }}" style="width: 100px; height: 100px; border-radius: 100px;" class="animated">
						</div>
						<div class="item-right">
						<div class="item-right-top">
							<h5>{{ $sp->name }}</h5>
							<span>$ {{ number_format($sp->min_price, 2) }}</span>
						</div>
						<p>{{ $sp->description ?? 'Mô tả sản phẩm đang cập nhật.' }}</p>
						</div>
					</div>
					</div>
					@endforeach
                </div>
                <div
                  class="btn-wrap wow fadeInUp"
                  data-wow-duration="1000ms"
                  data-wow-delay="300ms"
                >
                  <a href="menu_fixed.html" class="button-default"
                    >Explore Full Menu</a
                  >
                </div>
              </div>
            </div>
          </section>

          <!-- End Menu Item List -->

          <!-- Start Item list Part -->

          <section class="default-section">
            <div class="container">
              <div class="title text-center">
                <h2 class="text-primary">Coffee Build Your Base</h2>
                <h6>What Happens Here</h6>
              </div>
              <div class="product-wrapper">
				<div
					class="owl-carousel owl-theme"
					data-items="4"
					data-tablet="3"
					data-mobile="2"
					data-nav="false"
					data-dots="true"
					data-autoplay="true"
					data-speed="1800"
					data-autotime="5000"
			  >
					@foreach($sanpham as $sp)
					<div class="item">
					<div class="product-img">
						<a href="{{ route('client.product.detail', $sp->id) }}">
					<img src="{{ url('storage/uploads/'.$sp->image) }}" width="250px" alt="{{ $sp->name }}">
						<span class="icon-basket fontello"></span>
						</a>
					</div>
					<h5>{{ $sp->name }}</h5>
					<span>${{ number_format($sp->min_price, 2) }}</span>
					<del>${{ number_format($sp->min_price + 20, 2) }}</del> {{-- giả lập giá cũ --}}
					</div>
					@endforeach
				</div>
				</div>

              <div class="product-single">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="product-single-left bg-skin text-white">
                      <div class="product-single-detail">
                        <h2>TRY THE BEST COFFEE <span>IN THE CITY</span></h2>
                        <p>
                          Alienum phaedrum torquatos nec eu, vis detraxit
                          periculis ex, nihil expetendis in mei. Mei an at
                          dictum lacu pericula uni euripidis.
                        </p>
                        <div class="item-product">
                          <img src="{{ url('asset') }}/images/img10.png" alt="" class="animated" />
                        </div>
                        <a
                          href="menu.html"
                          class="button-default button-default-white margin-top-30"
                          >Explore Full Menu</a
                        >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div
                      class="owl-carousel owl-theme"
                      data-items="1"
                      data-tablet="1"
                      data-mobile="1"
                      data-nav="false"
                      data-dots="true"
                      data-autoplay="true"
                      data-speed="1300"
                      data-autotime="6000"
                    >
                      <div class="item dp-animation">
                        <div class="product-single-right">
                          <img src="{{ url('asset') }}/images/img9.png" alt="" class="animated" />
                        </div>
                      </div>
                      <div class="item dp-animation">
                        <div class="product-single-right">
                          <img src="{{ url('asset') }}/images/img9.png" alt="" class="animated" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- End Item list Part -->

          <!-- Start Feature Part -->

          <section class="default-section bg-grey">
            <div class="container">
              <div class="title text-center">
                <h2 class="text-coffee">Feature Blog</h2>
                <h6>Read Latest Delicious Posts And News</h6>
              </div>
              <div class="feature-blog">
                <div
                  class="owl-carousel owl-theme"
                  data-items="3"
                  data-tablet="2"
                  data-mobile="1"
                  data-nav="true"
                  data-dots="false"
                  data-autoplay="true"
                  data-speed="2500"
                  data-autotime="6000"
                >
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature1.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>Make It SImple</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature2.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>COFFEE SHOP</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature3.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>COFFEE BAR</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature1.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>Make It SImple</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature2.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>COFFEE SHOP</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img src="{{ url('asset') }}/images/feature3.jpg" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>COFFEE BAR</h5>
                      <p>
                        Aptent taciti sociosqu ad litora euismod atras vulputate
                        iltricies etri elit class.
                      </p>
                      <a href="blog_single.html"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- End Feature Part -->

          <!-- Start What Client Say -->

          <section
            class="default-section parallax text-center text-white client-say"
            data-stellar-offset-parent="true"
            data-stellar-background-ratio="0.5"
            style="background-image: url('{{ url('asset') }}/images/banner2.jpg')"
          >
            <div class="container">
              <div
                class="owl-carousel owl-theme"
                data-items="1"
                data-tablet="1"
                data-mobile="1"
                data-nav="false"
                data-dots="true"
                data-autoplay="true"
                data-speed="2000"
                data-autotime="4000"
              >
                <div class="item">
                  <h2 class="text-primary">What Clients Say</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img src="{{ url('asset') }}/images/client1.png" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
                <div class="item">
                  <h2 class="text-primary">What Clients Say</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img src="{{ url('asset') }}/images/client1.png" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
                <div class="item">
                  <h2 class="text-primary">What Clients Say</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img src="{{ url('asset') }}/images/client1.png" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
                <div class="item">
                  <h2 class="text-primary">What Clients Say</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img src="{{ url('asset') }}/images/client1.png" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
                <div class="item">
                  <h2 class="text-primary">What Clients Say</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img src="{{ url('asset') }}/images/client1.png" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- End What Client Say -->

          <!-- Start Feature List -->

          <section class="default-section">
            <div class="container">
              <div class="title text-center">
                <h2 class="text-primary">Our Some Feature</h2>
                <h6 class="text-turkish">
                  The role of a good cook ware in the preparation of a sumptuous
                  meal cannot be over <br />
                  emphasized then one consider white bread
                </h6>
              </div>
              <div class="feature-list">
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon1.png" alt="" />
                    <h5 class="text-coffee">COFFEE MAKER</h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon2.png" alt="" />
                    <h5 class="text-coffee">COFFEE GRINDER</h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon3.png" alt="" />
                    <h5 class="text-coffee">COFFEE CUPS</h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon4.png" alt="" />
                    <h5 class="text-coffee">ESPRESSO MACHINE</h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- End Feature List -->

          <!-- Start Gallery With Sllider -->

          <section class="default-section pad-top-remove">
            <div class="container">
              <div class="title text-center">
                <h2 class="text-primary">#coffeedespina</h2>
                <h6 class="text-turkish">
                  Enjoyed your stay at Despina? Share your moments with us.
                  Follow us on Instagram and use
                </h6>
              </div>
            </div>
            <div class="gallery-slider">
              <div
                class="owl-carousel owl-theme"
                data-items="5"
                data-tablet="4"
                data-mobile="1"
                data-nav="true"
                data-dots="false"
                data-autoplay="true"
                data-speed="2000"
                data-autotime="3000"
              >
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big1.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery1.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big2.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery2.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big3.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery3.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big4.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery4.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big5.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery5.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big1.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery1.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big2.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery2.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big3.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery3.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big4.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery4.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="item dp-animation">
                  <a
                    href="{{ url('asset') }}/images/gallery/gallery-big5.jpg"
                    class="magnific-popup"
                  >
                    <img
                      src="{{ url('asset') }}/images/gallery/gallery5.png"
                      alt=""
                      class="animated"
                    />
                    <div class="gallery-overlay">
                      <div class="gallery-overlay-inner">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </section>

          <!-- End Gallery With Sllider -->
        </div>
      </main>
	  @endsection