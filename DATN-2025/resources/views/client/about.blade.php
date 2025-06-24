@extends('layout2')
@section('main')
 <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li class="active"><a href="/about">About</a></li>
                            </ul>
                            <label class="now">ABOUT</label>
                        </div>
                    </div>
                </section>

                <section class="default-section about">
                    <div class="container">
                        <div class="title text-center">
                            <h2 class="text-coffee">Chào mừng đến với Mira Café</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                         <h4>Mira Café: Trải Nghiệm Hương Vị Đích Thực</h4>

    <p>Mira Café là <span class="highlight">điểm đến lý tưởng</span> cho những ai tìm kiếm sự kết hợp hoàn hảo giữa <span class="highlight">hương vị cà phê tuyệt hảo</span> và <span class="highlight">không gian thư thái</span>.</p>

    <p>Chúng tôi tự hào mang đến những ly cà phê được pha chế từ <span class="highlight">hạt tuyển chọn</span>, cùng đa dạng thức uống và bánh ngọt, bánh mặn hấp dẫn.</p>

    <p>Với <span class="highlight">không gian ấm cúng, hiện đại</span> và đội ngũ <span class="highlight">nhân viên tận tâm</span>, Mira Café cam kết mang lại những trải nghiệm đáng nhớ, giúp bạn tìm thấy sự bình yên giữa nhịp sống bận rộn.</p>
                                <div class="nto"><span>Nguyễn Xuân Huy</span> <br> <small>Nguyễn Xuân Huy / Giảng viên hướng dẫn</small></div>
                                <p><a href="/contact" class="submit-btn">TÌM HIỂU THÊM</a></p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <img src="{{ url('asset') }}/images/about-img.png" alt="">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="default-section partner-main text-center pad-top-remove">
                    <div class="container">
                        <div class="owl-carousel owl-theme" data-items="5" data-tablet="3" data-mobile="2" data-nav="true" data-dots="false" data-autoplay="true" data-speed="1500" data-autotime="1800">
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner1.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner2.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner3.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner4.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner5.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner1.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner2.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner3.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner4.png" alt="">
                            </div>
                            <div class="item dp-animation">
                                <img src="{{ url('asset') }}/images/partner5.png" alt="">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
@endsection
