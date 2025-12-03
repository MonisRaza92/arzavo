<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use App\Jobs\IssueSslForDomain;
use Illuminate\Http\Request;

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

        // 2. DNS Validation - check if domain points to THIS server IP
        $serverIp = request()->server('SERVER_ADDR'); // dynamically detect EC2 public IP
        $dnsRecords = @dns_get_record($domain, DNS_A);

        if (empty($dnsRecords)) {
            return response()->json([
                'status' => 'error',
                'message' => "No A-record found for <b>$domain</b>.<br>Add: A → $serverIp"
            ], 400);
        }

        $pointsToServer = collect($dnsRecords)->contains(
            fn($r) => ($r['ip'] ?? null) === $serverIp
        );

        if (! $pointsToServer) {
            return response()->json([
                'status' => 'error',
                'message' => "Domain is not pointing to server.<br><b>Fix DNS:</b><br>A → $serverIp"
            ], 400);
        }

        // 3. Dispatch SSL Job (background)
        IssueSslForDomain::dispatch($tenant->id, $domain);

        return response()->json([
            'status' => 'queued',
            'message' => "Domain verified. SSL installation started.",
            'domain'  => $domain
        ]);
    }
}
