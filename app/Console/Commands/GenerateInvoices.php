<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Tenant;
use App\Services\BillingService;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-invoices';

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
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            app(BillingService::class)->generateInvoice($tenant);
        }

        $this->info('Invoices generated');
    }
}
