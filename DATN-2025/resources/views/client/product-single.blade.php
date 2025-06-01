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
                                <span class="minus-text"><i class="icon-minus"></i></span>
                                <input type="text" name="qty" id="quantity" placeholder="1" pattern="[0-9]" value="1" readonly>
                                <span class="plus-text"><i class="icon-plus"></i></span>
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
                                            <h5>{{ $item->user->name }}</h5>
                                            <span class="comment-date">{{ $item->created_at }}</span>
                                            <p>{{ $item->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                @if (Auth::check())
                                    <div class="title text-center">
                                        <h3 class="text-coffee">Leave a Reply</h3>
                                    </div>
                                    <form class="form" method="post" action="{{ route('comment.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea placeholder="Comment" name="comment" required></textarea>
                                            </div>
                                            <input type="hidden" name="rating" id="rating" value="5">
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
$(document).ready(function() {
    // Hàm cập nhật giá
    function updatePrice() {
        console.log('Updating price...');

        // Lấy giá gốc của sản phẩm
        const productBasePrice = parseInt($('#display-price').data('base')) || 0;
        console.log('Base price:', productBasePrice);

        // Lấy giá của size đã chọn
        const selectedSize = $('input[name="size_id"]:checked');
        const sizePrice = selectedSize.length ? parseInt(selectedSize.data('price')) || 0 : 0;
        console.log('Size price:', sizePrice);

        // Tính tổng giá topping
        let toppingPrice = 0;
        $('input[name="topping_ids[]"]:checked').each(function() {
            toppingPrice += parseInt($(this).data('price')) || 0;
        });
        console.log('Topping price:', toppingPrice);

        // Lấy số lượng
        const quantity = parseInt($('#quantity').val()) || 1;
        console.log('Quantity:', quantity);

        // Tính tổng giá
        const totalPrice = (productBasePrice + sizePrice + toppingPrice) * quantity;
        console.log('Total price:', totalPrice);

        // Hiển thị giá
        $('#display-price').text(totalPrice.toLocaleString('vi-VN') + ' VND');
    }

    // Hàm thay đổi số lượng
    function changeQty(delta) {
        const $input = $('#quantity');
        let qty = parseInt($input.val()) || 1;
        qty = Math.max(1, qty + delta);
        $input.val(qty);
        updatePrice();
    }

    // Gắn sự kiện cho size
    $('input[name="size_id"]').on('click', function() {
        updatePrice();
    });

    // Gắn sự kiện cho topping
    $('input[name="topping_ids[]"]').on('click', function() {
        updatePrice();
    });

    // Gắn sự kiện cho nút tăng/giảm số lượng
    $('.minus-text').on('click', function() {
        changeQty(-1);
    });

    $('.plus-text').on('click', function() {
        changeQty(1);
    });

    // Cập nhật giá ban đầu khi trang load xong
    updatePrice();
});

const stars = document.querySelectorAll('.star-review-customer a');
const ratingInput = document.getElementById('rating');

stars.forEach((star, index) => {
    star.addEventListener('click', function(e) {
        e.preventDefault();
        const rating = index + 1;
        ratingInput.value = rating;

        // Reset màu hết trước
        stars.forEach(s => s.classList.remove('active'));
        // Active mấy cái sao đã chọn
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('active');
        }
    });
});

</script>
@endsection
