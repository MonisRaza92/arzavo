@extends('layouts.app')
@section('title', 'Acceptable Use Policy (AUP) - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Acceptable
                <span class="text-accent">Use Policy.</span>
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
                    This Acceptable Use Policy explains the permitted and prohibited uses of Arzavo by <strong>ARZAQ INSIGHTS</strong>. It applies to all institutions, administrators, teachers, staff, students, parents and other authorized users.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Permitted Use --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Permitted Use</h3>
                <p>
                    Arzavo may only be used for legitimate educational, administrative and institutional purposes in accordance with applicable law and these policies.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Prohibited Activities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Prohibited Activities</h3>
                <p class="mb-4">Users of the Platform are strictly prohibited from engaging in any of the following activities:</p>
                <ul class="list-disc pl-5 space-y-3">
                    <li>Uploading, publishing, or sharing any illegal, fraudulent, harmful, or deceptive content.</li>
                    <li>Attempting unauthorized access to any account, credentials, tenant, network, or server system.</li>
                    <li>Trying to inspect, download, or access another institution's isolated tenant database.</li>
                    <li>Uploading, transmitting, or distributing malware, ransomware, spyware, or other malicious code.</li>
                    <li>Sending spam, unsolicited bulk messages, or unauthorized commercial communications.</li>
                    <li>Using stolen or unauthorized payment methods, cards, or falsified identity parameters for subscriptions.</li>
                    <li>Infringing intellectual property, copyrights, trademarks, patents, or trade secrets of others.</li>
                    <li>Harassing, abusing, threatening, defaming, or violating the rights of other users or staff members.</li>
                    <li>Using the platform or integration webhooks to violate any local, state, or federal laws of India.</li>
                    <li>Reverse engineering, decompiling, or attempting to compromise the security controls of the Platform.</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Institution Responsibilities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Institution Responsibilities</h3>
                <p>
                    Each institution is responsible for managing its users, credentials, permissions, student self-registration configurations, tenant content, and verifying compliance with applicable regional data regulations.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Student & Staff Accounts --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Student & Staff Accounts</h3>
                <p>
                    Institutions determine whether student self-registration is enabled or requires administrator approval. Institutions are responsible for audit and lifecycle management of student/staff access within their tenant space.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Data Security --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Data Security</h3>
                <p>
                    Each tenant operates on a logically isolated database. Users must maintain secure passwords, avoid credential sharing, enable available authentication safeguards, and immediately report suspected unauthorized activities to Arzavo support.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Communications --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Communications</h3>
                <p>
                    Email, SMS, WhatsApp broadcasts, push notifications, and in-app notifications must only be used for lawful educational and administrative purposes. Users are responsible for obtaining any legally required consent before sending communications.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Enforcement --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Enforcement</h3>
                <p>
                    <strong>ARZAQ INSIGHTS</strong> may investigate suspected violations and may suspend, restrict or terminate accounts that violate this policy or applicable law.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Reporting Violations --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Reporting Violations</h3>
                <p>
                    Suspected abuse, security incidents, or violations should be reported promptly to <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a>.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Policy Updates</h3>
                <p>
                    This policy may be updated periodically. Continued use of Arzavo after updates constitutes acceptance of the revised policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">11. Contact</h3>
                <p class="mb-4">For any questions regarding this policy, contact us:</p>
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
