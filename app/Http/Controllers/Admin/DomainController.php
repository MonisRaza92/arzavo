<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tenant;
use Illuminate\Http\Request;

class DomainController
{
    public function verify(Request $request, $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        if (!$tenant->domain) {
            return response()->json(['message' => 'No custom domain configured.'], 400);
        }

        $records = dns_get_record($tenant->domain, DNS_CNAME);

        foreach ($records as $record) {
            if (isset($record['target']) && str_contains($record['target'], 'verify.' . config('app.domain'))) {
                $tenant->update([
                    'domain_verified' => true,
                    'domain_verified_at' => now(),
                ]);

                return response()->json(['message' => '✅ Domain verified successfully.']);
            }
        }

        return response()->json(['message' => '❌ Verification failed. DNS record not found.']);
    }

    public function connect(Request $request, $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $domain = $request->query('domain');

        if (! $domain) {
            return response()->json(['status' => 'error', 'message' => '⚠️ Domain required.']);
        }

        // Save temporarily before verifying
        $tenant->update(['custom_domain' => $domain]);

        // Check for DNS verification record
        $records = dns_get_record('verify.' . $domain, DNS_CNAME);

        foreach ($records as $record) {
            if (isset($record['target']) && str_contains($record['target'], 'verify.' . config('app.domain'))) {
                $tenant->update([
                    'domain_verified' => true,
                    'domain_verified_at' => now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => '✅ Domain verified successfully and connected!',
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => '❌ Verification failed. CNAME record not found for verify.' . $domain,
        ]);
    }
}
