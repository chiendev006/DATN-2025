<?php
        use App\Http\Controllers\AboutController;
        use App\Http\Controllers\admin\AddressController;
        use App\Http\Controllers\admin\AdminStaffController;
        use App\Http\Controllers\admin\Product_attributesController;
        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\Controller;
        use App\Http\Controllers\ContactController;
        use App\Http\Controllers\admin\HomeController;
        use App\Http\Controllers\admin\DanhmucController;
        use App\Http\Controllers\admin\SanphamController;
        use App\Http\Controllers\admin\ProductImageController;
        use App\Http\Controllers\admin\ContactAdminController;
        use App\Http\Controllers\admin\SizeController;
        use App\Http\Controllers\admin\ToppingController;
        use App\Http\Controllers\admin\BlogsController;
        use App\Http\Controllers\AuthenticationController;
        use App\Http\Controllers\BlogController;
        use App\Http\Controllers\CartController;
        use App\Http\Controllers\ServicesController;
        use App\Http\Controllers\ShowproductController;
        use App\Http\Controllers\ResetPasswordController;
        use App\Http\Controllers\admin\AuthController;
        use App\Http\Controllers\admin\CouponController;
        use App\Http\Controllers\OrderController;
        use App\Http\Controllers\admin\PayrollController;
        use App\Http\Controllers\CheckoutController;
        use App\Http\Controllers\MyaccountController;
        use App\Http\Controllers\OrderSearchController;
        use App\Http\Controllers\ShopController;
        use App\Http\Controllers\Staff\StaffController;
        use App\Http\Controllers\VNPayController;
        use App\Http\Controllers\Staff\AuthenController;
        use App\Http\Controllers\Staff\BartenderController;
        use App\Http\ViewComposers\CartComposer;
        use Illuminate\Support\Facades\Auth;




        Route::get('login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('login', [AuthenticationController::class, 'postLogin'])->name('post-login');
        Route::get('register', [AuthenticationController::class, 'register'])->name('register');
        Route::post('register', [AuthenticationController::class, 'postRegister'])->name('post-register');
        Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
        Route::get('forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('forgot-password/send', [AuthenticationController::class, 'sendResetPassword'])->name('send.reset');
        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

        // Show ctsp
        Route::get('/product/{id}', [ShowproductController::class, 'showctsp'])->name('client.product.detail');


        // cart
        Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/remove/{key?}', [CartController::class, 'removeItem'])->name('cart.remove');




        // Trang chủ Client
        Route::get('/', [Controller::class, 'index'])->name('index');
        Route::get('/',         [Controller::class, 'danhmuc'])->name('danhmuc1.index');
        Route::get('/menu/ctsp', [Controller::class, 'showsp'])->name('client.showsp');
        // routes/web.php
        Route::get('/menu', [Controller::class, 'show'])->name('menu.show');
        Route::get('/menu/category/{categoryId}', [Controller::class, 'getCategoryProducts'])->name('menu.category');

        Route::post('comment', [Controller::class, 'postComment'])->name('comment.store');



        // Shop
        Route::get('/shop', [ShopController::class, 'index'])->name('client.shop');
        Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
        Route::get('/shop/category/{id}', [ShopController::class, 'getByCategory'])->name('shop.category');


        // Search
        // web.php
        Route::get('/ajax-search', [App\Http\Controllers\Controller::class, 'ajaxSearch'])->name('ajax.search');
        Route::get('/ajax-filter-price', [Controller::class, 'filterByPrice'])->name('ajax.filter.price');


        // Liên hệ từ Client
        Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');


        // About
        Route::get('/about', [AboutController::class, 'index'])->name('about.index');

        // Checkout routes
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/order/complete/{orderId}', [CheckoutController::class, 'success'])->name('order.complete');



        // Services
        Route::get('/services', [ServicesController::class, 'index'])->name('services.index');

        // Admin
        Route::get('admin/login', [AuthController::class, 'login'])->name('admin.login');
        Route::post('admin/login', [AuthController::class, 'postLogin'])->name('admin.post-login');
        Route::get('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Group Admin Route
        Route::prefix('admin')->middleware(['auth', 'checkAdmin', 'check.valid.id'])->group(function () {


        // Trang chủ Admin
        Route::get('/', [HomeController::class, 'index'])->name('home.index');
        Route::post('/revenue/filter', [HomeController::class, 'filterRevenue'])->name('revenue.filter');
        Route::fallback(function () {
            return view('admin.errors404admin'); // Trả về view 'errors/404.blade.php'
        });
        // Đơn hàng
        Route::get('/order', [\App\Http\Controllers\admin\OrderController::class, 'ordersIndex'])->name('admin.order.index');
        Route::post('/order/update/{id}', [\App\Http\Controllers\admin\OrderController::class, 'update'])->name('admin.order.update');
        Route::delete('/order/delete/{id}', [\App\Http\Controllers\admin\OrderController::class, 'delete'])->name('admin.order.delete');
        Route::get('/order/json/{id}', [\App\Http\Controllers\admin\OrderController::class, 'showJson'])->name('admin.order.json');
        Route::get('/order/filter', [\App\Http\Controllers\admin\OrderController::class, 'filterOrders'])->name('admin.order.filter');
        Route::get('/order/search', [\App\Http\Controllers\admin\OrderController::class, 'searchByTransactionId'])->name('admin.order.search');

        // Danh mục
        Route::prefix('danhmuc')->group(function () {
        Route::get('/', [DanhmucController::class, 'index'])->name('danhmuc.index');
        Route::get('/create', [DanhmucController::class, 'create'])->name('danhmuc.create');
        Route::post('/store', [DanhmucController::class, 'store'])->name('danhmuc.store');
        Route::get('/edit/{id}', [DanhmucController::class, 'edit'])->name('danhmuc.edit');
        Route::post('/update/{id}', [DanhmucController::class, 'update'])->name('danhmuc.update');
        Route::delete('/delete/{id}', [DanhmucController::class, 'delete'])->name('danhmuc.delete');
        });

        // Quản lý ảnh theo sản phẩm
        Route::prefix('sanpham')->name('sanpham.')->group(function () {
        Route::get('{id}/images', [SanphamController::class, 'listImages'])->name('images');
        Route::get('{id}/images/create', [SanphamController::class, 'createImage'])->name('images.create');
        Route::post('{id}/images', [SanphamController::class, 'storeImage'])->name('images.store');
        Route::get('images/{image}/edit', [SanphamController::class, 'editImage'])->name('images.edit');
        Route::post('images/{image}', [SanphamController::class, 'updateImage'])->name('images.update');
        Route::delete('images/{image}/delete', [SanphamController::class, 'deleteImage'])->name('images.delete');
        Route::get('/filter-category', [SanphamController::class, 'filterByCategory'])->name('filterCategory');
        });

        // Coupon
        Route::prefix('coupon')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('coupon.index');
        Route::get('/create', [CouponController::class, 'create'])->name('coupon.create');
        Route::post('/store', [CouponController::class, 'store'])->name('coupon.store');
        Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('coupon.edit');
        Route::post('/update/{id}', [CouponController::class, 'update'])->name('coupon.update');
        Route::delete('/delete/{id}', [CouponController::class, 'delete'])->name('coupon.delete');
        });

        // địa chỉ
        Route::prefix('address')->group(function () {
        Route::get('/', [AddressController  ::class, 'index'])->name('address.index');
        Route::get('/create', [AddressController::class, 'create'])->name('address.create');
        Route::post('/store', [AddressController::class, 'store'])->name('address.store');
        Route::get('/edit/{id}', [AddressController::class, 'edit'])->name('address.edit');
        Route::post('/update/{id}', [AddressController::class, 'update'])->name('address.update');
        Route::delete('/delete/{id}', [AddressController::class, 'delete'])->name('address.delete');
        Route::get('/search', [AddressController::class, 'search'])->name('address.search');

        });

        // Sản phẩm
        Route::prefix('sanpham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('sanpham.index');
        Route::get('/search', [SanphamController::class, 'search'])->name('sanpham.search');
        Route::get('/create', [SanphamController::class, 'create'])->name('sanpham.create');
        Route::post('/store', [SanphamController::class, 'store'])->name('sanpham.store');
        Route::get('/edit/{id}', [SanphamController::class, 'edit'])->name('sanpham.edit');
        Route::post( '/update/{id}', [SanphamController::class, 'update'])->name('sanpham.update');
        Route::delete('/delete/{id}', [SanphamController::class, 'delete'])->name('sanpham.delete');
        });

        // payroll
        Route::prefix('payroll')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/create', [PayrollController::class, 'create'])->name('payroll.create');
        Route::post('/store', [PayrollController::class, 'store'])->name('payroll.store');
        Route::get('/show/{id}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::post('/toggle-workday', [PayrollController::class, 'toggleWorkDay'])->name('payroll.toggleWorkDay');
        Route::get('/attendance/form', [\App\Http\Controllers\admin\PayrollController::class, 'getAttendanceForm'])->name('attendance.form');
        Route::post('/attendance/store', [\App\Http\Controllers\admin\PayrollController::class, 'storeAttendance'])->name('attendance.store');
        Route::post('/pay/{id}', [PayrollController::class, 'pay'])->name('payroll.pay');
        });
        // Staff
        Route::prefix('staff')->group(function () {
        Route::get('/', [AdminStaffController::class, 'staffIndex'])->name('admin.staff.index');
        Route::get('/create', [AdminStaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/store', [AdminStaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/edit/{id}', [AdminStaffController::class, 'edit'])->name('admin.staff.edit');
        Route::post('/update/{id}', [AdminStaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/delete/{id}', [AdminStaffController::class, 'delete'])->name('admin.staff.delete');
        });


        // Ảnh sản phẩm
        Route::prefix('product-images')->group(function () {
        Route::get('/', [ProductImageController::class, 'index'])->name('product-images.index');
        Route::get('/create', [ProductImageController::class, 'create'])->name('product-images.create');
        Route::post('/', [ProductImageController::class, 'store'])->name('product-images.store');
        Route::get('/{id}/edit', [ProductImageController::class, 'edit'])->name('product-images.edit');
        Route::post('/{id}', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/{id}/delete', [ProductImageController::class, 'destroy'])->name('product-images.delete');
        });

        // Quản lý liên hệ từ Admin
        Route::prefix('contact')->group(function () {
                Route::get('/', [ContactAdminController::class, 'index'])->name('contact.index');
                Route::delete('/delete/{id}', [ContactAdminController::class, 'delete'])->name('contact.delete');
                Route::get('/search', [ContactAdminController::class, 'search'])->name('contact.search');
        });

        // Topping
        Route::prefix('topping')->group(function () {
        Route::get('/', [ToppingController::class, 'index'])->name('topping.index');
        Route::get('/create', [ToppingController::class, 'create'])->name('topping.create');
        Route::post('/store', [ToppingController::class, 'store'])->name('topping.store');
        Route::get('/edit/{id}', [ToppingController::class, 'edit'])->name('topping.edit');
        Route::post('/update/{id}', [ToppingController::class, 'update'])->name('topping.update');
        Route::delete('/delete/{id}', [ToppingController::class, 'delete'])->name('topping.delete');
        Route::get('/filter', [ToppingController::class, 'searchtopping'])->name('topping.search');

        });

        // Size
        Route::prefix('size')->group(function () {
        Route::get('/', [Product_attributesController::class, 'index'])->name('size.index');
        Route::get('/create', [Product_attributesController::class, 'create'])->name('size.add');
        Route::post('/store', [Product_attributesController::class, 'store'])->name('size.store');
        Route::get('/edit/{id}', [Product_attributesController::class, 'edit'])->name('size.edit');
        Route::post('/update/{id}', [Product_attributesController::class, 'update'])->name('size.update');
        Route::delete('/delete/{id}', [Product_attributesController::class, 'delete'])->name('size.delete');
        });

        // Blogs
        Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogsController::class, 'index'])->name('blogs.index');
        Route::get('/create', [BlogsController::class, 'create'])->name('blogs.create');
        Route::post('/store', [BlogsController::class, 'store'])->name('blogs.store');
        Route::get('/edit/{id}', [BlogsController::class, 'edit'])->name('blogs.edit');
        Route::post('/update/{id}', [BlogsController::class, 'update'])->name('blogs.update');
        Route::delete('/destroy/{id}', [BlogsController::class, 'destroy'])->name('blogs.destroy');
        Route::get('/search', [BlogsController::class, 'search'])->name('blogs.search');
        });
        });
        Route::delete('admin/product_img/delete/{id}', [ProductImageController::class, 'destroy'])->middleware(['auth', 'checkAdmin'])->name('product_img.delete');
        Route::delete('admin/topping_detail/delete/{id}', [Product_attributesController::class, 'deleteTopping'])->middleware(['auth', 'checkAdmin'])->name('topping_detail.delete');
        Route::post('admin/topping_detail/add/{id}', [Product_attributesController::class, 'addToppingDetail'])->middleware(['auth', 'checkAdmin'])->name('topping_detail.add');


        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

        // Checkout routes
        Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

        // Staff
        Route::get('staff/login', [AuthenController::class, 'login'])->name('staff.login');
        Route::post('staff/postlogin', [AuthenController::class, 'postlogin'])->name('staff.postlogin');
        Route::get('staff/logout', [AuthenController::class, 'logout'])->name('staff.logout');
        Route::prefix('staff')->middleware('checkStaff')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/product/{id}', [StaffController::class, 'ajaxShow'])->name('staff.options');
        Route::post('/orders', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/products', [StaffController::class, 'products'])->name('staff.products');
        Route::get('/products/category/{id}', [StaffController::class, 'productsByCategory'])->name('staff.products.category');
        Route::get('/orderdetailtoday', [StaffController::class, 'orderdetailtoday'])->name('staff.orderdetailtoday');
        Route::get('/staff/products/search', [StaffController::class, 'searchProducts'])->name('staff.products.search');
        Route::put('/orders/{id}/status', [StaffController::class, 'updateStatus'])->name('orders.updateStatus');
        });

        Route::prefix('bartender')->middleware('checkStaff')->group(function () {
        Route::get('/', [BartenderController::class, 'index'])->name('bartender.index');
        Route::get('/create', [BartenderController::class, 'create'])->name('bartender.create');
        Route::post('/store', [BartenderController::class, 'store'])->name('bartender.store');
        Route::get('/edit/{id}', [BartenderController::class, 'edit'])->name('bartender.edit');
        Route::post('/update/{id}', [BartenderController::class, 'update'])->name('bartender.update');
        Route::post('/delete/{id}', [BartenderController::class, 'delete'])->name('bartender.delete');
        Route::get('/order/{id}', [BartenderController::class, 'orderDetail'])->name('bartender.order.detail');
        Route::post('/order-detail/{id}/update-status', [BartenderController::class, 'updateOrderDetailStatus'])->name('bartender.order.detail.status');
        Route::get('/get-order-details/{id}', [BartenderController::class, 'getOrderDetails'])->name('bartender.get.order.details');
        Route::post('/update-order-status/{id}', [BartenderController::class, 'updateOrderStatus'])->name('bartender.update.order.status');
        Route::get('/get-incomplete-order-count', [BartenderController::class, 'getIncompleteOrderCount'])->name('bartender.get.incomplete.order.count');
        });

        // Test route for middleware
        Route::get('/test-middleware', function() {
        $guard = Auth::guard('staff');
        if ($guard->check()) {
                $user = $guard->user();
                return "Logged in as: " . $user->name . " (Role: " . $user->role . ")";
        }
        return "Not logged in";
        })->middleware('checkStaff');

        // VNPAY
        Route::get('/vnpay/return', [App\Http\Controllers\VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
        Route::get('/vnpay/redirect', [App\Http\Controllers\VNPayController::class, 'redirectToVnpay'])->name('vnpay.redirect');

        // My account
        Route::get('/myaccount', [MyaccountController::class, 'index'])->middleware('auth')->name('client.myaccount');
        Route::patch('/account/order/cancel/{id}', [MyaccountController::class, 'cancelOrder'])->name('client.order.cancel');
        Route::patch('/order/cancel-multiple', [MyaccountController::class, 'cancelMultiple'])->name('client.order.cancelMultiple');
        Route::post('/myaccount/ajax-update', [MyAccountController::class, 'ajaxUpdate'])->name('myaccount.ajax-update');
        Route::get('/check-order-status/{id}', [MyaccountController::class, 'checkOrderStatus'])->name('client.order.checkStatus');
        Route::post('/order/reorder/{orderId}', [OrderController::class, 'reorder'])->name('client.order.reorder');

        // blogs
        Route::get('/blog', [BlogController::class, 'index'])->name('client.blog');
        Route::get('/blog/{id}', [BlogController::class, 'show'])->name('client.blogsingle');

        // Discout
        Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
        Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

        // Cart mini
        Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
        Route::post('/cart/remove-item', [CartController::class, 'remove'])->name('cart.removeItem');

        // Tra cứu đơn hàng người chưa đăng nhập
        Route::get('/tra-cuu-don-hang', [OrderSearchController::class, 'search'])->name('order.search');
        Route::post('/tra-cuu-don-hang/reorder/{id}', [OrderSearchController::class, 'reorder'])->name('order.search.reorder');
        Route::get('/tra-cuu-don-hang/check-status/{id}', [OrderSearchController::class, 'checkOrderStatus'])->name('order.search.checkStatus');
        Route::patch('/tra-cuu-don-hang/cancel/{id}', [OrderSearchController::class, 'cancelOrder'])->name('order.search.cancel');
        Route::get('/order-detail/{id}', [OrderSearchController::class, 'getOrderDetail'])->name('order.search.detail');


        Route::fallback(function () {
            return view('client.errors404'); // Trả về view 'errors/404.blade.php'
        });
