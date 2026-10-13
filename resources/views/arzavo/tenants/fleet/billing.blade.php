@extends('layouts.arzavo.tenants')
@section('title', 'Plans & Invoices Fleet • ' . config('app.name'))

@section('content')
@php
    $totalInstitutes = $tenants->count();
    $totalInvoicesCount = $invoices->count();
    $pendingInvoicesCount = $invoices->where('status', 'pending')->count();
    $paidInvoicesCount = $invoices->where('status', 'paid')->count();
@endphp

<div class="space-y-6" x-data="{
    statusFilter: 'all'
}">

    <!-- 1. HEADER & FLEET BILLING METRICS (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-theme-text text-base shrink-0">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5 flex-wrap">
                        <span>Plans & Invoices Hub</span>
                        <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted shrink-0">
                            Unified Billing
                        </span>
                    </h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        Central billing ledger, active subscription tiers, and payment receipts across all your multi-tenant campuses.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('tenants.index') }}"
                   class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>All Institutes</span>
                </a>
                <a href="{{ route('pricing') }}"
                   class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-bolt text-[11px]"></i>
                    <span>Compare Plans</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards (bg-secondary, rounded-lg, flat) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Active Subscriptions</span>
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $totalInstitutes }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Campuses under management</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Pending Balance</span>
                    <i class="fa-solid fa-clock-rotate-left {{ $pendingAmount > 0 ? 'text-warning' : 'text-success' }}"></i>
                </div>
                <div class="text-xl font-semibold {{ $pendingAmount > 0 ? 'text-warning' : 'text-theme-text' }}">
                    ₹{{ number_format($pendingAmount, 2) }}
                </div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">{{ $pendingInvoicesCount }} unsettled invoices</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Total Settled</span>
                    <i class="fa-solid fa-receipt text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">₹{{ number_format($paidAmount, 2) }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">{{ $paidInvoicesCount }} paid receipts</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Fleet Status</span>
                    <i class="fa-solid fa-circle-check text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">Good Standing</div>
                <p class="text-[10px] text-success font-normal">All databases operational</p>
            </div>
        </div>
    </div>

    <!-- 2. SUBSCRIPTIONS PER INSTITUTE (List of clean cards) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-theme-text-muted"></i>
                    <span>Campus Subscription Tiers</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Plan allocations, renewal schedules, and seat quotas per institute.
                </p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal shrink-0">
                {{ $totalInstitutes }} {{ Str::plural('Subscription', $totalInstitutes) }}
            </span>
        </div>

        @forelse($tenants as $tenant)
            @php
                $sub = $tenant->subscription;
                $plan = $sub ? $sub->plan : null;
                $planName = $plan ? $plan->name : 'Standard Starter';
                $isTrial = $tenant->isTrialActive();
                $daysLeft = $tenant->trialDaysLeft();

                if ($tenant->isPendingDeletion()) {
                    $statusBadge = 'bg-warning-bg text-warning';
                    $statusLabel = 'Pending Deletion';
                } elseif ($isTrial) {
                    $statusBadge = 'bg-info-bg text-info';
                    $statusLabel = "Trial ({$daysLeft}d left)";
                } elseif ($tenant->status === 'active') {
                    $statusBadge = 'bg-success-bg text-success';
                    $statusLabel = 'Active';
                } else {
                    $statusBadge = 'bg-danger-bg text-danger';
                    $statusLabel = ucfirst($tenant->status);
                }
            @endphp
            <div x-show="search.trim() === '' || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain) }}'.includes(search.toLowerCase().trim())"
                 class="bg-primary rounded-lg p-5 transition space-y-4">

                <!-- Top Header Row -->
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-semibold text-theme-text truncate leading-snug">{{ $tenant->name }}</h3>
                                <span class="text-[11px] px-2.5 py-0.5 rounded-full font-normal inline-flex items-center gap-1.5 shrink-0 {{ $statusBadge }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-theme-text-muted mt-0.5 flex-wrap font-normal">
                                <span class="font-mono">{{ $tenant->subdomain }}</span>
                                <span class="opacity-30">·</span>
                                <span>Tier: <span class="font-medium text-theme-text">{{ $planName }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('tenants.show', $tenant->subdomain) }}#billing"
                           class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-sliders text-xs text-theme-text-muted"></i>
                            <span>Manage Plan</span>
                        </a>
                        <a href="{{ route('tenants.show', $tenant->subdomain) }}"
                           class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                            <span>Campus Details</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Middle 4-Column Stat Boxes Grid (bg-secondary, rounded-lg) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-cubes text-[9px]"></i> SUBSCRIPTION TIER
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $planName }}</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Multi-feature package</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-arrows-rotate text-[9px]"></i> BILLING CYCLE
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $sub ? ucfirst($sub->billing_cycle ?? 'Monthly') : 'Trial' }}</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Auto-renews periodically</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-regular fa-calendar-check text-[9px]"></i> RENEWAL DATE
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">
                            {{ $sub && $sub->current_period_end ? \Carbon\Carbon::parse($sub->current_period_end)->format('d M Y') : 'Active' }}
                        </span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Valid subscription period</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-users text-[9px]"></i> SEAT QUOTA
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">
                            {{ $plan ? ($plan->max_students ?: 'Unlimited') : '150' }} Learners
                        </span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Student capacity</span>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-primary rounded-lg p-8 text-center text-xs text-theme-text-muted font-normal">
                No institutes found in your fleet.
            </div>
        @endforelse
    </div>

    <!-- 3. INVOICES & SETTLEMENTS LEDGER (Cards per invoice) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-theme-text-muted"></i>
                    <span>Invoices & Billing History</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Official tax invoices, receipts, and pending settlement balances.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="statusFilter = 'all'"
                        :class="statusFilter === 'all' ? 'bg-brand text-primary font-normal' : 'bg-primary text-theme-text-muted hover:text-theme-text font-normal'"
                        class="px-3.5 py-1.5 rounded-full text-xs transition">
                    All ({{ $totalInvoicesCount }})
                </button>
                <button @click="statusFilter = 'pending'"
                        :class="statusFilter === 'pending' ? 'bg-brand text-primary font-normal' : 'bg-primary text-theme-text-muted hover:text-theme-text font-normal'"
                        class="px-3.5 py-1.5 rounded-full text-xs transition">
                    Pending ({{ $pendingInvoicesCount }})
                </button>
                <button @click="statusFilter = 'paid'"
                        :class="statusFilter === 'paid' ? 'bg-brand text-primary font-normal' : 'bg-primary text-theme-text-muted hover:text-theme-text font-normal'"
                        class="px-3.5 py-1.5 rounded-full text-xs transition">
                    Paid ({{ $paidInvoicesCount }})
                </button>
            </div>
        </div>

        @forelse($invoices as $invoice)
            @php
                $invTenant = $invoice->tenant;
                $invNumber = $invoice->invoice_number ?? ('INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT));
                $invStatus = strtolower($invoice->status ?? 'pending');
            @endphp
            <div x-show="
                (statusFilter === 'all' || statusFilter === '{{ $invStatus }}') &&
                (search.trim() === '' || '{{ strtolower($invNumber . ' ' . ($invTenant ? $invTenant->name : '')) }}'.includes(search.toLowerCase().trim()))
            " class="bg-primary rounded-lg p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 transition">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-xs shrink-0 text-theme-text">
                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono font-semibold text-sm text-theme-text">{{ $invNumber }}</span>
                            @if($invStatus === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                    Settled
                                </span>
                            @elseif($invStatus === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning">
                                    Pending Payment
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-danger-bg text-danger">
                                    {{ ucfirst($invStatus) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-theme-text-muted font-normal mt-1 flex items-center gap-2 flex-wrap">
                            <span>Campus: <strong class="text-theme-text font-medium">{{ $invTenant ? $invTenant->name : 'Central Fleet' }}</strong></span>
                            <span class="opacity-30">·</span>
                            <span>Issued: {{ $invoice->created_at ? $invoice->created_at->format('d M Y') : 'N/A' }}</span>
                            <span class="opacity-30">·</span>
                            <span>Due: {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'Immediate' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 shrink-0 self-start md:self-auto">
                    <div class="text-left md:text-right">
                        <div class="text-base font-semibold text-theme-text">₹{{ number_format($invoice->total_amount, 2) }}</div>
                        <div class="text-[10px] text-theme-text-muted font-normal">Inclusive of cloud taxes</div>
                    </div>

                    @if($invStatus === 'pending')
                        <a href="{{ route('checkout', $invoice->id) }}"
                           class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                            <span>Pay Now</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 bg-secondary text-theme-text-muted rounded-full font-normal text-xs inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-success text-[10px]"></i>
                            <span>Paid</span>
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-primary rounded-lg p-8 text-center text-xs text-theme-text-muted font-normal">
                No invoices have been generated yet for your fleet.
            </div>
        @endforelse
    </div>

    <!-- 4. ARCHITECTURAL SCOPE SEPARATION -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-theme-text text-sm shrink-0">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Arzavo Fleet Billing vs. Campus Student Fees</h3>
                <p class="text-xs text-theme-text-muted font-normal mt-1 leading-relaxed">
                    The invoices on this page cover <span class="font-medium text-theme-text">Arzavo Platform Subscriptions</span> (cloud servers, dedicated databases, automated backups, and storage quotas).
                    Course fees, student installment collections, fee receipts, and offline payment registers are managed entirely inside each institute's private <span class="font-medium text-theme-text">Finance & Fee Module</span> (<code class="bg-secondary px-1.5 py-0.5 rounded font-mono text-[11px]">yourdomain.com/admin/fees</code>).
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
