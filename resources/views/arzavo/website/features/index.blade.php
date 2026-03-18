@extends('layouts.app')

@section('title', 'Features - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.website.partials.navbar')

<!-- Page Header -->
<section class="pt-32 pb-20 bg-slate-950 relative overflow-hidden min-h-[50vh] flex items-center">
    <!-- Sophisticated Background Glow -->
     <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-accent/10 rounded-full blur-[120px] pointer-events-none translate-x-1/3 -translate-y-1/3 animate-pulse"></div>
     <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none -translate-x-1/3 translate-y-1/3"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="max-w-4xl mx-auto reveal-on-scroll">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <i class="fa-solid fa-layer-group text-accent animate-[spin_4s_linear_infinite]"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Capabilities</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8 leading-tight">
                Engineered for <br class="hidden md:block"/>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent via-accent-secondary to-blue-500">Excellence.</span>
            </h1>
            <p class="text-xl text-slate-400 font-medium max-w-2xl mx-auto leading-relaxed">
                Explore the deep ecosystem of tools designed to make your educational institution thrive in the digital age.
            </p>
        </div>
    </div>
</section>

<!-- Detailed Features Bento Grid -->
<section class="py-24 bg-slate-900 relative">
    <!-- Grid Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0wIDBoNDB2NDBIMHoiIGZpbGw9Im5vbmUiLz4KPHBhdGggZD0iTTAgMGg0MHY0MEgwem0zOSAzOVYxaC0zOHYzOGgzOHoiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMSkiLz4KPC9zdmc+')] opacity-50 z-0 mask-image:linear-gradient(to_bottom,transparent,black,transparent)"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        
        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 auto-rows-[300px]">
            
            <!-- Feature 1: Student Management (Large Card) -->
            <div class="md:col-span-2 md:row-span-2 glass-panel-dark p-10 rounded-[2.5rem] border border-white/5 hover:border-accent/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-1 flex flex-col justify-end">
                <!-- Hover Glow -->
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-accent/20 rounded-full blur-[80px] group-hover:bg-accent/30 transition-colors duration-700"></div>
                
                <div class="absolute top-10 pr-10">
                     <div class="w-16 h-16 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/10 group-hover:scale-110 group-hover:bg-accent group-hover:border-accent transition-all duration-500 shadow-xl">
                        <i class="fa-solid fa-user-graduate text-3xl text-white"></i>
                    </div>
                </div>
                
                <div class="relative z-10 mt-auto">
                    <h3 class="text-3xl font-black text-white mb-4">Student Lifecycle <br/>Management</h3>
                    <p class="text-lg text-slate-400 font-medium leading-relaxed max-w-md group-hover:text-slate-300 transition-colors">
                        Manage everything from admission to graduation. Automated ID cards, real-time session tracking, and comprehensive student performance portfolios—all in one place.
                    </p>
                </div>
            </div>

            <!-- Feature 2: Smart Attendance (Standard Card) -->
            <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/5 hover:border-accent-secondary/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-2">
                 <div class="absolute inset-0 bg-linear-to-br from-accent-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                 
                 <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/10 group-hover:rotate-12 group-hover:bg-accent-secondary group-hover:border-accent-secondary transition-all duration-500 relative z-10">
                    <i class="fa-solid fa-clock-rotate-left text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 relative z-10">Hybrid <br/>Attendance</h3>
                <p class="text-slate-400 font-medium text-sm leading-relaxed relative z-10">
                    Track attendance for online and offline batches. Real-time SMS alerts for parents and detailed monthly growth charts.
                </p>
            </div>

            <!-- Feature 3: Financial Suite (Standard Card) -->
            <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/5 hover:border-blue-500/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-3">
                 <div class="absolute inset-0 bg-linear-to-bl from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                 
                 <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/10 group-hover:-rotate-12 group-hover:bg-blue-500 group-hover:border-blue-500 transition-all duration-500 relative z-10">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 relative z-10">Automated <br/>Billing</h3>
                <p class="text-slate-400 font-medium text-sm leading-relaxed relative z-10">
                    Generate invoices, track pending fees, and collect payments effortlessly via UPI, Credit Cards, or Net Banking.
                </p>
            </div>
            
            <!-- Feature 4: Course Builder (Wide Card) -->
            <div class="md:col-span-2 lg:col-span-2 glass-panel-dark p-8 md:p-10 rounded-[2.5rem] border border-white/5 hover:border-purple-500/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-4 flex flex-col justify-center">
                 <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-linear-to-r from-transparent to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                 
                 <div class="flex items-start gap-6 relative z-10">
                     <div class="w-16 h-16 min-w-16 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10 group-hover:scale-110 group-hover:bg-purple-500 group-hover:border-purple-500 transition-all duration-500 shadow-xl">
                        <i class="fa-solid fa-layer-group text-3xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-white mb-3">Course Marketplace</h3>
                        <p class="text-slate-400 font-medium leading-relaxed max-w-md">
                            Host and sell your recorded videos, PDFs, and live workshops. Built-in, high-performance video player with robust content protection.
                        </p>
                    </div>
                 </div>
            </div>

            <!-- Feature 5: Staff & Payroll (Standard Card) -->
            <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/5 hover:border-emerald-500/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-5">
                 <div class="absolute inset-0 bg-linear-to-t from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                 
                <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/10 group-hover:rotate-12 group-hover:bg-emerald-500 group-hover:border-emerald-500 transition-all duration-500 relative z-10">
                    <i class="fa-solid fa-users-gear text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 relative z-10">Staff <br/>Governance</h3>
                <p class="text-slate-400 font-medium text-sm leading-relaxed relative z-10">
                    Manage salaries, attendance, and roles. Grant precise, granular permissions for distinct administrative layers.
                </p>
            </div>
            
            <!-- Feature 6: Exam & Results (Standard Card) -->
            <div class="glass-panel-dark p-8 rounded-[2rem] border border-white/5 hover:border-pink-500/40 transition-all duration-500 group relative overflow-hidden reveal-on-scroll stagger-6">
                 <div class="absolute inset-0 bg-linear-to-bl from-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                 
                <div class="w-14 h-14 bg-white/5 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/10 group-hover:-rotate-12 group-hover:bg-pink-500 group-hover:border-pink-500 transition-all duration-500 relative z-10">
                    <i class="fa-solid fa-square-poll-vertical text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 relative z-10">Result <br/>Engine</h3>
                <p class="text-slate-400 font-medium text-sm leading-relaxed relative z-10">
                    Create dynamic exams, grading systems, and automated report cards that students can download instantly from anywhere.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-32 relative overflow-hidden bg-slate-950 border-t border-white/5">
    <!-- Animated background grid -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxjaXJjbGUgY3g9IjEiIGN5PSIxIiByPSIxIiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+Cjwvc3ZnPg==')] opacity-50 z-0 mask-image:linear-gradient(to_bottom,transparent,black,transparent)"></div>
    
    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="glass-panel p-12 md:p-24 rounded-[3rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-linear-to-r from-accent/20 via-blue-500/20 to-accent-secondary/20 rounded-full blur-[100px] opacity-50 group-hover:opacity-100 group-hover:scale-110 transition-all duration-1000"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <i class="fa-solid fa-bolt text-5xl text-accent mb-8 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]"></i>
                <h2 class="text-5xl md:text-7xl font-black text-white mb-8 tracking-tight">Everything you need, <br/> in one place.</h2>
                <p class="text-xl text-slate-300 font-medium mb-12 max-w-2xl mx-auto">
                    Stop juggling multiple subscriptions. Bring your entire institution under one powerful, unified system today.
                </p>
                <a href="{{ route('register.form') }}" class="px-12 py-5 bg-white text-slate-900 text-lg font-bold rounded-2xl shadow-[0_0_40px_rgba(255,255,255,0.2)] hover:scale-105 hover:shadow-[0_0_60px_rgba(255,255,255,0.3)] transition-all duration-300 inline-flex items-center gap-3 group/btn">
                    Start Your Journey <i class="fa-solid fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection
