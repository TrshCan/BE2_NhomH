<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ShopController;

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