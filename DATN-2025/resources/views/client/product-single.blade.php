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
        <p class="price">
          <span id="display-price" data-base="{{ $sanpham->price }}">{{ number_format($sanpham->price) }} VND</span>
        </p>
        <p>{{ $sanpham->mota }}</p>

         <form action="{{ route('cart.add', $sanpham->id) }}" method="POST">
          @csrf
          <input type="hidden" name="cart_id" value="{{ $cart->id ?? '' }}">
          <input type="hidden" name="product_id" value="{{ $sanpham->id }}">
          <div class="form-group">
            <label for="size"><strong>Chọn size:</strong></label><br>
            @php
              $sizes = $sanpham->attributes;
            @endphp
            @foreach($sizes as $size)
              <label class="mr-3">
                <input type="radio" name="size_id" value="{{ $size->id }}" class="size-option" data-price="{{ $size->price }}" required>
                {{ $size->size }} (+{{ number_format($size->price) }} VND)
              </label><br>
            @endforeach
          </div>

          <div class="form-group">
            <label><strong>Chọn topping:</strong></label><br>
            @php
              $toppings = $sanpham->topping;
            @endphp
            @foreach($toppings as $top)
              <label class="mr-3">
                <input type="checkbox"
                       name="topping_ids[]"
                       value="{{ $top->id }}"
                       class="topping-option"
                       data-price="{{ $top->price }}">
                {{ $top->topping }} (+{{ number_format($top->price) }} VND)
              </label><br>
            @endforeach
          </div>

          <div class="form-group">
            <label><strong>Số lượng:</strong></label>
            <div class="input-group">
              <span class="input-group-btn">
                <button type="button" class="quantity-left-minus btn btn-outline-secondary" onclick="changeQty(-1)">-</button>
              </span>
              <input type="number" name="qty" id="quantity" class="form-control text-center" value="1" min="1" readonly>
              <span class="input-group-btn">
                <button type="button" class="quantity-right-plus btn btn-outline-secondary" onclick="changeQty(1)">+</button>
              </span>
            </div>
          </div>

          <button type="submit" class="btn btn-primary py-3 px-5">Thêm vào giỏ hàng</button>
        </form>
      </div>
    </div>
  </div>
</section>
<section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
          	<span class="subheading">Discover</span>
            <h2 class="mb-4">Related products</h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/menu-1.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Coffee Capuccino</a></h3>
    						<p>A small river named Duden flows by their place and supplies</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/menu-2.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Coffee Capuccino</a></h3>
    						<p>A small river named Duden flows by their place and supplies</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/menu-3.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Coffee Capuccino</a></h3>
    						<p>A small river named Duden flows by their place and supplies</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/menu-4.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Coffee Capuccino</a></h3>
    						<p>A small river named Duden flows by their place and supplies</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="#" class="btn btn-primary btn-outline-primary">Add to Cart</a></p>
    					</div>
    				</div>
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
    updatePrice();
  }

  function updatePrice() {
  // Lấy giá size được chọn
  let basePrice = 0;
  const sizeChecked = document.querySelector('.size-option:checked');
  if (sizeChecked) {
    basePrice = parseInt(sizeChecked.dataset.price);
  }
  const qty = parseInt(document.getElementById('quantity').value);
  let extra = 0;

  document.querySelectorAll('.topping-option:checked').forEach(el => {
    extra += parseInt(el.dataset.price);
  });

  const finalPrice = (basePrice + extra) * qty;
  document.getElementById('display-price').textContent = finalPrice.toLocaleString('vi-VN') + ' VND';
  document.getElementById('display-price').dataset.base = basePrice; // Cập nhật lại data-base nếu cần
}

document.querySelectorAll('.size-option, .topping-option').forEach(el => {
  el.addEventListener('change', updatePrice);
});

// Gọi updatePrice lần đầu để cập nhật giá khi load trang
updatePrice();
</script>
@endsection

