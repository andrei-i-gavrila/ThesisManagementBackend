<?php


use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::post("register", "RegisterController")->name('register');
    Route::post("login", "LoginController")->name("login");
});

Route::namespace("User")->prefix('user')->middleware(['auth:api'])->group(function () {
    Route::get('me', 'UserController@loggedUser')->name("me");
});
