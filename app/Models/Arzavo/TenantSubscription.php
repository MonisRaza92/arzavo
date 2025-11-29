<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'is_trial',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'is_trial' => 'boolean',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Subscription belongs to a Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Subscription belongs to a Plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if subscription is currently active
     */
    public function isActive()
    {
        return $this->status === 'active' && now()->lessThan($this->endsAt());
    }

    /**
     * Check if the trial is active
     */
    public function isInTrial()
    {
        return $this->is_trial && now()->lessThan($this->trial_ends_at);
    }

    /**
     * Get current valid period end (trial or plan)
     */
    public function endsAt()
    {
        return $this->isInTrial()
            ? $this->trial_ends_at
            : $this->ends_at;
    }

    /**
     * Quick helper — return allowed features directly
     */
    public function feature($key)
    {
        return $this->plan?->feature($key);
    }
}
