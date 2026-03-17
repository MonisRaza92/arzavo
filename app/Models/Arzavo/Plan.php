<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'monthly_price',
        'yearly_price',
        'is_active',
        'is_popular',
        'short_description',
        'description',
        'trial_days'
    ];
    protected $casts = [
        'features' => 'array',
        'limits' => 'array', // 🔥 THIS IS MISSING
    ];

    /**
     * A plan has many features
     */
    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    /**
     * Returns feature value by key
     * Example: $plan->feature('max_students')
     */
    public function feature($key)
    {
        return $this->features()->where('key', $key)->value('value');
    }

    /**
     * Tenants currently using this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(TenantSubscription::class);
    }
}
