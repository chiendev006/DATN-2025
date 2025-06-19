@extends('layout2')

@section('main')
<div class="header-category" style="background-color: #c19942; margin-top: 0px">
    <div class="container">
        <div class="category-icon">
            <div class="category-icon-menu">
                <a href="#" class="hambarger-icon">
                    <span class="bar-1"></span>
                    <span class="bar-2"></span>
                    <span class="bar-3"></span>
                </a>
            </div>
            <ul id="category-list">
                @foreach ($danhmucs as $index => $danhmuc)
                    <li class="{{ $index == 0 ? 'current' : '' }}">
                        <a  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="#" data-id="{{ $danhmuc->id }}">
                            <span class="custom-icon {{ strtolower($danhmuc->name) }}"></span>
                            <strong>{{ strtoupper($danhmuc->name) }}</strong>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<main>
    <div class="main-part">
        <section class="breadcrumb-nav">
            <div class="container">
                <div class="breadcrumb-nav-inner">
                    <ul>
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active"><a href="#">Menu</a></li>
                    </ul>
                    <label class="now">MENU</label>
                </div>
            </div>
        </section>
        <section class="default-section menu-fix bg-grey">
            <div class="container">
                <div class="menu-fix-main-list menu-fix-with-item">
                    <div class="title text-center">
                        <h2 class="text-dark" id="category-name">{{ $firstDanhmuc->name ?? 'Danh mục' }}</h2>
                        <h6  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" id="category-description">{{ $firstDanhmuc->description ?? 'Mô tả danh mục' }}</h6>
                    </div>

                    <div class="row" id="product-display">
                        @foreach ($firstProducts as $product)
                            <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="500ms">
                                <div class="menu-fix-list">
                                    <span class="menu-fix-product">
                                        <img src="{{ url('storage/uploads/' . $product->image) }}" style="width: 100px; height: 100px; border-radius: 100px;" alt="{{ $product->name }}">
                                    </span>
                                    <h5  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">{{ $product->name }} <span> {{ number_format($product->min_price) }} VND</span></h5>
                                    <p>{{ $product->mota ?? 'Không có mô tả.' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="text-align: center;" class="gallery-pagination">
                        <div class="gallery-pagination-inner">
                            <ul>
                                <li>
                                    <a  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="#" class="pagination-prev {{ $firstProducts->onFirstPage() ? 'disabled' : '' }}" data-page="{{ $firstProducts->currentPage() - 1 }}">
                                        <i class="icon-left-4"></i> <span>PREV page</span>
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $firstProducts->lastPage(); $i++)
                                    <li class="{{ $i == $firstProducts->currentPage() ? 'active' : '' }}">
                                        <a href="#" data-page="{{ $i }}"><span>{{ $i }}</span></a>
                                    </li>
                                @endfor
                                <li>
                                    <a  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="#" class="pagination-next {{ $firstProducts->hasMorePages() ? '' : 'disabled' }}" data-page="{{ $firstProducts->currentPage() + 1 }}">
                                        <span >next page</span> <i class="icon-right-4"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="default-section">
            <div class="container">
                <div class="discount-part">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="700ms" data-wow-delay="700ms">
                            <h6  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">HAPPY CUSTOMER EVENT</h6>
                            <h2  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" class="text-coffee">DISCOUNT <span>50%</span></h2>
                            <p  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor int et lamp dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                            <a  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="#" class="button-default buttone-text-dark">LEARN MORE</a>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <div class="discount-right">
                                <img src="{{ url('asset') }}/images/item7.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="on-flow">
                <img src="{{ url('asset') }}/images/item8.png" alt="">
            </div>
        </section>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#category-list a').click(function(e) {
        e.preventDefault();
        var categoryId = $(this).data('id');
        $('#category-list li').removeClass('current');
        $(this).parent().addClass('current');

        loadProducts(categoryId, 1);
    });

    $(document).on('click', '.gallery-pagination a', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;

        var page = $(this).data('page');
        var categoryId = $('#category-list li.current a').data('id');

        loadProducts(categoryId, page);
    });

    function loadProducts(categoryId, page) {
        $.ajax({
            url: '/menu/category/' + categoryId + '?page=' + page,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#category-name').text(response.category_name);
                $('#category-description').text(response.category_description);

                $('#product-display').empty();

                $.each(response.products, function(index, product) {
                    let imageUrl = product.image;
                    if (!imageUrl.startsWith('http')) {
                        imageUrl = '/storage/uploads/' + product.image.replace(/^\/+/, '');
                    }

                    let productHtml = `
                        <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 wow fadeInDown" data-wow-duration="700ms" data-wow-delay="500ms">
                            <div class="menu-fix-list">
                                <span class="menu-fix-product">
                                    <img src="${imageUrl}" style="width: 100px; height: 100px; border-radius: 100px;" alt="${product.name}">
                                </span>
                                <h5>${product.name.toUpperCase()} <span> ${Number(product.min_price).toLocaleString()} VND</span></h5>
                                <p>${product.mota || 'Không có mô tả.'}</p>
                            </div>
                        </div>
                    `;
                    $('#product-display').append(productHtml);
                });

                updatePagination(response.pagination);

                if (typeof WOW === 'function') {
                    new WOW().init();
                }
            },
            error: function() {
                alert('Không thể tải sản phẩm, vui lòng thử lại.');
            }
        });
    }

    function updatePagination(pagination) {
        var paginationHtml = `
            <div class="gallery-pagination-inner">
                <ul>
                    <li>
                        <a href="#" class="pagination-prev ${pagination.current_page === 1 ? 'disabled' : ''}" data-page="${pagination.current_page - 1}">
                            <i class="icon-left-4"></i> <span>PREV page</span>
                        </a>
                    </li>
        `;

        for (let i = 1; i <= pagination.last_page; i++) {
            paginationHtml += `
                <li class="${i === pagination.current_page ? 'active' : ''}">
                    <a href="#" data-page="${i}"><span>${i}</span></a>
                </li>
            `;
        }

        paginationHtml += `
                    <li>
                        <a href="#" class="pagination-next ${pagination.current_page === pagination.last_page ? 'disabled' : ''}" data-page="${pagination.current_page + 1}">
                            <span>next page</span> <i class="icon-right-4"></i>
                        </a>
                    </li>
                </ul>
            </div>
        `;

        $('.gallery-pagination').html(paginationHtml);
    }
});
</script>
@endsection
