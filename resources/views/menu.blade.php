@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header-category">
        <div class="container">
            <div class="category-icon">
                <ul class="category-menu">
                    <li class="current" data-category="coffee"><a href="#"><span class="cofee custom-icon"></span><strong>CÀ PHÊ</strong></a></li>
                    <li data-category="milk"><a href="#"><span class="milk custom-icon"></span><strong>SỮA</strong></a></li>
                    <li data-category="cocktail"><a href="#"><span class="cocktail custom-icon"></span><strong>COCKTAIL</strong></a></li>
                    <li data-category="beverages"><a href="#"><span class="bewerages custom-icon"></span><strong>NƯỚC GIẢI KHÁT</strong></a></li>
                    <li data-category="tea"><a href="#"><span class="tea custom-icon"></span><strong>TRÀ</strong></a></li>
                    <li data-category="cake"><a href="#"><span class="cake custom-icon"></span><strong>BÁNH</strong></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu-fix-main-list wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="700ms">
        <div class="row" id="product-container">
            <!-- Products will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Load initial products (coffee category)
    loadProducts('coffee');

    // Handle category click
    $('.category-menu li').click(function(e) {
        e.preventDefault();
        
        // Remove current class from all items
        $('.category-menu li').removeClass('current');
        
        // Add current class to clicked item
        $(this).addClass('current');
        
        // Get category and load products
        const category = $(this).data('category');
        loadProducts(category);
    });

    function loadProducts(category) {
        $.ajax({
            url: `/get-products-by-category/${category}`,
            method: 'GET',
            beforeSend: function() {
                // Hiển thị loading nếu cần
                $('#product-container').html('<div class="text-center">Đang tải...</div>');
            },
            success: function(response) {
                if (response.success) {
                    // Update the product container with new HTML
                    $('#product-container').html(response.html);
                    
                    // Reinitialize WOW animations
                    new WOW().init();
                }
            },
            error: function(xhr) {
                console.error('Lỗi khi tải sản phẩm:', xhr);
                $('#product-container').html('<div class="text-center">Có lỗi xảy ra khi tải sản phẩm</div>');
            }
        });
    }
});
</script>
@endpush
@endsection 