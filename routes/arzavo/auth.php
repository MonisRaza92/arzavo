<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ArzaqAuthController;

$baseDomain = config('app.domain');
if (str_starts_with($baseDomain, 'www.')) {
    $baseDomain = substr($baseDomain, 4);
}

// Arzaq Central OAuth & SSO Routes
Route::domain($baseDomain)->group(function () {
    Route::get('/login', [ArzaqAuthController::class, 'redirect'])->name('login');
    Route::get('/auth/redirect', [ArzaqAuthController::class, 'redirect'])->name('arzaq.redirect');
    Route::get('/auth/callback', [ArzaqAuthController::class, 'callback'])->name('arzaq.callback');
    Route::match(['get', 'post'], '/logout', [ArzaqAuthController::class, 'logout'])->name('logout');

    // Compatibility aliases
    Route::get('/auth/arzaq', [ArzaqAuthController::class, 'redirect']);
    Route::get('/auth/arzaq/callback', [ArzaqAuthController::class, 'callback']);
    Route::get('/register', [ArzaqAuthController::class, 'redirect'])->name('register');
});

// Login & Register Subdomains redirect to SSO
Route::domain('login.' . $baseDomain)->group(function () {
    Route::get('/', [ArzaqAuthController::class, 'redirect'])->name('login.form');
});

Route::domain('register.' . $baseDomain)->group(function () {
    Route::get('/', [ArzaqAuthController::class, 'redirect'])->name('register.form');
});
