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
        // 2. Detect target host & IP
        // --------------------------
        $targetHost = env('CUSTOM_DOMAIN_TARGET', 'domain.arzavo.com');
        $targetIp = gethostbyname($targetHost);
        if ($targetIp === $targetHost) {
            $targetIp = env('SERVER_IP', '13.234.98.38');
        }

        $isPointing = false;

        // Check CNAME records for apex domain and www
        $cnameRecords = array_merge(
            @dns_get_record($domain, DNS_CNAME) ?: [],
            @dns_get_record('www.' . $domain, DNS_CNAME) ?: []
        );

        foreach ($cnameRecords as $rec) {
            if (isset($rec['target']) && (
                strcasecmp(rtrim($rec['target'], '.'), rtrim($targetHost, '.')) === 0 ||
                str_contains(strtolower($rec['target']), 'domain.arzavo')
            )) {
                $isPointing = true;
                break;
            }
        }

        // Check resolved A / IP records (handles ALIAS / ANAME / CNAME Flattening & A records)
        if (! $isPointing) {
            $resolvedIp = gethostbyname($domain);
            $resolvedWwwIp = gethostbyname('www.' . $domain);
            if ($resolvedIp === $targetIp || $resolvedWwwIp === $targetIp) {
                $isPointing = true;
            }
        }

        // Check TXT ownership verification (verify.arzavo.com)
        $verifyHost = env('VERIFY_DOMAIN_TARGET', 'verify.arzavo.com');
        $txtRecords = array_merge(
            @dns_get_record($domain, DNS_TXT) ?: [],
            @dns_get_record('verify.' . $domain, DNS_TXT) ?: []
        );
        $hasTxtOwnership = false;
        foreach ($txtRecords as $rec) {
            $val = $rec['txt'] ?? (isset($rec['entries']) ? implode(' ', $rec['entries']) : '');
            if (str_contains(strtolower($val), 'verify.arzavo') || strcasecmp(trim($val), $verifyHost) === 0) {
                $hasTxtOwnership = true;
                break;
            }
        }

        if (! $isPointing && ! $hasTxtOwnership) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain is not pointing to <b>$targetHost</b>.<br>Please add: CNAME <b>@ → $targetHost</b> or TXT Record <b>@ → $verifyHost</b> in your domain DNS."
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
        ping_google();

        return response()->json([
            'status' => 'success',
            'message' => "$domain is LIVE with SSL",
            'nginx_output' => $nginxOutput,
            'ssl_output' => $sslOutput
        ]);
    }
}
