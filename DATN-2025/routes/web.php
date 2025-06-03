<?php

use App\Http\Controllers\AboutController;
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
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ShowproductController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\VNPayController;

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
        Route::get('/menu', [Controller::class, 'show'])->name('client.menu');
        Route::get('/menu/category/{id}', [Controller::class, 'getProductsByCategory']);

        Route::post('comment', [Controller::class, 'postComment'])->name('comment.store');



        // Shop
        Route::get('/shop',[ShopController::class,'index'])->name('client.shop');
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
        Route::get('/about',[AboutController::class, 'index'])->name('about.index');

        // Checkout routes
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/order/complete/{orderId}', [CheckoutController::class, 'success'])->name('order.complete');

        // Blog
        Route::get('/blog',[BlogController::class, 'index'])->name('blog.index');

        // Services
        Route::get('/services',[ServicesController::class, 'index'])->name('services.index');

        // Admin
        Route::get('admin/login', [AuthController::class, 'login'])->name('admin.login');
        Route::post('admin/login', [AuthController::class, 'postLogin'])->name('admin.post-login');
        Route::get('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Group Admin Route
        Route::prefix('admin')->middleware('checkAdmin')->group(function () {
        // Trang chủ Admin
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

        // Đơn hàng
        Route::get('/order', [\App\Http\Controllers\admin\OrderController::class, 'ordersIndex'])->name('admin.order.index');
        Route::post('/order/update/{id}', [\App\Http\Controllers\admin\OrderController::class, 'update'])->name('admin.order.update');
        Route::get('/order/{id}/delete', [\App\Http\Controllers\admin\OrderController::class, 'delete'])->name('admin.order.delete');
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
        Route::get('/delete/{id}', [DanhmucController::class, 'delete'])->name('danhmuc.delete');
        });

        // Quản lý ảnh theo sản phẩm
        Route::prefix('admin/sanpham')->name('sanpham.')->group(function () {
        Route::get('{id}/images', [SanphamController::class, 'listImages'])->name('images');
        Route::get('{id}/images/create', [SanphamController::class, 'createImage'])->name('images.create');
        Route::post('{id}/images', [SanphamController::class, 'storeImage'])->name('images.store');
        Route::get('images/{image}/edit', [SanphamController::class, 'editImage'])->name('images.edit');
        Route::put('images/{image}', [SanphamController::class, 'updateImage'])->name('images.update');
        Route::delete('images/{image}', [SanphamController::class, 'deleteImage'])->name('images.delete');
        Route::get('/filter-category', [SanphamController::class, 'filterByCategory'])->name('filterCategory');
        });


        // Sản phẩm
        Route::prefix('sanpham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('sanpham.index');
        Route::get('/search', [SanphamController::class, 'search'])->name('sanpham.search');
        Route::get('/create', [SanphamController::class, 'create'])->name('sanpham.create');
        Route::post('/store', [SanphamController::class, 'store'])->name('sanpham.store');
        Route::get('/edit/{id}', [SanphamController::class, 'edit'])->name('sanpham.edit');
        Route::post('/update/{id}', [SanphamController::class, 'update'])->name('sanpham.update');
        Route::get('/delete/{id}', [SanphamController::class, 'delete'])->name('sanpham.delete');
        });

          // Staff
        Route::prefix('staff')->group(function () {
        Route::get('/', [AdminStaffController::class, 'index'])->name('admin.staff.index');
        Route::post('/store', [AdminStaffController::class, 'store'])->name('admin.staff.store');
        Route::post('/update/{id}', [AdminStaffController::class, 'update'])->name('admin.staff.update');
        Route::get('/delete/{id}', [AdminStaffController::class, 'delete'])->name('admin.staff.delete');
        });

        // Ảnh sản phẩm
        Route::prefix('product-images')->group(function () {
        Route::get('/index', [ProductImageController::class, 'index'])->name('product-images.index');
        Route::get('/create', [ProductImageController::class, 'create'])->name('product-images.create');
        Route::post('/', [ProductImageController::class, 'store'])->name('product-images.store');
        Route::get('/{id}/edit', [ProductImageController::class, 'edit'])->name('product-images.edit');
        Route::put('/{id}', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/{id}', [ProductImageController::class, 'destroy'])->name('product-images.delete');
        });



        // Quản lý liên hệ từ Admin
        Route::prefix('contact')->group(function () {
        Route::get('/', [ContactAdminController::class, 'index'])->name('contact.index');
        Route::get('/delete/{id}', [ContactAdminController::class, 'delete'])->name('contact.delete');
        });

        // Topping
        Route::prefix('topping')->group(function () {
        Route::get('/', [ToppingController::class, 'index'])->name('topping.index');
        Route::get('/create', [ToppingController::class, 'create'])->name('topping.create');
        Route::post('/store', [ToppingController::class, 'store'])->name('topping.store');
        Route::get('/edit/{id}', [ToppingController::class, 'edit'])->name('topping.edit');
        Route::post('/update/{id}', [ToppingController::class, 'update'])->name('topping.update');
        Route::get('/delete/{id}', [ToppingController::class, 'delete'])->name('topping.delete');
        });


        // Size
        Route::prefix('size')->group(function () {
        Route::get('/', [Product_attributesController::class, 'index'])->name('size.index');
        Route::get('/create', [Product_attributesController::class, 'create'])->name('size.add');
        Route::post('/store', [Product_attributesController::class, 'store'])->name('size.store');
        Route::get('/edit/{id}', [Product_attributesController::class, 'edit'])->name('size.edit');
        Route::post('/update/{id}', [Product_attributesController::class, 'update'])->name('size.update');
        Route::get('/delete/{id}', [Product_attributesController::class, 'delete'])->name('size.delete');
         });


        });
        Route::get('admin/product_img/delete/{id}', [ProductImageController::class, 'destroy'])->name('product_img.delete');
        Route::get('admin/topping_detail/delete/{id}', [Product_attributesController::class, 'deleteTopping'])->name('topping_detail.delete');
        Route::post('admin/topping_detail/add/{id}', [Product_attributesController::class, 'addToppingDetail'])->name('topping_detail.add');


        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

        // Checkout routes
        Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

        // Staff
        Route::get('staff/login', [StaffController::class, 'login'])->name('staff.login');

        Route::prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/product/{id}/options', [StaffController::class, 'getOptions'])->name('staff.options');
        Route::get('/products', [StaffController::class, 'products'])->name('staff.products');
        Route::get('/products/category/{id}', [StaffController::class, 'productsByCategory'])->name('staff.products.category');
        Route::get('/orderdetailtoday', [StaffController::class, 'orderdetailtoday'])->name('staff.orderdetailtoday');
        });

        // VNPAY
        Route::get('/vnpay/return', [App\Http\Controllers\VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
        Route::get('/vnpay/redirect', [App\Http\Controllers\VNPayController::class, 'redirectToVnpay'])->name('vnpay.redirect');
