<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        view()->composer('*', function ($view) {

            if (!app()->bound('currentTenant')) {
                return $view->with([
                    'settings' => [],
                    'customizes' => [],
                    'user' => $user = Auth::guard('web')->user() ?? null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST MEMORY CACHE (RUNS ONLY ONCE PER REQUEST)
            |--------------------------------------------------------------------------
            */

            static $settings = null;
            static $customizes = null;
            static $schemes = null;
            static $menus = null;
            static $user = null;

            if ($settings === null) {
                $settings = \App\Models\Tenant\Settings
                    ::pluck('value', 'key')
                    ->toArray();
            }

            if ($customizes === null) {
                $customizes = \App\Models\Tenant\Customizes
                    ::pluck('value', 'key')
                    ->toArray();
            }

            if ($schemes === null) {

                $themeId = activeThemeId();

                $schemes = \App\Models\Tenant\ColorScheme
                    ::where('theme_id', $themeId)
                    ->get()
                    ->keyBy('key');
            }

            if ($menus === null) {
                $menus = \App\Models\Tenant\Menu::all();
            }

            if ($user === null) {
                $user = Auth::guard('tenant')->user() ?? null;
            }

            return $view->with([
                'settings'      => $settings,
                'customizes'    => $customizes,
                'colorSchemes'  => $schemes,
                'menus'         => $menus,
                'user'          => $user
            ]);
        });
    }
}