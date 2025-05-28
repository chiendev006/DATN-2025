@extends('layout2')
@section('main')
<main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
                                <li class="active"><a href="#">Shop</a></li>
                            </ul>
                            <label class="now">SHOP</label>
                        </div>
                    </div>
                </section>

                <!-- Start Blog List -->   
                <section class="default-section shop-page">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="blog-left-section">
                                   <div class="blog-left-search blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="search-input-wrapper">
                                            <input type="text" id="search" name="search" placeholder="Search">
                                            <i class="fa fa-search" id="btn-search"></i>
                                        </div>
                                    </div>
                                    <div class="blog-left-categories blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Categories</h5>
                                        <ul id="category-list">
                                            @foreach ($danhmucs as $index => $danhmuc)
                                                <li class="{{ $index == 0 ? 'current' : '' }}">
                                                    <a href="#" data-id="{{ $danhmuc->id }}">{{ strtoupper($danhmuc->name) }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                       </div>
                                   <div class="blog-left-filter blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Filter</h5>
                                        <p>Price: <span id="price-range-label">50.000₫ — 10.000.000₫</span></p>
                                        <input id="price-range" type="text"
                                            data-slider-min="50000"
                                            data-slider-max="10000000"
                                            data-slider-step="50000"
                                            data-slider-value="[50000,2000000]" />
                                        <a href="#" id="btn-filter" class="filter-btn">FILTER</a>
                                    </div>
                                    <div class="blog-left-deal blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Best Deals</h5>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img20.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img21.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                        <div class="best-deal-blog">
                                            <div class="best-deal-left">
                                                <img src="{{ url('asset') }}/images/img22.png" alt="">
                                            </div>
                                            <div class="best-deal-right">
                                                <p>Lasal Cheese</p>
                                                <p><strong>$ 15</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="popular-tag blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <h5>Popular Tags</h5>
                                        <a href="#">Audio</a> <a href="#">Service</a> <a href="#">Online Order</a> <a href="#">Contact</a> <a href="#">Cupcake</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-12">
                                <div class="blog-right-section">
                                    <div class="shop-search wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <h6>Showing all 12 results</h6>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="select-dropbox">
                                                    <option>Sort by newness</option>
                                                    <option>Sort</option>
                                                    <option>Sort newness</option>
                                                    <option>Sort by newness</option>
                                                    <option>newness</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                   <div class="row" id="product-list">
                                        @foreach ($firstProducts as $product)
                                            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                                <div class="shop-main-list">
                                                    <div class="shop-product">
                                                        <a href="">
                                                        <img class="" src="{{ asset('storage/' . ltrim($product->image, '/')) }}"  alt="{{ $product->name }}" style="border-radius: 20px;">
                                                        </a>
                                                         <div class="cart-overlay-wrap">
                                                        <div class="cart-overlay">
                                                            <a href="{{ route('client.product.detail', $product->id) }}" class="shop-cart-btn">ADD TO CART</a>
                                                        </div> 
                                                    </div>
                                                    </div>
                                                      <a href="shop_single.html"><h5>{{ $product->name }}</h5></a>
                                                    <h5><strong>{{ number_format($product->min_price) }} VND</strong></h5>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="gallery-pagination">
                                        <div class="gallery-pagination-inner">
                                            <ul>
                                                <li><a href="#" class="pagination-prev"><i class="icon-left-4"></i> <span>PREV page</span></a></li>
                                                <li class="active"><a href="#"><span>1</span></a></li>
                                                <li><a href="#"><span>2</span></a></li>
                                                <li><a href="#"><span>3</span></a></li>
                                                <li><a href="#" class="pagination-next"><span>next page</span> <i class="icon-right-4"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End Blog List -->

            </div>
        </main>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('#category-list a').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                let categoryId = this.dataset.id;

                fetch('/shop/category/' + categoryId)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('product-list').innerHTML = '';
                        data.products.forEach(product => {
                            document.getElementById('product-list').innerHTML += `
                                <div class="col-md-4">
                                    <div class="shop-main-list">
                                        <div class="shop-product">
                                            <img src="/storage/${product.image}" alt="${product.name}">
                                        </div>
                                        <h5>${product.name}</h5>
                                        <h5><strong>${Number(product.min_price).toLocaleString()} VND</strong></h5>
                                    </div>
                                </div>
                            `;
                        });
                    });
            });
        });
    });
