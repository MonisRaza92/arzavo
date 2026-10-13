@extends('layouts.arzavo.website')
@section('title', 'Data Retention & Deletion Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary"
         >
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-theme-text mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-theme-text tracking-tight mb-5 leading-tight animate-fade-in-up">
                Data Retention
                <span class="text-theme-text">& Deletion.</span>
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
                    This policy explains how Arzavo by <strong>ARZAQ INSIGHTS</strong> retains, archives and deletes data processed through the Arzavo platform.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 2. Scope --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">2. Scope</h3>
                <p>
                    This policy applies to all educational institutions (tenants), administrators, teachers, staff, students, parents and other authorized users using Arzavo.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 3. Data Ownership --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">3. Data Ownership</h3>
                <p>
                    Each institution remains the owner of its data. Every tenant operates on an isolated database designed to prevent access by other tenants.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 4. Operational Data --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">4. Operational Data</h3>
                <p>
                    Operational data such as admissions, attendance, academic records, communication logs, courses and learning records are retained while the tenant account remains active.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 5. Tenant Account Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">5. Tenant Account Deletion</h3>
                <p>
                    When an institution permanently deletes its tenant account, Arzavo will initiate deletion of operational data associated with that tenant, subject to technical processing time and legal obligations.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 6. Records Retained --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">6. Records Retained</h3>
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

            <div class="h-px bg-secondary my-6"></div>

            {{-- 7. Backups --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">7. Backups</h3>
                <p>
                    Encrypted backups may temporarily contain deleted information until backup rotation cycles complete. Backup copies are not restored except for disaster recovery or business continuity purposes.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 8. Legal Holds --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">8. Legal Holds</h3>
                <p>
                    If required by law, court order, regulatory investigation or dispute resolution, relevant information may be preserved until the legal obligation ends.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 9. Security --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">9. Security</h3>
                <p>
                    Data is hosted using AWS infrastructure in the Mumbai region. Administrative access is restricted, and tenant databases are logically isolated.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 10. User Requests --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">10. User Requests</h3>
                <p>
                    Institutions may contact ARZAQ INSIGHTS to request clarification regarding data retention, deletion status or legal retention obligations.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 11. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">11. Policy Updates</h3>
                <p>
                    This policy may be updated periodically. The latest version will be published on the Arzavo website.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 12. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-theme-text mb-4">12. Contact</h3>
                <p class="mb-4">For any questions or support regarding data retention, please contact us:</p>
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
