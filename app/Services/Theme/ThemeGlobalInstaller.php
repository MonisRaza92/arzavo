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

                $kind = $item['kind'] ?? 'section';
                $name = $item['name'] ?? null;

                if (!$name) {
                    continue;
                }

                if ($kind === 'section') {
                    $layout[$area]['sections'][] =
                        ThemeSectionFactory::fromSectionType($name, $themeSlug);
                }

                if ($kind === 'template') {
                    foreach (ThemeTemplateExpander::expand($name, $themeSlug) as $section) {
                        $layout[$area]['sections'][] = $section;
                    }
                }
            }
        }

        ThemeGlobalDesign::updateOrCreate(
            ['tenant_theme_id' => $tenantThemeId],
            ['layout' => $layout]
        );
    }
}
