<?php

use App\Http\Controllers\CrudUserController;
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


Route::get('/home', function () {
    return view('home');
})->name('home');