<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'custom_price',
        'delete_on_expiry',
        'pending_plan_id'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'delete_on_expiry' => 'boolean',
    ];
    public function applyPendingPlan()
    {
        if ($this->pending_plan_id) {

            $this->update([
                'plan_id' => $this->pending_plan_id,
                'pending_plan_id' => null,
                'starts_at' => now(),
                'ends_at' => null,
            ]);
        }
    }

    // Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // 🔥 Trial & Active checks
    public function isTrial()
    {
        return $this->status === 'trial' || ($this->trial_ends_at !== null && $this->ends_at === null);
    }

    public function isTrialActive()
    {
        return $this->trial_ends_at && now()->lessThanOrEqualTo($this->trial_ends_at);
    }

    public function isTrialExpired()
    {
        return $this->trial_ends_at && now()->greaterThan($this->trial_ends_at);
    }

    public function isActive()
    {
        // Cancelled or explicit expired status
        if ($this->status === 'cancelled' || $this->status === 'expired') {
            return false;
        }

        // Trial mode
        if ($this->status === 'trial') {
            return $this->trial_ends_at && now()->lessThanOrEqualTo($this->trial_ends_at);
        }

        // Active status
        if ($this->status === 'active') {
            // If it was a trial-based active subscription
            if ($this->trial_ends_at && $this->ends_at && $this->trial_ends_at->equalTo($this->ends_at)) {
                return now()->lessThanOrEqualTo($this->trial_ends_at);
            }
            return !$this->ends_at || now()->lessThanOrEqualTo($this->ends_at);
        }

        return false;
    }

    public function isInGracePeriod()
    {
        return $this->status === 'canceled' && $this->ends_at && now()->lessThan($this->ends_at);
    }

    public function aboutToExpire()
    {
        $expiryDate = $this->ends_at ?? $this->trial_ends_at;
        return $this->isActive() && $expiryDate && now()->diffInDays($expiryDate, false) <= 3 && now()->diffInDays($expiryDate, false) >= 0;
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
