<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ThemePageDesign extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'tenant_theme_id',
        'page_id',
        'layout',
    ];

    protected $casts = [
        'layout' => 'array',
    ];
}
