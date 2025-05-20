<?php

use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');