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

function updateTotalPrice() {
    // Giá của size đang chọn
    var basePrice = parseFloat($('#modalPosItem input[name="size"]:checked').data('price')) || 0;
    // Tổng giá topping đã chọn
    var toppingPrices = 0;
    $('#modalPosItem input[name^="addon"]:checked').each(function () {
        toppingPrices += parseFloat($(this).data('price') || 0);
    });
    // Số lượng
    var qty = parseInt($('#modalPosItem input[name="qty"]').val()) || 1;
    // Tổng
    var total = (basePrice + toppingPrices) * qty;
    // Hiển thị lên popup
    $('#modalPosItem .product-price').text(formatVND(total));
}

    $(document).ready(function () {

        // Tăng giảm số lượng
        $(document).on('click', '#modalPosItem .qty-increase', function (e) {
            e.preventDefault();
            var $qty = $('#modalPosItem input[name="qty"]');
            $qty.val(parseInt($qty.val() || 1) + 1);
            updateTotalPrice();
        });

        $(document).on('click', '#modalPosItem .qty-decrease', function (e) {
            e.preventDefault();
            var $qty = $('#modalPosItem input[name="qty"]');
            var val = parseInt($qty.val() || 1);
            if (val > 1) $qty.val(val - 1);
            updateTotalPrice();
        });

        // Đổi size hoặc topping hoặc nhập số lượng thủ công
        $(document).on('change', '#modalPosItem input[name="size"], #modalPosItem input[name^="addon"], #modalPosItem input[name="qty"]', function () {
            updateTotalPrice();
        });

        $('.pos-product').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: '/staff/product/' + id,
                type: 'GET',
                success: function(data) {
                    // --- Render size ---
                    var sizeHtml = '';
                    data.sizes.forEach(function(attr, index) {
                        var checked = index === 0 ? 'checked' : '';
                        sizeHtml += `
                            <div class="option">
                                <input type="radio" id="size${index}" name="size" class="option-input"
                                    value="${attr.id}" data-size-name="${attr.size}" data-price="${parseFloat(attr.price)}" ${checked}/>
                                <label class="option-label" for="size${index}">
                                    <span class="option-text">${attr.size}</span>
                                    <span class="option-price">+${parseFloat(attr.price).toFixed(2)}</span>
                                </label>
                            </div>
                        `;
                    });
                    $('#modalPosItem .size-list').html(sizeHtml);

                    // --- Render topping ---
                    var toppingHtml = '';
                    data.toppings.forEach(function(topping, index) {
                        toppingHtml += `
                        <div class="option">
                            <input type="checkbox" name="addon[${topping.id}]" value="${topping.id}"
                                class="option-input" id="addon${index}" data-topping-name="${topping.topping}" data-price="${parseFloat(topping.price)}"/>
                            <label class="option-label" for="addon${index}">
                                <span class="option-text">${topping.topping}</span>
                                <span class="option-price">+${parseFloat(topping.price).toFixed(2)}</span>
                            </label>
                        </div>
                    `;
                    });
                    $('#modalPosItem .topping-list').html(toppingHtml);

                    // --- Đổ các thông tin khác ---
                    $('#modalPosItem .product-image').attr('src', data.image);
                    $('#modalPosItem .product-name').text(data.name);
                    $('#modalPosItem .product-description').text(data.mota);
                    $('#modalPosItem .product-price').text(data.price ? data.price + 'đ' : '');
                    $('#modalPosItem .add-to-cart').data('id', data.id);
                },
                error: function() {
                    alert('Không lấy được thông tin sản phẩm!');
                }
            });

        });

        $(document).on('click', '#modalPosItem .add-to-cart', function(e) {
            e.preventDefault();

            // Lấy thông tin sản phẩm vừa chọn
            var id = $('#modalPosItem .add-to-cart').data('id');
            var name = $('#modalPosItem .product-name').text();
            var image = $('#modalPosItem .product-image').attr('src');
            var qty = parseInt($('#modalPosItem input[name="qty"]').val()) || 1;
            var sizeId = $('#modalPosItem input[name="size"]:checked').val(); // bây giờ là id (number)
            var sizeText = $('#modalPosItem input[name="size"]:checked').data('size-name') || '';
            var sizePrice = parseFloat($('#modalPosItem input[name="size"]:checked').data('price')) || 0;

            // Topping

            // Tính tổng topping
            var toppingIds = [];
            var toppingTextArr = [];
            var toppingTotal = 0;
            $('#modalPosItem input[name^="addon"]:checked').each(function () {
                toppingIds.push($(this).val());
                toppingTextArr.push($(this).siblings('label').find('.option-text').text());
                toppingTotal += parseFloat($(this).data('price')) || 0;
            });
            var toppingText = toppingTextArr.join(', ');

            // Tổng giá sản phẩm
            var total = (sizePrice + toppingTotal) * qty;

            // Tạo HTML sản phẩm cho giỏ
            var html = `
                <div class="pos-order mb-2"
                    data-id="${id}"
                    data-size="${sizeText}"
                    data-sizeid="${sizeId}"
                    data-sizeprice="${sizePrice}"
                    data-topping="${toppingText}"
                    data-toppingids="${toppingIds.length ? toppingIds.join(',') : ''}"
                    data-toppingtotal="${toppingTotal}">
                    <div class="pos-order-product d-flex align-items-center">
                        <div class="img" style="width:60px;height:60px;background-image:url('${image}');background-size:cover;background-position:center;border-radius:8px;"></div>
                        <div class="flex-1 ms-3">
                            <div class="h6 mb-1">${name}</div>
                            <div class="small text-muted">${formatVND(sizePrice)}${sizeText ? ' - size: ' + sizeText : ''}</div>
                            ${toppingText ? `<div class="small text-muted">+ Topping: ${toppingText}</div>` : ''}
                            <div class="d-flex align-items-center mt-2">
                                <a href="#" class="btn btn-secondary btn-sm order-qty-decrease"><i class="fa fa-minus"></i></a>
                                <input type="text" class="form-control w-50px form-control-sm mx-2 text-center order-qty" value="${qty}" />
                                <a href="#" class="btn btn-secondary btn-sm order-qty-increase"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="ms-2 text-end">
                            <div class="fw-bold order-total">${formatVND(total)}</div>
                            <a href="#" class="btn btn-default btn-sm order-remove mt-1" title="Xóa"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            `;
            // Thêm vào sidebar (append hoặc prepend tuỳ ý)
            $('#newOrderTab .pos-order-list').append(html);
            updateSidebarTotal();

            // Đóng modal popup lại
            $('#modalPosItem').modal('hide');
        });

        function updateSidebarTotal() {
            var subtotal = 0;
            $('#newOrderTab .pos-order-list .pos-order').each(function(){
                var $order = $(this);
                // Lấy giá trị hiển thị tổng giá của từng sản phẩm (chuỗi "xx đ")
                var itemTotal = $order.find('.order-total').text().replace(/[^\d]/g, '');
                subtotal += parseInt(itemTotal) || 0;
            });
            var taxRate = 0.06;
            var taxes = subtotal * taxRate;
            var total = subtotal + taxes;

            $('.pos-sidebar-footer .text-end.h6.mb-0').eq(0).text(formatVND(subtotal));
            $('.pos-sidebar-footer .text-end.h6.mb-0').eq(1).text(formatVND(taxes));
            $('.pos-sidebar-footer .text-end.h4.mb-0').text(formatVND(total));
        }

        $(document).on('click', '.order-remove', function(e){
            e.preventDefault();
            $(this).closest('.pos-order').remove();
            updateSidebarTotal();
        });

        // Thu thập toàn bộ thông tin giỏ hàng từ sidebar
        function collectOrderData() {
            let cart = [];
            $('#newOrderTab .pos-order-list .pos-order').each(function () {
                let $order = $(this);
                let product_id = $order.data('id');
                let name = $order.find('.h6.mb-1').text();
                let size = $order.data('size');
                let sizeId = $order.data('sizeid'); // lấy id từ thuộc tính đã set ở trên
                let sizePrice = parseFloat($order.data('sizeprice')) || 0;
                let qty = parseInt($order.find('.order-qty').val()) || 1;


                let toppingIds = [];
                let toppingIdsRaw = $order.attr('data-toppingids');
                if (typeof toppingIdsRaw === 'string' && toppingIdsRaw.length > 0) {
                    toppingIds = toppingIdsRaw.split(',').map(x => parseInt(x)).filter(x => !isNaN(x));
                } else {
                    toppingIds = [];
                }
                let toppingTotal = parseFloat($order.data('toppingtotal')) || 0;
                let total = (sizePrice + toppingTotal) * qty;

                cart.push({
                    product_id: product_id,
                    product_name: name,
                    size: size,
                    size_id: sizeId,
                    product_price: sizePrice,
                    toppings: toppingIds,
                    quantity: qty,
                    total: total
                });
            });


            // Tính tổng tiền
            let subtotal = 0;
            cart.forEach(item => subtotal += item.total);
            let taxRate = 0.06;
            let taxes = subtotal * taxRate;
            let total = subtotal + taxes;

            // Dữ liệu gửi lên server
            return {
                name: 'Khách lẻ',
                payment_method: 'cash',
                cart: cart,
                subtotal: subtotal,
                tax: taxes,
                total: total,
            };

        }
        // console.log('Topping raw:', toppingIdsRaw, '→ array:', toppingIds);

// Xử lý nút submit order
        $(document).on('click', '.btn-submit-order', function(e) {
            e.preventDefault();

            // Lấy dữ liệu order
            var orderData = collectOrderData();

            if(orderData.cart.length === 0) {
                alert('Giỏ hàng đang trống!');
                return;
            }

            $.ajax({
                url: '/staff/orders',
                method: 'POST',
                data: orderData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    alert('Đặt hàng thành công!');
                    // Xóa giỏ trên giao diện
                    $('#newOrderTab .pos-order-list').empty();
                    // Reset subtotal, total về 0
                    $('.pos-sidebar-footer .text-end.h6.mb-0').eq(0).text(formatVND(0));
                    $('.pos-sidebar-footer .text-end.h6.mb-0').eq(1).text(formatVND(0));
                    $('.pos-sidebar-footer .text-end.h4.mb-0').text(formatVND(0));
                },
                error: function(xhr){

                    console.log(xhr.responseText);

                    try {
                        var res = JSON.parse(xhr.responseText);
                        if (res.message) {
                            alert('Lỗi: ' + res.message);
                        } else {
                            alert(xhr.responseText);
                        }
                    } catch(e) {
                        alert(xhr.responseText);
                    }
                }
            });
        });
    });
