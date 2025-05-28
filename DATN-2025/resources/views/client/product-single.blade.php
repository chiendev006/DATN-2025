@extends('layout2')
@section('main')

<main>
    <div class="main-part">

        <section class="breadcrumb-nav">
            <div class="container">
                <div class="breadcrumb-nav-inner">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/shop">Shop</a></li>
                        <li class="active"><a href="#">Shop Single</a></li>
                    </ul>
                    <label class="now">SHOP SINGLE</label>
                </div>
            </div>
        </section>

        <!-- Start Shop Single -->   

        <section class="default-section shop-single pad-bottom-remove">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="slider slider-for slick-shop">
                            @php
                                $allImages = collect([$sanpham->image])->merge($sanpham->product_images->pluck('image_url')->toArray());
                            @endphp
                            @foreach ($allImages as $image)
                                <div>
                                    <img style= "width:555px;" src="{{ asset('storage/' . (str_contains($image, 'uploads/') ? $image : 'uploads/' . $image)) }}" alt="Ảnh sản phẩm">
                                </div>
                            @endforeach
                        </div>
                        <div class="slider slider-nav slick-shop-thumb">
                            @foreach ($allImages as $image)
                                <div>
                                    <img src="{{ asset('storage/' . (str_contains($image, 'uploads/') ? $image : 'uploads/' . $image)) }}" alt="Ảnh sản phẩm">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <h4 class="text-coffee">{{ $sanpham->name }}</h4>
                        <div class="star-review-collect">
                            <div class="star-rating">
                                <span class="star-rating-customer" style="width: 50%"></span>
                            </div>
                            <a href="#" class="review-text">03 customer review</a>
                        </div>
                        <p>{!! strip_tags($sanpham->mota, '<p><br><strong><em>') !!}</p>
                        <h3 class="text-coffee">
                            <span id="display-price" data-base="{{ $sanpham->price }}">{{ number_format($sanpham->price) }} VND</span>
                        </h3>
                        <form action="{{ route('cart.add', $sanpham->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $cart->id ?? '' }}">
                            <input type="hidden" name="product_id" value="{{ $sanpham->id }}">
                            <div class="form-group">
                                <label><strong>Chọn size:</strong></label><br>
                                @php
                                    $sizes = $sanpham->attributes;
                                @endphp
                                @foreach($sizes as $size)
                                    <label class="mr-3">
                                        <input type="radio" name="size_id" value="{{ $size->id }}" class="size-option" data-price="{{ $size->price }}" required>
                                        {{ $size->size }}
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
                                        {{ $top->topping }} ({{ number_format($top->price) }} VND)
                                    </label><br>
                                @endforeach
                            </div>
                            <div class="price-textbox">
                                <span class="minus-text"><i class="icon-minus" onclick="changeQty(-1)"></i></span>
                                <input type="text" name="qty" id="quantity" placeholder="1" pattern="[0-9]" value="1" readonly>
                                <span class="plus-text"><i class="icon-plus" onclick="changeQty(1)"></i></span>
                            </div>
                            <button type="submit" class="filter-btn btn-large"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Add to Cart</button>
                        </form>
                        <div class="share-tag">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="social-wrap">
                                        <h5>SHARE</h5>
                                        <ul class="social">
                                            <li class="social-facebook"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                            <li class="social-tweeter"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                            <li class="social-instagram"><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                            <li class="social-dribble"><a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
                                            <li class="social-google"><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="tag-wrap">
                                        <h5>TAGS</h5>
                                        <a href="#" class="tag-btn">Audio</a>
                                        <a href="#" class="tag-btn">Service</a>
                                        <a href="#" class="tag-btn">Cupcake</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- End Shop Single -->

        <!-- Start Tab Part -->

        <section class="default-section comment-review-tab bg-grey v-pad-remove wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="container">
                <div class="tab-part">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description</a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews ( 5 )</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane" id="description">
                            <div class="title text-left">
                                <h3 class="text-coffee">Description About Product</h3>
                            </div>
                            <p>{!! strip_tags($sanpham->mota, '<p><br><strong><em>') !!}</p>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="reviews">
                            <div class="title text-center">
                                <h3 class="text-coffee">2 Comment</h3>
                            </div>
                            <div class="comment-blog">
                                <div class="comment-inner-list">
                                    <div class="comment-img">
                                        <img src="images/comment-img1.png" alt="">
                                    </div>
                                    <div class="comment-info">
                                        <h5>Anna Taylor</h5>
                                        <span class="comment-date">AUGUST 9, 2016 10:57 AM</span>
                                        <p>Aqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                    </div>
                                </div>
                                <div class="comment-inner-list">
                                    <div class="comment-img">
                                        <img src="images/comment-img1.png" alt="">
                                    </div>
                                    <div class="comment-info">
                                        <h5>Anna Taylor</h5>
                                        <span class="comment-date">AUGUST 9, 2016 10:57 AM</span>
                                        <p>Aqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                    </div>
                                </div>
                                <div class="title text-center">
                                    <h3 class="text-coffee">Leave a Reply</h3>
                                </div>
                                <form class="form" method="post" name="form">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <textarea placeholder="Comment"></textarea>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input name="txt" placeholder="Name" type="text">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input name="email" placeholder="Email" type="email">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="star-review">
                                                <p>
                                                    <span>Your Rating</span>
                                                    <span class="star-review-customer">
                                                        <a href="#" class="star-1"></a>
                                                        <a href="#" class="star-2"></a>
                                                        <a href="#" class="star-3"></a>
                                                        <a href="#" class="star-4"></a>
                                                        <a href="#" class="star-5"></a>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                            <input name="submit" value="POST COMMENT" class="submit-btn" type="submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- End Tab Part -->

        <section class="default-section wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="container">
                <div class="title text-center">
                    <h3 class="text-coffee">Related Products</h3>
                </div>
                <div class="product-wrapper">
                    <div class="owl-carousel owl-theme" data-items="4" data-tablet="3" data-mobile="2" data-nav="false" data-dots="true" data-autoplay="true" data-speed="1800" data-autotime="5000">
                        <div class="item">
                            <div class="product-img">
                                <a href="shop_single.html">
                                    <img src="images/product1.png" alt="">
                                    <span class="icon-basket fontello"></span>
                                </a>
                            </div>
                            <h5>PLASTIC POUCH</h5>
                            <span>$79.00</span><del>$99.00</del>
                        </div>
                        <div class="item">
                            <div class="product-img">
                                <a href="shop_single.html">
                                    <img src="images/product2.png" alt="">
                                    <span class="icon-basket fontello"></span>
                                </a>
                            </div>
                            <h5>PAPER BAG</h5>
                            <span>$50.00</span><del>$70.00</del>
                        </div>
                        <div class="item">
                            <div class="product-img">
                                <a href="shop_single.html">
                                    <img src="images/product3.png" alt="">
                                    <span class="icon-basket fontello"></span>
                                </a>
                            </div>
                            <h5>PLASTIC POUCH</h5>
                            <span>$99.00</span><del>$120.00</del>
                        </div>
                        <div class="item">
                            <div class="product-img">
                                <a href="shop_single.html">
                                    <img src="images/product4.png" alt="">
                                    <span class="icon-basket fontello"></span>
                                </a>
                            </div>
                            <h5>COFFEE POT</h5>
                            <span>$40.00</span><del>$55.00</del>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

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

updatePrice();
</script>
@endsection