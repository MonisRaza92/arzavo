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

function registerDomains($domain)
{

    Route::domain($domain)->middleware('tenant')->group(function () {

        Route::get('/robots.txt', function () {

            $content = implode("\n", [
                "User-agent: *",
                "Allow: /",
                "",
                "Disallow: /admin/",
                "Disallow: /account/",
                "Disallow: /builder/",
                "Disallow: /preview/",
                "",
                "Sitemap: " . url('/sitemap.xml'),
            ]);

            return response($content, 200)
                ->header('Content-Type', 'text/plain');
        });

        Route::get('/sitemap.xml', SitemapController::class)
            ->name('tenant.sitemap');

        // 🔥 IMPORTANT: expired page (theme controlled)
        Route::get('/subscription-expired', function () {
            return app(ThemePageController::class)
                ->expired();
        })->name('subscription.expired');

        //Tenant Auth Routes
        Route::get('/', function () {
            return app(ThemePageController::class)->system('home');
        })->name('tenant.home');

        Route::get('/about', function () {
            return app(ThemePageController::class)->system('about');
        })->name('tenant.about');

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
        })->name('tenant.course');

        Route::get('/content-store', function () {
            return app(ThemePageController::class)->system('content-store');
        })->name('tenant.content-store');

        Route::get('content', function () {
            return app(ThemePageController::class)->system('content');
        })->name('tenant.content');

        Route::get('/privacy-policy', function () {
            return app(ThemePageController::class)->system('privacy-policy');
        })->name('tenant.privacy-policy');

        Route::get('/terms-conditions', function () {
            return app(ThemePageController::class)->system('terms-conditions');
        })->name('tenant.terms-conditions');

        Route::get('/preview/{theme}/{theme_id}/{slug}', function ($theme, $themeId = null, $slug = 'home') {
            return app(ThemePageController::class)->preview($theme, $themeId, $slug);
        })
            ->where('slug', '[A-Za-z0-9-_]+')
            ->name('website.preview');

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
                Route::resource('subjects', SubjectController::class);
                Route::get('/subjects/{id}/get', [SubjectController::class, 'get'])->name('subjects.get');
                Route::put('/subjects/{id}/update', [SubjectController::class, 'update'])->name('subjects.update');


                //Admin Contents Routes
                Route::resource('contents', ContentController::class);

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
                Route::resource('settings', SettingsController::class);
            });
        });
    });
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

    // // Manual domains
    // registerDomains('sanskriti.test');
    // registerDomains('arzaq.arzavo.test');
}
// ✅ AGAR TENANT DOMAIN PE HAI TOH SIRF CURRENT DOMAIN REGISTER KARO
else {
    registerDomains($currentHost);
}
