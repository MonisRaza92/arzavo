<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    /**
     * tenant:migrate              → migrate ALL tenants + auto-sync seed
     * tenant:migrate 5            → migrate tenant ID=5 + auto-sync seed
     * tenant:migrate --fresh      → migrate:fresh ALL tenants (drops tables) + auto-sync seed
     * tenant:migrate --seed       → migrate + FULL initial seed (TenantSeeder) — new tenants only
     * tenant:migrate --no-sync    → migrate only, skip TenantSyncSeeder
     */
    protected $signature = 'tenant:migrate
                            {tenant_id?    : Specific tenant ID to migrate (optional)}
                            {--fresh       : Drop all tables and re-run all migrations}
                            {--seed        : Also run the FULL TenantSeeder (initial setup only)}
                            {--no-sync     : Skip the automatic TenantSyncSeeder after migration}';

    protected $description = 'Run migrations (and safe sync seed) for all tenants or a specific tenant';

    public function handle(): void
    {
        $tenantId = $this->argument('tenant_id');
        $fresh    = $this->option('fresh');
        $seed     = $this->option('seed');
        $noSync   = $this->option('no-sync');

        // ── Resolve tenant list ──────────────────────────────────────
        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->error('No tenants found.');
            return;
        }

        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        foreach ($tenants as $tenant) {
            $this->newLine();
            $this->info("┌─ Tenant [{$tenant->id}] {$tenant->name}");

            // ── Switch DB connection ─────────────────────────────────
            $this->switchConnection($tenant);

            // ── 1. Migrations ────────────────────────────────────────
            $migrationCmd = $fresh ? 'migrate:fresh' : 'migrate';

            Artisan::call($migrationCmd, [
                '--database' => 'tenant',
                '--path'     => 'database/migrations/tenant',
                '--force'    => true,
            ]);

            $this->line('│  ✔ Migrations: ' . trim(Artisan::output()));

            // ── 2. Full initial seed (--seed flag only) ───────────────
            if ($seed) {
                $this->line('│  ⟳ Running TenantSeeder (full initial setup)…');
                Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--class'    => 'TenantSeeder',
                    '--force'    => true,
                ]);
                $this->line('│  ✔ TenantSeeder done');
            }

            // ── 3. Auto sync seed (always, unless --no-sync) ─────────
            if (! $noSync && ! $seed) {
                $this->line('│  ⟳ Running TenantSyncSeeder (safe upsert)…');
                Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--class'    => 'TenantSyncSeeder',
                    '--force'    => true,
                ]);
                $this->line('│  ✔ Sync done');
            }

            $this->info("└─ Tenant [{$tenant->id}] complete ✔");
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('All tenants migrated successfully!');
    }

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────

    private function switchConnection(Tenant $tenant): void
    {
        Config::set('database.connections.tenant', [
            'driver'    => 'mysql',
            'host'      => config('database.connections.mysql.host'),
            'port'      => config('database.connections.mysql.port'),
            'database'  => $tenant->db_name,
            'username'  => $tenant->db_username,
            'password'  => $tenant->db_password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
