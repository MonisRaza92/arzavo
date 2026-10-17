<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Admin\AdminController;
use App\Http\Controllers\Tenant\Admin\StudentsController as AdminStudentsController;
use App\Http\Controllers\Tenant\Admin\AttendanceController;
use App\Http\Controllers\Tenant\Admin\UsersController as AdminUsersController;
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
use App\Http\Controllers\Tenant\Admin\BookController;
use App\Http\Controllers\Tenant\Admin\BookCategoryController;
use App\Http\Controllers\Tenant\Admin\CommunicationController;
use App\Http\Controllers\Tenant\Admin\AcademicCategoryController;
use App\Http\Controllers\Tenant\Admin\CurriculumBoardController;
use App\Http\Controllers\Tenant\Admin\BatchController;
use App\Http\Controllers\Tenant\Admin\FinanceController;
use App\Http\Controllers\Tenant\Admin\PaymentSettingsController;
use App\Http\Controllers\Tenant\Admin\FeePlansController;
use App\Http\Controllers\Tenant\Admin\TeacherController;
use App\Http\Controllers\Tenant\Admin\HolidayController;

Route::middleware('auth:tenant')->group(function () {
    Route::prefix('admin')->middleware('role:admin')->as('admin.')->group(function () {
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::post('/cancel-downgrade', [BillingController::class, 'cancelDowngrade'])->name('plan.cancel-downgrade');
    });

    Route::prefix('admin')->middleware(['role:admin', 'subscription'])->as('admin.')->group(function () {
        Route::resource('dashboard', AdminController::class);

        // Admin Students Routes
        Route::get('/students', [AdminStudentsController::class, 'adminStudents'])->name('students');
        Route::get('/students/admissions', [AdminStudentsController::class, 'admissions'])->name('students.admissions');
        Route::post('/students/admissions/store', [AdminStudentsController::class, 'admissionsStore'])->name('students.admissions.store');
        Route::post('/students/admissions/{id}/approve', [AdminStudentsController::class, 'admissionsApprove'])->name('students.admissions.approve');
        Route::post('/students/admissions/{id}/reject', [AdminStudentsController::class, 'admissionsReject'])->name('students.admissions.reject');
        Route::get('/academic/classes-by-category/{categoryId}', [AdminStudentsController::class, 'getClassesByCategory'])->name('academic.classes-by-category');
        Route::get('/academic/subjects-by-class/{classId}', [AdminStudentsController::class, 'getSubjectsByClass'])->name('academic.subjects-by-class');
        Route::get('/students/attendance', [AttendanceController::class, 'index'])->name('students.attendance');
        Route::get('/students/attendance/mark', [AttendanceController::class, 'markForm'])->name('students.attendance.mark');
        Route::post('/students/attendance/save', [AttendanceController::class, 'save'])->name('students.attendance.save');
        Route::post('/students/attendance/quick-mark', [AttendanceController::class, 'quickMark'])->name('students.attendance.quick-mark');
        Route::get('/students/fees', [FeePlansController::class, 'index'])->name('students.fees');
        Route::post('/students/fee-plans/store', [FeePlansController::class, 'storePlan'])->name('students.fee-plans.store');
        Route::post('/students/fee-plans/update/{id}', [FeePlansController::class, 'updatePlan'])->name('students.fee-plans.update');
        Route::delete('/students/fee-plans/delete/{id}', [FeePlansController::class, 'deletePlan'])->name('students.fee-plans.delete');
        Route::post('/students/fee-plans/override/{studentId}', [FeePlansController::class, 'saveStudentOverride'])->name('students.fee-plans.override');
        Route::get('/students/feedback', [AdminStudentsController::class, 'feedback'])->name('students.feedback');
        Route::get('/students/id-card', [AdminStudentsController::class, 'idCard'])->name('students.id-card');
        Route::post('/update/student/role', [AdminStudentsController::class, 'updateStudentRole'])->name('update-student-role');
        Route::post('/update/student/status', [AdminStudentsController::class, 'updateStudentStatus'])->name('update-student-status');
        Route::post('/student/update/{id}', [AdminStudentsController::class, 'updateStudentDetails'])->name('admin-student-update');
        Route::post('/student/reset-password/{id}', [AdminStudentsController::class, 'resetStudentPassword'])->name('admin-student-reset-password');
        Route::get('/student/profile/{username}', [AdminStudentsController::class, 'adminStudentProfile'])->name('admin-student-profile');
        Route::post('/student/profile/info/update/{id}', [AdminStudentsController::class, 'studentProfileInfoUpdate'])->name('admin-student-profile-info-update');
        Route::post('/student/fee/update/{id}', [AdminStudentsController::class, 'studentFeeUpdate'])->name('admin-student-fee-update');
        Route::post('/student/fee/payment/{id}', [AdminStudentsController::class, 'studentFeePaymentStore'])->name('admin-student-fee-payment');

        // Admin Users Routes
        Route::get('/users', [AdminUsersController::class, 'adminUsers'])->name('users');
        Route::post('/update/user/role', [AdminUsersController::class, 'updateUserRole'])->name('update-user-role');
        Route::post('/users/promote', [AdminUsersController::class, 'promoteToStudent'])->name('users.promote');
        Route::post('/update/user/status', [AdminUsersController::class, 'updateUserStatus'])->name('update-user-status');

        // Admin Teachers & Staff Management Routes
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
        Route::post('/teachers/store', [TeacherController::class, 'store'])->name('teachers.store');
        Route::post('/teachers/update/{id}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::post('/teachers/toggle-status/{id}', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
        Route::post('/teachers/reset-password/{id}', [TeacherController::class, 'resetPassword'])->name('teachers.reset-password');
        // Unified Class & Subject Allocations
        Route::get('/teachers/allocations', [TeacherController::class, 'allocations'])->name('teachers.allocations');
        Route::post('/teachers/allocations/assign', [TeacherController::class, 'saveAllocation'])->name('teachers.allocations.assign');
        Route::post('/teachers/allocations/bulk-assign', [TeacherController::class, 'bulkAssignTeacherSubjects'])->name('teachers.allocations.bulk-assign');


        Route::get('/teachers/attendance', [TeacherController::class, 'attendance'])->name('teachers.attendance');
        Route::post('/teachers/attendance/quick-mark', [TeacherController::class, 'attendanceQuickMark'])->name('teachers.attendance.quick-mark');
        Route::get('/teachers/payroll', [TeacherController::class, 'payroll'])->name('teachers.payroll');
        Route::post('/teachers/payroll/save-component', [TeacherController::class, 'savePayrollComponent'])->name('teachers.payroll.save-component');
        Route::post('/teachers/payroll/mark-paid', [TeacherController::class, 'markPayrollPaid'])->name('teachers.payroll.mark-paid');
        Route::post('/teachers/update-salary/{id}', [TeacherController::class, 'updateSalary'])->name('teachers.update-salary');
        Route::get('/teachers/profile/{username}', [TeacherController::class, 'profile'])->name('teachers.profile');
        Route::post('/teachers/profile/update/{id}', [TeacherController::class, 'updateProfile'])->name('teachers.profile.update');
        Route::get('/staffs', [AdminController::class, 'staffs'])->name('admin-staffs');

        // =========================================================================
        // Unified Curriculum Architecture Board & Academic Tiers
        // =========================================================================
        Route::get('/curriculum', [CurriculumBoardController::class, 'index'])->name('curriculum.board');
        Route::post('/curriculum/reorder', [CurriculumBoardController::class, 'reorder'])->name('curriculum.reorder');

        // Academic Categories
        Route::post('/academic-categories', [AcademicCategoryController::class, 'store'])->name('academic-categories.store');
        Route::get('/academic-categories/{id}/get', [AcademicCategoryController::class, 'get'])->name('academic-categories.get');
        Route::put('/academic-categories/{id}/update', [AcademicCategoryController::class, 'update'])->name('academic-categories.update');
        Route::delete('/academic-categories/{id}/delete', [AcademicCategoryController::class, 'destroy'])->name('academic-categories.destroy');

        // Classes & Courses
        Route::post('/classes/courses', [ClassCourseController::class, 'store'])->name('classes.courses.store');
        Route::get('/classes/courses/{id}/get', [ClassCourseController::class, 'get'])->name('classes.courses.get');
        Route::put('/classes/courses/{id}/update', [ClassCourseController::class, 'update'])->name('classes.courses.update');
        Route::delete('/classes/courses/{id}/delete', [ClassCourseController::class, 'destroy'])->name('classes.courses.destroy');

        // Subjects
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/subjects/{id}/get', [SubjectController::class, 'get'])->name('subjects.get');
        Route::put('/subjects/{id}/update', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
        Route::delete('/subjects/{id}/delete', [SubjectController::class, 'destroy']);

        // Batches Management
        Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
        Route::get('/batches/create', [BatchController::class, 'create'])->name('batches.create');
        Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
        Route::get('/batches/{id}/edit', [BatchController::class, 'edit'])->name('batches.edit');
        Route::get('/batches/{id}/get', [BatchController::class, 'get'])->name('batches.get');
        Route::get('/batches/{id}/students', [BatchController::class, 'students'])->name('batches.students');
        Route::put('/batches/{id}/update', [BatchController::class, 'update'])->name('batches.update');
        Route::delete('/batches/{id}/delete', [BatchController::class, 'destroy'])->name('batches.destroy');

        // Holiday & Academic Calendar Routes
        Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
        Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
        Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
        Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
        Route::post('/holidays/{id}/toggle-status', [HolidayController::class, 'toggleStatus'])->name('holidays.toggle-status');
        Route::post('/holidays/sync-defaults', [HolidayController::class, 'syncDefaults'])->name('holidays.sync-defaults');
        Route::post('/holidays/bulk-sundays', [HolidayController::class, 'bulkToggleSundays'])->name('holidays.bulk-sundays');
        Route::post('/holidays/manage-weekly-offs', [HolidayController::class, 'manageWeeklyOffs'])->name('holidays.manage-weekly-offs');
        Route::post('/holidays/quick-declare', [HolidayController::class, 'quickDeclare'])->name('holidays.quick-declare');

        // Legacy Academic URL Redirects -> Curriculum Board
        Route::get('/academic-categories', [AcademicCategoryController::class, 'index'])->name('academic-categories.index');
        Route::get('/classes/courses', [ClassCourseController::class, 'index'])->name('classes.courses.index');
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');

        // Admin Contents Routes
        Route::resource('contents', ContentController::class);

        // Admin Library Routes
        Route::get('/book-categories', [BookCategoryController::class, 'index'])->name('book-categories.index');
        Route::post('/book-categories', [BookCategoryController::class, 'store'])->name('book-categories.store');
        Route::get('/book-categories/{id}/get', [BookCategoryController::class, 'get'])->name('book-categories.get');
        Route::put('/book-categories/{id}/update', [BookCategoryController::class, 'update'])->name('book-categories.update');
        Route::delete('/book-categories/{id}/delete', [BookCategoryController::class, 'destroy'])->name('book-categories.destroy');

        Route::resource('books', BookController::class);
        Route::resource('blog', BlogController::class);

        // Admin Courses Routes
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

        // Admin Finance, Orders, Fees & Invoices Routes
        Route::get('/finance/orders', [FinanceController::class, 'index'])->name('finance.orders');
        Route::get('/finance/orders/{id}', [FinanceController::class, 'show'])->name('finance.orders.show');
        Route::post('/finance/orders/{id}/approve', [FinanceController::class, 'approvePayment'])->name('finance.orders.approve');
        Route::post('/finance/orders/{id}/fulfillment', [FinanceController::class, 'updateFulfillment'])->name('finance.orders.fulfillment');
        Route::get('/finance/fees', [FinanceController::class, 'fees'])->name('finance.fees');
        Route::post('/finance/fees/record', [FinanceController::class, 'recordFeePayment'])->name('finance.fees.record');
        Route::post('/finance/fees/update-status/{id}', [FinanceController::class, 'updateFeeStatus'])->name('finance.fees.update-status');
        Route::delete('/finance/fees/{id}', [FinanceController::class, 'deleteFeePayment'])->name('finance.fees.destroy');
        Route::get('/finance/invoices', [FinanceController::class, 'invoices'])->name('finance.invoices');
        Route::get('/finance/invoices/{id}', [FinanceController::class, 'invoiceShow'])->name('finance.invoices.show');
        Route::get('/finance/reports', [FinanceController::class, 'reports'])->name('finance.reports');

        // Payment Settings Routes
        Route::get('/settings/payments', [PaymentSettingsController::class, 'index'])->name('settings.payments');
        Route::post('/settings/payments', [PaymentSettingsController::class, 'store'])->name('settings.payments.store');

        // Admin Customizations Routes
        Route::resource('customizes', CustomizesController::class);

        // Admin Color Scheme Routes
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

        // Admin Settings Routes
        Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
        Route::get('/settings/website', [SettingsController::class, 'website'])->name('settings.website');
        Route::get('/settings/academics', [SettingsController::class, 'academics'])->name('settings.academics');
        Route::get('/settings/communication', [SettingsController::class, 'communication'])->name('settings.communication');
        Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
        Route::resource('settings', SettingsController::class);
    });
});
