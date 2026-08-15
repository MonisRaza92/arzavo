<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\Admin\DashboardController;
use App\Http\Controllers\Arzavo\Admin\PlanController;
use App\Http\Controllers\Arzavo\Admin\UserController;
use App\Http\Controllers\Arzavo\Admin\TenantController;

Route::domain('admin.' . config('app.domain'))->middleware(['auth:web', 'role:admin'])->as('arzavo.admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('plans', PlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/assign-plan', [TenantController::class, 'assignPlan'])->name('tenants.assign-plan');
});
