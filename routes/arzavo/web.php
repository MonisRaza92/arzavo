<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\HomeController;
use App\Http\Controllers\Arzavo\DocumentationController;
use App\Http\Controllers\Arzavo\TenantController;
use App\Http\Controllers\Arzavo\DomainController;
use App\Http\Controllers\Arzavo\PaymentController;
use App\Http\Controllers\Tenant\Admin\BillingController;
use App\Http\Controllers\Arzavo;

Route::domain(config('app.domain'))->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::prefix('documentation')->as('documentation.')->group(function () {
        Route::get('/', [DocumentationController::class, 'index'])->name('index');
        Route::get('/{slug}', [DocumentationController::class, 'show'])->name('show');
    });
    Route::get('/docs', function () {
        return redirect()->route('documentation.index');
    })->name('docs');
    Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
    Route::get('/features', [HomeController::class, 'features'])->name('features');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
    Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
    Route::get('/refund-policy', [HomeController::class, 'refunds'])->name('refunds');
    Route::get('/cookie-policy', [HomeController::class, 'cookiePolicy'])->name('cookies');
    Route::get('/data-retention', [HomeController::class, 'dataRetention'])->name('retention');
    Route::get('/acceptable-use', [HomeController::class, 'acceptableUse'])->name('aup');
    Route::get('/security-policy', [HomeController::class, 'security'])->name('security');
    Route::get('/data-ownership', [HomeController::class, 'dataOwnership'])->name('ownership');
    Route::get('/student-privacy', [HomeController::class, 'studentPrivacy'])->name('student-privacy');
    Route::get('/communication-policy', [HomeController::class, 'communicationPolicy'])->name('communication-policy');
    Route::get('/dpa', [HomeController::class, 'dpa'])->name('dpa');
    Route::get('/subprocessors', [HomeController::class, 'subprocessors'])->name('subprocessors');
    Route::get('/trust', [HomeController::class, 'trust'])->name('trust');
    Route::get('/legal-notices', [HomeController::class, 'legal'])->name('legal');

    // Main domain Sitemap & Robots.txt
    Route::get('/sitemap.xml', [Arzavo\SitemapController::class, 'index'])->name('sitemap');
    Route::get('/robots.txt', function () {
        $content = implode("\n", [
            "User-agent: *",
            "Allow: /",
            "",
            "Disallow: /admin/",
            "Disallow: /auth/",
            "Disallow: /dashboard",
            "Disallow: /checkout/",
            "",
            "Sitemap: " . url('/sitemap.xml'),
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    });

    Route::get('/pay', [PaymentController::class, 'index']);
    Route::get('/pay/{tenant}', [PaymentController::class, 'pay']);
    Route::get('/billing/checkout', [PaymentController::class, 'checkout'])->name('billing.checkout');
    Route::post('/tenant/payment/session/{plan}', [PaymentController::class, 'planSession'])->name('payment.session');
    Route::post('/tenant/payment/payu/{plan}', [PaymentController::class, 'payuInit'])->name('payment.payu.init');
    Route::match(['get', 'post'], '/payment/payu/success', [PaymentController::class, 'payuSuccess'])->name('payment.payu.success');
    Route::match(['get', 'post'], '/payment/payu/failure', [PaymentController::class, 'payuFailure'])->name('payment.payu.failure');
    Route::post('/payment/payu/webhook', [PaymentController::class, 'payuWebhook'])->name('payment.payu.webhook');

    // User Dashboard & Tenant Management Routes
    Route::middleware('auth:web')->group(function () {
        // Dashboard
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        // Admin Tenant Routes
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/reset-password', [TenantController::class, 'resetAdminPassword'])->name('tenants.reset-password');
        Route::get('/checkout/plan/{slug}', [Arzavo\PlanController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/process/{slug}', [Arzavo\PlanController::class, 'checkout'])->name('checkout.process');
        Route::post('/plans/{slug}/subscribe', [Arzavo\PlanController::class, 'subscribe'])->name('subscribe');
        Route::get('/check-subdomain', [TenantController::class, 'checkSubdomain']);
        Route::put('tenant/toggle-status/{id}', [TenantController::class, 'toggleStatus'])->name('tenant.toggle-status');
        Route::get('/verify-domain/{tenant}', [DomainController::class, 'verifyDomain'])->name('domain.verify');
    });
});
