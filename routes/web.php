<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
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
Route::get('/product/get/{id}', [ProductController::class, 'get'])->name('products.get');