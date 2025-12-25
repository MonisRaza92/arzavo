<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class TenantTheme extends Model
{
    protected $connection = 'mysql';

    protected $table = 'tenant_themes';

    // MAIN DB

    protected $fillable = [
        'tenant_id',
        'theme_id',
        'status',
        'purchased_at',
        'activated_at',
        'expired_at',
        'meta',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'activated_at' => 'datetime',
        'expired_at' => 'datetime',
        'meta' => 'array',
    ];

    /* -------------------------
     | Relationships
     |--------------------------*/

    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    /* -------------------------
     | Helpers
     |--------------------------*/

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'purchased']);
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }
}
