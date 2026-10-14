@extends('layouts.arzavo.admin')
@section('title', 'Plans Management')

@section('content')
<div x-data="{
    filter: 'all',
    deletePlanId: null,
    deleteModalOpen: false,
    openDelete(p) {
        this.deletePlanId = p.id;
        this.deleteModalOpen = true;
    }
}" class="space-y-6">

    <!-- 1. HERO HEADER WITH LIVE SEARCH & ACTIONS -->
    <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-normal bg-brand/10 text-brand">
                    Monetization & Plans
                </span>
                <span class="text-xs text-theme-text-muted">
                    • {{ $plans->count() }} Defined Pricing Tiers
                </span>
            </div>
            <h1 class="text-2xl font-semibold text-theme-text leading-tight">
                Subscription Tiers & Quota Packages
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 max-w-2xl leading-relaxed">
                Control commercial plan pricing, configure feature capabilities, set academy student & storage limits, and adjust promotional trial periods.
            </p>

            <!-- Search Feedback Strip -->
            <div x-show="search && search.length > 0" class="mt-2 text-xs text-theme-text-muted">
                Showing matching plans for <span class="text-theme-text font-medium" x-text="'&ldquo;' + search + '&rdquo;'"></span>
                <button type="button" @click="search = ''" class="ml-2 text-brand hover:underline">Clear search</button>
            </div>
        </div>

        <button type="button" onclick="openCreatePlanModal()"
                class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create Plan Tier</span>
        </button>
    </div>

    <!-- 2. FILTER PILLS BAR -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <button type="button" @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            All Tiers ({{ $plans->count() }})
        </button>
        <button type="button" @click="filter = 'active'"
                :class="filter === 'active' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Active ({{ $plans->where('is_active', true)->count() }})
        </button>
        <button type="button" @click="filter = 'popular'"
                :class="filter === 'popular' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Featured Popular ({{ $plans->where('is_popular', true)->count() }})
        </button>
        <button type="button" @click="filter = 'hidden'"
                :class="filter === 'hidden' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Admin Restricted ({{ $plans->where('is_hidden', true)->count() }})
        </button>
    </div>

    <!-- 3. PLANS CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plans as $plan)
            @php
                $isFree = $plan->monthly_price == 0;
            @endphp
            <div class="rounded-lg bg-primary p-6 flex flex-col justify-between relative"
                 x-show="(!search || '{{ strtolower($plan->name . ' ' . $plan->slug . ' ' . ($plan->short_description ?? '')) }}'.includes(search.toLowerCase())) &&
                         (filter === 'all' || 
                          (filter === 'active' && {{ $plan->is_active ? 'true' : 'false' }}) || 
                          (filter === 'popular' && {{ $plan->is_popular ? 'true' : 'false' }}) || 
                          (filter === 'hidden' && {{ $plan->is_hidden ? 'true' : 'false' }}))">
                <div>
                    <!-- Badges Header -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal {{ $plan->is_active ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                            {{ $plan->is_active ? 'Active Tier' : 'Inactive' }}
                        </span>

                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                            @if($plan->is_popular)
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-warning-bg text-warning font-normal">
                                    ⭐ Popular
                                </span>
                            @endif
                            @if($plan->is_limited_time)
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-danger-bg text-danger font-normal">
                                    🔥 Limited
                                </span>
                            @endif
                            @if($plan->is_coming_soon)
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-info-bg text-info font-normal">
                                    ⏳ Soon
                                </span>
                            @endif
                            @if($plan->is_hidden)
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text-muted font-normal">
                                    🔒 Admin Only
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Plan Title & Tagline -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-theme-text">{{ $plan->name }}</h3>
                        <p class="text-xs text-theme-text-muted mt-1 leading-relaxed">
                            {{ $plan->short_description ?: 'Comprehensive multi-tenant subscription package' }}
                        </p>
                    </div>

                    <!-- Price Block -->
                    <div class="p-4 rounded-lg bg-secondary mb-4 space-y-2">
                        <div class="flex items-baseline gap-1.5">
                            @if($isFree)
                                <span class="text-3xl font-semibold text-theme-text">Free</span>
                                <span class="text-xs text-theme-text-muted">/ forever</span>
                            @else
                                <span class="text-3xl font-semibold text-theme-text">₹{{ number_format($plan->monthly_price, 0) }}</span>
                                <span class="text-xs text-theme-text-muted">/ month</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-theme-text-muted pt-1">
                            <span>
                                @if($plan->yearly_price)
                                    ₹{{ number_format($plan->yearly_price, 0) }} billed annually
                                @else
                                    Monthly billing cycle
                                @endif
                            </span>
                            @if($plan->trial_days)
                                <span class="text-brand font-normal">{{ $plan->trial_days }} Days Free Trial</span>
                            @endif
                        </div>
                    </div>

                    <!-- Resource Limits Inner Block -->
                    <div class="p-3.5 rounded-lg bg-secondary text-xs mb-4 space-y-1.5">
                        <p class="text-[10px] text-theme-text-muted uppercase tracking-wider font-semibold mb-2">Resource Quotas</p>
                        @foreach(config('plan.limits') as $limitKey => $limitLabel)
                            @php
                                $val = $plan->limits[$limitKey] ?? null;
                            @endphp
                            <div class="flex items-center justify-between text-theme-text-muted text-[11px]">
                                <span>{{ $limitLabel }}</span>
                                <span class="text-theme-text font-medium">{{ ($val === null || $val === '') ? 'Unlimited' : $val }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Features Checklist Preview (Expandable) -->
                    @php
                        $allPlanFeatures = config('plan.features') ?? [];
                        $visFeatures = array_slice($allPlanFeatures, 0, 5, true);
                        $remFeatures = array_slice($allPlanFeatures, 5, null, true);
                    @endphp
                    <div class="mb-5 space-y-1.5" x-data="{ expanded: false }">
                        <p class="text-[10px] text-theme-text-muted uppercase tracking-wider font-semibold mb-2">Module Capabilities</p>
                        @foreach($visFeatures as $fKey => $fLabel)
                            @php
                                $isEnabled = (bool) ($plan->features[$fKey] ?? false);
                            @endphp
                            <div class="flex items-center gap-2 text-xs">
                                <i class="fa-solid {{ $isEnabled ? 'fa-circle-check text-success' : 'fa-circle-xmark text-theme-text-muted opacity-40' }} text-xs shrink-0"></i>
                                <span class="{{ $isEnabled ? 'text-theme-text' : 'text-theme-text-muted line-through opacity-60' }} truncate">
                                    {{ $fLabel }}
                                </span>
                            </div>
                        @endforeach
                        @if(!empty($remFeatures))
                            <div x-show="expanded" x-collapse class="space-y-1.5 pt-1.5">
                                @foreach($remFeatures as $fKey => $fLabel)
                                    @php
                                        $isEnabled = (bool) ($plan->features[$fKey] ?? false);
                                    @endphp
                                    <div class="flex items-center gap-2 text-xs">
                                        <i class="fa-solid {{ $isEnabled ? 'fa-circle-check text-success' : 'fa-circle-xmark text-theme-text-muted opacity-40' }} text-xs shrink-0"></i>
                                        <span class="{{ $isEnabled ? 'text-theme-text' : 'text-theme-text-muted line-through opacity-60' }} truncate">
                                            {{ $fLabel }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" @click="expanded = !expanded"
                                    class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1.5 pt-1 cursor-pointer">
                                <span x-text="expanded ? '- Show fewer features' : '+{{ count($remFeatures) }} more included features'"></span>
                                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Card Actions Row -->
                <div class="pt-3 flex items-center justify-between gap-3">
                    <span class="text-xs text-theme-text-muted">
                        <span class="font-semibold text-theme-text">{{ $plan->subscriptions_count }}</span> active {{ Str::plural('tenant', $plan->subscriptions_count) }}
                    </span>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="editPlan({{ $plan->id }})"
                                class="px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                            Edit Tier
                        </button>

                        <button type="button" @click="openDelete({{ json_encode($plan) }})"
                                class="p-2 rounded-full bg-secondary text-danger hover:bg-danger-bg transition text-xs cursor-pointer"
                                title="Delete Plan">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-lg bg-primary p-12 text-center text-theme-text-muted">
                <i class="fa-solid fa-credit-card text-4xl mb-3 opacity-40 block"></i>
                <h3 class="text-base font-semibold text-theme-text">No plans created</h3>
                <p class="text-xs text-theme-text-muted mt-1 mb-4">Click below to establish your initial subscription tier</p>
                <button type="button" onclick="openCreatePlanModal()"
                        class="px-5 py-2.5 bg-brand text-primary rounded-full text-xs font-normal inline-flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create Pricing Tier</span>
                </button>
            </div>
        @endforelse
    </div>

    <!-- 4. DELETE PLAN CONFIRMATION MODAL -->
    <div x-show="deleteModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-md p-6 space-y-4 my-auto relative"
             @click.away="deleteModalOpen = false">

            <div class="w-12 h-12 rounded-full bg-danger-bg text-danger flex items-center justify-center text-lg mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>

            <div class="text-center">
                <h3 class="text-base font-semibold text-theme-text">Delete Subscription Tier?</h3>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed">
                    This action will permanently remove this pricing plan. Any tier currently occupied by active institutes cannot be deleted.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>
                <form :action="'/plans/' + deletePlanId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-danger text-white rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                        Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Include Plan Create/Edit Modal Component --}}
@include('arzavo.admin.plans.planadd')

@endsection