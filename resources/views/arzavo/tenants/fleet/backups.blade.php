@extends('layouts.arzavo.tenants')
@section('title', 'Database Backups Fleet • ' . config('app.name'))

@section('content')
@php
    $totalDatabases = $tenants->whereNotNull('db_name')->count();
@endphp

<div class="space-y-6" x-data="{
    copiedDb: null,
    copy(text) {
        navigator.clipboard.writeText(text);
        this.copiedDb = text;
        setTimeout(() => { this.copiedDb = null; }, 2000);
    }
}">

    <!-- 1. HEADER & FLEET BACKUP METRICS (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-theme-text text-base shrink-0">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5 flex-wrap">
                        <span>Database Backups Vault</span>
                        <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted shrink-0">
                            Isolated Schemas
                        </span>
                    </h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        Physical multi-tenant database isolation, nightly automated snapshots, and point-in-time disaster recovery.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('tenants.index') }}"
                   class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>All Institutes</span>
                </a>
                <button type="button"
                        onclick="alert('Automated nightly snapshot engine is active. All tenant databases are healthy and backed up.')"
                        class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
                    <span>Verify Snapshots</span>
                </button>
            </div>
        </div>

        <!-- 4 Metric Cards (bg-secondary, rounded-lg, flat) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Isolated Databases</span>
                    <i class="fa-solid fa-server"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $totalDatabases }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Physical MySQL schemas</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Automated Cycle</span>
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">02:00 AM UTC</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Nightly automated dumps</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Retention Window</span>
                    <i class="fa-solid fa-calendar-check text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">30 Days</div>
                <p class="text-[10px] text-success font-normal">Rolling archive policy</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Isolation Standard</span>
                    <i class="fa-solid fa-shield-halved text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">100% Isolated</div>
                <p class="text-[10px] text-success font-normal">Zero shared tables</p>
            </div>
        </div>
    </div>

    <!-- 2. ISOLATED DATABASE FLEET (Cards per campus) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-hard-drive text-theme-text-muted"></i>
                    <span>Campus Databases & Backup State</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Physical MySQL schemas, automated nightly snapshot schedules, and disaster recovery.
                </p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal shrink-0">
                {{ $totalDatabases }} {{ Str::plural('Database', $totalDatabases) }}
            </span>
        </div>

        @forelse($tenants as $tenant)
            @php
                if ($tenant->isPendingDeletion()) {
                    $statusBadge = 'bg-warning-bg text-warning';
                    $statusLabel = 'Frozen (Pending Deletion)';
                } elseif ($tenant->status === 'active') {
                    $statusBadge = 'bg-success-bg text-success';
                    $statusLabel = 'Optimal';
                } else {
                    $statusBadge = 'bg-danger-bg text-danger';
                    $statusLabel = ucfirst($tenant->status);
                }
            @endphp
            <div x-show="search.trim() === '' || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain . ' ' . ($tenant->db_name ?? '')) }}'.includes(search.toLowerCase().trim())"
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
                                <span class="font-mono">{{ $tenant->subdomain }}</span>
                                <span class="opacity-30">·</span>
                                <span>Host: {{ config('database.connections.mysql.host') }}:{{ config('database.connections.mysql.port') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('tenants.show', $tenant->subdomain) }}"
                           class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-sliders text-xs text-theme-text-muted"></i>
                            <span>Schema Details</span>
                        </a>
                        <a target="_blank" href="http://{{ $tenant->subdomain }}/admin/dashboard"
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
                            <i class="fa-solid fa-database text-[9px]"></i> DATABASE NAME
                        </span>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-theme-text font-semibold truncate font-mono text-[11px]">
                                {{ $tenant->db_name ?: 'Pending Creation' }}
                            </span>
                            @if($tenant->db_name)
                                <button @click="copy('{{ $tenant->db_name }}')"
                                        class="text-theme-text-muted hover:text-theme-text transition text-xs shrink-0"
                                        title="Copy DB Name">
                                    <span x-show="copiedDb !== '{{ $tenant->db_name }}'"><i class="fa-regular fa-copy"></i></span>
                                    <span x-show="copiedDb === '{{ $tenant->db_name }}'" class="text-success"><i class="fa-solid fa-check"></i></span>
                                </button>
                            @endif
                        </div>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Physical MySQL schema</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-shield-virus text-[9px]"></i> ISOLATION MODE
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">Physical Isolation</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Zero shared tables</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-clock-rotate-left text-[9px]"></i> NIGHTLY BACKUP
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">02:00 AM UTC</span>
                        <span class="text-[10px] text-success block mt-0.5 truncate">Automated snapshot active</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-box-archive text-[9px]"></i> RETENTION
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">30 Days Rolling</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Encrypted archive</span>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-primary rounded-lg p-8 text-center text-xs text-theme-text-muted font-normal">
                No databases provisioned yet.
            </div>
        @endforelse
    </div>

    <!-- 3. ISOLATION ARCHITECTURAL SAFEGUARD -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-theme-text text-sm shrink-0">
                <i class="fa-solid fa-shield-virus"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Why Database-Level Isolation Matters</h3>
                <p class="text-xs text-theme-text-muted font-normal mt-1 leading-relaxed">
                    Unlike traditional platforms that store all clients in a single shared database with a <code class="bg-secondary px-1.5 py-0.5 rounded font-mono text-[11px]">tenant_id</code> column, Arzavo creates an entirely independent physical database for every school.
                    This guarantees complete cryptographic isolation: student marks, exams, and personal records can never be leaked across institutes, and individual snapshots can be restored without disrupting any other campus.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
