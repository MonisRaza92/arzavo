<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantWebsitesController;
use App\Http\Controllers\ArzavoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\DomainVerificationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StudentsController as AdminStudentsController;
use App\Http\Controllers\Admin\ContentsController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CustomizesController;
use App\Http\Controllers\Admin\ImagesController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Students\StudentsController;
use App\Http\Controllers\Teachers\TeachersController;

Route::domain(config('app.domain'))->group(function () {
    Route::get('/', [ArzavoController::class, 'index'])->name('home');
    //Auth Routes
    Route::prefix('auth')->group(function () {
        Route::get('/login', [LoginController::class, 'login'])->name('login.form');
        Route::post('/login', [LoginController::class, 'loginHandle'])->name('login.handle');
        Route::get('/register', [LoginController::class, 'register'])->name('register.form');
        Route::post('/register', [LoginController::class, 'registerHandle'])->name('register.handle');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

Route::domain('{tenant}.' . config('app.domain'))->middleware('tenant')->group(function () {
    //Tenant Auth Routes
    Route::get('/', [TenantWebsitesController::class, 'index'])->name('tenant.home');
    Route::get('/{slug}', [TenantWebsitesController::class, 'index'])->where('slug', '^(?!\/$)[A-Za-z0-9-_]+$')->name('tenant.page');

    Route::prefix('account')->group(function () {
        Route::get('/login', [TenantLoginController::class, 'login'])->name('tenant.login.form');
        Route::post('/login', [TenantLoginController::class, 'loginHandle'])->name('tenant.login.handle');
        Route::get('/register', [TenantLoginController::class, 'register'])->name('tenant.register.form');
        Route::post('/register', [TenantLoginController::class, 'registerHandle'])->name('tenant.register.handle');
        Route::get('/logout', [TenantLoginController::class, 'logout'])->name('tenant.logout');
    });


    Route::middleware('auth')->group(function () {
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
    });
});

//Admin Routes
Route::domain(config('app.domain'))->prefix('admin')->middleware(['auth', 'role:admin'])->as('admin.')->group(function () {

    //Admin Tenant Routes
    Route::resource('tenants', TenantController::class);
    Route::put('tenant/toggle-status/{id}', [TenantController::class, 'toggleStatus'])->name('tenant.toggle-status');
    Route::get('/verify-domain/{tenant}', [DomainVerificationController::class, 'verify'])->name('domain.verify');
});

Route::domain('{tenant}.' . config('app.domain'))->prefix('admin')->middleware(['auth', 'role:admin', 'tenant'])->as('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

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


    //Admin Contents Routes
    Route::get('/notes', [ContentsController::class, 'notes'])->name('admin-notes');
    Route::get('/books', [ContentsController::class, 'books'])->name('admin-books');
    Route::get('/videos', [ContentsController::class, 'videos'])->name('admin-videos');
    Route::post('/content/upload', [ContentsController::class, 'uploadContent'])->name('admin-content-upload');
    Route::put('/content/update/{id}', [ContentsController::class, 'updateContent'])->name('admin-content-update');
    Route::delete('/content/delete/{id}', [ContentsController::class, 'deleteContent'])->name('admin-content-delete');


    //Admin Courses Routes
    Route::get('/courses', [CourseController::class, 'courses'])->name('admin-courses');
    Route::post('/upload/course', [CourseController::class, 'uploadCourse'])->name('upload-course');
    Route::put('/update/course', [CourseController::class, 'updateCourse'])->name('update-course');
    Route::delete('/delete/course', [CourseController::class, 'deleteCourse'])->name('delete-course');


    Route::get('/exams', [AdminController::class, 'exams'])->name('admin-exams');
    Route::get('/results', [AdminController::class, 'results'])->name('admin-results');
    Route::get('/library', [AdminController::class, 'library'])->name('admin-library');
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin-blogs');
    Route::get('/events', [AdminController::class, 'events'])->name('admin-events');


    //Admin Customizations Routes
    Route::resource('customizes', CustomizesController::class);
    Route::resource('images', ImagesController::class);
    Route::resource('pages', PageController::class);
    Route::prefix('builder')->name('builder.')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('index');
        Route::prefix('sections')->name('sections.')->group(function () {
            Route::post('/{page}', [SectionController::class, 'store'])->name('store');
            Route::put('/{section}', [SectionController::class, 'update'])->name('update');
            Route::delete('/{section}', [SectionController::class, 'destroy'])->name('destroy');
            Route::post('/{page}/reorder', [SectionController::class, 'reorder'])->name('reorder');
            Route::post('/{section}/toggle-active', [SectionController::class, 'toggleActive'])->name('toggleActive');
        });
    });


    //Admin Modules Routes
    Route::get('/modules', [ModulesController::class, 'adminModules'])->name('admin-modules');
    Route::post('/add/category', [ModulesController::class, 'addCategory'])->name('admin-add-category');
    Route::put('/update/category/{id}', [ModulesController::class, 'updateCategory'])->name('admin-update-category');
    Route::delete('/delete/category/{id}', [ModulesController::class, 'deleteCategory'])->name('admin-delete-category');
    Route::post('/add/class', [ModulesController::class, 'addClass'])->name('admin-add-class');
    Route::put('/update/class/{id}', [ModulesController::class, 'updateClass'])->name('admin-update-class');
    Route::delete('/delete/class/{id}', [ModulesController::class, 'deleteClass'])->name('admin-delete-class');
    Route::post('/add/subject', [ModulesController::class, 'addSubject'])->name('admin-add-subject');
    Route::put('/update/subject/{id}', [ModulesController::class, 'updateSubject'])->name('admin-update-subject');
    Route::delete('/delete/subject/{id}', [ModulesController::class, 'deleteSubject'])->name('admin-delete-subject');
    Route::post('/add/faq', [ModulesController::class, 'addFaq'])->name('admin-add-faq');
    Route::delete('/delete/faq/{id}', [ModulesController::class, 'deleteFaq'])->name('admin-delete-faq');


    //Admin Settings Routes
    Route::resource('settings', SettingsController::class);
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin-settings');
    Route::post('/update/settings', [SettingsController::class, 'store'])->name('update-settings');


    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin-reviews');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin-reports');
});
