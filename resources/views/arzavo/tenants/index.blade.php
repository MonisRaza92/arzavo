@extends('layouts.arzavo.tenants')
@section('title', 'Institutes - ' . config('app.name'))

@section('content')
@php
    $totalTenants = $tenants->count();
    $activeCount = 0;
    $expiredCount = 0;
    $trialCount = 0;
    $totalStudents = 0;
    $totalStorageUsed = 0;
    $totalStorageLimit = 0;

    foreach ($tenants as $t) {
        $sub = $t->subscription;
        $isExp = $sub ? !$sub->isActive() : true;
        if ($t->status === 'suspended' || $isExp || $t->status === 'expired') {
            $expiredCount++;
        } elseif ($t->isTrialActive()) {
            $trialCount++;
        } else {
            $activeCount++;
        }

        $s = $tenantStats[$t->id] ?? null;
        if ($s) {
            $totalStudents += (int) ($s['users_count'] ?? 0);
            $totalStorageUsed += (int) ($s['storage_used'] ?? 0);
            $totalStorageLimit += (int) ($s['storage_limit'] ?? 0);
        }
    }

    $searchArray = [];
    foreach ($tenants as $t) {
        $sub = $t->subscription;
        $isExp = $sub ? !$sub->isActive() : true;
        if ($t->status === 'suspended' || $t->status === 'inactive' || $isExp || $t->status === 'expired') {
            $sfk = 'expired';
        } elseif ($t->isTrialActive()) {
            $sfk = 'trial';
        } else {
            $sfk = 'active';
        }
        $searchArray[] = [
            'id' => $t->id,
            'text' => strtolower($t->name . ' ' . $t->subdomain . ' ' . ($t->custom_domain ?? '')),
            'status' => $sfk
        ];
    }
@endphp

