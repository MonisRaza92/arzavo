<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tenant;
use Illuminate\Http\Request;

class DomainVerificationController
{
    public function verify(Request $request, $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        if (! $tenant->custom_domain) {
            return response()->json(['message' => 'No custom domain configured.'], 400);
        }

        $records = dns_get_record($tenant->custom_domain, DNS_CNAME);

        foreach ($records as $record) {
            if (isset($record['target']) && str_contains($record['target'], 'verify.arzavo.com')) {
                $tenant->update([
                    'domain_verified' => true,
                    'domain_verified_at' => now(),
                ]);

                return response()->json(['message' => '✅ Domain verified successfully.']);
            }
        }

        return response()->json(['message' => '❌ Verification failed. DNS record not found.']);
    }
}
