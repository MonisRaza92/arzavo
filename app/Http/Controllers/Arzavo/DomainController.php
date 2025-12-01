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

        // -----------------------------
        // 1. Fetch Tenant
        // -----------------------------
        $tenant = Tenant::where('custom_domain', $domain)->first();

        if (! $tenant) {
            return response()->json([
                'status'  => 'error',
                'message' => "No tenant found for domain: <b>$domain</b>"
            ], 404);
        }

        // -----------------------------
        // 2. DNS Validation
        // -----------------------------
        $serverIp  = '3.80.86.193';
        $dnsRecords = @dns_get_record($domain, DNS_A);

        if (empty($dnsRecords)) {
            return response()->json([
                'status'  => 'error',
                'message' => "No A-record found for <b>$domain</b>.<br>Add: A → $serverIp"
            ], 400);
        }

        $pointsToUs = collect($dnsRecords)->contains(fn($r) => ($r['ip'] ?? null) === $serverIp);

        if (! $pointsToUs) {
            return response()->json([
                'status'  => 'error',
                'message' => "Domain is not pointing to server.<br><b>Fix DNS:</b><br>A → $serverIp"
            ], 400);
        }

        // -----------------------------
        // 3. Run SSL Script
        // -----------------------------
        $command = "sudo /usr/local/bin/tenant-ssl.sh {$domain} 2>&1";
        $output  = shell_exec($command) ?? '';

        // -----------------------------
        // 4. Detect Let's Encrypt Rate Limit
        // -----------------------------
        if (preg_match('/retry after (.*?) UTC/i', $output, $match)) {
            $utcTime = $match[1];
            $istTime = Carbon::parse($utcTime, 'UTC')->setTimezone('Asia/Kolkata')->format('d M Y, h:i A');

            return response()->json([
                'status'       => 'rate_limited',
                'message'      => "Too many SSL attempts. Try again after:<br><b>$istTime IST</b>",
                'retry_utc'    => $utcTime,
                'retry_ist'    => $istTime,
                'raw_output'   => $output,
            ], 429);
        }

        // -----------------------------
        // 5. Detect SSL Failure
        // -----------------------------
        if (! str_contains($output, 'Successfully received certificate')) {
            return response()->json([
                'status'    => 'error',
                'message'   => "SSL generation failed.",
                'raw_output' => $output
            ], 500);
        }

        // -----------------------------
        // 6. Mark Verified in Database
        // -----------------------------
        $tenant->update([
            'domain_verified'    => true,
            'domain_verified_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Domain connected & SSL installed successfully!",
            'domain'  => $domain
        ]);
    }
}
