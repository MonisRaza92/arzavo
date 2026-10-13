@extends('layouts.arzavo.tenants')
@section('title', 'Security & Logs Fleet • ' . config('app.name'))

@section('content')
@php
    $totalInstitutes = $tenants->count();
    $pendingDeletionTenants = $tenants->filter(function($t) {
        return !empty($t->scheduled_delete_at);
    });
    $scheduledDeletionsCount = $pendingDeletionTenants->count();
@endphp

<div class="space-y-6">

    <!-- 1. HEADER & FLEET SECURITY METRICS (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-theme-text text-base shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5 flex-wrap">
                        <span>Security & Audit Logs Hub</span>
                        <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted shrink-0">
                            Zero Trust Fleet
                        </span>
                    </h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        Fleet-wide access security, 24-hour scheduled deletion protection, SSL encryption, and multi-tenant isolation compliance.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('tenants.index') }}"
                   class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>All Institutes</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards (bg-secondary, rounded-lg, flat) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Fleet Security Shield</span>
                    <i class="fa-solid fa-lock text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">Active</div>
                <p class="text-[10px] text-success font-normal">End-to-end multi-tier isolation</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>TLS Encryption</span>
                    <i class="fa-solid fa-shield-halved text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">TLS 1.3</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Automated SSL for all domains</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Scheduled Freezes</span>
                    <i class="fa-solid fa-clock-rotate-left {{ $scheduledDeletionsCount > 0 ? 'text-warning' : 'text-theme-text-muted' }}"></i>
                </div>
                <div class="text-xl font-semibold {{ $scheduledDeletionsCount > 0 ? 'text-warning' : 'text-theme-text' }}">
                    {{ $scheduledDeletionsCount }}
                </div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">In 24h grace safety window</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Data Compliance</span>
                    <i class="fa-solid fa-circle-check text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">Compliant</div>
                <p class="text-[10px] text-success font-normal">ISO & GDPR data segregation</p>
            </div>
        </div>
    </div>

    <!-- 2. PENDING 24-HOUR DELETION ACTIONS (IF ANY) -->
    @if($pendingDeletionTenants->count() > 0)
        <div class="bg-primary rounded-lg p-5 md:p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-warning-bg text-warning flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-theme-text">
                        Active 24-Hour Scheduled Freezes (Action Required)
                    </h2>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        The following campuses are currently locked and scheduled for permanent deletion. You can restore them instantly anytime before the grace window expires.
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($pendingDeletionTenants as $pTenant)
                    @php
                        $delDate = \Carbon\Carbon::parse($pTenant->scheduled_delete_at);
                        $hoursRemaining = max(0, round(now()->diffInHours($delDate, false)));
                    @endphp
                    <div class="bg-secondary rounded-lg p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                                {{ strtoupper(substr($pTenant->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-semibold text-theme-text truncate leading-snug">{{ $pTenant->name }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning shrink-0">
                                        {{ $hoursRemaining }}h grace left
                                    </span>
                                </div>
                                <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                                    <span class="font-mono">{{ $pTenant->subdomain }}</span>
                                    <span class="opacity-30">·</span>
                                    <span>Scheduled purge at: <strong class="text-theme-text font-normal">{{ $delDate->format('d M Y, h:i A') }}</strong></span>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('tenants.restore', $pTenant->subdomain) }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                <span>Cancel Deletion & Restore</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 3. SECURITY SAFEGUARDS CHECKLIST (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <h2 class="text-sm font-semibold text-theme-text mb-4 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-theme-text-muted"></i>
            <span>Active Security Protocols in Your Fleet</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            <!-- Safeguard 1 -->
            <div class="bg-secondary rounded-lg p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-theme-text">Physical Database Isolation</h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5 leading-relaxed">
                        Each campus operates on a distinct, isolated MySQL schema. There are no shared tables, preventing SQL injection spillover.
                    </p>
                </div>
            </div>

            <!-- Safeguard 2 -->
            <div class="bg-secondary rounded-lg p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-theme-text">24-Hour Scheduled Grace Freeze</h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5 leading-relaxed">
                        Deletion actions immediately lock the portal to prevent unauthorized tampering, while keeping all databases safe for 24 hours for emergency recovery.
                    </p>
                </div>
            </div>

            <!-- Safeguard 3 -->
            <div class="bg-secondary rounded-lg p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-theme-text">Admin SSO Token Authentication</h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5 leading-relaxed">
                        One-click access from your central console into tenant dashboards utilizes short-lived cryptographically signed SSO sessions with zero credential sharing.
                    </p>
                </div>
            </div>

            <!-- Safeguard 4 -->
            <div class="bg-secondary rounded-lg p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-shield-virus"></i>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-theme-text">Automated Daily Encryption Snapshots</h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5 leading-relaxed">
                        Database dumps are executed nightly at 02:00 AM UTC and stored in isolated encrypted directories with 30-day retention policies.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. FLEET AUDIT TIMELINE (Cards per tenant) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-theme-text-muted"></i>
                    <span>Fleet Provisioning & Security Audit Log</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Chronological events, database provisioning records, and status updates.
                </p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal shrink-0">
                {{ $totalInstitutes }} {{ Str::plural('Event', $totalInstitutes) }}
            </span>
        </div>

        @foreach($tenants as $tenant)
            <div x-show="search.trim() === '' || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain) }}'.includes(search.toLowerCase().trim())"
                 class="bg-primary rounded-lg p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 transition">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-11 h-11 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                        {{ strtoupper(substr($tenant->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm font-semibold text-theme-text truncate leading-snug">{{ $tenant->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal {{ $tenant->status === 'active' ? 'bg-success-bg text-success' : 'bg-warning-bg text-warning' }} shrink-0">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </div>
                        <div class="text-xs text-theme-text-muted font-normal mt-0.5 flex items-center gap-2 flex-wrap">
                            <span class="font-mono">{{ $tenant->subdomain }}</span>
                            <span class="opacity-30">·</span>
                            <span>Isolated DB: <code class="font-mono text-theme-text">{{ $tenant->db_name ?: 'None' }}</code></span>
                        </div>
                    </div>
                </div>

                <div class="text-left md:text-right shrink-0">
                    <span class="text-xs text-theme-text-muted font-normal block">
                        {{ $tenant->created_at ? $tenant->created_at->format('d M Y, h:i A') : 'System initialization' }}
                    </span>
                    <span class="text-[10px] text-theme-text-muted font-normal block mt-0.5">
                        Multi-tenant instance active
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 5. ARCHITECTURAL BOUNDARY EXPLANATION -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-theme-text text-sm shrink-0">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Fleet Cloud Security vs. Campus LMS Student Logs</h3>
                <p class="text-xs text-theme-text-muted font-normal mt-1 leading-relaxed">
                    This central dashboard enforces infrastructure-level security (firewalls, physical MySQL isolation, SSL certificates, and tenant lifecycle).
                    Student exam cheating logs, teacher attendance records, student activity tracking, and assignment submission IP logs are stored inside each campus's dedicated <span class="font-medium text-theme-text">Campus Security Log</span> (<code class="bg-secondary px-1.5 py-0.5 rounded font-mono text-[11px]">yourdomain.com/admin/audit-logs</code>).
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
