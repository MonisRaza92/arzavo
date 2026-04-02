<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    protected $signature = 'tenant:migrate {tenant_id?} {--fresh} {--seed}';

    protected $description = 'Run migrations for all tenants or a specific tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        $fresh = $this->option('fresh');
        $seed = $this->option('seed');

        // Get tenants list
        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->error("No tenants found.");
            return;
        }

        foreach ($tenants as $tenant) {
            $this->info("Running migration for Tenant ID: {$tenant->id}");

            // Switch database connection for this tenant
            Config::set('database.connections.tenant', [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $tenant->db_name,
                'username' => $tenant->db_username,
                'password' => $tenant->db_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            // Run migration
            $command = $fresh ? 'migrate:fresh' : 'migrate';

            Artisan::call($command, [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant', // ← apne tenant migrations ki folder
                '--force' => true,
            ]);

            if ($seed) {
                Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--class' => 'TenantSeeder',
                    '--force' => true,
                ]);
            }

            $this->info("✔ Tenant {$tenant->id} migration completed");
        }
    }
}
