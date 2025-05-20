<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mazer Admin Dashboard</title>
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
                <h1 class="auth-title">Đặt lại mật khẩu</h1>
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', request()->email) }}">
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" class="form-control form-control-xl" placeholder="Nhập mật khẩu mới">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    {{-- Xác nhận mật khẩu --}}
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password_confirmation" class="form-control form-control-xl" placeholder="Xác nhận mật khẩu mới">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                    </div>
                    @error('password')
                    <h6 class="text-danger">{{ $message }}</h6>
                    @enderror
                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-1">Xác nhận</button>
                </form>
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




