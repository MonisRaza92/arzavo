@extends('layouts.app')
@section('title', 'Communication & Consent Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Communication
                <span class="text-accent">& Consent.</span>
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
                    This policy explains how Arzavo by <strong>ARZAQ INSIGHTS</strong> manages communications and consent for educational institutions using the platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Scope --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Scope</h3>
                <p>
                    This policy applies to institutions, administrators, teachers, staff, students, parents and other authorized users of Arzavo.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Communication Channels --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Communication Channels</h3>
                <p class="mb-4">Depending on enabled features, Arzavo may send or facilitate communications through:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Email</li>
                    <li>SMS</li>
                    <li>WhatsApp</li>
                    <li>Push Notifications</li>
                    <li>In-App Notifications</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Types of Communications --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Types of Communications</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Transactional notifications (logins, password resets, invoices, receipts).</li>
                    <li>Academic notifications (attendance, examinations, assignments, report cards).</li>
                    <li>Administrative notifications (announcements, fee reminders, timetable changes).</li>
                    <li>Service-related notices (maintenance, security alerts, policy updates).</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Consent --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Consent</h3>
                <p>
                    Each institution is responsible for obtaining any legally required consent from students, parents, guardians, staff or other recipients before sending communications through Arzavo. Where applicable, recipients should be provided with appropriate opt-in and opt-out mechanisms.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Institution Responsibilities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Institution Responsibilities</h3>
                <p>
                    Institutions are responsible for the accuracy of recipient contact information, message content, legal compliance, and appropriate use of communication features.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Delivery --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Delivery</h3>
                <p>
                    <strong>ARZAQ INSIGHTS</strong> cannot guarantee delivery of messages. Delivery depends on third-party providers, recipient devices, network availability and other external factors.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Communication Records --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Communication Records</h3>
                <p>
                    Communication logs may be retained for operational, troubleshooting, audit, fraud-prevention and legal compliance purposes in accordance with the Data Retention & Deletion Policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Prohibited Use --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Prohibited Use</h3>
                <p class="mb-4">Users are strictly prohibited from utilizing the platform communications systems for:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Spam or unsolicited bulk messages.</li>
                    <li>Fraudulent or deceptive communications.</li>
                    <li>Harassment or abusive messages.</li>
                    <li>Illegal or infringing content.</li>
                    <li>Messages that violate applicable laws or regulations.</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Third-Party Providers --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Third-Party Providers</h3>
                <p>
                    Message delivery may rely on third-party service providers such as email, SMS or WhatsApp infrastructure providers. Their services remain subject to their own terms and privacy policies.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Policy Updates</h3>
                <p>
                    This policy may be updated periodically. Continued use of Arzavo after updates constitutes acceptance of the revised policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">12. Contact</h3>
                <p class="mb-4">For any concerns regarding communication settings or consents, please contact us:</p>
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
