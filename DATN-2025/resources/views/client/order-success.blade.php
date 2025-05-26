@extends('layout')

@section('main')
<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('asset/images/bg_2.jpg') }});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Order Success</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="/">Home</a></span> <span>Order Success</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center ftco-animate">
                <h2 class="mb-4">Cảm ơn bạn đã đặt hàng!</h2>
                <p>Đơn hàng của bạn đã được đặt thành công. Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.</p>
                <p><a href="{{ route('client.home') }}" class="btn btn-primary py-3 px-4">Quay lại trang chủ</a></p>
            </div>
        </div>
    </div>
</section>
@endsection