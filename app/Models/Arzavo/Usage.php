<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'tenant_id',
        'key',
        'used_value',
    ];

    /**
     * Relations
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * 🔥 Get usage value safely
     */
    public static function getUsage($tenantId, $key)
    {
        return self::where('tenant_id', $tenantId)
            ->where('key', $key)
            ->value('used_value') ?? 0;
    }

    /**
     * 🔥 Increment usage
     */
    public static function incrementUsage($tenantId, $key, $amount = 1)
    {
        return self::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'key' => $key,
            ],
            [
                'used_value' => \DB::raw("COALESCE(used_value, 0) + $amount")
            ]
        );
    }
}