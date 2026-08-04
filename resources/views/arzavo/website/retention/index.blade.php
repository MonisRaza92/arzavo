@extends('layouts.app')
@section('title', 'Data Retention & Deletion Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Data Retention
                <span class="text-accent">& Deletion.</span>
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
                    This policy explains how Arzavo by <strong>ARZAQ INSIGHTS</strong> retains, archives and deletes data processed through the Arzavo platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Scope --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Scope</h3>
                <p>
                    This policy applies to all educational institutions (tenants), administrators, teachers, staff, students, parents and other authorized users using Arzavo.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Data Ownership --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Data Ownership</h3>
                <p>
                    Each institution remains the owner of its data. Every tenant operates on an isolated database designed to prevent access by other tenants.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Operational Data --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Operational Data</h3>
                <p>
                    Operational data such as admissions, attendance, academic records, communication logs, courses and learning records are retained while the tenant account remains active.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Tenant Account Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Tenant Account Deletion</h3>
                <p>
                    When an institution permanently deletes its tenant account, Arzavo will initiate deletion of operational data associated with that tenant, subject to technical processing time and legal obligations.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Records Retained --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Records Retained</h3>
                <p class="mb-4">
                    Certain records may be retained after tenant deletion where required for legitimate business or legal purposes, including:
                </p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Invoices and billing records</li>
                    <li>Payment transaction references</li>
                    <li>Tax and accounting records</li>
                    <li>Fraud prevention and security logs</li>
                    <li>Records required under applicable law</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Backups --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Backups</h3>
                <p>
                    Encrypted backups may temporarily contain deleted information until backup rotation cycles complete. Backup copies are not restored except for disaster recovery or business continuity purposes.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Legal Holds --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Legal Holds</h3>
                <p>
                    If required by law, court order, regulatory investigation or dispute resolution, relevant information may be preserved until the legal obligation ends.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Security --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Security</h3>
                <p>
                    Data is hosted using AWS infrastructure in the Mumbai region. Administrative access is restricted, and tenant databases are logically isolated.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. User Requests --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. User Requests</h3>
                <p>
                    Institutions may contact ARZAQ INSIGHTS to request clarification regarding data retention, deletion status or legal retention obligations.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Policy Updates</h3>
                <p>
                    This policy may be updated periodically. The latest version will be published on the Arzavo website.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">12. Contact</h3>
                <p class="mb-4">For any questions or support regarding data retention, please contact us:</p>
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
