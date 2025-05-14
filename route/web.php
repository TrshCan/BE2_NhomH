<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;


Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


Route::get('/review/{product_id}', [ReviewController::class, 'index'])->name('reviews.form');   
Route::get('/products/{product_id}/review', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
