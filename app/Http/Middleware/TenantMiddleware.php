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

        // 1. Skip system domain (main platform, admin, login, register, super)
        if (isSystemDomain($host)) {
            return $next($request);
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

        // 7. Auto-authenticate tenant owner if already authenticated on main platform (SSO)
        $this->syncTenantOwnerAuth($tenant);

        return $next($request);
    }

    private function isMainDomain($host, $base)
    {
        return isSystemDomain($host);
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
        $base = strtolower(config('app.domain'));
        // If it is a third-party custom domain (e.g. school.com), scope cookie to that custom domain
        if (!str_ends_with($host, '.' . $base) && $host !== $base) {
            config([
                'session.domain' => $host,
            ]);
        }
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

    private function syncTenantOwnerAuth($tenant)
    {
        try {
            if (!\Illuminate\Support\Facades\Auth::guard('tenant')->check() && \Illuminate\Support\Facades\Auth::guard('web')->check()) {
                $webUser = \Illuminate\Support\Facades\Auth::guard('web')->user();
                if ($webUser && ($tenant->user_id === $webUser->id || $webUser->role === 'super_admin')) {
                    $tenantAdmin = \App\Models\Tenant\User::where('email', $webUser->email)->first();
                    if ($tenantAdmin) {
                        \Illuminate\Support\Facades\Auth::guard('tenant')->login($tenantAdmin);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently continue if tenant table is not accessible
        }
    }
}