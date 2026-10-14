@extends('layouts.arzavo.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- 1. GREETING & METRICS FEEDBACK STRIP -->
    <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-normal bg-success-bg text-success">
                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                    Ecosystem Operational
                </span>
                <span class="text-xs text-theme-text-muted">
                    • {{ now()->format('l, d M Y') }}
                </span>
            </div>
            <h1 class="text-2xl font-semibold text-theme-text leading-tight">
                Super Admin Cockpit 👋
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 max-w-2xl leading-relaxed">
                Centralized oversight of all multi-tenant institutes, user permissions, subscription quotas, and system health.
            </p>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('arzavo.admin.tenants.index') }}"
               class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-xs"></i>
                <span>All Tenants</span>
            </a>
            <a href="{{ route('arzavo.admin.plans.index') }}"
               class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-xs"></i>
                <span>Plans</span>
            </a>
            <a href="{{ route('arzavo.admin.users.index') }}"
               class="px-4 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-users text-xs"></i>
                <span>Manage Users</span>
            </a>
        </div>
    </div>

    <!-- 2. TOP KPI CARDS GRID (4 METRICS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- KPI 1: Total Users -->
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted uppercase tracking-wider font-normal">Platform Users</span>
                    <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-semibold text-theme-text">{{ number_format($totalUsers) }}</h2>
                    <span class="inline-flex items-center text-[11px] px-2 py-0.5 rounded-full {{ $userGrowth >= 0 ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                        <i class="fa-solid {{ $userGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px] mr-1"></i>
                        {{ abs($userGrowth) }}%
                    </span>
                </div>
                <p class="text-[11px] text-theme-text-muted mt-1">+{{ $usersThisMonth }} joined this month</p>
            </div>
            <div class="mt-4 p-3 rounded-lg bg-secondary text-xs text-theme-text font-normal flex items-center justify-between">
                <span>Active: <span class="font-semibold">{{ $activeUsers }}</span></span>
                <span>Suspended: <span class="font-semibold">{{ $suspendedUsers }}</span></span>
                <span>Admins: <span class="font-semibold">{{ $adminsCount }}</span></span>
            </div>
        </div>

        <!-- KPI 2: Total Tenants -->
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted uppercase tracking-wider font-normal">Total Institutes</span>
                    <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-semibold text-theme-text">{{ number_format($totalTenants) }}</h2>
                    <span class="inline-flex items-center text-[11px] px-2 py-0.5 rounded-full {{ $tenantGrowth >= 0 ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                        <i class="fa-solid {{ $tenantGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px] mr-1"></i>
                        {{ abs($tenantGrowth) }}%
                    </span>
                </div>
                <p class="text-[11px] text-theme-text-muted mt-1">+{{ $tenantsThisMonth }} institutes added this month</p>
            </div>
            <div class="mt-4 p-3 rounded-lg bg-secondary text-xs text-theme-text font-normal flex items-center justify-between">
                <span>Active: <span class="font-semibold">{{ $activeTenants }}</span></span>
                <span>Custom DNS: <span class="font-semibold">{{ $customDomainsCount }}</span></span>
                <span>Verified: <span class="font-semibold">{{ $verifiedDomainsCount }}</span></span>
            </div>
        </div>

        <!-- KPI 3: Active Subscriptions -->
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted uppercase tracking-wider font-normal">Active Subscriptions</span>
                    <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-semibold text-theme-text">{{ number_format($activeSubscriptionsCount) }}</h2>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-brand/10 text-brand">
                        {{ $activePlans }} Active Tiers
                    </span>
                </div>
                <p class="text-[11px] text-theme-text-muted mt-1">Total defined plans: {{ $totalPlans }}</p>
            </div>
            <div class="mt-4 p-3 rounded-lg bg-secondary text-xs text-theme-text font-normal flex items-center justify-between">
                <span>Billed Institutes: <span class="font-semibold">{{ $activeSubscriptionsCount }}</span></span>
                <a href="{{ route('arzavo.admin.plans.index') }}" class="text-brand hover:underline">Manage Plans →</a>
            </div>
        </div>

        <!-- KPI 4: Monthly Recurring Run Rate -->
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted uppercase tracking-wider font-normal">Estimated MRR</span>
                    <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-semibold text-theme-text">₹{{ number_format($estimatedMRR, 0) }}</h2>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-success-bg text-success">
                        Active MRR
                    </span>
                </div>
                <p class="text-[11px] text-theme-text-muted mt-1">Projected revenue based on current plan charges</p>
            </div>
            <div class="mt-4 p-3 rounded-lg bg-secondary text-xs text-theme-text font-normal flex items-center justify-between">
                <span>Billing Engine: <span class="text-success font-semibold">Healthy</span></span>
                <a href="{{ route('arzavo.admin.tenants.index') }}" class="text-brand hover:underline">Audit Billing →</a>
            </div>
        </div>

    </div>

    <!-- 3. VISUAL CHARTS SECTION (2 COLUMNS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Platform Growth Chart (2 Columns wide) -->
        <div class="lg:col-span-2 rounded-lg bg-primary p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Platform Expansion Trends</h3>
                    <p class="text-xs text-theme-text-muted">Monthly registrations of institutes and users over the past 6 months</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5 text-theme-text-muted">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Tenants
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-theme-text-muted">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span> Users
                    </span>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Plan Distribution Doughnut (1 Column wide) -->
        <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col justify-between">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-theme-text">Tier Distribution</h3>
                <p class="text-xs text-theme-text-muted">Subscriptions categorized by pricing tier</p>
            </div>
            <div class="h-52 relative flex items-center justify-center">
                <canvas id="planDistributionChart"></canvas>
            </div>
            <div class="mt-4 pt-2 space-y-2 text-xs">
                @foreach($plans->take(4) as $idx => $p)
                    <div class="flex items-center justify-between text-theme-text-muted">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $planColors[$idx % count($planColors)] }};"></span>
                            <span>{{ $p->name }}</span>
                        </span>
                        <span class="font-medium text-theme-text">{{ $p->subscriptions_count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- 4. RECENT ACTIVITY GRIDS (TENANTS & USERS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Left: Recent Tenants -->
        <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Recently Created Institutes</h3>
                    <p class="text-xs text-theme-text-muted">Newly provisioned tenant environments</p>
                </div>
                <a href="{{ route('arzavo.admin.tenants.index') }}"
                   class="text-xs text-brand hover:underline inline-flex items-center gap-1">
                    <span>View all</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse($recentTenants as $t)
                    <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3"
                         x-show="!search || '{{ strtolower($t->name . ' ' . $t->subdomain) }}'.includes(search.toLowerCase())">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center shrink-0 uppercase">
                                {{ substr($t->name, 0, 2) }}
                            </div>
                            <div class="min-w-0 truncate">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-semibold text-theme-text truncate">{{ $t->name }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $t->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                    {{ $t->subdomain }}.{{ config('app.domain') }} • {{ $t->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if($t->url)
                                <a href="{{ $t->url }}" target="_blank"
                                   class="p-2 rounded-full bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle transition text-xs"
                                   title="Visit live portal">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            @endif
                            <a href="{{ route('arzavo.admin.tenants.index') }}"
                               class="px-3 py-1.5 rounded-full bg-primary text-theme-text hover:bg-subtle transition text-xs font-normal">
                                Manage
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 rounded-lg bg-secondary text-center text-xs text-theme-text-muted">
                        No recent institutes registered yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Recent Platform Users -->
        <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Recent Users</h3>
                    <p class="text-xs text-theme-text-muted">Users registered across the ecosystem</p>
                </div>
                <a href="{{ route('arzavo.admin.users.index') }}"
                   class="text-xs text-brand hover:underline inline-flex items-center gap-1">
                    <span>View all</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse($recentUsers as $u)
                    <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3"
                         x-show="!search || '{{ strtolower($u->name . ' ' . $u->email) }}'.includes(search.toLowerCase())">
                        <div class="flex items-center gap-3 min-w-0">
                            @if(!empty($u->profile_picture))
                                <img src="{{ media($u->profile_picture) }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-full bg-brand text-primary flex items-center justify-center text-xs font-semibold shrink-0 uppercase">
                                    {{ strtoupper(substr($u->fname ?? ($u->name ?? 'U'), 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 truncate">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-semibold text-theme-text truncate">{{ $u->full_name ?? ($u->name ?? 'User') }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $u->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                        {{ ucfirst($u->status) }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                    {{ $u->email }} • {{ ucfirst($u->role) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-primary text-theme-text-muted text-[10px] font-normal">
                                {{ $u->tenants->count() }} {{ Str::plural('tenant', $u->tenants->count()) }}
                            </span>
                            <a href="{{ route('arzavo.admin.users.index') }}"
                               class="p-2 rounded-full bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle transition text-xs">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 rounded-lg bg-secondary text-center text-xs text-theme-text-muted">
                        No registered users found.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 5. SYSTEM HEALTH & DIAGNOSTICS STRIP -->
    <div class="rounded-lg bg-primary p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-theme-text">System Environment & Diagnostics</h3>
                <p class="text-xs text-theme-text-muted">Host configuration and platform runtime details</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] bg-secondary text-theme-text-muted font-normal">
                PHP {{ $systemInfo['php_version'] }} · Laravel {{ $systemInfo['laravel_version'] }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">Environment</p>
                <p class="text-xs font-semibold text-theme-text uppercase">{{ $systemInfo['app_env'] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">Database Driver</p>
                <p class="text-xs font-semibold text-theme-text uppercase">{{ $systemInfo['database_driver'] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">Debug Mode</p>
                <p class="text-xs font-semibold {{ $systemInfo['debug_mode'] === 'Enabled' ? 'text-warning' : 'text-success' }}">
                    {{ $systemInfo['debug_mode'] }}
                </p>
            </div>
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">Server Software</p>
                <p class="text-xs font-semibold text-theme-text truncate">{{ explode(' ', $systemInfo['server_software'])[0] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">SSO Auth Status</p>
                <p class="text-xs font-semibold text-success">Synced (Arzaq)</p>
            </div>
            <div class="p-3 rounded-lg bg-secondary text-center">
                <p class="text-[10px] text-theme-text-muted uppercase tracking-wider mb-1">Multi-Tenancy</p>
                <p class="text-xs font-semibold text-theme-text">Active (Subdomain)</p>
            </div>
        </div>
    </div>

</div>

<!-- CHART.JS SCRIPTS INITIALIZATION -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Platform Growth Chart
        const growthCtx = document.getElementById('growthChart')?.getContext('2d');
        if (growthCtx) {
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [
                        {
                            label: 'Institutes',
                            data: {!! json_encode($tenantChartData) !!},
                            borderColor: '#0b57d0',
                            backgroundColor: 'rgba(11, 87, 208, 0.08)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Users',
                            data: {!! json_encode($userChartData) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1f20',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#a1a1aa' }
                        },
                        y: {
                            grid: { color: 'rgba(161, 161, 170, 0.1)' },
                            ticks: { font: { size: 11 }, color: '#a1a1aa', stepSize: 1 },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // 2. Plan Distribution Doughnut Chart
        const planCtx = document.getElementById('planDistributionChart')?.getContext('2d');
        if (planCtx) {
            new Chart(planCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($planLabels) !!},
                    datasets: [{
                        data: {!! json_encode($planCounts) !!},
                        backgroundColor: {!! json_encode($planColors) !!},
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1f20',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 10,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        }
    });
</script>
@endsection