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

        // Cleanup domain
        $domain = strtolower(trim($request->domain));
        $domain = str_replace(['https://', 'http://'], '', $domain);
        $domain = explode('/', $domain)[0];

        // 1. Find tenant
        $tenant = Tenant::where('custom_domain', $domain)->first();
        if (! $tenant) {
            return response()->json([
                'status'  => 'error',
                'message' => "No tenant found for domain: <b>$domain</b>"
            ], 404);
        }

        // 2. Auto-detect public server IP
        $serverIp = trim(shell_exec("curl -s http://checkip.amazonaws.com"));

        if (!$serverIp || !filter_var($serverIp, FILTER_VALIDATE_IP)) {
            return response()->json([
                'status' => 'error',
                'message' => "Unable to detect server public IP."
            ], 500);
        }

        // 3. DNS validation
        $dns = @dns_get_record($domain, DNS_A);

        if (empty($dns)) {
            return response()->json([
                'status' => 'error',
                'message' => "No A-record found for <b>$domain</b>.<br>Add: A → $serverIp"
            ], 400);
        }

        $pointsToUs = collect($dns)->contains(fn($r) => ($r['ip'] ?? null) === $serverIp);

        if (! $pointsToUs) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain is not pointing to server.<br><b>Add A-record:</b> $serverIp"
            ], 400);
        }

        // 4. Generate SSL directly from here (NO JOB)
        $safeDomain = escapeshellarg($domain);

        $cmd = "sudo certbot --nginx -d {$safeDomain} --non-interactive --agree-tos -m monisrazakhan2001@gmail.com --redirect 2>&1";

        $output = shell_exec($cmd);

        if (! $output) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed to run Certbot command."
            ], 500);
        }

        // 5. Check success
        if (! str_contains($output, 'Congratulations')) {
            return response()->json([
                'status' => 'error',
                'message' => "SSL installation failed.",
                'certbot_output' => $output
            ], 500);
        }

        // 6. Mark verified
        $tenant->update([
            'domain_verified'     => true,
            'domain_verified_at'  => now(),
            'domain_ssl_output'   => $output
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Domain verified and SSL installed successfully!",
            'domain' => $domain,
            'server_ip' => $serverIp,
            'certbot_output' => $output
        ]);
    }
}
