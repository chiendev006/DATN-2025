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

                         <div class="row mb-4">
                                    <div class="col-12">
                                        <h5>Thông tin đặt hàng</h5>
                                    </div>

                                    <!-- Name & Phone -->
                                    <div class="col-md-6 mb-3">
                                         <label>Họ tên: <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" placeholder="Họ và tên" required class="form-control @error('name') is-invalid @enderror" style="height: 45px; border-radius: 30px;">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                          <label>Số điện thoại: <span class="text-danger">*</span></label>
                                        <input type="text" name="phone_raw" value="{{ old('phone_raw', Auth::check() ? Auth::user()->phone : '') }}" placeholder="Số điện thoại (10-11 số)" required class="form-control @error('phone_raw') is-invalid @enderror" style="height: 45px; border-radius: 30px;">
                                        @error('phone_raw')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-12 mb-3">
                                          <label>Email: <span class="text-muted">(Không bắt buộc)</span></label>
                                        <input type="email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" placeholder="Email" class="form-control @error('email') is-invalid @enderror" style="height: 45px; border-radius: 30px;">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- District & Address -->
                                    <div class="col-md-6 mb-3">
                                        <label>Chọn huyện (tỉnh Hải Phòng) <span class="text-danger">*</span></label>
                                        <select name="district" id="district-select" class="form-control @error('district') is-invalid @enderror" style="height: 45px; border-radius: 30px;">
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

                                    <div class="col-md-6 mb-3">
                                        <label>Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                        <input type="text" name="address_detail" value="{{ old('address_detail') }}" placeholder="Nhập số nhà, tên đường..." class="form-control @error('address_detail') is-invalid @enderror" style="height: 45px; border-radius: 30px;">
                                        @error('address_detail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p>Phí vận chuyển hiện tại: <strong id="shipping-fee-display">Chưa chọn huyện</strong></p>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label>Ghi chú đơn hàng <span class="text-muted">(Không bắt buộc)</span></label>
                                        <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3" placeholder="Nhập ghi chú cho đơn hàng (nếu có)">{{ old('note') }}</textarea>
                                        @error('note')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5>Phương thức thanh toán <span class="text-danger">*</span></h5>
                                        <div class="payment-methods @error('payment_method') is-invalid @enderror">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" value="cash" {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <img src="{{ url('asset') }}/images/cod.png" alt="COD" style="height: 24px;">
                                                    Thanh toán khi nhận hàng (COD)
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" value="banking" {{ old('payment_method') === 'banking' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <img src="{{ url('asset') }}/images/vnpay.jpg" alt="VNPAY" style="height: 24px;">
                                                    Chuyển khoản ngân hàng (qua VNPAY)
                                                </label>
                                            </div>
                                            @error('payment_method')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="form-check @error('terms') is-invalid @enderror">
                                            <input class="form-check-input" type="checkbox" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                            <label class="form-check-label">Tôi đồng ý với các điều khoản và điều kiện <span class="text-danger">*</span></label>
                                        </div>
                                        @error('terms')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="redirect" value="1">
                                <div style="margin-top: 30px;" class="row mb-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">Đặt hàng</button>
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
                                        $displayItems = Auth::check() ? $items : $cart;
                                    @endphp

                                    @if(empty($displayItems))
                                        <p>Giỏ hàng của bạn đang trống. <a href="{{ route('shop.index') }}">Quay lại cửa hàng</a></p>
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
                                                <div class="col-xs-7">
                                                    <p><strong>{{ $name }} x{{ $quantity }} </strong></p>
                                                    @if(isset($desc[1]))
                                                        <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[1] }}</p>
                                                    @endif
                                                    @if(isset($desc[2]))
                                                        <p style="color: #666; font-size: 0.8em; font-weight: bold;">{{ $desc[2] }}</p>
                                                    @endif
                                                </div>
                                                <div style="margin-left: 30px;" class="col-xs-4 text-right">
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
                                    $shippingFee = 0;
                                    if (old('district')) {
                                        $selectedDistrict = $districts->firstWhere('id', old('district'));
                                        if ($selectedDistrict) {
                                            $shippingFee = $selectedDistrict->shipping_fee;
                                        }
                                    }
                                    $total = max(0, $subtotal + $shippingFee - $discount);
                                @endphp

                                @if(!empty($appliedCoupons))
                                    @foreach($appliedCoupons as $coupon)
                                        @php
                                            $couponValue = ($coupon['type'] === 'percent')
                                                ? ($subtotal * $coupon['discount'] / 100)
                                                : $coupon['discount'];
                                        @endphp
                                        <div class="checkout-total">
                                            <h6>Mã giảm giá ({{ $coupon['code'] }}): <span>-{{ number_format($couponValue) }} VND</span></h6>
                                        </div>
                                    @endforeach
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
    display: block;
}

