<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\frontend\BannerController;
use App\Http\Controllers\frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\frontend\OrderController as FrontendOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Frontend

//Pages
Route::get('/',[FrontendHomeController::class,'index'])->name('home');
Route::get('/latest-product',[FrontendHomeController::class,'latestProduct'])->name('latestProduct');
Route::get('/popular-product',[FrontendHomeController::class,'popularProduct'])->name('popularProduct');
Route::get('/about', [CustomPageController::class,'about'])->name('about.page');


Route::get('/product/page',[FrontendHomeController::class,'product'])->name('product.page');
Route::get('/product/details/{slug}',[FrontendHomeController::class,'details'])->name('product.details');
Route::get('/contact',[ContactController::class,'index'])->name('contact');
Route::get('/category',[CategoryController::class,'index'])->name('category');

Route::get('/products/cart', [ProductController::class,'cart'])->name('cart');


//Auth//Register
Route::get('/login',[AuthController::class,'index'])->name('login');
Route::post('/store',[AuthController::class,'store'])->name('store');
Route::get('/registration',[RegistrationController::class,'index'])->name('registration');
Route::post('/registration/store',[RegistrationController::class,'store'])->name('registration.store');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('auth/github', [GithubController::class,'redirect'] )->name('github.login');
Route::get('auth/github/callback', [GithubController::class,'callback'] );


//Backend
Route::post('/success', [FrontendOrderController::class, 'success'])->name('sslcommerz.success');
Route::post('/fail', [FrontendOrderController::class, 'fail'])->name('fail');
Route::post('/cancel', [FrontendOrderController::class, 'cancel'])->name('cancel');
//Middleware
Route::group(['middleware'=>'auth'],function(){

    Route::get('/add-to-cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::patch('/cart/update/{productId}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::get('/remove-from-cart/{product}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/clear-cart', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/checkout', [FrontendOrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [FrontendOrderController::class, 'processOrder'])->name('checkout.process');

    Route::get('/order/index', [OrderController::class, 'index'])->name('order.index');
//Pages
Route::get('/app',[HomeController::class,'index'])->name('app');

Route::get('/custom/page', [CustomPageController::class,'index'])->name('custom.page.index');
Route::get('/edit/{id}', [CustomPageController::class,'edit'])->name('custom.page.edit');
Route::post('/update/{id}', [CustomPageController::class,'update'])->name('custom.page.update');

Route::get('/banner/click', [BannerController::class, 'click'])->name('banner.click');

Route::prefix('category')->name('category.')->group(function () {
    Route::get('/index', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit'); // Should be GET
    Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update'); // Use PUT/PATCH
    Route::post('/store', [CategoryController::class, 'store'])->name('store'); // Added store route
    Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy'); // For delete
});


//Product
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/create', [ProductController::class,'create'])->name('create');
    Route::post('/store', [ProductController::class,'store'])->name('store');
    Route::get('/index', [ProductController::class,'index'])->name('index');
    Route::put('/update/{id}', [ProductController::class,'update'])->name('update');
    Route::get('/edit/{id}', [ProductController::class,'edit'])->name('edit');
    Route::delete('/delete/{id}', [ProductController::class,'store'])->name('destroy');
});


Route::get('/checkout/process', [FrontendOrderController::class, 'processPaypalPayment'])->name('processPaypalPayment');
Route::get('/paypal/success', [FrontendOrderController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [FrontendOrderController::class, 'paypalCancel'])->name('paypal.cancel');

Route::post('/stripe-purchase', [FrontendOrderController::class, 'processStripePayment'])->name('purchase');
Route::get('/payment-success', [FrontendOrderController::class, 'StripeSuccess'])->name('stripe.success');
Route::get('/payment-cancel', [FrontendOrderController::class, 'index'])->name('stripe.cancel');




Route::get('/logout',[TestController::class,'logout'])->name('logout');
Route::get('/form',[TestController::class,'form'])->name('form');
Route::get('/setting',[SettingController::class,'index'])->name('setting');
Route::get('/change-password',[ChangePasswordController::class,'index'])->name('change.password');
Route::post('/update-password/{id}',[ChangePasswordController::class,'update'])->name('update.password');
Route::get('/user-list',[AuthController::class,'list'])->name('user.list');
Route::get('/category-list',[CategoryController::class,'list'])->name('category.list');
Route::get('/category-form',[CategoryController::class,'form'])->name('category.form');


//profile
Route::get('/profile',[ProfileController::class,'index'])->name('profile');
//post
Route::post('/registration/update{id}',[RegistrationController::class,'update'])->name('registration.update');


Route::get('/roles', [RolePermissionController::class, 'createRole'])->name('roles.permission.create');
Route::get('/roles/permission/index', [RolePermissionController::class, 'index'])->name('roles.permission.index');
Route::post('/roles/store', [RolePermissionController::class, 'storeRole'])->name('store.roles');
Route::post('/permissions/store', [RolePermissionController::class, 'createPermission'])->name('permissions.create');
Route::post('/roles/assign', [RolePermissionController::class, 'assignRole'])->name('assign.role');
Route::post('/permissions/assign', [RolePermissionController::class, 'assignPermission'])->name('assign.permission');
});
