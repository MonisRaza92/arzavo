<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class UsagePricing extends Model
{
    protected $connection = 'mysql';
    protected $table = 'usage_pricing';

    protected $fillable = [
        'key',
        'price_per_unit',
        'plan_id',
        'tenant_id',
        'unit',
    ];

    /**
     * Relations
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * 🔥 Get pricing with priority:
     * tenant > plan
     */
    public static function getPrice($tenantId, $planId, $key)
    {
        return self::where('key', $key)
            ->where(function ($q) use ($tenantId, $planId) {
                $q->where('tenant_id', $tenantId)
                    ->orWhere(function ($q2) use ($planId) {
                        $q2->whereNull('tenant_id')
                            ->where('plan_id', $planId);
                    });
            })
            ->orderByRaw('tenant_id IS NOT NULL DESC') // tenant override first
            ->first();
    }
}