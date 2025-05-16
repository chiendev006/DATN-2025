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
        <img src="{{ asset('storage/uploads/' . $sanpham->image) }}" width="100%" class="img-fluid" alt="{{ $sanpham->name }}">
		@if($sanpham->product_images->count() > 0)
		<div class="variant-images mt-4">
			<h5>Ảnh biến thể:</h5>
			<div class="d-flex flex-wrap">
			@foreach($sanpham->product_images as $variant)
				<div class="mr-3 mb-3" style="width: 100px; cursor: pointer;">
				<img src="{{ asset('storage/' . $variant->image_url) }}" alt="Ảnh biến thể" class="img-fluid variant-image" 
					data-size="{{ $variant->size->name ?? '' }}" data-topping="{{ $variant->topping->name ?? '' }}">
				
				</div>
			@endforeach
			</div>
		</div>
		@endif
      </div>
      <div class="col-lg-6 product-details pl-md-5 ftco-animate">
        <h3>{{ $sanpham->name }}</h3>
        <p class="price"><span>{{ number_format($sanpham->price) }} VND</span></p>
        <p>{{ $sanpham->mota }}</p>

        <!-- FORM THÊM GIỎ HÀNG -->
          <div class="form-group">
            <label for="size"><strong>Chọn size:</strong></label>
            <select class="form-control" name="size_id" required>
              @php
                $sizes = $sanpham->attributes->pluck('size')->unique('id')->filter();
              @endphp
              @foreach($sizes as $size)
                <option value="{{ $size->id }}">{{ $size->name }} (+{{ number_format($size->price) }} VND)</option>
              @endforeach
            </select>
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
          <button type="submit" class="btn btn-primary py-3 px-5"> Thêm vào giỏ hàng</button>
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
    document.getElementById('quantity_input').value = qty;
  }
</script>

@endsection
