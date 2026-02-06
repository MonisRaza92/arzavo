<?php

namespace App\Services;

use App\Models\Tenant\TenantTheme;

class ThemeContext
{
    public static function active(): ?TenantTheme
    {
        return TenantTheme::where('is_active', true)
            ->where('status', 'published')
            ->first();
    }

    public static function draft(): ?TenantTheme
    {
        return TenantTheme::where('status', 'draft')
            ->latest()
            ->first();
    }
    public static function slug(): ?string
    {
        return self::active()?->theme_slug;
    }
}
