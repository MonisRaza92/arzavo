@extends('layouts.app')
@section('title', 'Pricing Plans - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fffdf5 50%, #fff8f8 100%);"
         x-data="{ annual: false }">
    <div class="container relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Transparent Pricing</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight">
                Simple, predictable
                <span class="text-accent">pricing.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed mb-10">
                No hidden fees. No surprises. Scale your institution with confidence on a platform built for growth.
            </p>

            {{-- Toggle --}}
            <div class="flex items-center justify-center gap-3">
                <span class="text-sm font-semibold transition-colors" :class="!annual ? 'text-dark' : 'text-dark/40'">Monthly</span>
                <button @click="annual = !annual"
                        type="button"
                        class="w-12 h-6 rounded-full relative focus:outline-none transition-colors cursor-pointer"
                        :class="annual ? 'bg-accent' : 'bg-gray-300'">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-200"
                         :class="annual ? 'right-1' : 'left-1'"></div>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold transition-colors" :class="annual ? 'text-dark' : 'text-dark/40'">Annually</span>
                    <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Save on Annual</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Pricing Cards --}}
<section class="relative py-16 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);"
         x-data="{ annual: false }">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            @php
                $isFree = $plan->monthly_price == 0;
            @endphp
            <div class="relative rounded-lg overflow-hidden flex flex-col transition-all duration-200 {{ $plan->is_popular ? 'border-2 border-accent! shadow-md' : 'border border-gray-200 shadow-xs' }} bg-white">

                {{-- Badges --}}
                @if($plan->is_coming_soon)
                <div class="text-center py-2 bg-blue-600">
                    <span class="text-white text-xs font-semibold uppercase tracking-widest">⏳ Coming Soon</span>
                </div>
                @elseif($plan->is_popular)
                <div class="text-center py-2 bg-accent">
                    <span class="text-white text-xs font-semibold uppercase tracking-widest">✦ Most Popular</span>
                </div>
                @endif

                <div class="p-7 flex flex-col flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest {{ $plan->is_popular ? 'text-accent' : 'text-dark/50' }} mb-4">
                        {{ $plan->name }}
                    </p>

                    {{-- Price Display with Annual Toggle --}}
                    <div class="mb-3">
                        @if($isFree)
                            <span class="text-4xl font-bold text-dark">Free</span>
                        @else
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-bold {{ $plan->is_popular ? 'text-accent' : 'text-dark' }}"
                                      x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '₹{{ number_format($plan->yearly_price ?? ($plan->monthly_price * 12), 0) }}' : '₹{{ number_format($plan->monthly_price, 0) }}'">
                                    ₹{{ number_format($plan->monthly_price, 0) }}
                                </span>
                                <span class="text-sm text-dark/50"
                                      x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '/year' : '/month'">
                                    /month
                                </span>
                            </div>

                            @if($plan->yearly_price)
                                <p class="text-xs text-dark/50 mt-1" x-show="!annual">
                                    ₹{{ number_format($plan->yearly_price, 0) }} / year (billed annually)
                                </p>
                            @endif
                        @endif

                        @if($plan->trial_days)
                            <p class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-gift"></i> {{ $plan->trial_days }} Days Free Trial
                            </p>
                        @endif
                    </div>

                    <p class="text-sm text-dark/60 pb-5 mb-5 border-b border-gray-100">
                        {{ $plan->short_description ?? 'Designed for educational institutions' }}
                    </p>

                    {{-- Features List (All Features with Check and Cross) --}}
                    <div class="mb-6 flex-grow space-y-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-dark/40 mb-3">Included Features</p>
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

                        {{-- Limits --}}
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-dark/40 mb-3">Usage Limits</p>
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

                    {{-- CTA --}}
                    @if($plan->is_coming_soon)
                        <button disabled
                           class="w-full py-3 text-center text-sm font-semibold rounded bg-gray-100 text-dark/40 cursor-not-allowed block">
                            ⏳ Coming Soon
                        </button>
                    @else
                        <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                           class="w-full py-3 text-center text-sm font-semibold rounded transition-all duration-200 block shadow-xs {{ $plan->is_popular ? 'bg-accent text-white hover:opacity-90' : 'bg-dark text-white hover:bg-dark/90' }}">
                            {{ $isFree ? 'Get Started Free' : 'Choose Plan' }}
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Feature Comparison Table --}}
<section class="py-20 hidden md:block overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #f9f9f9 100%);">
    <div class="container">
        <div class="mb-14 text-center max-w-3xl mx-auto">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Detailed Comparison</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">Compare every plan feature.</h2>
            <p class="text-dark/70 leading-relaxed text-base">
                Everything you need to know about what's included in each tier.
            </p>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-xs">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50">
                        <th class="p-5 font-bold text-dark w-1/3">Features & Capabilities</th>
                        @foreach($plans as $plan)
                            <th class="p-5 text-center font-bold {{ $plan->is_popular ? 'text-accent' : 'text-dark' }}">
                                <div>{{ $plan->name }}</div>
                                <div class="text-xs font-normal text-dark/50 mt-1">
                                    {{ $plan->monthly_price == 0 ? 'Free' : '₹' . number_format($plan->monthly_price, 0) . '/mo' }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    {{-- Features rows --}}
                    <tr class="bg-gray-100/60 font-semibold text-dark text-xs uppercase tracking-wider">
                        <td colspan="{{ count($plans) + 1 }}" class="p-3.5 px-5">Features</td>
                    </tr>
                    @foreach(config('plan.features') as $key => $label)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 px-5 text-dark/80 font-medium">{{ $label }}</td>
                            @foreach($plans as $plan)
                                @php $enabled = $plan->features[$key] ?? false; @endphp
                                <td class="p-4 text-center">
                                    @if($enabled)
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                                    @else
                                        <i class="fa-solid fa-circle-xmark text-red-300 text-base"></i>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- Limits rows --}}
                    <tr class="bg-gray-100/60 font-semibold text-dark text-xs uppercase tracking-wider">
                        <td colspan="{{ count($plans) + 1 }}" class="p-3.5 px-5">Limits & Quotas</td>
                    </tr>
                    @foreach(config('plan.limits') as $key => $label)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 px-5 text-dark/80 font-medium">{{ $label }}</td>
                            @foreach($plans as $plan)
                                @php $val = $plan->limits[$key] ?? null; @endphp
                                <td class="p-4 text-center font-semibold text-dark">
                                    {{ ($val === null || $val === '') ? 'Unlimited' : $val }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection