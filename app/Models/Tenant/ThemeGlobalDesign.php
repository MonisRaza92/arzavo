<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ThemeGlobalDesign extends Model
{
    protected $connection = 'tenant';
    protected $table = 'theme_global_designs';

    protected $fillable = [
        'tenant_theme_id',
        'layout',
    ];

    protected $casts = [
        'layout' => 'array',
    ];

    public function tenantTheme()
    {
        return $this->belongsTo(TenantTheme::class, 'tenant_theme_id');
    }
}
