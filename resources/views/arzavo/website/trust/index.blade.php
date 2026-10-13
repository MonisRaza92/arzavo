@extends('layouts.arzavo.website')

@section('title', 'Trust & Institutional Compliance Center • Arzavo')
@section('description', 'Our institutional commitments to platform reliability, 99.9% uptime SLA, student privacy, and Indian regulatory compliance.')

@section('content')
@include('arzavo.website.partials.navbar')

{{-- Hero Section --}}
<section class="pt-32 pb-16 md:pt-40 md:pb-20 bg-secondary">
    <div class="container relative z-10 max-w-4xl text-center mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-4">
            <i class="fa-solid fa-shield-halved text-[10px]"></i>
            <span>Institutional Governance</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-semibold text-theme-text tracking-tight mb-5 leading-tight">
            Trust & Compliance Center.
        </h1>
        <p class="text-sm md:text-base text-theme-text-muted leading-relaxed max-w-2xl mx-auto font-normal">
            Arzavo is engineered to provide school boards, university chancellors, and coaching directors absolute confidence in reliability, data integrity, and compliance.
        </p>
    </div>
</section>

{{-- Core Pillars Grid --}}
<section class="py-16 bg-primary border-y border-secondary">
    <div class="container max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">99.9% Uptime Commitment</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        Redundant multi-AZ cloud architecture on AWS Mumbai ensures classes, exams, and attendance are accessible without disruption.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-success flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                    <span>All Systems Operational</span>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Tenant Data Isolation</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        Logical database partitioning ensures no institution's student records, grades, or revenue data are ever accessible to another.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-brand">
                    <span>Zero Co-Mingling Policy</span>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">DPDP Act 2023 Compliance</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        Adheres to the Digital Personal Data Protection Act 2023 and the Information Technology SPDI Rules 2011 with full consent tracking.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-brand">
                    <span>Indian Data Sovereignty</span>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">100% Fee Sovereignty</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        All student fee collections settle directly into your institution's merchant accounts. Arzavo never holds or batches your revenue.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-brand">
                    <span>Zero Escrow Risk</span>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Granular Role RBAC</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        Administrative access is protected with Role-Based Access Control, audit logs, and two-factor authentication (2FA).
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-brand">
                    <span>Comprehensive Audit Trails</span>
                </div>
            </div>

            <div class="p-6 rounded-xl bg-secondary border border-secondary flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm mb-4">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Automated Cloud Backups</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal">
                        Daily encrypted database snapshots and media file replication prevent catastrophic data loss and ensure swift disaster recovery.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-secondary/60 text-[11px] font-semibold text-brand">
                    <span>24-Hour Recovery SLA</span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Institutional Legal & Compliance Directory --}}
<section class="py-16 md:py-20 bg-secondary">
    <div class="container max-w-4xl">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-semibold text-theme-text mb-2">Institutional Compliance Directory</h2>
            <p class="text-xs text-theme-text-muted">Review our formal policies, legal documentation, and data processing terms.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <a href="{{ route('privacy') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Privacy Policy</span>
                    <span class="text-[11px] text-theme-text-muted">Collection & processing safeguards</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>

            <a href="{{ route('terms') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Terms of Service</span>
                    <span class="text-[11px] text-theme-text-muted">Platform operating conditions</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>

            <a href="{{ route('security') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Security Architecture</span>
                    <span class="text-[11px] text-theme-text-muted">Encryption & isolation protocols</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>

            <a href="{{ route('student-privacy') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Student Data Privacy Pledge</span>
                    <span class="text-[11px] text-theme-text-muted">Protection for minor learners</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>

            <a href="{{ route('dpa') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Data Processing Agreement (DPA)</span>
                    <span class="text-[11px] text-theme-text-muted">Data fiduciary & processor roles</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>

            <a href="{{ route('subprocessors') }}" class="p-4 rounded-xl bg-primary border border-secondary hover:border-brand/40 transition flex items-center justify-between group">
                <div>
                    <span class="font-semibold text-theme-text block group-hover:text-brand transition">Approved Sub-processors</span>
                    <span class="text-[11px] text-theme-text-muted">Cloud infrastructure partners</span>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-theme-text-muted group-hover:text-brand transition"></i>
            </a>
        </div>
    </div>
</section>

@include('arzavo.website.partials.footer')
@endsection
