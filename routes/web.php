<?php

use Crater\Http\Controllers\V1\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::post('login', [LoginController::class, 'login']);

Route::get('auth/logout', function () {
    Auth::guard('web')->logout();
});


// Setup for installation of app
// ----------------------------------------------

Route::get('/on-boarding', function () {
    return view('app');
})->name('install')->middleware('redirect-if-installed');


// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/admin/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('admin')->middleware(['install', 'redirect-if-unauthenticated']);


// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('login')->middleware(['install', 'guest']);
