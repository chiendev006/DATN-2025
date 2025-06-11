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
                        <li class="active"><a href="#">Shop Cart</a></li>
                    </ul>
                    <label class="now">SHOP CART</label>
                </div>
            </div>
        </section>

        <section class="default-section shop-cart bg-grey">
            <div class="container">
                <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <ul class="checkout-bar">
                        <li class="active">Shopping Cart</li>
                        <li>Checkout</li>
                        <li>Order Complete</li>
                    </ul>
                </div>
                @php
                    use Illuminate\Support\Facades\Auth;
                    $cart = session('cart', []);
                    $isLoggedIn = Auth::check();
                @endphp

                @if(($isLoggedIn && isset($items) && count($items)) || (!$isLoggedIn && count($cart)))
                <div class="shop-cart-list wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <table class="shop-cart-table">
                        <thead>
                            <tr>
                                <th>ẢNH</th>
                                <th>TÊN</th>
                                <th>SIZE</th>
                                <th>TOPPING</th>
                                <th>GIÁ</th>
                                <th>TĂNG GIẢM</th>
                                <th>TỔNG TIỀN</th>
                                <th>XÓA</th>
                            </tr>
                        </thead>
                       <tbody>
                            @foreach($items as $item)
                            @php
                                if (Auth::check()) {
                                    $product = $item->product;
                                    if (!$product) continue;
                                    $size = $item->size;
                                    $basePrice = $size ? $size->price : $product->price;
                                    $toppingIdString = (string) ($item->topping_id ?? '');
                                    $toppingIds = array_filter(array_map('trim', explode(',', $toppingIdString)));
                                    $toppings = \App\Models\Product_topping::whereIn('id', $toppingIds)->get();
                                    $toppingPrice = $toppings->sum('price');
                                    $unitPrice = $basePrice + $toppingPrice;
                                    $total = $unitPrice * $item->quantity;
                                    $rowKey = $item->product_id . '-' . ($item->size_id ?? '0') . '-' . implode(',', $toppingIds);
                                    $image = $product->image;
                                    $name = $product->name;
                                    $quantity = $item->quantity;
                                } else {
                                    $basePrice = $item->size_price ?? 0;
                                    $toppingPrice = array_sum($item->topping_prices ?? []);
                                    $unitPrice = $basePrice + $toppingPrice;
                                    $total = $unitPrice * $item->quantity;
                                    $toppingIdsForImplode = [];
                                    if (isset($item->topping_ids)) {
                                        if (is_array($item->topping_ids)) {
                                            $toppingIdsForImplode = $item->topping_ids;
                                        } elseif (is_string($item->topping_ids) && $item->topping_ids !== '') {
                                            $toppingIdsForImplode = array_map('intval', array_filter(array_map('trim', explode(',', (string) $item->topping_ids))));
                                        }
                                    }
                                    $rowKey = $item->sanpham_id . '-' . ($item->size_id ?? '0') . '-' . implode(',', $toppingIdsForImplode);

                                    $image = $item->image;
                                    $name = $item->name;
                                    $quantity = $item->quantity;
                                }
                            @endphp
                            <tr data-key="{{ $rowKey }}">
                                <td>
                                    <div class="product-cart">
                                        <img src="{{ url('storage/uploads/' . $image) }}" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <div class="product-cart-title">
                                        <span>{{ $name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="size-info text-center">
                                        @if(Auth::check())
                                            @if($size)
                                                <div>{{ $size->size ?? 'Không rõ' }}</div>
                                                
                                            @else
                                                <div>Không rõ</div>
                                            @endif
                                        @else
                                            @if(isset($item->size_name) && isset($item->size_price))
                                                <div>{{ $item->size_name }}</div>
                                            
                                            @else
                                                <div>{{ $item->size_name ?? 'Không rõ' }}</div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if(Auth::check())
                                        @if($toppings->count())
                                            <ul class="topping-list" style="list-style: none; padding: 0;">
                                                @foreach($toppings as $top)
                                                    <li class="text-center">
                                                        <div>{{ $top->topping }}</div>
                                                        <div class="topping-price">{{ number_format($top->price) }} VND</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span>Không có</span>
                                        @endif
                                    @else
                                        @if(!empty($item->topping_names))
                                            <ul class="topping-list" style="list-style: none; padding: 0;">
                                                @foreach($item->topping_names as $index => $topping)
                                                    <li class="text-center">
                                                        <div>{{ $topping }}</div>
                                                        @if(isset($item->topping_prices[$index]))
                                                            <div class="topping-price">{{ number_format($item->topping_prices[$index]) }} VND</div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span>Không có</span>
                                        @endif
                                    @endif
                                </td>
                                <td><strong>{{ number_format($unitPrice) }} VND</strong></td>
                                <td>
                                    <div class="price-textbox">
                                        <span class="minus-text decrement-btn"><i class="icon-minus"></i></span>
                                        <input type="number" name="quantity" value="{{ $quantity }}"
                                            class="quantity-input" min="1" readonly
                                            data-key="{{ $rowKey }}"
                                            data-product_id="{{ Auth::check() ? $item->product_id : $item->sanpham_id }}"
                                            data-size_id="{{ Auth::check() ? ($item->size_id ?? '0') : ($item->size_id ?? '0') }}"
                                            data-topping_ids="{{ Auth::check() ? implode(',', $toppingIds) : implode(',', $toppingIdsForImplode) }}">
                                        <span class="plus-text increment-btn"><i class="icon-plus"></i></span>
                                    </div>
                                </td>
                                <td class="line-total">{{ number_format($total) }} VND</td>
                                <td class="shop-cart-close">
                                    <i class="icon-cancel-5 remove-item" data-key="{{ $rowKey }}"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   <div class="product-cart-detail">
                <div class="coupon-selection-container">
                    <label class="coupon-label">Chọn mã giảm giá:</label>
                    <div class="coupon-list-wrapper">
                        @forelse($availableCoupons as $coupon)
                            <div class="coupon-card @if(session('coupons') && array_key_exists($coupon->code, session('coupons'))) selected-coupon @endif" data-code="{{ $coupon->code }}">
                                <div class="coupon-header">
                                    <span class="coupon-code">{{ $coupon->code }}</span>
                                    <span class="coupon-discount-type">{{ $coupon->type === 'percent' ? $coupon->discount . '%' : number_format($coupon->discount) . ' VND' }}</span>
                                </div>
                                <div class="coupon-details">
                                    @if($coupon->min_order_value)
                                        <p class="coupon-condition">Đơn hàng tối thiểu: {{ number_format($coupon->min_order_value) }}đ</p>
                                    @endif
                                    @if($coupon->expires_at)
                                        <p class="coupon-expiry">HSD: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}</p>
                                    @endif
                                    @if(!is_null($coupon->used) && !is_null($coupon->usage_limit))
                                        <p class="coupon-usage">Còn lại: {{ $coupon->used }} / {{ $coupon->usage_limit }} lần</p>
                                    @endif
                                </div>
                                <button type="button" class="apply-coupon-btn" data-code="{{ $coupon->code }}">Áp dụng</button>
                                @if(session('coupons') && array_key_exists($coupon->code, session('coupons')))
                                    <button type="button" class="remove-applied-coupon-btn" data-code="{{ $coupon->code }}">Gỡ bỏ</button>
                                @endif
                            </div>
                        @empty
                            <p class="no-coupon-message">Không có mã giảm giá khả dụng nào.</p>
                        @endforelse
                    </div>

        @if($expiredCoupons->count())
            <div class="expired-coupon-section">
                <p class="expired-coupon-title">Mã đã hết hạn:</p>
                <ul class="expired-coupon-list">
                    @foreach($expiredCoupons as $coupon)
                        <li>{{ $coupon->code }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
                </div>

                @php
                    $subtotal = $isLoggedIn
                        ? collect($items ?? [])->sum(function($item) {
                            $product = $item->product;
                            if (!$product) return 0;
                            $size = $item->size;
                            $basePrice = $size ? $size->price : $product->price;
                            $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id ?? '')));
                            $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price');
                            return ($basePrice + $toppingPrice) * $item->quantity;
                        })
                        : collect($cart)->sum(function($item) {
                            $basePrice = $item['size_price'] ?? 0;
                            $toppingPrice = array_sum($item['topping_prices'] ?? []);
                            return ($basePrice + $toppingPrice) * ($item['quantity'] ?? 1);
                        });

                    $coupons = session('coupons', []);
                    $discount = 0;
                    foreach ($coupons as $c) {
                        if ($c['type'] === 'percent') {
                             $discount += ($subtotal * $c['discount']) / 100;
                        } else {
                            $discount += $c['discount'];
                        }
                    }
                    $total = max(0, $subtotal - $discount);
                @endphp

                <div class="cart-total wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms">
                    <div class="cart-total-title">
                        <h5>TỔNG GIỎ HÀNG</h5>
                    </div>
                    <div class="product-cart-total">
                        <small>Tạm tính</small>
                        <span id="cart-subtotal">{{ number_format($subtotal) }} VND</span>
                    </div>
                    <div id="applied-coupons-display">
                        @foreach(session('coupons', []) as $c)
                        <div class="product-cart-total coupon-applied-row" data-code="{{ $c['code'] }}">
                            <small>Mã giảm giá:  <span class="applied-coupon-code">{{ $c['code'] }}</span></small>
                            @php
                                $couponDisplayDiscount = ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
                            @endphp
                            <span class="applied-coupon-discount">-{{ number_format($couponDisplayDiscount) }} VND</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="grand-total">
                        <h5>TỔNG CỘNG <span id="cart-total">{{ number_format($total) }} VND</span></h5>
                    </div>
                    <div class="proceed-check">
                        <a href="{{ route('checkout.index') }}" class="btn-primary-gold btn-medium">TIẾN HÀNH THANH TOÁN</a>
                    </div>
                </div>
                @else
                <div class="text-center wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <p>Giỏ hàng trống.</p>
                    <a href="/shop" class="btn-medium btn-dark-coffee">Tiếp tục mua sắm</a>
                </div>
                @endif
            </div>
        </section>
        </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function logDebug(action, data) {
        console.log(`[${action}]`, data);
    }

    function formatCurrency(amount) {
        if (typeof amount !== 'number') {
            amount = parseFloat(amount);
            if (isNaN(amount)) {
                return 'N/A';
            }
        }
        return amount.toLocaleString('vi-VN') + ' VND';
    }

    function updateUIElements(data) {
        if (data.line_total !== undefined) {
            const row = document.querySelector(`tr[data-key="${data.key}"]`);
            if (row?.querySelector('.line-total')) {
                row.querySelector('.line-total').textContent = formatCurrency(data.line_total);
            }
        }

        if (data.subtotal !== undefined) {
            const subtotalEl = document.getElementById('cart-subtotal');
            if (subtotalEl) subtotalEl.textContent = formatCurrency(data.subtotal);
        }

        if (data.total !== undefined) {
            const totalEl = document.getElementById('cart-total');
            if (totalEl) totalEl.textContent = formatCurrency(data.total);
        }

        const appliedCouponsDisplay = document.getElementById('applied-coupons-display');
        if (appliedCouponsDisplay && data.applied_coupons !== undefined) {
            appliedCouponsDisplay.innerHTML = '';
            let discountHtml = '';
            for (const code in data.applied_coupons) {
                const coupon = data.applied_coupons[code];
                let discountAmount;
                if (coupon.type === 'percent') {
                    const currentSubtotal = parseFloat(document.getElementById('cart-subtotal')?.textContent.replace(/\D/g, '')) || 0;
                    discountAmount = (currentSubtotal * coupon.discount) / 100;
                } else {
                    discountAmount = coupon.discount;
                }
                discountHtml += `
                    <div class="product-cart-total coupon-applied-row" data-code="${coupon.code}">
                        <small>Mã giảm giá (<span class="applied-coupon-code">${coupon.code}</span>)</small>
                        <span class="applied-coupon-discount">- ${formatCurrency(discountAmount)}</span>
                    </div>
                `;
            }
            appliedCouponsDisplay.innerHTML = discountHtml;
        }
        document.querySelectorAll('.remove-applied-coupon-btn').forEach(btn => {
            btn.removeEventListener('click', handleRemoveCouponClick);
        });

        document.querySelectorAll('.coupon-card').forEach(card => {
            card.classList.remove('selected-coupon');
            const removeBtn = card.querySelector('.remove-applied-coupon-btn');
            if (removeBtn) removeBtn.remove();
        });

        if (data.applied_coupons) {
            for (const code in data.applied_coupons) {
                const selectedCouponCard = document.querySelector(`.coupon-card[data-code="${code}"]`);
                if (selectedCouponCard) {
                    selectedCouponCard.classList.add('selected-coupon');
                    let removeBtn = selectedCouponCard.querySelector('.remove-applied-coupon-btn');
                    if (!removeBtn) {
                        removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-applied-coupon-btn';
                        removeBtn.dataset.code = code;
                        removeBtn.textContent = 'Gỡ bỏ';
                        selectedCouponCard.appendChild(removeBtn);
                    }
                    removeBtn.addEventListener('click', handleRemoveCouponClick);
                }
            }
        }
    }

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;

            const row = this.closest('tr');
            const key = this.dataset.key;
            const productId = row.querySelector('.quantity-input').dataset.product_id;
            const sizeId = row.querySelector('.quantity-input').dataset.size_id;
            const toppingIds = row.querySelector('.quantity-input').dataset.topping_ids;

            if (!key && (!productId || sizeId === undefined)) {
                console.error('Missing key or product identifiers for remove item');
                return;
            }

            logDebug('Remove Request', { key, productId, sizeId, toppingIds });
            try {
                const response = await fetch(`/cart/remove/${encodeURIComponent(key)}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        size_id: sizeId,
                        topping_ids: toppingIds ? toppingIds.split(',').map(id => parseInt(id)) : []
                    })
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                logDebug('Remove Response', data);

                if (data.success) {
                    row?.remove();
                    const remainingRows = document.querySelectorAll('.shop-cart-table tbody tr');
                    if (remainingRows.length === 0) {
                        const cartTable = document.querySelector('.shop-cart-list');
                        const cartTotal = document.querySelector('.cart-total');
                        if (cartTable) cartTable.style.display = 'none';
                        if (cartTotal) cartTotal.style.display = 'none';

                        const container = document.querySelector('.container');
                        if (container) {
                            const emptyCart = document.createElement('div');
                            emptyCart.className = 'text-center wow fadeInDown';
                            emptyCart.setAttribute('data-wow-duration', '1000ms');
                            emptyCart.setAttribute('data-wow-delay', '300ms');
                            emptyCart.innerHTML = `
                                <p>Giỏ hàng trống.</p>
                                <a href="/shop" class="btn-medium btn-dark-coffee">Tiếp tục mua sắm</a>
                            `;
                            document.querySelector('section.shop-cart .container').appendChild(emptyCart);
                        }
                    }
                    updateUIElements(data);
                } else {
                    alert(data.message || "Không thể xóa sản phẩm.");
                }
            } catch (error) {
                console.error('Remove Error:', error);
                alert("Lỗi khi xóa sản phẩm. Vui lòng thử lại.");
            }
        });
    });

    async function updateQuantity(input, newQuantity) {
        const key = input.dataset.key;
        const product_id = input.dataset.product_id;
        const size_id = input.dataset.size_id;
        const topping_ids = input.dataset.topping_ids;

        if (!key || !product_id || size_id === undefined) {
            console.error('Missing required data:', { key, product_id, size_id, topping_ids });
            alert("Thiếu thông tin sản phẩm");
            return false;
        }

        const oldQuantity = parseInt(input.value);
        const requestData = {
            key: key,
            quantity: newQuantity,
            product_id: product_id,
            size_id: size_id,
            topping_ids: topping_ids ? topping_ids.split(',').map(id => parseInt(id)) : []
        };

        logDebug('Update Quantity Request', requestData);

        try {
            const response = await fetch('/cart/update', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            logDebug('Update Quantity Response', data);

            if (data.success) {
                input.value = data.quantity;
                updateUIElements(data);
                return true;
            } else {
                input.value = oldQuantity;
                alert(data.message || "Không thể cập nhật số lượng.");
                return false;
            }
        } catch (error) {
            console.error('Update Quantity Error:', error);
            input.value = oldQuantity;
            alert("Lỗi khi cập nhật số lượng. Vui lòng thử lại.");
            return false;
        }
    }

    document.querySelectorAll('.increment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (!input) return;
            const oldValue = parseInt(input.value) || 1;
            updateQuantity(input, oldValue + 1);
        });
    });

    document.querySelectorAll('.decrement-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (!input) return;
            const oldValue = parseInt(input.value) || 1;
            const minValue = parseInt(input.min) || 1;
            if (oldValue > minValue) {
                updateQuantity(input, oldValue - 1);
            }
        });
    });

    async function handleApplyCouponClick(e) {
        e.preventDefault();
        const couponCode = this.dataset.code;

        if (!couponCode) {
            alert('Mã giảm giá không xác định.');
            return;
        }

        const subtotalText = document.getElementById('cart-subtotal')?.textContent;
        const subtotal = parseFloat(subtotalText?.replace(/\D/g, '')) || 0;

        const requestData = {
            code: couponCode,
            subtotal: subtotal
        };

        logDebug('Coupon Apply Request', requestData);

        try {
            const response = await fetch('/cart/apply-coupon', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(requestData)
            });

            const data = await response.json();
            logDebug('Coupon Apply Response', data);

            if (data.success) {
                alert(data.message);
                updateUIElements({
                    subtotal: data.subtotal,
                    discount: data.discount,
                    total: data.total,
                    applied_coupons: data.applied_coupons
                });
            } else {
                alert(data.message || "Mã giảm giá không hợp lệ");
            }
        } catch (error) {
            console.error('Coupon Apply Error:', error);
            alert("Lỗi khi áp dụng mã giảm giá. Vui lòng thử lại.");
        }
    }

    document.querySelectorAll('.apply-coupon-btn').forEach(btn => {
        btn.addEventListener('click', handleApplyCouponClick);
    });

    document.querySelectorAll('.coupon-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.classList.contains('apply-coupon-btn') && !e.target.classList.contains('remove-applied-coupon-btn')) {
                this.querySelector('.apply-coupon-btn')?.click();
            }
        });
    });

    async function handleRemoveCouponClick(e) {
        e.preventDefault();
        const couponCodeToRemove = this.dataset.code;

        if (!confirm('Bạn có muốn hủy áp dụng mã giảm giá này?')) return;

        try {
            const response = await fetch('/cart/remove-coupon', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ code: couponCodeToRemove })
            });
            const data = await response.json();
            logDebug('Remove Coupon Response', data);

            if (data.success) {
                alert(data.message);
                updateUIElements({
                    subtotal: data.subtotal,
                    discount: data.discount,
                    total: data.total,
                    applied_coupons: data.applied_coupons
                });
            } else {
                alert(data.message || "Không thể hủy mã giảm giá.");
            }
        } catch (error) {
            console.error('Remove Coupon Error:', error);
            alert("Lỗi khi hủy mã giảm giá. Vui lòng thử lại.");
        }
    }

    document.querySelectorAll('.remove-applied-coupon-btn').forEach(btn => {
        btn.addEventListener('click', handleRemoveCouponClick);
    });
});
</script>
<style>
    .coupon-list-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: space-between;
}

.coupon-card {
    width: calc(25% - 10px); /* 4 cái trên 1 hàng */
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px;
    background-color: #f9f9f9;
    font-size: 13px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.coupon-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    margin-bottom: 6px;
}

.coupon-details p {
    margin: 3px 0;
    font-size: 12px;
}

.apply-coupon-btn,
.remove-applied-coupon-btn {
    font-size: 12px;
    margin-top: 5px;
    padding: 4px 8px;
    border: none;
    border-radius: 4px;
    background-color:dc3545;
    color: white;
    cursor: pointer;
}

.remove-applied-coupon-btn {
    background-color: #dc3545;
}

.selected-coupon {
    border-color: #28a745;
    background-color: #e9fbe9;
}

</style>
@endsection