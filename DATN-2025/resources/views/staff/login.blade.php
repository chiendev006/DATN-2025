<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>AspStudio | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ url('assetstaff') }}/css/vendor.min.css" rel="stylesheet" />
    <link href="{{ url('assetstaff') }}/css/app.min.css" rel="stylesheet" />

    <!-- ================== END core-css ================== -->
</head>
<body>
<!-- BEGIN #app -->
<div id="app" class="app app-full-height app-without-header">
    <!-- BEGIN login -->
    <div class="login">
        <!-- BEGIN login-content -->
        <div class="login-content">
            <form action="{{ route('staff.postlogin') }}" method="post">
                @csrf
                <h1 class="text-center">Sign In</h1>
                    @if(session('message'))
                        <div class="text-center mb-4">
                            <p class="text-danger">{{ session('message') }}</p>
                        </div>
                    @endif
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="text"
                        class="form-control form-control-lg fs-15px"
                        name="email"
                        placeholder="username@address.com"
                    />
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <label class="form-label">Password</label>
                    </div>
                    <input
                        type="password"
                        class="form-control form-control-lg fs-15px"
                        name="password"
                        placeholder="Enter your password"
                    />
                </div>
                <button
                    type="submit"
                    class="btn btn-theme btn-lg d-block w-100 fw-500 mb-3"
                >
                    Sign In
                </button>
            </form>
        </div>
        <!-- END login-content -->
    </div>
    <!-- END login -->

    <!-- BEGIN btn-scroll-top -->
    <a href="#" data-click="scroll-top" class="btn-scroll-top fade"
    ><i class="fa fa-arrow-up"></i
        ></a>
    <!-- END btn-scroll-top -->
    <!-- BEGIN theme-panel -->

    <!-- END theme-panel -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script
    src="{{ url('assetstaff') }}/js/vendor.min.js"
    type="109ae531c2c46eac75a2f14c-text/javascript"
></script>
<script
    src="{{ url('assetstaff') }}/js/app.min.js"
    type="109ae531c2c46eac75a2f14c-text/javascript"
></script>
<!-- ================== END core-js ================== -->

<!-- Google tag (gtag.js) -->
<script
    async
    src="https://www.googletagmanager.com/gtag/js?id=G-Y3Q0VGQKY3"
    type="109ae531c2c46eac75a2f14c-text/javascript"
></script>
<script type="109ae531c2c46eac75a2f14c-text/javascript">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-Y3Q0VGQKY3');
</script>
<script
    src="../cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js"
    data-cf-settings="109ae531c2c46eac75a2f14c-|49"
    defer
></script>
<script
    defer
    src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
    integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
    data-cf-beacon='{"rayId":"946fcc0ddf2c1e38","version":"2025.4.0-1-g37f21b1","r":1,"serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"4db8c6ef997743fda032d4f73cfeff63","b":1}'
    crossorigin="anonymous"
></script>
</body>
</html>

