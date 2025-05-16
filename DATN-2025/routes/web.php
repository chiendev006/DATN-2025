<?php

use App\Http\Controllers\AboutController;
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

        // Login
        Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('login');
        Route::post('post-login', [AuthenticationController::class, 'postLogin'])->name('postLogin');
        Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');
        Route::get('register', [AuthenticationController::class, 'showRegisterForm'])->name('register');
        Route::post('post-register', [AuthenticationController::class, 'postRegister'])->name('postRegister');
        // Show ctsp
        Route::get('/product/{id}', [ShowproductController::class, 'showctsp'])->name('client.product.detail');
        Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');




        // Trang chủ Client
        Route::get('/', [Controller::class, 'index']);
        Route::get('/', [Controller::class, 'danhmuc'])->name('danhmuc1.index');
        Route::get('/menu', [Controller::class, 'show'])->name('client.menu');
        Route::get('/menu/ctsp', [Controller::class, 'showsp'])->name('client.showsp'); 
        Route::post('/add-to-cart/{id}', [Controller::class, 'addToCart'])->name('cart.add');

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

        // Sản phẩm
        Route::prefix('sanpham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('sanpham.index');
        Route::get('/create', [SanphamController::class, 'create'])->name('sanpham.create');
        Route::post('/store', [SanphamController::class, 'store'])->name('sanpham.store');
        Route::get('/edit/{id}', [SanphamController::class, 'edit'])->name('sanpham.edit');
        Route::post('/update/{id}', [SanphamController::class, 'update'])->name('sanpham.update');
        Route::get('/delete/{id}', [SanphamController::class, 'delete'])->name('sanpham.delete');
        });

        // Ảnh sản phẩm (biến thể)
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
        Route::get('/', [SizeController::class, 'index'])->name('size.index');
        Route::get('/create', [SizeController::class, 'create'])->name('size.create');
        Route::post('/store', [SizeController::class, 'store'])->name('size.store');
        Route::get('/edit/{id}', [SizeController::class, 'edit'])->name('size.edit');
        Route::post('/update/{id}', [SizeController::class, 'update'])->name('size.update');
        Route::get('/delete/{id}', [SizeController::class, 'delete'])->name('size.delete');
         });

   
        
});
 


