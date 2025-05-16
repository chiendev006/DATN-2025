@extends('layout')

@section('main')
<section class="home-slider owl-carousel">

      <div class="slider-item" style="background-image: url('{{ asset('asset/images/bg_3.jpg') }}');" data-stellar-background-ratio="0.5">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center">

            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<h1 class="mb-3 mt-5 bread">Our Menu</h1>
	            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Menu</span></p>
            </div>

          </div>
        </div>
      </div>
    </section>

<div class="container mt-5">
    <form class="search-bar mb-4" action="{{ route('search') }}" method="GET">
        <input class="search-input" required name="search" type="search" autocomplete="off" placeholder="Search products...">
        <button type="submit" class="search-btn">
            <span>Search</span>
        </button>
    </form>

    <h4 class="mb-4">Kết quả tìm kiếm cho: <strong>{{ $keyword }}</strong></h4>

    <div class="row">
        @forelse($sanpham as $sp)
            <div class="col-md-4 text-center mb-4">
                <div class="menu-wrap">
                    <a href="#" class="menu-img img mb-4">
                        <img src="{{ url('/storage/uploads/' . $sp->image) }}" width="250px" alt="">
                    </a>
                    <div class="text">
                        <h3><a href="#">{{ $sp->name }}</a></h3>
                        <p>{{ $sp->mota }}</p>
                        <p class="price"><span>{{ number_format($sp->price) }} VND</span></p>
                        <p><a href="#" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
                    </div>
                </div>
            </div>
        @empty
            <p>Không tìm thấy sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection
