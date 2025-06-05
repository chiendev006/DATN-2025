<!doctype html>
<html lang="en">

<!-- Mirrored from www.bootstrapget.com/demos/themeforest/unipro-admin-template/demos/01-design-blue/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 25 May 2025 08:58:19 GMT -->
<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Meta -->
		<meta name="description" content="Responsive Bootstrap4 Dashboard Template">
		<meta name="author" content="ParkerThemes">
		<link rel="shortcut icon" href="img/fav.png">

		<!-- Title -->
		<title>Uni Pro Admin Template - Admin Dashboard</title>


		<!-- *************
			************ Common Css Files *************
		************ -->
		<!-- Bootstrap css -->
		<link rel="stylesheet" href="{{ url('assetadmin') }}/css/bootstrap.min.css">

		<!-- Icomoon Font Icons css -->
		<link rel="stylesheet" href="{{ url('assetadmin') }}/fonts/style.css">

		<!-- Main css -->
		<link rel="stylesheet" href="{{ url('assetadmin') }}/css/main.css">


		<!-- *************
			************ Vendor Css Files *************
		************ -->

		<!-- Mega Menu -->
		<link rel="stylesheet" href="{{ url('assetadmin') }}/vendor/megamenu/css/megamenu.css">

		<!-- Search Filter JS -->
		<link rel="stylesheet" href="{{ url('assetadmin') }}/vendor/search-filter/search-filter.css">
		<link rel="stylesheet" href="{{ url('assetadmin') }}/vendor/search-filter/custom-search-filter.css">
	<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<!-- Summernote JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

	</head>
	<body>
@php
    $isHomeTab = request()->is('admin') || request()->is('admin/danhmuc*') || request()->is('admin/sanpham*') || request()->is('admin/topping*') || request()->is('admin/order*');
    $isAuthTab = request()->is('admin/staff*') || request()->is('admin/payroll*');
@endphp

		<!-- Loading wrapper start -->
		<div id="loading-wrapper">
			<div class="spinner-border"></div>
			Loading...
		</div>
		<!-- Loading wrapper end -->

		<!-- Page wrapper start -->
		<div class="page-wrapper">

			<!-- Sidebar wrapper start -->
			<nav class="sidebar-wrapper">

				<!-- Sidebar content start -->
				<div class="sidebar-tabs">

					<!-- Tabs nav start -->
					<div class="nav" role="tablist" aria-orientation="vertical">
						<a href="#" class="logo">
							<img src="img/logo.svg" alt="Uni Pro Admin">
						</a>
						<a class="nav-link {{ $isHomeTab ? 'active' : '' }}" id="home-tab" data-bs-toggle="tab" href="#tab-home" role="tab" aria-controls="tab-home" aria-selected="true">
							<i class="icon-home2"></i>
							<span class="nav-link-text">Thống kê - Quản lí</span>
						</a>

						<a class="nav-link {{ $isAuthTab ? 'active' : '' }}" id="authentication-tab" data-bs-toggle="tab" href="#tab-authentication" role="tab" aria-controls="tab-authentication" aria-selected="false">
							<i class="icon-unlock"></i>
							<span class="nav-link-text">Tài khoản</span>
						</a>
						<a class="nav-link settings" id="settings-tab" data-bs-toggle="tab" href="#tab-settings" role="tab" aria-controls="tab-authentication" aria-selected="false">
							<i class="icon-settings1"></i>
							<span class="nav-link-text">Settings</span>
						</a>
					</div>
					<!-- Tabs nav end -->

					<!-- Tabs content start -->
					<div class="tab-content">

						<!-- Chat tab -->
						<div class="tab-pane fade {{ $isHomeTab ? 'show active' : '' }}" id="tab-home" role="tabpanel" aria-labelledby="home-tab">

							<!-- Tab content header start -->
							<div class="tab-pane-header">
								Dashboards
							</div>
							<!-- Tab content header end -->

							<!-- Sidebar menu starts -->
							<div class="sidebarMenuScroll">
								<div class="sidebar-menu">
									<ul>
										<li>
											<a href="/admin" class="{{ request()->is('admin') ? 'current-page' : '' }}">Thống kê</a>
										</li>
										<li>
											<a href="{{ route('danhmuc.index') }}" class="{{ request()->is('admin/danhmuc*') ? 'current-page' : '' }}">Danh mục</a>
										</li>
										<li>
											<a href="{{ route('sanpham.index') }}" class="{{ request()->is('admin/sanpham*') ? 'current-page' : '' }}">Sản phẩm</a>
										</li>
										<li>
											<a href="{{ route('topping.index') }}" class="{{ request()->is('admin/topping*') ? 'current-page' : '' }}">Topping</a>
										</li>
										<li>
											<a href="{{ route('admin.order.index') }}" class="{{ request()->is('admin/order*') ? 'current-page' : '' }}">Đơn hàng</a>
										</li>
										<li>
											<a href="crm.html">Bình luận</a>
										</li>
										<li>
											<a href="reports.html">Blog</a>
										</li>


									</ul>

								</div>
							</div>
							<!-- Sidebar menu ends -->

							<!-- Sidebar actions starts -->
							<div class="sidebar-actions">
								<a href="orders.html" class="red">
									<div class="bg-avatar">12</div>
									<h5>New Orders</h5>
								</a>
								<a href="invoices-list.html" class="blue">
									<div class="bg-avatar">24</div>
									<h5>Bills Pending</h5>
								</a>
							</div>
							<!-- Sidebar actions ends -->

						</div>

						<!-- Pages tab -->


						<!-- Authentication tab -->
						<div class="tab-pane fade {{ $isAuthTab ? 'show active' : '' }}" id="tab-authentication" role="tabpanel" aria-labelledby="authentication-tab">

							<!-- Tab content header start -->
							<div class="tab-pane-header">
								Authentication
							</div>
							<!-- Tab content header end -->

							<!-- Sidebar menu starts -->
							<div class="sidebarMenuScroll">
								<div class="sidebar-menu">
									<ul>
										<li>
											<a href="{{ route('admin.staff.index') }}" class="{{ request()->is('admin/staff*') ? 'current-page' : '' }}">Nhân viên</a>
										</li>
										<li>
											<a href="{{ route('payroll.index') }}" class="{{ request()->is('admin/payroll*') ? 'current-page' : '' }}">Bảng lương</a>
										</li>
										<li>
											<a href="{{ route('admin.logout') }}">Logout</a>
										</li>

									</ul>
								</div>
							</div>
							<!-- Sidebar menu ends -->



						</div>

						<!-- Settings tab -->
						<div class="tab-pane fade" id="tab-settings" role="tabpanel" aria-labelledby="settings-tab">

							<!-- Tab content header start -->
							<div class="tab-pane-header">
								Settings
							</div>
							<!-- Tab content header end -->

							<!-- Settings start -->
							<div class="sidebarMenuScroll">
								<div class="sidebar-settings">
									<div class="accordion" id="settingsAccordion">
										<div class="accordion-item">
											<h2 class="accordion-header" id="genInfo">
												<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#genCollapse" aria-expanded="true" aria-controls="genCollapse">
													General Info
												</button>
											</h2>
											<div id="genCollapse" class="accordion-collapse collapse show" aria-labelledby="genInfo" data-bs-parent="#settingsAccordion">
												<div class="accordion-body">
													<div class="field-wrapper">
														<input type="text" value="Jeivxezer Lopexz" />
														<div class="field-placeholder">Full Name</div>
													</div>

													<div class="field-wrapper">
														<input type="email" value="jeivxezer-lopexz@email.com" />
														<div class="field-placeholder">Email</div>
													</div>

													<div class="field-wrapper">
														<input type="text" value="0 0000 00000" />
														<div class="field-placeholder">Contact</div>
													</div>
													<div class="field-wrapper m-0">
														<button class="btn btn-primary stripes-btn">Save</button>
													</div>
												</div>
											</div>
										</div>
										<div class="accordion-item">
											<h2 class="accordion-header" id="chngPwd">
												<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chngPwdCollapse" aria-expanded="false" aria-controls="chngPwdCollapse">
													Change Password
												</button>
											</h2>
											<div id="chngPwdCollapse" class="accordion-collapse collapse" aria-labelledby="chngPwd" data-bs-parent="#settingsAccordion">
												<div class="accordion-body">
													<div class="field-wrapper">
														<input type="text" value="">
														<div class="field-placeholder">Current Password</div>
													</div>
													<div class="field-wrapper">
														<input type="password" value="">
														<div class="field-placeholder">New Password</div>
													</div>
													<div class="field-wrapper">
														<input type="password" value="">
														<div class="field-placeholder">Confirm Password</div>
													</div>
													<div class="field-wrapper m-0">
														<button class="btn btn-primary stripes-btn">Save</button>
													</div>

												</div>
											</div>
										</div>
										<div class="accordion-item">
											<h2 class="accordion-header" id="sidebarNotifications">
												<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#notiCollapse" aria-expanded="false" aria-controls="notiCollapse">
													Notifications
												</button>
											</h2>
											<div id="notiCollapse" class="accordion-collapse collapse" aria-labelledby="sidebarNotifications" data-bs-parent="#settingsAccordion">
												<div class="accordion-body">
													<div class="list-group m-0">
														<div class="noti-container">
															<div class="noti-block">
																<div>Alerts</div>
																<div class="form-switch">
																	<input class="form-check-input" type="checkbox" id="showAlertss" checked>
																	<label class="form-check-label" for="showAlertss"></label>
																</div>
															</div>
															<div class="noti-block">
																<div>Enable Sound</div>
																<div class="form-switch">
																	<input class="form-check-input" type="checkbox" id="soundEnable">
																	<label class="form-check-label" for="soundEnable"></label>
																</div>
															</div>
															<div class="noti-block">
																<div>Allow Chat</div>
																<div class="form-switch">
																	<input class="form-check-input" type="checkbox" id="allowChat">
																	<label class="form-check-label" for="allowChat"></label>
																</div>
															</div>
															<div class="noti-block">
																<div>Desktop Messages</div>
																<div class="form-switch">
																	<input class="form-check-input" type="checkbox" id="desktopMessages">
																	<label class="form-check-label" for="desktopMessages"></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Settings end -->

							<!-- Sidebar actions starts -->
							<div class="sidebar-actions">
								<div class="support-tile blue">
									<a href="account-settings.html" class="btn btn-light m-auto">Advance Settings</a>
								</div>
							</div>
							<!-- Sidebar actions ends -->
						</div>

					</div>
					<!-- Tabs content end -->

				</div>
				<!-- Sidebar content end -->

			</nav>
			<!-- Sidebar wrapper end -->

			<!-- *************
				************ Main container start *************
			************* -->
			<div class="main-container">

				<!-- Page header starts -->
				<div class="page-header">

					<!-- Row start -->
					<div class="row gutters">
						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-6 col-9">

							<!-- Search container start -->
							<div class="search-container">

								<!-- Toggle sidebar start -->
								<div class="toggle-sidebar" id="toggle-sidebar">
									<i class="icon-menu"></i>
								</div>
								<!-- Toggle sidebar end -->

								<!-- Mega Menu Start -->

								<!-- Mega Menu End -->


							</div>
							<!-- Search container end -->

						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-3">

							<!-- Header actions start -->

							<!-- Header actions end -->

						</div>
					</div>
					<!-- Row end -->

				</div>
				<!-- Page header ends -->
