<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use App\Models\Arzavo\Tenant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
class TenantController
{
    public function index()
    {
        $tenants = Auth::guard('web')->user()->tenants;

        $tenantIds = $tenants->pluck('id');

        $pendingAmount = \App\Models\Arzavo\Invoice::whereIn('tenant_id', $tenantIds)
            ->where('status', 'pending')
            ->sum('total_amount');

        $invoices = \App\Models\Arzavo\Invoice::whereIn('tenant_id', $tenantIds)
            ->with('tenant')
            ->orderBy('created_at', 'desc')
            ->get();

        $tenantStats = [];
        foreach ($tenants as $tenant) {
            $tenantStats[$tenant->id] = $this->getTenantStats($tenant);
        }

        return view('arzavo.tenants.index', compact('tenants', 'pendingAmount', 'invoices', 'tenantStats'));
    }

    protected function getTenantStats($tenant)
    {
        try {
            if (!$tenant->db_name) {
                return ['users_count' => 0, 'students_limit' => 150, 'storage_used' => 0, 'storage_limit' => 5 * 1024 * 1024 * 1024];
            }

            config([
                'database.connections.tenant' => [
                    'driver' => 'mysql',
                    'host' => config('database.connections.mysql.host'),
                    'port' => config('database.connections.mysql.port'),
                    'database' => $tenant->db_name,
                    'username' => $tenant->db_username,
                    'password' => $tenant->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            $usersCount = DB::connection('tenant')->table('users')->count();

            $storageUsed = 0;
            if (DB::connection('tenant')->getSchemaBuilder()->hasTable('contents')) {
                $storageUsed += (int)(DB::connection('tenant')->table('contents')->sum('size') ?? 0);
            }
            if (DB::connection('tenant')->getSchemaBuilder()->hasTable('media')) {
                $storageUsed += (int)(DB::connection('tenant')->table('media')->sum('size') ?? 0);
            }

            // Check physical directory size
            $tenantDir = storage_path('app/public/tenants/' . $tenant->id);
            if (file_exists($tenantDir)) {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tenantDir)) as $file) {
                    if ($file->isFile()) {
                        $storageUsed += $file->getSize();
                    }
                }
            }

            DB::disconnect('tenant');

            $plan = $tenant->subscription ? $tenant->subscription->plan : null;
            $studentsLimit = $plan ? ($plan->max_students ?: 'Unlimited') : 150;
            $storageLimitBytes = ($plan && !empty($plan->storage_limit_mb)) ? ($plan->storage_limit_mb * 1024 * 1024) : (5 * 1024 * 1024 * 1024);

            return [
                'users_count' => $usersCount,
                'students_limit' => $studentsLimit,
                'storage_used' => $storageUsed,
                'storage_limit' => $storageLimitBytes,
            ];
        } catch (\Throwable $e) {
            DB::disconnect('tenant');
            return [
                'users_count' => 0,
                'students_limit' => 150,
                'storage_used' => 0,
                'storage_limit' => 5 * 1024 * 1024 * 1024,
            ];
        }
    }

    public function create()
    {
        $tenants = Auth::guard('web')->user()->tenants;

        return view('arzavo.tenants.create', compact('tenants'));
    }

