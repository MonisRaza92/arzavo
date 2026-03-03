<?php

namespace App\Services\Theme;

use App\Models\Tenant\TenantTheme;
use App\Models\Tenant\ThemeGlobalDesign;
use App\Models\Tenant\ThemePageDesign;
use App\Models\Tenant\ColorScheme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThemeUninstaller
{
    public static function uninstall(TenantTheme $tenantTheme): void
    {
        DB::transaction(function () use ($tenantTheme) {

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Remove GLOBAL DESIGN
            |--------------------------------------------------------------------------
            */
            ThemeGlobalDesign::where(
                'tenant_theme_id',
                $tenantTheme->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Remove PAGE SECTIONS (NOT PAGES)
            |--------------------------------------------------------------------------
            */
            ThemePageDesign::where(
                'tenant_theme_id',
                $tenantTheme->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Remove COLOR SCHEMES
            |--------------------------------------------------------------------------
            */
            ColorScheme::where(
                'theme_id',
                $tenantTheme->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Delete Tenant Theme
            |--------------------------------------------------------------------------
            */
            $tenantTheme->delete();
        });
    }
}