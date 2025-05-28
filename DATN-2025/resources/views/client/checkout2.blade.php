 @extends('layout2')
@section('main')
<main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="index-2.html">Home</a></li>
                                <li><a href="shop-checkout.html">Shop</a></li>
                                <li class="active"><a href="#">Shop Checkout</a></li>
                            </ul>
                            <label class="now">SHOP CHECKOUT</label>
                        </div>
                    </div>
                </section>

                <!-- Start Shop Cart -->   

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
                                    <h6>Returning customer? Click here to <a href="login_register.html">login</a></h6>
                                    <form class="form-checkout" name="form" method="post">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h5>Billing Details</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <input type="text" name="txt" placeholder="First Name">
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <input type="text" name="txt" placeholder="Last Name">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="txt" placeholder="Company Name">
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <input type="email" name="email" placeholder="Email">
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <input type="text" name="text" placeholder="Phone">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <select class="select-dropbox">
                                                    <option>Country</option>
                                                    <option>India</option>
                                                    <option>USA</option>
                                                    <option>London</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea placeholder="Address"></textarea>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <select class="select-dropbox">
                                                    <option>Province</option>
                                                    <option>list 1</option>
                                                    <option>list 2</option>
                                                    <option>list 3</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label><input type="checkbox" name="checkbox">Create an account ?</label>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h5>Shipping Address</h5>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label><input type="checkbox" name="checkbox">Ship to a different address ?</label>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea placeholder="Order Notes"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <div class="shop-checkout-right">
                                    <div class="shop-checkout-box">
                                        <h5>YOUR ORDER</h5>
                                        <div class="shop-checkout-title">
                                            <h6>PRODUCT <span>TOTAL</span></h6>
                                        </div>
                                        <div class="shop-checkout-row">
                                            <p><span>Rocha Sleeve Sweater</span> x1 <small>$140.00</small></p>
                                            <p><span>Mauris Tincidunt</span> x6 <small>$140.00</small></p>
                                        </div>
                                        <div class="checkout-total">
                                            <h6>CART SUBTOTAL <small>$140.00</small></h6>
                                        </div>
                                        <div class="checkout-total">
                                            <h6>SHIPPING <small>Free Shipping</small></h6>
                                        </div>
                                        <div class="checkout-total">
                                            <h6>ORDER TOTAL <small class="price-big">$140.00</small></h6>
                                        </div>
                                    </div>
                                    <div class="shop-checkout-box">
                                        <h5>PAYMENT METHODS</h5>
                                        <label><input type="radio" name="radio">Direct Bank Transfer</label>
                                        <p>Make your payment directly into our bank account. Please use your cleared in our account.</p>
                                        <div class="payment-mode">
                                            <label><input type="radio" name="radio">Check Payments</label>
                                        </div>
                                        <div class="payment-mode">
                                            <label><input type="radio" name="radio">Cash on Delivery</label>
                                        </div>
                                        <div class="payment-mode">
                                            <label><input type="radio" name="radio"> PayPal</label> <a href="#"><img src="images/paycart.png" alt=""></a><a href="#">What is PayPal?</a>
                                        </div>
                                        <div class="checkout-terms">
                                            <label><input type="checkbox" name="checkbox">I’ve read and accept the terms &amp; conditions *</label>
                                        </div>
                                        <div class="checkout-button">
                                            <button class="button-default btn-large btn-primary-gold">PROCEED TO PAYMENT</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End Shop Cart -->

            </div>
        </main> 
        @endsection