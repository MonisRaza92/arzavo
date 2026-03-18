@extends('layouts.app')

@section('title', 'Pricing Plans - Arzavo Educational Management Platform')

@section('content')
    @include('arzavo.partials.navbar')

    <!-- Page Header -->
    <section class="pt-32 pb-20 bg-primary relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none opacity-5">
            <div class="absolute -bottom-20 -left-20 w-[600px] h-[600px] bg-accent-secondary rounded-full blur-[140px]">
            </div>
        </div>
        <div class="container relative z-10 text-center">
            <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6 animate-fade-up">Investment</h2>
            <h1 class="text-5xl md:text-7xl font-black outfit-font tracking-tight mb-8 animate-fade-up stagger-1">
                Predictable <br />
                <span class="text-gradient-red">Pricing.</span>
            </h1>
            <p class="text-xl text-secondary font-medium max-w-2xl mx-auto animate-fade-up stagger-2">
                No hidden fees. Scale your institution with confidence on a platform built for growth.
            </p>
        </div>
    </section>

    <!-- Pricing Grid (V3) -->
    <section class="py-24 bg-white relative">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

                @foreach($plans as $plan)
                        <div class="relative group bg-tertiary/20 p-10 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col
                                            {{ $plan->is_popular ? 'bg-invert text-white scale-105 shadow-premium z-10' : '' }}">

                            <!-- POPULAR -->
                            @if($plan->is_popular)
                                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/20 rounded-full -mr-16 -mt-16 animate-pulse">
                                </div>

                                <div
                                    class="inline-block bg-accent text-invert px-4 py-1 text-[10px] font-black uppercase tracking-tighter border-rounded-full mb-6">
                                    Most Popular
                                </div>
                            @endif

                            <!-- HEADER -->
                            <div class="mb-10 relative z-10">
                                <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">
                                    {{ $plan->name }}
                                </h3>

                                <div class="text-5xl font-black mb-4 outfit-font">
                                    {{ $plan->monthly_price == 0 ? 'Free' : '₹' . $plan->monthly_price }}
                                </div>

                                <p class="text-sm font-medium">
                                    {{ $plan->short_description }}
                                </p>
                            </div>

                            <!-- FEATURES -->
                            <ul class="space-y-5 mb-12 flex-grow font-medium">

                                @foreach($plan->features ?? [] as $key => $value)
                                    @if($value)
                                        <li class="flex items-center gap-3 text-sm">
                                            <i class="fa-solid fa-circle-check text-accent scale-125"></i>
                                            <span class="font-bold">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </span>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- LIMITS -->
                                @foreach($plan->limits ?? [] as $key => $value)
                                    <li class="flex items-center gap-3 text-sm">
                                        <i class="fa-solid fa-database text-accent-secondary scale-125"></i>
                                        <span class="font-bold">
                                            {{ $value }} {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </span>
                                    </li>
                                @endforeach

                            </ul>

                            <!-- CTA -->
                            <a href="{{ route('register.form') }}" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs 
                                                {{ $plan->is_popular
                    ? 'bg-accent text-invert shadow-xl'
                    : 'border-primary border-rounded-lg group-hover:bg-invert group-hover:text-invert' }}">

                                {{ $plan->monthly_price == 0 ? 'Get Started' : 'Choose Plan' }}
                            </a>

                        </div>
                @endforeach

            </div>
        </div>
    </section>

    @php
        $allFeatures = [];

        foreach ($plans as $plan) {
            foreach (($plan->features ?? []) as $key => $val) {
                $allFeatures[$key] = true;
            }
        }

        $allFeatures = array_keys($allFeatures);
    @endphp

    <!-- Comparison Table (V3) -->
    <section class="py-24 bg-tertiary/10">
        <div class="container">
            <h3 class="text-4xl font-black outfit-font text-center mb-16">Compare Features</h3>
            <div class="max-w-4xl mx-auto bg-white border-rounded-xl shadow-xl overflow-hidden overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="p-6 text-xs font-black uppercase">Feature</th>

                            @foreach($plans as $plan)
                                <th class="p-6 text-xs text-center">
                                    {{ $plan->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-primary/10">

                        @foreach($allFeatures as $feature)
                            <tr>
                                <td class="p-6 text-sm font-bold text-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                </td>

                                @foreach($plans as $plan)
                                    <td class="p-6 text-center">
                                        @if(($plan->features[$feature] ?? false))
                                            <span class="text-accent">✅</span>
                                        @else
                                            <span class="text-tertiary">❌</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('arzavo.partials.footer')
@endsection