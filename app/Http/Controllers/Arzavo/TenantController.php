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

        return view('arzavo.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
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
                'required',
                'string',
                'max:50',
                Rule::unique('tenants', 'custom_domain')
            ],

        ]);

        $user = Auth::user();
        // Create Tenant
        $tenant = Tenant::create([
            'admin_id'   => $user->id,
            'name'       => $request->name,
            'logo'       => '',
            'banner'     => '',
            'heading'    => $request->name,
            'about'      => 'Coming soon...',
            'subdomain'  => strtolower($request->subdomain). '.' . config('app.domain'),
            'custom_domain'  => $request->custom_domain,
            'domain_verified' => false,
            'status'     => 'active',
        ]);

        // Create Database for tenant
        $this->createTenantDatabase($tenant);

        // Run Tenant Migrations & Create Admin
        $this->initializeTenant($tenant, $user);

        return back()->with('success', 'Tenant created successfully!');
    }

    // Create tenant DB
    protected function createTenantDatabase($tenant)
    {
        // Convert tenant name to db-safe slug
        $slug = Str::slug($tenant->name);

        // Ensure DB name is always unique
        $dbName = $slug . '_' . Str::random(8) . rand(100000, 999999);

        $dbName = substr(strtolower($dbName), 0, 64);

        // Create the database
        DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Save db info in tenant record
        $tenant->update([
            'db_name'     => $dbName,
            'db_username' => env('DB_USERNAME'),
            'db_password' => env('DB_PASSWORD'),
        ]);
    }


    // Run tenant migrations & create admin user in tenant DB
    protected function initializeTenant($tenant, $mainUser)
    {
        // Set tenant DB connection
        config([
            'database.connections.tenant' => [
                'driver'   => 'mysql',
                'host'     => env('DB_HOST'),
                'port'     => env('DB_PORT'),
                'database' => $tenant->db_name,
                'username' => $tenant->db_username,
                'password' => $tenant->db_password,
            ]
        ]);

        // Reset cached DB connection
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Run tenant migrations
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => database_path('migrations/tenant'),
            '--force' => true
        ]);

        // Run tenant seeder
        Artisan::call('db:seed', [
            '--class' => 'TenantSeeder',
            '--database' => 'tenant',
            '--force' => true
        ]);

        // Create admin user
        DB::connection('tenant')->table('users')->insert([
            'fname'      => $mainUser->fname,
            'lname'      => $mainUser->lname,
            'email'      => $mainUser->email,
            'number'     => $mainUser->number,
            'username'   => $mainUser->username,
            'password'   => $mainUser->password,
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    public function update(Request $request, $id)
    {
        // Validate and update tenant logic here
    }

    public function toggleStatus($id)
    {
        // Toggle tenant active/inactive status logic here
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // Drop the tenant database
        if ($tenant->db_name) {
            DB::statement("DROP DATABASE IF EXISTS `{$tenant->db_name}`");
        }
        
        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully!');
    }
}
