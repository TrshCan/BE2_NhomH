<?php

use App\Http\Controllers\ChatController;
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

Route::get('/chat/users', [ChatController::class, 'getUsers'])->name('chat.users');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/messages', [ChatController::class, 'storeMessage'])->name('chat.messages');

Route::get('/chat', function () {
    return view('chat.livechat');
})->name('chat.admin');
Route::get('/chatuser', function () {
    return view('chat.livechatuser');
})->name("chat.widget");

Route::get('/hotline', function () {
    return view('hotline');
})->name('hotline');
Route::get('/home', function () {
    return view('home');
})->name('home');