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
        if ($this->status === 'active')
            return true;

        if ($this->status === 'trial' && $this->trial_ends_at > now()) {
            return true;
        }

        return false;
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
