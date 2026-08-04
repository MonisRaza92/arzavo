@extends('layouts.app')
@section('title', 'Contact & Legal Notices - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Contact &
                <span class="text-accent">Legal Notices.</span>
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
            
            {{-- 1. Business Information --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Business Information</h3>
                <div class="bg-gray-50 border border-gray-100 p-5 rounded-lg text-sm space-y-2">
                    <p>Arzavo is a product of <strong>ARZAQ INSIGHTS</strong>.</p>
                    <p><strong>Business Type:</strong> Proprietorship</p>
                    <p><strong>Registered Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204, India</p>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. General Support --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. General Support</h3>
                <div class="space-y-1">
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Legal Notices --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Legal Notices</h3>
                <p>
                    All legal notices, claims, requests or formal communications relating to the Arzavo platform should be sent in writing to the registered address above or by email to <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a>. Where required, ARZAQ INSIGHTS may request additional information to verify the identity and authority of the sender.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Privacy Requests --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Privacy Requests</h3>
                <p>
                    Requests relating to access, correction, deletion, data portability or privacy concerns should include sufficient information to identify the relevant institution and account.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Copyright & Intellectual Property --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Copyright & Intellectual Property</h3>
                <p>
                    Copyright concerns, trademark complaints or intellectual property notices should clearly identify the protected work, the allegedly infringing material and supporting ownership information.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Security Reports --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Security Reports</h3>
                <p>
                    Potential security vulnerabilities should be reported responsibly to <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a>. Please do not publicly disclose vulnerabilities before allowing reasonable time for investigation and remediation.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Billing & Subscription --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Billing & Subscription</h3>
                <p>
                    Questions regarding subscriptions, invoices, payments, refunds or cancellations should reference the institution name, registered email address and payment reference where available.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Business Hours --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Business Hours</h3>
                <p>
                    Support response times may vary depending on the subscription plan, issue severity and operational requirements.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Governing Documents --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Governing Documents</h3>
                <p>
                    This page should be read together with the Privacy Policy, Terms of Service, Refund & Cancellation Policy, Cookie Policy, Security Policy, Data Processing Agreement and other legal documents published by Arzavo.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Changes --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Changes</h3>
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
