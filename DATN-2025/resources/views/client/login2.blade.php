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
                        <label class="now">LOGIN</label>
                    </div>
                </div>
            </section>

            <!-- Start Login & Register -->

            <section class="default-section login-register bg-grey">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-sm-8 col-xs-12 mx-auto wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <div class="login-wrap form-common">
                                <div class="title text-center">
                                    <h3 class="text-coffee">Login</h3>
                                </div>
                                <form class="login-form" method="post" name="login" action="{{ route('post-login') }}">
                                    @csrf
                                    @if(session('message'))
                                        <div class="alert alert-danger">
                                            <p>{{ session('message') }}</p>
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            <p>{{ session('success') }}</p>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="email" placeholder="Username or email address" class="input-fields">
                                            @error('email')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="password" name="password" placeholder="********" class="input-fields">
                                            @error('password')
                                            <div class="alert alert-danger">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <a href="{{ route('forgot-password') }}" class="pull-right">Quên mật khẩu</a>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <a href="{{ route('register') }}" class="pull-right">Đăng kí</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="submit" name="submit" value="LOGIN" class="button-default button-default-submit">
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
