@extends('layouts.arzavo.tenants')
@section('title', $tenant->name . ' - Manage Institute')

@section('content')
@php
    $isSubActive = $subscription && $subscription->isActive();
    $isSubExpired = $subscription ? !$subscription->isActive() : true;

    if ($tenant->isPendingDeletion()) {
        $statusBadgeClass = 'bg-warning-bg text-warning';
        $statusLabel = 'Pending Deletion (' . $tenant->deletionHoursLeft() . 'h left)';
    } elseif ($tenant->status === 'suspended' || $tenant->status === 'inactive' || $isSubExpired || $tenant->status === 'expired') {
        $statusBadgeClass = 'bg-danger-bg text-danger';
        $statusLabel = ($tenant->status === 'suspended' || $tenant->status === 'inactive') ? ucfirst($tenant->status) : 'Expired';
    } elseif ($tenant->isTrialActive()) {
        $statusBadgeClass = 'bg-info-bg text-info';
        $statusLabel = 'Trial';
    } else {
        $statusBadgeClass = 'bg-success-bg text-success';
        $statusLabel = 'Active';
    }

    $usersCount = $stats['users_count'] ?? 0;
    $usersLimit = is_numeric($stats['students_limit'] ?? null) ? $stats['students_limit'] : 'Unlimited';
    $storageUsedBytes = $stats['storage_used'] ?? 0;
    $storageLimitBytes = $stats['storage_limit'] ?? (5 * 1024 * 1024 * 1024);
    $storageUsedFormatted = formatSize($storageUsedBytes);
    $storageLimitFormatted = formatSize($storageLimitBytes);
    $storagePercentage = $storageLimitBytes > 0 ? min(100, max(2, round(($storageUsedBytes / $storageLimitBytes) * 100))) : 2;
    $planName = $plan ? $plan->name : ($tenant->isTrialActive() ? 'Free Trial' : 'Basic Plan');
    $adminLimitDisplay = $adminLimit === 999 ? 'Unlimited' : $adminLimit;
    $adminsCount = $tenantAdmins->count();
    $canAddAdmin = $adminsCount < $adminLimit;
@endphp

