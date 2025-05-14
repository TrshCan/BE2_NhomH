<?php

use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;


Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');


Route::post('/signOut', [CrudUserController::class, 'signOut'])->name('signOut');


Route::get('/register', [CrudUserController::class, 'createUser'])->name('register');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');


Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('password/forgot',[ForgotPassword::class,'forgotPasswordForm'])->name('password.forgot');
Route::post('password/forgot',[ForgotPassword::class,'forgotPasswordFormPost'])->name('password.forgot.post');
Route::get('password/forgot/{token}',[ForgotPassword::class,'showForm'])->name('password.forgot.link');
Route::post('password/email/submit',[ForgotPassword::class,'resetPassword'])->name('password.forgot.link.submit');


Route::get('list/user', [CrudUserController::class, 'listUser'])->name('user.list');

// Xem chi tiết người dùng
Route::get('/user/{id}', [CrudUserController::class, 'showUser'])->name('user.show');

// Cập nhật người dùng
Route::get('list/user/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.update');
Route::post('list/user/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');

// Xóa người dùng
Route::post('list/user/delete', [CrudUserController::class, 'deleteUser'])->name('user.delete');

Route::get('admin/adminpanel', [CrudUserController::class, 'adminpanel'])->name('adminpanel');
Route::get('/home', function () {
    return view('home');
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;


use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderManagementController;

Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.show');
Route::get('/quanlysanpham', [AdminController::class, 'index'])->name('dashboard.show');
//
// Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
//     Route::get('/quanlysanpham', [AdminController::class, 'index'])->name('admin.products');
//     Route::get('/products/{id}', [AdminController::class, 'show'])->name('admin.products.show');
//     Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
//     Route::put('/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
//     Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
// });

// ORDER MANAGEMENT
// Route::prefix('admin')->name('admin.orders.')->group(function () {
//     Route::get('/orders', [OrderManagementController::class, 'index'])->name('index');
//     Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('show');
//     Route::post('/orders', [OrderManagementController::class, 'store'])->name('store');
//     Route::put('/orders/{id}', [OrderManagementController::class, 'update'])->name('update');
//     Route::delete('/orders/{id}', [OrderManagementController::class, 'destroy'])->name('destroy');
// });
Route::get('/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
Route::post('/orders', [OrderManagementController::class, 'store'])->name('admin.orders.store');
Route::post('/orders/{id}/update', [OrderManagementController::class, 'update'])->name('admin.orders.update');
Route::get('/orders/{id}/delete', [OrderManagementController::class, 'destroy'])->name('admin.orders.destroy');

// Cart routes
Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('cart', [CartController::class, 'viewCart'])->name('cart.cart');
Route::get('/cart/delete/{product_id}', [CartController::class, 'remove']);
Route::get('/cart/deleteall', [CartController::class, 'clear']);
Route::get('/cart/update_quantity/{product_id}/{qty}', [CartController::class, 'updateQuantity']);

Route::get('/checkout', [OrderController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
Route::get('/chat/users', [ChatController::class, 'getUsers'])->name('chat.users');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/messages', [ChatController::class, 'storeMessage'])->name('chat.messages');

Route::get('/chat', function () {
    return view('chat.livechat');
})->name('chat.admin');
Route::get('/chatuser', function () {
    return view('chat.livechatuser');
})->name("chat.widget");

Route::get('/hotline', function () {
    return view('hotline');
})->name('hotline');
Route::get('/home', function () {
    return view('home');
