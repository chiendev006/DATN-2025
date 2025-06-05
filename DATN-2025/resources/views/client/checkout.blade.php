@extends('layout2')
@section('main')
@php
    use Illuminate\Support\Facades\Auth;
    $isLoggedIn = Auth::check();
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
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Họ và tên" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Số điện thoại" required>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                      <div class="col-md-12">
                                        <label>Chọn huyện (tỉnh Hải Phòng) <span class="text-danger">*</span></label>
                                        <select name="district" id="district-select" class="form-control" required>
                                            <option value="" disabled selected>-- Chọn huyện --</option>
                                            <option value="Lê Chân" data-ship="10000" {{ old('district') == 'Lê Chân' ? 'selected' : '' }}>An Lão</option>
                                            <option value="Ngô Quyền" data-ship="12000" {{ old('district') == 'Ngô Quyền' ? 'selected' : '' }}>Kiến An</option>
                                            <option value="Hồng Bàng" data-ship="15000" {{ old('district') == 'Hồng Bàng' ? 'selected' : '' }}>Hải An</option>
                                            <option value="Kiến An" data-ship="18000" {{ old('district') == 'Kiến An' ? 'selected' : '' }}>Ngô Quyền</option>
                                            <option value="Dương Kinh" data-ship="20000" {{ old('district') == 'Dương Kinh' ? 'selected' : '' }}>Ngô Quyền</option>
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
                                                    <input type="radio" name="payment_method" value="cash" required>
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
                                                <input type="checkbox" name="terms" required>
                                                <span>Tôi đồng ý với các điều khoản và điều kiện *</span>
                                            </label>
                                            @error('terms')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden input để xác định redirect (nếu dùng JS cũng có thể xử lý redirect này tự động sau khi form gửi) -->
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
                            <div  class="shop-checkout-box">
                                <h5 style="color: #959393;">ĐƠN HÀNG CỦA BẠN</h5>
                                <div class="shop-checkout-title">
                                    <h6 style="color: #959393;">SẢN PHẨM <span>THÀNH TIỀN</span></h6>
                                </div>
                                <div class="shop-checkout-row">
                                    @php
                                        $subtotal = 0;
                                        $items = Auth::check() ? $items : $cart;
                                    @endphp

                                    @foreach($items as $item)
                                        @php
                                            if (Auth::check()) {
                                                $product = $item->product;
                                                if (!$product) continue;

                                                $productPrice = $product->price ?? 0;
                                                $sizePrice = $item->size ? ($item->size->price ?? 0) : 0;

                                                $sizeName = $item->size ? $item->size->size : null;

                                                $toppingNames = [];
                                                if (!empty($item->topping_id)) {
                                                    $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id)));
                                                    if (!empty($toppingIds)) {
                                                        $toppingNames = \App\Models\Product_topping::whereIn('id', $toppingIds)->pluck('topping')->toArray();
                                                    }
                                                }

                                                $toppingPrice = 0;
                                                if (!empty($toppingIds)) {
                                                    $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price');
                                                }

                                                $unitPrice = $productPrice + $sizePrice + $toppingPrice;
                                                $total = $unitPrice * $item->quantity;
                                                $subtotal += $total;

                                                $name = $product->name;
                                                $quantity = $item->quantity;
                                            } else {
                                                $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                                                $total = $unitPrice * ($item['quantity'] ?? 1);
                                                $subtotal += $total;

                                                $name = $item['name'];
                                                $quantity = $item['quantity'] ?? 1;
                                                $sizeName = $item['size_name'] ?? null;
                                                $toppingNames = $item['topping_names'] ?? [];
                                            }
                                            // Tạo chuỗi mô tả size và topping
                                            $desc = [];
                                            if (!empty($sizeName)) $desc[1] = 'Size: ' . $sizeName;
                                            if (!empty($toppingNames)) $desc[2] = 'Topping: ' . implode(', ', $toppingNames);
                                        @endphp
                                          <hr style="margin-top: 0px ">
                                        <div class="row" ></div>
                                            <small>x{{ $quantity }}</small>
                                        <div> <p><Strong>{{ $name }}</Strong></p>
                                        @if(isset($desc[1]))
                                            <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[1] }}</p>
                                        @endif

                                        @if(isset($desc[2]))
                                            <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[2] }}</p>
                                        @endif

                                       <div> <p style="justify-self: end;">
                                            <strong>{{ number_format($total) }} VND</strong>
                                        </p></div>

                                        </div>

                                    @endforeach
                                </div>

                                <div class="checkout-total">
                                    <h6>Tạm tính: <span>{{ number_format($subtotal) }} VND</span></h6>
                                </div>

                                @php
                                    $coupons = session('coupons', []);
                                    $discount = 0;
                                    foreach ($coupons as $coupon) {
                                        $discount += ($coupon['type'] === 'percent')
                                            ? ($subtotal * $coupon['discount'] / 100)
                                            : $coupon['discount'];
                                    }
                                      $shippingFee = 0;
                    if (old('district')) {
                        $shippingFees = [
                            'Lê Chân' => 10000,
                            'Ngô Quyền' => 12000,
                            'Hồng Bàng' => 15000,
                            'Kiến An' => 18000,
                            'Dương Kinh' => 20000,
                        ];
                        $shippingFee = $shippingFees[old('district')] ?? 0;
                    }

                                    $total = max(0, $subtotal + $shippingFee - $discount);
                                @endphp

                                @if($discount > 0)
                                    <div class="checkout-total">
                                        <h6>Giảm giá: <span>-{{ number_format($discount) }} VND</span></h6>
                                    </div>
                                @endif

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
    const totalBeforeShipping = {{ $subtotal }} - {{ $discount }};
    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN') + " đ";
    }
    districtSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const shippingCost = parseInt(selectedOption.getAttribute("data-ship")) || 0;
        shippingCostElement.textContent = formatCurrency(shippingCost);
        if (shippingFeeDisplayRight) shippingFeeDisplayRight.textContent = formatCurrency(shippingCost);
        if (totalWithShippingElement) totalWithShippingElement.textContent = formatCurrency(totalBeforeShipping + shippingCost);
    });
    // Gọi khi load trang nếu đã có huyện được chọn
    const selectedOption = districtSelect.options[districtSelect.selectedIndex];
    const shippingCost = selectedOption ? parseInt(selectedOption.getAttribute("data-ship")) || 0 : 0;
    shippingCostElement.textContent = formatCurrency(shippingCost);
    if (shippingFeeDisplayRight) shippingFeeDisplayRight.textContent = formatCurrency(shippingCost);
    if (totalWithShippingElement) totalWithShippingElement.textContent = formatCurrency(totalBeforeShipping + shippingCost);
});
</script>

@endsection













