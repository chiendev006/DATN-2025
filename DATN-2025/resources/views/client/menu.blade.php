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
                          <a href="#" data-id="{{ $danhmuc->id }}">
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
                                <h6 id="category-description">{{ $firstDanhmuc->description ?? 'Mô tả danh mục' }}</h6>
                            </div>

                            <div class="row" id="product-display">
                                @foreach ($firstProducts as $product)
                                    <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
                                        <div class="menu-fix-list">
                                            <span class="menu-fix-product">
                                                <img src="{{ asset('storage/' . ltrim($product->image, '/')) }}" style="width: 100px; height: 100px; border-radius: 100px;" alt="{{ $product->name }}">
                                            </span>
                                            <h5>{{ strtoupper($product->name) }} <span> {{ number_format($product->min_price) }} VND</span></h5>
                                            <p>{{ $product->mota ?? 'Không có mô tả.' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                <section class="default-section">
                    <div class="container">
                        <div class="discount-part">
                            <div class="row">
                                <div class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
                                    <h6>HAPPY CUSTOMER EVENT</h6>
                                    <h2 class="text-coffee">DISCOUNT <span>50%</span></h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor int et lamp dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                    <a href="#" class="button-default buttone-text-dark">LEARN MORE</a>
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
$(document).ready(function(){
    $('#category-list a').click(function(e){
        e.preventDefault();

        var categoryId = $(this).data('id');

        // Đổi active class
        $('#category-list li').removeClass('current');
        $(this).parent().addClass('current');

        $.ajax({
            url: '/menu/category/' + categoryId,
            type: 'GET',
            dataType: 'json',
            success: function(response){
                // Cập nhật tên, mô tả danh mục
                $('#category-name').text(response.category_name);
                $('#category-description').text(response.category_description);

                // Xóa hết sản phẩm cũ
                $('#product-display').empty();

                // Thêm sản phẩm mới
                $.each(response.products, function(index, product){
                    var productHtml = `
                        <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
                            <div class="menu-fix-list">
                                <span class="menu-fix-product">
                                    <img src="/storage/${product.image.replace(/^\/+/, '')}" alt="${product.name}">
                                </span>
                                <h5>${product.name.toUpperCase()} <span>$ ${parseFloat(product.min_price).toFixed(2)}</span></h5>
                                <p>${product.description || 'Không có mô tả.'}</p>
                            </div>
                        </div>
                    `;
                    $('#product-display').append(productHtml);
                });
            },
            error: function(){
                alert('Không thể tải sản phẩm, vui lòng thử lại.');
            }
        });
    });
});
$(document).ready(function() {
    $('#category-list li a').click(function(e) {
        e.preventDefault();

        var categoryId = $(this).data('id');

        $.ajax({
            url: '/menu/category/' + categoryId,
            method: 'GET',
            success: function(response) {
                // Cập nhật tên, mô tả danh mục
                $('#category-name').text(response.category_name);
                $('#category-description').text(response.category_description);

                // Xóa hết sản phẩm cũ
                $('#product-display').empty();

                // Thêm sản phẩm mới với style giống Blade
                $.each(response.products, function(index, product) {
                    // Xử lý đường dẫn ảnh
                    let imageUrl = product.image;
                    if (!imageUrl.startsWith('http')) {
                        imageUrl = '/storage/' + product.image.replace(/^\/+/, '');
                    }

                    let productHtml = `
                        <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
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

                // Nếu bạn dùng wow.js thì gọi lại init:
                if (typeof WOW === 'function') {
                    new WOW().init();
                }
            }
        });
    });
});

</script>
@endsection
