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
                                <li class="active"><a href="#">Shop Cart</a></li>
                            </ul>
                            <label class="now">SHOP CART</label>
                        </div>
                    </div>
                </section>

                <!-- Start Shop Cart -->   

                <section class="default-section shop-cart bg-grey">
                    <div class="container">
                        <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <ul class="checkout-bar">
                                <li class="active">Shopping Cart</li>
                                <li>Checkout</li>
                                <li>Order Complete</li>
                            </ul>
                        </div>
                        <div class="shop-cart-list wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <table class="shop-cart-table">
                                <thead>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <th>PRICE</th>
                                        <th>QUANTITY</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <td>
                                            <div class="product-cart">
                                                <img src="images/img45.png" alt="">
                                            </div>
                                            <div class="product-cart-title">
                                                <span>Blanched Garlic</span>
                                            </div>
                                        </td>
                                        <th>PRICE</th>
                                        <td>
                                            <strong>$59</strong>
                                            <del>$5400.00</del>
                                        </td>
                                        <th>QUANTITY</th>
                                        <td>
                                            <div class="price-textbox">
                                                <span class="minus-text"><i class="icon-minus"></i></span>
                                                <input name="txt" placeholder="1" type="text">
                                                <span class="plus-text"><i class="icon-plus"></i></span>
                                            </div>
                                        </td>
                                        <th>TOTAL</th>
                                        <td>
                                            $59
                                        </td>
                                        <td class="shop-cart-close"><i class="icon-cancel-5"></i></td>
                                    </tr>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <td>
                                            <div class="product-cart">
                                                <img src="images/img46.png" alt="">
                                            </div>
                                            <div class="product-cart-title">
                                                <span>Blanched Garlic</span>
                                            </div>
                                        </td>
                                        <th>PRICE</th>
                                        <td>
                                            <strong>$59</strong>
                                            <del>$5400.00</del>
                                        </td>
                                        <th>QUANTITY</th>
                                        <td>
                                            <div class="price-textbox">
                                                <span class="minus-text"><i class="icon-minus"></i></span>
                                                <input name="txt" placeholder="1" type="text">
                                                <span class="plus-text"><i class="icon-plus"></i></span>
                                            </div>
                                        </td>
                                        <th>TOTAL</th>
                                        <td>
                                            $59
                                        </td>
                                        <td class="shop-cart-close"><i class="icon-cancel-5"></i></td>
                                    </tr>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <td>
                                            <div class="product-cart">
                                                <img src="images/img47.png" alt="">
                                            </div>
                                            <div class="product-cart-title">
                                                <span>Blanched Garlic</span>
                                            </div>
                                        </td>
                                        <th>PRICE</th>
                                        <td>
                                            <strong>$59</strong>
                                            <del>$5400.00</del>
                                        </td>
                                        <th>QUANTITY</th>
                                        <td>
                                            <div class="price-textbox">
                                                <span class="minus-text"><i class="icon-minus"></i></span>
                                                <input name="txt" placeholder="1" type="text">
                                                <span class="plus-text"><i class="icon-plus"></i></span>
                                            </div>
                                        </td>
                                        <th>TOTAL</th>
                                        <td>
                                            $59
                                        </td>
                                        <td class="shop-cart-close"><i class="icon-cancel-5"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="product-cart-detail">
                                <div class="cupon-part">
                                    <input type="text" name="txt" placeholder="Cupon Code">
                                </div>
                                <a href="#" class="btn-medium btn-dark-coffee">Apply Coupon</a>
                                <a href="#" class="btn-medium btn-skin pull-right">UPDATE cart</a>
                            </div>
                        </div>
                        <div class="cart-total wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms">
                            <div class="cart-total-title">
                                <h5>CART TOTALS</h5>
                            </div>
                            <div class="product-cart-total">
                                <small>Total products</small>
                                <span>$140.00</span>
                            </div>
                            <div class="product-cart-total">
                                <small>Total shipping</small>
                                <span>$15.00</span>
                            </div>
                            <div class="grand-total">
                                <h5>TOTAL <span>$140.00</span></h5>
                            </div>
                            <div class="proceed-check">
                                <a href="shop_checkout.html" class="btn-primary-gold btn-medium">PROCEED TO CHECKOUT</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End Shop Cart -->

            </div>
        </main>  
        @endsection
