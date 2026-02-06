<?php
namespace App\Services\Theme;

use App\Models\Tenant\ColorScheme;

class ThemeColorSchemeInstaller
{
    public static function install(array $themeJson, int $themeId): void
    {
        if (empty($themeJson['color_schemes'])) {
            return;
        }

        foreach ($themeJson['color_schemes'] as $scheme) {

            // HARD VALIDATION (controller style)
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
