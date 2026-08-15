<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\PaymentController;

Route::view('/offline', 'offline');
Route::post('/cashfree/webhook', [PaymentController::class, 'webhook']);

// Arzavo Main Website, Auth & Arzavo Admin Routes
require __DIR__ . '/arzavo/web.php';
require __DIR__ . '/arzavo/auth.php';
require __DIR__ . '/arzavo/admin.php';

// Tenant Domain Routes
registerTenantRoutes(function () {
    require __DIR__ . '/tenant/web.php';
    require __DIR__ . '/tenant/auth.php';
    require __DIR__ . '/tenant/admin.php';
});
