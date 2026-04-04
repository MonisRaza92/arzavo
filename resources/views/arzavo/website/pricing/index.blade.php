@extends('layouts.app')

@section('title', 'Pricing Plans - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.website.partials.navbar')

<!-- Page Header -->
<div class="pt-32 pb-20 bg-slate-950 relative overflow-hidden min-h-[50vh] flex flex-col justify-center">
    <!-- Sophisticated Background Glow -->
    <div class="absolute inset-0 opacity-30 mix-blend-screen pointer-events-none">
        <div class="absolute w-[800px] h-[800px] bg-accent/20 rounded-full blur-[120px] top-[-20%] left-[10%] animate-[spin_20s_linear_infinite]"></div>
        <div class="absolute w-[600px] h-[600px] bg-blue-500/20 rounded-full blur-[100px] bottom-[-20%] right-[-10%] animate-[spin_15s_linear_infinite_reverse]"></div>
    </div>
    
    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="max-w-4xl mx-auto reveal-on-scroll">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <i class="fa-solid fa-tags text-accent animate-pulse"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Investment</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8 leading-tight">
                Predictable <br class="hidden md:block"/>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent via-accent-secondary to-blue-500">Pricing.</span>
            </h1>
            <p class="text-xl text-slate-400 font-medium max-w-2xl mx-auto leading-relaxed mb-12">
                No hidden fees. Scale your institution with confidence on a platform built for infinite growth.
            </p>
            
            <!-- Pricing Toggle (Visual Only) -->
            <div class="flex items-center justify-center gap-4">
                <span class="text-sm font-bold text-white">Monthly</span>
                <button type="button" class="relative inline-flex h-8 w-16 shrink-0 cursor-pointer items-center justify-center rounded-full bg-white/10 border border-white/20 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-slate-900 transition-colors duration-300" role="switch" aria-checked="false">
                    <span aria-hidden="true" class="pointer-events-none absolute h-6 w-full rounded-full bg-transparent"></span>
                    <span aria-hidden="true" class="pointer-events-none absolute left-1 h-6 w-6 transform rounded-full bg-accent shadow ring-0 transition duration-300 ease-in-out translate-x-8"></span>
                </button>
                <span class="text-sm font-bold text-slate-400">Annually <span class="ml-1 text-xs text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded-full border border-blue-500/20">Save 20%</span></span>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Grid -->
