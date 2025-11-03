<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        $domain = trim(config('app.domain'));
        $host = trim($request->getHost());

        $subdomain = str_replace("." . $domain, '', $host);

        if (!Auth::check()) {

            if ($subdomain !== $domain && $subdomain !== 'www' && $subdomain !== $host) {
                return redirect()->route('tenant.login.form', ['tenant' => $subdomain]);
            }

            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
