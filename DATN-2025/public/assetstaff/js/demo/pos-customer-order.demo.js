/*
Template Name: STUDIO ASP - Responsive Bootstrap 5 Admin Template
Version: 5.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/studio/
*/

var handleFilter = function() {
	"use strict";

	$(document).on('click', '.pos-menu [data-filter]', function(e) {
		e.preventDefault();

		var targetType = $(this).attr('data-filter');

		$(this).addClass('active');
		$('.pos-menu [data-filter]').not(this).removeClass('active');
		if (targetType == 'all') {
			$('.pos-content [data-type]').removeClass('d-none');
		} else {
			$('.pos-content [data-type="'+ targetType +'"]').removeClass('d-none');
			$('.pos-content [data-type]').not('.pos-content [data-type="'+ targetType +'"]').addClass('d-none');
		}
	});
};

function formatVND(number) {
    return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
}


$(document).ready(function () {

    function updateTotalPrice() {
        var basePrice = parseFloat($('#modalPosItem input[name="size"]:checked').data('price') || 0);
        var toppingPrices = 0;
        $('#modalPosItem input[name^="addon"]:checked').each(function () {
            toppingPrices += parseFloat($(this).data('price') || 0);
        });
        var qty = parseInt($('#modalPosItem input[name="qty"]').val()) || 1;
        var total = (basePrice + toppingPrices) * qty;
        $('#modalPosItem .product-price').text(formatVND(total));
    }

    // Xử lý khi nhấn vào sản phẩm để hiển thị modal
    $('.pos-product').on('click', function (e) {
        e.preventDefault();
        var productId = $(this).data('id');
        $('#modalPosItem').data('product-id', productId);
        var productName = $(this).data('name');
        var productImage = $(this).data('image');

        // Điền dữ liệu cơ bản vào modal
        $('#modalPosItem .product-name').text(productName);
        $('#modalPosItem .product-image').attr('src', productImage).attr('alt', productName);
        $('#modalPosItem input[name="qty"]').val(1);
        $('#modalPosItem .size-list').empty();
        $('#modalPosItem .topping-list').empty();
        $('#modalPosItem .product-price').text('Đang tải...');

        // Lấy kích thước và topping qua AJAX
        $.ajax({
            url: '/staff/product/' + productId + '/options',
            method: 'GET',
            success: function (response) {
                // Điền kích thước
                var sizeHtml = '';
                if (response.attributes.length === 0) {
                    sizeHtml = '<p>Không có kích thước nào.</p>';
                } else {
                    response.attributes.forEach(function (attr, index) {
                        var checked = index === 0 ? 'checked' : '';
                        sizeHtml += `
                            <div class="option">
                                <input type="radio" id="size${index}" name="size" class="option-input"
                                    value="${attr.size}" data-price="${parseFloat(attr.price)}" ${checked}/>
                                <label class="option-label" for="size${index}">
                                    <span class="option-text">${attr.size}</span>
                                    <span class="option-price">+${parseFloat(attr.price).toFixed(2)}</span>
                                </label>
                            </div>
                        `;
                    });
                }
                $('#modalPosItem .size-list').html(sizeHtml);

                // Cập nhật giá dựa trên kích thước đầu tiên
                var initialPrice = response.attributes.length > 0 ? parseFloat(response.attributes[0].price).toFixed(2) : '0.00';
                $('#modalPosItem .product-price').text(formatVND(parseFloat(initialPrice)));

                // Điền topping
                var toppingHtml = '';
                if (response.toppings.length === 0) {
                    toppingHtml = '<p>Không có topping nào.</p>';
                } else {
                    response.toppings.forEach(function (topping, index) {
                        toppingHtml += `
                            <div class="option">
                                <input type="checkbox" name="addon[${topping.topping}]" value="true"
                                    class="option-input" id="addon${index}" data-price="${parseFloat(topping.price)}"/>
                                <label class="option-label" for="addon${index}">
                                    <span class="option-text">${topping.topping}</span>
                                    <span class="option-price">+${parseFloat(topping.price).toFixed(2)}</span>
                                </label>
                            </div>
                        `;
                    });
                }
                $('#modalPosItem .topping-list').html(toppingHtml);
            },
            error: function (xhr) {
                alert('Lỗi khi tải kích thước và topping: ' + xhr.responseJSON.message);
                $('#modalPosItem .size-list').html('<p>Lỗi tải dữ liệu.</p>');
                $('#modalPosItem .topping-list').html('<p>Lỗi tải dữ liệu.</p>');
                $('#modalPosItem .product-price').text('$0.00');
            }
        });
    });

    // Xử lý giảm số lượng
    $(document).on('click', '#modalPosItem .qty-decrease', function (e) {
        e.preventDefault();
        var qtyInput = $(this).siblings('input[name="qty"]');
        var qty = parseInt(qtyInput.val());
        if (qty > 1) {
            qtyInput.val(qty - 1);
            updateTotalPrice();
        }
    });

    // Xử lý tăng số lượng
    $(document).on('click', '#modalPosItem .qty-increase', function (e) {
        e.preventDefault();
        var qtyInput = $(this).siblings('input[name="qty"]');
        var qty = parseInt(qtyInput.val());
        qtyInput.val(qty + 1);
        updateTotalPrice();
    });
    // Cập nhật giá khi thay đổi kích thước hoặc topping
    $(document).on('change', '#modalPosItem input[name="size"], #modalPosItem input[name^="addon"]', function () {
        updateTotalPrice();
    });

});
