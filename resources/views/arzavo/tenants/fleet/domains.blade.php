@extends('layouts.arzavo.tenants')
@section('title', 'Domains & DNS Fleet • ' . config('app.name'))

@section('content')
@php
    $totalInstitutes = $tenants->count();
    $customDomainsCount = $tenants->whereNotNull('custom_domain')->where('custom_domain', '!=', '')->count();
    $verifiedCount = $tenants->filter(function($t) {
        return !empty($t->custom_domain) && ($t->custom_domain_verified ?? false);
    })->count();
@endphp

<div class="space-y-6" x-data="{
    copiedText: null,
    copy(text) {
        navigator.clipboard.writeText(text);
        this.copiedText = text;
        setTimeout(() => { this.copiedText = null; }, 2000);
    }
}">

    <!-- 1. HEADER & FLEET METRICS (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-theme-text text-base shrink-0">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5 flex-wrap">
                        <span>Domains & DNS Hub</span>
                        <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted shrink-0">
                            Fleet Routing
                        </span>
                    </h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        Manage custom domain mapping, dedicated SSL certificates, and DNS records across all campus portals.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('tenants.index') }}"
                   class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>All Institutes</span>
                </a>
                <a href="{{ route('tenants.create') }}"
                   class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>Add Institute</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards (bg-secondary, rounded-lg, flat) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Total Portals</span>
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $totalInstitutes }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Active multi-tenant instances</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Subdomains Active</span>
                    <i class="fa-solid fa-link"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $totalInstitutes }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">*.{{ config('app.domain') }} native routes</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Custom Domains</span>
                    <i class="fa-solid fa-at"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $customDomainsCount }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Branded domains mapped</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Dedicated SSL</span>
                    <i class="fa-solid fa-shield-halved text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">100%</div>
                <p class="text-[10px] text-success font-normal">Automated TLS encryption</p>
            </div>
        </div>
    </div>

    <!-- 2. DNS SERVER CONFIGURATION REFERENCE CARD -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-theme-text-muted"></i>
                    <span>Global DNS Records for Custom Domains</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Point your domain from GoDaddy, Namecheap, or Cloudflare using these server parameters:
                </p>
            </div>
            <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted self-start md:self-auto shrink-0">
                CNAME / Hostname Routing
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- CNAME / ALIAS Record -->
            <div class="bg-secondary rounded-lg p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Apex / Root (CNAME / ALIAS)</span>
                    <div class="text-xs font-semibold text-theme-text font-mono mt-0.5">@ → {{ $cnameTarget }}</div>
                </div>
                <button @click="copy('{{ $cnameTarget }}')"
                        class="px-3 py-1.5 rounded-full bg-primary hover:bg-brand hover:text-primary text-theme-text text-xs transition font-normal"
                        title="Copy CNAME Target">
                    <span x-show="copiedText !== '{{ $cnameTarget }}'"><i class="fa-regular fa-copy"></i></span>
                    <span x-show="copiedText === '{{ $cnameTarget }}'" class="text-success"><i class="fa-solid fa-check"></i></span>
                </button>
            </div>

            <!-- CNAME www Record -->
            <div class="bg-secondary rounded-lg p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Subdomain / WWW (CNAME)</span>
                    <div class="text-xs font-semibold text-theme-text font-mono mt-0.5">www → {{ $cnameTarget }}</div>
                </div>
                <button @click="copy('{{ $cnameTarget }}')"
                        class="px-3 py-1.5 rounded-full bg-primary hover:bg-brand hover:text-primary text-theme-text text-xs transition font-normal"
                        title="Copy CNAME">
                    <span x-show="copiedText !== '{{ $cnameTarget }}'"><i class="fa-regular fa-copy"></i></span>
                    <span x-show="copiedText === '{{ $cnameTarget }}'" class="text-success"><i class="fa-solid fa-check"></i></span>
                </button>
            </div>

            <!-- Verification TXT Record -->
            @php $verifyTarget = env('VERIFY_DOMAIN_TARGET', 'verify.arzavo.com'); @endphp
            <div class="bg-secondary rounded-lg p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-theme-text-muted font-normal">Ownership Verification (TXT)</span>
                    <div class="text-xs font-semibold text-theme-text font-mono mt-0.5">@ → {{ $verifyTarget }}</div>
                </div>
                <button @click="copy('{{ $verifyTarget }}')"
                        class="px-3 py-1.5 rounded-full bg-primary hover:bg-brand hover:text-primary text-theme-text text-xs transition font-normal"
                        title="Copy Verification TXT Target">
                    <span x-show="copiedText !== '{{ $verifyTarget }}'"><i class="fa-regular fa-copy"></i></span>
                    <span x-show="copiedText === '{{ $verifyTarget }}'" class="text-success"><i class="fa-solid fa-check"></i></span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. DOMAINS DIRECTORY (List of clean cards per campus) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-route text-theme-text-muted"></i>
                    <span>Campus Domain Routes & Mapping</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Individual DNS routes, SSL certificates, and custom domain verification status.
                </p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal shrink-0">
                {{ $totalInstitutes }} {{ Str::plural('Portal', $totalInstitutes) }}
            </span>
        </div>

        <!-- Campus Cards List -->
        @forelse($tenants as $tenant)
            @php
                $hasCustom = !empty($tenant->custom_domain);
                $isVerified = $hasCustom && ($tenant->custom_domain_verified ?? false);
                $primaryUrl = 'http://' . $tenant->subdomain;
                $customUrl = $hasCustom ? 'http://' . $tenant->custom_domain : null;

                if ($tenant->isPendingDeletion()) {
                    $statusBadge = 'bg-warning-bg text-warning';
                    $statusLabel = 'Pending Deletion';
                } elseif ($tenant->status === 'active') {
                    $statusBadge = 'bg-success-bg text-success';
                    $statusLabel = 'Active';
                } else {
                    $statusBadge = 'bg-danger-bg text-danger';
                    $statusLabel = ucfirst($tenant->status);
                }
            @endphp
            <div x-show="search.trim() === '' || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain . ' ' . ($tenant->custom_domain ?? '')) }}'.includes(search.toLowerCase().trim())"
                 class="bg-primary rounded-lg p-5 transition space-y-4">

                <!-- Top Header Row -->
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-semibold text-theme-text truncate leading-snug">{{ $tenant->name }}</h3>
                                <span class="text-[11px] px-2.5 py-0.5 rounded-full font-normal inline-flex items-center gap-1.5 shrink-0 {{ $statusBadge }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-theme-text-muted mt-0.5 flex-wrap font-normal">
                                <a target="_blank" href="{{ $primaryUrl }}" class="hover:underline flex items-center gap-1 font-mono text-theme-text-muted hover:text-theme-text">
                                    <span>{{ $tenant->subdomain }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                                <span class="opacity-30">·</span>
                                <span>SSL: <span class="text-success font-normal">Dedicated Active</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('tenants.show', $tenant->subdomain) }}#domain"
                           class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-sliders text-xs text-theme-text-muted"></i>
                            <span>Configure DNS</span>
                        </a>
                        <a target="_blank" href="{{ $primaryUrl }}"
                           class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                            <span>Open Portal</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Middle 4-Column Stat Boxes Grid (bg-secondary, rounded-lg) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-link text-[9px]"></i> PRIMARY SUBDOMAIN
                        </span>
                        <a target="_blank" href="{{ $primaryUrl }}" class="text-theme-text font-semibold block mt-1 truncate hover:text-brand font-mono">
                            {{ $tenant->subdomain }}
                        </a>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Native SSL route</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-at text-[9px]"></i> CUSTOM DOMAIN
                        </span>
                        @if($hasCustom)
                            <a target="_blank" href="{{ $customUrl }}" class="text-theme-text font-semibold block mt-1 truncate hover:text-brand font-mono">
                                {{ $tenant->custom_domain }}
                            </a>
                            <span class="text-[10px] {{ $isVerified ? 'text-success' : 'text-warning' }} block mt-0.5 truncate">
                                {{ $isVerified ? 'Verified & Active' : 'Pending DNS Records' }}
                            </span>
                        @else
                            <span class="text-theme-text font-normal block mt-1 truncate">None Configured</span>
                            <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Using native subdomain</span>
                        @endif
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-shield-halved text-[9px]"></i> SSL CERTIFICATE
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">Automated TLS 1.3</span>
                        <span class="text-[10px] text-success block mt-0.5 truncate">Encrypted & active</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-network-wired text-[9px]"></i> ROUTING STATUS
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">
                            {{ $tenant->status === 'active' ? 'Online' : ucfirst($tenant->status) }}
                        </span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Nginx reverse proxy</span>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-primary rounded-lg p-8 text-center text-xs text-theme-text-muted font-normal">
                No institutes found in your fleet.
            </div>
        @endforelse
    </div>

    <!-- 4. WHITE-LABELING ARCHITECTURAL GUIDANCE -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-theme-text text-sm shrink-0">
                <i class="fa-solid fa-paintbrush"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">White-Label DNS & Branding Separation</h3>
                <p class="text-xs text-theme-text-muted font-normal mt-1 leading-relaxed">
                    Arzavo operates on an isolated multi-tenant architecture. Once your custom domain points to the server IP, the institute's entire LMS ecosystem (student logins, certificates, course pages, fee invoices, teacher portals) will be completely branded under your custom domain.
                    All student-facing branding, logos, color schemes, and themes are managed from the institute's dedicated <span class="font-medium text-theme-text">Admin Panel</span> (<code class="bg-secondary px-1.5 py-0.5 rounded font-mono text-[11px]">yourdomain.com/admin</code>), keeping this central console focused solely on fleet cloud infrastructure.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
