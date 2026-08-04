@extends('layouts.app')
@section('title', 'Security Policy - Arzavo Educational Management Platform')
@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="relative pt-32 pb-16 flex items-center overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #fff8f8 50%, #fffdf5 100%);">
    <div class="container relative z-10">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3 animate-fade-in-down">Legal Documents</p>
            <h1 class="text-5xl md:text-7xl font-semibold text-dark tracking-tight mb-5 leading-tight animate-fade-in-up">
                Security
                <span class="text-accent">Policy.</span>
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
                    This Security Policy describes the administrative, technical and organizational measures implemented by <strong>ARZAQ INSIGHTS</strong> to protect the Arzavo platform and customer information.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 2. Infrastructure --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Infrastructure</h3>
                <p>
                    Arzavo is hosted on Amazon Web Services (AWS) infrastructure in the Mumbai (India) region. Infrastructure components are monitored and maintained to support availability, reliability and security.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 3. Tenant Isolation --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Tenant Isolation</h3>
                <p>
                    Each educational institution operates within its own isolated tenant database. Tenant data is logically separated to reduce the risk of unauthorized cross-tenant access.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 4. Access Control --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Access Control</h3>
                <p>
                    Access to administrative systems is restricted to authorized personnel with a legitimate business need. Authentication controls and permission management are used to limit access.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 5. Authentication --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Authentication</h3>
                <p>
                    Users are responsible for protecting their credentials. Institutions should assign appropriate roles and promptly remove access for users who no longer require it.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 6. Encryption --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">6. Encryption</h3>
                <p>
                    Industry-standard encryption is used where appropriate to protect data in transit. Sensitive information is protected using suitable security controls.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 7. Monitoring & Logging --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">7. Monitoring & Logging</h3>
                <p>
                    Security events, authentication activity and operational logs may be recorded to detect suspicious activity, investigate incidents and improve platform security.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 8. Backup & Disaster Recovery --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">8. Backup & Disaster Recovery</h3>
                <p>
                    Regular backups are maintained to support disaster recovery and business continuity. Backup retention and restoration follow internal operational procedures.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 9. Vulnerability Management --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">9. Vulnerability Management</h3>
                <p>
                    ARZAQ INSIGHTS may periodically review infrastructure, software dependencies and platform components to identify and remediate security vulnerabilities.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 10. Incident Response --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">10. Incident Response</h3>
                <p>
                    Suspected security incidents are investigated promptly. Where appropriate, affected customers may be notified in accordance with applicable law and operational requirements.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 11. Customer Responsibilities --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">11. Customer Responsibilities</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Use strong passwords.</li>
                    <li>Protect account credentials.</li>
                    <li>Assign user permissions responsibly.</li>
                    <li>Keep contact information up to date.</li>
                    <li>Report suspected security issues immediately.</li>
                </ul>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 12. Responsible Disclosure --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">12. Responsible Disclosure</h3>
                <p>
                    Security researchers who identify a potential vulnerability are encouraged to report it responsibly to <a href="mailto:support@arzavo.com" class="text-accent hover:underline">support@arzavo.com</a>. Please do not exploit or publicly disclose vulnerabilities before allowing reasonable time for investigation.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 13. Policy Updates --}}
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">13. Policy Updates</h3>
                <p>
                    This policy may be revised from time to time to reflect improvements in security practices or changes in legal requirements.
                </p>
            </div>

            <hr class="border-gray-100 my-8">

            {{-- 14. Contact --}}
            <div class="bg-accent/5 p-6 rounded border border-accent/10">
                <h3 class="text-lg font-semibold text-dark mb-4">14. Contact</h3>
                <p class="mb-4">For any security concerns or reporting, contact us:</p>
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
