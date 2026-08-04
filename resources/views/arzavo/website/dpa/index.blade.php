@extends('layouts.app')
@section('title', 'Data Processing Agreement (DPA) - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Data Processing
                <span class="text-accent">Agreement.</span>
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
            
            {{-- 1. Parties --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Parties</h3>
                <p>
                    This Data Processing Agreement ('DPA') forms part of the agreement between <strong>ARZAQ INSIGHTS</strong> ('Processor') and the educational institution using Arzavo ('Controller').
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Purpose --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Purpose</h3>
                <p>
                    The Processor processes personal data only to provide, maintain, secure and support the Arzavo platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Scope of Processing --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Scope of Processing</h3>
                <p>
                    Processing may include collection, storage, organization, retrieval, transmission, backup, deletion and other operations necessary to provide the Services.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Categories of Data --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Categories of Data</h3>
                <p class="mb-4">Data processed under this agreement includes details relating to:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Institution administrators</li>
                    <li>Teachers and staff</li>
                    <li>Students</li>
                    <li>Parents/guardians</li>
                    <li>Admissions and enquiry records</li>
                    <li>Attendance, examinations and academic records</li>
                    <li>Billing and payment information</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Controller Responsibilities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Controller Responsibilities</h3>
                <p>
                    The institution (Controller) is responsible for determining the purposes and lawful basis for processing personal data, obtaining required consents where applicable, and ensuring the accuracy of submitted information.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Processor Responsibilities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Processor Responsibilities</h3>
                <p>
                    <strong>ARZAQ INSIGHTS</strong> (Processor) will process personal data only on documented instructions from the Controller except where required by applicable law. Appropriate technical and organizational measures will be implemented to protect personal data.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Security Measures --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Security Measures</h3>
                <p>
                    Data is hosted on AWS infrastructure in the Mumbai region. Tenant databases are logically isolated. Access controls, logging and other reasonable security measures are used to protect customer information.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Subprocessors --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Subprocessors</h3>
                <p>
                    The Processor may use trusted third-party providers (such as cloud infrastructure, payment processors, communication providers and analytics providers) to deliver the Services. These providers are required to protect personal data appropriately.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Data Subject Requests --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Data Subject Requests</h3>
                <p>
                    Where technically feasible, <strong>ARZAQ INSIGHTS</strong> will reasonably assist the Controller in responding to lawful requests relating to access, correction or deletion of personal data.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Data Retention & Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Data Retention & Deletion</h3>
                <p>
                    Data retention and deletion are governed by the Arzavo Data Retention & Deletion Policy. Upon tenant deletion, operational data may be removed while legally required financial and compliance records may be retained.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Incident Notification --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Incident Notification</h3>
                <p>
                    Where appropriate and required by applicable law, the Processor will notify the Controller of confirmed personal data security incidents without unreasonable delay.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. International Transfers --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">12. International Transfers</h3>
                <p>
                    Arzavo currently operates in India. If international data transfers become necessary, appropriate safeguards will be implemented where required by applicable law.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 13. Governing Law --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">13. Governing Law</h3>
                <p>
                    This DPA is governed by the laws of India and should be interpreted together with the Terms of Service and Privacy Policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 14. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">14. Contact</h3>
                <p class="mb-4">For any inquiries related to data processing agreements, please contact us:</p>
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
