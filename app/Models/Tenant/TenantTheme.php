<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TenantTheme extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'theme_id',
        'theme_slug',
        'theme_version',
        'status',
        'is_active',
        'installed_at',
        'published_at',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'installed_at' => 'datetime',
        'published_at' => 'datetime',
        'is_active' => 'boolean'
    ];


    public function getThemeNameAttribute()
    {
        return DB::connection('mysql')->table('themes')->where('id', $this->theme_id)->value('name');
    }

    public function getCategoryAttribute()
    {
        return DB::connection('mysql')->table('themes')->where('id', $this->theme_id)->value('category');
    }
    public function globalDesign()
    {
        return $this->hasOne(ThemeGlobalDesign::class, 'tenant_theme_id');
    }
    public function colorSchemes()
    {
        return $this->hasMany(
            ColorScheme::class,
            'theme_id', // FK on color_schemes table
            'theme_id'  // theme_id on tenant_themes table
        );
    }

    /**
     * Get a color scheme by key (helper, optional but VERY useful)
     */
    public function colorScheme(string $key): ?ColorScheme
    {
        return $this->colorSchemes()
            ->where('key', $key)
            ->first();
    }
}
