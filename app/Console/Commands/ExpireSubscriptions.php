<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Subscription;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire trial and active subscriptions';

    public function handle()
    {
        // 🔹 Expire trial
        $trialExpired = Subscription::where('status', 'trial')
            ->where('trial_ends_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        // 🔹 Expire active (paid plans later use honge)
        $activeExpired = Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        $this->info("Expired Trials: $trialExpired");
        $this->info("Expired Active: $activeExpired");

        return Command::SUCCESS;
    }
}