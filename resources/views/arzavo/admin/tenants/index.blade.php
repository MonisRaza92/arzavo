@extends('layouts.arzavo.admin')
@section('title', 'Tenants Management')

@section('content')
<div x-data="{
    filter: 'all',
    viewMode: 'grid',
    selectedTenant: null,
    assignModalOpen: false,
    deleteModalOpen: false,
    tenantToDelete: null,
    confirmSubdomain: '',
    openAssign(t) {
        this.selectedTenant = t;
        this.assignModalOpen = true;
        this.$nextTick(() => {
            const form = document.getElementById('assignPlanForm');
            if (form) form.action = `/tenants/${t.id}/assign-plan`;
            const planSelect = document.getElementById('assign_plan_id');
            if (planSelect) planSelect.value = t.subscription ? t.subscription.plan_id : '';
            const statusSelect = document.getElementById('assign_status');
            if (statusSelect) statusSelect.value = t.subscription ? t.subscription.status : 'active';
            const priceInput = document.getElementById('assign_custom_price');
            if (priceInput) priceInput.value = t.subscription && t.subscription.custom_price ? t.subscription.custom_price : '';
            const deleteCheck = document.getElementById('assign_delete_on_expiry');
            if (deleteCheck) deleteCheck.checked = Boolean(t.subscription && t.subscription.delete_on_expiry);
            
            const durationSelect = document.getElementById('assign_duration_type');
            const customDateCont = document.getElementById('customDateContainer');
            const customDateInput = document.getElementById('assign_custom_ends_at');
            
            if (t.subscription && t.subscription.ends_at) {
                if (durationSelect) durationSelect.value = 'custom';
                if (customDateInput) customDateInput.value = t.subscription.ends_at.substring(0, 10);
                if (customDateCont) customDateCont.classList.remove('hidden');
            } else {
                if (durationSelect) durationSelect.value = '1_month';
                if (customDateCont) customDateCont.classList.add('hidden');
            }
        });
    },
    openDelete(t) {
        this.tenantToDelete = t;
        this.confirmSubdomain = '';
        this.deleteModalOpen = true;
    }
}" class="space-y-6">

    <!-- 1. HERO HEADER WITH LIVE SEARCH & ACTIONS -->
    <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-normal bg-brand/10 text-brand">
                    Multi-Tenant Fleet
                </span>
                <span class="text-xs text-theme-text-muted">
                    • {{ $tenants->count() }} Total Institutes
                </span>
            </div>
            <h1 class="text-2xl font-semibold text-theme-text leading-tight">
                Tenant & Academy Fleet
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 max-w-2xl leading-relaxed">
                Configure live subdomains, monitor domain verification status, assign customized billing subscriptions, and enforce quota policies.
            </p>

            <!-- Search Feedback Strip -->
            <div x-show="search && search.length > 0" class="mt-2 text-xs text-theme-text-muted">
                Showing matching institutes for <span class="text-theme-text font-medium" x-text="'&ldquo;' + search + '&rdquo;'"></span>
                <button type="button" @click="search = ''" class="ml-2 text-brand hover:underline">Clear search</button>
            </div>
        </div>

        <!-- Controls: View Toggle & Create Institute -->
        <div class="flex items-center gap-3 flex-wrap">
            <div class="bg-secondary p-1 rounded-full flex items-center">
                <button type="button" @click="viewMode = 'grid'"
                        :class="viewMode === 'grid' ? 'bg-primary text-theme-text shadow-sm' : 'text-theme-text-muted hover:text-theme-text'"
                        class="px-3 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-grid-2 text-xs"></i>
                    <span>Cards</span>
                </button>
                <button type="button" @click="viewMode = 'table'"
                        :class="viewMode === 'table' ? 'bg-primary text-theme-text shadow-sm' : 'text-theme-text-muted hover:text-theme-text'"
                        class="px-3 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-list text-xs"></i>
                    <span>Table</span>
                </button>
            </div>

            <a href="{{ route('tenants.create') }}"
               class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Provision Institute</span>
            </a>
        </div>
    </div>

    <!-- 2. FILTER PILLS BAR -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <button type="button" @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            All Institutes ({{ $tenants->count() }})
        </button>
        <button type="button" @click="filter = 'active'"
                :class="filter === 'active' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Active ({{ $tenants->where('status', 'active')->count() }})
        </button>
        <button type="button" @click="filter = 'suspended'"
                :class="filter === 'suspended' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Suspended ({{ $tenants->where('status', 'suspended')->count() }})
        </button>
        <button type="button" @click="filter = 'verified'"
                :class="filter === 'verified' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Verified DNS ({{ $tenants->where('domain_verified', true)->count() }})
        </button>
        <button type="button" @click="filter = 'custom'"
                :class="filter === 'custom' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Custom Domains ({{ $tenants->whereNotNull('custom_domain')->where('custom_domain', '!=', '')->count() }})
        </button>
    </div>

    <!-- 3. CARDS GRID VIEW -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($tenants as $tenant)
            @php
                $sub = $tenant->subscription;
                $plan = $sub?->plan;
                $hasCustomDomain = !empty($tenant->custom_domain);
            @endphp
            <div class="rounded-lg bg-primary p-5 flex flex-col justify-between"
                 x-show="(!search || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain . ' ' . ($tenant->custom_domain ?? '') . ' ' . ($tenant->admin->email ?? '')) }}'.includes(search.toLowerCase())) &&
                         (filter === 'all' || 
                          (filter === 'active' && '{{ $tenant->status }}' === 'active') || 
                          (filter === 'suspended' && '{{ $tenant->status }}' === 'suspended') || 
                          (filter === 'verified' && {{ $tenant->domain_verified ? 'true' : 'false' }}) || 
                          (filter === 'custom' && {{ $hasCustomDomain ? 'true' : 'false' }}))">
                <div>
                    <!-- Top Status & Icon Row -->
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Tenant Badge (Rule 4.3: bg-brand text-primary font-semibold rounded-full) -->
                            <div class="w-10 h-10 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center shrink-0 uppercase">
                                {{ substr($tenant->name, 0, 2) }}
                            </div>
                            <div class="min-w-0 truncate">
                                <h3 class="text-sm font-semibold text-theme-text truncate leading-tight">{{ $tenant->name }}</h3>
                                <p class="text-xs text-theme-text-muted truncate mt-0.5">
                                    {{ $tenant->subdomain }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Pill -->
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal shrink-0 {{ $tenant->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </div>

                    <!-- Meta & Subdomain Strip -->
                    <div class="space-y-2 mb-4">
                        <!-- Custom Domain / Live URL -->
                        <div class="p-3 rounded-lg bg-secondary text-xs flex items-center justify-between gap-2">
                            <span class="text-theme-text-muted truncate flex items-center gap-2">
                                <i class="fa-solid fa-globe text-theme-text-muted"></i>
                                <span class="truncate">{{ $tenant->custom_domain ?: ($tenant->subdomain . '.' . config('app.domain')) }}</span>
                            </span>
                            @if($tenant->domain_verified)
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-info-bg text-info shrink-0">Verified</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-warning-bg text-warning shrink-0">Unverified</span>
                            @endif
                        </div>

                        <!-- Admin Details -->
                        <div class="p-3 rounded-lg bg-secondary text-xs space-y-1">
                            <div class="flex items-center justify-between text-theme-text-muted">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-user-shield"></i>
                                    <span class="text-theme-text truncate font-normal">{{ $tenant->admin->full_name ?? ($tenant->admin->name ?? 'Unassigned') }}</span>
                                </span>
                                <span>{{ $tenant->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-[11px] text-theme-text-muted truncate pl-5">
                                {{ $tenant->admin->email ?? 'No admin email' }}
                            </p>
                        </div>

                        <!-- Subscription Plan & Quota Info -->
                        <div class="p-3 rounded-lg bg-secondary text-xs flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-credit-card text-brand text-xs"></i>
                                <span class="font-normal text-theme-text">{{ $plan->name ?? 'No Plan Assigned' }}</span>
                            </span>
                            <span class="text-theme-text-muted text-[11px]">
                                @if($sub && $sub->ends_at)
                                    Exp: {{ $sub->ends_at->format('d M Y') }}
                                @else
                                    Lifetime Access
                                @endif
                            </span>
                        </div>

                        @if($sub && $sub->delete_on_expiry)
                            <div class="px-3 py-1.5 rounded-lg bg-danger-bg text-danger text-[11px] flex items-center gap-1.5">
                                <i class="fa-solid fa-fire text-xs"></i>
                                <span>Auto-Delete Scheduled on Plan Expiry</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Actions Row -->
                <div class="pt-2 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        @if($tenant->url)
                            <a href="{{ $tenant->url }}" target="_blank"
                               class="p-2 rounded-full bg-secondary text-theme-text hover:bg-subtle transition text-xs"
                               title="Visit Academy Portal">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif

                        <button type="button" @click="openAssign({{ json_encode($tenant) }})"
                                class="px-3.5 py-1.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-sliders text-xs"></i>
                            <span>Assign Plan</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Toggle Status -->
                        <form action="{{ route('arzavo.admin.tenants.update', $tenant->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-full text-xs font-normal transition cursor-pointer {{ $tenant->status === 'active' ? 'bg-secondary text-warning hover:bg-subtle' : 'bg-success-bg text-success' }}">
                                {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <button type="button" @click="openDelete({{ json_encode($tenant) }})"
                                class="p-2 rounded-full bg-secondary text-danger hover:bg-danger-bg transition text-xs cursor-pointer"
                                title="Delete Institute">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-lg bg-primary p-12 text-center text-theme-text-muted">
                <i class="fa-solid fa-building-columns text-4xl mb-3 opacity-40 block"></i>
                <h3 class="text-base font-semibold text-theme-text">No institutes registered</h3>
                <p class="text-xs text-theme-text-muted mt-1 mb-4">Click below to provision the first tenant institute</p>
                <a href="{{ route('tenants.create') }}" class="px-5 py-2.5 bg-brand text-primary rounded-full text-xs font-normal inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create Institute</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- 4. TABLE VIEW (ALTERNATE) -->
    <div x-show="viewMode === 'table'" class="rounded-lg bg-primary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-theme-text">
                <thead class="bg-secondary text-[11px] text-theme-text-muted uppercase tracking-wider font-normal">
                    <tr>
                        <th class="p-4 pl-6">Institute</th>
                        <th class="p-4">Subdomain / DNS</th>
                        <th class="p-4">Admin</th>
                        <th class="p-4">Subscription Plan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary">
                    @forelse($tenants as $tenant)
                        @php
                            $sub = $tenant->subscription;
                            $plan = $sub?->plan;
                            $hasCustomDomain = !empty($tenant->custom_domain);
                        @endphp
                        <tr
                            x-show="(!search || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain . ' ' . ($tenant->custom_domain ?? '') . ' ' . ($tenant->admin->email ?? '')) }}'.includes(search.toLowerCase())) &&
                                    (filter === 'all' || 
                                     (filter === 'active' && '{{ $tenant->status }}' === 'active') || 
                                     (filter === 'suspended' && '{{ $tenant->status }}' === 'suspended') || 
                                     (filter === 'verified' && {{ $tenant->domain_verified ? 'true' : 'false' }}) || 
                                     (filter === 'custom' && {{ $hasCustomDomain ? 'true' : 'false' }}))">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center uppercase shrink-0">
                                        {{ substr($tenant->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-theme-text">{{ $tenant->name }}</p>
                                        <p class="text-[11px] text-theme-text-muted">Created {{ $tenant->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-0.5">
                                    <p class="text-theme-text">{{ $tenant->subdomain }}.{{ config('app.domain') }}</p>
                                    @if($tenant->custom_domain)
                                        <p class="text-[11px] text-theme-text-muted flex items-center gap-1.5">
                                            <span>{{ $tenant->custom_domain }}</span>
                                            <span class="text-[9px] px-1.5 py-0.2 rounded-full {{ $tenant->domain_verified ? 'bg-info-bg text-info' : 'bg-warning-bg text-warning' }}">
                                                {{ $tenant->domain_verified ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <div>
                                    <p class="text-theme-text">{{ $tenant->admin->full_name ?? ($tenant->admin->name ?? 'Unassigned') }}</p>
                                    <p class="text-[11px] text-theme-text-muted">{{ $tenant->admin->email ?? '—' }}</p>
                                </div>
                            </td>
                            <td class="p-4">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] bg-brand/10 text-brand">
                                        {{ $plan->name ?? 'Free Tier' }}
                                    </span>
                                    <p class="text-[10px] text-theme-text-muted mt-1">
                                        {{ $sub && $sub->ends_at ? 'Ends ' . $sub->ends_at->format('d M Y') : 'Lifetime' }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] {{ $tenant->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if($tenant->url)
                                        <a href="{{ $tenant->url }}" target="_blank"
                                           class="p-2 rounded-full bg-secondary text-theme-text hover:bg-subtle transition text-xs"
                                           title="Visit Portal">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    @endif
                                    <button type="button" @click="openAssign({{ json_encode($tenant) }})"
                                            class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle transition text-xs font-normal cursor-pointer">
                                        Assign Plan
                                    </button>
                                    <form action="{{ route('arzavo.admin.tenants.update', $tenant->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-full text-xs font-normal transition cursor-pointer {{ $tenant->status === 'active' ? 'bg-secondary text-warning hover:bg-subtle' : 'bg-success-bg text-success' }}">
                                            {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </form>
                                    <button type="button" @click="openDelete({{ json_encode($tenant) }})"
                                            class="p-2 rounded-full bg-secondary text-danger hover:bg-danger-bg transition text-xs cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-theme-text-muted">No institutes registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. ASSIGN PLAN MODAL (Rule 4.2: shadow-2xl modal exception) -->
    <div x-show="assignModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-lg p-6 space-y-5 my-auto relative"
             @click.away="assignModalOpen = false">

            <div class="flex items-start justify-between pb-2">
                <div>
                    <h3 class="text-base font-semibold text-theme-text">Assign or Update Plan</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5" x-text="selectedTenant ? 'Configuring subscription for ' + selectedTenant.name : 'Select a tenant'"></p>
                </div>
                <button type="button" @click="assignModalOpen = false" class="text-theme-text-muted hover:text-theme-text text-sm p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="assignPlanForm" method="POST" class="space-y-4">
                @csrf

                <!-- Plan Selection -->
                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Select Subscription Plan</label>
                    <select name="plan_id" id="assign_plan_id"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none" required>
                        <option value="">-- Choose Plan Tier --</option>
                        @foreach($plans as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} (₹{{ number_format($p->monthly_price, 0) }}/mo)
                                @if($p->is_hidden) [Admin Restricted] @endif
                                @if($p->is_coming_soon) [Upcoming] @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status & Duration Type -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Subscription Status</label>
                        <select name="status" id="assign_status"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none" required>
                            <option value="active">Active</option>
                            <option value="trial">Trial</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Validity Duration</label>
                        <select name="duration_type" id="assign_duration_type"
                                onchange="document.getElementById('customDateContainer').classList.toggle('hidden', this.value !== 'custom')"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none" required>
                            <option value="7_days">7 Days Trial</option>
                            <option value="1_month" selected>1 Month (30 Days)</option>
                            <option value="3_months">3 Months</option>
                            <option value="6_months">6 Months</option>
                            <option value="1_year">1 Year (365 Days)</option>
                            <option value="lifetime">Lifetime Access</option>
                            <option value="custom">Custom Date</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Date Container -->
                <div id="customDateContainer" class="hidden">
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Custom Expiry Date</label>
                    <input type="date" name="custom_ends_at" id="assign_custom_ends_at"
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <!-- Custom Price Override -->
                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Custom Price Override (₹ Optional)</label>
                    <input type="number" step="0.01" name="custom_price" id="assign_custom_price"
                           placeholder="Leave empty for standard tier pricing"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <!-- Auto-Delete on Expiry Toggle -->
                <div class="p-3.5 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div class="pr-2">
                        <p class="text-xs font-semibold text-theme-text flex items-center gap-1.5">
                            <i class="fa-solid fa-fire text-danger"></i> Auto-Delete on Expiry
                        </p>
                        <p class="text-[11px] text-theme-text-muted">Automatically purge this tenant database when plan terminates.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="delete_on_expiry" id="assign_delete_on_expiry" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 dark:bg-neutral-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-danger"></div>
                    </label>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="assignModalOpen = false"
                            class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                        Confirm Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. SAFE DELETE CONFIRMATION MODAL -->
    <div x-show="deleteModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-md p-6 space-y-4 my-auto relative"
             @click.away="deleteModalOpen = false">

            <div class="w-12 h-12 rounded-full bg-danger-bg text-danger flex items-center justify-center text-lg mx-auto">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div class="text-center">
                <h3 class="text-base font-semibold text-theme-text">Delete Tenant Institute?</h3>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed">
                    This action will permanently delete <span class="font-semibold text-theme-text" x-text="tenantToDelete ? tenantToDelete.name : ''"></span> and all its isolated academy data, student records, and courses.
                </p>
            </div>

            <div class="p-3 rounded-lg bg-secondary text-xs text-theme-text-muted">
                To confirm, type <span class="text-brand font-mono font-medium select-all" x-text="tenantToDelete ? tenantToDelete.subdomain : ''"></span> below:
                <input type="text" x-model="confirmSubdomain"
                       placeholder="Enter subdomain..."
                       class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full mt-2 focus:outline-none">
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>
                <form :action="tenantToDelete ? '/tenants/' + tenantToDelete.id : '#'" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            :disabled="!tenantToDelete || confirmSubdomain.trim().toLowerCase() !== tenantToDelete.subdomain.toLowerCase()"
                            :class="tenantToDelete && confirmSubdomain.trim().toLowerCase() === tenantToDelete.subdomain.toLowerCase() ? 'bg-danger text-white cursor-pointer hover:opacity-90' : 'bg-secondary text-theme-text-muted cursor-not-allowed opacity-50'"
                            class="px-5 py-2.5 rounded-full font-normal text-xs transition">
                        Permanently Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection