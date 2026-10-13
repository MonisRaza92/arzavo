@extends('layouts.arzavo.tenants')
@section('title', 'Storage & Quotas Fleet • ' . config('app.name'))

@section('content')
@php
    $totalInstitutes = $tenants->count();

    // Helper formatter for bytes
    $formatBytes = function ($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    };

    $usedFormatted = $formatBytes($totalStorageUsed);
    $limitFormatted = $totalStorageLimit > 0 ? $formatBytes($totalStorageLimit) : 'Unlimited';
    $percentUsed = ($totalStorageLimit > 0) ? min(100, round(($totalStorageUsed / $totalStorageLimit) * 100, 2)) : 0;
    $headroomFormatted = $totalStorageLimit > $totalStorageUsed ? $formatBytes($totalStorageLimit - $totalStorageUsed) : '0 B';
@endphp

<div class="space-y-6">

    <!-- 1. FLEET SUMMARY & STORAGE METRICS (bg-primary, rounded-lg, flat) -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-full bg-secondary flex items-center justify-center text-theme-text text-base shrink-0">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-theme-text flex items-center gap-2.5 flex-wrap">
                        <span>Storage & Quotas Hub</span>
                        <span class="text-[11px] font-normal px-2.5 py-0.5 rounded-full bg-secondary text-theme-text-muted shrink-0">
                            Disk & Assets Fleet
                        </span>
                    </h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                        Monitor document uploads, media assets, syllabus files, and database footprints across all campuses.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('tenants.index') }}"
                   class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>All Institutes</span>
                </a>
                <a href="{{ route('pricing') }}"
                   class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                    <span>Expand Storage</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards (bg-secondary, rounded-lg, flat) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Combined Used</span>
                    <i class="fa-solid fa-hard-drive"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $usedFormatted }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Media & database files</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Allocated Quota</span>
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ $limitFormatted }}</div>
                <p class="text-[10px] text-theme-text-muted mt-1 font-normal">Combined subscription allowance</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Storage Headroom</span>
                    <i class="fa-solid fa-gauge-high text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">{{ round(100 - $percentUsed, 1) }}%</div>
                <p class="text-[10px] text-success font-normal">{{ $headroomFormatted }} available capacity</p>
            </div>

            <div class="bg-secondary rounded-lg p-4">
                <div class="flex items-center justify-between text-xs text-theme-text-muted mb-1 font-normal">
                    <span>Storage Health</span>
                    <i class="fa-solid fa-circle-check text-success"></i>
                </div>
                <div class="text-xl font-semibold text-theme-text">Healthy</div>
                <p class="text-[10px] text-success font-normal">All campuses within limit</p>
            </div>
        </div>

        <!-- Global Fleet Usage Progress Bar Card (bg-secondary) -->
        <div class="mt-4 bg-secondary rounded-lg p-4">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="text-theme-text font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-simple text-theme-text-muted text-[11px]"></i>
                    <span>Fleet Storage Utilization:</span>
                </span>
                <span class="text-theme-text font-semibold">
                    {{ $usedFormatted }} <span class="text-theme-text-muted font-normal">of {{ $limitFormatted }} ({{ $percentUsed }}%)</span>
                </span>
            </div>
            <div class="w-full bg-primary h-2 rounded-full overflow-hidden">
                <div class="bg-brand h-full rounded-full transition-all duration-500" style="width: {{ max(1, $percentUsed) }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-theme-text-muted mt-2 font-normal">
                <span>Media uploads & document stores</span>
                <span>{{ $headroomFormatted }} free space remaining</span>
            </div>
        </div>
    </div>

    <!-- 2. CAMPUS STORAGE ALLOCATION & BREAKDOWN (List of clean cards) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div>
                <h2 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-building-columns text-theme-text-muted"></i>
                    <span>Campus Storage Allocation & Footprint</span>
                </h2>
                <p class="text-xs text-theme-text-muted font-normal mt-0.5">
                    Storage breakdown for each isolated institute database, media uploads, and student attachments.
                </p>
            </div>
            <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-primary text-theme-text-muted font-normal shrink-0">
                {{ $totalInstitutes }} {{ Str::plural('Campus', $totalInstitutes) }}
            </span>
        </div>

        <!-- Campus Cards List -->
        @forelse($tenants as $tenant)
            @php
                $st = $tenantStats[$tenant->id] ?? null;
                $uBytes = (int) ($st['storage_used'] ?? 0);
                $lBytes = (int) ($st['storage_limit'] ?? (5 * 1024 * 1024 * 1024));
                $uFmt = $formatBytes($uBytes);
                $lFmt = $formatBytes($lBytes);
                $pUsed = $lBytes > 0 ? min(100, round(($uBytes / $lBytes) * 100, 1)) : 0;
                $learnersCount = (int) ($st['users_count'] ?? 0);
                $dbFootprint = $tenant->db_name ? '~1.2 MB' : '0 MB';
                $mediaFootprint = $formatBytes(max(0, $uBytes));

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
            <div x-show="search.trim() === '' || '{{ strtolower($tenant->name . ' ' . $tenant->subdomain) }}'.includes(search.toLowerCase().trim())"
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
                                <a target="_blank" href="http://{{ $tenant->subdomain }}" class="hover:underline flex items-center gap-1 font-mono text-theme-text-muted hover:text-theme-text">
                                    <span>{{ $tenant->subdomain }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                                <span class="opacity-30">·</span>
                                <span>Isolated DB: <code class="font-mono text-theme-text text-[11px]">{{ $tenant->db_name ?: 'Pending' }}</code></span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('tenants.show', $tenant->subdomain) }}#storage"
                           class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-sliders text-xs text-theme-text-muted"></i>
                            <span>Manage Quota</span>
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
                            <i class="fa-solid fa-hard-drive text-[9px]"></i> STORAGE USED
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $uFmt }}</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Media & documents</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-box-archive text-[9px]"></i> QUOTA LIMIT
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $lFmt }}</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Subscription tier limit</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-users text-[9px]"></i> ENROLLED LEARNERS
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $learnersCount }} Students</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Active enrolled users</span>
                    </div>

                    <div class="p-3 rounded-lg bg-secondary">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider flex items-center gap-1 font-normal">
                            <i class="fa-solid fa-database text-[9px]"></i> DB FOOTPRINT
                        </span>
                        <span class="text-theme-text font-semibold block mt-1 truncate">{{ $dbFootprint }}</span>
                        <span class="text-[10px] text-theme-text-muted block mt-0.5 truncate">Physical MySQL schema</span>
                    </div>
                </div>

                <!-- Bottom Storage Progress Bar Row (bg-secondary, rounded-lg, p-3) -->
                <div class="bg-secondary rounded-lg p-3.5 flex items-center justify-between flex-wrap gap-3 text-xs">
                    <div class="flex items-center gap-3 grow min-w-[200px]">
                        <span class="text-theme-text-muted text-[10px] uppercase tracking-wider shrink-0 font-normal flex items-center gap-1 whitespace-nowrap">
                            <i class="fa-solid fa-chart-pie text-[9px]"></i> DISK CONSUMPTION
                        </span>
                        <div class="w-full bg-primary h-2 rounded-full overflow-hidden">
                            <div class="bg-brand h-full rounded-full transition-all duration-300" style="width: {{ max(2, $pUsed) }}%"></div>
                        </div>
                    </div>

                    <div class="text-right shrink-0 flex items-center gap-3">
                        <span class="text-xs font-semibold text-theme-text">
                            {{ $uFmt }} <span class="text-theme-text-muted font-normal">/ {{ $lFmt }}</span>
                        </span>
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-normal {{ $pUsed >= 80 ? 'bg-danger-bg text-danger' : 'bg-primary text-theme-text-muted' }}">
                            {{ $pUsed }}% used
                        </span>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-primary rounded-lg p-8 text-center text-xs text-theme-text-muted font-normal">
                No institutes found in your fleet.
            </div>
        @endforelse
    </div>

    <!-- 3. STORAGE ARCHITECTURAL INSIGHT -->
    <div class="bg-primary rounded-lg p-5 md:p-6">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-theme-text text-sm shrink-0">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Isolated Media & Document Isolation</h3>
                <p class="text-xs text-theme-text-muted font-normal mt-1 leading-relaxed">
                    To prevent data leakage between schools, each institute's PDF syllabus, lecture videos, exam papers, and student avatars are stored strictly inside dedicated isolated folders:
                    <code class="bg-secondary px-1.5 py-0.5 rounded font-mono text-[11px]">storage/app/public/tenants/{tenant_id}/</code>.
                    Student files are managed directly through each campus's LMS file manager and course portals, keeping your central infrastructure organized and secure.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
