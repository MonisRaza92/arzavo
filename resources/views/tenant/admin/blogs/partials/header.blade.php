{{-- Page Header Banner (Curriculum Architecture Standard) --}}
<div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
            <i class="fa-solid fa-blog"></i>
        </div>
        <div>
            <h1 class="text-base font-semibold text-theme-text leading-tight">
                Blogs, News &amp; Institute Stories
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                Publish insightful articles, academic announcements, student achievements, and campus updates for {{ app('currentTenant')->name ?? 'your institute' }}.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
        <a href="{{ route('admin.blog.create') }}"
           class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Write New Article</span>
        </a>
    </div>
</div>