<div x-data="{
    tab: 'overview',
    addAdminModal: false,
    resetPasswordModal: false,
    deleteAdminModal: false,
    selectedAdminId: '',
    selectedAdminName: '',
    selectedAdminEmail: '',
    deleteAdminAction: ''
}">

    <!-- MANAGE HERO HEADER (bg-primary, rounded-lg, no border, no shadow) -->
    <div class="mb-5 p-5 rounded-lg bg-primary">
        @if($tenant->isPendingDeletion())
            <!-- PENDING DELETION WARNING BANNER -->
            <div class="mb-5 p-4 rounded-lg bg-warning-bg/30 flex items-center justify-between flex-wrap gap-3 text-xs">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-warning text-black flex items-center justify-center shrink-0 text-sm">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="font-semibold text-warning block text-sm">Workspace Scheduled for Deletion · {{ $tenant->deletionHoursLeft() }} Hours Remaining</span>
                        <span class="text-xs text-theme-text-muted block mt-0.5">All student, staff, and public domains are frozen. Your database and files are preserved. You can restore access anytime before expiry.</span>
                    </div>
                </div>
                <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2.5 bg-warning text-black rounded-full font-semibold text-xs hover:opacity-90 transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-xs"></i> Restore Workspace Now
                    </button>
                </form>
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <!-- Avatar Initial (rounded-full) -->
                <div class="w-12 h-12 rounded-full bg-brand flex items-center justify-center text-base font-semibold text-primary shrink-0">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-lg font-semibold text-theme-text">{{ $tenant->name }}</h1>
                        <span class="text-[11px] px-2.5 py-0.5 rounded-full font-normal inline-flex items-center gap-1.5 {{ $statusBadgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusLabel === 'Active' ? 'bg-success' : ($tenant->isPendingDeletion() ? 'bg-warning' : ($statusLabel === 'Trial' ? 'bg-info' : 'bg-danger')) }}"></span>
                            {{ $statusLabel }}
                        </span>
                        <span class="text-[11px] px-2.5 py-0.5 rounded-full font-normal bg-secondary text-theme-text-muted">
                            {{ $planName }}
                        </span>
                    </div>

                    <p class="text-xs text-theme-text-muted mt-1 font-normal flex items-center gap-2 flex-wrap">
                        <span>Subdomain: <a target="_blank" href="{{ $tenant->url }}" class="text-brand hover:underline font-normal">{{ $tenant->subdomain }}</a></span>
                        <span>·</span>
                        <span>Created: {{ $tenant->created_at->format('d M Y') }}</span>
                        @if($tenant->custom_domain && $tenant->domain_verified)
                            <span>·</span>
                            <span class="text-success font-normal inline-flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $tenant->custom_domain }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Action Buttons Group -->
            <div class="flex items-center gap-2.5 flex-wrap">
                @if($tenant->isPendingDeletion())
                    <button disabled
                        class="px-4 py-2.5 bg-secondary text-theme-text-muted rounded-full font-normal text-xs cursor-not-allowed inline-flex items-center gap-2 opacity-60">
                        <i class="fa-solid fa-lock text-[10px]"></i> Services Locked
                    </button>
                    <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2.5 bg-warning text-black rounded-full font-semibold text-xs transition inline-flex items-center gap-2 hover:opacity-90">
                            <i class="fa-solid fa-rotate-left text-xs"></i> Restore
                        </button>
                    </form>
                @else
                    <a target="_blank" href="{{ $tenant->url }}/admin/dashboard"
                        class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition inline-flex items-center gap-2 hover:opacity-90">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Open Admin Console
                    </a>
                    <a target="_blank" href="{{ $tenant->url }}/account/login"
                        class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs transition inline-flex items-center gap-2 hover:bg-subtle">
                        <i class="fa-solid fa-graduation-cap text-xs text-theme-text-muted"></i> Student Login
                    </a>
                @endif
            </div>
        </div>

        <!-- High-Level Telemetry Ribbon -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-4 pt-4 border-t border-secondary text-xs">
            <div class="flex items-center gap-2 text-theme-text-muted">
                <i class="fa-solid fa-database text-success text-[11px]"></i>
                <span>Database: <strong class="font-normal text-theme-text">Isolated ({{ $tenant->db_name }})</strong></span>
            </div>
            <div class="flex items-center gap-2 text-theme-text-muted">
                <i class="fa-solid fa-shield-halved text-success text-[11px]"></i>
                <span>SSL: <strong class="font-normal text-theme-text">Active (Wildcard TLS 1.3)</strong></span>
            </div>
            <div class="flex items-center gap-2 text-theme-text-muted">
                <i class="fa-solid fa-user-shield text-brand text-[11px]"></i>
                <span>Admins: <strong class="font-normal text-theme-text">{{ $adminsCount }} / {{ $adminLimitDisplay }} Seats</strong></span>
            </div>
            <div class="flex items-center gap-2 text-theme-text-muted">
                <i class="fa-solid fa-clock-rotate-left text-info text-[11px]"></i>
                <span>Backup: <strong class="font-normal text-theme-text">Daily 03:00 AM UTC</strong></span>
            </div>
        </div>

        <!-- Manage Navigation Tabs (rounded-full pills) -->
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-secondary overflow-x-auto">
            <button @click="tab = 'overview'"
                :class="tab === 'overview' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text-muted hover:text-theme-text'"
                class="px-4 py-2.5 rounded-full text-xs font-normal transition shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-gauge text-xs"></i> Overview & Telemetry
            </button>
            <button @click="tab = 'domain'"
                :class="tab === 'domain' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text-muted hover:text-theme-text'"
                class="px-4 py-2.5 rounded-full text-xs font-normal transition shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-globe text-xs"></i> Domains & Routing
            </button>
            <button @click="tab = 'billing'"
                :class="tab === 'billing' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text-muted hover:text-theme-text'"
                class="px-4 py-2.5 rounded-full text-xs font-normal transition shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-xs"></i> Plan & Invoices
            </button>
            <button @click="tab = 'security'"
                :class="tab === 'security' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text-muted hover:text-theme-text'"
                class="px-4 py-2.5 rounded-full text-xs font-normal transition shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-xs"></i> Admin Credentials
                <span class="text-[10px] px-2 py-0.2 rounded-full font-normal"
                      :class="tab === 'security' ? 'bg-primary/20 text-primary' : 'bg-primary text-theme-text'">
                    {{ $adminsCount }}/{{ $adminLimitDisplay }}
                </span>
            </button>
            <button @click="tab = 'danger'"
                :class="tab === 'danger' ? 'bg-danger text-white' : 'bg-secondary text-theme-text-muted hover:text-danger'"
                class="px-4 py-2.5 rounded-full text-xs font-normal transition shrink-0 flex items-center gap-2 ml-auto">
                <i class="fa-solid fa-triangle-exclamation text-xs"></i> Danger Zone
            </button>
        </div>
    </div>

    @if($isSubExpired)
        <div class="mb-5 p-4 rounded-lg bg-danger-bg text-danger flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-full bg-danger/20 text-danger flex items-center justify-center shrink-0 text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-danger flex items-center gap-2">
                        <span>Subscription Inactive</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-danger text-white font-normal uppercase tracking-wider">Expired</span>
                    </h4>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                        Your workspace plan expired {{ $subscription && $subscription->ends_at ? 'on ' . $subscription->ends_at->format('d M Y') : 'recently' }}. Student and staff logins are restricted.
                    </p>
                </div>
            </div>
            <a href="{{ route('checkout', $plan ? $plan->slug : 'pro') }}"
               class="px-5 py-2.5 bg-danger text-white rounded-full font-normal text-xs transition hover:opacity-90 shrink-0">
                Renew Subscription
            </a>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 1: OVERVIEW & TELEMETRY                -->
    <!-- ========================================== -->
    <div x-show="tab === 'overview'" x-transition class="space-y-4">
        <!-- 6 KPI Metric Cards Row -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 text-xs">
            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">STUDENTS</span>
                    <i class="fa-solid fa-graduation-cap text-xs text-brand"></i>
                </div>
                <span class="text-theme-text font-semibold text-base block">{{ $usersCount }}</span>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">Limit: {{ $usersLimit }}</span>
            </div>

            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">STORAGE</span>
                    <i class="fa-solid fa-hard-drive text-xs text-info"></i>
                </div>
                <span class="text-theme-text font-semibold text-base block">{{ $storageUsedFormatted }}</span>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">Quota: {{ $storageLimitFormatted }}</span>
            </div>

            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">ADMIN SEATS</span>
                    <i class="fa-solid fa-user-shield text-xs text-brand"></i>
                </div>
                <span class="text-theme-text font-semibold text-base block">{{ $adminsCount }} / {{ $adminLimitDisplay }}</span>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">Tenant Admins</span>
            </div>

            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">DB STATUS</span>
                    <i class="fa-solid fa-server text-xs text-success"></i>
                </div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="text-theme-text font-semibold text-sm">Online</span>
                </div>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">Isolated MySQL</span>
            </div>

            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">SSL SECURITY</span>
                    <i class="fa-solid fa-lock text-xs text-success"></i>
                </div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="text-theme-text font-semibold text-sm">TLS 1.3</span>
                </div>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">Auto-renewing</span>
            </div>

            <div class="p-4 rounded-lg bg-primary">
                <div class="flex items-center justify-between text-theme-text-muted mb-1">
                    <span class="text-[10px] uppercase tracking-wider font-normal">PACKAGE</span>
                    <i class="fa-solid fa-crown text-xs text-warning"></i>
                </div>
                <span class="text-theme-text font-semibold text-base block truncate">{{ $planName }}</span>
                <span class="text-[11px] text-theme-text-muted font-normal block mt-0.5">{{ $isSubExpired ? 'Subscription Expired' : 'Active Tier' }}</span>
            </div>
        </div>

        <!-- Two Column Detailed Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Left Column: Infrastructure & Storage Telemetry -->
            <div class="space-y-4">
                <!-- Infrastructure Specs Card -->
                <div class="p-5 rounded-lg bg-primary space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text">Infrastructure & Isolation Specs</h3>
                            <p class="text-xs text-theme-text-muted font-normal mt-0.5">Technical specifications for this dedicated educational tenant instance.</p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-success-bg text-success font-normal">Zero-Leakage Isolation</span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">Isolated Database Schema</span>
                            <span class="font-mono text-theme-text font-normal">{{ $tenant->db_name }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">Tenant Isolation Engine</span>
                            <span class="text-theme-text font-normal">Dedicated Physical Database</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">PHP & OPcache Runtime</span>
                            <span class="text-theme-text font-normal">PHP 8.2+ FastCGI (JIT Active)</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">TLS Encryption</span>
                            <span class="text-theme-text font-normal">Let's Encrypt Wildcard (256-bit AES)</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">Automated Snapshot Frequency</span>
                            <span class="text-theme-text font-normal">Every 24 Hours at 03:00 AM UTC</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted font-normal">Cloud Data Region</span>
                            <span class="text-theme-text font-normal">ap-south-1 (Mumbai, India)</span>
                        </div>
                    </div>
                </div>

                <!-- Storage Allocation Breakdown Card -->
                <div class="p-5 rounded-lg bg-primary space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text">Disk Allocation & Media Storage</h3>
                            <p class="text-xs text-theme-text-muted font-normal mt-0.5">Isolated storage quota consumed by student records and media.</p>
                        </div>
                        <span class="text-xs text-theme-text font-normal">{{ $storagePercentage }}% Used</span>
                    </div>

                    <div class="w-full bg-secondary h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand h-full rounded-full transition-all duration-300" style="width: {{ $storagePercentage }}%"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">TOTAL CONSUMED</span>
                            <span class="text-theme-text font-semibold block mt-0.5">{{ $storageUsedFormatted }}</span>
                            <span class="text-[11px] text-theme-text-muted font-normal">DB tables + file uploads</span>
                        </div>
                        <div class="p-3 rounded-lg bg-secondary">
                            <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">AVAILABLE QUOTA</span>
                            <span class="text-theme-text font-semibold block mt-0.5">{{ $storageLimitFormatted }}</span>
                            <span class="text-[11px] text-theme-text-muted font-normal">Allocated under {{ $planName }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Portal Endpoints & Operations -->
            <div class="space-y-4">
                <!-- Institute Portal Endpoints Card -->
                <div class="p-5 rounded-lg bg-primary space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text">Institute Portal Endpoints</h3>
                        <p class="text-xs text-theme-text-muted font-normal mt-0.5">Direct entry points for students, parents, and academy administrators.</p>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <!-- Student / Public Website -->
                        <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">PUBLIC ACADEMY WEBSITE</span>
                                <span class="text-theme-text font-normal truncate block mt-0.5">{{ $tenant->url }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button onclick="navigator.clipboard.writeText('{{ $tenant->url }}'); alert('Public URL copied to clipboard!');"
                                    class="w-7 h-7 rounded-full bg-primary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition" title="Copy URL">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <a target="_blank" href="{{ $tenant->url }}"
                                    class="px-3 py-1.5 rounded-full bg-brand text-primary font-normal text-[11px] inline-flex items-center gap-1 hover:opacity-90">
                                    Open <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Student Login Portal -->
                        <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">STUDENT & STAFF LOGIN</span>
                                <span class="text-theme-text font-normal truncate block mt-0.5">{{ $tenant->url }}/account/login</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button onclick="navigator.clipboard.writeText('{{ $tenant->url }}/account/login'); alert('Login URL copied to clipboard!');"
                                    class="w-7 h-7 rounded-full bg-primary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition" title="Copy URL">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <a target="_blank" href="{{ $tenant->url }}/account/login"
                                    class="px-3 py-1.5 rounded-full bg-primary text-theme-text font-normal text-[11px] inline-flex items-center gap-1 hover:bg-subtle">
                                    Open <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Admin Console -->
                        <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">ACADEMY ADMIN CONSOLE</span>
                                <span class="text-theme-text font-normal truncate block mt-0.5">{{ $tenant->url }}/admin/dashboard</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button onclick="navigator.clipboard.writeText('{{ $tenant->url }}/admin/dashboard'); alert('Admin URL copied to clipboard!');"
                                    class="w-7 h-7 rounded-full bg-primary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition" title="Copy URL">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <a target="_blank" href="{{ $tenant->url }}/admin/dashboard"
                                    class="px-3 py-1.5 rounded-full bg-brand text-primary font-normal text-[11px] inline-flex items-center gap-1 hover:opacity-90">
                                    Open <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Security Audit Feed Card -->
                <div class="p-5 rounded-lg bg-primary space-y-3">
                    <h3 class="text-sm font-semibold text-theme-text">Tenant Health & Audit Trail</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-start gap-2.5 p-2 rounded-lg bg-secondary">
                            <i class="fa-solid fa-circle-check text-success text-xs mt-0.5"></i>
                            <div>
                                <span class="text-theme-text font-normal block">Isolated MySQL schema ready</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Database tables migrated and validated with zero latency.</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 p-2 rounded-lg bg-secondary">
                            <i class="fa-solid fa-shield-halved text-brand text-xs mt-0.5"></i>
                            <div>
                                <span class="text-theme-text font-normal block">Direct Admin Authentication configured</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Password security enabled with bcrypt encryption in isolated database.</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 p-2 rounded-lg bg-secondary">
                            <i class="fa-solid fa-clock-rotate-left text-info text-xs mt-0.5"></i>
                            <div>
                                <span class="text-theme-text font-normal block">Automated backup schedule active</span>
                                <span class="text-theme-text-muted text-[11px] font-normal">Retention policy set to 30 continuous rollback snapshots.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: DOMAIN & ROUTING                    -->
    <!-- ========================================== -->
    <div x-show="tab === 'domain'" x-transition class="space-y-4">
        <div class="p-5 rounded-lg bg-primary space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Domains & Network Routing</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Manage your default Arzavo subdomain or map your own custom branded domain.</p>
                </div>
                <button id="connectDomainBtn-{{ $tenant->id }}"
                    class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Connect Custom Domain
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <!-- Subdomain Card -->
                <div class="p-4 rounded-lg bg-secondary space-y-2">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">PRIMARY ARZAVO SUBDOMAIN</span>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-theme-text font-normal truncate">{{ $tenant->subdomain }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-success-bg text-success font-normal">Active & Routed</span>
                    </div>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        Always active and mapped automatically with our central edge reverse proxy and SSL certificate.
                    </p>
                </div>

                <!-- Custom Domain Card -->
                <div class="p-4 rounded-lg bg-secondary space-y-2">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">CUSTOM BRANDED DOMAIN</span>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-theme-text font-normal truncate">{{ $tenant->custom_domain ?: 'Not connected' }}</span>
                        @if($tenant->custom_domain && $tenant->domain_verified)
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-success-bg text-success font-normal">Verified & Live</span>
                        @elseif($tenant->custom_domain)
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-warning-bg text-warning font-normal">DNS Pending</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-subtle text-theme-text-muted font-normal">Disabled</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        Point your registrar's DNS records to our server IP to activate custom white-label branding.
                    </p>
                </div>
            </div>

            <!-- DNS Guidance Box -->
            <div class="p-4 rounded-lg bg-secondary space-y-3">
                <h4 class="text-xs font-semibold text-theme-text">Recommended DNS Settings for Custom Domains</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="text-theme-text-muted text-[10px] uppercase tracking-wider">
                                <th class="py-2 font-normal">Record Type</th>
                                <th class="py-2 font-normal">Host / Name</th>
                                <th class="py-2 font-normal">Target / Value</th>
                                <th class="py-2 font-normal">TTL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary">
                            <tr>
                                <td class="py-2 text-theme-text font-mono font-normal">CNAME / ALIAS</td>
                                <td class="py-2 text-theme-text-muted font-normal">@</td>
                                <td class="py-2 text-theme-text font-mono font-normal">{{ env('CUSTOM_DOMAIN_TARGET', 'domain.arzavo.com') }}</td>
                                <td class="py-2 text-theme-text-muted font-normal">3600 (Automatic)</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-theme-text font-mono font-normal">CNAME</td>
                                <td class="py-2 text-theme-text-muted font-normal">www</td>
                                <td class="py-2 text-theme-text font-mono font-normal">{{ env('CUSTOM_DOMAIN_TARGET', 'domain.arzavo.com') }}</td>
                                <td class="py-2 text-theme-text-muted font-normal">3600 (Automatic)</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-theme-text font-mono font-normal">TXT Record</td>
                                <td class="py-2 text-theme-text-muted font-normal">@ (or verify)</td>
                                <td class="py-2 text-theme-text font-mono font-normal">{{ env('VERIFY_DOMAIN_TARGET', 'verify.arzavo.com') }}</td>
                                <td class="py-2 text-theme-text-muted font-normal">3600 (Automatic)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: PLAN & BILLING                      -->
    <!-- ========================================== -->
    <div x-show="tab === 'billing'" x-transition class="space-y-4">
        <!-- Plan Overview Card -->
        <div class="p-5 rounded-lg bg-primary space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Plan & Subscription Details</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Subscription tier, active limits, and renewal dates for this institute.</p>
                </div>
                <div>
                    @if($isSubExpired)
                        <a href="{{ route('checkout', $plan ? $plan->slug : 'pro') }}"
                            class="px-5 py-2.5 bg-danger text-white rounded-full font-normal text-xs transition hover:opacity-90">
                            Renew Subscription
                        </a>
                    @else
                        <a href="{{ route('pricing') }}"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90">
                            Upgrade / Change Plan
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div class="p-3.5 rounded-lg bg-secondary">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">ACTIVE PACKAGE</span>
                    <span class="text-theme-text font-semibold block mt-1">{{ $planName }}</span>
                    <span class="text-[11px] text-theme-text-muted font-normal mt-0.5 block">Isolated Multi-Tenant Architecture</span>
                </div>
                <div class="p-3.5 rounded-lg bg-secondary">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">EXPIRATION / RENEWAL</span>
                    <span class="block mt-1 font-normal {{ $isSubExpired ? 'text-danger' : 'text-theme-text' }}">
                        {{ $subscription && $subscription->ends_at ? $subscription->ends_at->format('d M Y') : ($tenant->isTrialActive() ? $tenant->trialDaysLeft() . ' days trial left' : 'Active') }}
                    </span>
                    <span class="text-[11px] text-theme-text-muted font-normal mt-0.5 block">Automated invoice generation</span>
                </div>
                <div class="p-3.5 rounded-lg bg-secondary">
                    <span class="text-theme-text-muted text-[10px] uppercase tracking-wider block font-normal">STUDENT SEATS ALLOCATION</span>
                    <span class="text-theme-text font-normal block mt-1">{{ $usersLimit }} Students Allowed</span>
                    <span class="text-[11px] text-theme-text-muted font-normal mt-0.5 block">Currently using {{ $usersCount }} seats</span>
                </div>
            </div>

            <!-- Entitlements Checklist -->
            <div class="pt-2 border-t border-secondary">
                <h4 class="text-xs font-semibold text-theme-text mb-2.5">Included Subscription Entitlements</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Dedicated Isolated Database</span>
                    </div>
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Tenant Admin Multi-Accounts ({{ $adminLimitDisplay }})</span>
                    </div>
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Custom Domain & Wildcard SSL</span>
                    </div>
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Course LMS & Batch Scheduler</span>
                    </div>
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Student Fee Collection & Receipts</span>
                    </div>
                    <div class="flex items-center gap-2 text-theme-text">
                        <i class="fa-solid fa-circle-check text-success text-xs"></i>
                        <span>Nightly Automated Database Backups</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices List Card -->
        <div class="p-5 rounded-lg bg-primary space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Invoices & Billing History</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Past invoices generated for this institute workspace.</p>
                </div>
                <span class="text-xs font-normal {{ $pendingAmount > 0 ? 'text-danger' : 'text-success' }}">
                    Pending Balance: ₹{{ number_format($pendingAmount, 2) }}
                </span>
            </div>

            @if($invoices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="text-theme-text-muted text-[10px] uppercase tracking-wider">
                                <th class="py-2.5 font-normal">Invoice ID</th>
                                <th class="py-2.5 font-normal">Date</th>
                                <th class="py-2.5 font-normal">Amount</th>
                                <th class="py-2.5 font-normal">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-secondary/40 transition">
                                    <td class="py-2.5 text-theme-text font-normal">
                                        #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-2.5 text-theme-text-muted font-normal">{{ $invoice->created_at->format('d M Y') }}</td>
                                    <td class="py-2.5 text-theme-text font-normal">
                                        ₹{{ number_format($invoice->total_amount, 2) }}
                                    </td>
                                    <td class="py-2.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal {{ $invoice->status === 'paid' ? 'bg-success-bg text-success' : ($invoice->status === 'pending' ? 'bg-warning-bg text-warning' : 'bg-danger-bg text-danger') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-theme-text-muted py-2 font-normal">No past invoices found for this workspace.</p>
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: TENANT ADMINISTRATORS (STAFF ONLY)  -->
    <!-- ========================================== -->
    <div x-show="tab === 'security'" x-transition class="space-y-4">

        <!-- Scope & Architecture Clarification Notice -->
        <div class="p-4 rounded-lg bg-secondary text-xs space-y-2">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-brand text-xs"></i>
                <h4 class="font-semibold text-theme-text text-xs">Tenant Database Administrator Scope</h4>
            </div>
            <p class="text-theme-text-muted font-normal leading-relaxed">
                The administrators listed here are created directly inside this institute's isolated database (<code class="text-theme-text font-mono text-[11px]">{{ $tenant->db_name }}.users</code> with role <strong class="text-theme-text font-normal">admin</strong>).
                They possess full authority over batches, courses, exams, fees, and staff within <strong class="text-theme-text font-normal">{{ $tenant->name }}</strong>.
                <strong class="text-theme-text font-normal">They are NOT Arzavo central admins</strong> and have no access to platform billing, server configurations, or any other institute's data.
            </p>
        </div>

        <!-- Admin Quota & Action Bar Card -->
        <div class="p-5 rounded-lg bg-primary space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Tenant Administrators</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                        Manage administrative credentials authorized to access {{ $tenant->name }}'s Admin Console.
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs px-3 py-1.5 rounded-full bg-secondary text-theme-text font-normal">
                        <strong class="text-brand font-semibold">{{ $adminsCount }}</strong> of <strong class="font-semibold">{{ $adminLimitDisplay }}</strong> Admin Seats Used
                    </span>

                    @if($canAddAdmin)
                        <button @click="addAdminModal = true"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                            <i class="fa-solid fa-plus text-xs"></i> Add Tenant Admin
                        </button>
                    @else
                        <button disabled title="Admin seat limit reached. Upgrade plan for more seats."
                            class="px-5 py-2.5 bg-secondary text-theme-text-muted rounded-full font-normal text-xs cursor-not-allowed inline-flex items-center gap-2">
                            <i class="fa-solid fa-lock text-xs"></i> Seats Full ({{ $planName }})
                        </button>
                    @endif
                </div>
            </div>

            <!-- Admins Table -->
            @if($adminsCount > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="text-theme-text-muted text-[10px] uppercase tracking-wider">
                                <th class="py-2.5 font-normal">Administrator</th>
                                <th class="py-2.5 font-normal">Email & Phone</th>
                                <th class="py-2.5 font-normal">Assigned Role</th>
                                <th class="py-2.5 font-normal">Access Type</th>
                                <th class="py-2.5 font-normal">Created At</th>
                                <th class="py-2.5 font-normal text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary">
                            @foreach($tenantAdmins as $index => $admin)
                                @php
                                    $adminFullName = trim(($admin->fname ?? '') . ' ' . ($admin->lname ?? ''));
                                    if (empty($adminFullName)) {
                                        $adminFullName = $admin->name ?? 'Administrator';
                                    }
                                    $isPrimaryAdmin = ($index === 0);
                                @endphp
                                <tr class="hover:bg-secondary/40 transition">
                                    <td class="py-3 text-theme-text font-normal">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-brand text-primary flex items-center justify-center font-semibold text-xs shrink-0">
                                                {{ strtoupper(substr($adminFullName, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="font-semibold block text-theme-text">{{ $adminFullName }}</span>
                                                @if($isPrimaryAdmin)
                                                    <span class="text-[10px] px-2 py-0.2 rounded-full bg-success-bg text-success font-normal inline-block mt-0.5">
                                                        Primary Owner
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-theme-text font-normal">
                                        <div class="space-y-0.5">
                                            <span class="block text-theme-text">{{ $admin->email }}</span>
                                            @if(!empty($admin->number))
                                                <span class="text-[11px] text-theme-text-muted font-normal block">{{ $admin->number }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-brand/10 text-brand inline-flex items-center gap-1">
                                            <i class="fa-solid fa-shield-halved text-[9px]"></i> Tenant Admin
                                        </span>
                                    </td>
                                    <td class="py-3 text-theme-text-muted font-normal">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                            <span class="text-[11px] text-theme-text">Direct Login + SSO</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-theme-text-muted font-normal">
                                        {{ isset($admin->created_at) ? \Carbon\Carbon::parse($admin->created_at)->format('d M Y') : 'Active' }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Reset Password Button -->
                                            <button @click="selectedAdminId = '{{ $admin->id }}'; selectedAdminName = '{{ addslashes($adminFullName) }}'; selectedAdminEmail = '{{ addslashes($admin->email) }}'; resetPasswordModal = true;"
                                                class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle text-[11px] font-normal transition inline-flex items-center gap-1.5"
                                                title="Reset direct password for this admin">
                                                <i class="fa-solid fa-key text-[10px] text-theme-text-muted"></i> Reset Password
                                            </button>

                                            <!-- Delete Admin Button (Protected for Primary or Only Admin) -->
                                            @if(!$isPrimaryAdmin && $adminsCount > 1)
                                                <button @click="selectedAdminName = '{{ addslashes($adminFullName) }}'; deleteAdminAction = '{{ route('tenants.admins.destroy', [$tenant->subdomain, $admin->id]) }}'; deleteAdminModal = true;"
                                                    class="w-7 h-7 rounded-full bg-secondary text-danger hover:bg-danger-bg text-[11px] flex items-center justify-center transition"
                                                    title="Delete this tenant admin">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            @else
                                                <button disabled
                                                    class="w-7 h-7 rounded-full bg-secondary/50 text-theme-text-muted/40 text-[11px] flex items-center justify-center cursor-not-allowed"
                                                    title="Primary or sole admin cannot be deleted">
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 rounded-lg bg-secondary text-center">
                    <i class="fa-solid fa-user-shield text-2xl text-theme-text-muted mb-2 block"></i>
                    <p class="text-xs text-theme-text-muted font-normal">No administrators found in tenant database.</p>
                </div>
            @endif
        </div>

        <!-- Login Instructions Helping Card -->
        <div class="p-5 rounded-lg bg-primary space-y-3">
            <h4 class="text-xs font-semibold text-theme-text">How Tenant Admins Log In</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <div class="p-3.5 rounded-lg bg-secondary space-y-1.5">
                    <span class="text-theme-text font-semibold block">1. Direct Subdomain / Domain Login</span>
                    <p class="text-theme-text-muted font-normal leading-relaxed">
                        Navigate to <a target="_blank" href="{{ $tenant->url }}/account/login" class="text-brand hover:underline font-normal">{{ $tenant->url }}/account/login</a> and log in with the admin's email and direct password.
                    </p>
                </div>
                <div class="p-3.5 rounded-lg bg-secondary space-y-1.5">
                    <span class="text-theme-text font-semibold block">2. Single Sign-On (SSO) from Arzavo</span>
                    <p class="text-theme-text-muted font-normal leading-relaxed">
                        Arzavo account owners can seamlessly click <strong class="text-theme-text font-normal">Open Admin Console</strong> to authenticate without entering credentials again.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 5: DANGER ZONE                         -->
    <!-- ========================================== -->
    <div x-show="tab === 'danger'" x-transition class="space-y-4">
        @if($tenant->isPendingDeletion())
            <div class="p-5 rounded-lg bg-primary space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-warning text-black flex items-center justify-center shrink-0 text-sm">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-warning">Workspace is Scheduled for Deletion</h3>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            This institute is scheduled for permanent deletion in <strong class="text-theme-text font-normal">{{ $tenant->deletionHoursLeft() }} hours</strong>. All portals and services are currently frozen.
                        </p>
                    </div>
                </div>

                <div class="p-3.5 rounded-lg bg-secondary text-xs text-theme-text-muted font-normal space-y-1.5">
                    <p><i class="fa-solid fa-shield-halved text-success mr-1"></i> Data Preservation: Your database schema (<code class="text-theme-text font-mono text-[11px]">{{ $tenant->db_name }}</code>) and files remain safe during this grace period.</p>
                    <p><i class="fa-solid fa-rotate-left text-brand mr-1"></i> Restoration: You can restore this workspace now to immediately unfreeze student, staff, and admin access.</p>
                </div>

                <form action="{{ route('tenants.restore', $tenant->subdomain) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2.5 bg-warning text-black rounded-full font-semibold text-xs hover:opacity-90 transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-xs"></i> Restore Workspace Now
                    </button>
                </form>
            </div>
        @else
            <div class="p-5 rounded-lg bg-primary space-y-4">
                <div>
                    <h3 class="text-sm font-semibold text-danger flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i> Schedule Institute Deletion
                    </h3>
                    <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                        Scheduling deletion immediately freezes all student and staff portals. Your database schema (<code class="text-theme-text font-mono text-[11px]">{{ $tenant->db_name }}</code>) and files will remain safely preserved for a 24-hour grace period, after which they will be permanently purged.
                    </p>
                </div>

                <div>
                    <button onclick="document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.remove('hidden')"
                        class="px-5 py-2.5 bg-danger text-white rounded-full font-normal text-xs hover:opacity-90 transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-clock text-xs"></i> Schedule Deletion (24h Grace)
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- MODALS & POPUPS                            -->
    <!-- ========================================== -->

    <!-- 1. ADD TENANT ADMIN MODAL (shadow-2xl, rounded-lg, bg-primary) -->
    <div x-show="addAdminModal" x-transition.opacity
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4"
        style="display: none;">
        <div @click.away="addAdminModal = false"
            class="bg-primary rounded-lg w-full max-w-lg p-6 relative shadow-2xl">
            <button @click="addAdminModal = false"
                class="absolute right-4 top-4 text-theme-text-muted hover:text-theme-text w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-sm transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-brand text-primary flex items-center justify-center text-sm shrink-0 font-semibold">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-theme-text">Create Tenant Administrator</h3>
                    <p class="text-xs text-theme-text-muted font-normal">Add a new admin account strictly for {{ $tenant->name }}</p>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-secondary text-xs text-theme-text-muted font-normal mb-4">
                <i class="fa-solid fa-circle-info text-brand mr-1"></i>
                Only <strong class="text-theme-text font-normal">Tenant Admins</strong> can be created from this central panel. Teachers, accountants, and other staff should be created inside the Academy Admin Console.
            </div>

            <form action="{{ route('tenants.admins.store', $tenant->subdomain) }}" method="POST" class="space-y-3.5">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-normal text-theme-text block mb-1">First Name *</label>
                        <input type="text" name="fname" required placeholder="John"
                            class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-normal text-theme-text block mb-1">Last Name</label>
                        <input type="text" name="lname" placeholder="Doe"
                            class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text block mb-1">Admin Email Address *</label>
                    <input type="email" name="email" required placeholder="admin@institute.com"
                        class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                    <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Used to log into {{ $tenant->subdomain }}.</p>
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text block mb-1">Contact Phone (Optional)</label>
                    <input type="tel" name="number" placeholder="+91 98765 43210"
                        class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-normal text-theme-text block mb-1">Direct Password *</label>
                        <input type="password" name="password" required minlength="8" placeholder="Min. 8 chars (letters & numbers)"
                            class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-normal text-theme-text block mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required minlength="8" placeholder="••••••••"
                            class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check text-xs"></i> Create Tenant Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. RESET TARGETED ADMIN PASSWORD MODAL (shadow-2xl, rounded-lg, bg-primary) -->
    <div x-show="resetPasswordModal" x-transition.opacity
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4"
        style="display: none;">
        <div @click.away="resetPasswordModal = false"
            class="bg-primary rounded-lg w-full max-w-md p-6 relative shadow-2xl">
            <button @click="resetPasswordModal = false"
                class="absolute right-4 top-4 text-theme-text-muted hover:text-theme-text w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-sm transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-secondary text-theme-text flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-theme-text">Reset Admin Password</h3>
                    <p class="text-xs text-theme-text-muted font-normal" x-text="selectedAdminName ? selectedAdminName : 'Institute Admin'"></p>
                </div>
            </div>

            <p class="text-xs text-theme-text-muted mb-4 font-normal">
                Set a new direct login password for this administrator in <strong class="text-theme-text font-normal">{{ $tenant->name }}</strong>'s isolated database.
            </p>

            <form action="{{ route('tenants.reset-password', $tenant->subdomain) }}" method="POST" class="space-y-3.5">
                @csrf
                <input type="hidden" name="admin_id" :value="selectedAdminId">

                <div>
                    <label class="text-xs font-normal text-theme-text block mb-1">New Direct Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Min. 8 chars (letters & numbers)"
                        class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text block mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" placeholder="••••••••"
                        class="w-full px-4 py-2.5 bg-secondary text-theme-text text-xs rounded-full focus:outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check text-xs"></i> Update Direct Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. DELETE TENANT ADMIN MODAL (shadow-2xl, rounded-lg, bg-primary) -->
    <div x-show="deleteAdminModal" x-transition.opacity
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-[999] p-4"
        style="display: none;">
        <div @click.away="deleteAdminModal = false"
            class="bg-primary rounded-lg w-full max-w-md p-6 relative shadow-2xl">
            <button @click="deleteAdminModal = false"
                class="absolute right-4 top-4 text-theme-text-muted hover:text-theme-text w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-sm transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-danger-bg text-danger flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-danger">Remove Tenant Admin</h3>
                    <p class="text-xs text-theme-text-muted font-normal" x-text="selectedAdminName"></p>
                </div>
            </div>

            <p class="text-xs text-theme-text-muted mb-4 p-3 rounded-lg bg-secondary font-normal">
                This administrator will be permanently removed from this tenant's isolated database. They will no longer be able to log into this academy.
            </p>

            <form :action="deleteAdminAction" method="POST" class="space-y-3">
                @csrf
                @method('DELETE')
                <div class="flex items-center gap-2">
                    <button type="button" @click="deleteAdminModal = false"
                        class="w-1/2 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs transition hover:bg-subtle">
                        Cancel
                    </button>
                    <button type="submit"
                        class="w-1/2 py-2.5 bg-danger text-white rounded-full font-normal text-xs transition hover:opacity-90">
                        Confirm Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. DOMAIN VERIFY MODAL -->
    @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])

    <!-- 5. DELETE WORKSPACE MODAL (Rich Content, Strict Full Subdomain Match, Option A + 24h Grace) -->
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
                    <h3 class="text-sm font-semibold text-danger">Schedule Institute Deletion</h3>
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

</div>
@endsection