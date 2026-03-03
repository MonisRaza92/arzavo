<?php

if (!function_exists('formatSize')) {

    function formatSize($bytes, $precision = 2)
    {
        if (!is_numeric($bytes) || $bytes <= 0) {
            return '0 KB';
        }

        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($bytes < $mb) {
            return round($bytes / $kb, $precision) . ' KB';
        }

        if ($bytes < $gb) {
            return round($bytes / $mb, $precision) . ' MB';
        }

        if ($bytes < $tb) {
            return round($bytes / $gb, $precision) . ' GB';
        }

        return round($bytes / $tb, $precision) . ' TB';
    }
}

function tenant_url(): string
{
    $tenant = app()->bound('currentTenant')
        ? app('currentTenant')
        : null;

    if (!$tenant) {
        return config('app.url');
    }

    $scheme = request()->getScheme(); // http / https auto

    if (!empty($tenant->custom_domain) && $tenant->domain_verified) {
        return $scheme . '://' . $tenant->custom_domain;
    }

    return $scheme . '://' . $tenant->subdomain;
}
function ping_google()
{
    try {
        Http::timeout(3)->get(
            'https://www.google.com/ping?sitemap=' .
            urlencode(url('/sitemap.xml'))
        );
    } catch (\Throwable $e) {
        // silently ignore
    }
}

function activeThemeId()
{
    // builder editing priority
    if (app()->bound('builderThemeId')) {
        return app('builderThemeId');
    }

    // fallback live theme
    return app('currentThemeId');
}