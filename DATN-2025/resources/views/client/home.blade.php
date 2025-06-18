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
Cà phê - Cao cấp<br />
                      <span class="v-light">Đậm</span>
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
                     Mira café
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
                    2025
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
                      Sinh tố - Tự nhiên <br />
                      <span class="v-light">Tươi</span>
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
                  mira café
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
                      2025
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
                      <h3>Hương vị cà phê mới</h3>
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
                        Caffee chồn giảm  <span class="round-price">25%</span> 
                      </h3>
                    </div>
                  </div>
                  <div class="blog-list dp-animation">
                    <img src="{{ url('asset') }}/images/img5.png" alt="" class="animated" />
                    <div class="blog-over-info">
                      <h3>Thưởng thức tuyệt vời</h3>
                     
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
                <h2 class="text-primary">Danh sách Menu</h2>
                <h6>Mời bạn tham khảo nhé ^^</h6>

              </div>
              <div class="item-list">
                <div class="row">
                  @foreach($sanpham as $sp)
					<div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
					<div class="item-wrap dp-animation">
						<div class="item-left">
            <img src="{{ url('storage/uploads/' .$sp->image) }}" width="250px" alt="{{ $sp->name }}" style="width: 100px; height: 100px; border-radius: 100px;" class="animated">
						</div>
						<div class="item-right">
						<div class="item-right-top">
							<h5>{{ $sp->name }}</h5>
							<span> {{ number_format($sp->min_price) }} VNĐ</span>

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
                  <a href="/menu" class="button-default"
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
                <h2 class="text-primary">Một số sản phẩm nổi bật</h2>
                <h6>Mời bạn tham khảo nhé ^^</h6>

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

					<span>{{ number_format($sp->min_price) }} VNĐ</span>

					</div>
					@endforeach
				</div>
				</div>

              <div class="product-single">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="product-single-left bg-skin text-white">
                      <div class="product-single-detail">
                        <h2>THỬ CÀ PHÊ NGON <span><br> HẢI PHÒNG</span></h2>
                        <p>
                          Được thiết kế với tone màu ấm cúng, ánh đèn vàng nhẹ và những bản nhạc chill,
                           quán đem lại cảm giác thư giãn nhẹ nhàng,
                            thích hợp để "chạy trốn" khỏi guồng quay đô thị.
                        </p>
                        <div class="item-product">
                          <img src="{{ url('asset') }}/images/img10.png" alt="" class="animated" />
                        </div>
                        
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
                @foreach ($blog as $item)
                  <div class="item dp-animation">
                    <div class="feature-img">
                      <img style="height: auto;" src="{{ asset('storage/'.$item->image) }}" alt="" class="animated" />
                      <div class="date-feature">
                        27 <br />
                        <small>may</small>
                      </div>
                    </div>
                    <div class="feature-info">
                      <span><i class="icon-user-1"></i> By Ali TUFAN</span>
                      <span><i class="icon-comment-5"></i> 5 Comments</span>
                      <h5>{{ $item->title }}</h5>
                      <p>
                       {!! $item->content !!}
                      </p>
                      <a href="{{ route('client.blogsingle',[$item->id]) }}"
                        >Read More <i class="icon-right-4"></i
                      ></a>
                    </div>
                  </div>
                @endforeach
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
                  <h2 class="text-primary">Lý Thông</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img style="width: 100px; height: 100px; border-radius: 100px;" src="{{ url('asset') }}/images/lythong.png" alt="" /></p>
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
                  <h2 class="text-primary">Nam Per</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img style="width: 100px; height: 100px; border-radius: 100px;" src="{{ url('asset') }}/images/sếp_tổng_Nam.jpg" alt="" /></p>
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
                  <h2 class="text-primary">Hoàng Kun</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img style="width: 100px; height: 100px; border-radius: 100px;" src="{{ url('asset') }}/images/anhmu.jpg" alt="" /></p>
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
                  <h2 class="text-primary">Bố Nam per</h2>
                  <p>1500+ Satisfied Clients</p>
                  <p><img style="width: 100px; height: 100px; border-radius: 100px;" src="{{ url('asset') }}/images/senna.jpg" alt="" /></p>
                  <h5 class="text-primary">Alice Williams</h5>
                  <p>Kitchen Manager</p>
                  <p>
                    Success isn’t really that difficult. There is a significant
                    portion of the <br />
                    population here in North America, that actually want and
                    need <br />success really no magic to be hard.
                  </p>
                </div>
                <!-- <div class="item">
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
                </div> -->
              </div>
            </div>
          </section>

          <!-- End What Client Say -->

          <!-- Start Feature List -->

          <section class="default-section">
            <div class="container">
              <div class="title text-center">
                <h2 class="text-primary">Một số tính năng của chúng tôi</h2>
                <h6 class="text-turkish">
                 Vai trò của một dụng cụ nấu ăn tốt trong việc chuẩn bị một bữa ăn thịnh soạn không thể được
                nhấn mạnh hơn so với bánh mì trắng.
                </h6>
              </div>
              <div class="feature-list">
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon1.png" alt="" />
                    <h5 class="text-coffee">MÁY PHA CÀ PHÊ</h5>
                    <p>
                    "Thiết kế hiện đại, dễ sử dụng – phù hợp cho cả gia đình và quán nhỏ."
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon2.png" alt="" />
                    <h5 class="text-coffee">MÁY XAY CÀ PHÊ</h5>
                    <p>
                   "Xay nhanh – giữ trọn hương vị nguyên bản của hạt cà phê."
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon3.png" alt="" />
                    <h5 class="text-coffee">TÁCH CÀ PHÊ</h5>
                    <p>
                     "Tách sứ cao cấp, giữ nhiệt tốt – giúp bạn thưởng thức trọn vẹn từng ngụm cà phê."
                    </p>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <img src="{{ url('asset') }}/images/icon/icon4.png" alt="" />
                    <h5 class="text-coffee">MÁY PHA CÀ PHÊ ESPRESSO</h5>
                    <p>
                  "Mang lại trải nghiệm cà phê chuyên nghiệp ngay tại nhà."
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
                 Bạn có thích kỳ nghỉ của mình tại Despina không? Hãy chia sẻ những khoảnh khắc của bạn với chúng tôi. Theo dõi chúng tôi trên Instagram và sử dụng
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
