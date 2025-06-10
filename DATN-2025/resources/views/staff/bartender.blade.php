<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Bartender Dashboard</title>
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
<div id="app" class="app">
    <!-- BEGIN #header -->
    <div id="header" class="app-header">
        <!-- BEGIN navbar-header -->
        <div class="navbar-header">
            <a href="{{ route('bartender.index') }}" class="navbar-brand">
                <span class="navbar-logo"></span>
                <b>Bartender</b> Dashboard
            </a>
            <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- END navbar-header -->
        <!-- BEGIN header-nav -->
        <div class="navbar-nav">
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span>
                        <span class="d-none d-md-inline">{{ Auth::guard('staff')->user()->name ?? 'Bartender' }}</span>
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-1">
                    <a href="{{ route('staff.logout') }}" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>
        <!-- END header-nav -->
    </div>
    <!-- END #header -->

    <!-- BEGIN #sidebar -->
    <div id="sidebar" class="app-sidebar">
        <!-- BEGIN scrollbar -->
        <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-header">Navigation</div>
                <div class="menu-item active">
                    <a href="{{ route('bartender.index') }}" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-coffee"></i>
                        </div>
                        <div class="menu-text">Dashboard</div>
                    </a>
                </div>
                <!-- Add more menu items as needed -->
            </div>
            <!-- END menu -->
        </div>
        <!-- END scrollbar -->
    </div>
    <!-- END #sidebar -->

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- Flash Message Display -->
        @if(session('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Thông báo!</strong> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            alert("{{ session('message') }}");
        </script>
        @endif

        <h1 class="page-header">Bartender Dashboard</h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Welcome to Bartender Dashboard</h4>
            </div>
            <div class="panel-body">
                <p>This is the bartender dashboard. You have role 22 access.</p>
                <!-- Add your bartender-specific content here -->
            </div>
        </div>
    </div>
    <!-- END #content -->

    <!-- BEGIN scroll-top-btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <!-- END scroll-top-btn -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="{{ url('assetstaff') }}/js/vendor.min.js"></script>
<script src="{{ url('assetstaff') }}/js/app.min.js"></script>
<!-- ================== END core-js ================== -->
</body>
</html>
