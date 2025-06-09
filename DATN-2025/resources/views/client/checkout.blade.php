@extends('layout2')
@section('main')
@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    $isLoggedIn = Auth::check();
    Log::info('Rendering checkout page', [
        'is_logged_in' => $isLoggedIn,
        'session_cart' => session()->get('cart', []),
        'session_coupons' => session()->get('coupons', []),
        'items' => $items,
        'cart' => $cart,
        'districts_count' => count($districts)
    ]);
@endphp
<main>
    <div class="main-part">
        <section class="breadcrumb-nav">
            <div class="container">
                <div class="breadcrumb-nav-inner">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/shop">Shop</a></li>
                        <li class="active"><a href="#">Shop Checkout</a></li>
                    </ul>
                    <label class="now">SHOP CHECKOUT</label>
                </div>
            </div>
        </section>

        <section class="default-section shop-checkout bg-grey">
            <div class="container">
                <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <ul class="checkout-bar">
                        <li class="done-proceed">Shopping Cart</li>
                        <li class="active">Checkout</li>
                        <li>Order Complete</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-7 col-sm-7 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="shop-checkout-left">
                            @if(!$isLoggedIn)
                            <h6>Returning customer? Click here to <a href="{{ route('login') }}">login</a></h6>
                            @endif
                            <form id="checkout-form" class="form" method="POST" action="{{ route('checkout.process') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Thông tin đặt hàng</h5>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" name="name" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" placeholder="Họ và tên" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" name="phone_raw" value="{{ old('phone_raw', Auth::check() ? Auth::user()->phone : '') }}" placeholder="Số điện thoại" required>
                                        @error('phone_raw')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label>Chọn huyện (tỉnh Hải Phòng) <span class="text-danger">*</span></label>
                                        <select name="district" id="district-select" class="form-control" required>
                                            <option value="" disabled selected>-- Chọn huyện --</option>
                                            @foreach($districts as $districtOption)
                                                <option value="{{ $districtOption->id }}" data-ship="{{ $districtOption->shipping_fee }}" {{ old('district') == $districtOption->id ? 'selected' : '' }}>
                                                    {{ $districtOption->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('district')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label>Địa chỉ chi tiết (Số nhà, tên đường, ngõ,...) <span class="text-danger">*</span></label>
                                        <input type="text" name="address_detail" value="{{ old('address_detail') }}" placeholder="Nhập số nhà, tên đường..." required class="form-control">
                                        @error('address_detail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <p>Phí vận chuyển hiện tại: <strong id="shipping-fee-display">Chưa chọn huyện</strong></p>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5>Phương thức thanh toán</h5>
                                        <div class="payment-methods">
                                            <div class="payment-method" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <label style="display: flex; align-items: center; gap: 10px;">
                                                    <input type="radio" name="payment_method" value="cash" required {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                                                    <img src="{{ url('asset') }}/images/cod.png" alt="COD" style="height: 24px;">
                                                    <span>Thanh toán khi nhận hàng (COD)</span>
                                                </label>
                                            </div>

                                            <div class="payment-method" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <label style="display: flex; align-items: center; gap: 10px;">
                                                    <input type="radio" name="payment_method" value="banking" {{ old('payment_method') === 'banking' ? 'checked' : '' }}>
                                                    <img src="{{ url('asset') }}/images/vnpay.jpg" alt="VNPAY" style="height: 24px;">
                                                    <span>Chuyển khoản ngân hàng (qua VNPAY)</span>
                                                </label>
                                            </div>

                                            @error('payment_method')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="terms-conditions">
                                            <label>
                                                <input type="checkbox" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                                                <span>Tôi đồng ý với các điều khoản và điều kiện *</span>
                                            </label>
                                            @error('terms')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="redirect" value="1">

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn-large btn-primary-gold">Đặt hàng</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="shop-checkout-right">
                            <div class="shop-checkout-box">
                                <h5 style="color: #959393;">ĐƠN HÀNG CỦA BẠN</h5>
                                <div class="shop-checkout-title">
                                    <h6 style="color: #959393;">SẢN PHẨM <span>THÀNH TIỀN</span></h6>
                                </div>
                                <div class="shop-checkout-row">
                                    @php
                                        // subtotal được tính từ controller
                                        $displayItems = Auth::check() ? $items : $cart;
                                    @endphp

                                    @if(empty($displayItems))
                                        <p>Giỏ hàng của bạn đang trống. <a href="{{ route('shop') }}">Quay lại cửa hàng</a></p>
                                    @else
                                        @foreach($displayItems as $item)
                                            @php
                                                $name = '';
                                                $quantity = 0;
                                                $sizeName = null;
                                                $toppingNames = [];
                                                $itemTotal = 0;
                                                $unitPrice = 0;

                                                if (Auth::check()) {
                                                    $product = $item->product;
                                                    if (!$product) {
                                                        Log::warning('Skipping display item due to missing product', ['item_id' => $item->id]);
                                                        continue;
                                                    }

                                                    $productPrice = $product->price ?? 0;
                                                    $sizePrice = $item->size ? ($item->size->price ?? 0) : 0;
                                                    $sizeName = $item->size ? $item->size->size : null;

                                                    $toppingIds = !empty($item->topping_id) ? array_filter(array_map('trim', explode(',', $item->topping_id))) : [];
                                                    $toppingPrice = 0;

                                                    if (!empty($toppingIds)) {
                                                        $toppingsData = \App\Models\Product_topping::whereIn('id', $toppingIds)->get();
                                                        $toppingPrice = $toppingsData->sum('price');
                                                        $toppingNames = $toppingsData->pluck('topping')->toArray();
                                                    }

                                                    $unitPrice = $sizePrice + $toppingPrice;
                                                    $itemTotal = $unitPrice * $item->quantity;

                                                    $name = $product->name;
                                                    $quantity = $item->quantity;
                                                } else {
                                                    $productModel = \App\Models\Sanpham::find($item['sanpham_id']);
                                                    if (!$productModel) {
                                                        Log::warning('Skipping guest cart item due to missing product', ['sanpham_id' => $item['sanpham_id']]);
                                                        continue;
                                                    }

                                                    $basePrice = 0;
                                                    if(isset($item['size_id'])) {
                                                        $sizeAttr = DB::table('product_attributes')->where('id', $item['size_id'])->first();
                                                        $basePrice = $sizeAttr->price ?? 0;
                                                    }

                                                    $toppingIds = !empty($item['topping_ids']) ? array_filter(array_map('trim', explode(',', $item['topping_ids']))) : [];
                                                    $toppingTotal = !empty($toppingIds) ? \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price') : 0;

                                                    $unitPrice = $basePrice + $toppingTotal;
                                                    $itemTotal = $unitPrice * ($item['quantity'] ?? 1);

                                                    $name = $item['name'] ?? $productModel->name;
                                                    $quantity = $item['quantity'] ?? 1;
                                                    $sizeName = $item['size_name'] ?? null;
                                                    $toppingNames = !empty($toppingIds) ? \App\Models\Product_topping::whereIn('id', $toppingIds)->pluck('topping')->toArray() : [];
                                                }

                                                $desc = [];
                                                if (!empty($sizeName)) $desc[1] = 'Size: ' . $sizeName;
                                                if (!empty($toppingNames)) $desc[2] = 'Topping: ' . implode(', ', $toppingNames);
                                            @endphp
                                            <hr style="margin-top: 0px ">
                                            <div class="row" >
                                                <div class="col-xs-2">
                                                    <small>x{{ $quantity }}</small>
                                                </div>
                                                <div class="col-xs-7">
                                                    <p><strong>{{ $name }}</strong></p>
                                                    @if(isset($desc[1]))
                                                        <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[1] }}</p>
                                                    @endif
                                                    @if(isset($desc[2]))
                                                        <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[2] }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-xs-3 text-right">
                                                    <p><strong>{{ number_format($itemTotal) }} VND</strong></p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="checkout-total">
                                    <h6>Tạm tính: <span>{{ number_format($subtotal) }} VND</span></h6>
                                </div>

                                @php
                                    // $discount và $appliedCoupons được truyền từ controller
                                    $shippingFee = 0;
                                    if (old('district')) {
                                        $selectedDistrict = $districts->firstWhere('id', old('district'));
                                        if ($selectedDistrict) {
                                            $shippingFee = $selectedDistrict->shipping_fee;
                                        }
                                    }
                                    // $total được tính bằng tổng tạm tính + phí ship - tổng giảm giá
                                    $total = max(0, $subtotal + $shippingFee - $discount);
                                @endphp

                                {{-- === THAY ĐỔI TẠI ĐÂY: HIỂN THỊ CHI TIẾT MÃ GIẢM GIÁ === --}}
                                @if(!empty($appliedCoupons))
                                    @foreach($appliedCoupons as $coupon)
                                        @php
                                            // Tính toán lại giá trị của từng coupon để hiển thị
                                            $couponValue = ($coupon['type'] === 'percent')
                                                ? ($subtotal * $coupon['discount'] / 100)
                                                : $coupon['discount'];
                                        @endphp
                                        <div class="checkout-total">
                                            <h6>Mã giảm giá ({{ $coupon['code'] }}): <span>-{{ number_format($couponValue) }} VND</span></h6>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- === KẾT THÚC THAY ĐỔI === --}}

                                <div class="checkout-total">
                                    <h6>Phí vận chuyển: <span id="shipping-fee-display-right">{{ $shippingFee > 0 ? number_format($shippingFee) . ' đ' : '0 đ' }}</span></h6>
                                </div>

                                <div class="checkout-total">
                                    <h6>Tổng cộng: <span class="price-big" id="total-with-shipping">{{ number_format($total) }} VND</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<style>
