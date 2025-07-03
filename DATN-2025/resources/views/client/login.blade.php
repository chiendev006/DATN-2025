@extends('layout2')
@section('main')
    <main>
        <div class="main-part">

            <section class="breadcrumb-nav">
                <div class="container">
                    <div class="breadcrumb-nav-inner">
                        <ul>
                            <li><a href="/">Home</a></li>
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
                                <form id="login-form">
                                    @csrf
                                    <div id="login-error-message" class="alert alert-danger" style="display: none;"></div>
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            <p>{{ session('success') }}</p>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="email" placeholder="Username or email address" class="input-fields" required>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="password" name="password" placeholder="********" class="input-fields" required>
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
                                            <button type="submit" class="button-default button-default-submit">LOGIN</button>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorMessageDiv = document.getElementById('login-error-message');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            fetch('{{ route('post-login') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.token) {
                    localStorage.setItem('authToken', result.token);
                    window.location.href = result.redirect_url || '/';
                } else {
                    errorMessageDiv.textContent = result.message || 'Đăng nhập thất bại.';
                    errorMessageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessageDiv.textContent = 'Đã xảy ra lỗi khi đăng nhập.';
                errorMessageDiv.style.display = 'block';
            });
        });
    }
});
</script>
@endsection
