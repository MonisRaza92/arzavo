<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Subscription;

class BillingController
{
    public function index()
    {
        $tenant = app('currentTenant');
        $subscription = $tenant ? $tenant->subscription : null;
        $plan = $subscription ? $subscription->plan : null;
        $plans = Plan::where('is_active', true)->where('is_hidden', false)->orderBy('monthly_price', 'asc')->get();

        // Compute actual live tenant usage metrics from tenant DB
        $studentsCount = \App\Models\Tenant\User::where('role', 'student')->count();
        $teachersCount = \App\Models\Tenant\User::where('role', 'teacher')->count();
        $staffCount = \App\Models\Tenant\User::where('role', 'staff')->count();
        $coursesCount = \App\Models\Tenant\Course::count();

        // Calculate real physical and DB storage used
        $storageBytes = 0;
        try {
            if (\Schema::connection('tenant')->hasTable('contents')) {
                $storageBytes += (int) (\DB::connection('tenant')->table('contents')->sum('size') ?? 0);
            }
            if (\Schema::connection('tenant')->hasTable('media')) {
                $storageBytes += (int) (\DB::connection('tenant')->table('media')->sum('size') ?? 0);
            }
        } catch (\Throwable $e) {}

        $tenantDir = storage_path('app/public/tenants/' . ($tenant->id ?? 0));
        if (file_exists($tenantDir)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tenantDir, \FilesystemIterator::SKIP_DOTS)) as $file) {
                if ($file->isFile()) {
                    $storageBytes += $file->getSize();
                }
            }
        }

        $storageUsedMb = round($storageBytes / (1024 * 1024), 2);
        $storageUsedGb = round($storageBytes / (1024 * 1024 * 1024), 2);

        // Get limits from plan (or defaults)
        $studentsLimit = $plan ? ($plan->limits['students'] ?? null) : null;
        $teachersLimit = $plan ? ($plan->limits['teachers'] ?? null) : null;
        $storageLimitGb = $plan ? ($plan->limits['storage'] ?? null) : 5;

        $stats = [
            'students_count' => $studentsCount,
            'students_limit' => $studentsLimit ?: 'Unlimited',
            'students_percent' => ($studentsLimit && $studentsLimit > 0) ? min(100, round(($studentsCount / $studentsLimit) * 100)) : 0,

            'teachers_count' => $teachersCount,
            'teachers_limit' => $teachersLimit ?: 'Unlimited',
            'teachers_percent' => ($teachersLimit && $teachersLimit > 0) ? min(100, round(($teachersCount / $teachersLimit) * 100)) : 0,

            'staff_count' => $staffCount,
            'courses_count' => $coursesCount,

            'storage_used_mb' => $storageUsedMb,
            'storage_used_gb' => $storageUsedGb,
            'storage_limit_gb' => $storageLimitGb,
            'storage_percent' => ($storageLimitGb && $storageLimitGb > 0) ? min(100, round(($storageUsedGb / $storageLimitGb) * 100, 1)) : 0,
        ];

        return view('tenant.admin.billing.index', compact('plans', 'subscription', 'plan', 'tenant', 'stats'));
    }
    
    // public function checkout(Request $request)
    // {
    //     $plan = Plan::findOrFail($request->plan_id);

    //     return view('tenant.admin.billing.checkout', compact('plan'));
    // }
    public function cancelDowngrade()
    {
        $tenant = app('currentTenant');
        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->pending_plan_id) {
            return back()->with('error', 'No downgrade scheduled');
        }

        $subscription->update([
            'pending_plan_id' => null
        ]);

        return back()->with('success', 'Downgrade cancelled successfully');
    }
}
