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
                            <li class="active"><a href="#">Login / Register</a></li>
                        </ul>
                        <label class="now">LOGIN REGISTER</label>
                    </div>
                </div>
            </section>

            <!-- Start Login & Register -->

            <section class="default-section login-register bg-grey">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <div class="register-wrap form-common">
                                <div class="title text-center">
                                    <h3 class="text-coffee">Register</h3>
                                </div>
                                <form class="register-form" method="post" name="register">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="name" placeholder="Name" class="input-fields">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="email" placeholder="Your email" class="input-fields">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="phone" placeholder="Phone number" class="input-fields">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="password" name="password" placeholder="Password" class="input-fields">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="submit" name="submit" class="button-default button-default-submit" value="RegIster now">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- End Login & Register List -->

        </div>
    </main>
@endsection
