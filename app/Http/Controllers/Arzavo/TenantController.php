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
        $this->purgeExpiredTenants();

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

    public function domains()
    {
        $this->purgeExpiredTenants();
        $tenants = Auth::guard('web')->user()->tenants;
        $cnameTarget = env('CUSTOM_DOMAIN_TARGET', 'domain.arzavo.com');

        return view('arzavo.tenants.fleet.domains', compact('tenants', 'cnameTarget'));
    }

    public function billing()
    {
        $this->purgeExpiredTenants();
        $tenants = Auth::guard('web')->user()->tenants;
        $tenantIds = $tenants->pluck('id');

        $invoices = \App\Models\Arzavo\Invoice::whereIn('tenant_id', $tenantIds)
            ->with('tenant')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingAmount = $invoices->where('status', 'pending')->sum('total_amount');
        $paidAmount = $invoices->where('status', 'paid')->sum('total_amount');

        return view('arzavo.tenants.fleet.billing', compact('tenants', 'invoices', 'pendingAmount', 'paidAmount'));
    }

    public function storage()
    {
        $this->purgeExpiredTenants();
        $tenants = Auth::guard('web')->user()->tenants;
        $tenantStats = [];
        $totalStorageUsed = 0;
        $totalStorageLimit = 0;

        foreach ($tenants as $tenant) {
            $stats = $this->getTenantStats($tenant);
            $tenantStats[$tenant->id] = $stats;
            $totalStorageUsed += (int) ($stats['storage_used'] ?? 0);
            $totalStorageLimit += (int) ($stats['storage_limit'] ?? 0);
        }

        return view('arzavo.tenants.fleet.storage', compact('tenants', 'tenantStats', 'totalStorageUsed', 'totalStorageLimit'));
    }

    public function backups()
    {
        $this->purgeExpiredTenants();
        $tenants = Auth::guard('web')->user()->tenants;

        return view('arzavo.tenants.fleet.backups', compact('tenants'));
    }

    public function security()
    {
        $this->purgeExpiredTenants();
        $tenants = Auth::guard('web')->user()->tenants;

        return view('arzavo.tenants.fleet.security', compact('tenants'));
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

            $usersCount = DB::connection('tenant')->table('users')->where('role', 'student')->count();

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
                'admin_password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                    function ($attribute, $value, $fail) {
                        $commonWeak = ['12345678', '123456789', 'password', 'password1', 'password123', 'admin123', 'qwerty123', '11111111'];
                        if (in_array(strtolower($value), $commonWeak) || preg_match('/^(.)\1+$/', $value)) {
                            $fail('Password is too common or weak. Please choose a stronger password.');
                        }
                    }
                ],
            ], [
                'admin_password.min' => 'Admin password must be at least 8 characters long.',
                'admin_password.regex' => 'Admin password must contain both letters and numbers for security.',
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
            $this->initializeTenant($tenant, $user, $request->admin_password);
            ping_google();

            session()->flash('success', "Educational institute '{$tenant->name}' has been created successfully!");

            return response()->json([
                'success' => true,
                'redirect' => route('tenants.index')
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
    protected function initializeTenant($tenant, $mainUser, $adminPassword = null)
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
                    'number' => $mainUser->number ?: '0000000000',
                    'username' => $mainUser->username ?: Str::slug($mainUser->fname . '-' . rand(100, 999)),
                    'password' => $adminPassword ? \Hash::make($adminPassword) : $mainUser->password,
                    'role' => 'admin',
                    'status' => 'active',
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
        $this->purgeExpiredTenants();

        $tenant = Tenant::where('subdomain', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $subscription = $tenant->subscription;
        $plan = $subscription ? $subscription->plan : null;
        $invoices = \App\Models\Arzavo\Invoice::where('tenant_id', $tenant->id)->orderBy('created_at', 'desc')->get();
        $pendingAmount = $invoices->where('status', 'pending')->sum('total_amount');
        $stats = $this->getTenantStats($tenant);

        // Fetch Tenant Administrators from isolated tenant DB
        $tenantAdmins = collect();
        try {
            if ($tenant->db_name) {
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
                $tenantAdmins = DB::connection('tenant')->table('users')
                    ->where('role', 'admin')
                    ->orderBy('id', 'asc')
                    ->get();
                DB::disconnect('tenant');
            }
        } catch (\Throwable $e) {
            \Log::warning("Could not fetch tenant admins for {$tenant->id}: " . $e->getMessage());
            DB::disconnect('tenant');
        }

        // Calculate admin seat limits based on subscription plan
        $adminLimit = 1;
        if ($plan) {
            if (isset($plan->limits['admins'])) {
                $adminLimit = (int) $plan->limits['admins'];
            } elseif ($plan->slug === 'pro') {
                $adminLimit = 3;
            } elseif ($plan->slug === 'enterprise') {
                $adminLimit = 999;
            }
        }

        return view('arzavo.tenants.show', compact('tenant', 'subscription', 'plan', 'invoices', 'pendingAmount', 'stats', 'tenantAdmins', 'adminLimit'));
    }

    public function storeAdmin(Request $request, $identifier)
    {
        $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'number' => 'nullable|string|max:20',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                function ($attribute, $value, $fail) {
                    $commonWeak = ['12345678', '123456789', 'password', 'password1', 'password123', 'admin123', 'qwerty123', '11111111'];
                    if (in_array(strtolower($value), $commonWeak) || preg_match('/^(.)\1+$/', $value)) {
                        $fail('Password is too common or weak. Please choose a stronger password.');
                    }
                }
            ],
        ], [
            'password.min' => 'Admin password must be at least 8 characters long.',
            'password.regex' => 'Admin password must contain both letters and numbers for security.',
        ]);

        $tenant = Tenant::where('subdomain', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        if (!$tenant->db_name) {
            return back()->with('error', 'Tenant database is not configured.');
        }

        $subscription = $tenant->subscription;
        $plan = $subscription ? $subscription->plan : null;
        $adminLimit = 1;
        if ($plan) {
            if (isset($plan->limits['admins'])) {
                $adminLimit = (int) $plan->limits['admins'];
            } elseif ($plan->slug === 'pro') {
                $adminLimit = 3;
            } elseif ($plan->slug === 'enterprise') {
                $adminLimit = 999;
            }
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

            $currentAdminCount = DB::connection('tenant')->table('users')->where('role', 'admin')->count();
            if ($currentAdminCount >= $adminLimit) {
                DB::disconnect('tenant');
                return back()->with('error', "Admin seat limit reached ({$adminLimit} max on current plan). Please upgrade to add more administrators.");
            }

            $emailExists = DB::connection('tenant')->table('users')->where('email', $request->email)->exists();
            if ($emailExists) {
                DB::disconnect('tenant');
                return back()->with('error', "A user with email '{$request->email}' already exists in this institute.");
            }

            DB::connection('tenant')->table('users')->insert([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'number' => $request->number ?: '0000000000',
                'username' => Str::slug($request->fname . '-' . $request->lname . '-' . rand(100, 999)),
                'password' => \Hash::make($request->password),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::disconnect('tenant');
            return back()->with('success', 'Tenant administrator added successfully!');
        } catch (\Throwable $e) {
            DB::disconnect('tenant');
            return back()->with('error', 'Failed to create tenant admin: ' . $e->getMessage());
        }
    }

    public function destroyAdmin(Request $request, $identifier, $adminId)
    {
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

            $adminCount = DB::connection('tenant')->table('users')->where('role', 'admin')->count();
            if ($adminCount <= 1) {
                DB::disconnect('tenant');
                return back()->with('error', 'You cannot delete the primary administrator of this institute.');
            }

            DB::connection('tenant')->table('users')
                ->where('id', $adminId)
                ->where('role', 'admin')
                ->delete();

            DB::disconnect('tenant');
            return back()->with('success', 'Tenant administrator removed successfully!');
        } catch (\Throwable $e) {
            DB::disconnect('tenant');
            return back()->with('error', 'Failed to remove tenant admin: ' . $e->getMessage());
        }
    }

    public function resetAdminPassword(Request $request, $identifier)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                function ($attribute, $value, $fail) {
                    $commonWeak = ['12345678', '123456789', 'password', 'password1', 'password123', 'admin123', 'qwerty123', '11111111'];
                    if (in_array(strtolower($value), $commonWeak) || preg_match('/^(.)\1+$/', $value)) {
                        $fail('Password is too common or weak. Please choose a stronger password.');
                    }
                }
            ],
            'admin_id' => 'nullable|integer',
        ], [
            'password.min' => 'Admin password must be at least 8 characters long.',
            'password.regex' => 'Admin password must contain both letters and numbers for security.',
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

            $query = DB::connection('tenant')->table('users')->where('role', 'admin');
            if ($request->filled('admin_id')) {
                $query->where('id', $request->admin_id);
            }

            $query->update([
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
            'confirm_subdomain' => 'required|string',
        ]);

        $user = Auth::guard('web')->user();

        $tenant = Tenant::where(function ($q) use ($identifier) {
            $q->where('subdomain', $identifier)->orWhere('id', $identifier);
        })->where('admin_id', $user->id)->firstOrFail();

        $expectedSubdomain = strtolower(trim($tenant->subdomain));
        $typedSubdomain = strtolower(trim($request->confirm_subdomain));

        if ($typedSubdomain !== $expectedSubdomain) {
            return back()->with('error', "Confirmation failed! Please type the exact subdomain '{$tenant->subdomain}' to schedule deletion.");
        }

        // Schedule deletion for 24 hours from now and immediately freeze all services
        $tenant->scheduled_delete_at = now()->addHours(24);
        $tenant->status = 'suspended';
        $tenant->save();

        return redirect()->route('tenants.index')->with('success', "Workspace '{$tenant->name}' has been scheduled for deletion. All services are frozen immediately. You have 24 hours to restore it from your console.");
    }

    public function restore(Request $request, $identifier)
    {
        $user = Auth::guard('web')->user();

        $tenant = Tenant::where(function ($q) use ($identifier) {
            $q->where('subdomain', $identifier)->orWhere('id', $identifier);
        })->where('admin_id', $user->id)->firstOrFail();

        if (empty($tenant->scheduled_delete_at)) {
            return back()->with('info', "Workspace '{$tenant->name}' is not scheduled for deletion.");
        }

        // Cancel scheduled deletion and reactivate
        $tenant->scheduled_delete_at = null;
        $tenant->status = 'active';
        $tenant->save();

        return back()->with('success', "Workspace '{$tenant->name}' has been restored successfully! All services and access have been reactivated.");
    }

    public function purgeExpiredTenants()
    {
        $expiredTenants = Tenant::whereNotNull('scheduled_delete_at')
            ->where('scheduled_delete_at', '<=', now())
            ->get();

        foreach ($expiredTenants as $tenant) {
            if ($tenant->db_name) {
                try {
                    DB::statement("DROP DATABASE IF EXISTS `{$tenant->db_name}`");
                } catch (\Throwable $e) {
                    // Ignore DB drop error if already removed
                }
            }

            $tenantDir = storage_path('app/public/tenants/' . $tenant->id);
            if (file_exists($tenantDir)) {
                try {
                    \Illuminate\Support\Facades\File::deleteDirectory($tenantDir);
                } catch (\Throwable $e) {
                    // Ignore directory deletion error
                }
            }

            $tenant->delete();
        }
    }
}
