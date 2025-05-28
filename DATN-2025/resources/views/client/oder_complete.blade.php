   @extends('layout2')
@section('main')

   <main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
                                <li><a href="shop.html">Shop</a></li>
                                <li class="active"><a href="#">Order Complate</a></li>
                            </ul>
                            <label class="now">ORDER COMPLATE</label>
                        </div>
                    </div>
                </section>

                <!-- Start Shop Cart -->   

                <section class="default-section shop-cart bg-grey">
                    <div class="container">
                        <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <ul class="checkout-bar">
                                <li class="done-proceed">Shopping Cart</li>
                                <li class="done-proceed">Checkout</li>
                                <li class="active done-proceed">Order Complete</li>
                            </ul>
                        </div>
                        <div class="order-complete-box wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <img src="images/complete-sign.png" alt="">
                            <p>Thank you for ordering in our coffee. You will receive a confirmation email shortly. <br> Now check a Food Tracker progress with your order.</p>
                            <a href="track_order.html" class="btn-medium btn-skin btn-large">Go To Food Tracker</a>
                        </div>
                    </div>
                </section>

                <!-- End Shop Cart -->

            </div>
        </main>  
        @endsection