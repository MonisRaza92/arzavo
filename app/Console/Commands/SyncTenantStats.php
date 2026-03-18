<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Tenant;
use App\Models\Arzavo\TenantStat;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SyncTenantStats extends Command
{
    protected $signature = 'tenants:sync-stats {tenantId?}';
    protected $description = 'Sync tenant stats from tenant DB to main DB';

    public function handle()
    {
        $tenantId = $this->argument('tenantId');

        // 🔥 IMPORTANT: force main DB
        $tenants = $tenantId
            ? Tenant::on('mysql')->where('id', $tenantId)->get()
            : Tenant::on('mysql')->get();

        if ($tenants->isEmpty()) {
            $this->error("No tenants found.");
            return;
        }

        $this->info("Syncing {$tenants->count()} tenants...");

        foreach ($tenants as $tenant) {

            try {

                $this->info("➡ Tenant ID: {$tenant->id}");

                // 🔥 SWITCH TO TENANT DB (same as your migration logic)
                Config::set('database.connections.tenant', [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST'),
                    'database' => $tenant->db_name,
                    'username' => $tenant->db_username,
                    'password' => $tenant->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]);

                DB::purge('tenant');
                DB::reconnect('tenant');

                // 🔥 USE TENANT DB
                $users = DB::connection('tenant')->table('users');

                $data = [
                    'students_count' => (clone $users)->where('role', 'student')->count(),
                    'teachers_count' => (clone $users)->where('role', 'teacher')->count(),
                    'admins_count' => (clone $users)->where('role', 'admin')->count(),
                    'users_count' => $users->count(),

                    // optional (safe)
                    'storage_used' => DB::connection('tenant')
                        ->table('contents')
                        ->sum('size') ?? 0,
                ];

                // 🔥 VERY IMPORTANT: reset connection back
                DB::disconnect('tenant');

                // 🔥 SAVE IN MAIN DB (force mysql)
                TenantStat::on('mysql')->updateOrCreate(
                    ['tenant_id' => $tenant->id],
                    [
                        'data' => $data,
                        'last_synced_at' => now(),
                    ]
                );

                $this->info("✔ Synced Tenant {$tenant->id}");

            } catch (\Throwable $e) {

                DB::disconnect('tenant');

                $this->error("❌ Failed Tenant {$tenant->id}");
                $this->error($e->getMessage());
            }
        }

        $this->info("✅ Sync Completed");
    }
}