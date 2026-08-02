<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Arzavo\HomeController;
use App\Http\Controllers\Arzavo\DocumentationController;
use App\Http\Controllers\Arzavo\TenantController;
use App\Http\Controllers\Arzavo\DomainController;
use App\Http\Controllers\Arzavo;
use App\Http\Controllers\Arzavo\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Tenant\Website\ThemePageController;
use App\Http\Controllers\Tenant\Admin\AdminController;
use App\Http\Controllers\Tenant\Admin\StudentsController as AdminStudentsController;
use App\Http\Controllers\Tenant\Admin\ContentController;
use App\Http\Controllers\Tenant\Admin\BlogController;
use App\Http\Controllers\Tenant\Admin\CourseController;
use App\Http\Controllers\Tenant\Admin\CustomizesController;
use App\Http\Controllers\Tenant\Admin\ColorSchemeController;
use App\Http\Controllers\Tenant\Admin\SettingsController;
use App\Http\Controllers\Tenant\Admin\BillingController;
use App\Http\Controllers\Tenant\Admin\PageController;
use App\Http\Controllers\Tenant\Admin\ThemeController;
use App\Http\Controllers\Tenant\Admin\SectionController;
use App\Http\Controllers\Tenant\Admin\BlockController;
use App\Http\Controllers\Tenant\Admin\ClassCourseController;
use App\Http\Controllers\Tenant\Admin\SubjectController;
use App\Http\Controllers\Tenant\Admin\MenuController;
use App\Http\Controllers\Tenant\Admin\MenuItemController;
use App\Http\Controllers\Tenant\Admin\CourseModuleController;
use App\Http\Controllers\Tenant\Admin\CourseLessonController;
use App\Http\Controllers\Tenant\Admin\CourseModuleLessonController;
use App\Http\Controllers\Tenant\User\UserController;
use App\Http\Controllers\Tenant\Students\StudentsController;
use App\Http\Controllers\Tenant\Teachers\TeachersController;
use App\Http\Controllers\Tenant\SitemapController;
use App\Http\Controllers\Tenant\Admin\BookController;
use App\Http\Controllers\Tenant\Admin\BookCategoryController;
use App\Http\Controllers\Tenant\Admin\CommunicationController;

Route::view('/offline', 'offline');
Route::post('/cashfree/webhook', [PaymentController::class, 'webhook']);

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


    Route::get('/pay', [PaymentController::class, 'index']);
    Route::get('/pay/{tenant}', [PaymentController::class, 'pay']);
    Route::get('/billing/checkout', [PaymentController::class, 'checkout'])->name('billing.checkout');
    Route::post('/tenant/payment/session/{plan}', [PaymentController::class, 'planSession'])->name('payment.session');

    //Auth Routes
    Route::prefix('auth')->group(function () {
        Route::get('/login', [LoginController::class, 'login'])->name('login.form');
        Route::post('/login', [LoginController::class, 'loginHandle'])->name('login.handle');
        Route::get('/register', [LoginController::class, 'register'])->name('register.form');
        Route::post('/register', [LoginController::class, 'registerHandle'])->name('register.handle');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/{provider}', [LoginController::class, 'redirect'])->name('social.redirect');
        Route::get('/{provider}/callback', [LoginController::class, 'callback'])->name('social.callback');
        Route::post('/google/onetap', [LoginController::class, 'oneTap'])->name('google.onetap');
    });

    //Admin Routes
    Route::middleware('auth:web')->group(function () {
        // Dashboard
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        //Admin Tenant Routes
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/reset-password', [TenantController::class, 'resetAdminPassword'])->name('tenants.reset-password');
        Route::get('/checkout/plan/{slug}', [Arzavo\PlanController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/process/{slug}', [Arzavo\PlanController::class, 'checkout'])->name('checkout.process');
        Route::post('/plans/{slug}/subscribe', [BillingController::class, 'subscribe'])->name('subscribe');
        Route::get('/check-subdomain', [TenantController::class, 'checkSubdomain']);
        Route::put('tenant/toggle-status/{id}', [TenantController::class, 'toggleStatus'])->name('tenant.toggle-status');
        Route::get('/verify-domain/{tenant}', [DomainController::class, 'verifyDomain'])->name('domain.verify');

        Route::prefix('admin')->middleware('role:admin')->as('arzavo.admin.')->group(function () {
            Route::resource('plans', Arzavo\Admin\PlanController::class);
            Route::resource('users', Arzavo\Admin\UserController::class);
            Route::resource('tenants', Arzavo\Admin\TenantController::class);
        });
    });
});

