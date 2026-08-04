@extends('layouts.app')
@section('title', 'Trust Center - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Security & Transparency</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Trust
                <span class="text-accent">Center.</span>
            </h1>
            <p class="text-lg text-dark/60 leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Effective Date: 05/08/2026 · Last Updated: 05/08/2026
            </p>
        </div>
    </div>
</section>

{{-- Policy Content --}}
<section class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #fff 100%);">
    <div class="container max-w-4xl">
        <div class="rounded-lg border border-gray-200 bg-white p-8 md:p-12 text-dark/70 leading-relaxed text-sm space-y-6">
            
            {{-- About --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-3">About the Trust Center</h3>
                <p>
                    The Arzavo Trust Center provides transparency regarding security, privacy, reliability and compliance practices followed by <strong>ARZAQ INSIGHTS</strong> while operating the Arzavo platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- Grid of Pillars --}}
            <div class="grid md:grid-cols-2 gap-6 my-8">
                <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-5">
                    <h4 class="text-sm font-semibold text-dark mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-accent text-xs"></i> Security
                    </h4>
                    <p class="text-xs text-dark/60 leading-relaxed">
                        Arzavo is hosted on AWS infrastructure in the Mumbai region. Administrative access is restricted, tenant databases are isolated, and security controls are implemented to protect customer information.
                    </p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-5">
                    <h4 class="text-sm font-semibold text-dark mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-lock text-accent text-xs"></i> Privacy
                    </h4>
                    <p class="text-xs text-dark/60 leading-relaxed">
                        Educational institutions remain the owners of their data. Personal information is processed in accordance with the Privacy Policy, Terms of Service and Data Processing Agreement.
                    </p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-5">
                    <h4 class="text-sm font-semibold text-dark mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-accent text-xs"></i> Reliability
                    </h4>
                    <p class="text-xs text-dark/60 leading-relaxed">
                        We continuously monitor platform availability, perform maintenance, maintain backups and work to improve reliability. Planned maintenance may occasionally affect service availability.
                    </p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-5">
                    <h4 class="text-sm font-semibold text-dark mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-database text-accent text-xs"></i> Data Isolation
                    </h4>
                    <p class="text-xs text-dark/60 leading-relaxed">
                        Every tenant operates on an isolated database. Institution data is not intentionally shared with or accessible by other tenants.
                    </p>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- Pillars --}}
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-dark mb-3">Compliance</h3>
                    <p>
                        Arzavo is designed to support compliance with applicable Indian laws, including the <strong>Digital Personal Data Protection Act, 2023</strong>, while recognizing that each institution remains responsible for its own legal compliance obligations.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-dark mb-3">Third-Party Providers</h3>
                    <p>
                        We work with carefully selected providers for infrastructure, security, payments, communication and related operational services. Details are available in the <a href="{{ route('subprocessors') }}" class="text-accent hover:underline font-semibold">Third-Party Services & Subprocessors</a> document.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-dark mb-3">Incident Response</h3>
                    <p>
                        Security incidents are investigated promptly. Where required by law or appropriate under the circumstances, affected institutions may be notified without unreasonable delay.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-dark mb-3">Responsible Disclosure</h3>
                    <p>
                        Security researchers are encouraged to report vulnerabilities responsibly to <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a>. Please avoid exploiting vulnerabilities or accessing customer information.
                    </p>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- Document Library --}}
            <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg">
                <h3 class="text-base font-semibold text-dark mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-accent"></i> Document Library
                </h3>
                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('privacy') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Privacy Policy
                    </a>
                    <a href="{{ route('terms') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Terms of Service
                    </a>
                    <a href="{{ route('security') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Security Policy
                    </a>
                    <a href="{{ route('dpa') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Data Processing Agreement
                    </a>
                    <a href="{{ route('retention') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Data Retention & Deletion
                    </a>
                    <a href="{{ route('aup') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Acceptable Use Policy
                    </a>
                    <a href="{{ route('subprocessors') }}" class="flex items-center gap-2 text-dark/70 hover:text-accent font-semibold transition-colors">
                        <i class="fa-regular fa-file-lines text-dark/30"></i> Subprocessors
                    </a>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">Contact Information</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>ARZAQ INSIGHTS</strong></p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
                </div>
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
