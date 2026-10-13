@extends('layouts.arzavo.website')
@section('title', 'Contact & Legal Notices - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary"
         >
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-theme-text mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-theme-text tracking-tight mb-5 leading-tight animate-fade-in-up">
                Contact &
                <span class="text-theme-text">Legal Notices.</span>
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
            
            {{-- 1. Business Information --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">1. Business Information</h3>
                <div class="bg-gray-50 border border-gray-100 p-5 rounded-lg text-sm space-y-2">
                    <p>Arzavo is a product of <strong>ARZAQ INSIGHTS</strong>.</p>
                    <p><strong>Business Type:</strong> Proprietorship</p>
                    <p><strong>Registered Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204, India</p>
                </div>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 2. General Support --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">2. General Support</h3>
                <div class="space-y-1">
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-theme-text hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
                </div>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 3. Legal Notices --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">3. Legal Notices</h3>
                <p>
                    All legal notices, claims, requests or formal communications relating to the Arzavo platform should be sent in writing to the registered address above or by email to <a href="mailto:support@arzavo.com" class="text-theme-text hover:underline">support@arzavo.com</a>. Where required, ARZAQ INSIGHTS may request additional information to verify the identity and authority of the sender.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 4. Privacy Requests --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">4. Privacy Requests</h3>
                <p>
                    Requests relating to access, correction, deletion, data portability or privacy concerns should include sufficient information to identify the relevant institution and account.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 5. Copyright & Intellectual Property --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">5. Copyright & Intellectual Property</h3>
                <p>
                    Copyright concerns, trademark complaints or intellectual property notices should clearly identify the protected work, the allegedly infringing material and supporting ownership information.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 6. Security Reports --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">6. Security Reports</h3>
                <p>
                    Potential security vulnerabilities should be reported responsibly to <a href="mailto:support@arzavo.com" class="text-theme-text hover:underline">support@arzavo.com</a>. Please do not publicly disclose vulnerabilities before allowing reasonable time for investigation and remediation.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 7. Billing & Subscription --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">7. Billing & Subscription</h3>
                <p>
                    Questions regarding subscriptions, invoices, payments, refunds or cancellations should reference the institution name, registered email address and payment reference where available.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 8. Business Hours --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">8. Business Hours</h3>
                <p>
                    Support response times may vary depending on the subscription plan, issue severity and operational requirements.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 9. Governing Documents --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">9. Governing Documents</h3>
                <p>
                    This page should be read together with the Privacy Policy, Terms of Service, Refund & Cancellation Policy, Cookie Policy, Security Policy, Data Processing Agreement and other legal documents published by Arzavo.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 10. Changes --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">10. Changes</h3>
                <p>
                    ARZAQ INSIGHTS may update this page from time to time. The latest version will always be available on the Arzavo website.
                </p>
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