.text-muted {
    color: #6c757d;
    font-size: 0.875rem;
}

.mt-4 {
    margin-top: 2rem;
}

.payment-methods {
    margin: 1rem 0;
}

.payment-methods .form-check {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.payment-methods .form-check:hover {
    background-color: rgba(199, 161, 122, 0.1);
}

.payment-methods .form-check-input {
    margin-right: 0.75rem;
    margin-top: 0;
}

.payment-methods .form-check-label {
    display: flex;
    align-items: center;
    margin: 0;
    cursor: pointer;
    font-weight: 500;
}

.payment-methods img {
    margin-right: 0.5rem;
    vertical-align: middle;
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
    color: #959393;
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

/* Validation styles */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.form-control.is-invalid {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='m5.8 4.6 2.4 2.4m0-2.4L5.8 7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.payment-methods.is-invalid {
    border: 1px solid #dc3545;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: rgba(220, 53, 69, 0.05);
}

.form-check.is-invalid {
    border: 1px solid #dc3545;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: rgba(220, 53, 69, 0.05);
}

/* Label styles */
label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

/* Required field indicator */
.text-danger {
    font-weight: bold;
}

/* Form group spacing */
.mb-3 {
    margin-bottom: 1rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

/* Input focus styles */
.form-control:focus {
    border-color: #c7a17a;
    box-shadow: 0 0 0 0.2rem rgba(199, 161, 122, 0.25);
}

/* Select focus styles */
select.form-control:focus {
    border-color: #c7a17a;
    box-shadow: 0 0 0 0.2rem rgba(199, 161, 122, 0.25);
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const districtSelect = document.getElementById("district-select");
    const shippingCostElement = document.getElementById("shipping-fee-display");
    const shippingFeeDisplayRight = document.getElementById("shipping-fee-display-right");
    const totalWithShippingElement = document.getElementById("total-with-shipping");
    const checkoutForm = document.getElementById("checkout-form");
    
    const subtotal = parseFloat({{ $subtotal }});
    const discount = parseFloat({{ $discount }});

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

        const newTotal = subtotal + shippingCost - discount;
        if (totalWithShippingElement) {
            totalWithShippingElement.textContent = formatCurrency(Math.max(0, newTotal));
        }

        console.log('Shipping and total updated:', {
            shipping_cost: shippingCost,
            new_total: newTotal
        });
    }

    // Client-side validation functions
    function validateName(name) {
        if (!name || name.trim().length < 2) {
            return 'Họ tên phải có ít nhất 2 ký tự';
        }
        if (name.length > 255) {
            return 'Họ tên không được quá 255 ký tự';
        }
        return null;
    }

    function validatePhone(phone) {
        const phoneRegex = /^[0-9]{10,11}$/;
        if (!phone) {
            return 'Vui lòng nhập số điện thoại';
        }
        if (!phoneRegex.test(phone)) {
            return 'Số điện thoại phải có 10-11 chữ số';
        }
        return null;
    }

    function validateEmail(email) {
        if (email && email.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return 'Email không đúng định dạng';
            }
            if (email.length > 255) {
                return 'Email không được quá 255 ký tự';
            }
        }
        return null;
    }

    function validateDistrict(district) {
        if (!district) {
            return 'Vui lòng chọn huyện';
        }
        return null;
    }

    function validateAddress(address) {
        if (!address || address.trim().length < 10) {
            return 'Địa chỉ phải có ít nhất 10 ký tự';
        }
        if (address.length > 255) {
            return 'Địa chỉ không được quá 255 ký tự';
        }
        return null;
    }

    function validatePaymentMethod() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            return 'Vui lòng chọn phương thức thanh toán';
        }
        return null;
    }

    function validateTerms() {
        const termsChecked = document.querySelector('input[name="terms"]:checked');
        if (!termsChecked) {
            return 'Bạn phải đồng ý với điều khoản và điều kiện';
        }
        return null;
    }

    function validateNote(note) {
        if (note && note.length > 1000) {
            return 'Ghi chú không được quá 1000 ký tự';
        }
        return null;
    }

    function showError(field, message) {
        field.classList.add('is-invalid');
        let errorElement = field.parentNode.querySelector('.text-danger');
        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.className = 'text-danger';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    function clearError(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.text-danger');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Real-time validation
    const nameInput = document.querySelector('input[name="name"]');
    const phoneInput = document.querySelector('input[name="phone_raw"]');
    const emailInput = document.querySelector('input[name="email"]');
    const addressInput = document.querySelector('input[name="address_detail"]');
    const noteInput = document.querySelector('textarea[name="note"]');

    nameInput.addEventListener('blur', function() {
        const error = validateName(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    phoneInput.addEventListener('blur', function() {
        const error = validatePhone(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    emailInput.addEventListener('blur', function() {
        const error = validateEmail(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    districtSelect.addEventListener('change', function() {
        updateShippingAndTotal();
        const error = validateDistrict(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    addressInput.addEventListener('blur', function() {
        const error = validateAddress(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    noteInput.addEventListener('blur', function() {
        const error = validateNote(this.value);
        if (error) {
            showError(this, error);
        } else {
            clearError(this);
        }
    });

    // Payment method validation
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            const paymentContainer = document.querySelector('.payment-methods');
            const error = validatePaymentMethod();
            if (error) {
                paymentContainer.classList.add('is-invalid');
                let errorElement = paymentContainer.querySelector('.text-danger');
                if (!errorElement) {
                    errorElement = document.createElement('span');
                    errorElement.className = 'text-danger';
                    paymentContainer.appendChild(errorElement);
                }
                errorElement.textContent = error;
            } else {
                paymentContainer.classList.remove('is-invalid');
                const errorElement = paymentContainer.querySelector('.text-danger');
                if (errorElement) {
                    errorElement.remove();
                }
            }
        });
    });

    // Terms validation
    const termsCheckbox = document.querySelector('input[name="terms"]');
    termsCheckbox.addEventListener('change', function() {
        const termsContainer = document.querySelector('.form-check');
        const error = validateTerms();
        if (error) {
            termsContainer.classList.add('is-invalid');
            let errorElement = termsContainer.querySelector('.text-danger');
            if (!errorElement) {
                errorElement = document.createElement('span');
                errorElement.className = 'text-danger';
                termsContainer.appendChild(errorElement);
            }
            errorElement.textContent = error;
        } else {
            termsContainer.classList.remove('is-invalid');
            const errorElement = termsContainer.querySelector('.text-danger');
            if (errorElement) {
                errorElement.remove();
            }
        }
    });

    // Form submission validation
    checkoutForm.addEventListener("submit", function (event) {
        let hasErrors = false;

        // Validate all fields
        const nameError = validateName(nameInput.value);
        if (nameError) {
            showError(nameInput, nameError);
            hasErrors = true;
        }

        const phoneError = validatePhone(phoneInput.value);
        if (phoneError) {
            showError(phoneInput, phoneError);
            hasErrors = true;
        }

        const emailError = validateEmail(emailInput.value);
        if (emailError) {
            showError(emailInput, emailError);
            hasErrors = true;
        }

        const districtError = validateDistrict(districtSelect.value);
        if (districtError) {
            showError(districtSelect, districtError);
            hasErrors = true;
        }

        const addressError = validateAddress(addressInput.value);
        if (addressError) {
            showError(addressInput, addressError);
            hasErrors = true;
        }

        const paymentError = validatePaymentMethod();
        if (paymentError) {
            const paymentContainer = document.querySelector('.payment-methods');
            paymentContainer.classList.add('is-invalid');
            let errorElement = paymentContainer.querySelector('.text-danger');
            if (!errorElement) {
                errorElement = document.createElement('span');
                errorElement.className = 'text-danger';
                paymentContainer.appendChild(errorElement);
            }
            errorElement.textContent = paymentError;
            hasErrors = true;
        }

        const termsError = validateTerms();
        if (termsError) {
            const termsContainer = document.querySelector('.form-check');
            termsContainer.classList.add('is-invalid');
            let errorElement = termsContainer.querySelector('.text-danger');
            if (!errorElement) {
                errorElement = document.createElement('span');
                errorElement.className = 'text-danger';
                termsContainer.appendChild(errorElement);
            }
            errorElement.textContent = termsError;
            hasErrors = true;
        }

        const noteError = validateNote(noteInput.value);
        if (noteError) {
            showError(noteInput, noteError);
            hasErrors = true;
        }

        if (hasErrors) {
            event.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    districtSelect.addEventListener("change", updateShippingAndTotal);
    updateShippingAndTotal();
});
</script>
@endsection