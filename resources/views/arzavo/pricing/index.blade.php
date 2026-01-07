@extends('layouts.app')

@section('title', 'Pricing Plans - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.partials.navbar')

<!-- Page Header -->
<section class="pt-32 pb-20 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <div class="absolute -bottom-20 -left-20 w-[600px] h-[600px] bg-accent-secondary rounded-full blur-[140px]"></div>
    </div>
    <div class="container relative z-10 text-center">
        <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6 animate-fade-up">Investment</h2>
        <h1 class="text-5xl md:text-7xl font-black outfit-font tracking-tight mb-8 animate-fade-up stagger-1">
            Predictable <br/>
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
            <!-- Free / Tenant Plan -->
            <div class="group bg-tertiary/20 p-10 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">Community</h3>
                    <div class="text-5xl font-black mb-4 outfit-font text-primary">Free</div>
                    <p class="text-sm text-secondary font-medium">For visionary starters.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow">
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>tenant.arzavo.com</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Arzavo Branding included</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Up to 50 Core Students</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs border-primary border-rounded-lg group-hover:bg-invert group-hover:text-invert transition-all duration-500">
                    Get Started
                </a>
            </div>

            <!-- Pro Plan -->
            <div class="relative group bg-invert p-12 border-rounded-xl shadow-premium flex flex-col transform lg:scale-105 z-10 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/20 rounded-full -mr-16 -mt-16 animate-pulse"></div>
                
                <div class="mb-10 relative z-10">
                    <div class="inline-block bg-accent text-invert px-4 py-1 text-[10px] font-black uppercase tracking-tighter border-rounded-full mb-6">
                        Most Popular
                    </div>
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">Professional</h3>
                    <div class="text-5xl font-black mb-4 outfit-font text-invert">Premium</div>
                    <p class="text-sm text-tertiary font-medium">Host on your terms.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow text-invert relative z-10 font-medium">
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Custom Domain Support</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Zero Arzavo Branding</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Unlimited Global Scaling</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">24/7 Priority Concierge</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs bg-accent text-invert border-rounded-lg hover:bg-accent-secondary transition-all duration-500 relative z-10 shadow-xl">
                    Upgrade Now
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="group bg-tertiary/20 p-10 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">SaaS Hub</h3>
                    <div class="text-5xl font-black mb-4 outfit-font text-primary">Custom</div>
                    <p class="text-sm text-secondary font-medium">Full white-label logistics.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow">
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>White-label Solution</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Multi-Branch Analytics</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Dedicated Success Partner</span>
                    </li>
                </ul>

                <a href="#contact" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs border-primary border-rounded-lg group-hover:bg-invert group-hover:text-invert transition-all duration-500">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Comparison Table (V3) -->
<section class="py-24 bg-tertiary/10">
    <div class="container">
        <h3 class="text-4xl font-black outfit-font text-center mb-16">Compare Features</h3>
        <div class="max-w-4xl mx-auto bg-white border-rounded-xl shadow-xl overflow-hidden overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-primary/5">
                        <th class="p-6 text-xs font-black uppercase tracking-widest">Capabilities</th>
                        <th class="p-6 text-xs font-black uppercase tracking-widest text-center">Community</th>
                        <th class="p-6 text-xs font-black uppercase tracking-widest text-center">Professional</th>
                        <th class="p-6 text-xs font-black uppercase tracking-widest text-center">SaaS Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/10">
                    <tr>
                        <td class="p-6 text-sm font-bold text-secondary">Custom Domain</td>
                        <td class="p-6 text-center text-tertiary">❌</td>
                        <td class="p-6 text-center text-accent">✅</td>
                        <td class="p-6 text-center text-accent">✅</td>
                    </tr>
                    <tr>
                        <td class="p-6 text-sm font-bold text-secondary">Attendance Engine</td>
                        <td class="p-6 text-center text-accent">✅</td>
                        <td class="p-6 text-center text-accent">✅</td>
                        <td class="p-6 text-center text-accent">✅</td>
                    </tr>
                    <tr>
                        <td class="p-6 text-sm font-bold text-secondary">White Labeling</td>
                        <td class="p-6 text-center text-tertiary">❌</td>
                        <td class="p-6 text-center text-tertiary">❌</td>
                        <td class="p-6 text-center text-accent">✅</td>
                    </tr>
                    <tr>
                        <td class="p-6 text-sm font-bold text-secondary">Priority Support</td>
                        <td class="p-6 text-center text-tertiary">Email</td>
                        <td class="p-6 text-center text-accent font-black">24/7</td>
                        <td class="p-6 text-center text-accent font-black">VIP</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

@include('arzavo.partials.footer')
@endsection
