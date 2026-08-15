<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Auth\ProfileController;

Route::prefix('account')->group(function () {
    Route::get('/login', [TenantLoginController::class, 'login'])->name('tenant.login');
    Route::post('/login', [TenantLoginController::class, 'loginHandle'])->name('tenant.login.handle');
    Route::get('/register', [TenantLoginController::class, 'register'])->name('tenant.register');
    Route::post('/register', [TenantLoginController::class, 'registerHandle'])->name('tenant.register.handle');
    Route::get('/logout', [TenantLoginController::class, 'logout'])->name('tenant.logout');
});

// Helper aliases on tenant domain
Route::get('/login', [TenantLoginController::class, 'login'])->name('tenant.login.short');
Route::post('/login', [TenantLoginController::class, 'loginHandle'])->name('tenant.login.submit');
Route::get('/register', [TenantLoginController::class, 'register'])->name('tenant.register.short');
Route::post('/register', [TenantLoginController::class, 'registerHandle'])->name('tenant.register.submit');

Route::middleware('auth:tenant')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/info/update', [ProfileController::class, 'profileInfoUpdate'])->name('profile-info-update');
    Route::post('/profile/banner/update', [ProfileController::class, 'profileBannerUpdate'])->name('profile-banner-update');
    Route::post('/profile/picture/update', [ProfileController::class, 'profilePictureUpdate'])->name('profile-picture-update');
});
