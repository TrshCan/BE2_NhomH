<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\CartController;


Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');

// Cart controller
Route::get('/cart', [CartController::class, 'index'])->name('cart.cart');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::get('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::get('/cart/deleteall', [CartController::class, 'deleteAll'])->name('cart.deleteAll');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
