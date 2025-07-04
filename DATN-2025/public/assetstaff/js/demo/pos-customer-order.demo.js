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
    number = Number(number) || 0;
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

        loadCartFromLocalStorage();

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
                    if (data.no_topping) {
                        toppingHtml = '<div class="text-danger">Sản phẩm này không kèm topping</div>';
                    } else {
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
                    }
                    $('#modalPosItem .topping-list').html(toppingHtml);

                    // --- Đổ các thông tin khác ---
                    $('#modalPosItem .product-image').attr('src', data.image);
                    $('#modalPosItem .product-name').text(data.name);
                    $('#modalPosItem .product-description').text(data.mota);
                    $('#modalPosItem .product-price').text(data.price ? data.price + 'đ' : '');
                    $('#modalPosItem .add-to-cart').data('id', data.id);
                    $('#modalPosItem input[name="qty"]').val(1);
                },
                error: function() {
                    alert('Không lấy được thông tin sản phẩm!');
                }
            });

        });

        $(document).on('click', '#modalPosItem .add-to-cart', function(e) {
            e.preventDefault();

            var id = $('#modalPosItem .add-to-cart').data('id');
            var name = $('#modalPosItem .product-name').text();
            var image = $('#modalPosItem .product-image').attr('src');
            var qty = parseInt($('#modalPosItem input[name="qty"]').val()) || 1;
            var sizeId = $('#modalPosItem input[name="size"]:checked').val();
            var sizeText = $('#modalPosItem input[name="size"]:checked').data('size-name') || '';
            var sizePrice = parseFloat($('#modalPosItem input[name="size"]:checked').data('price')) || 0;

            var toppingIds = [];
            var toppingNames = [];
            var toppingTotal = 0;
            $('#modalPosItem input[name^="addon"]:checked').each(function () {
                toppingIds.push($(this).val());
                toppingNames.push($(this).siblings('label').find('.option-text').text());
                toppingTotal += parseFloat($(this).data('price')) || 0;
            });
            var toppingText = toppingNames.join(', ');

            var total = (sizePrice + toppingTotal) * qty;

            var html = `
                <div class="pos-order mb-2"
                    data-id="${id}"
                    data-size="${sizeText}"
                    data-sizeid="${sizeId}"
                    data-sizeprice="${sizePrice}"
                    data-image="${image}"
                    data-topping="${toppingText}"
                    data-toppingnames="${toppingNames.join('|')}"
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
            $('#newOrderTab .pos-order-list').append(html);
            updateSidebarTotal();
            saveCartToLocalStorage();
            $('#modalPosItem').modal('hide');
        });

        $(document).on('click', '.order-remove', function(e){
            e.preventDefault();
            $(this).closest('.pos-order').remove();
            updateSidebarTotal();
            saveCartToLocalStorage();
        });

        // Coupons
        var appliedCoupon = null;

        // Load danh sách coupon hợp lệ vào select
        $.get('/staff/coupons', function(data) {
            if(data && data.length > 0) {
                data.forEach(function(coupon) {
                    $('#coupon-select').append(`
                      <option value="${coupon.code}"
                        data-type="${coupon.type}"
                        data-discount="${coupon.discount}"
                        data-min="${coupon.min_order_value}">
                        ${coupon.code} - ${
                        coupon.type === 'percent'
                            ? (coupon.discount + '%')
                            : (Number(coupon.discount).toLocaleString('vi-VN') + 'đ')
                            } (Tối thiểu: ${Number(coupon.min_order_value).toLocaleString('vi-VN')}đ)
                      </option>
                    `);
                });
                updateCouponOptions();
            }
        });

        // Khi bấm Áp dụng coupon
        $('#apply-coupon-btn').on('click', function() {
            let code = $('#coupon-select').val();
            let type = $('#coupon-select option:selected').data('type');
            let discount = Number($('#coupon-select option:selected').data('discount'));
            let min = Number($('#coupon-select option:selected').data('min'));
            let subtotal = getCartSubtotal();

            if(!code) {
                $('#coupon-message').text('Bạn chưa chọn mã giảm giá!').css('color', 'red');
                appliedCoupon = null;
                updateSidebarTotal();
                return;
            }
            if(subtotal < min) {
                $('#coupon-message').text('Đơn hàng chưa đủ giá trị tối thiểu!').css('color', 'red');
                appliedCoupon = null;
                updateSidebarTotal();
                return;
            }

            // Tính số tiền giảm giá
            let discountValue = type === 'percent' ? Math.floor(subtotal * discount / 100) : discount;
            if (discountValue > subtotal) discountValue = subtotal;
            appliedCoupon = {
                code, type, discount, discountValue
            };
            $('#coupon-message').html('Áp dụng thành công: <b>-' + discountValue.toLocaleString() + 'đ</b>').css('color', 'green');
            updateSidebarTotal();
        });

        // Tự động áp dụng khi chọn coupon
        $('#coupon-select').on('change', function() {
            $('#apply-coupon-btn').trigger('click');
        });

        // Reset coupon khi xóa hết sản phẩm
        $(document).on('click', '.order-remove', function() {
            setTimeout(function() {
                if(getCartSubtotal() === 0) {
                    appliedCoupon = null;
                    $('#coupon-select').val('');
                    $('#coupon-message').text('');
                    updateSidebarTotal();
                }
            }, 100);
        });

        // Hàm lấy tổng phụ
        function getCartSubtotal() {
            var subtotal = 0;
            $('#newOrderTab .pos-order-list .pos-order').each(function(){
                var $order = $(this);
                var itemTotal = $order.find('.order-total').text().replace(/[^\d]/g, '');
                subtotal += parseInt(itemTotal) || 0;
            });
            return subtotal;
        }

        function updateSidebarTotal() {
            var subtotal = getCartSubtotal();
            var discount = appliedCoupon ? appliedCoupon.discountValue : 0;
            var total = subtotal - discount;
            if (total < 0) total = 0;

            $('#cart-subtotal').text(formatVND(subtotal));
            $('#cart-discount').text('-' + formatVND(discount));
            $('#cart-total').text(formatVND(total));

            updateCouponOptions();
        }

        // Thu thập toàn bộ thông tin giỏ hàng từ sidebar
        function collectOrderData() {
            let cart = [];
            $('#newOrderTab .pos-order-list .pos-order').each(function () {
                let $order = $(this);
                let product_id = $order.data('id');
                let name = $order.find('.h6.mb-1').text();
                let size = $order.data('size');
                let sizeId = $order.data('sizeid');
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
                    total: total,
                });
            });

            let subtotal = getCartSubtotal();
            let total = subtotal - (appliedCoupon ? appliedCoupon.discountValue : 0);

            return {
                name: 'Khách lẻ',
                payment_method: 'cash',
                cart: cart,
                subtotal: subtotal,
                total: total,
                coupon_code: appliedCoupon ? appliedCoupon.code : null,
                coupon_discount: appliedCoupon ? appliedCoupon.discountValue : 0,
                pay_status: 0 // Mặc định là chờ thanh toán
            };
        }

        // Xử lý sự kiện click cho nút "Xác nhận" và "Thanh toán"
        $(document).on('click', '#btn-confirm-order, #btn-pay-order', function(e) {
            e.preventDefault();

            const payStatus = $(this).data('pay_status');

            // Lấy dữ liệu order
            var orderData = collectOrderData();
            orderData.pay_status = payStatus;

            if(orderData.cart.length === 0) {
                alert('Giỏ hàng đang trống!');
                return;
            }

            $.ajax({
                url: '/staff/orders',
                method: 'POST',
                data: JSON.stringify(orderData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    alert('Đặt hàng thành công!');
                    localStorage.removeItem('pos_cart');
                    // Xóa giỏ trên giao diện
                    $('#newOrderTab .pos-order-list').empty();
                    // Reset subtotal, total về 0
                    $('.pos-sidebar-footer .text-end.h6.mb-0').eq(0).text(formatVND(0));
                    $('.pos-sidebar-footer .text-end.h4.mb-0').text(formatVND(0));
                    // XÓA coupon message
                    $('#coupon-message').text('');
                    $('#coupon-select').val('');
                    // Reset biến global coupon nếu có
                    appliedCoupon = null;
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

        function updateCouponOptions() {
            let subtotal = getCartSubtotal();
            $('#coupon-select option').each(function(){
                let min = Number($(this).data('min')) || 0;
                if (subtotal < min && $(this).val() !== "") {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
            if ($('#coupon-select option:selected').prop('disabled')) {
                $('#coupon-select').val('');
                $('#coupon-message').text('');
                appliedCoupon = null;
                updateSidebarTotal();
            }
        }

        // Tăng số lượng trong giỏ
        $(document).on('click', '.order-qty-increase', function(e) {
            e.preventDefault();
            var $qtyInput = $(this).closest('.pos-order-product').find('.order-qty');
            var qty = parseInt($qtyInput.val()) || 1;
            $qtyInput.val(qty + 1);

            // Cập nhật tổng tiền cho sản phẩm
            var $order = $(this).closest('.pos-order');
            var sizePrice = parseFloat($order.data('sizeprice')) || 0;
            var toppingTotal = parseFloat($order.data('toppingtotal')) || 0;
            var newTotal = (sizePrice + toppingTotal) * (qty + 1);
            $order.find('.order-total').text(formatVND(newTotal));
            $order.data('total', newTotal);

            updateSidebarTotal();
            saveCartToLocalStorage();
        });

        // Giảm số lượng trong giỏ
        $(document).on('click', '.order-qty-decrease', function(e) {
            e.preventDefault();
            var $qtyInput = $(this).closest('.pos-order-product').find('.order-qty');
            var qty = parseInt($qtyInput.val()) || 1;
            if (qty > 1) {
                $qtyInput.val(qty - 1);

                // Cập nhật tổng tiền cho sản phẩm
                var $order = $(this).closest('.pos-order');
                var sizePrice = parseFloat($order.data('sizeprice')) || 0;
                var toppingTotal = parseFloat($order.data('toppingtotal')) || 0;
                var newTotal = (sizePrice + toppingTotal) * (qty - 1);
                $order.find('.order-total').text(formatVND(newTotal));
                $order.data('total', newTotal);

                updateSidebarTotal();
                saveCartToLocalStorage();
            }
        });


        function saveCartToLocalStorage() {
            let cart = [];
            $('#newOrderTab .pos-order-list .pos-order').each(function () {
                let $order = $(this);
                let product_id = $order.data('id');
                let name = $order.find('.h6.mb-1').text();
                let image = $order.data('image');
                let size = $order.data('size');
                let sizeId = $order.data('sizeid');
                let sizePrice = parseFloat($order.data('sizeprice')) || 0;
                let qty = parseInt($order.find('.order-qty').val()) || 1;
                let toppingIdsRaw = $order.attr('data-toppingids');
                let toppingNamesRaw = $order.attr('data-toppingnames') || '';
                let toppingIds = [];
                let toppingNames = [];
                if (typeof toppingIdsRaw === 'string' && toppingIdsRaw.length > 0) {
                    toppingIds = toppingIdsRaw.split(',').map(x => parseInt(x)).filter(x => !isNaN(x));
                }
                if (typeof toppingNamesRaw === 'string' && toppingNamesRaw.length > 0) {
                    toppingNames = toppingNamesRaw.split('|');
                }
                let toppingTotal = parseFloat($order.data('toppingtotal')) || 0;
                let total = (sizePrice + toppingTotal) * qty;

                cart.push({
                    product_id: product_id,
                    product_name: name,
                    size: size,
                    size_id: sizeId,
                    image: image,
                    product_price: sizePrice,
                    toppings: toppingIds,
                    topping_names: toppingNames,
                    quantity: qty,
                    total: total
                });
            });
            localStorage.setItem('pos_cart', JSON.stringify(cart));
        }

        function loadCartFromLocalStorage() {
            let cart = [];
            let saved = localStorage.getItem('pos_cart');
            if (saved) {
                try {
                    cart = JSON.parse(saved);
                } catch (e) {
                    cart = [];
                }
            }
            if (cart.length > 0) {
                $('#newOrderTab .pos-order-list').empty();
                cart.forEach(function(item) {
                    var toppingsArr = Array.isArray(item.topping_names) ? item.topping_names : [];
                    var toppingText = toppingsArr.length > 0 ? toppingsArr.join(', ') : '';
                    var html = `
                            <div class="pos-order mb-2"
                                data-id="${item.product_id}"
                                data-size="${item.size}"
                                data-sizeid="${item.size_id}"
                                data-sizeprice="${item.product_price}"
                                data-image="${item.image || ''}"
                                data-topping="${toppingText}"
                                data-toppingnames="${toppingsArr.join('|')}"
                                data-toppingids="${Array.isArray(item.toppings) ? item.toppings.join(',') : ''}"
                                data-toppingtotal="${((item.total/item.quantity) - item.product_price).toFixed(0)}">
                                <div class="pos-order-product d-flex align-items-center">
                                    <div class="img" style="width:60px;height:60px;background-image:url('${item.image || ''}');background-size:cover;background-position:center;border-radius:8px;"></div>
                                    <div class="flex-1 ms-3">
                                        <div class="h6 mb-1">${item.product_name}</div>
                                        <div class="small text-muted">${formatVND(item.product_price)}${item.size ? ' - size: ' + item.size : ''}</div>
                                        ${toppingText ? `<div class="small text-muted">+ Topping: ${toppingText}</div>` : ''}
                                        <div class="d-flex align-items-center mt-2">
                                            <a href="#" class="btn btn-secondary btn-sm order-qty-decrease"><i class="fa fa-minus"></i></a>
                                            <input type="text" class="form-control w-50px form-control-sm mx-2 text-center order-qty" value="${item.quantity}" />
                                            <a href="#" class="btn btn-secondary btn-sm order-qty-increase"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="ms-2 text-end">
                                        <div class="fw-bold order-total">${formatVND(item.total)}</div>
                                        <a href="#" class="btn btn-default btn-sm order-remove mt-1" title="Xóa"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        `;
                    $('#newOrderTab .pos-order-list').append(html);
                });
                updateSidebarTotal();
            }
        }
    });