if (!function_exists('registerDomains')) {
    function registerDomains($domain)
    {

        Route::domain($domain)->middleware('tenant')->group(function () {

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

                return response($content, 200)
                    ->header('Content-Type', 'text/plain');
            });

            Route::get('/sitemap.xml', SitemapController::class)
                ->name('tenant.sitemap');

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

            // 🔥 IMPORTANT: expired page (theme controlled)
            Route::get('/subscription-expired', function () {
                return app(ThemePageController::class)
                    ->expired();
            })->name('subscription.expired');

            //Tenant Auth Routes
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


            Route::get('/preview/{theme}/{theme_id}/{slug}', function ($theme, $themeId = null, $slug = 'home') {
                return app(ThemePageController::class)->preview($theme, $themeId, $slug);
            })
                ->where('slug', '[A-Za-z0-9-_]+')
                ->name('website.preview');

            Route::post('/contact-submit', [ThemePageController::class, 'contactSubmit'])->name('contact.form');
            Route::post('/newsletter-submit', [ThemePageController::class, 'newsletterSubmit'])->name('newsletter.submit');

            // 🛒 Universal Checkout & Review Routes
            Route::get('/checkout', [\App\Http\Controllers\Tenant\Website\CheckoutController::class, 'show'])->name('checkout.show');
            Route::post('/checkout', [\App\Http\Controllers\Tenant\Website\CheckoutController::class, 'process'])->name('checkout.submit');
            Route::get('/checkout/success/{orderNumber}', [\App\Http\Controllers\Tenant\Website\CheckoutController::class, 'success'])->name('checkout.success');
            Route::post('/reviews', [\App\Http\Controllers\Tenant\Website\ReviewController::class, 'store'])->name('reviews.store');

            // 📥 Item Access Gate (Download / Read with Login + Entitlement check)
            Route::get('/item/download', [\App\Http\Controllers\Tenant\Website\ItemAccessController::class, 'download'])->name('item.download');
            Route::get('/item/read', [\App\Http\Controllers\Tenant\Website\ItemAccessController::class, 'read'])->name('item.read');

            Route::get('/{slug}', function ($slug) {
                return app(ThemePageController::class)->page($slug);
            })
                ->where('slug', '[A-Za-z0-9-_]+')
                ->name('tenant.pages');


            Route::prefix('account')->group(function () {
                Route::get('/login', [TenantLoginController::class, 'login'])->name('tenant.login');
                Route::post('/login', [TenantLoginController::class, 'loginHandle'])->name('tenant.login.handle');
                Route::get('/register', [TenantLoginController::class, 'register'])->name('tenant.register');
                Route::post('/register', [TenantLoginController::class, 'registerHandle'])->name('tenant.register.handle');
                Route::get('/logout', [TenantLoginController::class, 'logout'])->name('tenant.logout');
            });


            Route::middleware('auth:tenant')->group(function () {
                //Profile Routes

                Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
                Route::post('/profile/info/update', [ProfileController::class, 'profileInfoUpdate'])->name('profile-info-update');
                Route::post('/profile/banner/update', [ProfileController::class, 'profileBannerUpdate'])->name('profile-banner-update');
                Route::post('/profile/picture/update', [ProfileController::class, 'profilePictureUpdate'])->name('profile-picture-update');

                //User Routes
                Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user-dashboard');

                //Students Routes
                Route::get('/students-dashboard', [StudentsController::class, 'dashboard'])->name('students-dashboard');

                //Teachers Routes
                Route::get('/teachers-dashboard', [TeachersController::class, 'dashboard'])->name('teachers-dashboard');

                Route::prefix('admin')->middleware('role:admin')->as('admin.')->group(function () {

                    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
                    // Route::get('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
                    Route::post('/cancel-downgrade', [BillingController::class, 'cancelDowngrade'])->name('plan.cancel-downgrade');

                });
                Route::prefix('admin')->middleware(['role:admin', 'subscription'])->as('admin.')->group(function () {
                    Route::resource('dashboard', AdminController::class);
                    //Admin Students Routes
                    Route::get('/students', [AdminStudentsController::class, 'adminStudents'])->name('admin-students');
                    Route::post('/update/student/role', [AdminStudentsController::class, 'updateStudentRole'])->name('update-student-role');
                    Route::post('/update/student/status', [AdminStudentsController::class, 'updateStudentStatus'])->name('update-student-status');
                    Route::get('/student/profile/{username}', [AdminStudentsController::class, 'adminStudentProfile'])->name('admin-student-profile');
                    Route::post('/student/profile/info/update/{id}', [AdminStudentsController::class, 'studentProfileInfoUpdate'])->name('admin-student-profile-info-update');
                    Route::post('/student/fee/update/{id}', [AdminStudentsController::class, 'studentFeeUpdate'])->name('admin-student-fee-update');


                    Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin-teachers');
                    Route::get('/staffs', [AdminController::class, 'staffs'])->name('admin-staffs');
                    Route::get('/classes', [AdminController::class, 'classes'])->name('admin-classes');

                    //Admin Subjects Routes
                    Route::get('/classes/courses', [ClassCourseController::class, 'index'])->name('classes.courses.index');
                    Route::post('/classes/courses', [ClassCourseController::class, 'store'])->name('classes.courses.store');
                    Route::get('/classes/courses/{id}/get', [ClassCourseController::class, 'get'])->name('classes.courses.get');
                    Route::put('/classes/courses/{id}/update', [ClassCourseController::class, 'update'])->name('classes.courses.update');
                    Route::delete('/classes/courses/{id}/delete', [ClassCourseController::class, 'destroy'])->name('classes.courses.destroy');
                    Route::get('/academic-categories', [\App\Http\Controllers\Tenant\Admin\AcademicCategoryController::class, 'index'])->name('academic-categories.index');
                    Route::post('/academic-categories', [\App\Http\Controllers\Tenant\Admin\AcademicCategoryController::class, 'store'])->name('academic-categories.store');
                    Route::get('/academic-categories/{id}/get', [\App\Http\Controllers\Tenant\Admin\AcademicCategoryController::class, 'get'])->name('academic-categories.get');
                    Route::put('/academic-categories/{id}/update', [\App\Http\Controllers\Tenant\Admin\AcademicCategoryController::class, 'update'])->name('academic-categories.update');
                    Route::delete('/academic-categories/{id}/delete', [\App\Http\Controllers\Tenant\Admin\AcademicCategoryController::class, 'destroy'])->name('academic-categories.destroy');

                    Route::resource('subjects', SubjectController::class);
                    Route::get('/subjects/{id}/get', [SubjectController::class, 'get'])->name('subjects.get');
                    Route::put('/subjects/{id}/update', [SubjectController::class, 'update'])->name('subjects.update_custom');


                    //Admin Contents Routes
                    Route::resource('contents', ContentController::class);

                    //Admin Library Routes
                    Route::get('/book-categories', [BookCategoryController::class, 'index'])->name('book-categories.index');
                    Route::post('/book-categories', [BookCategoryController::class, 'store'])->name('book-categories.store');
                    Route::get('/book-categories/{id}/get', [BookCategoryController::class, 'get'])->name('book-categories.get');
                    Route::put('/book-categories/{id}/update', [BookCategoryController::class, 'update'])->name('book-categories.update');
                    Route::delete('/book-categories/{id}/delete', [BookCategoryController::class, 'destroy'])->name('book-categories.destroy');

                    Route::resource('books', BookController::class);

                    Route::resource('blog', BlogController::class);

                    //Admin Courses Routes
                    Route::resource('courses', CourseController::class);
                    Route::put('courses/{course}/status', [CourseController::class, 'status'])->name('course.status');
                    Route::resource('courses.modules', CourseModuleController::class);
                    Route::resource('courses.lessons', CourseLessonController::class);
                    Route::resource('modules.lessons', CourseModuleLessonController::class);

                    Route::get('/exams', [AdminController::class, 'exams'])->name('admin-exams');
                    Route::get('/results', [AdminController::class, 'results'])->name('admin-results');
                    Route::get('/library', [AdminController::class, 'library'])->name('admin-library');
                    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin-blogs');
                    Route::get('/events', [AdminController::class, 'events'])->name('admin-events');

                    // Admin Communication Routes
                    Route::get('/communication/inquiries', [CommunicationController::class, 'inquiries'])->name('communication.inquiries');
                    Route::delete('/communication/inquiries/{id}', [CommunicationController::class, 'inquiryDelete'])->name('communication.inquiries.delete');
                    Route::get('/communication/subscribers', [CommunicationController::class, 'subscribers'])->name('communication.subscribers');
                    Route::delete('/communication/subscribers/{id}', [CommunicationController::class, 'subscriberDelete'])->name('communication.subscribers.delete');

                    // 💼 Admin Finance & Order Ledger Routes
                    Route::get('/finance/orders', [\App\Http\Controllers\Tenant\Admin\FinanceController::class, 'index'])->name('finance.orders');
                    Route::get('/finance/orders/{id}', [\App\Http\Controllers\Tenant\Admin\FinanceController::class, 'show'])->name('finance.orders.show');
                    Route::post('/finance/orders/{id}/approve', [\App\Http\Controllers\Tenant\Admin\FinanceController::class, 'approvePayment'])->name('finance.orders.approve');
                    Route::post('/finance/orders/{id}/fulfillment', [\App\Http\Controllers\Tenant\Admin\FinanceController::class, 'updateFulfillment'])->name('finance.orders.fulfillment');

                    // 💳 Payment Settings Routes
                    Route::get('/settings/payments', [\App\Http\Controllers\Tenant\Admin\PaymentSettingsController::class, 'index'])->name('settings.payments');
                    Route::post('/settings/payments', [\App\Http\Controllers\Tenant\Admin\PaymentSettingsController::class, 'store'])->name('settings.payments.store');

                    //Admin Customizations Routes
                    Route::resource('customizes', CustomizesController::class);

                    // Admin Color Sheme Routes
                    Route::resource('scheme', ColorSchemeController::class);

                    // Admin Pages Routes
                    Route::resource('pages', PageController::class);

                    // Admin Menus Routes
                    Route::resource('menus', MenuController::class);
                    Route::resource('menu-items', MenuItemController::class);
                    Route::post('/menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

                    // Admin Themes Routes
                    Route::resource('themes', ThemeController::class);
                    Route::post('/themes/install/{id}', [ThemeController::class, 'install'])->name('themes.install');
                    Route::post('/themes/upload', [ThemeController::class, 'upload'])->name('themes.upload');
                    Route::post('/themes/publish/{id}', [ThemeController::class, 'publish'])->name('themes.publish');
                    Route::post('/themes/copy/{id}', [ThemeController::class, 'copy'])->name('themes.copy');

                    // Admin Builder Routes
                    Route::prefix('builder/{theme}')->name('builder.')->group(function () {
                        Route::get('/', [SectionController::class, 'index'])->name('index');
                        Route::prefix('{page}/')->name('sections.')->group(function () {
                            Route::post('/', [SectionController::class, 'store'])->name('store');
                            Route::post('/template', [SectionController::class, 'storeTemplate'])->name('store.template');
                            Route::prefix('/{sectionId}')->group(function () {
                                Route::put('/', [SectionController::class, 'update'])->name('update');
                                Route::delete('/', [SectionController::class, 'destroy'])->name('destroy');
                                Route::post('/reorder', [SectionController::class, 'reorder'])->name('reorder');
                                Route::post('/toggle-active', [SectionController::class, 'toggleActive'])->name('toggleActive');
                                Route::post('/blocks', [BlockController::class, 'store'])->name('blocks.store');
                                Route::prefix('/{blockId}/')->name('blocks.')->group(function () {
                                    Route::post('/nested', [BlockController::class, 'storeNested'])->name('nested.store');
                                    Route::put('/update', [BlockController::class, 'update'])->name('update');
                                    Route::delete('/delete', [BlockController::class, 'destroy'])->name('destroy');
                                    Route::post('/toggle-active', [BlockController::class, 'toggleActive'])->name('toggleActive');
                                    Route::post('/reorder', [BlockController::class, 'reorder'])->name('reorder');
                                    Route::post('/nested/reorder', [BlockController::class, 'reorderNested'])->name('nested.reorder');
                                });
                            });
                        });
                    });

                    //Admin Settings Routes
                    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
                    Route::get('/settings/website', [SettingsController::class, 'website'])->name('settings.website');
                    Route::get('/settings/academics', [SettingsController::class, 'academics'])->name('settings.academics');
                    Route::get('/settings/communication', [SettingsController::class, 'communication'])->name('settings.communication');
                    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
                    Route::resource('settings', SettingsController::class);
                });
            });
        });
    }
}


// ✅ OPTIMIZED DOMAIN REGISTRATION
$currentHost = request()->getHost();
$baseDomain = config('app.domain');

// ✅ AGAR MAIN DOMAIN PE HAI TOH SAB REGISTER KARO
if ($currentHost === $baseDomain || $currentHost === "www." . $baseDomain) {
    // Database se saare active tenants
    $tenants = \App\Models\Arzavo\Tenant::all();

    foreach ($tenants as $tenant) {
        // ✅ Subdomain register karo (as-it-is, e.g., "tenant1")
        if (!empty($tenant->subdomain) && $tenant->subdomain !== $baseDomain && $tenant->subdomain !== "www." . $baseDomain) {
            registerDomains($tenant->subdomain);
        }

        // ✅ Verified custom domain register karo (as-it-is, e.g., "school.com")
        if ($tenant->domain_verified && !empty($tenant->custom_domain)) {
            if ($tenant->custom_domain !== $baseDomain && $tenant->custom_domain !== "www." . $baseDomain) {
                registerDomains($tenant->custom_domain);
            }
        }
    }
}
// ✅ AGAR TENANT DOMAIN PE HAI TOH SIRF CURRENT DOMAIN REGISTER KARO
else {
    registerDomains($currentHost);
}
