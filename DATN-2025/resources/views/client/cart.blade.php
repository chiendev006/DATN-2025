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

        <!-- Start Shop Cart -->
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
            // Đảm bảo $item->topping_id là chuỗi trước khi explode
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

            // **ĐIỂM SỬA LỖI TRONG BLADE VIEW:**
            // Đảm bảo $item->topping_ids là một mảng trước khi implode
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
                        <div class="size-price">{{ number_format($size->price) }} VND</div>
                    @else
                        <div>Không rõ</div>
                    @endif
                @else
                    @if(isset($item->size_name) && isset($item->size_price))
                        <div>{{ $item->size_name }}</div>
                        <div class="size-price">{{ number_format($item->size_price) }} VND</div>
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
                        <div class="cupon-part">
                            <input type="text" name="coupon_code" placeholder="Mã giảm giá">
                        </div>
                        <a href="#" class="btn-medium btn-dark-coffee" id="apply-coupon">Áp dụng mã</a>
                        <a href="#" class="btn-medium btn-skin pull-right" id="update-cart">Cập nhật giỏ hàng</a>
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
                        $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
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
                    @foreach($coupons as $c)
                    <div class="product-cart-total">
                        <small>Mã giảm giá ({{ $c['code'] }})</small>
                        <span>-{{ $c['type'] === 'percent' ? number_format($subtotal * $c['discount'] / 100) : number_format($c['discount']) }} VND</span>
                    </div>
                    @endforeach
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
        <!-- End Shop Cart -->
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
        return Number(amount).toLocaleString('vi-VN') + ' VND';
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
    }

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('Xóa sản phẩm?')) return;

            const row = this.closest('tr');
            const key = this.dataset.key;
            if (!key) {
                console.error('Missing key for remove item');
                return;
            }
            logDebug('Remove Request', { key });
            try {
                const response = await fetch(`/cart/remove/${encodeURIComponent(key)}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                logDebug('Remove Response', data);
                if (data.success) {
                    row?.remove();
                    updateUIElements(data);
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
                            container.appendChild(emptyCart);
                        }
                    }
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

        logDebug('Update Request', requestData);

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
            logDebug('Update Response', data);

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
            console.error('Update Error:', error);
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

    document.getElementById('apply-coupon')?.addEventListener('click', async function(e) {
        e.preventDefault();
        const couponInput = document.querySelector('input[name="coupon_code"]');
        const couponCode = couponInput?.value;

        if (!couponCode) {
            alert('Vui lòng nhập mã giảm giá');
            return;
        }

        const requestData = { code: couponCode };
        logDebug('Coupon Request', requestData);

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
            logDebug('Coupon Response', data);

            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || "Mã giảm giá không hợp lệ");
            }
        } catch (error) {
            console.error('Coupon Error:', error);
            alert("Lỗi khi áp dụng mã giảm giá. Vui lòng thử lại.");
        }
    });
});
</script>
@endsection
