{{-- Pricing Section --}}
<section id="pricing" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fffbf8 50%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-16">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Investment</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Simple, transparent pricing.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Choose the plan that fits your growth stage. No lock-ins, no hidden fees, cancel anytime.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            @php
                $isFree = $plan->monthly_price == 0;
            @endphp
            <div class="relative rounded-lg border flex flex-col transition-all duration-300 bg-white shadow-xs hover:shadow-md
                 {{ $plan->is_popular ? 'border-accent! ring-1 ring-accent' : 'border-gray-200' }}">

                {{-- Badges --}}
                @if($plan->is_coming_soon)
                <div class="absolute top-4 right-4 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded shadow-xs">
                    ⏳ Coming Soon
                </div>
                @elseif($plan->is_limited_time)
                <div class="absolute top-4 right-4 bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded shadow-xs animate-pulse">
                    🔥 Limited Time Offer
                </div>
                @elseif($plan->is_popular)
                <div class="absolute top-4 right-4 bg-accent text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded shadow-xs">
                    ★ Most Popular
                </div>
                @endif

                <div class="p-8 flex flex-col flex-1">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-3 {{ $plan->is_popular ? 'text-accent' : 'text-dark/50' }}">
                        {{ $plan->name }}
                    </h3>

                    <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-4xl font-bold text-dark">
                            {{ $isFree ? 'Free' : '₹' . number_format($plan->monthly_price, 0) }}
                        </span>
                        @if(!$isFree)
                            <span class="text-sm text-dark/50 font-normal">/month</span>
                        @endif
                    </div>

                    @if($plan->yearly_price && !$isFree)
                        <p class="text-xs text-dark/50 mb-2">
                            ₹{{ number_format($plan->yearly_price, 0) }} / year (billed annually)
                        </p>
                    @endif

                    @if($plan->trial_days)
                        <p class="text-xs text-emerald-600 font-semibold mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-gift"></i> {{ $plan->trial_days }} Days Free Trial
                        </p>
                    @endif

                    <p class="text-sm text-dark/60 pb-5 mb-5 border-b border-gray-100">
                        {{ $plan->short_description ?? 'Full-featured tier designed for institutions' }}
                    </p>

                    {{-- Features List (Enabled + Disabled with Cross) --}}
                    <div class="mb-6 flex-grow space-y-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-dark/40 mb-3">Platform Features</p>
                            <ul class="space-y-2.5">
                                @foreach(config('plan.features') as $key => $label)
                                    @php
                                        $enabled = $plan->features[$key] ?? false;
                                    @endphp
                                    <li class="flex items-center gap-2.5 text-sm">
                                        @if($enabled)
                                            <i class="fa-solid fa-check text-emerald-600 text-xs shrink-0"></i>
                                            <span class="text-dark/80 font-medium">{{ $label }}</span>
                                        @else
                                            <i class="fa-solid fa-xmark text-red-400 text-xs shrink-0"></i>
                                            <span class="text-dark/35 line-through">{{ $label }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Resource Limits --}}
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-dark/40 mb-3">Resource Limits</p>
                            <ul class="space-y-2 text-xs">
                                @foreach(config('plan.limits') as $key => $label)
                                    @php
                                        $val = $plan->limits[$key] ?? null;
                                    @endphp
                                    <li class="flex justify-between items-center text-dark/70">
                                        <span>{{ $label }}</span>
                                        <strong class="text-dark font-semibold">
                                            {{ ($val === null || $val === '') ? 'Unlimited' : $val }}
                                        </strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    @if($plan->is_coming_soon)
                        <button disabled
                           class="w-full py-3 text-center text-sm font-semibold rounded bg-gray-100 text-dark/40 cursor-not-allowed block">
                            ⏳ Coming Soon
                        </button>
                    @else
                        <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                           class="w-full py-3 text-center text-sm font-semibold rounded transition-all duration-200 block shadow-xs {{ $plan->is_popular ? 'bg-accent text-white hover:opacity-90' : 'bg-dark text-white hover:bg-dark/90' }}">
                            {{ $isFree ? 'Get Started Free' : 'Choose ' . $plan->name }}
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
