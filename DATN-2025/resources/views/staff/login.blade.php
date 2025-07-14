<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Staff Login | Coffee Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ url('assetstaff') }}/css/vendor.min.css" rel="stylesheet" />
    <link href="{{ url('assetstaff') }}/css/app.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
    
        <style>
        body {
            background: url('{{ url('asset/images/backgrout2.jpg') }}') no-repeat center center fixed !important; 
            background-size: cover !important;
            min-height: 100vh;
        }
        
        /* Đảm bảo app container không override background */
        #app {
            background: transparent !important;
        }
        
        .app {
            background: transparent !important;
        }
        .main-part {
            background-color: transparent;
            min-height: 100vh;
        }
        
        .breadcrumb-nav {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            padding: 20px 0;
            color: white;
        }
        
        .breadcrumb-nav-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breadcrumb-nav-inner ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 10px;
        }
        
        .breadcrumb-nav-inner ul li {
            color: #ddd;
        }
        
        .breadcrumb-nav-inner ul li.active {
            color: white;
        }
        
        .breadcrumb-nav-inner ul li a {
            color: inherit;
            text-decoration: none;
        }
        
        .breadcrumb-nav-inner .now {
            font-weight: bold;
            font-size: 18px;
        }
        
        .login-register {
            padding: 60px 0;
        }
        
        .login-wrap {
            margin-top: 130px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .title h3 {
            margin-bottom: 30px;
            color: #8B4513;
            font-weight: bold;
        }
        
        .input-fields {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .input-fields:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .button-default {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .button-default:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        }
        
        .href_matches {
            color: #8B4513;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .href_matches:hover {
            color: #A0522D;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .wow {
            animation-duration: 1s;
            animation-fill-mode: both;
        }
        
        .fadeInDown {
            animation-name: fadeInDown;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -100%, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        
        
        .text-coffee {
            color: #8B4513 !important;
        }
        
        /* Đảm bảo login-wrap có background semi-transparent */
        .login-wrap {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        
        /* Đảm bảo breadcrumb có background semi-transparent */
        .breadcrumb-nav {
            background: linear-gradient(135deg, rgba(139, 69, 19, 0.9) 0%, rgba(160, 82, 45, 0.9) 100%) !important;
        }
        
    </style>
</head>
<body>
<!-- BEGIN #app -->
<div id="app" class="app app-full-height app-without-header">
    <div class="main-part">
        
        <section class="default-section login-register bg-grey">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-8 col-xs-12 mx-auto wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <div class="login-wrap form-common">
                            <div class="title text-center">
                                <h3 class="text-coffee">Đăng nhập nhân viên</h3>
                            </div>
                            <form action="{{ route('staff.postlogin') }}" method="post">
                                @csrf
                                <div id="login-error-message" class="alert alert-danger" style="display: none;"></div>
                                @if(session('message'))
                                    <div class="alert alert-danger">
                                        <p>{{ session('message') }}</p>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="email" placeholder="Nhập email" class="input-fields" required>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="password" name="password" placeholder="Nhập mật khẩu" class="input-fields" required>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="button-default button-default-submit">Đăng nhập</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="{{ url('assetstaff') }}/js/vendor.min.js"></script>
<script src="{{ url('assetstaff') }}/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form[action="{{ route("staff.postlogin") }}"]');
    const errorMessageDiv = document.getElementById('login-error-message');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            // Remove any existing error messages
            if (errorMessageDiv) {
                errorMessageDiv.style.display = 'none';
            }
        });
    }
    
    // Debug: Kiểm tra background image
    console.log('Background image URL:', '{{ url("asset/images/lovepik.jpg") }}');
    
    // Test load image
    const img = new Image();
    img.onload = function() {
        console.log('Background image loaded successfully');
    };
    img.onerror = function() {
        console.error('Failed to load background image');
    };
    img.src = '{{ url("asset/images/lovepik.jpg") }}';
});
</script>
</body>
</html>

