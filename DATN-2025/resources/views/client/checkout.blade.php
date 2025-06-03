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
            <h5 class="checkout-heading">ĐƠN HÀNG CỦA BẠN</h5>
            <div class="shop-checkout-title">
                <div class="checkout-row header">
                    <h6>SẢN PHẨM</h6>
                    <h6>THÀNH TIỀN</h6>
                </div>
            </div>
            <div class="shop-checkout-body">
                @php
                    $subtotal = 0;
                    $items = Auth::check() ? $items : $cart;
                @endphp

                @foreach ($items as $item)
                    @php
                        if (Auth::check()) {
                            $product = $item->product;
                            if (!$product) continue;

                            $productName = $product->name ?? 'Sản phẩm không tên';
                            $productPrice = $product->price ?? 0;
                            $sizePrice = $item->size ? ($item->size->price ?? 0) : 0;

                            $toppingPrice = 0;
                            $toppingText = '';

                            if (!empty($item->topping_id)) {
                                $toppingIds = array_filter(array_map('intval', explode(',', $item->topping_id)));
                                if (!empty($toppingIds)) {
                                    $toppings = \App\Models\Topping::whereIn('id', $toppingIds)->get();
                                    $toppingText = $toppings->pluck('name')->implode(', ');
                                    $toppingPrice = $toppings->sum('price');
                                }
                            }

                            $unitPrice = $productPrice + $sizePrice + $toppingPrice;
                            $totalItem = $unitPrice * $item->quantity;
                            $subtotal += $totalItem;
                        } else {
                            $productName = $item['name'] ?? 'Sản phẩm không tên';
                            $quantity = $item['quantity'] ?? 1;
                            $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                            $totalItem = $unitPrice * $quantity;
                            $subtotal += $totalItem;
                        }
                    @endphp
                    <div class="checkout-row">
                        <div>
                            <strong>{{ $productName }}</strong> x {{ Auth::check() ? $item->quantity : $quantity }}
                            @if (($toppingText ?? false) || (!empty($item['topping_names'] ?? [])))
                                <div class="topping-text">Topping: {{ $toppingText ?? implode(', ', $item['topping_names']) }}</div>
                            @endif
                        </div>
                        <div>{{ number_format($totalItem) }} đ</div>
                    </div>
                @endforeach

                @php
                    $coupons = session('coupons', []);
                    $discount = 0;
                    foreach ($coupons as $coupon) {
                        $discount += $coupon['type'] === 'percent'
                            ? $subtotal * $coupon['discount'] / 100
                            : $coupon['discount'];
                    }
                    $total = max(0, $subtotal - $discount);

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

                    $totalWithShipping = $total + $shippingFee;
                @endphp

                <hr>
                <div class="checkout-row">
                    <div>Tạm tính</div>
                    <div>{{ number_format($subtotal) }} đ</div>
                </div>
                @if ($discount > 0)
                    <div class="checkout-row">
                        <div>Giảm giá</div>
                        <div>-{{ number_format($discount) }} đ</div>
                    </div>
                @endif
                <div class="checkout-row">
                    <div>Phí vận chuyển</div>
                    <div id="shipping-fee-display-right">
                        {{ $shippingFee > 0 ? number_format($shippingFee) . ' đ' : '0 đ' }}
                    </div>
                </div>
                <hr>
                <div class="checkout-row total">
                    <div><strong>Tổng cộng</strong></div>
                    <div class="price-big" id="total-with-shipping">{{ number_format($totalWithShipping) }} đ</div>
                </div>
            </div>
        </div>
    </div>
                </div>

            </div>
        </section>
    </div>
</main>
<script>
    const districtSelect = document.getElementById("district-select");
    const shippingCostElement = document.getElementById("shipping-fee-display");
    const shippingFeeDisplayRight = document.getElementById("shipping-fee-display-right");
    const totalWithShippingElement = document.getElementById("total-with-shipping");
    const totalBeforeShipping = {{ $total }};
    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN') + " đ";
    }
    districtSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const shippingCost = parseInt(selectedOption.getAttribute("data-ship")) || 0;
        shippingCostElement.textContent = formatCurrency(shippingCost);
        shippingFeeDisplayRight.textContent = formatCurrency(shippingCost);
        const total = totalBeforeShipping + shippingCost;
        totalWithShippingElement.textContent = formatCurrency(total);
    });
</script>
@endsection