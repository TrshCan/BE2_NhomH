<?php
<<<<<<< HEAD

use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');
=======
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponManagementController;

// Trang chủ
Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.show');

// Trang welcome
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Trang home
// Route::get('home',[CrudUserController::class,'home'])->name('home');
// QUẢN LÝ NGƯỜI DÙNG
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::post('/signOut', [CrudUserController::class, 'signOut'])->name('signOut');

Route::post('logout',[AdminController::class,'logout'])->name('logout');
//Setting
Route::get('/setting/users/{id}', [UserController::class, 'showUser'])->name('showUser');
Route::middleware(['auth'])->group(function () {
    Route::get('/setting/users/profile/update/{id}', [UserController::class, 'editProfile'])->name('user.profile.update');
    Route::post('/setting/users/profile/update/{id}', [UserController::class, 'updateProfile'])->name('user.profile.post.update');
});


Route::get('/register', [CrudUserController::class, 'createUser'])->name('register');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('password/forgot', [ForgotPassword::class, 'forgotPasswordForm'])->name('password.forgot');
Route::post('password/forgot', [ForgotPassword::class, 'forgotPasswordFormPost'])->name('password.forgot.post');
Route::get('password/forgot/{token}', [ForgotPassword::class, 'showForm'])->name('password.forgot.link');
Route::post('password/email/submit', [ForgotPassword::class, 'resetPassword'])->name('password.forgot.link.submit');

Route::get('list/user', [CrudUserController::class, 'listUser'])->name('user.list');
Route::get('/user/{id}', [CrudUserController::class, 'showUser'])->name('user.show');
Route::get('list/user/update/{id}', [CrudUserController::class, 'updateUser'])->name('user.update');
Route::post('list/user/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('user.postUpdateUser');
Route::post('list/user/delete', [CrudUserController::class, 'deleteUser'])->name('user.delete');

Route::get('admin/adminpanel', [CrudUserController::class, 'adminpanel'])->name('adminpanel');

// QUẢN LÝ ĐƠN HÀNG
Route::get('/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
Route::post('/orders', [OrderManagementController::class, 'store'])->name('admin.orders.store');
Route::post('/orders/{id}/update', [OrderManagementController::class, 'update'])->name('admin.orders.update');
Route::get('/orders/{id}/delete', [OrderManagementController::class, 'destroy'])->name('admin.orders.destroy');

// GIỎ HÀNG & THANH TOÁN
Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('cart', [CartController::class, 'viewCart'])->name('cart.cart');
Route::get('/cart/delete/{product_id}', [CartController::class, 'remove']);
Route::get('/cart/deleteall', [CartController::class, 'clear']);
Route::get('/cart/update_quantity/{product_id}/{qty}', [CartController::class, 'updateQuantity']);

Route::get('/checkout', [OrderController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');

// CHAT
Route::get('/chat/users', [ChatController::class, 'getUsers'])->name('chat.users');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages.get');
Route::post('/chat/messages', [ChatController::class, 'storeMessage'])->name('chat.messages');

Route::get('/chat', function () {
    return view('chat.livechat');
})->name('chat.admin');

Route::get('/chatuser', function () {
    return view('chat.livechatuser');
})->name("chat.widget");

// HOTLINE
Route::get('/hotline', function () {
    return view('hotline');
})->name('hotline');
Route::post('/checkout/apply-coupon', [OrderController::class, 'applyCoupon'])->name('checkout.applyCoupon');
Route::post('/checkout/remove-coupon', [OrderController::class, 'removeCoupon'])->name('checkout.removeCoupon');
Route::get('/product/get/{id}', [ProductController::class, 'get'])->name('products.get');

Route::get('/coupons', [CouponManagementController::class, 'index'])->name('admin.coupons.index');
Route::get('/coupons/{id}', [CouponManagementController::class, 'show']);
Route::post('/coupons', [CouponManagementController::class, 'store'])->name('admin.coupons.store');
Route::post('/coupons/{id}/update', [CouponManagementController::class, 'update']);
Route::get('/coupons/{id}/delete', [CouponManagementController::class, 'destroy']);
// Route::get('/quanlysanpham', [AdminController::class, 'index'])->name('dashboard.show');
Route::prefix('admin')->group(function () {
    Route::get('/quanlysanpham', [AdminController::class, 'index'])->name('admin.products');
    Route::get('/products/{id}', [AdminController::class, 'show'])->name('admin.products.show');
    Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
});
>>>>>>> test_merge_semiver2
