<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\Admin\DashboardController;
use App\Http\Controllers\Arzavo\Admin\PlanController;
use App\Http\Controllers\Arzavo\Admin\UserController;
use App\Http\Controllers\Arzavo\Admin\TenantController;

Route::domain('admin.' . config('app.domain'))->middleware(['arzaq.auth', 'role:admin|super_admin'])->as('arzavo.admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('plans', PlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/assign-plan', [TenantController::class, 'assignPlan'])->name('tenants.assign-plan');
});

// Direct path prefix access support on main domain (e.g. domain/admin)
Route::domain(config('app.domain'))->prefix('admin')->middleware(['arzaq.auth', 'role:admin|super_admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('plans', PlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/assign-plan', [TenantController::class, 'assignPlan']);
});