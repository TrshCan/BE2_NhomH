<?php

use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;



Route::get('/', [ProductController::class, 'index'])->name('products.home');
Route::get('/deal-of-the-week', [ProductController::class, 'dealOfTheWeek'])->name('products.deal');
