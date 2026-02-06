<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $connection = 'tenant';

    protected $fillable = ['theme_id', 'key', 'colors'];

    protected $casts = [
        'colors' => 'array',
    ];

    // Enable direct access like $scheme->scheme_colors, $scheme->primary_btn
    public function __get($key)
    {
        $colors = $this->getAttribute('colors'); // get raw attribute value

        if (is_array($colors) && isset($colors[0]) && is_array($colors[0])) {
            if (array_key_exists($key, $colors[0])) {
                return (object) $colors[0][$key]; // 👈 IMPORTANT
            }
        }

        return parent::__get($key);
    }

    public function tenantTheme()
    {
        return $this->belongsTo(
            TenantTheme::class,
            'theme_id', // FK on color_schemes table
            'theme_id'  // owner key on tenant_themes table
        );
    }
}
