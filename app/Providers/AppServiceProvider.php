<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        view()->composer('*', function ($view) {

            // If TenantMiddleware has NOT run yet → RETURN immediately
            if (!app()->bound('currentTenant')) {
                // Main platform only gets main user
                $customizes = [];
                $settings = [];
                return $view->with([
                    'user' => Auth::guard('web')->user(),
                    'customizes' => $customizes,
                    'settings'   => $settings,
                ]);
            } else {

                // Otherwise TENANT DB is active → now load tenant models
                $tenant = app('currentTenant');

                $user = Auth::guard('tenant')->user();

                $settings = \App\Models\Tenant\Settings::pluck('value', 'key')->toArray();
                $customizes = \App\Models\Tenant\Customizes::pluck('value', 'key')->toArray();

                $students = \App\Models\Tenant\User::where('role', 'student')->get();
                $teachers = \App\Models\Tenant\User::where('role', 'teacher')->get();
                $staff = \App\Models\Tenant\User::where('role', 'staff')->get();
                $colorSchemes = \App\Models\Tenant\ColorScheme::where('theme_id', app('currentThemeId'))->orderBy('id', 'asc')->get();

                $contents = \App\Models\Tenant\Content::all();

                $classCourses = \App\Models\Tenant\ClassCourse::all();
                $subjects = \App\Models\Tenant\Subject::all();
                $courses = \App\Models\Tenant\Course::all();
                $menus = \App\Models\Tenant\Menu::all();

                $activeTheme = app('activeTheme');

                return $view->with([
                    'user'          => $user,
                    'settings'      => $settings,
                    'customizes'    => $customizes,
                    'students'      => $students,
                    'teachers'      => $teachers,
                    'staff'         => $staff,
                    'courses'       => $courses,
                    'classCourses'  => $classCourses,
                    'subjects'      => $subjects,
                    'contents'      => $contents,
                    'menus'         => $menus,
                    'activeTheme'   => $activeTheme,
                    'colorSchemes'  => $colorSchemes
                ]);
            }
        });
    }
}
