@extends('layouts.app')
@section('title', 'Third-Party Services & Subprocessors - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Third-Party &
                <span class="text-accent">Subprocessors.</span>
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
            
            {{-- 1. Purpose --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Purpose</h3>
                <p>
                    This document identifies categories of third-party service providers ('Subprocessors') that ARZAQ INSIGHTS may use to operate and deliver the Arzavo platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Why We Use Third Parties --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Why We Use Third Parties</h3>
                <p>
                    Trusted service providers help us deliver secure, reliable and scalable services. Third parties receive only the information reasonably necessary to perform their services.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Current Categories of Subprocessors --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Current Categories of Subprocessors</h3>
                <div class="space-y-4">
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Cloud Infrastructure</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            <strong>Amazon Web Services (AWS):</strong> Hosting, storage, isolated database setups, networking, database backups, and disaster recovery services (Mumbai, India region).
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Content Delivery & Security</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            <strong>Cloudflare:</strong> DNS routing, content delivery networks (CDN), web application firewall protections, and DDoS mitigations.
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Payment Processing</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            <strong>Razorpay:</strong> Secure payment processing services, recurring subscription bills, payment gateway APIs, and compliance audits.
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Email Delivery</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            SMTP gateway infrastructure and transactional email sending systems.
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">SMS & Messaging</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            SMS gateway providers and WhatsApp Business API endpoints for sending student academic alerts.
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Authentication</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            Optional single sign-on (SSO) authentication services through Google and Microsoft identity frameworks.
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <h4 class="text-sm font-semibold text-dark mb-2">Analytics</h4>
                        <p class="text-sm text-dark/60 leading-relaxed">
                            System performance diagnostics, website error tracking tools, and analytical dashboards.
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Selection Criteria --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Selection Criteria</h3>
                <p>
                    ARZAQ INSIGHTS evaluates service providers based on security, reliability, legal compliance, technical capability and business requirements.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Data Shared --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Data Shared</h3>
                <p>
                    Only the minimum information necessary to provide the requested service is shared. Sensitive information is protected through contractual, technical and organizational safeguards where applicable.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. International Processing --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. International Processing</h3>
                <p>
                    Arzavo currently operates primarily in India. If a provider processes information outside India, appropriate safeguards will be implemented where required by applicable law.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Changes to Subprocessors --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Changes to Subprocessors</h3>
                <p>
                    We may add, replace or remove subprocessors as our services evolve. The latest version of this document will reflect material updates.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">8. Contact</h3>
                <p class="mb-4">For any questions regarding subprocessors or third-party service listings, please contact us:</p>
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
