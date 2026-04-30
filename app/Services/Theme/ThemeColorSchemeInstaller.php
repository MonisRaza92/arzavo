<?php
namespace App\Services\Theme;

use App\Models\Tenant\ColorScheme;

class ThemeColorSchemeInstaller
{
    /**
     * Install color schemes from config/schemes.json data.
     * 
     * @param array $schemesData  The decoded JSON from config/schemes.json
     * @param int   $themeId      The tenant_theme ID
     */
    public static function install(array $schemesData, int $themeId): void
    {
        $schemes = $schemesData['color_schemes'] ?? [];

        if (empty($schemes)) {
            return;
        }

        foreach ($schemes as $scheme) {

            // HARD VALIDATION
            if (
                !isset($scheme['key']) ||
                !isset($scheme['colors']) ||
                !is_array($scheme['colors']) ||
                !isset($scheme['colors'][0])
            ) {
                continue;
            }

            ColorScheme::updateOrCreate(
                [
                    'theme_id' => $themeId,
                    'key' => $scheme['key'],
                ],
                [
                    'colors' => $scheme['colors']
                ]
            );
        }
    }
}
