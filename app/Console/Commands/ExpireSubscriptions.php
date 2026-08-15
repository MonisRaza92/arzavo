<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Subscription;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire trial and active subscriptions, and auto-delete marked tenants';

    public function handle()
    {
        // 🔹 1. Auto-delete expired tenants that have delete_on_expiry enabled
        $deleteSubscriptions = Subscription::with('tenant')
            ->where('delete_on_expiry', true)
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('status', 'trial')->where('trial_ends_at', '<', now());
                })->orWhere(function ($sub) {
                    $sub->where('status', 'active')->whereNotNull('ends_at')->where('ends_at', '<', now());
                });
            })
            ->get();

        $deletedCount = 0;
        foreach ($deleteSubscriptions as $sub) {
            if ($sub->tenant) {
                $tenantName = $sub->tenant->name;
                $sub->tenant->delete();
                $deletedCount++;
                $this->warn("Auto-deleted expired tenant: {$tenantName}");
            }
        }

        // 🔹 2. Expire remaining trial subscriptions
        $trialExpired = Subscription::where('status', 'trial')
            ->where('trial_ends_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        // 🔹 3. Expire remaining active subscriptions
        $activeExpired = Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        $this->info("Auto-Deleted Tenants: $deletedCount");
        $this->info("Expired Trials: $trialExpired");
        $this->info("Expired Active: $activeExpired");

        return Command::SUCCESS;
    }
}