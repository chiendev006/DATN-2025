<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/pages/auth.css') }}">
</head>

<body>
<div id="auth">

    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <h1 class="auth-title">Quên mật khẩu</h1>
                <p class="auth-subtitle mb-5">Nhập email của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu.</p>
                @if(session('message'))
                        <h6 class="text-danger">{{ session('message') }}</h6>
                @endif
                <form action="{{ route('send.reset') }}" method="post">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email" class="form-control form-control-xl" placeholder="Email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    @error('email')
                        <h6 class="text-danger">{{ $message }}</h6>
                    @enderror
                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Gửi</button>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Bạn còn nhớ tài khoản của mình? <a href="{{ route('login') }}" class="font-bold">Đăng nhập</a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>

</div>
</body>

</html>



