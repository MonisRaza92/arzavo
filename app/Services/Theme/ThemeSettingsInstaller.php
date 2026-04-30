<?php
namespace App\Services\Theme;

use App\Models\Tenant\Customizes;

class ThemeSettingsInstaller
{
    /**
     * Install settings from config/settings.json.
     *
     * IMPORTANT: Only updates the fields that are defined in settings.json.
     * Existing fields that are NOT in settings.json will NOT be touched/reset.
     * This ensures user customizations are preserved when switching themes.
     *
     * @param array $settings  Key-value pairs from settings.json
     */
    public static function install(array $settings): void
    {
        if (empty($settings)) {
            return;
        }

        // Only update the fields present in settings.json
        // Do NOT delete or reset any existing Customizes keys
        foreach ($settings as $key => $value) {

            // Skip null values
            if ($value === null) {
                continue;
            }

            // Convert arrays/objects to JSON strings
            if (is_array($value)) {
                $value = json_encode($value);
            }

            Customizes::set($key, $value);
        }
    }
}
