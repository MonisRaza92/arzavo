@extends('layouts.app')
@section('title', 'Terms of Service - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Terms of
                <span class="text-accent">Service.</span>
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
            
            {{-- Intro --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Introduction</h3>
                <p>
                    These Terms of Service ('Terms') govern the use of Arzavo, a software platform operated by <strong>ARZAQ INSIGHTS</strong>, a proprietorship business located at 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204, India. By creating an account or using Arzavo, you agree to these Terms.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Eligibility --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Eligibility</h3>
                <p>
                    Arzavo is intended for educational institutions. Institutions may create and manage administrator, teacher, staff, parent and student accounts. Each institution determines whether student self-registration is permitted or requires approval.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Services --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Services</h3>
                <p>
                    Arzavo provides education management services including admissions, academics, attendance, examinations, fee management, communication, learning management and related services.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Subscription Plans --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Subscription Plans</h3>
                <p>
                    Arzavo may offer a limited free plan and paid monthly, 3-month, 6-month and yearly subscriptions. Features may vary by plan.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Billing and Refunds --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Billing and Refunds</h3>
                <p>
                    Payments are due in advance. Refund requests may be considered only within three (3) calendar days of payment, subject to the Refund & Cancellation Policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Data Ownership --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Data Ownership</h3>
                <p>
                    The institution remains the owner of its data. Each tenant operates on an isolated database designed to prevent access by other tenants.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Account Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Account Deletion</h3>
                <p>
                    When a tenant requests deletion, operational data may be removed. Certain financial, billing, invoice, tax, fraud-prevention and legally required records may be retained as required by law or legitimate business obligations.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Acceptable Use --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Acceptable Use</h3>
                <p>
                    Users must not upload illegal content, attempt unauthorized access, distribute malware, infringe intellectual property, interfere with platform operations, or use Arzavo for unlawful purposes.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Service Availability --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Service Availability</h3>
                <p>
                    Arzavo aims to provide reliable service but does not guarantee uninterrupted availability. Maintenance, upgrades and force majeure events may affect availability.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Limitation of Liability --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Limitation of Liability</h3>
                <p>
                    To the maximum extent permitted by Indian law, ARZAQ INSIGHTS shall not be liable for indirect, incidental, consequential or special damages arising from use of the platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Governing Law --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Governing Law</h3>
                <p>
                    These Terms are governed by the laws of India. Jurisdiction shall lie with the competent courts determined by ARZAQ INSIGHTS unless otherwise required by law.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">12. Contact</h3>
                <p class="mb-4">For any questions or support regarding these Terms, contact us:</p>
                <div class="space-y-2 text-sm">
                    <p><strong>ARZAQ INSIGHTS</strong></p>
                    <p><strong>Address:</strong> 208/10 Musapur, Sandila, Hardoi, Uttar Pradesh 241204</p>
                    <p><strong>Email:</strong> <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a></p>
                    <p><strong>Phone:</strong> +91 8090492602</p>
                </div>
            </div>

            <div class="text-xs text-dark/40 pt-4">
                Note: This document is a strong starting draft but should be reviewed by a qualified lawyer before production use.
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
