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

    // 🔥 Active check (VERY IMPORTANT)
    public function isActive()
    {
        return $this->status === 'active' && (!$this->ends_at || now()->lessThan($this->ends_at));
    }

    public function isInGracePeriod()
    {
        return $this->status === 'canceled' && $this->ends_at && now()->lessThan($this->ends_at);
    }

    public function aboutToExpire()
    {
        return $this->status === 'active' && $this->ends_at && now()->diffInDays($this->ends_at) <= 7;
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
