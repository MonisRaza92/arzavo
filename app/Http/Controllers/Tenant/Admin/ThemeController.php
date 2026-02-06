<?php
namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Arzavo\Theme; // central DB
use App\Models\Tenant\TenantTheme;
use App\Services\Theme\ThemeInstaller;
use Illuminate\Support\Facades\DB;

class ThemeController
{
    /**
     * List all themes
     */
    public function index()
    {
        $themes = Theme::active()->get();

        $installedThemes = TenantTheme::all()->keyBy('theme_id');

        return view('tenant.admin.themes.index', compact(
            'themes',
            'installedThemes'
        ));
    }

    /**
     * INSTALL THEME (DRAFT)
     */
    public function install($themeId)
    {
        $theme = Theme::findOrFail($themeId);

        // already installed?
        if (TenantTheme::where('theme_id', $theme->id)->exists()) {
            return back()->with('info', 'Theme already installed');
        }

        DB::transaction(function () use ($theme) {

            // 1️⃣ Attach theme to tenant
            $tenantTheme = TenantTheme::create([
                'theme_id' => $theme->id,
                'theme_slug' => $theme->slug,
                'theme_version' => $theme->version,
                'status' => 'draft',
                'installed_at' => now(),
            ]);

            // 2️⃣ Apply theme using SERVICE (🔥 main point)
            ThemeInstaller::installForTenant(
                $theme->slug,
                $tenantTheme
            );
        });

        return back()->with('success', 'Theme installed successfully');
    }



    /**
     * PUBLISH THEME (ATOMIC SWITCH)
     */
    public function publish($tenantThemeId)
    {
        DB::transaction(function () use ($tenantThemeId) {

            TenantTheme::where('status', 'published')
                ->update(['status' => 'draft']);

            TenantTheme::where('id', $tenantThemeId)
                ->update([
                    'status' => 'published',
                    'is_active' => true,
                    'published_at' => now()
                ]);
        });

        session()->forget('preview_theme_id');

        return back()->with('success', 'Theme published successfully');
    }

    /**
     * Load theme.json safely
     */
    private function loadThemeJson(string $slug): array
    {
        $path = resource_path("views/tenant/themes/{$slug}/theme.json");

        if (!file_exists($path)) {
            abort(404, 'theme.json not found');
        }

        return json_decode(file_get_contents($path), true);
    }
}
