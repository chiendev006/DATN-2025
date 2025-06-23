@extends('layout2')
@section('main')
 <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
                                <li class="active"><a href="#">About</a></li>
                            </ul>
                            <label class="now">ABOUT</label>
                        </div>
                    </div>
                </section>

                <section class="default-section about">
                    <div class="container">
                        <div class="title text-center">
                            <h2 class="text-coffee">Chào mừng đến với Despina</h2>
                            <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <p>Xin chào. Đây là La Boom. Thanh lịch &amp; mẫu nhà hàng tinh tế. Royal Plate cung cấp nhiều bố cục trang chủ khác nhau với thiết kế thông minh và độc đáo, giới thiệu các yếu tố được thiết kế đẹp mắt mà mọi trang web nhà hàng nên có. Hoạt ảnh mượt mà, tải nhanh và trải nghiệm người dùng hấp dẫn chỉ là một số tính năng mà mẫu này cung cấp. Vì vậy, hãy thử và đắm mình vào thế giới trang web nhà hàng La Boom.</p>
                                <div class="nto"><span>Ali tufan</span> <br> <small>Ali Tufan / Tổng giám đốc điều hành</small></div>
                                <p><a href="#" class="submit-btn">TÌM HIỂU THÊM</a></p>
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
