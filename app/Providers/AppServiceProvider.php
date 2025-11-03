<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Courses;
use App\Models\Customizes;
use App\Models\Settings;
use App\Models\Images;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer(['*'], function ($view) {

            $tenantId = session('tenant_id');

            // Public pages me tenant missing ho sakta hai (skip)
            if (!$tenantId) {
                return;
            }

            // Tenant based Settings & Customizations
            $settings = cache()->remember("settings_$tenantId", 3600, function () use ($tenantId) {
                return Settings::where('tenant_id', $tenantId)->pluck('value', 'key')->toArray();
            });

            $customizes = cache()->remember("customizes_$tenantId", 3600, function () use ($tenantId) {
                return Customizes::where('tenant_id', $tenantId)->pluck('value', 'key')->toArray();
            });

            // Load User Roles under This Tenant
            $students = User::where('tenant_id', $tenantId)->where('role', 'student')->get();
            $teachers = User::where('tenant_id', $tenantId)->where('role', 'teacher')->get();
            $staff = User::where('tenant_id', $tenantId)->where('role', 'staff')->get();

            // Load Courses for Tenant
            $courses = Courses::where('tenant_id', $tenantId)->get();

            // Attach to all views
            $view->with([
                'settings'   => $settings,
                'customizes' => $customizes,
                'students'   => $students,
                'teachers'   => $teachers,
                'staff'      => $staff,
                'courses'    => $courses,
                'tenant_id'  => $tenantId
            ]);
        });
    }
}
