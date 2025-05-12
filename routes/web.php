<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;

Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');

Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');


Route::post('/signOut', [CrudUserController::class, 'signOut'])->name('signOut');


Route::get('/register', [CrudUserController::class, 'createUser'])->name('register');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');


Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('social.callback');


// Cart routes
Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('cart', [CartController::class, 'viewCart'])->name('cart.cart');
Route::get('/cart/delete/{product_id}', [CartController::class, 'remove']);
Route::get('/cart/deleteall', [CartController::class, 'clear']);
Route::get('/cart/update_quantity/{product_id}/{qty}', [CartController::class, 'updateQuantity']);

Route::get('/checkout', [OrderController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [OrderController::class, 'process'])->name('checkout.process');
