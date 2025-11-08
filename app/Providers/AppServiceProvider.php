<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Courses;
use App\Models\Customizes;
use App\Models\Settings;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        view()->composer(['*'], function ($view) {

            // ✅ 1. FIRST - GLOBAL $user VARIABLE SET KARO
            $mainDomain = config('app.domain');
            $currentDomain = request()->getHost();

            // Auto-detect guard based on domain
            if ($currentDomain === $mainDomain || $currentDomain === "www." . $mainDomain) {
                $user = Auth::guard('web')->user(); // Main domain - web guard
            } else {
                $user = Auth::guard('tenant')->user(); // Tenant domain - tenant guard
            }

            // ✅ 2. TENANT SPECIFIC DATA (Only if tenant exists)
            $tenantId = session('tenant_id');
            $settings = [];
            $customizes = [];
            $students = [];
            $teachers = [];
            $staff = [];
            $courses = [];

            // Tenant based data load karo only if tenant exists
            if ($tenantId) {
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
            }

            // ✅ 3. ATTACH ALL DATA TO VIEWS
            $view->with([
                'user'       => $user, // ✅ GLOBAL USER VARIABLE
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
