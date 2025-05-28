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

                <!-- Start About List -->   

                <section class="default-section about">
                    <div class="container">
                        <div class="title text-center">
                            <h2 class="text-coffee">Welcome To The Despina</h2>
                            <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <p>Welcome. This is La Boom. Elegant &amp; sophisticated restaurant template. Royal plate offers different home page layouts with smart and unique design, showcasing beautifully designed elements every restaurant website should have. Smooth animations, fast loading and engaging user experience are just some of , the features this template offers. So, give it a try and dive into a world of La Boom restaurant websites.</p>
                                <div class="nto"><span>Ali tufan</span> <br> <small>Ali Tufan / CEO</small></div>
                                <p><a href="#" class="submit-btn">LEARN MORE</a></p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <img src="{{ url('asset') }}/images/about-img.png" alt="">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End About List -->

                <!-- Start Partner Blog -->
                
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

                <!-- End Partner Blog -->

            </div>
        </main>  

@endsection
