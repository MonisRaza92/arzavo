@extends('layouts.app')

@section('title', 'Features - Arzavo Educational Management Platform')

@section('content')
@include('arzavo.partials.navbar')

<!-- Page Header -->
<section class="pt-32 pb-20 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
    </div>
    <div class="container relative z-10 text-center">
        <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6 animate-fade-up">Capabilities</h2>
        <h1 class="text-5xl md:text-7xl font-black outfit-font tracking-tight mb-8 animate-fade-up stagger-1">
            Engineered for <br/>
            <span class="text-gradient-red">Excellence.</span>
        </h1>
        <p class="text-xl text-secondary font-medium max-w-2xl mx-auto animate-fade-up stagger-2">
            Explore the deep ecosystem of tools designed to make your educational institution thrive in the digital age.
        </p>
    </div>
</section>

<!-- Detailed Features Grid -->
<section class="py-24 bg-white relative">
    <div class="container">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1: Student Management -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-invert transition-all">
                    <i class="fa-solid fa-user-graduate text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Student Lifecycle</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Manage everything from admission to graduation. Automated ID cards, session tracking, and student performance portfolios.
                </p>
            </div>

            <!-- Feature 2: Smart Attendance -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent-secondary group-hover:text-invert transition-all">
                    <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Hybrid Attendance</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Track attendance for online and offline batches. Real-time SMS alerts for parents and detailed monthly growth charts.
                </p>
            </div>

            <!-- Feature 3: Course Builder -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-invert transition-all">
                    <i class="fa-solid fa-layer-group text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Course Marketplace</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Host and sell your recorded videos, PDFs, and live workshops. Built-in video player with content protection.
                </p>
            </div>

            <!-- Feature 4: Financial Suite -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent-secondary group-hover:text-invert transition-all">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Automated Billing</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Generate invoices, track pending fees, and collect payments via UPI, Credit Cards, or Net Banking automatically.
                </p>
            </div>

            <!-- Feature 5: Staff & Payroll -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-invert transition-all">
                    <i class="fa-solid fa-users-gear text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Staff Governance</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Manage teacher salaries, attendance, and roles. Grant precise permissions for different administrative tasks.
                </p>
            </div>

            <!-- Feature 6: Exam & Results -->
            <div class="p-10 bg-tertiary/20 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:bg-accent-secondary group-hover:text-invert transition-all">
                    <i class="fa-solid fa-square-poll-vertical text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black outfit-font mb-4">Result Engine</h3>
                <p class="text-secondary font-medium leading-relaxed">
                    Create dynamic exams, grading systems, and automated report cards that students can download instantly.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 bg-invert text-invert relative overflow-hidden">
    <!-- Glow -->
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[120px]"></div>
    <div class="container relative z-10 text-center">
        <h2 class="text-4xl md:text-6xl font-black outfit-font mb-10">Everything you need, <br/> in one place.</h2>
        <a href="{{ route('register.form') }}" class="px-12 py-5 bg-accent text-invert text-lg font-black uppercase tracking-widest border-rounded-lg shadow-2xl hover-lift transition-all inline-block">
            Start Your Journey
        </a>
    </div>
</section>

@include('arzavo.partials.footer')
@endsection
