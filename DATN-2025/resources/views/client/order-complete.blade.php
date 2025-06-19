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
                        <li class="active"><a href="#">Order Complete</a></li>
                    </ul>
                    <label class="now">ORDER COMPLETE</label>
                </div>
            </div>
        </section>

        <section class="default-section shop-checkout bg-grey">
            <div class="container">
                <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <ul class="checkout-bar">
                        <li style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" class="done-proceed">Shopping Cart</li>
                        <li style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" class="done-proceed">Checkout</li>
                        <li style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" class="active">Order Complete</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="shop-checkout-left">
                            <h3>Cảm ơn bạn đã đặt hàng!</h3>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <h4 class="mt-5 mb-3">Chi tiết đơn hàng</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Size</th>
                                        <th>Toppings</th>
                                        <th>Địa chỉ</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($order->orderDetails->isNotEmpty())
                                        @foreach($order->orderDetails as $item)
                                            <tr>
                                                <td>
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/uploads/'.$item->product->image) }}" alt="Sản phẩm" width="80">
                                                    @else
                                                        <p>Không có ảnh</p>
                                                    @endif
                                                </td>
                                                <td>{{ $item->product_name }}</td>
                                                {{-- SỬA LỖI LOGIC: Hiển thị size hợp lý hơn --}}
                                                <td>{{ $item->size->size ?? 'Không có' }}</td>
                                                <td>
                                                    @php
                                                        $toppingIds = (!empty($item->topping_id) && is_string($item->topping_id))
                                                            ? explode(',', $item->topping_id)
                                                            : [];
                                                    @endphp

                                                    @if(!empty($toppingIds) && count(array_filter($toppingIds)) > 0)
                                                        @foreach($toppingIds as $toppingId)
                                                            @php $toppingId = (int) trim($toppingId); @endphp
                                                            @if(isset($allToppings[$toppingId]))
                                                                <div>
                                                                    {{ $allToppings[$toppingId]->topping ?? 'Không tên' }}
                                                                    ({{ number_format($allToppings[$toppingId]->price ?? 0, 0) }}đ)
                                                                </div>
                                                            @else
                                                                <div>Unknown Topping (ID: {{ $toppingId }})</div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div>Không topping</div>
                                                    @endif
                                                </td>

                                                {{-- THAY ĐỔI: Sử dụng các trường địa chỉ mới từ DB --}}
                                                <td>{{ $order->address_detail }}, {{ $order->district_name }}</td>

                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->product_price, 0) }}đ</td>
                                                <td>{{ number_format($item->total, 0) }}đ</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="8">Không có chi tiết đơn hàng.</td></tr>
                                    @endif
                                </tbody>
                            </table>

                            {{-- THAY ĐỔI: Thêm phần hiển thị giảm giá và tổng tiền --}}
                            <div class="text-right mt-3" style="max-width: 400px; margin-left: auto;">
                                <h5 style="display: flex; justify-content: space-between;">
                                    <span>Giảm giá:</span>
                                    <strong style="color: green;">-{{ number_format($order->coupon_total_discount, 0) }}đ</strong>
                                </h5>
                                <h5 style="display: flex; justify-content: space-between;">
                                    <span>Tiền ship:</span>
                                    <strong>{{ number_format($order->shipping_fee, 0) }}đ</strong>
                                </h5>
                                <hr>
                                <h5 style="display: flex; justify-content: space-between; color: red;">
                                    <span>Tổng tiền:</span>
                                    <strong>{{ number_format($order->total, 0) }}đ</strong>
                                </h5>
                            </div>

                            <p class="mt-4">Đơn hàng của bạn đã được đặt và sẽ được xử lý sớm nhất có thể.</p>
                            <p>Vui lòng ghi lại mã đơn hàng của bạn: <strong>#{{ session('order_number', $order->id) }}</strong></p>
                            <p>Bạn sẽ sớm nhận được email xác nhận đơn hàng.</p>
                            <div class="mt-4">
                                <a style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;" href="/shop" class="button-default btn-large btn-primary-gold">Tiếp tục mua sắm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
