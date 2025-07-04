@extends('layout2')
@section('main')
<main>
    <div class="main-part">
        <section class="breadcrumb-nav">
            <div class="container">
                <div class="breadcrumb-nav-inner">
                    <ul>
                        <li><a href="">Home</a></li>
                        <li class="active"><a href="{{ route('shop.index') }}">Shop</a></li>
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
                                    <li class="{{ request('danhmuc_id') == null ? 'current' : '' }}">
                                        <a href="#" data-id="">Tất cả</a>
                                    </li>
                                    @foreach ($danhmucs as $index => $danhmuc)
                                        <li class="{{ request('danhmuc_id') == $danhmuc->id ? 'current' : '' }}">
                                            <a style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="#" data-id="{{ $danhmuc->id }}">{{ $danhmuc->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="blog-left-filter blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <h5>Filter</h5>
                                <p>Price: <span id="price-range-label">30.000₫ — 100.000₫</span></p>
                                <div class="slider-wrapper">
                                    <input id="price-range" type="text" class="span2" value=""
                                        data-slider-min="30000"
                                        data-slider-max="100000"
                                        data-slider-step="1000"
                                        data-slider-value="[30000,200000]"
                                        data-slider-tooltip="hide"
                                    />
                                </div>
                                <a href="#" id="btn-filter" class="filter-btn">FILTER</a>
                            </div>
                           <div class="blog-left-deal blog-common-wide wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <h5>Best Deals</h5>

                            @if(isset($bestDeals) && !$bestDeals->isEmpty())
                                @foreach($bestDeals as $deal)
                                    <div class="best-deal-blog">
                                        <div class="best-deal-left">
                                            <a href="{{ route('client.product.detail', $deal->id) }}">  <img style="border-radius: 10px;" src="{{ url('storage/uploads/' . $deal->image) }}" alt="{{ $deal->name }}"
                                            onerror="this.onerror=null;this.src='https://placehold.co/80x80/f8f8f8/ccc?text=Image';">
                                        </a>

                                        </div>
                                        <div class="best-deal-right">
                                            <a href="{{ route('client.product.detail', $deal->id) }}">{{ $deal->name }}</a>
                                            <p><strong>{{ number_format($deal->min_price) }} đ</strong></p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No deals available at the moment.</p>
                            @endif

                        </div>

                        </div>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="blog-right-section">
                            <div class="shop-search wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h6 style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Showing {{ $firstProducts->firstItem() }}–{{ $firstProducts->lastItem() }} of {{ $firstProducts->total() }} results</h6>
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
                                                <a href="{{ route('client.product.detail', $product->id) }}">
                                                    <img style="max-width: 263px; min-width: 263px; max-height: 275px; min-height: 275px; border-radius:23px"  src="{{ url('storage/uploads/'.$product->image) }}" alt="{{ $product->name }}" style="border-radius: 20px;">
                                                </a>
                                                <div class="cart-overlay-wrap">
                                                    <a href="{{ route('client.product.detail', $product->id) }}">
                                                    <div class="cart-overlay">
                                                        <a style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="{{ route('client.product.detail', $product->id) }}" class="shop-cart-btn">ADD TO CART</a>
                                                    </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="{{ route('client.product.detail', $product->id) }}"><h5>{{ $product->name }}</h5></a>
                                            <h5><strong>{{ number_format($product->min_price) }} VND</strong></h5>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="text-align: center;" class="gallery-pagination">
                                <div class="gallery-pagination-inner">
                                    <ul>
                                        <li><a href="#" class="pagination-prev {{ $firstProducts->onFirstPage() ? 'disabled' : '' }}" data-page="{{ $firstProducts->currentPage() - 1 }}"><i class="icon-left-4"></i> <span style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">PREV page</span></a></li>
                                        @for ($i = 1; $i <= $firstProducts->lastPage(); $i++)
                                            <li class="{{ $i == $firstProducts->currentPage() ? 'active' : '' }}">
                                                <a href="#" data-page="{{ $i }}"><span>{{ $i }}</span></a>
                                            </li>
                                        @endfor
                                        <li><a href="#" class="pagination-next {{ $firstProducts->hasMorePages() ? '' : 'disabled' }}" data-page="{{ $firstProducts->currentPage() + 1 }}"><span style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">next page</span> <i class="icon-right-4"></i></a></li>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Define the product detail route template
    const productDetailRoute = "{{ route('client.product.detail', ':id') }}";
    const productList = document.getElementById('product-list');
    const paginationContainer = document.querySelector('.gallery-pagination-inner ul');
    const resultText = document.querySelector('.shop-search h6');
    let currentDanhmucId = '';

    // --- Thay thế toàn bộ phần xử lý filter price và phân trang filter price ---
    let isFilteringByPrice = false;
    let filterPriceRange = [30000, 200000];

    // Đổi tên hàm fetchProducts gốc thành fetchProductsByCategory
    function fetchProductsByCategory(page, danhmucId) {
        const url = `{{ route('shop.index') }}?page=${page}${danhmucId && danhmucId !== '' ? `&danhmuc_id=${danhmucId}` : ''}`;
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update product list
            productList.innerHTML = data.products.map(product => `
                <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <div class="shop-main-list">
                        <div class="shop-product">
                            <a href="${productDetailRoute.replace(':id', product.id)}">
                                <img style="max-width: 263px; min-width: 263px; max-height: 275px; min-height: 275px; border-radius:23px" src="/storage/uploads/${product.image}" alt="${product.name}" style="border-radius: 20px;">
                            </a>
                            <div class="cart-overlay-wrap">
                                <div class="cart-overlay">
                                    <a href="${productDetailRoute.replace(':id', product.id)}" class="shop-cart-btn">ADD TO CART</a>
                                </div>
                            </div>
                        </div>
                        <a href="${productDetailRoute.replace(':id', product.id)}"><h5>${product.name}</h5></a>
                        <h5><strong>${new Intl.NumberFormat('vi-VN').format(product.min_price)} VND</strong></h5>
                    </div>
                </div>
            `).join('');

            // Update pagination
            let paginationHtml = `
                <li><a href="#" class="pagination-prev ${data.current_page === 1 ? 'disabled' : ''}" data-page="${data.current_page - 1}"><i class="icon-left-4"></i> <span>PREV page</span></a></li>
            `;
            for (let i = 1; i <= data.last_page; i++) {
                paginationHtml += `
                    <li class="${i === data.current_page ? 'active' : ''}">
                        <a href="#" data-page="${i}"><span>${i}</span></a>
                    </li>
                `;
            }
            paginationHtml += `
                <li><a href="#" class="pagination-next ${data.current_page === data.last_page ? 'disabled' : ''}" data-page="${data.current_page + 1}"><span>next page</span> <i class="icon-right-4"></i></a></li>
            `;
            paginationContainer.innerHTML = paginationHtml;

            // Update result count
            resultText.textContent = `Showing ${data.products.length > 0 ? (data.current_page - 1) * data.per_page + 1 : 0}–${(data.current_page - 1) * data.per_page + data.products.length} of ${data.total} results`;

            // Update current danh muc id
            currentDanhmucId = data.danhmuc_id || '';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải sản phẩm.');
        });
    }

    // Định nghĩa lại fetchProducts để phân biệt giữa lọc giá và lọc danh mục
    function fetchProducts(page, danhmucId) {
        if (isFilteringByPrice) {
            fetchProductsByPrice(page, filterPriceRange[0], filterPriceRange[1]);
        } else {
            fetchProductsByCategory(page, danhmucId || '');
        }
    }

    function fetchProductsByPrice(page, minPrice, maxPrice) {
        $.ajax({
            url: "{{ route('ajax.filter.price') }}",
            type: "GET",
            data: { min: minPrice, max: maxPrice, page: page },
            success: function (data) {
                // Update product list
                productList.innerHTML = data.products.map(product => `
                    <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="shop-main-list">
                            <div class="shop-product">
                                <a href="${productDetailRoute.replace(':id', product.id)}">
                                    <img src="/storage/uploads/${product.image}" alt="${product.name}" style="border-radius: 20px;">
                                </a>
                                <div class="cart-overlay-wrap">
                                    <div class="cart-overlay">
                                        <a href="${productDetailRoute.replace(':id', product.id)}" class="shop-cart-btn">ADD TO CART</a>
                                    </div>
                                </div>
                            </div>
                            <a href="${productDetailRoute.replace(':id', product.id)}"><h5>${product.name}</h5></a>
                            <h5><strong>${new Intl.NumberFormat('vi-VN').format(product.min_price)} VND</strong></h5>
                        </div>
                    </div>
                `).join('');

                // Update pagination
                let paginationHtml = `
                    <li><a href="#" class="pagination-prev ${data.current_page === 1 ? 'disabled' : ''}" data-page="${data.current_page - 1}"><i class="icon-left-4"></i> <span>PREV page</span></a></li>
                `;
                for (let i = 1; i <= data.last_page; i++) {
                    paginationHtml += `
                        <li class="${i === data.current_page ? 'active' : ''}">
                            <a href="#" data-page="${i}"><span>${i}</span></a>
                        </li>
                    `;
                }
                paginationHtml += `
                    <li><a href="#" class="pagination-next ${data.current_page === data.last_page ? 'disabled' : ''}" data-page="${data.current_page + 1}"><span>next page</span> <i class="icon-right-4"></i></a></li>
                `;
                paginationContainer.innerHTML = paginationHtml;

                // Update result count
                resultText.textContent = `Showing ${data.products.length > 0 ? (data.current_page - 1) * data.per_page + 1 : 0}–${(data.current_page - 1) * data.per_page + data.products.length} of ${data.total} results`;
            },
            error: function() {
                productList.innerHTML = '<div class="col-12 text-center"><p>Đã có lỗi xảy ra. Vui lòng thử lại.</p></div>';
            }
        });
    }

    // Handle category clicks
    document.querySelectorAll('#category-list a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('#category-list li').forEach(li => li.classList.remove('current'));
            this.parentElement.classList.add('current');
            currentDanhmucId = this.getAttribute('data-id') || '';
            isFilteringByPrice = false;
            fetchProducts(1, currentDanhmucId); // Load page 1 of the selected category
        });
    });

    // Highlight category based on URL parameter when page loads
    const urlParams = new URLSearchParams(window.location.search);
    const danhmucIdFromUrl = urlParams.get('danhmuc_id');
    currentDanhmucId = danhmucIdFromUrl || '';

    if (danhmucIdFromUrl && danhmucIdFromUrl !== '') {
        document.querySelectorAll('#category-list li').forEach(li => li.classList.remove('current'));
        const categoryLink = document.querySelector(`#category-list a[data-id="${danhmucIdFromUrl}"]`);
        if (categoryLink) {
            categoryLink.parentElement.classList.add('current');
        }
    } else {
        // Highlight "Tất cả" when no danhmuc_id
        document.querySelectorAll('#category-list li').forEach(li => li.classList.remove('current'));
        const allCategoryLink = document.querySelector('#category-list a[data-id=""]');
        if (allCategoryLink) {
            allCategoryLink.parentElement.classList.add('current');
        }
    }

    // Sự kiện phân trang (cả khi lọc giá và khi xem danh mục)
    paginationContainer.addEventListener('click', function(e) {
        e.preventDefault();
        const target = e.target.closest('a');
        if (!target || target.classList.contains('disabled')) return;
        const page = target.getAttribute('data-page');
        fetchProducts(page, currentDanhmucId || '');
    });

    // Handle search
    $('#btn-search').on('click', function () {
        let keyword = $('#search').val();

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
                                        <a href="${productDetailRoute.replace(':id', item.id)}">
                                            <img src="/storage/uploads/${item.image}" alt="${item.name}" style="border-radius: 20px;">
                                        </a>
                                        <div class="cart-overlay-wrap">
                                            <div class="cart-overlay">
                                                <a href="${productDetailRoute.replace(':id', item.id)}" class="shop-cart-btn">ADD TO CART</a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="${productDetailRoute.replace(':id', item.id)}"><h5>${item.name}</h5></a>
                                    <h5><strong>${new Intl.NumberFormat('vi-VN').format(item.min_price)} VND</strong></h5>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
                }

                $('#product-list').html(html);
            },
            error: function() {
                $('#product-list').html('<p class="text-center">Đã có lỗi xảy ra. Vui lòng thử lại.</p>');
            }
        });
    });

    // Initialize slider
    var slider = new Slider('#price-range', {
        formatter: function(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(value[0]) +
                   ' — ' +
                   new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(value[1]);
        }
    });

    // Update price range label
    slider.on('slide', function(value) {
        $("#price-range-label").text(
            new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(value[0]) +
            ' — ' +
            new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(value[1])
        );
    });

    // Sự kiện click nút lọc giá
    $('#btn-filter').on('click', function (e) {
        e.preventDefault();
        let range = slider.getValue();
        filterPriceRange = range;
        isFilteringByPrice = true;
        fetchProductsByPrice(1, range[0], range[1]);
    });
});
</script>

<style>
.slider-wrapper {
    padding: 10px 15px;
    margin-bottom: 20px;
}
.slider.slider-horizontal {
    width: 100%;
    height: 20px;
    margin: 0 auto;
}
.slider-track {
    background: #e9ecef;
    box-shadow: none;
}
.slider-selection {
    background: #c7a17a;
    box-shadow: none;
}
.slider-handle {
    background: #c7a17a;
    border: 2px solid #fff;
    box-shadow: 0 0 3px rgba(0,0,0,0.3);
}
.slider-handle:hover {
    background: #b08b63;
}
#price-range-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    display: inline-block;
}
.filter-btn {
    background: #c7a17a;
    color: #fff;
    padding: 8px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}
.filter-btn:hover {
    background: #b08b63;
    color: #fff;
}
</style>
@endsection
