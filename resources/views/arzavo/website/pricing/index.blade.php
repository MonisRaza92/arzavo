@extends('layouts.arzavo.website')

@section('title', 'Pricing Plans — Transparent Educational ERP & LMS Investment • Arzavo')
@section('description', 'Simple, transparent multi-tenant licensing for schools, coaching institutes, and global academies. Zero setup fees, 100% fee retention, and instant cloud deployment.')

@section('content')
    @include('arzavo.website.partials.navbar')

    <main class="pt-24 min-h-screen bg-secondary" x-data="{ annual: false }">

        {{-- 1. Pricing Hero Header --}}
        <section class="py-16 md:py-24">
            <div class="container text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-6">
                    <i class="fa-solid fa-gem text-[10px]"></i>
                    <span>Predictable Institutional Licensing</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold text-theme-text tracking-tight mb-6 leading-tight">
                    Predictable Pricing.<br>
                    <span class="text-brand">Zero Hidden Fees</span>.
                </h1>

                <p class="text-sm md:text-base text-theme-text-muted leading-relaxed max-w-2xl mx-auto mb-10 font-normal">
                    Choose the multi-tenant tier that matches your current enrollment volume. Scale students, courses, and
                    custom domains effortlessly with 100% of your fee collections staying directly in your bank account.
                </p>

                {{-- Billing Frequency Toggle --}}
                <div class="inline-flex items-center gap-3 p-1.5 rounded-full bg-primary border border-secondary">
                    <span class="text-xs font-normal text-theme-text pl-3" :class="!annual ? 'font-semibold' : ''">Billed Monthly</span>
                    <button type="button" @click="annual = !annual"
                        class="relative w-12 h-6 rounded-full transition-colors duration-200 focus:outline-none cursor-pointer"
                        :class="annual ? 'bg-brand' : 'bg-subtle'">
                        <span class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform duration-200"
                            :class="annual ? 'translate-x-6' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-xs font-normal text-theme-text pr-2 flex items-center gap-1.5" :class="annual ? 'font-semibold' : ''">
                        <span>Billed Annually</span>
                        <span class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-semibold">Save ~20%</span>
                    </span>
                </div>
            </div>
        </section>

        {{-- 2. Plan Cards Grid (Ordered by increasing monthly_price) --}}
        <section class="pb-16 md:pb-24">
            <div class="container">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                        @php
                            $isFree = $plan->monthly_price == 0;
                            $allFeatures = config('plan.features') ?? [];
                            $visibleFeatures = array_slice($allFeatures, 0, 5, true);
                            $remainingFeatures = array_slice($allFeatures, 5, null, true);
                        @endphp
                        <div class="bg-primary rounded-lg p-6 flex flex-col justify-between relative">
                            <div>
                                {{-- Badges Header (Active Tier left, Popular/Status right) --}}
                                <div class="flex items-center justify-between gap-2 mb-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                        Active Tier
                                    </span>

                                    <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                        @if($plan->is_popular)
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning flex items-center gap-1">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                <span>Popular</span>
                                            </span>
                                        @endif
                                        @if($plan->is_coming_soon)
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-info-bg text-info">
                                                Upcoming
                                            </span>
                                        @elseif($plan->is_limited_time)
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-danger-bg text-danger">
                                                Limited Offer
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Plan Title & Description --}}
                                <div class="mb-4">
                                    <h3 class="text-xl font-semibold text-theme-text mb-1">{{ $plan->name }}</h3>
                                    <p class="text-xs text-theme-text-muted leading-relaxed min-h-[34px]">
                                        {{ $plan->short_description ?: 'Comprehensive institutional tier with essential academic tools.' }}
                                    </p>

                                    {{-- Price Display --}}
                                    <div class="mt-4 flex items-baseline gap-1.5">
                                        @if($isFree)
                                            <span class="text-3xl font-semibold text-theme-text">Free</span>
                                            <span class="text-xs text-theme-text-muted">/ forever</span>
                                        @else
                                            <span class="text-xs text-theme-text-muted">₹</span>
                                            <span class="text-3xl font-semibold text-theme-text"
                                                x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '{{ number_format(round($plan->yearly_price / 12), 0) }}' : '{{ number_format($plan->monthly_price, 0) }}'">
                                                {{ number_format($plan->monthly_price, 0) }}
                                            </span>
                                            <span class="text-xs text-theme-text-muted">/ month</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] text-theme-text-muted pt-1">
                                        <span>
                                            @if($isFree)
                                                Monthly billing cycle
                                            @elseif($plan->yearly_price)
                                                <span x-show="!annual">₹{{ number_format($plan->yearly_price, 0) }} billed annually</span>
                                                <span x-show="annual">₹{{ number_format($plan->yearly_price, 0) }} billed per year</span>
                                            @else
                                                Monthly billing cycle
                                            @endif
                                        </span>
                                        @if($plan->trial_days)
                                            <span class="text-brand font-normal">{{ $plan->trial_days }} Days Free Trial</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Resource Quotas Block --}}
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

                                {{-- Module Capabilities Checklist with Expandable Drawer --}}
                                <div class="mb-6 space-y-1.5" x-data="{ expanded: false }">
                                    <p class="text-[10px] text-theme-text-muted uppercase tracking-wider font-semibold mb-2">Module Capabilities</p>
                                    
                                    {{-- Visible First 5 Features --}}
                                    @foreach($visibleFeatures as $fKey => $fLabel)
                                        @php
                                            $isEnabled = (bool) ($plan->features[$fKey] ?? false);
                                        @endphp
                                        <div class="flex items-center gap-2 text-xs">
                                            <i class="fa-solid {{ $isEnabled ? 'fa-circle-check text-success' : 'fa-circle-xmark text-theme-text-muted/40' }} text-xs shrink-0"></i>
                                            <span class="{{ $isEnabled ? 'text-theme-text' : 'text-theme-text-muted/60 line-through' }} truncate">
                                                {{ $fLabel }}
                                            </span>
                                        </div>
                                    @endforeach

                                    {{-- Remaining Features (Expandable) --}}
                                    @if(!empty($remainingFeatures))
                                        <div x-show="expanded" x-collapse class="space-y-1.5 pt-1.5">
                                            @foreach($remainingFeatures as $fKey => $fLabel)
                                                @php
                                                    $isEnabled = (bool) ($plan->features[$fKey] ?? false);
                                                @endphp
                                                <div class="flex items-center gap-2 text-xs">
                                                    <i class="fa-solid {{ $isEnabled ? 'fa-circle-check text-success' : 'fa-circle-xmark text-theme-text-muted/40' }} text-xs shrink-0"></i>
                                                    <span class="{{ $isEnabled ? 'text-theme-text' : 'text-theme-text-muted/60 line-through' }} truncate">
                                                        {{ $fLabel }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Expand / Collapse Trigger --}}
                                        <button type="button" @click="expanded = !expanded"
                                                class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1.5 pt-1 cursor-pointer">
                                            <span x-text="expanded ? '- Show fewer features' : '+{{ count($remainingFeatures) }} more included features'"></span>
                                            <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <div class="pt-2">
                                @if($plan->is_coming_soon)
                                    <button disabled
                                        class="w-full py-3 text-center text-xs font-normal rounded-full bg-secondary text-theme-text-muted cursor-not-allowed">
                                        Coming Soon
                                    </button>
                                @else
                                    <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                                        class="w-full py-3 text-center text-xs font-normal rounded-full transition-opacity block hover:opacity-90 {{ $plan->is_popular ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle' }}">
                                        {{ $isFree ? 'Start Free Workspace' : 'Get Started with ' . $plan->name }}
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- 3. Comprehensive Feature Comparison Matrix (100% Real Plans & Details) --}}
        <section class="py-16 md:py-24 bg-primary">
            <div class="container max-w-6xl">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-3">
                        <i class="fa-solid fa-list-check text-[10px]"></i>
                        <span>Side-by-Side Breakdown</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-semibold text-theme-text mb-3">Plan Feature Comparison</h2>
                    <p class="text-xs md:text-sm text-theme-text-muted max-w-xl mx-auto">
                        A granular breakdown of platform quotas, curriculum modules, and commercial terms across all {{ $plans->count() }} active tiers.
                    </p>
                </div>

                <div class="overflow-x-auto rounded-lg bg-secondary">
                    <table class="w-full text-xs text-left">
                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr class="bg-primary">
                                <th class="py-5 px-6 font-semibold text-theme-text w-1/3 text-sm">
                                    Platform Feature
                                </th>
                                @foreach($plans as $plan)
                                    <th class="py-5 px-6 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-theme-text text-sm">{{ $plan->name }}</span>
                                                @if($plan->is_popular)
                                                    <span class="px-2 py-0.2 rounded-full text-[9px] bg-brand text-primary font-normal">
                                                        Popular
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-brand font-medium">
                                                {{ $plan->monthly_price == 0 ? 'Free' : '₹' . number_format($plan->monthly_price, 0) . '/mo' }}
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-primary/40">
                            {{-- SECTION 1: RESOURCE QUOTAS & LIMITS --}}
                            <tr class="bg-primary/50">
                                <td colspan="{{ $plans->count() + 1 }}" class="py-3 px-6 text-[11px] font-semibold uppercase tracking-wider text-theme-text">
                                    <i class="fa-solid fa-server text-brand mr-1.5 text-xs"></i> Resource Quotas & Limits
                                </td>
                            </tr>
                            @foreach(config('plan.limits') as $limitKey => $limitLabel)
                                <tr>
                                    <td class="py-3.5 px-6 text-theme-text font-normal">
                                        {{ $limitLabel }}
                                    </td>
                                    @foreach($plans as $plan)
                                        @php
                                            $val = $plan->limits[$limitKey] ?? null;
                                        @endphp
                                        <td class="py-3.5 px-6 text-center text-theme-text font-normal">
                                            @if($val === null || $val === '')
                                                <span class="text-success font-medium">Unlimited</span>
                                            @elseif($limitKey === 'storage' && is_numeric($val))
                                                <span>{{ $val }} GB</span>
                                            @else
                                                <span>{{ is_numeric($val) ? number_format($val) : $val }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- SECTION 2: MODULE CAPABILITIES & CAPABILITIES --}}
                            <tr class="bg-primary/50">
                                <td colspan="{{ $plans->count() + 1 }}" class="py-3 px-6 text-[11px] font-semibold uppercase tracking-wider text-theme-text">
                                    <i class="fa-solid fa-puzzle-piece text-brand mr-1.5 text-xs"></i> Module Capabilities
                                </td>
                            </tr>
                            @foreach(config('plan.features') as $fKey => $fLabel)
                                <tr>
                                    <td class="py-3.5 px-6 text-theme-text font-normal">
                                        {{ $fLabel }}
                                    </td>
                                    @foreach($plans as $plan)
                                        @php
                                            $isEnabled = (bool) ($plan->features[$fKey] ?? false);
                                        @endphp
                                        <td class="py-3.5 px-6 text-center">
                                            @if($isEnabled)
                                                <i class="fa-solid fa-circle-check text-success text-sm"></i>
                                            @else
                                                <span class="text-theme-text-muted/40 font-semibold text-sm">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- SECTION 3: COMMERCIAL TERMS & ARCHITECTURE --}}
                            <tr class="bg-primary/50">
                                <td colspan="{{ $plans->count() + 1 }}" class="py-3 px-6 text-[11px] font-semibold uppercase tracking-wider text-theme-text">
                                    <i class="fa-solid fa-shield-halved text-brand mr-1.5 text-xs"></i> Architecture & Commercial Terms
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 text-theme-text font-normal">Multi-Tenant Database Isolation</td>
                                @foreach($plans as $plan)
                                    <td class="py-3.5 px-6 text-center">
                                        <i class="fa-solid fa-circle-check text-success text-sm"></i>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 text-theme-text font-normal">Free Trial Period</td>
                                @foreach($plans as $plan)
                                    <td class="py-3.5 px-6 text-center text-theme-text font-normal">
                                        {{ $plan->trial_days ? $plan->trial_days . ' Days Trial' : 'No Free Trial' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 text-theme-text font-normal">Annual Billing Option</td>
                                @foreach($plans as $plan)
                                    <td class="py-3.5 px-6 text-center text-theme-text font-normal">
                                        {{ $plan->yearly_price ? '₹' . number_format($plan->yearly_price, 0) . ' / yr' : 'Monthly only' }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- SECTION 4: CALL TO ACTION FOOTER --}}
                            <tr class="bg-primary">
                                <td class="py-5 px-6 text-theme-text-muted text-xs">
                                    Select your preferred package
                                </td>
                                @foreach($plans as $plan)
                                    @php
                                        $isFree = $plan->monthly_price == 0;
                                    @endphp
                                    <td class="py-5 px-6 text-center">
                                        <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                                           class="px-5 py-2.5 rounded-full text-xs font-normal inline-block transition hover:opacity-90 {{ $plan->is_popular ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle' }}">
                                            {{ $isFree ? 'Start Free' : 'Choose ' . $plan->name }}
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- 4. FAQ & Value Proposition Section --}}
        <section class="py-16 md:py-24 bg-secondary">
            <div class="container max-w-4xl">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-semibold text-theme-text mb-3">Frequently Asked Questions</h2>
                    <p class="text-xs md:text-sm text-theme-text-muted">Common questions regarding multi-tenant hosting, student quotas, and migrations.</p>
                </div>

                <div class="space-y-4">
                    <div class="p-5 rounded-lg bg-primary">
                        <h3 class="text-sm font-semibold text-theme-text mb-1.5">Can I upgrade or downgrade my plan at any time?</h3>
                        <p class="text-xs text-theme-text-muted leading-relaxed">
                            Yes. Your billing is automatically prorated when upgrading. When scaling down, your new quota takes effect at the end of the current billing cycle.
                        </p>
                    </div>

                    <div class="p-5 rounded-lg bg-primary">
                        <h3 class="text-sm font-semibold text-theme-text mb-1.5">Are student transaction fees charged by Arzavo?</h3>
                        <p class="text-xs text-theme-text-muted leading-relaxed">
                            Zero fees. 100% of student fee collections through Razorpay, PayU, or Cashfree go straight to your institution's merchant account.
                        </p>
                    </div>

                    <div class="p-5 rounded-lg bg-primary">
                        <h3 class="text-sm font-semibold text-theme-text mb-1.5">How does custom white-label domain mapping work?</h3>
                        <p class="text-xs text-theme-text-muted leading-relaxed">
                            You simply add a CNAME record pointing to our edge proxy. SSL certificates are provisioned and renewed automatically without manual intervention.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    @include('arzavo.website.partials.footer')
@endsection