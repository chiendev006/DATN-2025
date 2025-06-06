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
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-sm-8 col-xs-12 mx-auto wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <div class="register-wrap form-common">
                                <div class="title text-center">
                                    <h3 class="text-coffee">Register</h3>
                                </div>
                                <form class="register-form" method="post" name="register" action="{{ route('post-register') }}">
                                    @csrf
                                    <div class="row">
                                        @if(session('message'))
                                            <div class="alert alert-danger">
                                                <p>{{ session('message') }}</p>
                                            </div>
                                        @endif
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="name" placeholder="Name" class="input-fields">
                                            @error('name')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="email" placeholder="Your email" class="input-fields">
                                            @error('email')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="phone" placeholder="Phone number" class="input-fields">
                                            @error('phone')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="password" name="password" placeholder="Password" class="input-fields">
                                            @error('password')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" name="password_confirmation" placeholder="Confirm password" class="input-fields">
                                                @error('password')
                                                <div class="alert alert-danger">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="submit" name="submit" class="button-default button-default-submit" value="Register now">
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
