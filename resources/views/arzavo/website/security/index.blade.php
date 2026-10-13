@extends('layouts.arzavo.website')

@section('title', 'Security Architecture & Institutional Data Protection • Arzavo')
@section('description', 'Comprehensive multi-tenant security architecture, AES-256 encryption, isolated database partitioning, and DPDP Act 2023 compliance standards for Arzavo.')

@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary">
    <div class="container relative z-10 max-w-4xl">
        <p class="text-xs font-semibold uppercase tracking-widest text-brand mb-3">Enterprise Infrastructure</p>
        <h1 class="text-4xl md:text-6xl font-semibold text-theme-text tracking-tight mb-5 leading-tight">
            Security & Data Protection.
        </h1>
        <p class="text-sm md:text-base text-theme-text-muted leading-relaxed max-w-2xl font-normal">
            How Arzavo shields your institution’s student records, academic intellectual property, and financial transactions through multi-tenant cloud isolation.
        </p>
    </div>
</section>

{{-- Policy Content --}}
<section class="py-12 bg-secondary">
    <div class="container max-w-4xl">
        <div class="bg-primary rounded-xl p-8 md:p-12 text-theme-text-muted leading-relaxed text-xs md:text-sm space-y-8 border border-secondary shadow-sm">
            
            <div class="p-4 rounded-lg bg-secondary text-theme-text border border-brand/20">
                <strong>Multi-Tenant Architecture Guarantee:</strong> Each educational institution on Arzavo operates within an isolated database scope. Student records, exam keys, and accounting ledgers from one school are never co-mingled or accessible by another.
            </div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">1. Multi-Tenant Architectural Isolation</h2>
                <p class="mb-3">
                    Arzavo enforces rigorous logical isolation between all tenant institutions. Every query executed across student admissions, examination results, library books, and fee collections is bound to the verified tenant identifier. Cross-tenant leakage is prevented at both the application middleware and database abstraction layers.
                </p>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">2. Cryptographic Encryption Standards</h2>
                <p class="mb-3">
                    Data protection is enforced at every layer of transmission and storage:
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-3">
                    <li><strong>Data in Transit:</strong> All HTTP traffic between web browsers, mobile devices, and our cloud cluster is strictly encrypted using Transport Layer Security (TLS 1.3 / TLS 1.2) with automated HSTS enforcement.</li>
                    <li><strong>Data at Rest:</strong> Persistent database storage, document uploads, and student media files are encrypted using Advanced Encryption Standard (AES-256) on AWS infrastructure.</li>
                    <li><strong>Credential Protection:</strong> Passwords are never stored in plaintext and are salted and hashed using bcrypt with adaptive cost factors.</li>
                </ul>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">3. Identity, OAuth & Session Hardening</h2>
                <p class="mb-3">
                    Arzavo integrates with Arzaq Central Identity via state-of-the-art OAuth 2.0 with Proof Key for Code Exchange (PKCE). Sessions are monitored per device, and any unauthorized concurrent access or account compromise can be terminated instantaneously by institutional administrators. Two-factor authentication (2FA) is supported across all administrative accounts.
                </p>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">4. Role-Based Access Control (RBAC)</h2>
                <p class="mb-3">
                    Arzavo provides granular permission boundaries for institutional stakeholders:
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-3">
                    <li><strong>Super Administrators:</strong> Full campus configuration, domain mapping, billing, and faculty provisioning.</li>
                    <li><strong>Teachers & Staff:</strong> Scoped access to assigned classes, attendance marking, study material uploads, and grading.</li>
                    <li><strong>Students & Parents:</strong> Strictly sandboxed access to individual course enrollments, attendance percentages, report cards, and fee payment receipts.</li>
                </ul>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">5. Automated Daily Backups & Disaster Recovery</h2>
                <p class="mb-3">
                    Database snapshots and document assets are backed up automatically on a 24-hour cycle to geographically replicated cloud vaults located within India. In the event of catastrophic data corruption or human error, institutional databases can be restored to a specified recovery point with minimal downtime.
                </p>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">6. Cloud Hosting & Indian Sovereignty</h2>
                <p class="mb-3">
                    All primary database instances, media processing clusters, and storage volumes are hosted in Amazon Web Services (AWS) Asia Pacific (Mumbai) Data Center, ensuring full compliance with the Digital Personal Data Protection Act 2023 (DPDP Act) and the Information Technology Act 2000.
                </p>
            </div>

            <div class="h-px bg-secondary"></div>

            <div>
                <h2 class="text-base md:text-lg font-semibold text-theme-text mb-3">7. Vulnerability Disclosure & Support</h2>
                <p class="mb-3">
                    If you identify a suspected vulnerability or security anomaly in the Arzavo platform, please immediately report it to our security incident team at <a href="mailto:security@arzavo.in" class="text-brand underline">security@arzavo.in</a>. All credible reports receive priority engineering triaging within 12 hours.
                </p>
            </div>

        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection
