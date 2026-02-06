<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\Arzavo\Tenant;
use App\Services\ThemeContext;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $base = config('app.domain'); // arzavo.test

        // 1. MAIN PLATFORM — No tenant logic
        if ($host === $base || $host === "www.$base") {
            return $next($request);
        }

        // 2. Identify tenant
        $tenant = Tenant::where('subdomain', $host)
            ->orWhere('custom_domain', $host)
            ->where('domain_verified', true)
            ->first();

        if (!$tenant) {
            abort(404, "Tenant not found for: $host");
        }

        // 3. SESSION ISOLATION (MOST IMPORTANT)
        // Unique per-tenant cookie name
        config([
            'session.cookie' => 'tenant_' . md5($host),
            'session.domain' => null,  // Important: do NOT set domain manually
            'session.path'   => '/',   // ensure shared path
        ]);



        // 4. TENANT DB SWITCH
        config([
            'database.connections.tenant' => [
                'driver'   => 'mysql',
                'host'     => config('database.connections.mysql.host', '127.0.0.1'),
                'port'     => config('database.connections.mysql.port', 3306),
                'database' => $tenant->db_name,
                'username' => $tenant->db_username,
                'password' => $tenant->db_password,
                'charset'  => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);


        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');

        // 5. BIND TENANT INSTANCE
        $activeTheme = ThemeContext::active();

        $themeSlug = $activeTheme->theme_slug;
        // fallback safety
        $tenantThemeId = $activeTheme->id;

        // Bind globally
        app()->instance('currentThemeSlug', $themeSlug);
        app()->instance('currentTenant', $tenant);
        app()->instance('currentThemeId', $tenantThemeId);
        app()->instance('activeTheme', $activeTheme);
        
        return $next($request);
    }
}
