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
                                    $sizes = collect($sanpham->attributes->all());
                                    $minSizeId = $sizes->sortBy('price')->first()['id'] ?? null;
                                @endphp
                                @foreach($sizes as $size)
                                    <label class="mr-3">
                                        <input type="radio" name="size_id" value="{{ $size['id'] }}" class="size-option" data-price="{{ $size['price'] }}" required {{ $size['id'] == $minSizeId ? 'checked' : '' }}>
                                        {{ $size['size'] }}
                                    </label><br>
                                @endforeach
                            </div>
                            @if($sanpham->danhmuc->role == 1)
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
                            @else
                            <div class="form-group">
                                <p class="text-muted">Sản phẩm này không sử dụng topping</p>
                            </div>
                            @endif
                            <div class="price-textbox">
                                <span class="minus-text"><i class="icon-minus"></i></span>
                                <input type="text" name="qty" id="quantity" placeholder="1" pattern="[0-9]" value="1" readonly>
                                <span class="plus-text"><i class="icon-plus"></i></span>
                            </div>
                            <button type="submit" class="filter-btn btn-large"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Add to Cart</button>
                        </form>
                        <!-- <div class="share-tag">
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
                        </div> -->
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
                            <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews ( {{ $product->comments->count() }} )</a>
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
                                <h3 class="text-coffee">{{ $product->comments->count() }} Comments</h3>
                            </div>
                            <div class="comment-blog">
                                @foreach($comment as $item)
                                    <div class="comment-inner-list">
                                        <div class="comment-img">
                                            <img src="images/comment-img1.png" alt="">
                                        </div>
                                        <div class="comment-info">
                                            <h5> <img style="width: 50px; height: 50px; border-radius: 50px;" src="{{ asset('storage/'.$item->user->image) }}" alt=""> {{ $item->user->name }}</h5>
                                            <span class="comment-date">{{ $item->created_at->format('d/m/Y') }}</span>
                                            <input disabled type="text" name="product_id" value="{{ $item->comment }}">
                                        </div>
                                    </div>
                                @endforeach
                                @if (Auth::check())
                                    <div class="title text-center">
                                        <h3 class="text-coffee">Nhập comment</h3>
                                    </div>
                                    <form class="form" method="post" action="{{ route('comment.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea placeholder="Comment" name="comment" required></textarea>
                                            </div>
                                            <input type="hidden" name="rating" id="rating" value="5">
                                            <!-- <div class="col-md-12 col-sm-12 col-xs-12">
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
                                            </div> -->
                                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input name="submit" value="POST COMMENT" class="submit-btn" type="submit">
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <h6>Muốn phọt ra bình luận? <a href="{{ route('login') }}">Đăng nhập cái đã!</a></h6>
                                @endif
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

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const displayPrice = document.getElementById('display-price');
        const quantityInput = document.getElementById('quantity');
        const minusBtn = document.querySelector('.minus-text');
        const plusBtn = document.querySelector('.plus-text');
        const formGroups = document.querySelectorAll('.form-group');

        function getSelectedSizePrice() {
            const checkedSize = document.querySelector('input.size-option:checked');
            return checkedSize ? parseInt(checkedSize.dataset.price) : 0;
        }
        function getSelectedToppingPrice() {
            let total = 0;
            document.querySelectorAll('input.topping-option:checked').forEach(function (el) {
                total += parseInt(el.dataset.price);
            });
            return total;
        }
        function getQuantity() {
            let qty = parseInt(quantityInput.value);
            return isNaN(qty) || qty < 1 ? 1 : qty;
        }
        function updatePrice() {
            const sizePrice = getSelectedSizePrice();
            const toppingPrice = getSelectedToppingPrice();
            const qty = getQuantity();
            const total = (sizePrice + toppingPrice) * qty;
            displayPrice.textContent = total.toLocaleString('vi-VN') + ' VND';
            console.log('Update price:', {sizePrice, toppingPrice, qty, total});
        }
        function checkMinSizeAndUpdate() {
            const sizeOptions = document.querySelectorAll('input.size-option');
            let minPrice = Infinity;
            let minRadio = null;
            sizeOptions.forEach(function (el) {
                const price = parseInt(el.dataset.price);
                if (price < minPrice) {
                    minPrice = price;
                    minRadio = el;
                }
            });
            if (minRadio && !minRadio.checked) {
                minRadio.checked = true;
            }
            updatePrice();
        }

        // Bắt sự kiện click trên từng .form-group (size và topping)
        formGroups.forEach(function(group) {
            group.addEventListener('click', function(e) {
                // Nếu click vào label hoặc input bên trong
                if (
                    e.target.matches('label') ||
                    e.target.matches('input.size-option') ||
                    e.target.matches('input.topping-option')
                ) {
                    // Delay 1 chút để input nhận checked
                    setTimeout(updatePrice, 10);
                }
            });
        });

        // Sự kiện tăng/giảm số lượng
        plusBtn.addEventListener('click', function () {
            let qty = getQuantity();
            quantityInput.value = qty + 1;
            updatePrice();
        });
        minusBtn.addEventListener('click', function () {
            let qty = getQuantity();
            if (qty > 1) {
                quantityInput.value = qty - 1;
                updatePrice();
            }
        });
        quantityInput.addEventListener('input', function () {
            let val = quantityInput.value.replace(/[^0-9]/g, '');
            quantityInput.value = val === '' ? 1 : val;
            updatePrice();
        });
        checkMinSizeAndUpdate();

        document.addEventListener('click', function(e) {
            if (
                e.target.matches('input.size-option') ||
                e.target.matches('input.topping-option') ||
                e.target.closest('label')
            ) {
                setTimeout(updatePrice, 10);
            }
        });
    });
  updatePrice();
//#
$(document).ready(function(){
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: false,
        focusOnSelect: true
    });
});


</script>
@endsection