<div class="py-24 bg-slate-900 relative">
    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto items-end">

            @foreach($plans as $plan)
                <div class="glass-panel-dark p-10 rounded-[2.5rem] border {{ $plan->is_popular ? 'border-accent shadow-[0_0_40px_rgba(239,68,68,0.15)] relative scale-105 z-10 bg-slate-800/80 backdrop-blur-2xl' : 'border-white/5 hover:border-white/20 transition-all duration-500' }} flex flex-col h-full reveal-on-scroll group min-h-[500px]">

                    @if($plan->is_popular)
                        <!-- Popular Highlight Elements -->
                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-linear-to-r from-accent to-accent-secondary text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-[0_0_20px_rgba(239,68,68,0.5)] z-20 whitespace-nowrap">
                            Most Popular
                        </div>
                        <div class="absolute inset-0 bg-linear-to-b from-accent/5 to-transparent rounded-[2.5rem] pointer-events-none"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-accent/20 rounded-full blur-2xl pointer-events-none animate-pulse"></div>
                    @else
                         <!-- Hover Gradient for non-popular -->
                         <div class="absolute inset-0 bg-linear-to-b from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2.5rem] pointer-events-none"></div>
                    @endif

                    <div class="relative z-10 flex-grow flex flex-col">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">{{ $plan->name }}</h3>
                        
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-5xl font-black text-white tracking-tight">
                                {{ $plan->monthly_price == 0 ? 'Free' : '₹' . $plan->monthly_price }}
                            </span>
                            @if($plan->monthly_price != 0)
                                <span class="text-sm font-medium text-slate-500">/mo</span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-slate-400 font-medium mb-8 pb-8 border-b border-white/10">
                            {{ $plan->short_description }}
                        </p>

                        <ul class="space-y-4 mb-10 grow">
                            @foreach($plan->features ?? [] as $key => $value)
                                @if($value)
                                    <li class="flex items-start gap-4 text-sm text-slate-300 font-medium group/item hover:text-white transition-colors">
                                        <div class="shrink-0 w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mt-0.5 group-hover/item:bg-emerald-500 group-hover/item:border-emerald-500 transition-colors">
                                            <i class="fa-solid fa-check text-[10px] text-emerald-400 group-hover/item:text-white"></i>
                                        </div>
                                        <span>{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    </li>
                                @endif
                            @endforeach

                            @foreach($plan->limits ?? [] as $key => $value)
                                <li class="flex items-start gap-4 text-sm text-slate-300 font-medium group/item hover:text-white transition-colors">
                                    <div class="shrink-0 w-5 h-5 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mt-0.5 group-hover/item:bg-blue-500 group-hover/item:border-blue-500 transition-colors">
                                        <i class="fa-solid fa-database text-[10px] text-blue-400 group-hover/item:text-white"></i>
                                    </div>
                                    <span><strong class="text-white">{{ $value }}</strong> {{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('checkout', ['slug' => $plan->slug]) }}" class="mt-auto w-full py-4 text-center text-sm font-bold uppercase tracking-widest rounded-xl transition-all duration-300
                            {{ $plan->is_popular 
                                ? 'bg-white text-slate-900 shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:bg-slate-200 hover:scale-[1.02]' 
                                : 'bg-white/5 text-white border border-white/10 hover:bg-white/10 hover:border-white/20' }}">
                            {{ $plan->monthly_price == 0 ? 'Get Started Free' : 'Choose Plan' }}
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

@php
    $allFeatures = [];
    foreach ($plans as $plan) {
        foreach (($plan->features ?? []) as $key => $val) {
            $allFeatures[$key] = true;
        }
    }
    $allFeatures = array_keys($allFeatures);
@endphp

<!-- Comprehensive Feature Comparison -->
<div class="py-32 bg-slate-950 relative overflow-hidden hidden md:block border-t border-white/5">
    <!-- Glow -->
    <div class="absolute bottom-1/4 -right-64 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-20 reveal-on-scroll">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Compare Features</h2>
            <p class="text-lg text-slate-400 font-medium pb-10">Dive deep into what makes Arzavo the ultimate platform.</p>
        </div>
        
        <div class="max-w-6xl mx-auto glass-panel border border-white/10 rounded-3xl overflow-hidden shadow-2xl reveal-on-scroll stagger-1">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10">
                            <th class="p-8 text-xs font-bold uppercase tracking-widest text-slate-400 w-1/4">Core Feature</th>
                            @foreach($plans as $plan)
                                <th class="p-8 text-center border-l border-white/5 w-1/4">
                                    <div class="text-lg font-black text-white mb-1">{{ $plan->name }}</div>
                                    <div class="text-sm font-medium text-slate-500">{{ $plan->monthly_price == 0 ? 'Free' : '₹'.$plan->monthly_price }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($allFeatures as $index => $feature)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-6 text-sm font-bold text-slate-300 group-hover:text-white transition-colors pl-8">
                                    {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                </td>
                                @foreach($plans as $plan)
                                    <td class="p-6 text-center border-l border-white/5">
                                        @if(($plan->features[$feature] ?? false))
                                            <div class="w-8 h-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mx-auto opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all">
                                                <i class="fa-solid fa-check text-xs text-emerald-400"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-600 opacity-50">
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Bottom CTA bar -->
            <div class="p-8 bg-black/20 border-t border-white/10 flex items-center justify-between">
                <p class="text-sm text-slate-400 font-medium">Need a custom enterprise solution?</p>
                <a href="{{ route('contact') }}" class="text-sm font-bold text-white hover:text-accent transition-colors uppercase tracking-widest flex items-center gap-2">
                    Contact Sales <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@include('arzavo.website.partials.footer')
@endsection