<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Tenant;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $baseDomain = config('app.domain', 'arzavo.test');

        // Skip main platform domain
        if ($host === $baseDomain || $host === "www.$baseDomain") {
            return $next($request);
        }

        $tenant = Tenant::where('subdomain', $host)
            ->orWhere(function ($query) use ($host) {
                $query->where('custom_domain', $host)
                    ->where('domain_verified', true);
            })
            ->first();

        if (!$tenant) {
            abort(404, "Tenant not found for domain: $host");
        }

        // ✅ IMPORTANT: Tenant domain ke liye session configure karo
        config([
            'session.cookie' => 'tenant_session_' . md5($host),
            'session.domain' => $host,
        ]);

        app()->instance('currentTenant', $tenant);
        session(['tenant_id' => $tenant->id]);
        URL::forceRootUrl($request->getSchemeAndHttpHost());

        return $next($request);
    }
}
