<?php

if (!function_exists('section')) {
    function section($section)
    {
        return new \App\Services\Section\Section($section);
    }
}

if (!function_exists('block')) {
    function block($block)
    {
        return new \App\Services\Block\Block($block);
    }
}

function resolveFieldPresets(array $fields, ?string $themeSlug = null): array
{
    static $cache = [];

    $resolved = [];

    $theme = $themeSlug
        ?? (app()->bound('currentThemeSlug') ? app('currentThemeSlug') : null)
        ?? (app()->bound('activeTheme') ? app('activeTheme')->theme_slug : null)
        ?? 'nucleus';

    foreach ($fields as $field) {

        if (!isset($field['preset'])) {
            $resolved[] = $field;
            continue;
        }

        $name = $field['preset'];

        $cacheKey = "$theme:$name";

        if (!isset($cache[$cacheKey])) {
            $data = getThemeSchema($theme, $name, 'schema');
            $cache[$cacheKey] = is_array($data) ? $data : [];
        }

        // Copy preset so cache remains unchanged
        $presetFields = $cache[$cacheKey];

        $overrides = $field['override'] ?? [];

        foreach ($presetFields as &$presetField) {

            $key = $presetField['key'] ?? null;

            if ($key && isset($overrides[$key]) && is_array($overrides[$key])) {

                $presetField = array_replace(
                    $presetField,
                    $overrides[$key]
                );
            }
        }

        unset($presetField);

        $resolved = array_merge($resolved, $presetFields);
    }

    return $resolved;
}

if (!function_exists('isBuilder')) {
    function isBuilder(): bool
    {
        return app()->bound('builderThemeId')
            || request()->is('admin/builder/*');
    }
}

if (!function_exists('getThemeSchema')) {
    function getThemeSchema(string $themeSlug, string $type, string $folder = 'sections'): ?array
    {
        static $memoryCache = [];
        $memKey = "{$folder}_{$themeSlug}_{$type}";

        if (array_key_exists($memKey, $memoryCache)) {
            return $memoryCache[$memKey];
        }

        $path = resource_path("views/tenant/themes/{$themeSlug}/{$folder}/{$type}.json");
        if (!file_exists($path)) {
            return $memoryCache[$memKey] = null;
        }

        $mtime = filemtime($path);
        $cacheKey = "theme_schema_{$folder}_{$themeSlug}_{$type}_{$mtime}";

        $schema = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($path) {
            $content = file_get_contents($path);
            return json_decode($content, true) ?: [];
        });

        return $memoryCache[$memKey] = $schema;
    }
}