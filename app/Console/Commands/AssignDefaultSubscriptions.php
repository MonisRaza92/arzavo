<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Subscription;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Tenant;

class AssignDefaultSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:subscriptions';

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
        $plan = Plan::where('slug', 'basic')->first();

        if (!$plan) {
            $this->error('Basic plan not found');
            return;
        }

        $tenants = Tenant::doesntHave('subscription')->get();

        foreach ($tenants as $tenant) {
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'trial_ends_at' => now()->addDays($plan->trial_days),
            ]);
        }

        $this->info('Subscriptions assigned successfully');
    }
}
