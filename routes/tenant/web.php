<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Website\ThemePageController;
use App\Http\Controllers\Tenant\Website\CheckoutController;
use App\Http\Controllers\Tenant\Website\ReviewController;
use App\Http\Controllers\Tenant\Website\ItemAccessController;
use App\Http\Controllers\Tenant\User\UserController;
use App\Http\Controllers\Tenant\Students\StudentsController;
use App\Http\Controllers\Tenant\Teachers\TeachersController;
use App\Http\Controllers\Tenant\SitemapController;

Route::get('/robots.txt', function () {
    $settings = \App\Models\Tenant\Settings::pluck('value', 'key')->toArray();
    $allowIndexing = $settings['allow_indexing'] ?? 1;

    if ($allowIndexing == 0) {
        $content = implode("\n", [
            "User-agent: *",
            "Disallow: /",
        ]);
    } else {
        $content = implode("\n", [
            "User-agent: *",
            "Allow: /",
            "",
            "Disallow: /admin/",
            "Disallow: /account/",
            "Disallow: /builder/",
            "Disallow: /preview/",
            "",
        ]);

        if ($settings['sitemap_enabled'] ?? 1) {
            $content .= "\nSitemap: " . url('/sitemap.xml');
        }
    }

    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', SitemapController::class)->name('tenant.sitemap');

Route::get('/manifest.webmanifest', function () {
    $tenant = app()->bound('currentTenant') ? app('currentTenant') : null;
    $siteName = $tenant?->name ?? config('app.name');

    $manifest = [
        'name' => $siteName,
        'short_name' => $siteName,
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => '#ffffff',
        'icons' => [
            [
                'src' => media($tenant?->logo ?? ''),
                'sizes' => '192x192',
                'type' => 'image/png',
            ],
            [
                'src' => media($tenant?->logo ?? ''),
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
        ],
    ];

    return response()->json($manifest, 200, ['Content-Type' => 'application/manifest+json']);
})->name('tenant.manifest');

// Expired page
Route::get('/subscription-expired', function () {
    return app(ThemePageController::class)->expired();
})->name('subscription.expired');

// Public Pages
Route::get('/', function () {
    return app(ThemePageController::class)->system('home');
})->name('tenant.home');

Route::get('/courses', function () {
    return app(ThemePageController::class)->system('courses');
})->name('tenant.courses');

Route::get('course', function () {
    return app(ThemePageController::class)->system('course');
})->name('tenant.course');

Route::get('/blogs', function () {
    return app(ThemePageController::class)->system('blogs');
})->name('tenant.blogs');

Route::get('blog', function () {
    return app(ThemePageController::class)->system('blog');
})->name('tenant.blog');

Route::get('/book-categories', function () {
    return app(ThemePageController::class)->system('book-categories');
})->name('tenant.book-categories');

Route::get('/books', function () {
    return app(ThemePageController::class)->system('books');
})->name('tenant.books');

Route::get('book', function () {
    return app(ThemePageController::class)->system('book');
})->name('tenant.book');

Route::get('contact', function () {
    return app(ThemePageController::class)->system('contact');
})->name('tenant.contact');

Route::get('/preview/{theme}/{theme_id}/{slug}', function ($theme, $themeId = null, $slug = 'home') {
    return app(ThemePageController::class)->preview($theme, $themeId, $slug);
})->where('slug', '[A-Za-z0-9-_]+')->name('website.preview');

// Theme Asset Server Route
Route::get('/theme-asset/{theme}/{path}', function ($theme, $path) {
    $fullPath = resource_path("views/tenant/themes/{$theme}/assets/{$path}");
    if (file_exists($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*')->name('theme.asset');

Route::post('/contact-submit', [ThemePageController::class, 'contactSubmit'])->name('contact.form');
Route::post('/newsletter-submit', [ThemePageController::class, 'newsletterSubmit'])->name('newsletter.submit');

// Universal Checkout & Review Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.submit');
Route::post('/checkout/razorpay/verify', [CheckoutController::class, 'verifyRazorpay'])->name('checkout.razorpay.verify');
Route::match(['get', 'post'], '/checkout/payu/success', [CheckoutController::class, 'payuSuccess'])->name('checkout.payu.success');
Route::match(['get', 'post'], '/checkout/payu/failure', [CheckoutController::class, 'payuFailure'])->name('checkout.payu.failure');
Route::match(['get', 'post'], '/checkout/paytm/callback', [CheckoutController::class, 'paytmCallback'])->name('checkout.paytm.callback');
Route::get('/checkout/cashfree/verify/{orderNumber}', [CheckoutController::class, 'verifyCashfree'])->name('checkout.cashfree.verify');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::match(['get', 'post'], '/api/v1/payments/webhook/{gateway?}', [\App\Http\Controllers\Tenant\Website\PaymentWebhookController::class, 'handle'])->name('tenant.payment.webhook');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Item Access Gate
Route::get('/item/download', [ItemAccessController::class, 'download'])->name('item.download');
Route::get('/item/read', [ItemAccessController::class, 'read'])->name('item.read');

// Academic Cascade Helper Routes
Route::get('/academic/classes-by-category/{categoryId}', [\App\Http\Controllers\Tenant\Admin\StudentsController::class, 'getClassesByCategory'])->name('web.academic.classes-by-category');
Route::get('/academic/subjects-by-class/{classId}', [\App\Http\Controllers\Tenant\Admin\StudentsController::class, 'getSubjectsByClass'])->name('web.academic.subjects-by-class');

Route::get('/{slug}', function ($slug) {
    return app(ThemePageController::class)->page($slug);
})->where('slug', '[A-Za-z0-9-_]+')->name('tenant.pages');

// Authenticated Portals (User, Student, Teacher)
Route::middleware('auth:tenant')->group(function () {
    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->middleware('role:user')->name('user-dashboard');

    Route::prefix('user')->middleware('role:user')->as('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [UserController::class, 'orders'])->name('orders');
        Route::get('/downloads', [UserController::class, 'downloads'])->name('downloads');
        Route::get('/invoices', [UserController::class, 'invoices'])->name('invoices');
        Route::get('/inquiries', [UserController::class, 'inquiries'])->name('inquiries');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::post('/apply-admission', [UserController::class, 'applyAdmission'])->name('apply-admission');
    });

    // Students Routes
    Route::get('/students-dashboard', [StudentsController::class, 'dashboard'])->middleware('role:student')->name('students-dashboard');

    Route::prefix('student')->middleware('role:student')->as('student.')->group(function () {
        Route::get('/dashboard', [StudentsController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [StudentsController::class, 'courses'])->name('courses');
        Route::get('/books', [StudentsController::class, 'books'])->name('books');
        Route::get('/assignments', [StudentsController::class, 'assignments'])->name('assignments');
        Route::get('/fees', [StudentsController::class, 'fees'])->name('fees');
        Route::post('/fees/pay-online', [StudentsController::class, 'payFeeOnline'])->name('fees.pay-online');
        Route::get('/attendance', [StudentsController::class, 'attendance'])->name('attendance');
        Route::get('/orders', [StudentsController::class, 'orders'])->name('orders');
        Route::get('/certificates', [StudentsController::class, 'certificates'])->name('certificates');
        Route::get('/profile', [StudentsController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [StudentsController::class, 'updateProfile'])->name('profile.update');
    });

    // Teachers Routes
    Route::get('/teachers-dashboard', [TeachersController::class, 'dashboard'])->middleware('role:teacher')->name('teachers-dashboard');
});
