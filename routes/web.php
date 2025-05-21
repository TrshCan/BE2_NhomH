<?php

use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CrudUserController::class, 'login'])->name('login');

Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser'])->name('user.authUser');


Route::post('/signOut', [CrudUserController::class, 'signOut'])->name('signOut');


Route::get('/register', [CrudUserController::class, 'createUser'])->name('register');
Route::post('/register', [CrudUserController::class, 'postUser'])->name('user.postUser');

Route::get('password/forgot',[ForgotPassword::class,'forgotPasswordForm'])->name('password.forgot');
Route::post('password/forgot',[ForgotPassword::class,'forgotPasswordFormPost'])->name('password.forgot.post');
Route::get('password/forgot/{token}',[ForgotPassword::class,'showForm'])->name('password.forgot.link');
Route::post('password/email/submit',[ForgotPassword::class,'resetPassword'])->name('password.forgot.link.submit');

