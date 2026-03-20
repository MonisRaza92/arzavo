<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Arzavo\Subscription;
use App\Models\Arzavo\UsagePricing;

class Plan extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'slug',
        'monthly_price',
        'yearly_price',
        'is_active',
        'is_popular',
        'short_description',
        'description',
        'trial_days',
        'features',   // ✅ add
        'limits',     // ✅ add
    ];

    protected $casts = [
        'features' => 'array',
        'limits' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'monthly_price' => 'float',
        'yearly_price' => 'float',
    ];

    /**
     * Subscriptions using this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    /**
     * Check if feature is enabled
     */
    public function hasFeature($key): bool
    {
        return (bool) ($this->features[$key] ?? false);
    }

    /**
     * Get feature value (raw)
     */
    public function feature($key, $default = null)
    {
        return $this->features[$key] ?? $default;
    }

    /**
     * Get limit value
     */
    public function limit($key)
    {
        $value = $this->limits[$key] ?? null;

        return $value === null || $value === '' ? null : $value;
    }

    /**
     * Check if limit is exceeded
     */
    public function isLimitReached($key, $currentValue): bool
    {
        $limit = $this->limit($key);

        if (is_null($limit)) {
            return false; // unlimited
        }

        return $currentValue >= $limit;
    }
    public function usagePricing()
    {
        return $this->hasMany(UsagePricing::class, 'plan_id');
    }
    public function getUsagePrice($key)
    {
        return $this->usagePricing()
            ->where('key', $key)
            ->whereNull('tenant_id')
            ->value('price_per_unit') ?? 0;
    }
}