<?php
namespace App\Services\Theme;

use App\Models\Tenant\TenantTheme;

class ThemeInstaller
{
    public static function installForTenant(string $themeSlug, TenantTheme $tenantTheme): void
    {
        $basePath = resource_path("views/tenant/themes/{$themeSlug}");

        if (!is_dir($basePath)) {
            throw new \Exception("Theme directory not found for {$themeSlug}");
        }

        // 1️⃣ Install global layout (from layouts/global.json)
        $globalPath = "{$basePath}/layouts/global.json";
        $globalLayout = file_exists($globalPath)
            ? json_decode(file_get_contents($globalPath), true) ?? []
            : [];

        ThemeGlobalInstaller::install(
            $globalLayout,
            $tenantTheme->id,
            $themeSlug
        );

        // 2️⃣ Create pages and install page layouts (from layouts/pages/*.json)
        $pagesDir = "{$basePath}/layouts/pages";
        $pages = [];

        if (is_dir($pagesDir)) {
            foreach (glob("{$pagesDir}/*.json") as $pageFile) {
                $pageSlug = pathinfo($pageFile, PATHINFO_FILENAME);
                $pageData = json_decode(file_get_contents($pageFile), true) ?? [];

                // Extract meta fields
                $meta = [
                    'meta_title' => $pageData['meta_title'] ?? null,
                    'meta_description' => $pageData['meta_description'] ?? null,
                ];

                // Pages are stored as { "pageSlug": [...sections...] }
                if (isset($pageData[$pageSlug]) && is_array($pageData[$pageSlug])) {
                    $pages[$pageSlug] = [
                        'sections' => $pageData[$pageSlug],
                        'meta' => $meta,
                    ];
                } else {
                    // Try first key that has an array value (skip meta_title, meta_description)
                    foreach ($pageData as $key => $value) {
                        if (is_array($value)) {
                            $pages[$key] = [
                                'sections' => $value,
                                'meta' => $meta,
                            ];
                            break;
                        }
                    }
                }
            }
        }

        ThemeLayoutInstaller::installPages(
            $pages,
            $tenantTheme->id,
            $themeSlug
        );

        // 3️⃣ Install color schemes (from config/schemes.json)
        $schemesPath = "{$basePath}/config/schemes.json";
        $schemesData = file_exists($schemesPath)
            ? json_decode(file_get_contents($schemesPath), true) ?? []
            : [];

        ThemeColorSchemeInstaller::install(
            $schemesData,
            $tenantTheme->id
        );

        // 4️⃣ Install settings / customizes (from config/settings.json)
        $settingsPath = "{$basePath}/config/settings.json";
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
            ThemeSettingsInstaller::install($settings);
        }
    }
}
