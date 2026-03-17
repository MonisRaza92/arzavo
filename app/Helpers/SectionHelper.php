<?php

use App\Services\Section\SectionResolver;

if (!function_exists('sectionResolve')) {
    function sectionResolve(array $section): array
    {
        return (new SectionResolver($section))->resolve();
    }
}

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

function resolveFieldPresets($fields)
{
    static $cache = [];

    $resolved = [];

    foreach ($fields as $field) {

        if (isset($field['preset'])) {

            $name = $field['preset'];

            if (!isset($cache[$name])) {

                $path = resource_path("views/tenant/themes/schema/$name.json");

                $cache[$name] = file_exists($path)
                    ? json_decode(file_get_contents($path), true)
                    : [];
            }

            $resolved = array_merge($resolved, $cache[$name]);

        } else {
            $resolved[] = $field;
        }
    }

    return $resolved;
}