</script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#btn-search').on('click', function () {
        let keyword = $('#search').val();

        $.ajax({
            url: "{{ route('ajax.search') }}",
            type: "GET",
            data: { search: keyword },
            success: function (response) {
                let html = '';

                if(response.sanpham.length > 0) {
                    response.sanpham.forEach(function(item) {
                        html += `
                            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown">
                                <div class="shop-main-list">
                                    <div class="shop-product">
                                        <a href="/product/${item.id}">
                                            <img src="${item.image}" alt="${item.name}" style="border-radius: 20px;">
                                        </a>
                                        <div class="cart-overlay-wrap">
                                            <div class="cart-overlay">
                                                <a href="/cart/add/${item.id}" class="shop-cart-btn">ADD TO CART</a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/product/${item.id}"><h5>${item.name}</h5></a>
                                    <h5><strong>${Number(item.min_price).toLocaleString('vi-VN')} VND</strong></h5>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
                }

                $('#product-list').html(html);
            }
        });
    });
    // Name sp 
    document.getElementById('btn-search').addEventListener('click', function () {
    let keyword = document.getElementById('search').value;

    $.ajax({
        url: "{{ route('ajax.search') }}",
        type: "GET",
        data: { search: keyword },
        success: function (response) {
            let html = '';

            if (response.sanpham.length > 0) {
                response.sanpham.forEach(function (item) {
                    html += `
                        <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown">
                            <div class="shop-main-list">
                                <div class="shop-product">
                                    <a href="/product/${item.id}">
                                        <img src="${item.image}" alt="${item.name}" style="border-radius: 20px;">
                                    </a>
                                    <div class="cart-overlay-wrap">
                                        <div class="cart-overlay">
                                            <a href="/cart/add/${item.id}" class="shop-cart-btn">ADD TO CART</a>
                                        </div>
                                    </div>
                                </div>
                                <a href="/product/${item.id}"><h5>${item.name}</h5></a>
                                <h5><strong>${Number(item.min_price).toLocaleString('vi-VN')} VND</strong></h5>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
            }

            $('#product-list').html(html);
        }
    });
});
// price 
   $(document).ready(function () {
    // Khởi tạo slider
    $("#price-range").slider({
        formatter: function(value) {
            if (Array.isArray(value)) {
                return formatVND(value[0]) + " — " + formatVND(value[1]);
            }
            return formatVND(value);
        }
    });

    // Hàm format số thành VND
    function formatVND(number) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number).replace('₫', 'VND');
    }

    // Cập nhật label khi slider thay đổi
    $("#price-range").on('slide', function(event) {
        let val = event.value;
        if (Array.isArray(val)) {
            $("#price-range-label").text(formatVND(val[0]) + " — " + formatVND(val[1]));
        } else {
            $("#price-range-label").text(formatVND(val));
        }
    });

    // Hiển thị giá mặc định khi load trang
    $("#price-range").trigger('slide', {value: $("#price-range").slider('getValue')});

    // Xử lý nút lọc giá
    $('#btn-filter').on('click', function (e) {
        e.preventDefault();
        
        let range = $("#price-range").slider('getValue');
        let minPrice = range[0];
        let maxPrice = range[1];

        // Thêm loading state
        $('#product-list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');

        $.ajax({
            url: "{{ route('ajax.filter.price') }}",
            type: "GET",
            data: { min: minPrice, max: maxPrice },
            success: function (response) {
                let html = '';

                if(response.sanpham && response.sanpham.length > 0) {
                    response.sanpham.forEach(function(item) {
                        html += `
                            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown">
                                <div class="shop-main-list">
                                    <div class="shop-product">
                                        <a href="/product/${item.id}">
                                            <img src="${item.image}" alt="${item.name}" style="border-radius: 20px;">
                                        </a>
                                        <div class="cart-overlay-wrap">
                                            <div class="cart-overlay">
                                                <a href="/cart/add/${item.id}" class="shop-cart-btn">ADD TO CART</a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/product/${item.id}"><h5>${item.name}</h5></a>
                                    <h5><strong>${Number(item.min_price).toLocaleString('vi-VN')} VND</strong></h5>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="col-12 text-center"><p>Không tìm thấy sản phẩm nào trong khoảng giá này.</p></div>';
                }

                $('#product-list').html(html);
            },
            error: function() {
                $('#product-list').html('<div class="col-12 text-center"><p>Đã có lỗi xảy ra. Vui lòng thử lại.</p></div>');
            }
        });
    });
});

</script>


@endsection
