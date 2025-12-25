<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $connection = 'mysql';

    protected $table = 'themes';

    // IMPORTANT: MAIN DB

    protected $fillable = [
        'slug',
        'name',
        'category',
        'version',
        'is_paid',
        'price',
        'source',
        'owner_tenant_id',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'meta' => 'array',
    ];

    /* -------------------------
     | Relationships
     |--------------------------*/

    public function tenantThemes()
    {
        return $this->hasMany(TenantTheme::class, 'theme_id');
    }

    /* -------------------------
     | Scopes
     |--------------------------*/

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }
}
