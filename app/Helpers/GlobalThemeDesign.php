<?php
function globalThemeDesign($tenantThemeId)
{
    return \App\Models\Tenant\ThemeGlobalDesign::firstOrCreate(
        ['tenant_theme_id' => $tenantThemeId],
        [
            'layout' => [
                'header' => ['sections' => []],
                'footer' => ['sections' => []],
                'globals' => ['sections' => []],
            ]
        ]
    );
}
