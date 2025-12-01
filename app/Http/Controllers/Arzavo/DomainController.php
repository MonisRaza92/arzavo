<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;

class DomainController
{
    // STEP 1 → CLEAN DOMAIN
    private function cleanDomain($domain)
    {
        $domain = strtolower(trim($domain));

        $domain = str_replace(['http://', 'https://'], '', $domain);
        $domain = preg_replace('/^www\./', '', $domain);

        return rtrim($domain, '/');
    }

    // STEP 2 → ADD domain + AUTO SSL
    public function verifyDomain(Request $request, $tenantId)
    {
        $request->validate([
            'domain' => 'required|string'
        ]);

        $domain = $this->cleanDomain($request->domain);

        $tenant = Tenant::findOrFail($tenantId);

        // Save domain in tenant table
        $tenant->update([
            'domain' => $domain,
            'domain_verified' => false,
        ]);

        // CHECK DNS → domain must point to server
        $serverIp = '3.80.86.193';
        $dnsRecords = dns_get_record($domain, DNS_A);

        if (empty($dnsRecords)) {
            return response()->json([
                'status' => 'error',
                'message' => "❌ No A-record found for $domain",
            ], 400);
        }

        $pointsToUs = false;
        foreach ($dnsRecords as $rec) {
            if ($rec['ip'] === $serverIp) {
                $pointsToUs = true;
            }
        }

        if (! $pointsToUs) {
            return response()->json([
                'status' => 'error',
                'message' => "❌ Domain is not pointing to your server.  
Add this DNS record first:  
A → $serverIp",
            ], 400);
        }

        // STEP 3 → RUN SSL SCRIPT
        $command = "sudo /usr/local/bin/tenant-ssl.sh {$domain} 2>&1";
        $output  = shell_exec($command);

        // SSL FAILURE
        if (! str_contains($output, 'Successfully received certificate')) {
            return response()->json([
                'status' => 'error',
                'message' => '❌ SSL generation failed.',
                'output' => $output
            ], 500);
        }

        // STEP 4 → Mark domain verified
        $tenant->update([
            'domain_verified' => true,
            'domain_verified_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "✅ Domain connected & SSL installed successfully!",
            'domain'  => $domain
        ]);
    }
}
