<?php

use App\Http\Controllers\AboutController;
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


        Route::get('login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('login', [AuthenticationController::class, 'postLogin'])->name('post-login');
        Route::get('register', [AuthenticationController::class, 'register'])->name('register');
        Route::post('register', [AuthenticationController::class, 'postRegister'])->name('post-register');
        Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');
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
        Route::get('/cart/remove/{key}', [CartController::class, 'removeItem'])->name('cart.remove');




        // Trang chủ Client
        Route::get('/', [Controller::class, 'index']);
        Route::get('/', [Controller::class, 'danhmuc'])->name('danhmuc1.index');
        Route::get('/menu', [Controller::class, 'show'])->name('client.menu');
        Route::get('/menu/ctsp', [Controller::class, 'showsp'])->name('client.showsp');

        // Search
        Route::get('/search', [Controller::class, 'search'])->name('search');

        // Liên hệ từ Client
        Route::get('/contact/create', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');


        // About
        Route::get('/about',[AboutController::class, 'index'])->name('about.index');

        // Blog
        Route::get('/blog',[BlogController::class, 'index'])->name('blog.index');

        // Services
        Route::get('/services',[ServicesController::class, 'index'])->name('services.index');

        // Group Admin Route
        Route::prefix('admin')->group(function () {
        // Trang chủ Admin
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

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
        });


        // Sản phẩm
        Route::prefix('sanpham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('sanpham.index');
        Route::get('/create', [SanphamController::class, 'create'])->name('sanpham.create');
        Route::post('/store', [SanphamController::class, 'store'])->name('sanpham.store');
        Route::get('/edit/{id}', [SanphamController::class, 'edit'])->name('sanpham.edit');
        Route::post('/update/{id}', [SanphamController::class, 'update'])->name('sanpham.update');
        Route::get('/delete/{id}', [SanphamController::class, 'delete'])->name('sanpham.delete');
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



