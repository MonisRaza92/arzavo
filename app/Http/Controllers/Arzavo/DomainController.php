<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DomainController
{
    public function verifyDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string'
        ]);

        $domain = strtolower(trim($request->domain));

        // 1. Find tenant
        $tenant = Tenant::where('custom_domain', $domain)->first();
        if (! $tenant) {
            return response()->json([
                'status'  => 'error',
                'message' => "No tenant found for domain: <b>$domain</b>"
            ], 404);
        }

        // 2. DNS validation
        $serverIp  = '3.80.86.193';
        $dnsRecords = @dns_get_record($domain, DNS_A);
        if (empty($dnsRecords)) {
            return response()->json([
                'status' => 'error',
                'message' => "No A-record found for <b>$domain</b>.<br>Add: A → $serverIp"
            ], 400);
        }

        $pointsToUs = collect($dnsRecords)->contains(fn($r) => ($r['ip'] ?? null) === $serverIp);
        if (! $pointsToUs) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain is not pointing to server.<br><b>Fix DNS:</b><br>A → $serverIp"
            ], 400);
        }


        // -----------------------------------------------
        // 3. CREATE NGINX CONFIG FOR THIS DOMAIN
        // -----------------------------------------------
        $nginxCmd = "sudo /usr/local/bin/generate-domain-nginx.sh {$domain} 2>&1";
        $nginxOutput = shell_exec($nginxCmd) ?? '';

        if (! str_contains($nginxOutput, 'Nginx config created')) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed to create nginx config.",
                'raw_output' => $nginxOutput
            ], 500);
        }


        // -----------------------------------------------
        // 4. RUN SSL SCRIPT
        // -----------------------------------------------
        $sslCmd = "sudo /usr/local/bin/tenant-ssl.sh {$domain} 2>&1";
        $sslOutput = shell_exec($sslCmd) ?? '';


        // Detect rate limit
        if (preg_match('/retry after (.*?) UTC/i', $sslOutput, $match)) {
            $utcTime = $match[1];
            $istTime = Carbon::parse($utcTime, 'UTC')->setTimezone('Asia/Kolkata')->format('d M Y, h:i A');

            return response()->json([
                'status' => 'rate_limited',
                'message' => "Too many SSL attempts. Try again after:<br><b>$istTime IST</b>",
                'retry_utc' => $utcTime,
                'retry_ist' => $istTime,
                'raw_output' => $sslOutput
            ], 429);
        }


        // SSL failed
        if (! str_contains($sslOutput, 'Successfully received certificate')) {
            return response()->json([
                'status' => 'error',
                'message' => "SSL generation failed.",
                'raw_output' => $sslOutput
            ], 500);
        }


        // -----------------------------------------------
        // 5. Mark verified in database
        // -----------------------------------------------
        $tenant->update([
            'domain_verified' => true,
            'domain_verified_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Domain connected & SSL installed successfully!",
            'domain' => $domain,
            'nginx_output' => $nginxOutput,
            'ssl_output' => $sslOutput
        ]);
    }
}
