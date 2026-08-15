<?php

namespace App\Observers;

use App\Models\Arzavo\Tenant;
use App\Models\Arzavo\Subscription;
use App\Models\Arzavo\Plan;

class TenantObserver
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        // 1. Default free / basic plan dhoondo, warna first active plan
        $plan = Plan::where('slug', 'basic')->where('is_active', true)->first()
            ?? Plan::where('monthly_price', 0)->where('is_active', true)->first()
            ?? Plan::where('is_active', true)->orderBy('monthly_price', 'asc')->first();

        if (!$plan) {
            return;
        }

        $trialDays = (int) ($plan->trial_days ?? 0);
        $trialEndsAt = $trialDays > 0 ? now()->addDays($trialDays) : null;

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => $trialDays > 0 ? 'trial' : 'active',
            'starts_at' => now(),
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $trialEndsAt,
        ]);

        if ($trialDays > 0) {
            $tenant->updateQuietly([
                'has_used_trial' => true,
                'trial_used_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "restored" event.
     */
    public function restored(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "force deleted" event.
     */
    public function forceDeleted(Tenant $tenant): void
    {
        //
    }
}
