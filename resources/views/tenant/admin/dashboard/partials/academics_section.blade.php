{{-- SECTION 4: ACADEMICS & CONTENT CATALOG --}}
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex bg-primary p-4 rounded-lg items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-brand text-sm"></i>
                Academics & Content Catalog
            </h2>
            <p class="text-[11px] text-theme-text-muted mt-0.5">
                Curriculum courses, published library titles, admission applications, and student inquiries
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.index') }}"
               class="px-3.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-xs text-theme-text font-normal transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-book-open text-[10px] text-brand"></i>
                <span>Courses</span>
            </a>
            <a href="{{ route('admin.books.index') }}"
               class="px-3.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-xs text-theme-text font-normal transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-book text-[10px] text-brand"></i>
                <span>Library</span>
            </a>
        </div>
    </div>

    {{-- Academics Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- 1. Curriculum Courses --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Courses Catalog</span>
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalCourses }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Curriculum courses & learning programs
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="font-medium text-success">{{ $publishedCourses }} Published</span>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Manage <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 2. Library & Books --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Library Titles</span>
                    <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalBooks }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Published books, ebooks & notes
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand"></span>
                    <span class="text-theme-text-muted text-[11px]">{{ $bookCategoriesCount }} Categories</span>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Catalog <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 3. Admissions Applications --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Admissions Pipeline</span>
                    <div class="w-8 h-8 rounded-full bg-sky-500/10 text-sky-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalAdmissions }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Student registration applications
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-2">
                    <span class="text-theme-text-muted text-[11px]">{{ $approvedAdmissions }} Approved</span>
                    <span class="text-theme-text-muted/60">·</span>
                    <span class="font-medium text-warning">{{ $pendingAdmissions }} In Review</span>
                </div>
                <a href="{{ route('admin.students.admissions') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Review <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 4. Inquiries & Leads --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Student Inquiries</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalInquiries }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Contact requests & admission leads
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <span class="text-theme-text-muted text-[11px]">Portal Inquiries</span>
                <a href="{{ route('admin.communication.inquiries') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Inbox <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

    </div>
</div>
