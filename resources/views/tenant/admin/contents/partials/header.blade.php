{{-- Page Header Banner (Curriculum Architecture Standard) --}}
<div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
            <i class="fa-solid fa-photo-film"></i>
        </div>
        <div>
            <h1 class="text-base font-semibold text-theme-text leading-tight">
                Media &amp; Digital Content Library
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                Upload and manage video lectures, study PDFs, diagrams, and digital assets. Content is securely distributed and instantly accessible across student and teacher portals.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
        <button type="button"
                @click="openUploadModal()"
                class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
            <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
            <span>Upload Content</span>
        </button>
    </div>
</div>