    public function checkSubdomain(Request $request)
    {
        $exists = Tenant::where('subdomain', $request->subdomain . '.' . config('app.domain'))->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'subdomain' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-z0-9-]+$/',
                    Rule::unique('tenants', 'subdomain'),
                ],
                'custom_domain' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('tenants', 'custom_domain')
                ],

            ]);

            $user = Auth::guard('web')->user();
            // Create Tenant
            $tenant = Tenant::create([
                'admin_id' => $user->id,
                'name' => $request->name,
                'logo' => '',
                'banner' => '',
                'heading' => $request->name,
                'about' => 'Coming soon...',
                'subdomain' => strtolower($request->subdomain) . '.' . config('app.domain'),
                'custom_domain' => $request->custom_domain,
                'domain_verified' => false,
                'status' => 'active',
            ]);

            // Create Database for tenant
            $this->createTenantDatabase($tenant);

            // Run Tenant Migrations & Create Admin
            $this->initializeTenant($tenant, $user);
            ping_google();

            $tenantUrl = $tenant->custom_domain && $tenant->domain_verified
                ? 'https://' . $tenant->custom_domain . '/admin/dashboard'
                : 'https://' . $tenant->subdomain . '/admin/dashboard';

            return response()->json([
                'success' => true,
                'redirect' => $tenantUrl
            ]);
        } catch (\Throwable $e) {
            if (isset($tenant) && $tenant->exists) {
                if (isset($tenant->db_name)) {
                    DB::statement("DROP DATABASE IF EXISTS `{$tenant->db_name}`");
                }
                $tenant->delete();
            }
            \Log::error("Tenant creation failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'redirect' => route('tenants.index')
            ]);
        }
    }

    // Create tenant DB
    protected function createTenantDatabase($tenant)
    {
        // Convert tenant name to db-safe slug
        $slug = Str::slug($tenant->name);

        // Ensure DB name is always unique
        $dbName = $slug . '_' . Str::random(16) . rand(100000, 999999);

        $dbName = substr(strtolower($dbName), 0, 64);

        // Create the database
        DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Save db info in tenant record
        $tenant->update([
            'db_name' => $dbName,
            'db_username' => config('database.connections.mysql.username'),
            'db_password' => config('database.connections.mysql.password'),
        ]);
    }


    // Run tenant migrations & create admin user in tenant DB
    protected function initializeTenant($tenant, $mainUser)
    {
        // 1. Set tenant DB connection
        config([
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $tenant->db_name,
                'username' => $tenant->db_username,
                'password' => $tenant->db_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // 2. Run tenant migrations
        Artisan::call('tenant:migrate', [
            'tenant_id' => $tenant->id,
            '--seed' => true,
        ]);

        // 3. Create tenant admin user (after migrations)
        try {
            $alreadyExists = DB::connection('tenant')
                ->table('users')
                ->where('email', $mainUser->email)
                ->exists();

            if (!$alreadyExists) {
                DB::connection('tenant')->table('users')->insert([
                    'fname' => $mainUser->fname,
                    'lname' => $mainUser->lname,
                    'email' => $mainUser->email,
                    'number' => $mainUser->number,
                    'username' => $mainUser->username,
                    'password' => $mainUser->password,
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error("Tenant admin user creation failed for tenant {$tenant->id}: " . $e->getMessage());
            throw $e; // re-throw so store() can catch it
        }
    }



    public function show($identifier)
    {
        $tenant = Tenant::where('subdomain', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $subscription = $tenant->subscription;
        $plan = $subscription ? $subscription->plan : null;
        $invoices = \App\Models\Arzavo\Invoice::where('tenant_id', $tenant->id)->orderBy('created_at', 'desc')->get();
        $pendingAmount = $invoices->where('status', 'pending')->sum('total_amount');
        $stats = $this->getTenantStats($tenant);

        return view('arzavo.tenants.show', compact('tenant', 'subscription', 'plan', 'invoices', 'pendingAmount', 'stats'));
    }

    public function resetAdminPassword(Request $request, $identifier)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $tenant = Tenant::where('subdomain', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        if (!$tenant->db_name) {
            return back()->with('error', 'Tenant database is not configured.');
        }

        try {
            config([
                'database.connections.tenant' => [
                    'driver' => 'mysql',
                    'host' => config('database.connections.mysql.host'),
                    'port' => config('database.connections.mysql.port'),
                    'database' => $tenant->db_name,
                    'username' => $tenant->db_username,
                    'password' => $tenant->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            DB::connection('tenant')->table('users')
                ->where('role', 'admin')
                ->update([
                    'password' => \Hash::make($request->password),
                    'updated_at' => now(),
                ]);

            DB::disconnect('tenant');

            return back()->with('success', 'Tenant admin password updated successfully!');
        } catch (\Throwable $e) {
            DB::disconnect('tenant');
            return back()->with('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validate and update tenant logic here
    }

    public function toggleStatus($id)
    {
        // Toggle tenant active/inactive status logic here
    }

    public function destroy(Request $request, $identifier)
    {
        $request->validate([
            'confirm_password' => 'required|string',
        ]);

        $user = Auth::guard('web')->user();

        if (!\Hash::check($request->confirm_password, $user->password)) {
            return back()->with('error', 'Incorrect password! Workspace deletion failed.');
        }

        $tenant = Tenant::where('subdomain', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        if ($tenant->db_name) {
            try {
                DB::statement("DROP DATABASE IF EXISTS `{$tenant->db_name}`");
            } catch (\Throwable $e) {
                // Ignore DB drop error if already removed
            }
        }

        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Workspace deleted successfully!');
    }
}
