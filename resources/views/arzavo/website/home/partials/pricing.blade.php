{{-- Pricing Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="pricing" class="py-20 bg-secondary overflow-hidden">
    <div class="container">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Simple, Transparent <br class="hidden sm:inline">
                    <span class="text-brand">Investment Tiers</span>.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Choose the multi-tenant plan that matches your current enrollment volume. Upgrade or downgrade
                    anytime as your institute expands, with zero hidden setup fees and 100% of your student fee
                    collections staying with you.
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-data="{ annual: false }">
            @foreach($plans as $plan)
                @php
                    $isFree = $plan->monthly_price == 0;
                @endphp
                <div class="bg-primary rounded-lg p-6 flex flex-col justify-between relative">

                    {{-- Status & Name Header --}}
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-theme-text">
                            {{ $plan->name }}
                        </span>

                        @if($plan->is_coming_soon)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-info-bg text-info">
                                Coming Soon
                            </span>
                        @elseif($plan->is_limited_time)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning">
                                Limited Offer
                            </span>
                        @elseif($plan->is_popular)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-brand text-primary">
                                Most Popular
                            </span>
                        @endif
                    </div>

                    {{-- Price Display --}}
                    <div class="mb-5">
                        @if($isFree)
                            <div class="text-3xl md:text-4xl font-semibold text-theme-text">Free</div>
                        @else
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl md:text-4xl font-semibold text-theme-text"
                                    x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '₹{{ number_format($plan->yearly_price ?? ($plan->monthly_price * 12), 0) }}' : '₹{{ number_format($plan->monthly_price, 0) }}'">
                                    ₹{{ number_format($plan->monthly_price, 0) }}
                                </span>
                                <span class="text-xs text-theme-text-muted font-normal"
                                    x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '/year' : '/month'">
                                    /month
                                </span>
                            </div>

                            @if($plan->yearly_price)
                                <p class="text-[11px] text-theme-text-muted font-normal mt-1" x-show="!annual">
                                    ₹{{ number_format($plan->yearly_price, 0) }} / year (billed annually)
                                </p>
                            @endif
                        @endif

                        @if($plan->trial_days)
                            <p class="text-xs text-success font-normal mt-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-gift text-[11px]"></i> {{ $plan->trial_days }} Days Free Trial
                                Included
                            </p>
                        @endif

                        <p class="text-xs text-theme-text-muted font-normal mt-3 leading-relaxed">
                            {{ $plan->short_description ?? 'Designed for modern educational institutions' }}
                        </p>
                    </div>

                    {{-- Feature Matrix --}}
                    <div class="space-y-4 mb-6 flex-1">
                        <div class="bg-secondary rounded-lg p-3.5 space-y-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2">
                                Included Modules</p>
                            @foreach(config('plan.features') as $key => $label)
                                @php
                                    $enabled = $plan->features[$key] ?? false;
                                @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    @if($enabled)
                                        <i class="fa-solid fa-check text-success text-[10px] shrink-0"></i>
                                        <span class="text-theme-text font-normal">{{ $label }}</span>
                                    @else
                                        <i class="fa-solid fa-xmark text-theme-text-muted/40 text-[10px] shrink-0"></i>
                                        <span class="text-theme-text-muted/50 font-normal line-through">{{ $label }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Quotas --}}
                        <div class="bg-secondary rounded-lg p-3.5 space-y-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2">
                                Usage Quotas</p>
                            @foreach(config('plan.limits') as $key => $label)
                                @php
                                    $val = $plan->limits[$key] ?? null;
                                @endphp
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-theme-text-muted font-normal">{{ $label }}</span>
                                    <span class="text-theme-text font-semibold">
                                        {{ ($val === null || $val === '') ? 'Unlimited' : $val }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <div>
                        @if($plan->is_coming_soon)
                            <button disabled
                                class="w-full py-3 text-center text-xs font-normal rounded-full bg-secondary text-theme-text-muted cursor-not-allowed">
                                Coming Soon
                            </button>
                        @else
                            <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                                class="w-full py-3 text-center text-xs font-normal rounded-full transition-opacity block hover:opacity-90 {{ $plan->is_popular ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle' }}">
                                {{ $isFree ? 'Start Free Workspace' : 'Select ' . $plan->name }}
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>