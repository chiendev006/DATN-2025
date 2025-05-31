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
                                        <textarea name="address" placeholder="Địa chỉ giao hàng" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
                            <div class="shop-checkout-box">
                                <h5>ĐƠN HÀNG CỦA BẠN</h5>
                                <div class="shop-checkout-title">
                                    <h6>SẢN PHẨM <span>THÀNH TIỀN</span></h6>
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
                                            if (!empty($sizeName)) $desc[] = 'Size: ' . $sizeName;
                                            if (!empty($toppingNames)) $desc[] = 'Topping: ' . implode(', ', $toppingNames);
                                            $descStr = $desc ? ' (' . implode(', ', $desc) . ')' : '';
                                        @endphp
                                        <p>
                                            <span>{{ $name }}{!! $descStr !!}</span>
                                            <small>x{{ $quantity }}</small>
                                            <strong>{{ number_format($total) }} VND</strong>
                                        </p>
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
                                    $total = max(0, $subtotal - $discount);
                                @endphp

                                @if($discount > 0)
                                    <div class="checkout-total">
                                        <h6>Giảm giá: <span>-{{ number_format($discount) }} VND</span></h6>
                                    </div>
                                @endif

                                <div class="checkout-total">
                                    <h6>Phí vận chuyển: <span>Miễn phí</span></h6>
                                </div>

                                <div class="checkout-total">
                                    <h6>Tổng cộng: <span class="price-big">{{ number_format($total) }} VND</span></h6>
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
let provinces = {};
let districts = {};

fetch('/data/tinh_tp.json')
    .then(res => res.json())
    .then(data => {
        provinces = data;
        const provinceSelect = document.getElementById('province');
        Object.entries(data).forEach(([code, info]) => {
            const opt = document.createElement('option');
            opt.value = info.name;
            opt.text = info.name;
            provinceSelect.appendChild(opt);
        });
    });

document.getElementById('province').addEventListener('change', function () {
    const provinceCode = Object.keys(provinces).find(k => provinces[k].name === this.value);
    fetch('/data/quan_huyen.json')
        .then(res => res.json())
        .then(data => {
            districts = data;
            const districtSelect = document.getElementById('district');
            districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
            Object.entries(data)
                .filter(([code, info]) => info.parent_code === provinceCode)
                .forEach(([code, info]) => {
                    const opt = document.createElement('option');
                    opt.value = info.name;
                    opt.text = info.name;
                    districtSelect.appendChild(opt);
                });
            fetchShippingFee(); // cập nhật phí khi tỉnh thay đổi
        });
});

document.getElementById('district').addEventListener('change', fetchShippingFee);
document.getElementById('address').addEventListener('input', fetchShippingFee);

function fetchShippingFee() {
    const province = document.getElementById('province').value;
    const district = document.getElementById('district').value;
    const address = document.getElementById('address').value;

    if (!province || !district || !address) {
        document.getElementById('shipping-fee').innerText = 'Vui lòng chọn đầy đủ địa chỉ';
        return;
    }

    fetch(`/calculate-fee?province=${province}&district=${district}&address=${encodeURIComponent(address)}&weight=1000&value=300000&transport=road&deliver_option=none`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.fee) {
                document.getElementById('shipping-fee').innerText = data.fee.fee.toLocaleString() + ' VND';
            } else {
                document.getElementById('shipping-fee').innerText = 'Không hỗ trợ giao';
            }
        })
        .catch(() => {
            document.getElementById('shipping-fee').innerText = 'Lỗi tính phí giao hàng';
        });
}
</script>


@endsection