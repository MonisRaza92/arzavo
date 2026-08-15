@extends('layouts.arzavo')
@section('title', 'Arzavo Super Admin Dashboard')

@section('content')
<div class="dashboard-wrapper flex flex-col gap-6">

    {{-- ======================================================== --}}
    {{-- 1. HEADER & GREETING BAR                                  --}}
    {{-- ======================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-primary border-primary border-rounded p-5">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-500/10 text-green-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Platform Operational
                </span>
                <span class="text-xs text-tertiary">
                    • {{ now()->format('l, d M Y') }}
                </span>
            </div>
            <h1 class="text-2xl font-bold text-primary mt-1">
                Welcome back, {{ $user->fname ?? 'Super Admin' }}! 👋
            </h1>
            <p class="text-xs text-tertiary mt-0.5">
                Here is a summary of your multi-tenant ecosystem, subscriptions, and platform performance.
            </p>
        </div>

        {{-- Quick Actions --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('arzavo.admin.tenants.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium border-rounded bg-primary text-secondary border border-primary hover:bg-hover-secondary transition">
                <i class="fa-solid fa-building-columns text-tertiary"></i>
                <span>Tenants List</span>
            </a>
            <a href="{{ route('arzavo.admin.plans.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium border-rounded bg-primary text-secondary border border-primary hover:bg-hover-secondary transition">
                <i class="fa-solid fa-credit-card text-tertiary"></i>
                <span>Plans</span>
            </a>
            <a href="{{ route('arzavo.admin.users.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium border-rounded bg-invert text-invert hover:opacity-90 transition">
                <i class="fa-solid fa-user-plus"></i>
                <span>Manage Users</span>
            </a>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- 2. TOP KPI CARDS GRID (4 CARDS)                          --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- KPI 1: Total Platform Users --}}
        <div class="stat-card bg-primary border-primary border-rounded p-4 flex flex-col justify-between hover:bg-hover-secondary transition group">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-tertiary uppercase tracking-wider">Total Users</span>
                    <div class="w-9 h-9 border-rounded bg-invert text-invert flex items-center justify-center text-sm">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-primary counter" data-target="{{ $totalUsers }}">{{ $totalUsers }}</h2>
                    <span class="inline-flex items-center text-[11px] font-semibold {{ $userGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fa-solid {{ $userGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                        {{ abs($userGrowth) }}%
                    </span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-tertiary mt-2">
                    <span>Active: <strong class="text-secondary">{{ $activeUsers }}</strong></span>
                    <span>Suspended: <strong class="text-secondary">{{ $suspendedUsers }}</strong></span>
                </div>
            </div>
            <a href="{{ route('arzavo.admin.users.index') }}" class="mt-4 pt-3 border-top flex items-center justify-between text-xs text-tertiary group-hover:text-primary transition">
                <span>View all users</span>
                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        {{-- KPI 2: Total Tenants / Institutes --}}
        <div class="stat-card bg-primary border-primary border-rounded p-4 flex flex-col justify-between hover:bg-hover-secondary transition group">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-tertiary uppercase tracking-wider">Total Tenants</span>
                    <div class="w-9 h-9 border-rounded bg-invert text-invert flex items-center justify-center text-sm">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-primary counter" data-target="{{ $totalTenants }}">{{ $totalTenants }}</h2>
                    <span class="inline-flex items-center text-[11px] font-semibold {{ $tenantGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fa-solid {{ $tenantGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                        {{ abs($tenantGrowth) }}%
                    </span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-tertiary mt-2">
                    <span>Active: <strong class="text-secondary">{{ $activeTenants }}</strong></span>
                    <span>Verified Domains: <strong class="text-secondary">{{ $verifiedDomainsCount }}</strong></span>
                </div>
            </div>
            <a href="{{ route('arzavo.admin.tenants.index') }}" class="mt-4 pt-3 border-top flex items-center justify-between text-xs text-tertiary group-hover:text-primary transition">
                <span>Manage tenants</span>
                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        {{-- KPI 3: Pricing Plans & Subscriptions --}}
        <div class="stat-card bg-primary border-primary border-rounded p-4 flex flex-col justify-between hover:bg-hover-secondary transition group">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-tertiary uppercase tracking-wider">Active Plans</span>
                    <div class="w-9 h-9 border-rounded bg-invert text-invert flex items-center justify-center text-sm">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-primary counter" data-target="{{ $activePlans }}">{{ $activePlans }}</h2>
                    <span class="text-xs text-tertiary">/ {{ $totalPlans }} total</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-tertiary mt-2">
                    <span>Active Subs: <strong class="text-secondary">{{ $activeSubscriptionsCount }}</strong></span>
                    <span>Addons: <strong class="text-secondary">Available</strong></span>
                </div>
            </div>
            <a href="{{ route('arzavo.admin.plans.index') }}" class="mt-4 pt-3 border-top flex items-center justify-between text-xs text-tertiary group-hover:text-primary transition">
                <span>Configure plans</span>
                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition"></i>
            </a>
        </div>

        {{-- KPI 4: Monthly Recurring Revenue (MRR) --}}
        <div class="stat-card bg-primary border-primary border-rounded p-4 flex flex-col justify-between hover:bg-hover-secondary transition group">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-tertiary uppercase tracking-wider">Estimated MRR</span>
                    <div class="w-9 h-9 border-rounded bg-invert text-invert flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-3xl font-extrabold text-primary">₹{{ number_format($estimatedMRR, 0) }}</h2>
                    <span class="inline-flex items-center text-[10px] px-1.5 py-0.5 rounded font-semibold bg-green-500/10 text-green-500">
                        Monthly
                    </span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-tertiary mt-2">
                    <span>Subscription Revenue</span>
                    <span class="text-green-500 font-medium"><i class="fa-solid fa-shield-check mr-1"></i>Secure</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top flex items-center justify-between text-xs text-tertiary">
                <span>Platform Billing</span>
                <span class="text-xs text-secondary font-medium">Auto-renew</span>
            </div>
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- 3. ANALYTICS & CHARTS ROW                                --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Chart 1: Tenant & User Growth (2 Cols) --}}
        <div class="lg:col-span-2 bg-primary border-primary border-rounded p-5 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="text-base font-bold text-primary">Platform Growth Trend</h3>
                    <p class="text-xs text-tertiary">Monthly new tenant onboardings vs registered platform users</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-secondary">
                        <span class="w-3 h-3 rounded-full bg-[#4f46e5]"></span>
                        Tenants
                    </span>
                    <span class="flex items-center gap-1.5 text-secondary">
                        <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                        Users
                    </span>
                </div>
            </div>

            <div class="relative w-full h-[260px]">
                <canvas id="platformGrowthChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Plan Subscriptions & Domain Health (1 Col) --}}
        <div class="bg-primary border-primary border-rounded p-5 flex flex-col justify-between">
            <div class="mb-4 pb-3 border-bottom">
                <h3 class="text-base font-bold text-primary">Plan & Domain Distribution</h3>
                <p class="text-xs text-tertiary">Subscriptions by plan and domain status</p>
            </div>

            <div class="relative w-full h-[180px] flex items-center justify-center">
                <canvas id="planDistributionChart"></canvas>
            </div>

            {{-- Domain Breakdown Bars --}}
            <div class="mt-4 pt-4 border-top flex flex-col gap-2.5">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-tertiary">Custom Domains Verified</span>
                        <span class="font-semibold text-secondary">{{ $verifiedDomainsCount }} / {{ max($totalTenants, 1) }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-secondary border-rounded overflow-hidden">
                        <div class="h-full bg-accent border-rounded" 
                             style="width: {{ $totalTenants > 0 ? ($verifiedDomainsCount / $totalTenants) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-tertiary">Active Tenant Ratio</span>
                        <span class="font-semibold text-secondary">{{ $activeTenants }} / {{ max($totalTenants, 1) }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-secondary border-rounded overflow-hidden">
                        <div class="h-full bg-green-500 border-rounded" 
                             style="width: {{ $totalTenants > 0 ? ($activeTenants / $totalTenants) * 100 : 0 }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- 4. RECENT ACTIVITY (SPLIT TABLES)                         --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Recent Tenants (2 Cols) --}}
        <div class="lg:col-span-2 bg-primary border-primary border-rounded p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="text-base font-bold text-primary">Recent Tenants</h3>
                    <p class="text-xs text-tertiary">Latest schools and coaching institutes created on Arzavo</p>
                </div>
                <a href="{{ route('arzavo.admin.tenants.index') }}" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1">
                    View all <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-tertiary border-bottom font-medium">
                            <th class="pb-2.5">Tenant</th>
                            <th class="pb-2.5">Domain / URL</th>
                            <th class="pb-2.5">Owner</th>
                            <th class="pb-2.5">Status</th>
                            <th class="pb-2.5 text-right">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @forelse($recentTenants as $tenant)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 pr-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-invert text-invert flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div class="truncate max-w-[150px]">
                                            <span class="font-semibold text-primary block truncate">{{ $tenant->name }}</span>
                                            <span class="text-[10px] text-tertiary block truncate">ID: #{{ $tenant->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 pr-2 text-tertiary">
                                    @if($tenant->url)
                                        <a href="{{ $tenant->url }}" target="_blank" class="text-accent hover:underline inline-flex items-center gap-1">
                                            <span class="truncate max-w-[130px]">{{ str_replace(['http://', 'https://'], '', $tenant->url) }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                        </a>
                                    @else
                                        <span class="text-tertiary">—</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-2">
                                    <span class="text-secondary font-medium block truncate max-w-[120px]">{{ $tenant->admin->full_name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 pr-2">
                                    @if($tenant->status === 'active')
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-medium bg-green-500/10 text-green-600">Active</span>
                                    @else
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-medium bg-red-500/10 text-red-600">Suspended</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right text-tertiary whitespace-nowrap">
                                    {{ $tenant->created_at ? $tenant->created_at->diffForHumans() : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-tertiary">
                                    <i class="fa-solid fa-building-columns text-2xl mb-2 block"></i>
                                    No tenants created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Recent Platform Users (1 Col) --}}
        <div class="bg-primary border-primary border-rounded p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-bottom">
                    <div>
                        <h3 class="text-base font-bold text-primary">Recent Users</h3>
                        <p class="text-xs text-tertiary">Latest platform account signups</p>
                    </div>
                    <a href="{{ route('arzavo.admin.users.index') }}" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1">
                        View all <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="flex flex-col divide-y divide-primary">
                    @forelse($recentUsers as $usr)
                        <div class="py-2.5 flex items-center justify-between gap-3 hover:bg-hover-secondary transition px-1 rounded">
                            <div class="flex items-center gap-2.5 truncate">
                                <x-profile-image :user="$usr" />
                                <div class="truncate">
                                    <h4 class="text-xs font-semibold text-primary truncate">{{ $usr->full_name }}</h4>
                                    <p class="text-[10px] text-tertiary truncate">{{ $usr->email }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] px-1.5 py-0.5 rounded font-medium {{ $usr->role === 'admin' ? 'bg-purple-500/10 text-purple-600' : 'bg-secondary text-tertiary' }}">
                                    {{ ucfirst($usr->role) }}
                                </span>
                                <span class="text-[9px] text-tertiary block mt-0.5">{{ $usr->tenants->count() }} tenants</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-tertiary py-4 text-center">No recent users.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-center">
                <a href="{{ route('arzavo.admin.users.index') }}" class="text-xs text-tertiary hover:text-primary transition font-medium">
                    Manage all platform accounts &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- 5. PLANS OVERVIEW & SYSTEM DIAGNOSTICS                   --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Active Subscription Plans (2 Cols) --}}
        <div class="lg:col-span-2 bg-primary border-primary border-rounded p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="text-base font-bold text-primary">Pricing Plans</h3>
                    <p class="text-xs text-tertiary">Active subscription tiers and adoption</p>
                </div>
                <a href="{{ route('arzavo.admin.plans.index') }}" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1">
                    Manage Plans <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @forelse($plans as $plan)
                    <div class="border border-primary border-rounded p-3.5 bg-primary hover:bg-hover-secondary transition flex flex-col justify-between relative">
                        @if($plan->is_popular)
                            <span class="absolute top-2 right-2 text-[9px] font-bold px-1.5 py-0.5 rounded bg-accent text-invert uppercase">
                                Popular
                            </span>
                        @endif
                        <div>
                            <h4 class="text-sm font-bold text-primary">{{ $plan->name }}</h4>
                            <p class="text-xs text-tertiary mt-0.5">{{ $plan->short_description ?? 'Standard Tier' }}</p>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-xl font-extrabold text-primary">₹{{ number_format($plan->monthly_price, 0) }}</span>
                                <span class="text-[10px] text-tertiary">/month</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-2 border-top flex items-center justify-between text-[11px] text-tertiary">
                            <span>Subscribers: <strong class="text-secondary">{{ $plan->subscriptions_count }}</strong></span>
                            <span class="{{ $plan->is_active ? 'text-green-500' : 'text-red-500' }} font-medium">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-tertiary col-span-3 text-center py-4">No plans configured yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Right: System Diagnostics & Health (1 Col) --}}
        <div class="bg-primary border-primary border-rounded p-5 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-bottom">
                    <h3 class="text-base font-bold text-primary">System Diagnostics</h3>
                    <p class="text-xs text-tertiary">Server runtime and application environment</p>
                </div>

                <div class="flex flex-col gap-2.5 text-xs">
                    <div class="flex items-center justify-between py-1.5 border-bottom">
                        <span class="text-tertiary flex items-center gap-1.5">
                            <i class="fa-brands fa-php text-sm"></i> PHP Version
                        </span>
                        <span class="font-mono font-medium text-secondary">{{ $systemInfo['php_version'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-bottom">
                        <span class="text-tertiary flex items-center gap-1.5">
                            <i class="fa-brands fa-laravel text-sm text-red-500"></i> Laravel Version
                        </span>
                        <span class="font-mono font-medium text-secondary">v{{ $systemInfo['laravel_version'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-bottom">
                        <span class="text-tertiary flex items-center gap-1.5">
                            <i class="fa-solid fa-database text-sm"></i> Database
                        </span>
                        <span class="font-medium text-green-500 inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Connected
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-bottom">
                        <span class="text-tertiary flex items-center gap-1.5">
                            <i class="fa-solid fa-server text-sm"></i> Environment
                        </span>
                        <span class="uppercase text-[10px] px-2 py-0.5 rounded font-semibold bg-secondary text-secondary">
                            {{ $systemInfo['app_env'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-tertiary flex items-center gap-1.5">
                            <i class="fa-solid fa-bug text-sm"></i> Debug Mode
                        </span>
                        <span class="text-[10px] font-medium text-secondary">
                            {{ $systemInfo['debug_mode'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-center text-[10px] text-tertiary">
                Arzavo Platform Engine &copy; {{ date('Y') }}
            </div>
        </div>

    </div>

</div>

{{-- ======================================================== --}}
{{-- 6. CHART.JS SCRIPT INITIALIZATION                        --}}
{{-- ======================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Platform Growth Chart
    const growthCtx = document.getElementById('platformGrowthChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Tenants',
                        data: {!! json_encode($tenantChartData) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    },
                    {
                        label: 'Users',
                        data: {!! json_encode($userChartData) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(150, 150, 150, 0.1)' },
                        ticks: { precision: 0, font: { size: 11 } }
                    }
                }
            }
        });
    }

    // 2. Plan Distribution Doughnut Chart
    const planCtx = document.getElementById('planDistributionChart');
    if (planCtx) {
        new Chart(planCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($planLabels) !!},
                datasets: [{
                    data: {!! json_encode($planCounts) !!},
                    backgroundColor: {!! json_encode($planColors) !!},
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: 12,
                            font: { size: 10 }
                        }
                    },
                    tooltip: {
                        padding: 8,
                        cornerRadius: 6
                    }
                }
            }
        });
    }
});
</script>
@endsection
