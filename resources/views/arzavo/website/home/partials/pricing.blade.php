{{-- Pricing Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="pricing" class="py-20 bg-secondary overflow-hidden">
    <div class="container">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-4">
                    <i class="fa-solid fa-gem text-[10px]"></i>
                    <span>Transparent Pricing</span>
                </div>
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Simple, Transparent <br class="hidden sm:inline">
                    <span class="text-brand">Investment Tiers</span>.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Choose the multi-tenant plan that matches your current enrollment volume. Scale students, courses, and
                    custom domains effortlessly with zero setup fees and 100% of your fee collections staying directly in your account.
                </p>
            </div>

        </div>

        {{-- Plan Cards Grid (Ordered by increasing monthly_price) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ annual: false }">
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
                                    <span class="text-3xl font-semibold text-theme-text">{{ number_format($plan->monthly_price, 0) }}</span>
                                    <span class="text-xs text-theme-text-muted">/ month</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-theme-text-muted pt-1">
                                <span>
                                    @if($isFree)
                                        Monthly billing cycle
                                    @elseif($plan->yearly_price)
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

        {{-- Bottom Link to Full Pricing & Comparison --}}
        <div class="text-center mt-12">
            <a href="{{ route('pricing') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-theme-text text-xs font-normal hover:bg-subtle transition">
                <span>Compare all features & detailed limits</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

    </div>
</section>