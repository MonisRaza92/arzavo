@extends('layouts.arzavo.website')
@section('title', 'Institution Data Ownership Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary"
         >
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-theme-text mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-theme-text tracking-tight mb-5 leading-tight animate-fade-in-up">
                Institution Data
                <span class="text-theme-text">Ownership.</span>
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
                    This policy explains data ownership, control and responsibilities for educational institutions using Arzavo by <strong>ARZAQ INSIGHTS</strong>.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 2. Ownership of Data --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">2. Ownership of Data</h3>
                <p>
                    Each educational institution ('Tenant') remains the sole owner of all data uploaded, created, collected or managed within its Arzavo workspace. <strong>ARZAQ INSIGHTS</strong> does not claim ownership of institution data.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 3. Types of Institution Data --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">3. Types of Institution Data</h3>
                <p class="mb-4">Institution data includes, but is not limited to, the following resources:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Student records and files</li>
                    <li>Teacher and staff profiles or contracts</li>
                    <li>Parent or guardian contact details</li>
                    <li>Admissions, enquiries, and lead data</li>
                    <li>Attendance logs and examination metrics</li>
                    <li>Fee structures, billing history, invoices, and transaction logs</li>
                    <li>Courses, learning content, lessons, notes, and study material</li>
                    <li>Uploaded documents, images, audio, video lectures, and private files</li>
                </ul>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 4. Tenant Isolation --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">4. Tenant Isolation</h3>
                <p>
                    Each tenant operates on an isolated database. Institution data is logically separated from every other tenant to reduce the risk of unauthorized cross-tenant access.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 5. Access Control --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">5. Access Control</h3>
                <p>
                    The institution determines who can access its data by assigning roles and permissions. Student self-registration or approval-based registration is configurable by the institution.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 6. Data Processing --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">6. Data Processing</h3>
                <p>
                    <strong>ARZAQ INSIGHTS</strong> processes institution data only to provide, maintain, secure and improve Arzavo or where required by applicable law.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 7. Data Export --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">7. Data Export</h3>
                <p>
                    Where supported by the platform, institutions may export their data in available formats. Export capabilities may vary depending on the subscription plan and feature availability.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 8. Account Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">8. Account Deletion</h3>
                <p>
                    Upon permanent tenant deletion, operational data will be scheduled for deletion. Certain financial, invoice, payment, audit and legally required records may be retained in accordance with legal, tax, accounting or fraud-prevention obligations.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 9. Confidentiality --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">9. Confidentiality</h3>
                <p>
                    <strong>ARZAQ INSIGHTS</strong> treats institution data as confidential and implements reasonable technical and organizational safeguards to protect it.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 10. Legal Disclosure --}}
            <div>
                <h3 class="text-lg font-semibold text-theme-text mb-4">10. Legal Disclosure</h3>
                <p>
                    Institution data may be disclosed only where required by law, valid legal process, regulatory requirements or to protect rights, safety or security.
                </p>
            </div>

            <div class="h-px bg-secondary my-6"></div>

            {{-- 11. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-theme-text mb-4">11. Contact</h3>
                <p class="mb-4">For questions regarding data ownership or configuration requests, please contact us:</p>
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
