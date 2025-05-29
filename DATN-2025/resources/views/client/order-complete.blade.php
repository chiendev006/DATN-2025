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