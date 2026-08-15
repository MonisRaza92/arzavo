<?php

namespace App\Http\Controllers\Arzavo\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arzavo\User;
use App\Models\Arzavo\Tenant;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Counts
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $adminsCount = User::whereIn('role', ['admin', 'super_admin'])->count();

        // User growth this month
        $startOfThisMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        $usersThisMonth = User::where('created_at', '>=', $startOfThisMonth)->count();
        $usersLastMonth = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $userGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : ($usersThisMonth > 0 ? 100 : 0);

        // 2. Tenants Metrics
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();
        $verifiedDomainsCount = Tenant::where('domain_verified', true)->count();
        $customDomainsCount = Tenant::whereNotNull('custom_domain')->where('custom_domain', '!=', '')->count();

        $tenantsThisMonth = Tenant::where('created_at', '>=', $startOfThisMonth)->count();
        $tenantsLastMonth = Tenant::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $tenantGrowth = $tenantsLastMonth > 0 ? round((($tenantsThisMonth - $tenantsLastMonth) / $tenantsLastMonth) * 100, 1) : ($tenantsThisMonth > 0 ? 100 : 0);

        // 3. Plans & Subscriptions Metrics
        $plans = Plan::withCount('subscriptions')->get();
        $totalPlans = $plans->count();
        $activePlans = $plans->where('is_active', true)->count();

        $activeSubscriptionsCount = Subscription::where('status', 'active')->count();
        
        // Estimated MRR calculation
        $estimatedMRR = Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.monthly_price');

        // 4. Monthly Trend Data (Last 6 Months)
        $months = [];
        $tenantChartData = [];
        $userChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M Y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $months[] = $date->format('M');
            $tenantChartData[] = Tenant::whereBetween('created_at', [$start, $end])->count();
            $userChartData[] = User::whereBetween('created_at', [$start, $end])->count();
        }

        // 5. Plan Distribution Data
        $planLabels = [];
        $planCounts = [];
        $planColors = ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'];

        foreach ($plans as $plan) {
            $planLabels[] = $plan->name;
            $planCounts[] = $plan->subscriptions_count;
        }

        // 6. Recent Activity Collections
        $recentTenants = Tenant::with('admin')
            ->latest()
            ->take(6)
            ->get();

        $recentUsers = User::with('tenants')
            ->latest()
            ->take(6)
            ->get();

        // 7. System Diagnostics Info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx/Apache',
            'database_driver' => config('database.default'),
            'app_env' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
        ];

        return view('arzavo.admin.dashboard.index', compact(
            'totalUsers',
            'activeUsers',
            'suspendedUsers',
            'adminsCount',
            'userGrowth',
            'usersThisMonth',
            'totalTenants',
            'activeTenants',
            'suspendedTenants',
            'verifiedDomainsCount',
            'customDomainsCount',
            'tenantGrowth',
            'tenantsThisMonth',
            'totalPlans',
            'activePlans',
            'activeSubscriptionsCount',
            'estimatedMRR',
            'plans',
            'months',
            'tenantChartData',
            'userChartData',
            'planLabels',
            'planCounts',
            'planColors',
            'recentTenants',
            'recentUsers',
            'systemInfo'
        ));
    }
}
