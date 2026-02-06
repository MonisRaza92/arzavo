<?php
namespace App\Services\Theme;

use App\Models\Arzavo\Theme;
use App\Models\Tenant\TenantTheme;

class ThemeInstaller
{
    public static function installForTenant(string $themeSlug, TenantTheme $tenantTheme): void
    {
        // Load theme.json
        $path = resource_path("views/tenant/themes/{$themeSlug}/theme.json");

        if (!file_exists($path)) {
            throw new \Exception("theme.json not found for {$themeSlug}");
        }

        $themeJson = json_decode(file_get_contents($path), true);

        // 1️⃣ Install global layout
        ThemeGlobalInstaller::install(
            $themeJson['layout']['global'] ?? [],
            $tenantTheme->id,
            $themeSlug
        );

        // 2️⃣ Install page layouts
        ThemeLayoutInstaller::installPages(
            $themeJson['layout']['pages'] ?? [],
            $tenantTheme->id,
            $themeSlug
        );

        // 3️⃣ Install color schemes
        ThemeColorSchemeInstaller::install(
            $themeJson,
            $tenantTheme->id
        );
    }
}
