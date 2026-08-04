@extends('layouts.app')
@section('title', 'Features - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero --}}
<section class="relative pt-32 pb-20 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Platform Capabilities</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Everything you need to
                <span class="text-accent">run your institute.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Explore the complete ecosystem of tools designed to streamline every aspect of your educational institution — from admissions to analytics.
            </p>
        </div>
    </div>
</section>

{{-- Core Modules --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container">
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Core Modules</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">Powerful modules, one unified platform.</h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Each module is built to work independently or together, giving you complete flexibility in how you manage your institute.
            </p>
        </div>

        {{-- Feature Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Student Management - Featured --}}
            <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex items-start gap-5">
                    <div class="w-11 h-11 rounded-lg bg-accent/10 flex items-center justify-center text-accent shrink-0">
                        <i class="fa-solid fa-user-graduate text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-dark mb-2">Student Lifecycle Management</h3>
                        <p class="text-sm text-dark/60 leading-relaxed mb-4">
                            Manage everything from admission to graduation. Automated ID cards, real-time session tracking, batch management, and comprehensive student performance portfolios — all in one place.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-xs px-2.5 py-1 rounded bg-accent/5 text-accent/80 font-medium">Admissions</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-accent/5 text-accent/80 font-medium">Batches</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-accent/5 text-accent/80 font-medium">ID Cards</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-accent/5 text-accent/80 font-medium">Transfers</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attendance --}}
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-accent-secondary/10 flex items-center justify-center text-accent-secondary mb-5">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">Hybrid Attendance</h3>
                <p class="text-sm text-dark/60 leading-relaxed">Track attendance for online and offline batches. Real-time SMS/WhatsApp alerts for parents and detailed monthly reports.</p>
            </div>

            {{-- Billing --}}
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600 mb-5">
                    <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">Automated Billing & Fees</h3>
                <p class="text-sm text-dark/60 leading-relaxed">Generate invoices, set installment plans, track pending fees, and collect payments via UPI, Credit Cards, or Net Banking.</p>
            </div>

            {{-- Course Marketplace - Featured --}}
            <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex items-start gap-5">
                    <div class="w-11 h-11 rounded-lg bg-violet-500/10 flex items-center justify-center text-violet-600 shrink-0">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-dark mb-2">LMS & Course Marketplace</h3>
                        <p class="text-sm text-dark/60 leading-relaxed mb-4">
                            Host and sell recorded videos, PDFs, and live workshops. Built-in high-performance video player with DRM-level content protection. Students get a branded learning portal.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-xs px-2.5 py-1 rounded bg-violet-500/5 text-violet-600/80 font-medium">Video Courses</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-violet-500/5 text-violet-600/80 font-medium">DRM Protection</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-violet-500/5 text-violet-600/80 font-medium">Test Series</span>
                            <span class="text-xs px-2.5 py-1 rounded bg-violet-500/5 text-violet-600/80 font-medium">Certificates</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Staff --}}
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 mb-5">
                    <i class="fa-solid fa-users-gear text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">Staff & HR Governance</h3>
                <p class="text-sm text-dark/60 leading-relaxed">Manage salaries, leave, attendance, and roles. Grant precise permissions for distinct administrative layers.</p>
            </div>

            {{-- Results --}}
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center text-pink-600 mb-5">
                    <i class="fa-solid fa-square-poll-vertical text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">Exam & Result Engine</h3>
                <p class="text-sm text-dark/60 leading-relaxed">Create dynamic MCQ/subjective exams, grading systems, and automated report cards students can download instantly.</p>
            </div>

            {{-- Communication --}}
            <div class="rounded-lg border border-gray-200 bg-white p-7">
                <div class="w-10 h-10 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-600 mb-5">
                    <i class="fa-solid fa-bell text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-dark mb-2">Communication Hub</h3>
                <p class="text-sm text-dark/60 leading-relaxed">Send targeted notifications via WhatsApp, SMS, and in-app alerts. Auto-reminders for fee dues, exams, and attendance.</p>
            </div>

            {{-- Analytics - Full Width --}}
            <div class="lg:col-span-3 rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex flex-col md:flex-row items-start gap-5">
                    <div class="w-11 h-11 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-600 shrink-0">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-dark mb-2">Real-time Analytics Dashboard</h3>
                        <p class="text-sm text-dark/60 leading-relaxed">Monitor enrollment trends, revenue metrics, attendance patterns, and student performance through interactive, always-live dashboards. Make informed decisions backed by data — not guesswork.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- White-Label Section --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #fff 0%, #fffbf8 50%, #fff 100%);">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div >
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Your Brand, Your Platform</p>
                <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                    Complete white-label experience.
                </h2>
                <p class="text-dark/60 leading-relaxed text-lg mb-8">
                    Your students will never know Arzavo exists. Everything runs under your brand, your domain, and your design system.
                </p>
                <div class="space-y-3">
                    @php $wlFeatures = [
                        'Custom domain mapping (learn.yourinstitute.com)',
                        'Your logo, colors, and brand identity throughout',
                        'White-labeled student & teacher mobile portals',
                        'Custom email templates with your branding',
                        'Drag-and-drop website builder for your landing page',
                    ]; @endphp
                    @foreach($wlFeatures as $f)
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-check text-accent text-xs"></i>
                        <span class="text-sm text-dark/70">{{ $f }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="rounded-lg border border-gray-200 bg-white p-8">
                    <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-100">
                        <div class="w-8 h-8 rounded bg-accent flex items-center justify-center text-white text-xs font-semibold">Y</div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Your Academy</p>
                            <p class="text-xs text-dark/40">learn.youracademy.com</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="h-3 bg-gray-100 rounded w-full"></div>
                        <div class="h-3 bg-gray-100 rounded w-4/5"></div>
                        <div class="h-3 bg-gray-100 rounded w-3/5"></div>
                        <div class="flex gap-3 mt-6">
                            <div class="h-20 bg-accent/5 rounded flex-1 flex items-center justify-center text-accent/30">
                                <i class="fa-solid fa-play text-lg"></i>
                            </div>
                            <div class="h-20 bg-accent/5 rounded flex-1 flex items-center justify-center text-accent/30">
                                <i class="fa-solid fa-play text-lg"></i>
                            </div>
                            <div class="h-20 bg-accent/5 rounded flex-1 flex items-center justify-center text-accent/30">
                                <i class="fa-solid fa-play text-lg"></i>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded w-2/3 mt-4"></div>
                        <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
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
                <div class="w-14 h-14 mx-auto rounded-xl bg-white/15 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-bolt text-white text-xl"></i>
                </div>
                <h2 class="text-3xl md:text-5xl font-semibold text-white mb-5 leading-tight tracking-tight">
                    Everything you need, in one place.
                </h2>
                <p class="text-white/75 text-lg leading-relaxed mb-10">
                    Stop juggling multiple subscriptions. Bring your entire institution under one powerful, unified system today.
                </p>
                <a href="{{ route('register.form') }}"
                   class="px-8 py-3.5 bg-white text-accent font-semibold rounded text-sm inline-flex items-center gap-2 hover:opacity-90 transition-opacity">
                    Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                </a>
                <p class="text-white/50 text-xs mt-6">No credit card required · Setup in under 2 minutes</p>
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
