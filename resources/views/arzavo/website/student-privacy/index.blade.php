@extends('layouts.app')
@section('title', 'Student Data & Privacy Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Student Data
                <span class="text-accent">& Privacy.</span>
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
                    This policy explains how student and parent-related information is collected, processed, protected and managed within Arzavo by <strong>ARZAQ INSIGHTS</strong>.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Scope --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Scope</h3>
                <p>
                    This policy applies to educational institutions, students, parents or guardians, teachers, staff and administrators using Arzavo.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Student Data We Process --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Student Data We Process</h3>
                <p class="mb-4">Depending on the features enabled by your institution, we may process the following student information:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Basic profile information (name, avatar, date of birth)</li>
                    <li>Admission logs and active enrollment status records</li>
                    <li>Daily attendance logs (online and offline sessions)</li>
                    <li>Academic performance, grading, and examination results</li>
                    <li>Course participation records, assignments, and learning management progress</li>
                    <li>Fee schedules, outstanding balance logs, payment details, and invoices</li>
                    <li>Parent or guardian contact information (emails, phone numbers) where provided by the institution</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Role of the Institution --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Role of the Institution</h3>
                <p>
                    The educational institution acts as the controller of student information managed within its tenant. <strong>ARZAQ INSIGHTS</strong> processes such information only for providing and maintaining the Arzavo platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Student Registration --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Student Registration</h3>
                <p>
                    Each institution determines whether students may self-register or require administrative approval before accessing the platform.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Data Access --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Data Access</h3>
                <p>
                    Only authorized users within the institution may access student information according to roles and permissions configured by the institution.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Data Sharing --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Data Sharing</h3>
                <p>
                    Student information is not shared with other tenants. Each institution operates within an isolated database. Information may only be disclosed where required by law or with authorization from the institution.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Security --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Security</h3>
                <p>
                    Reasonable administrative, technical and organizational safeguards are implemented to protect student information. Institutions are responsible for managing user permissions and protecting account credentials.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Retention & Deletion --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Retention & Deletion</h3>
                <p>
                    Student records are retained while the institution maintains an active account. Upon tenant deletion, operational records may be deleted while certain financial or legally required records may be retained as described in the Data Retention & Deletion Policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Student & Parent Rights --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Student & Parent Rights</h3>
                <p>
                    Requests regarding correction, deletion or access to student information should generally be directed to the educational institution, which controls the data. <strong>ARZAQ INSIGHTS</strong> may assist institutions where technically appropriate.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Policy Updates</h3>
                <p>
                    This policy may be updated periodically. Continued use of Arzavo after publication of revisions constitutes acceptance of the updated policy.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">12. Contact</h3>
                <p class="mb-4">For any concerns regarding student privacy settings, please contact us:</p>
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
