<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class TenantStat extends Model
{
    protected $fillable = [
        'tenant_id',
        'data',
        'last_synced_at',
    ];

    protected $casts = [
        'data' => 'array',
        'last_synced_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // 🔹 Belongs to Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    // 🔹 Get specific stat
    public function get($key, $default = 0)
    {
        return $this->data[$key] ?? $default;
    }

    // 🔹 Set specific stat (optional)
    public function set($key, $value)
    {
        $data = $this->data ?? [];
        $data[$key] = $value;

        $this->data = $data;

        return $this;
    }

    // 🔹 Increment stat (powerful)
    public function incrementStat($key, $value = 1)
    {
        $data = $this->data ?? [];

        $data[$key] = ($data[$key] ?? 0) + $value;

        $this->data = $data;

        return $this;
    }
}