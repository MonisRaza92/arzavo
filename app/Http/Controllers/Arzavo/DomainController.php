<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;

class DomainController
{
    public function verifyDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string'
        ]);

        $domain = strtolower(trim($request->domain));
        $domain = str_replace(['https://', 'http://'], '', $domain);
        $domain = explode('/', $domain)[0];

        // --------------------------
        // 1. Find tenant
        // --------------------------
        $tenant = Tenant::where('custom_domain', $domain)->first();
        if (! $tenant) {
            return response()->json([
                'status' => 'error',
                'message' => "No tenant found for domain: $domain"
            ], 404);
        }

        // --------------------------
        // 2. Detect server IP
        // --------------------------
        $serverIp = trim(shell_exec("curl -s http://checkip.amazonaws.com"));

        $dns = @dns_get_record($domain, DNS_A);
        if (empty($dns)) {
            return response()->json([
                'status' => 'error',
                'message' => "No A-record found for $domain. Add A → $serverIp"
            ]);
        }

        $points = collect($dns)->contains(fn($r) => ($r['ip'] ?? null) === $serverIp);

        if (! $points) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain not pointing. Add:<br>A → $serverIp"
            ]);
        }

        // --------------------------
        // 3. CREATE NGINX CONFIG (via root script)
        // --------------------------
        $nginxCmd = "sudo /usr/local/bin/generate-domain-nginx.sh " . escapeshellarg($domain) . " 2>&1";
        $nginxOutput = shell_exec($nginxCmd);

        if (! str_contains($nginxOutput, 'Nginx config created')) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed to create Nginx config.",
                'nginx_output' => $nginxOutput
            ], 500);
        }

        // --------------------------
        // 4. INSTALL SSL (via root script)
        // --------------------------
        $sslCmd = "sudo /usr/local/bin/tenant-ssl.sh " . escapeshellarg($domain) . " 2>&1";
        $sslOutput = shell_exec($sslCmd);

        if (! str_contains($sslOutput, 'Congratulations')) {
            return response()->json([
                'status' => 'error',
                'message' => "SSL installation failed.",
                'ssl_output' => $sslOutput
            ], 500);
        }

        // --------------------------
        // 5. Update tenant
        // --------------------------
        $tenant->update([
            'domain_verified'      => true,
            'domain_verified_at'   => now(),
            'domain_ssl_output'    => $sslOutput,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "$domain is LIVE with SSL",
            'nginx_output' => $nginxOutput,
            'ssl_output' => $sslOutput
        ]);
    }
}
