<?php


use App\Http\Controllers\FaqController;





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
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponManagementController;
use App\Http\Controllers\BrandController; // Added to use BrandController
use App\Http\Controllers\ContactController; // Added to use BrandController
use App\Http\Controllers\ImageController; // Added to use BrandController
use App\Http\Controllers\DealProductController;
use App\Http\Controllers\ReviewController;


// Trang chủ
Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.show');
Route::get('/shop/{brandSlug?}', [ShopController::class, 'index'])->name('products.index');
Route::get('/shop/{brandSlug?}/{categorySlug?}', [ShopController::class, 'index'])->name('shop');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/blog', [BlogController::class, 'clientIndex'])->name('blog.index');
Route::get('/post/{id}', [BlogController::class, 'clientShow'])->name('post.show');

// Trang welcome
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// QUẢN LÝ NGƯỜI DÙNG
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');
Route::post('/signOut', [CrudUserController::class, 'signOut'])->name('signOut');

Route::post('logout', [AdminController::class, 'logout'])->name('logout');
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

// QUẢN LÝ ĐƠN HÀNG
Route::get('/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
Route::post('/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show2');
Route::post('/orders', [OrderManagementController::class, 'store'])->name('admin.orders.store');
Route::post('/orders/{id}/update', [OrderManagementController::class, 'update'])->name('admin.orders.update');
Route::post('/orders/{id}/delete', [OrderManagementController::class, 'destroy'])->name('admin.orders.destroy');

// GIỎ HÀNG & THANH TOÁN
Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('cart', [CartController::class, 'viewCart'])->name('cart.cart');
Route::get('/cart/delete/{product_id}', [CartController::class, 'remove']);
Route::get('/cart/deleteall', [CartController::class, 'clear']);
Route::post('/cart/update_quantity/{product_id}', [CartController::class, 'updateQuantity']);
Route::get('/cart/update_quantity/{product_id}', [CartController::class, 'updateQuantity']);

Route::get('/checkout', [OrderController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
Route::post('/checkout/validate', [OrderController::class, 'validate'])->name('checkout.validate');

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
//Thong ke
Route::get('/admin/statistical', [AdminController::class, 'index'])->name('admin.statistical');

    
// Statistics filter
Route::get('/statistics/filter', [AdminController::class, 'filter'])->name('admin.statistics.filter');

//FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');

//review
Route::middleware(['auth'])->group(function () {
    Route::get('/review/{product_id}', [ReviewController::class, 'index'])->name('reviews.form');   
});

Route::get('/products/{product_id}/review', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::post('/checkout/apply-coupon', [OrderController::class, 'applyCoupon'])->name('checkout.applyCoupon');
Route::post('/checkout/remove-coupon', [OrderController::class, 'removeCoupon'])->name('checkout.removeCoupon');
Route::get('/product/get/{id}', [ProductController::class, 'get'])->name('products.get');

Route::get('/coupons', [CouponManagementController::class, 'index'])->name('admin.coupons.index');
Route::get('/coupons/{id}', [CouponManagementController::class, 'show']);
Route::post('/coupons', [CouponManagementController::class, 'store'])->name('admin.coupons.store');
Route::post('/coupons/{id}/update', [CouponManagementController::class, 'update']);
Route::get('/coupons/{id}/delete', [CouponManagementController::class, 'destroy']);

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/adminPanel', [AdminController::class, 'adminPanel'])->name('admin.adminPanel');

    Route::get('/quanlynguoidung', [CrudUserController::class, 'index'])->name('admin.indexUser');
    Route::get('/user/{id}', [CrudUserController::class, 'showUser'])->name('admin.showUser');
    Route::get('/user/update/{id}', [CrudUserController::class, 'updateUser'])->name('admin.updateUser');
    Route::post('/user/update/{id}', [CrudUserController::class, 'postUpdateUser'])->name('admin.postUpdateUser');
    Route::post('/user/delete', [CrudUserController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::get('/quanlysanpham', [AdminController::class, 'index'])->name('admin.products');
    Route::get('/products/{id}', [AdminController::class, 'show'])->name('admin.products.show');
    Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/quanlythuonghieu', [BrandController::class, 'index'])->name('admin.brands');
    Route::get('/brands/{id}', [BrandController::class, 'show'])->name('admin.brands.show');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::put('/brands/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    Route::get('/quanlydanhmuc', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('admin.categories.show');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/images', [ImageController::class, 'index'])->name('admin.images.index');
    Route::get('/images/create', [ImageController::class, 'create'])->name('admin.images.create');
    Route::post('/images', [ImageController::class, 'store'])->name('admin.images.store');
    Route::get('/images/{id}', [ImageController::class, 'show'])->name('admin.images.show');
    Route::get('/images/{id}/edit', [ImageController::class, 'edit'])->name('admin.images.edit');
    Route::put('/images/{id}', [ImageController::class, 'update'])->name('admin.images.update');
    Route::delete('/images/{id}', [ImageController::class, 'destroy'])->name('admin.images.destroy');

    Route::get('deals', [DealProductController::class, 'index'])->name('admin.deals.index');
    Route::get('deals/{id}', [DealProductController::class, 'show'])->name('admin.deals.show');
    Route::put('deals/{id}', [DealProductController::class, 'update'])->name('admin.deals.update');

    Route::get('/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
    Route::post('/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');
    Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('admin.blogs.show');
    Route::put('/blogs/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');
});
