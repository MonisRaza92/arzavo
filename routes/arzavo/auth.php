<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

$baseDomain = config('app.domain');
if (str_starts_with($baseDomain, 'www.')) {
    $baseDomain = substr($baseDomain, 4);
}

// 🔹 LOGIN SUBDOMAIN (login.domain.com)
Route::domain('login.' . $baseDomain)->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login.form');
    Route::post('/', [LoginController::class, 'loginHandle'])->name('login.handle');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/{provider}', [LoginController::class, 'redirect'])->name('social.redirect');
    Route::get('/{provider}/callback', [LoginController::class, 'callback'])->name('social.callback');
    Route::post('/google/onetap', [LoginController::class, 'oneTap'])->name('google.onetap');
});

// 🔹 REGISTER SUBDOMAIN (register.domain.com)
Route::domain('register.' . $baseDomain)->group(function () {
    Route::get('/', [LoginController::class, 'register'])->name('register.form');
    Route::post('/', [LoginController::class, 'registerHandle'])->name('register.handle');
});
