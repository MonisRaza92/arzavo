<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Arzavo\Tenant;
use App\Services\ThemeContext;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = strtolower($request->getHost());
        $base = strtolower(config('app.domain'));

        // 1. Skip main domain (abort if tenant route is accessed on main domain)
        if ($this->isMainDomain($host, $base)) {
            abort(404);
        }

        // 2. Resolve tenant
        $tenant = $this->resolveTenant($host);

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // 3. Check status
        $this->validateTenantStatus($tenant);

        // 4. Setup session isolation
        $this->setupSession($host);

        // 5. Switch database
        $this->switchDatabase($tenant);

        // 6. Bind tenant + theme
        $this->bindContext($tenant);

        return $next($request);
    }

    private function isMainDomain($host, $base)
    {
        return $host === $base || $host === "www.$base";
    }

    private function resolveTenant($host)
    {
        return Tenant::where(function ($q) use ($host) {
            $q->whereRaw('LOWER(subdomain) = ?', [$host])
                ->orWhere(function ($q2) use ($host) {
                    $q2->whereRaw('LOWER(custom_domain) = ?', [$host])
                        ->where('domain_verified', true);
                });
        })->first();
    }

    private function validateTenantStatus($tenant)
    {
        $status = strtolower(trim((string) $tenant->status));

        if ($status === 'suspended') {
            abort(403, $tenant->name . 'suspended');
        }
    }

    private function setupSession($host)
    {
        config([
            'session.cookie' => 'tenant_' . md5($host),
            'session.domain' => null,
            'session.path' => '/',
        ]);
    }

    private function switchDatabase($tenant)
    {
        config([
            'database.default' => 'tenant',
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $tenant->db_name,
                'username' => $tenant->db_username,
                'password' => $tenant->db_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        app('db')->setDefaultConnection('tenant');
    }

    private function bindContext($tenant)
    {
        $theme = ThemeContext::active();

        app()->instance('currentTenant', $tenant);
        app()->instance('activeTheme', $theme);
        app()->instance('currentThemeSlug', $theme?->theme_slug);
        app()->instance('currentThemeId', $theme?->id);
    }
}