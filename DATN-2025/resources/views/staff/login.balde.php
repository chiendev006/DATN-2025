<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>AspStudio | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/app.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>
<body>
<!-- BEGIN #app -->
<div id="app" class="app app-full-height app-without-header">
    <!-- BEGIN login -->
    <div class="login">
        <!-- BEGIN login-content -->
        <div class="login-content">
            <form action="" method="post" name="login_form">

                <h1 class="text-center">Sign In</h1>
                <div class="text-muted text-center mb-4">
                    For your protection, please verify your identity.
                </div>
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
                        <a href="#" class="ms-auto text-muted">Forgot password?</a>
                    </div>
                    <input
                        type="password"
                        class="form-control form-control-lg fs-15px"
                        name="password"
                        placeholder="Enter your password"
                    />
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            value=""
                            id="customCheck1"
                        />
                        <label class="form-check-label fw-500" for="customCheck1"
                        >Remember me</label
                        >
                    </div>
                </div>
                <button
                    type="submit"
                    class="btn btn-theme btn-lg d-block w-100 fw-500 mb-3"
                >
                    Sign In
                </button>
                <div class="text-center text-muted">
                    Don't have an account yet?
                    <a href="page_register.html">Sign up</a>.
                </div>
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
    <div class="theme-panel">
        <a
            href="javascript:;"
            data-click="theme-panel-expand"
            class="theme-collapse-btn"
        ><i class="fa fa-cog"></i
            ></a>
        <div class="theme-panel-content">
            <ul class="theme-list clearfix">
                <li>
                    <a
                        href="javascript:;"
                        class="bg-red"
                        data-theme="theme-red"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Red"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-pink"
                        data-theme="theme-pink"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Pink"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-orange"
                        data-theme="theme-orange"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Orange"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-yellow"
                        data-theme="theme-yellow"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Yellow"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-lime"
                        data-theme="theme-lime"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Lime"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-green"
                        data-theme="theme-green"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Green"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-teal"
                        data-theme="theme-teal"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Teal"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-cyan"
                        data-theme="theme-cyan"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Aqua"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li class="active">
                    <a
                        href="javascript:;"
                        class="bg-blue"
                        data-theme=""
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Default"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-purple"
                        data-theme="theme-purple"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Purple"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-indigo"
                        data-theme="theme-indigo"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Indigo"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
                <li>
                    <a
                        href="javascript:;"
                        class="bg-gray-600"
                        data-theme="theme-gray-600"
                        data-click="theme-selector"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-container="body"
                        data-bs-title="Gray"
                        data-original-title=""
                        title=""
                    >&nbsp;</a
                    >
                </li>
            </ul>
            <hr class="mb-0" />
            <div class="row mt-10px pt-3px">
                <div class="col-9 control-label text-body-emphasis fw-bold">
                    <div>
                        Dark Mode
                        <span
                            class="badge bg-theme text-theme-color ms-1 position-relative py-4px px-6px"
                            style="top: -1px"
                        >NEW</span
                        >
                    </div>
                    <div class="lh-sm fs-13px fw-semibold">
                        <small class="text-body-emphasis opacity-50">
                            Adjust the appearance to reduce glare and give your eyes a
                            break.
                        </small>
                    </div>
                </div>
                <div class="col-3 d-flex">
                    <div class="form-check form-switch ms-auto mb-0 mt-2px">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="app-theme-dark-mode"
                            id="appThemeDarkMode"
                            value="1"
                        />
                        <label class="form-check-label" for="appThemeDarkMode"
                        >&nbsp;</label
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END theme-panel -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script
    src="assets/js/vendor.min.js"
    type="109ae531c2c46eac75a2f14c-text/javascript"
></script>
<script
    src="assets/js/app.min.js"
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

