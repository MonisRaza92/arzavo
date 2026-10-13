@extends('layouts.arzavo.website')
@section('title', 'Third-Party Services & Subprocessors - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary"
         >
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-theme-text mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-theme-text tracking-tight mb-5 leading-tight animate-fade-in-up">
                Third-Party &
                <span class="text-theme-text">Subprocessors.</span>
            </h1>
            <p class="text-lg text-theme-text-muted leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Effective Date: 05/08/2026 · Last Updated: 05/08/2026
            </p>
        </div>
    </div>
</section>

{{-- Policy Content --}}
<section class="py-12 bg-secondary"
         >
    <div class="container max-w-4xl">
        <div class="bg-primary rounded-lg p-8 md:p-12 text-theme-text-muted leading-relaxed text-sm space-y-6">
            
            {{-- 1. Purpose --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">1. Purpose</h3>
                <p>
                    This document identifies categories of third-party service providers ('Subprocessors') that ARZAQ INSIGHTS may use to operate and deliver the Arzavo platform.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 2. Why We Use Third Parties --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">2. Why We Use Third Parties</h3>
                <p>
                    Trusted service providers help us deliver secure, reliable and scalable services. Third parties receive only the information reasonably necessary to perform their services.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 3. Current Categories of Subprocessors --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">3. Current Categories of Subprocessors</h3>
                <div class="space-y-4">
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Cloud Infrastructure</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            <strong>Amazon Web Services (AWS):</strong> Hosting, storage, isolated database setups, networking, database backups, and disaster recovery services (Mumbai, India region).
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Content Delivery & Security</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            <strong>Cloudflare:</strong> DNS routing, content delivery networks (CDN), web application firewall protections, and DDoS mitigations.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Payment Processing</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            <strong>Razorpay:</strong> Secure payment processing services, recurring subscription bills, payment gateway APIs, and compliance audits.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Email Delivery</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            SMTP gateway infrastructure and transactional email sending systems.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">SMS & Messaging</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            SMS gateway providers and WhatsApp Business API endpoints for sending student academic alerts.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Authentication</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            Optional single sign-on (SSO) authentication services through Google and Microsoft identity frameworks.
                        </p>
                    </div>
                    <div class="bg-primary rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-theme-text mb-2">Analytics</h4>
                        <p class="text-sm text-theme-text-muted leading-relaxed">
                            System performance diagnostics, website error tracking tools, and analytical dashboards.
                        </p>
                    </div>
                </div>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 4. Selection Criteria --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">4. Selection Criteria</h3>
                <p>
                    ARZAQ INSIGHTS evaluates service providers based on security, reliability, legal compliance, technical capability and business requirements.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 5. Data Shared --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">5. Data Shared</h3>
                <p>
                    Only the minimum information necessary to provide the requested service is shared. Sensitive information is protected through contractual, technical and organizational safeguards where applicable.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 6. International Processing --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">6. International Processing</h3>
                <p>
                    Arzavo currently operates primarily in India. If a provider processes information outside India, appropriate safeguards will be implemented where required by applicable law.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 7. Changes to Subprocessors --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">7. Changes to Subprocessors</h3>
                <p>
                    We may add, replace or remove subprocessors as our services evolve. The latest version of this document will reflect material updates.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 8. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-theme-text mb-4">8. Contact</h3>
                <p class="mb-4">For any questions regarding subprocessors or third-party service listings, please contact us:</p>
                <div class="space-y-2 text-sm">
                    <p><strong>ARZAQ INSIGHTS</strong></p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-theme-text hover:underline">support@arzavo.com</a></p>
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
