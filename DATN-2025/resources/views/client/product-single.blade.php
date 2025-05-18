@extends('layout')
@section('main')

<section class="home-slider owl-carousel">
  <div class="slider-item" style="background-image: url('{{ asset('asset/images/bg_3.jpg') }}');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">
        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">Chi tiết sản phẩm</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="/">Trang chủ</a></span> <span>Sản phẩm</span></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-5 ftco-animate">
        @php
          $allImages = collect([$sanpham->image])->merge($sanpham->product_images->pluck('image_url')->toArray());
        @endphp
        <div id="productCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            @foreach ($allImages as $key => $image)
              <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . (str_contains($image, 'uploads/') ? $image : 'uploads/' . $image)) }}" class="d-block w-100" alt="Ảnh sản phẩm">
              </div>
            @endforeach
          </div>
          <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>

      <div class="col-lg-6 product-details pl-md-5 ftco-animate">
        <h3>{{ $sanpham->name }}</h3>
        <p class="price"><span>{{ number_format($sanpham->price) }} VND</span></p>
        <p>{{ $sanpham->mota }}</p>

        <!-- FORM THÊM GIỎ HÀNG -->
        <form action="#" method="POST">
          @csrf
          <div class="form-group">
            <label for="size"><strong>Chọn size:</strong></label><br>
            @php
              $sizes = $sanpham->attributes->pluck('size')->unique('id')->filter();
            @endphp
            @foreach($sizes as $size)
              <label class="mr-3">
                <input type="radio" name="size_id" value="{{ $size->id }}" required>
                {{ $size->name }} (+{{ number_format($size->price) }} VND)
              </label><br>
            @endforeach
          </div>

          <div class="form-group">
            <label><strong>Chọn topping:</strong></label><br>
            @php
              $toppings = $sanpham->attributes->pluck('topping')->unique('id')->filter();
            @endphp
            @foreach($toppings as $topping)
              <label class="mr-3">
                <input type="checkbox" name="topping_ids[]" value="{{ $topping->id }}">
                {{ $topping->name }} (+{{ number_format($topping->price) }} VND)
              </label>
            @endforeach
          </div>

          <div class="form-group">
            <label><strong>Số lượng:</strong></label>
            <div class="input-group">
              <span class="input-group-btn">
                <button type="button" class="btn btn-outline-secondary" onclick="changeQty(-1)">-</button>
              </span>
              <input type="text" name="qty" id="quantity" class="form-control text-center" value="1" readonly>
              <span class="input-group-btn">
                <button type="button" class="btn btn-outline-secondary" onclick="changeQty(1)">+</button>
              </span>
            </div>
          </div>

          <button type="submit"> Thêm vào giỏ hàng</button>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
  function changeQty(delta) {
    const input = document.getElementById('quantity');
    let qty = parseInt(input.value);
    if (isNaN(qty)) qty = 1;
    qty = Math.max(1, qty + delta);
    input.value = qty;
  }
</script>

@endsection
