{{-- ======================================================== --}}
{{-- 1. STATS SUMMARY CARDS (TENANTS DESIGN SYSTEM TOKENS)     --}}
{{-- ======================================================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Invoiced Fee Plans --}}
    <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
        <div>
            <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total Invoiced Fees</p>
            <p class="text-2xl font-semibold mt-1 text-theme-text">₹{{ number_format($totalPlanned, 2) }}</p>
            <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Active enrolled student liability</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
    </div>

    {{-- Verified Collections --}}
    <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
        <div>
            <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Verified Collections</p>
            <p class="text-2xl font-semibold mt-1 text-success">₹{{ number_format($totalCollected, 2) }}</p>
            <p class="text-[11px] text-success mt-0.5 font-normal flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $collectionRatio }}% recovery ratio
            </p>
        </div>
        <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
            <i class="fa-solid fa-circle-dollar-to-slot"></i>
        </div>
    </div>

    {{-- Outstanding Dues --}}
    <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
        <div>
            <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Cumulative Balance Due</p>
            <p class="text-2xl font-semibold mt-1 text-warning">₹{{ number_format($totalPending, 2) }}</p>
            <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Unpaid carried over cycles</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-warning-bg flex items-center justify-center text-warning text-lg">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
    </div>

    {{-- Active Structures & Hierarchy Rules --}}
    <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
        <div>
            <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Hierarchy Plans Active</p>
            <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $activeStructuresCount }} Plans</p>
            <p class="text-[11px] text-brand mt-0.5 font-normal">
                {{ $overrideCount }} Custom {{ Str::plural('Override', $overrideCount) }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
            <i class="fa-solid fa-sitemap"></i>
        </div>
    </div>
</div>
