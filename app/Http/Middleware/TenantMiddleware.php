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
        $domain = config('app.domain');

        // 🔍 Find subdomain
        $subdomain = str_replace("." . $domain, "", $host);

        // ✅ Tenant Load Before Auth
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            abort(404, "Tenant not found");
        }

        // ✅ Attach active tenant everywhere
        app()->instance('currentTenant', $tenant);
        session(['tenant_id' => $tenant->id]);

        // ✅ Add tenant in URL defaults for route()
        URL::defaults([
            'tenant' => $tenant->subdomain
        ]);

        // ✅ If user is logged in → authorization check
        if (Auth::check()) {
            if (!Auth::user()->tenants()->where('id', $tenant->id)->exists()) {
                abort(403, "You are not authorized for this tenant");
            }

            if (!$tenant->is_active) {
                abort(403, "School is inactive or suspended");
            }
        }

        return $next($request);
    }
}
