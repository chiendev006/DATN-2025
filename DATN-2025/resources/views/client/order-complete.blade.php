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
                        <li class="done-proceed">Shopping Cart</li>
                        <li class="done-proceed">Checkout</li>
                        <li class="active">Order Complete</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="shop-checkout-left">
                            <h3>Thank you for your order!</h3>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <h4 class="mt-5 mb-3">Order Details</h4>
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
                                                <td>{{ $item->size_id ? ($item->size->size ?? 'Không topping') : 'Không topping' }}</td>
                                            <td>
                                                @php
                                                    $toppingIds = (!empty($item->topping_id) && is_string($item->topping_id)) 
                                                        ? explode(',', $item->topping_id) 
                                                        : [];
                                                @endphp

                                                @if(!empty($toppingIds))
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

                                                <td>{{ $order->address }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->product_price, 0) }}đ</td>
                                                <td>{{ number_format($item->total, 0) }}đ</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="6">No order details available.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                                 <div style="color: red;" class="text-right mt-3">
                                <h5>Tiền ship: <strong>{{ number_format($order->shipping_fee, 0) }}đ</strong></h5>
                            </div>
                            <div style="color: red;" class="text-right mt-3">
                                <h5>Tổng tiền: <strong>{{ number_format($order->total, 0) }}đ</strong></h5>
                            </div>

                            <p>Your order has been placed and will be processed as soon as possible.</p>
                            <p>Make sure you make note of your order number, which is <strong>#{{ session('order_number') }}</strong></p>
                            <p>You will be receiving an email shortly with confirmation of your order.</p>
                            <div class="mt-4">
                                <a href="/shop" class="button-default btn-large btn-primary-gold">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection