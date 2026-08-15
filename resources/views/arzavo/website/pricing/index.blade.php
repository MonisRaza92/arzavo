@extends('layouts.app')
@section('title', 'Pricing Plans - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fffdf5 50%, #fff8f8 100%);"
         x-data="{ annual: true }">
    <div class="container relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Transparent Pricing</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Simple, predictable
                <span class="text-accent">pricing.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed mb-10 animate-fade-in-up" style="animation-delay:.1s;">
                No hidden fees. No surprises. Scale your institution with confidence on a platform built for infinite growth.
            </p>

            {{-- Toggle --}}
            <div class="flex items-center justify-center gap-3 animate-fade-in-up" style="animation-delay:.2s;">
                <span class="text-sm font-semibold transition-colors" :class="!annual ? 'text-dark' : 'text-dark/40'">Monthly</span>
                <button @click="annual = !annual"
                        class="w-12 h-6 rounded-full relative focus:outline-none transition-colors cursor-pointer"
                        :class="annual ? 'bg-accent' : 'bg-gray-300'">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-200"
                         :class="annual ? 'right-1' : 'left-1'"></div>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold transition-colors" :class="annual ? 'text-dark' : 'text-dark/40'">Annually</span>
                    <span class="text-xs font-semibold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Save 20%</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Pricing Cards --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);"
         x-data="{ annual: true }">
    <div class="container">
        {{-- Sync toggle (re-use same state, or use a shared parent in production) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($plans as $plan)
            <div class="relative rounded-lg overflow-hidden flex flex-col {{ $plan->is_popular ? 'border border-accent!' : 'border' }} bg-white">

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
                    <p class="text-xs font-semibold uppercase tracking-widest {{ $plan->is_popular ? 'text-accent' : 'text-dark/40' }} mb-4">{{ $plan->name }}</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-4xl font-semibold {{ $plan->is_popular ? 'text-accent' : 'text-dark' }}">
                            {{ $plan->monthly_price == 0 ? 'Free' : '₹' . $plan->monthly_price }}
                        </span>
                        @if($plan->monthly_price != 0)<span class="text-sm text-dark/40">/mo</span>@endif
                    </div>
                    <p class="text-sm text-dark/50 pb-5 mb-5 border-b border-gray-100">{{ $plan->short_description }}</p>

                    <ul class="space-y-3 mb-8 flex-grow">
                        @foreach($plan->features ?? [] as $key => $value)
                            @if($value)
                            <li class="flex items-center gap-3 text-sm text-dark/70">
                                <i class="fa-solid fa-check {{ $plan->is_popular ? 'text-accent' : 'text-dark/30' }} text-xs shrink-0"></i>
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </li>
                            @endif
                        @endforeach
                        @foreach($plan->limits ?? [] as $key => $value)
                        <li class="flex items-center gap-3 text-sm text-dark/70">
                            <i class="fa-solid fa-database text-accent-secondary text-xs shrink-0"></i>
                            <span><strong class="text-dark">{{ $value }}</strong> {{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                        </li>
                        @endforeach
                    </ul>

                    @if($plan->is_coming_soon)
                        <button disabled
                           class="w-full py-3 text-center text-sm font-semibold rounded bg-gray-100 text-dark/40 cursor-not-allowed block">
                            ⏳ Coming Soon
                        </button>
                    @else
                        <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                           class="w-full py-3 text-center text-sm font-semibold rounded transition-all duration-200 block {{ $plan->is_popular ? 'bg-accent text-white hover:opacity-90' : 'bg-gray-50 text-dark/70 hover:bg-gray-100' }}">
                            {{ $plan->monthly_price == 0 ? 'Get Started Free' : 'Choose Plan' }}
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Feature Comparison Table --}}
@php
    $allFeatures = [];
    foreach ($plans as $plan) {
        foreach (($plan->features ?? []) as $key => $val) { $allFeatures[$key] = true; }
    }
    $allFeatures = array_keys($allFeatures);
@endphp

<section class="py-20 hidden md:block overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #f9f9f9 100%);">
    <div class="container">
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Detailed Comparison</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">Compare every feature.</h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Dive deep into what makes Arzavo the ultimate platform for your institution.
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="p-5 pl-6 text-xs font-semibold uppercase tracking-widest text-dark/40 w-1/4">Core Feature</th>
                            @foreach($plans as $plan)
                                <th class="p-5 text-center w-1/4 {{ $plan->is_popular ? 'bg-accent/5' : '' }}">
                                    <div class="text-sm font-semibold {{ $plan->is_popular ? 'text-accent' : 'text-dark' }} mb-0.5">{{ $plan->name }}</div>
                                    <div class="text-xs text-dark/40">{{ $plan->monthly_price == 0 ? 'Free' : '₹'.$plan->monthly_price.'/mo' }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allFeatures as $feature)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 pl-6 text-sm font-medium text-dark">{{ ucfirst(str_replace('_', ' ', $feature)) }}</td>
                                @foreach($plans as $plan)
                                    <td class="p-4 text-center {{ $plan->is_popular ? 'bg-accent/5' : '' }}">
                                        @if(($plan->features[$feature] ?? false))
                                            <i class="fa-solid fa-check text-accent text-xs"></i>
                                        @else
                                            <i class="fa-solid fa-xmark text-dark/20 text-xs"></i>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-gray-200 bg-accent/5">
                <p class="text-sm text-dark/60">Need a custom enterprise solution?</p>
                <x-button url="{{ route('contact') }}" padding="px-6 py-2.5">
                    Contact Sales <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                </x-button>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Mini --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #fffbf8 100%);"
         x-data="{ active: null }">
    <div class="container">
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Pricing FAQ</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">Common questions about pricing.</h2>
        </div>
        <div class="max-w-3xl space-y-3">
            @php $pricingFaq = [
                ['q' => 'Can I switch plans at any time?', 'a' => 'Yes, you can upgrade or downgrade your plan anytime from your admin dashboard. Changes take effect immediately, and billing is prorated automatically.'],
                ['q' => 'Is there a free trial for paid plans?', 'a' => 'Yes, every paid plan comes with a 14-day free trial. No credit card required. You get full access to all features during the trial period.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept UPI, credit/debit cards, net banking, and wallet payments via Razorpay. For annual enterprise plans, we also support bank transfers and invoicing.'],
                ['q' => 'What happens when I exceed my plan limits?', 'a' => 'You\'ll receive a notification when you\'re at 80% of your limit. We never suddenly cut access. You can upgrade or contact us for a custom extension.'],
            ]; @endphp
            @foreach($pricingFaq as $i => $faq)
            <div class="rounded-lg border border-gray-200 bg-white overflow-hidden transition-all duration-300"
                 :class="active === {{ $i }} ? 'border-accent/30' : ''">
                <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between p-5 text-left gap-4 cursor-pointer">
                    <span class="text-sm font-semibold text-dark">{{ $faq['q'] }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-dark/30 transition-transform duration-300 shrink-0"
                       :class="active === {{ $i }} ? 'rotate-180 !text-accent' : ''"></i>
                </button>
                <div x-show="active === {{ $i }}"
                     x-collapse
                     x-cloak>
                    <div class="px-5 pb-5 text-sm text-dark/60 leading-relaxed border-t border-gray-100 pt-4">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="rounded-lg p-12 md:p-16 bg-accent text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full pointer-events-none opacity-15"
                 style="background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 65%); transform: translate(30%, -30%);"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-semibold text-white mb-5 leading-tight tracking-tight">
                    Start for free. Scale when ready.
                </h2>
                <p class="text-white/75 text-lg leading-relaxed mb-10">
                    No credit card required. Full feature access for 14 days.
                </p>
                <a href="{{ route('register.form') }}"
                   class="px-8 py-3.5 bg-white text-accent font-semibold rounded text-sm inline-flex items-center gap-2 hover:opacity-90 transition-opacity">
                    Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection

<style>
@keyframes fade-in-down { from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);} }
@keyframes fade-in-up { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
.animate-fade-in-down { animation: fade-in-down .6s ease-out both; }
.animate-fade-in-up { animation: fade-in-up .6s ease-out both; }
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>