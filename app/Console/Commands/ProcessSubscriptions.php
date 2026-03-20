<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = \App\Models\Arzavo\Subscription::whereNotNull('pending_plan_id')
            ->where('ends_at', '<=', now())
            ->get();

        foreach ($subscriptions as $sub) {
            $sub->applyPendingPlan();
        }
    }
}
