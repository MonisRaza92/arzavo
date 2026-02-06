<?php
namespace App\Services\Theme;

use App\Models\Tenant\ThemeGlobalDesign;

class ThemeGlobalInstaller
{
    public static function install(array $global, int $tenantThemeId, string $themeSlug): void
    {
        $layout = [];

        foreach ($global as $area => $items) {
            $layout[$area] = ['sections' => []];

            foreach ($items as $item) {
                $layout[$area]['sections'][] =
                    ThemeSectionFactory::fromSectionType($item['name'], $themeSlug);
            }
        }

        ThemeGlobalDesign::updateOrCreate(
            ['tenant_theme_id' => $tenantThemeId],
            ['layout' => $layout]
        );
    }
}