.text-danger {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.mt-4 {
    margin-top: 2rem;
}

.payment-methods {
    margin: 1rem 0;
}

.payment-method {
    margin: 1rem 0;
}

.payment-method label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.payment-method input[type="radio"] {
    margin-right: 0.5rem;
}

.terms-conditions {
    margin: 1rem 0;
}

.terms-conditions label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.terms-conditions input[type="checkbox"] {
    margin-right: 0.5rem;
}

.shop-checkout-row p {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0.5rem 0;
}

.shop-checkout-row p small {
    margin: 0 0.5rem;
    color: #666;
}

.shop-checkout-row p strong {
    color: #000;
}

.checkout-total h6 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
}

.price-big {
    font-size: 1.25rem;
    color: #c7a17a;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const districtSelect = document.getElementById("district-select");
    const shippingCostElement = document.getElementById("shipping-fee-display");
    const shippingFeeDisplayRight = document.getElementById("shipping-fee-display-right");
    const totalWithShippingElement = document.getElementById("total-with-shipping");
    
    // Các giá trị này được truyền từ PHP vào Javascript
    const subtotal = parseFloat({{ $subtotal }});
    const discount = parseFloat({{ $discount }}); // Tổng số tiền giảm giá

    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN') + " đ";
    }

    function updateShippingAndTotal() {
        const selectedOption = districtSelect.options[districtSelect.selectedIndex];
        const shippingCost = parseInt(selectedOption.getAttribute("data-ship")) || 0;

        shippingCostElement.textContent = formatCurrency(shippingCost);
        if (shippingFeeDisplayRight) {
            shippingFeeDisplayRight.textContent = formatCurrency(shippingCost);
        }

        // Tổng cuối cùng = Tạm tính + Phí vận chuyển - Tổng giảm giá
        const newTotal = subtotal + shippingCost - discount;
        if (totalWithShippingElement) {
            totalWithShippingElement.textContent = formatCurrency(Math.max(0, newTotal));
        }

        console.log('Shipping and total updated:', {
            shipping_cost: shippingCost,
            new_total: newTotal
        });
    }

    districtSelect.addEventListener("change", updateShippingAndTotal);
    // Chạy lần đầu khi tải trang để cập nhật giá trị ban đầu nếu có old('district')
    updateShippingAndTotal();

    document.getElementById("checkout-form").addEventListener("submit", function (event) {
        if (!districtSelect.value) {
            event.preventDefault();
            alert("Vui lòng chọn huyện.");
        }
    });
});
</script>
@endsection