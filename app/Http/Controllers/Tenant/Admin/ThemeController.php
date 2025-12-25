<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Theme;
use App\Models\Arzavo\TenantTheme;
use App\Models\Tenant\ThemeState;
use Tenancy\Facades\Tenancy;

class ThemeController
{
    /**
     * Show all available themes
     */
    public function index(Request $request)
    {
        $tenantId = app('currentTenant')->id;

        $themes = Theme::active()
            ->orderBy('is_paid')
            ->get()
            ->map(function ($theme) use ($tenantId) {

                $theme->has_access = true;

                if ($theme->is_paid) {
                    $theme->has_access = TenantTheme::where('tenant_id', $tenantId)
                        ->where('theme_id', $theme->id)
                        ->whereIn('status', ['purchased', 'active'])
                        ->exists();
                }

                return $theme;
            });

        $currentTheme = ThemeState::current();

        return view('tenant.admin.themes.index', compact(
            'themes',
            'currentTheme'
        ));
    }

    /**
     * Apply theme
     */
    public function apply(Request $request, $id)
    {

        $tenantId = app('currentTenant')->id;
        $theme = Theme::findOrFail($id);

        // Paid check
        if ($theme->is_paid) {
            $allowed = TenantTheme::where('tenant_id', $tenantId)
                ->where('theme_id', $theme->id)
                ->whereIn('status', ['purchased', 'active'])
                ->exists();

            if (! $allowed) {
                return back()->withErrors([
                    'theme' => 'This theme is not purchased'
                ]);
            }
        }

        /**
         * IMPORTANT:
         * Yahan sirf theme state set kar rahe hain
         * Actual theme.json apply logic tum already bana chuke ho
         */
        ThemeState::set([
            'theme_id' => $theme->id,
            'theme_name' => $theme->name,
            'theme_slug' => $theme->slug,
            'theme_version' => $theme->version,
            'applied_with_reset' => true,
        ]);

        // yahan tumhara existing theme apply service call hoga
        // app(ThemeApplyService::class)->apply($theme);

        return back()->with('success', 'Theme applied successfully');
    }
}
