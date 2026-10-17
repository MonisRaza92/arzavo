{{-- Top KPI Metrics Bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Files -->
    <div class="rounded-lg bg-primary p-4 space-y-1">
        <span class="text-[11px] text-theme-text-muted font-normal block">Total Media Files</span>
        <div class="flex items-center justify-between">
            <span class="text-xl font-semibold text-theme-text" x-text="stats.total">
                {{ number_format($contents->count()) }}
            </span>
            <span class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-photo-film"></i>
            </span>
        </div>
        <span class="text-[10px] text-theme-text-muted">All uploaded media assets</span>
    </div>

    <!-- Video Lectures -->
    <div class="rounded-lg bg-primary p-4 space-y-1">
        <span class="text-[11px] text-theme-text-muted font-normal block">Video Lectures</span>
        <div class="flex items-center justify-between">
            <span class="text-xl font-semibold text-theme-text" x-text="stats.videos">
                {{ number_format($contents->where('type', 'video')->count()) }}
            </span>
            <span class="w-7 h-7 rounded-full bg-info-bg text-info flex items-center justify-center text-xs">
                <i class="fa-solid fa-video"></i>
            </span>
        </div>
        <span class="text-[10px] text-theme-text-muted">Lessons &amp; video tutorials</span>
    </div>

    <!-- Documents & PDFs -->
    <div class="rounded-lg bg-primary p-4 space-y-1">
        <span class="text-[11px] text-theme-text-muted font-normal block">Books &amp; Notes (PDF)</span>
        <div class="flex items-center justify-between">
            <span class="text-xl font-semibold text-theme-text" x-text="stats.pdfs">
                {{ number_format($contents->where('type', 'pdf')->count()) }}
            </span>
            <span class="w-7 h-7 rounded-full bg-danger-bg text-danger flex items-center justify-center text-xs">
                <i class="fa-solid fa-file-pdf"></i>
            </span>
        </div>
        <span class="text-[10px] text-theme-text-muted">E-books, syllabi &amp; slides</span>
    </div>

    <!-- Storage Used -->
    <div class="rounded-lg bg-primary p-4 space-y-1">
        <span class="text-[11px] text-theme-text-muted font-normal block">Storage Consumed</span>
        <div class="flex items-center justify-between">
            <span class="text-xl font-semibold text-theme-text" x-text="stats.storageFormatted">
                {{ formatSize($storageUsed) }}
            </span>
            <span class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-hard-drive"></i>
            </span>
        </div>
        <span class="text-[10px] text-theme-text-muted">Tenant cloud disk usage</span>
    </div>
</div>
