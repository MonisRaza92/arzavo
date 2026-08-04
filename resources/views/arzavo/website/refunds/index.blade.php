@extends('layouts.app')
@section('title', 'Refund & Cancellation Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Refund &
                <span class="text-accent">Cancellation.</span>
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
            
            {{-- 1. Overview --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Overview</h3>
                <p>
                    This Refund & Cancellation Policy applies to all subscriptions and services offered by Arzavo by <strong>ARZAQ INSIGHTS</strong>.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Subscription Plans --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Subscription Plans</h3>
                <p>
                    Arzavo offers a limited Free Plan and paid subscription plans including Monthly, 3-Month, 6-Month and Yearly plans. Features vary by plan.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Free Plan --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Free Plan</h3>
                <p>
                    The Free Plan is provided 'as available' with feature and usage limitations. It does not create any entitlement to refunds or service credits.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Refund Eligibility --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Refund Eligibility</h3>
                <p>
                    Refund requests are accepted only within <strong>three (3) calendar days</strong> from the original successful payment. Requests received after this period are generally not eligible for a refund unless required by applicable law.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Non-Refundable Situations --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Non-Refundable Situations</h3>
                <p class="mb-4">Refunds will not be issued in the following scenarios:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Partial use of a subscription period.</li>
                    <li>Failure to use purchased features or resources.</li>
                    <li>Change of mind after the three (3) day refund window.</li>
                    <li>Suspension or termination of an account due to violation of the Terms of Service.</li>
                    <li>Downgrading to a lower pricing plan after the refund window.</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Cancellation --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Cancellation</h3>
                <p>
                    Customers may cancel renewal of their subscription at any time. Cancellation prevents future automatic billing renewals but does not automatically entitle the customer to a refund for the current active billing period.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Tenant Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Tenant Deletion</h3>
                <p>
                    If a tenant permanently deletes its account, operational data may be removed. Certain financial, invoice, payment, tax and legally required records may be retained to comply with legal, accounting and audit obligations.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Refund Process --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Refund Process</h3>
                <p class="mb-4">
                    To request a refund, contact <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a> within three (3) calendar days and provide your registered account details, payment reference and reason for the request.
                </p>
                <p>
                    Approved refunds will be processed to the original payment method where feasible, within standard banking processing times.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">9. Contact</h3>
                <p class="mb-4">For questions or concerns about this policy, or to manage your subscription renewals, please contact us:</p>
                <div class="space-y-2 text-sm">
                    <p><strong>ARZAQ INSIGHTS</strong></p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
                </div>
            </div>

            <div class="text-xs text-dark/40 pt-4">
                Note: This policy should be reviewed by legal counsel before production use.
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
