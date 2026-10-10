<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ArzaqAuthController;

$baseDomain = config('app.domain');
if (str_starts_with($baseDomain, 'www.')) {
    $baseDomain = substr($baseDomain, 4);
}

// 🔹 ARZAQ SSO ROOT & CONVENIENCE ROUTES (arzavo.test)
Route::domain($baseDomain)->group(function () {
    Route::get('/login', [ArzaqAuthController::class, 'redirect'])->name('login');
    Route::get('/register', [ArzaqAuthController::class, 'redirect'])->name('register');
    Route::get('/auth/arzaq', [ArzaqAuthController::class, 'redirect'])->name('arzaq.redirect');
    Route::get('/auth/callback', [ArzaqAuthController::class, 'callback'])->name('arzaq.callback');
    Route::get('/auth/arzaq/callback', [ArzaqAuthController::class, 'callback']);
    Route::match(['GET', 'POST'], '/logout', [\App\Http\Controllers\Auth\ArzaqAuthController::class, 'logout'])->name('logout');
});

// 🔹 LOGIN SUBDOMAIN (login.domain.com) -> Direct ARZAQ Central SSO
Route::domain('login.' . $baseDomain)->group(function () {
    Route::get('/', [ArzaqAuthController::class, 'redirect'])->name('login.form');
    Route::get('/auth/arzaq', [ArzaqAuthController::class, 'redirect'])->name('login.arzaq.redirect');
    Route::get('/auth/callback', [ArzaqAuthController::class, 'callback'])->name('login.arzaq.callback');
    Route::match(['GET', 'POST'], '/logout', [\App\Http\Controllers\Auth\ArzaqAuthController::class, 'logout'])->name('login.logout');
});

// 🔹 REGISTER SUBDOMAIN (register.domain.com) -> Direct ARZAQ Central SSO
Route::domain('register.' . $baseDomain)->group(function () {
    Route::get('/', [ArzaqAuthController::class, 'redirect'])->name('register.form');
});
