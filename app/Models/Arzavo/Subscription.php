<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'custom_price',
    ];

    // Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // 🔥 Active check (VERY IMPORTANT)
    public function isActive()
    {
        // active plan
        if ($this->status === 'active' && $this->ends_at && now()->lessThanOrEqualTo($this->ends_at)) {
            return true;
        }

        // trial plan
        if ($this->status === 'trial' && $this->trial_ends_at && now()->lessThanOrEqualTo($this->trial_ends_at)) {
            return true;
        }

        return false;
    }

    public function isTrial()
    {
        return $this->status === 'trial'
            && $this->trial_ends_at
            && now()->lessThanOrEqualTo($this->trial_ends_at);
    }
    public function getIsTrialAttribute()
    {
        return $this->isTrial();
    }

    // 🔥 GRACE PERIOD CHECK (MAIN HELPER)
    public function isInGracePeriod($days = 7)
    {
        // only apply to expired active subscriptions
        if ($this->status !== 'active' || !$this->ends_at) {
            return false;
        }

        return now()->greaterThan($this->ends_at) &&
            now()->lessThanOrEqualTo($this->ends_at->copy()->addDays($days));
    }

    // 🔥 FINAL ACCESS HELPER (USE THIS EVERYWHERE)
    public function canAccess($graceDays = 7)
    {
        return $this->isActive() || $this->isInGracePeriod($graceDays);
    }

    public function overrides()
    {
        return $this->hasMany(\App\Models\Arzavo\SubscriptionOverride::class);
    }

    public function addons()
    {
        return $this->belongsToMany(
            \App\Models\Arzavo\Addon::class,
            'subscription_addons'
        )->withTimestamps();
    }
}