<div x-data="{
    tenantList: {{ Js::from($searchArray) }},
    get matchCount() {
        const q = (search || '').toLowerCase().trim();
        return this.tenantList.filter(t => (q === '' || t.text.includes(q))).length;
    }
}">

    <!-- FLEET SUMMARY BANNER (bg-primary, rounded-lg, no border, no shadow) -->
    <div class="mb-5 p-5 rounded-lg bg-primary">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
            <div>
                <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5">
                    <i class="fa-solid fa-graduation-cap text-brand text-base"></i> Institutes Fleet
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 font-normal flex items-center gap-2 flex-wrap">
                    <span>Manage all your coaching centers, schools, colleges, and training academies in one place.</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary text-[11px] text-theme-text-muted">
                        <i class="fa-solid fa-server text-[9px] text-brand"></i> Isolated Multi-Tenant Fleet
                    </span>
                </p>
            </div>

            <div>
                <a href="{{ route('tenants.create') }}"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition inline-flex items-center gap-2 hover:opacity-90">
                    <i class="fa-solid fa-plus text-[10px]"></i> New Institute
                </a>
            </div>
        </div>

        <!-- Fleet Metric Strip (Enhanced with icons & guiding subtitles) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <!-- 1. Total Institutes -->
            <div class="p-3.5 rounded-lg bg-secondary flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">TOTAL INSTITUTES</span>
                    <span class="text-theme-text font-semibold text-lg block mt-0.5">{{ $totalTenants }}</span>
                    <span class="text-[11px] text-theme-text-muted block mt-1 font-normal">Multi-tenant instances</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-brand text-xs shrink-0">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>

            <!-- 2. Fleet Health -->
            <div class="p-3.5 rounded-lg bg-secondary flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">FLEET HEALTH</span>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="text-success text-xs font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> {{ $activeCount }} Active
                        </span>
                        @if($expiredCount > 0)
                            <span class="text-danger text-xs font-normal flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> {{ $expiredCount }} Action Needed
                            </span>
                        @else
                            <span class="text-theme-text-muted text-[11px] opacity-70">100% Operational</span>
                        @endif
                    </div>
                    <span class="text-[11px] text-theme-text-muted block mt-1 font-normal">DNS routing & DB status</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-success text-xs shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>

            <!-- 3. Combined Students -->
            <div class="p-3.5 rounded-lg bg-secondary flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">COMBINED STUDENTS</span>
                    <span class="text-theme-text font-semibold text-lg block mt-0.5">{{ number_format($totalStudents) }}</span>
                    <span class="text-[11px] text-theme-text-muted block mt-1 font-normal">Enrolled across all campuses</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-info text-xs shrink-0">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>

            <!-- 4. Combined Storage -->
            <div class="p-3.5 rounded-lg bg-secondary flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">COMBINED STORAGE</span>
                    <span class="text-theme-text font-semibold text-lg block mt-0.5">{{ formatSize($totalStorageUsed) }}</span>
                    <span class="text-[11px] text-theme-text-muted block mt-1 font-normal">Media, documents & backups</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-warning text-xs shrink-0">
                    <i class="fa-solid fa-hard-drive"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION HEADER & GUIDING BAR (Filter tabs removed per requirement) -->
    <div class="mb-4 flex flex-wrap justify-between items-center gap-3">
        <div class="flex items-center gap-3">
            <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                All Campuses & Institutes
            </h2>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal">
                {{ $totalTenants }} {{ Str::plural('Institute', $totalTenants) }}
            </span>
            <span class="text-[11px] text-theme-text-muted font-normal hidden md:inline">
                · Fully isolated database schemas & personalized routing
            </span>
        </div>

        <!-- Search Status Feedback (Driven by top navbar search) -->
        <div class="text-xs text-theme-text-muted flex items-center gap-2 font-normal">
            <div x-show="search && search.trim().length > 0" class="flex items-center gap-1.5 bg-primary px-3 py-1.5 rounded-full text-[11px]">
                <i class="fa-solid fa-magnifying-glass text-[10px] text-theme-text-muted"></i>
                <span>Showing "<span x-text="search" class="text-theme-text font-semibold"></span>"</span>
                <span class="opacity-40">·</span>
                <span x-text="matchCount + ' match'"></span>
                <button @click="search = ''" class="text-danger hover:underline ml-1 cursor-pointer">Clear</button>
            </div>
            <span x-show="!search || search.trim().length === 0" class="text-[11px] text-theme-text-muted hidden sm:inline">
                Quick Action: Click <strong class="text-theme-text font-normal">Open</strong> for direct Single Sign-On
            </span>
        </div>
    </div>

    <!-- WORKSPACES CARDS LIST -->
    @if($tenants->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($tenants as $tenant)
                @php
                    $subscription = $tenant->subscription;
                    $plan = $subscription ? $subscription->plan : null;

                    // Pending invoice & amount
                    $tenantInvoices = $invoices->where('tenant_id', $tenant->id);
                    $pendingInvoice = $tenantInvoices->where('status', 'pending')->first();
                    $pendingAmount = $tenantInvoices->where('status', 'pending')->sum('total_amount');

                    // Subscription active & expired state
                    $isSubActive = $subscription && $subscription->isActive();
                    $isSubExpired = $subscription ? !$subscription->isActive() : true;

                    // Status Badge Mapping
                    if ($tenant->isPendingDeletion()) {
                        $statusFilterKey = 'expired';
                        $statusBadgeClass = 'bg-warning-bg text-warning';
                        $statusLabel = 'Pending Deletion (' . $tenant->deletionHoursLeft() . 'h left)';
                    } elseif ($tenant->status === 'suspended' || $tenant->status === 'inactive' || $isSubExpired || $tenant->status === 'expired') {
                        $statusFilterKey = 'expired';
                        $statusBadgeClass = 'bg-danger-bg text-danger';
                        $statusLabel = ($tenant->status === 'suspended' || $tenant->status === 'inactive') ? ucfirst($tenant->status) : 'Expired';
                    } elseif ($tenant->isTrialActive()) {
                        $statusFilterKey = 'trial';
                        $statusBadgeClass = 'bg-info-bg text-info';
                        $statusLabel = 'Trial';
                    } else {
                        $statusFilterKey = 'active';
                        $statusBadgeClass = 'bg-success-bg text-success';
                        $statusLabel = 'Active';
                    }

                    // REAL Tenant Stats
                    $stats = $tenantStats[$tenant->id] ?? ['users_count' => 0, 'students_limit' => 150, 'storage_used' => 0, 'storage_limit' => 5 * 1024 * 1024 * 1024];
                    $usersCount = $stats['users_count'];
                    $usersLimit = is_numeric($stats['students_limit']) ? $stats['students_limit'] : 'Unlimited';

                    // Storage calculations
                    $storageUsedBytes = $stats['storage_used'] ?: 0;
                    $storageLimitBytes = $stats['storage_limit'] ?: (5 * 1024 * 1024 * 1024);
                    $storageUsedFormatted = formatSize($storageUsedBytes);
                    $storageLimitFormatted = formatSize($storageLimitBytes);
                    $storagePercentage = $storageLimitBytes > 0 ? min(100, max(2, round(($storageUsedBytes / $storageLimitBytes) * 100))) : 2;

                    $planName = $plan ? $plan->name : ($tenant->isTrialActive() ? 'Free Trial' : 'Basic Plan');

                    // Renewal Date
                    if ($isSubExpired) {
                        $renewalDate = 'Expired (' . ($subscription && $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'Past') . ')';
                    } elseif ($subscription && $subscription->ends_at) {
                        $renewalDate = $subscription->ends_at->format('d M Y');
                    } elseif ($tenant->isTrialActive()) {
                        $renewalDate = 'Trial (' . $tenant->trialDaysLeft() . 'd left)';
                    } else {
                        $renewalDate = 'Lifetime';
                    }

                    $lastLogin = $tenant->admin && $tenant->admin->last_login
                        ? $tenant->admin->last_login->diffForHumans()
                        : $tenant->updated_at->diffForHumans();

                    $createdAtFormatted = $tenant->created_at->format('d M Y');
                    $createdAtDiff = $tenant->created_at->diffForHumans();
                    $searchData = strtolower($tenant->name . ' ' . $tenant->subdomain . ' ' . ($tenant->custom_domain ?? ''));
                @endphp

                <!-- WORKSPACE CARD BLOCK (bg-primary) -->
                <div x-show="search.trim() === '' || '{{ addslashes($searchData) }}'.includes(search.toLowerCase().trim())"
                     x-data="{ menuOpen: false }"
                     class="workspace-card w-full rounded-lg bg-primary overflow-hidden transition">

                    <!-- Top Bar -->
                    <div class="p-5 flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Avatar Initial (rounded-full) -->
                            <div class="w-11 h-11 rounded-full bg-brand flex items-center justify-center text-sm font-semibold text-primary shrink-0">
                                {{ strtoupper(substr($tenant->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-semibold text-theme-text truncate leading-snug">{{ $tenant->name }}</h3>
                                    <span class="text-[11px] px-2.5 py-0.5 rounded-full font-normal inline-flex items-center gap-1.5 shrink-0 {{ $statusBadgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-theme-text-muted mt-0.5 flex-wrap">
                                    @if($tenant->custom_domain && $tenant->domain_verified)
                                        <a target="_blank" href="{{ $tenant->url }}" class="hover:underline flex items-center gap-1 text-success font-normal">
                                            {{ $tenant->custom_domain }} <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        </a>
                                    @else
                                        <a target="_blank" href="{{ $tenant->url }}" class="hover:underline flex items-center gap-1 font-normal text-theme-text-muted hover:text-theme-text">
                                            {{ $tenant->subdomain }} <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    @endif
                                    <span class="opacity-30">·</span>
                                    <span class="text-[11px] text-theme-text-muted">Dedicated SSL</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions (Enhanced py-3 buttons) -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <!-- Manage Button -->
                            <a href="{{ route('tenants.show', $tenant->subdomain) }}"
                                class="text-xs px-5 py-3 bg-secondary text-theme-text rounded-full font-normal transition inline-flex items-center gap-2 hover:bg-subtle">
                                <i class="fa-solid fa-sliders text-xs text-theme-text-muted"></i> Manage
                            </a>

                            @if($tenant->isPendingDeletion())
                                <!-- Restore Button (When Pending Deletion) -->
                                <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs px-5 py-3 bg-warning text-black rounded-full font-semibold transition inline-flex items-center gap-2 hover:opacity-90">
                                        <i class="fa-solid fa-rotate-left text-xs"></i> Restore
                                    </button>
                                </form>
                            @else
                                <!-- Direct Login / Open Workspace Button -->
                                <a target="_blank" href="{{ $tenant->url }}/admin/dashboard"
                                    class="text-xs px-5 py-3 bg-brand text-primary rounded-full font-normal transition inline-flex items-center gap-2 hover:opacity-90">
                                    Open <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            @endif

                            <!-- 3-Dots Menu -->
                            <div class="relative">
                                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                                    class="w-10 h-10 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition hover:bg-subtle">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div x-show="menuOpen" x-transition
                                    class="absolute right-0 top-10 w-48 bg-secondary rounded-lg z-50 py-1.5 text-xs overflow-hidden shadow-lg">
                                    <a href="{{ route('tenants.show', $tenant->subdomain) }}"
                                        class="flex items-center gap-2.5 px-3.5 py-2 text-theme-text hover:bg-subtle font-normal transition">
                                        <i class="fa-solid fa-sliders text-theme-text-muted w-4"></i> Manage
                                    </a>
                                    @if($tenant->isPendingDeletion())
                                        <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-warning hover:bg-subtle font-normal transition">
                                                <i class="fa-solid fa-rotate-left w-4"></i> Restore Workspace
                                            </button>
                                        </form>
                                    @endif
                                    @if($isSubExpired)
                                        <a href="{{ route('checkout', $plan ? $plan->slug : 'pro') }}"
                                            class="flex items-center gap-2.5 px-3.5 py-2 text-danger hover:bg-subtle font-normal transition">
                                            <i class="fa-solid fa-rotate-right w-4"></i> Renew Subscription
                                        </a>
                                    @endif
                                    <button
                                        @click="menuOpen = false; document.getElementById('connectDomainPopup-{{ $tenant->id }}').classList.remove('hidden')"
                                        class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-theme-text hover:bg-subtle font-normal transition">
                                        <i class="fa-solid fa-globe text-theme-text-muted w-4"></i> Connect Domain
                                    </button>
                                    <a href="{{ route('tenants.show', $tenant->subdomain) }}#billing"
                                        class="flex items-center gap-2.5 px-3.5 py-2 text-theme-text hover:bg-subtle font-normal transition">
                                        <i class="fa-solid fa-credit-card text-theme-text-muted w-4"></i> Invoices
                                    </a>
                                    <button
                                        @click="menuOpen = false; document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.remove('hidden')"
                                        class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-theme-text hover:bg-subtle font-normal transition">
                                        <i class="fa-solid fa-key text-theme-text-muted w-4"></i> Reset Password
                                    </button>
                                    @if(!$tenant->isPendingDeletion())
                                        <div class="h-[1px] bg-subtle my-1"></div>
                                        <button
                                            @click="menuOpen = false; document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.remove('hidden')"
                                            class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-danger hover:bg-subtle font-normal transition">
                                            <i class="fa-solid fa-trash-can w-4"></i> Schedule Deletion
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($tenant->isPendingDeletion())
                        <!-- PENDING DELETION WARNING BANNER -->
                        <div class="mx-5 mb-4 p-3.5 rounded-lg bg-warning-bg/30 flex items-center justify-between flex-wrap gap-3 text-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-warning text-black flex items-center justify-center shrink-0 text-xs">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="font-semibold text-warning block">Scheduled for deletion in {{ $tenant->deletionHoursLeft() }} hours · All services frozen</span>
                                    <span class="text-[11px] text-theme-text-muted block mt-0.5 truncate">Database & assets are safe during this grace period. You can restore access anytime before expiry.</span>
                                </div>
                            </div>
                            <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-warning text-black rounded-full font-semibold text-xs hover:opacity-90 transition inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Restore Now
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Metadata Grid with Icons & Guiding Descriptions (Inner stat boxes bg-secondary) -->
                    <div class="p-5 pt-0 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 text-xs">
                        <!-- 1. Last Login -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-regular fa-clock text-[9px]"></i> LAST LOGIN
                            </span>
                            <span class="text-theme-text font-normal block mt-1 truncate">{{ $lastLogin }}</span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Admin console session</span>
                        </div>

                        <!-- 2. Subscription Plan -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-solid fa-cubes text-[9px]"></i> SUBSCRIPTION
                            </span>
                            <span class="text-theme-text font-normal block mt-1 truncate">{{ $planName }}</span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Multi-feature tier</span>
                        </div>

                        <!-- 3. Primary Domain -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-solid fa-link text-[9px]"></i> PRIMARY DOMAIN
                            </span>
                            <a target="_blank" href="{{ $tenant->url }}"
                                class="text-theme-text hover:text-brand block mt-1 truncate font-normal">
                                {{ $tenant->subdomain }}
                            </a>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Native SSL route</span>
                        </div>

                        <!-- 4. Custom Domain -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-solid fa-globe text-[9px]"></i> CUSTOM DOMAIN
                            </span>
                            @if($tenant->custom_domain)
                                @if($tenant->domain_verified)
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="text-success truncate font-normal">{{ $tenant->custom_domain }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-success-bg text-success font-normal shrink-0">
                                            Verified
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Custom DNS active</span>
                                @else
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="text-warning truncate font-normal">{{ $tenant->custom_domain }}</span>
                                        <button id="connectDomainBtn-{{ $tenant->id }}"
                                            class="px-2 py-0.5 rounded-full text-[10px] bg-warning-bg text-warning font-normal shrink-0 hover:opacity-80 transition cursor-pointer">
                                            Unverified
                                        </button>
                                    </div>
                                    <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Point DNS records</span>
                                @endif
                            @else
                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                    <span class="text-theme-text-muted font-normal">Not configured</span>
                                    <button id="connectDomainBtn-{{ $tenant->id }}"
                                        class="px-2 py-0.5 rounded-full text-[10px] bg-subtle text-theme-text font-normal shrink-0 hover:bg-theme-border transition cursor-pointer">
                                        + Connect
                                    </button>
                                </div>
                                <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">White-label web address</span>
                            @endif
                        </div>

                        <!-- 5. Renewal Date -->
                        <div class="p-3 rounded-lg bg-secondary {{ $isSubExpired ? 'bg-danger-bg text-danger' : '' }}">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-regular fa-calendar text-[9px]"></i> RENEWAL
                            </span>
                            <span class="block mt-1 truncate font-normal {{ $isSubExpired ? 'text-danger' : 'text-theme-text' }}">
                                {{ $renewalDate }}
                            </span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">{{ $isSubExpired ? 'Action required' : 'Auto-renew enabled' }}</span>
                        </div>

                        <!-- 6. Enrolled Users -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-solid fa-users text-[9px]"></i> ENROLLED LEARNERS
                            </span>
                            <span class="text-theme-text font-normal block mt-1 truncate">
                                {{ $usersCount }} / {{ $usersLimit }}
                            </span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Enrolled student accounts</span>
                        </div>

                        <!-- 7. Pending Invoices -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-regular fa-file-lines text-[9px]"></i> PENDING INVOICE
                            </span>
                            <span class="block mt-1 truncate font-normal {{ $pendingInvoice ? 'text-danger' : 'text-theme-text' }}">
                                {{ $pendingInvoice ? '#INV-' . str_pad($pendingInvoice->id, 5, '0', STR_PAD_LEFT) : 'Settled' }}
                            </span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">{{ $pendingInvoice ? 'Awaiting payment' : 'No overdue invoices' }}</span>
                        </div>

                        <!-- 8. Outstanding Balance -->
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                                <i class="fa-solid fa-receipt text-[9px]"></i> OUTSTANDING
                            </span>
                            <span class="block mt-1 truncate font-normal {{ $pendingAmount > 0 ? 'text-danger' : 'text-theme-text' }}">
                                ₹{{ number_format($pendingAmount, 2) }}
                            </span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">{{ $pendingAmount > 0 ? 'Due for settlement' : 'Zero outstanding balance' }}</span>
                        </div>
                    </div>

                    <!-- Footer: Storage Bar, Architecture Badges & Date -->
                    <div class="px-5 py-3.5 bg-secondary/60 flex items-center justify-between flex-wrap gap-4 text-xs">
                        <div class="flex items-center gap-3 grow max-w-md">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider shrink-0 font-normal flex items-center gap-1">
                                <i class="fa-solid fa-hard-drive text-[9px]"></i> STORAGE
                            </span>
                            <div class="w-full bg-subtle h-1.5 rounded-full overflow-hidden">
                                <div class="bg-brand h-full rounded-full" style="width: {{ $storagePercentage }}%"></div>
                            </div>
                            <span class="text-theme-text-muted text-[11px] shrink-0 font-normal">
                                {{ $storageUsedFormatted }} / {{ $storageLimitFormatted }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4 text-[11px] text-theme-text-muted font-normal flex-wrap">
                            <span class="flex items-center gap-1.5 text-theme-text-muted">
                                <i class="fa-solid fa-database text-[10px] text-brand"></i>
                                <span>Isolated Database</span>
                            </span>
                            <span class="opacity-30">·</span>
                            <span class="flex items-center gap-1.5 text-theme-text-muted">
                                <i class="fa-solid fa-shield-halved text-[10px] text-success"></i>
                                <span>Nightly Snapshots Active</span>
                            </span>
                            <span class="opacity-30">·</span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar-check text-[10px]"></i>
                                <span>Created {{ $createdAtFormatted }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- DOMAIN VERIFY MODAL -->
                @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])

                <!-- RESET PASSWORD MODAL (bg-primary) -->
                <div id="resetPasswordPopup-{{ $tenant->id }}"
                    class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4">
                    <div class="bg-primary rounded-lg w-full max-w-md p-6 relative shadow-2xl">
                        <button onclick="document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.add('hidden')"
                            class="absolute right-4 top-4 text-theme-text-muted hover:text-theme-text w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-sm transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <h3 class="text-base font-semibold text-theme-text mb-1">Reset Tenant Admin Password</h3>
                        <p class="text-xs text-theme-text-muted mb-4 font-normal">Enter a new password for the workspace database administrator.</p>

                        <form action="{{ route('tenants.reset-password', $tenant->subdomain) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-xs font-normal text-theme-text block mb-1">New Password</label>
                                <input type="password" name="password" required minlength="6"
                                    class="w-full px-4 py-3 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-normal text-theme-text block mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required minlength="6"
                                    class="w-full px-4 py-3 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90">
                                Reset Admin Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- DELETE WORKSPACE MODAL (Rich Content, Strict Full Subdomain Match, Option A + 24h Grace) -->
                <div id="deleteWorkspacePopup-{{ $tenant->id }}"
                    x-data="{ confirmSubdomain: '', expectedSubdomain: '{{ strtolower($tenant->subdomain) }}' }"
                    class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-[999] p-4">
                    <div class="bg-primary rounded-lg w-full max-w-lg p-6 relative shadow-2xl max-h-[92vh] overflow-y-auto">
                        <button onclick="document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.add('hidden')"
                            class="absolute right-4 top-4 text-theme-text-muted hover:text-theme-text w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        
                        <!-- Header -->
                        <div class="flex items-center gap-3.5 mb-4">
                            <div class="w-11 h-11 rounded-full bg-danger-bg text-danger flex items-center justify-center text-base shrink-0">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-danger">Schedule Workspace Deletion</h3>
                                <p class="text-xs text-theme-text-muted font-normal mt-0.5">{{ $tenant->name }} · <code class="text-theme-text font-mono text-[11px]">{{ $tenant->subdomain }}</code></p>
                            </div>
                        </div>

                        <!-- 3-Phase Detailed Explanation Cards -->
                        <div class="p-4 rounded-lg bg-secondary text-xs space-y-3 mb-4">
                            <!-- Phase 1: Immediate Freeze -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-subtle text-theme-text-muted flex items-center justify-center text-xs shrink-0 mt-0.5">
                                    <i class="fa-solid fa-lock text-[11px]"></i>
                                </div>
                                <div class="flex-1 leading-relaxed text-[11px] text-theme-text-muted">
                                    <span class="text-theme-text font-medium block text-xs">1. Immediate Access Freeze</span>
                                    Student & staff portals (<code class="text-theme-text font-mono">/account/login</code>), public pages, and custom domain routing will be locked instantly. Active sessions terminate immediately.
                                </div>
                            </div>

                            <div class="h-[1px] bg-subtle"></div>

                            <!-- Phase 2: 24h Safe Grace Period -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-subtle text-theme-text-muted flex items-center justify-center text-xs shrink-0 mt-0.5">
                                    <i class="fa-solid fa-clock text-[11px]"></i>
                                </div>
                                <div class="flex-1 leading-relaxed text-[11px] text-theme-text-muted">
                                    <span class="text-theme-text font-medium block text-xs">2. 24-Hour Safety Grace Window</span>
                                    Data is <strong class="text-theme-text font-medium">not deleted immediately</strong>. Your dedicated database (<code class="text-theme-text font-mono">{{ $tenant->db_name }}</code>), enrolled students, fee records, and uploaded files remain safely intact for 24 hours.
                                </div>
                            </div>

                            <div class="h-[1px] bg-subtle"></div>

                            <!-- Phase 3: Instant Restoration or Purge -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-subtle text-theme-text-muted flex items-center justify-center text-xs shrink-0 mt-0.5">
                                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                </div>
                                <div class="flex-1 leading-relaxed text-[11px] text-theme-text-muted">
                                    <span class="text-theme-text font-medium block text-xs">3. Reversible Anytime · Auto-Purge at Expiry</span>
                                    You can restore full access with a single click anytime from your dashboard. If not restored within 24 hours, all database tables and media files will be permanently destroyed.
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Form (Strict Full Subdomain Match) -->
                        <form action="{{ route('tenants.destroy', $tenant->subdomain) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('DELETE')
                            <div>
                                <label class="text-xs font-normal text-theme-text block mb-2">
                                    To confirm scheduled freeze, type the exact domain <span class="px-2 py-0.5 rounded-full bg-secondary text-brand font-mono font-medium text-[11px] select-all">{{ $tenant->subdomain }}</span> below:
                                </label>
                                <div class="relative">
                                    <input type="text" name="confirm_subdomain" x-model="confirmSubdomain" required autocomplete="off"
                                        placeholder="Type '{{ $tenant->subdomain }}' to confirm"
                                        class="w-full px-4 py-3 bg-secondary text-theme-text text-xs rounded-full focus:outline-none placeholder:text-theme-text-muted/50 transition pr-24">
                                    <span x-show="confirmSubdomain.trim().toLowerCase() === expectedSubdomain"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[11px] text-success font-medium flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-xs"></i> Matched
                                    </span>
                                </div>
                                <p class="text-[11px] text-theme-text-muted mt-1.5 font-normal">
                                    Full domain must match exactly to enable the schedule deletion button.
                                </p>
                            </div>

                            <div class="flex items-center gap-2.5 pt-1">
                                <button type="button"
                                    onclick="document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.add('hidden')"
                                    class="w-1/3 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs transition hover:bg-subtle text-center">
                                    Cancel
                                </button>
                                <button type="submit"
                                    :disabled="confirmSubdomain.trim().toLowerCase() !== expectedSubdomain"
                                    :class="confirmSubdomain.trim().toLowerCase() === expectedSubdomain ? 'bg-danger text-white hover:opacity-90 cursor-pointer shadow-sm' : 'bg-secondary text-theme-text-muted cursor-not-allowed opacity-50'"
                                    class="w-2/3 py-3 rounded-full font-medium text-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-clock text-xs"></i> Schedule Deletion (24h)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- NO MATCHING INSTITUTES STATE (When search has 0 matches) -->
        <div x-cloak x-show="matchCount === 0" class="p-8 rounded-lg bg-primary text-center my-6 space-y-3">
            <div class="w-10 h-10 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-sm mx-auto">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">No matching institutes found</h3>
                <p class="text-xs text-theme-text-muted mt-1 font-normal">No coaching center, school, or institute matches your search query.</p>
            </div>
            <button @click="search = ''"
                class="px-5 py-3 bg-secondary text-theme-text text-xs rounded-full hover:bg-subtle transition font-normal inline-flex items-center gap-2">
                <i class="fa-solid fa-rotate-left text-[11px]"></i> Clear search
            </button>
        </div>

        <!-- MULTI-CAMPUS GUIDANCE & OPERATIONS RESOURCE BLOCK (Helping content stays below results) -->
        <div class="mt-8 p-5 rounded-lg bg-primary">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-brand flex items-center justify-center text-primary text-[10px]">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text">Multi-Institute Fleet Operations</h3>
                </div>
                <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted font-normal">
                    Architecture & Best Practices
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 text-xs">
                <!-- Guide 1 -->
                <div class="p-4 rounded-lg bg-secondary">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-brand text-xs shrink-0">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <h4 class="font-semibold text-theme-text text-xs">Direct Single Sign-On (SSO)</h4>
                    </div>
                    <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                        Click the <strong class="text-theme-text font-normal">Open</strong> button on any institute card to instantly launch its administrative console without re-authenticating.
                    </p>
                </div>

                <!-- Guide 2 -->
                <div class="p-4 rounded-lg bg-secondary">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-success text-xs shrink-0">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <h4 class="font-semibold text-theme-text text-xs">Custom Domain & SSL Verification</h4>
                    </div>
                    <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                        Point your domain's <code class="text-theme-text font-mono text-[10px] bg-primary px-1.5 py-0.5 rounded-full">A-Record</code> to the Arzavo IP. Once verified, free automated SSL certificates protect student & faculty logins.
                    </p>
                </div>

                <!-- Guide 3 -->
                <div class="p-4 rounded-lg bg-secondary">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-warning text-xs shrink-0">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <h4 class="font-semibold text-theme-text text-xs">Complete Database Isolation</h4>
                    </div>
                    <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                        Each coaching center and academy operates on its own dedicated database schema. Student grades, attendance, fees, and exams remain strictly private.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- EMPTY STATE (bg-primary) -->
        <div class="p-10 rounded-lg bg-primary text-center max-w-md mx-auto my-12 space-y-4">
            <div class="w-12 h-12 rounded-full bg-secondary text-theme-text flex items-center justify-center text-base mx-auto">
                <i class="fa-solid fa-graduation-cap text-brand"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-theme-text">No Institutes Found</h2>
                <p class="text-xs text-theme-text-muted mt-1 max-w-xs mx-auto font-normal">
                    Create your first coaching institute, school, or college environment to start managing students, staff, courses, and billing.
                </p>
            </div>
            <a href="{{ route('tenants.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90">
                <i class="fa-solid fa-plus text-[10px]"></i> Create Institute
            </a>
        </div>
    @endif
</div>
@endsection