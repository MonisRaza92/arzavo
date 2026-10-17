@extends('layouts.admin')
@section('title', 'Batches & Sections Management')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="batchesIndexComponent()" class="space-y-5">

    {{-- ======================================================== --}}
    {{-- 1. STANDARDIZED PAGE HEADER BANNER                       --}}
    {{-- ======================================================== --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Batches & Sections Management
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Configure classroom sections for schools and multi-subject batches for coaching centers. Assign head mentors, schedule periods with break calculations, and manage learner seat capacities.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.curriculum.board') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-[11px] text-brand"></i>
                <span>Curriculum Board</span>
            </a>
            <a href="{{ route('admin.batches.create') }}"
               class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Create Batch / Section</span>
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-lg bg-success-bg text-success text-xs flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-danger-bg text-danger text-xs flex items-center gap-2.5">
            <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- 2. METRICS / STATS SUMMARY CARDS                         --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Batches --}}
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total Batches</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $totalBatches }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Active & archived groups</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
        </div>

        {{-- Active Batches --}}
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Active Batches</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $activeBatches }}</p>
                <p class="text-[11px] text-success mt-0.5 font-normal flex items-center gap-1">
                    <i class="fa-solid fa-circle text-[6px]"></i> Open for student admissions
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        {{-- Assigned Faculty --}}
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Assigned Faculty</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $assignedTeachers }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Dedicated batch mentors</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>

        {{-- Enrolled Students --}}
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Enrolled Learners</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $totalBatchStudents }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Allocated to batches</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- 3. FILTER & SEARCH TOOLBAR                               --}}
    {{-- ======================================================== --}}
    <div class="rounded-lg bg-primary p-5 space-y-4">
        <form method="GET" action="{{ route('admin.batches.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                {{-- Keyword Search --}}
                <div class="lg:col-span-2 relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search batch name, code or room..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-10 rounded-full focus:outline-none font-normal">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
                    @if(request('search'))
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                           class="absolute right-3.5 top-3 text-theme-text-muted hover:text-theme-text text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

                {{-- Category Filter --}}
                <div>
                    <select name="category_id"
                            onchange="this.form.submit()"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Class Filter --}}
                <div>
                    <select name="class_course_id"
                            onchange="this.form.submit()"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        <option value="">All Classes</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ request('class_course_id') == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Teacher Filter --}}
                <div>
                    <select name="teacher_id"
                            onchange="this.form.submit()"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        <option value="">All Mentors</option>
                        @foreach($teachers as $tch)
                            <option value="{{ $tch->id }}" {{ request('teacher_id') == $tch->id ? 'selected' : '' }}>
                                {{ $tch->fname }} {{ $tch->lname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
            </div>

            @if(request()->anyFilled(['search', 'category_id', 'class_course_id', 'teacher_id', 'status']))
                <div class="flex items-center justify-between pt-1 text-xs">
                    <span class="text-theme-text-muted">
                        Active filter applied · Showing {{ $batches->total() }} matching {{ Str::plural('batch', $batches->total()) }}
                    </span>
                    <a href="{{ route('admin.batches.index') }}"
                       class="text-brand hover:underline inline-flex items-center gap-1 font-normal">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        Reset All Filters
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- ======================================================== --}}
    {{-- 4. BATCHES CARDS GRID                                    --}}
    {{-- ======================================================== --}}
    @if($batches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($batches as $batch)
                @php
                    $enrolled = $batch->students_count ?? 0;
                    $max = $batch->max_students;
                    $percentage = ($max && $max > 0) ? min(100, round(($enrolled / $max) * 100)) : 0;
                    $daysArray = array_filter(array_map('trim', explode(',', $batch->days ?? '')));
                    $daysNormalized = array_map(function($d) {
                        return substr(ucfirst(strtolower($d)), 0, 3);
                    }, $daysArray);
                    $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $weekLetters = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                    $periodCount = $batch->batchSubjects->count();
                @endphp

                <div class="rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4 transition hover:opacity-95">
                    {{-- Card Top Row: Batch Name, Code & Status --}}
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-sm font-semibold text-theme-text">
                                        {{ $batch->name }}
                                    </h2>
                                    @if($batch->code)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text-muted font-normal">
                                            {{ $batch->code }}
                                        </span>
                                    @endif
                                </div>
                                @if($batch->description)
                                    <p class="text-[11px] text-theme-text-muted line-clamp-1">
                                        {{ $batch->description }}
                                    </p>
                                @endif
                            </div>

                            @if($batch->status)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success shrink-0">
                                    Active
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-danger-bg text-danger shrink-0">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        {{-- Academic Track Hierarchy: Category > Class --}}
                        <div class="mt-3 p-2.5 rounded-lg bg-secondary text-[11px] text-theme-text-muted flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-1.5 flex-wrap truncate">
                                <span class="truncate max-w-[120px]" title="{{ $batch->classCourse?->academicCategory?->name ?? 'General' }}">
                                    {{ $batch->classCourse?->academicCategory?->name ?? 'Category' }}
                                </span>
                                <i class="fa-solid fa-chevron-right text-[8px] opacity-40"></i>
                                <span class="font-semibold text-theme-text truncate max-w-[140px]" title="{{ $batch->classCourse?->name ?? 'Class' }}">
                                    {{ $batch->classCourse?->name ?? 'Class' }}
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-primary text-brand text-[10px] font-normal shrink-0">
                                {{ $periodCount }} {{ Str::plural('Period', $periodCount) }}
                            </span>
                        </div>

                        {{-- Subject Periods Mini Preview Pills --}}
                        @if($batch->batchSubjects->isNotEmpty())
                            <div class="mt-2.5 flex items-center gap-1.5 flex-wrap">
                                @foreach($batch->batchSubjects->take(3) as $bs)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text font-normal flex items-center gap-1"
                                          title="{{ $bs->subject?->name }}: {{ $bs->start_time ? \Carbon\Carbon::parse($bs->start_time)->format('h:i A') : '' }} - {{ $bs->end_time ? \Carbon\Carbon::parse($bs->end_time)->format('h:i A') : '' }}">
                                        <i class="fa-solid fa-book-bookmark text-[8px] text-brand"></i>
                                        <span>{{ $bs->subject?->name }}</span>
                                    </span>
                                @endforeach
                                @if($batch->batchSubjects->count() > 3)
                                    <span class="text-[10px] text-theme-text-muted font-normal px-1">
                                        +{{ $batch->batchSubjects->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Faculty Assignment: Batch Mentor --}}
                    <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @if($batch->teacher?->profile_picture)
                                <img src="{{ asset($batch->teacher->profile_picture) }}"
                                     alt="{{ $batch->teacher->fname }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0">
                                    {{ strtoupper(substr($batch->teacher?->fname ?? 'T', 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <span class="text-[10px] text-theme-text-muted block">Batch Head Mentor</span>
                                <p class="text-xs font-semibold text-theme-text truncate">
                                    {{ $batch->teacher ? ($batch->teacher->fname . ' ' . ($batch->teacher->lname ?? '')) : 'No Faculty Assigned' }}
                                </p>
                            </div>
                        </div>

                        @if($batch->room_number)
                            <span class="px-2.5 py-1 rounded-full bg-primary text-[10px] text-theme-text-muted shrink-0 inline-flex items-center gap-1">
                                <i class="fa-solid fa-door-open text-brand text-[9px]"></i>
                                {{ $batch->room_number }}
                            </span>
                        @endif
                    </div>

                    {{-- Schedule: Timings, Duration with Break & Visual Days Strip (S M T W T F S) --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-theme-text-muted flex-wrap gap-1">
                            <span class="inline-flex items-center gap-1.5 text-[11px]">
                                <i class="fa-regular fa-clock text-brand text-[11px]"></i>
                                @if($batch->start_time || $batch->end_time)
                                    <span class="text-theme-text font-normal">
                                        {{ $batch->start_time ? \Carbon\Carbon::parse($batch->start_time)->format('h:i A') : '--:--' }}
                                        -
                                        {{ $batch->end_time ? \Carbon\Carbon::parse($batch->end_time)->format('h:i A') : '--:--' }}
                                    </span>
                                @else
                                    <span>Flexible Timings</span>
                                @endif
                            </span>

                            <span class="text-[10px] text-brand font-medium">
                                {{ $batch->formatted_duration }}
                            </span>
                        </div>

                        {{-- Visual S M T W T F S Indicator Strip --}}
                        <div class="flex items-center justify-between gap-1 p-1 rounded-full bg-secondary">
                            @foreach($weekDays as $idx => $wd)
                                @php
                                    $isActiveDay = in_array($wd, $daysNormalized) || count($daysArray) === 0;
                                @endphp
                                <span class="flex-1 text-center py-0.5 rounded-full text-[10px] transition
                                            {{ $isActiveDay ? 'bg-brand text-primary font-semibold shadow-xs' : 'text-theme-text-muted/40 font-normal' }}"
                                      title="{{ $wd }}: {{ $isActiveDay ? 'Active class day' : 'Off day' }}">
                                    {{ $weekLetters[$idx] }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Capacity & Enrolled Students Bar --}}
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-theme-text-muted">Seat Capacity:</span>
                            <span class="font-normal text-theme-text">
                                <span class="font-semibold text-brand">{{ $enrolled }}</span> / {{ $max ? $max . ' Seats' : 'Unlimited' }}
                            </span>
                        </div>

                        @if($max && $max > 0)
                            <div class="w-full h-1.5 rounded-full bg-secondary overflow-hidden">
                                <div class="h-full bg-brand rounded-full transition-all duration-300"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer Action Buttons --}}
                    <div class="pt-3 flex items-center justify-between gap-2">
                        <button type="button"
                                @click="openStudentsModal({{ $batch->id }}, '{{ addslashes($batch->name) }}')"
                                class="px-3 py-2 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs transition inline-flex items-center gap-1.5 font-normal">
                            <i class="fa-solid fa-user-group text-[10px] text-brand"></i>
                            <span>Students ({{ $enrolled }})</span>
                        </button>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.batches.edit', $batch->id) }}"
                               class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                               title="Edit Batch & Period Schedule">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <button type="button"
                                    @click="openDeleteModal({{ $batch->id }}, '{{ addslashes($batch->name) }}', {{ $enrolled }})"
                                    class="w-8 h-8 rounded-full bg-secondary text-danger hover:bg-danger-bg flex items-center justify-center text-xs transition"
                                    title="Delete Batch">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $batches->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="rounded-lg bg-primary p-12 text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-semibold text-theme-text">No Batches or Sections Found</h3>
                <p class="text-xs text-theme-text-muted max-w-md mx-auto">
                    @if(request()->anyFilled(['search', 'category_id', 'class_course_id', 'teacher_id', 'status']))
                        No batches matched your current filter criteria. Try clearing some filters or searching with a different term.
                    @else
                        No batches have been created for this institute yet. Create your first batch/section with scheduled subject periods.
                    @endif
                </p>
            </div>
            <div class="flex items-center justify-center gap-2 pt-2">
                @if(request()->anyFilled(['search', 'category_id', 'class_course_id', 'teacher_id', 'status']))
                    <a href="{{ route('admin.batches.index') }}"
                       class="px-5 py-2.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('admin.batches.create') }}"
                   class="px-5 py-2.5 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Create First Batch</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- 5. MODAL: VIEW ENROLLED STUDENTS                         --}}
    {{-- ======================================================== --}}
    <div x-show="studentsModalOpen"
         x-transition
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/75" @click="studentsModalOpen = false"></div>

        {{-- Dialog --}}
        <div class="relative w-full max-w-2xl bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] flex flex-col"
             @click.stop>
            <div class="flex items-center justify-between pb-3 mb-2 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text" x-text="activeBatchName + ' · Enrolled Students'"></h3>
                        <p class="text-[11px] text-theme-text-muted" x-text="batchStudentsList.length + ' students currently allocated'"></p>
                    </div>
                </div>
                <button type="button" @click="studentsModalOpen = false"
                        class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Loading State --}}
            <div x-show="studentsLoading" class="py-12 text-center text-xs text-theme-text-muted space-y-2">
                <i class="fa-solid fa-spinner fa-spin text-lg text-brand"></i>
                <p>Loading enrolled learners...</p>
            </div>

            {{-- Students List Content --}}
            <div x-show="!studentsLoading" class="overflow-y-auto scrollbar flex-1 pr-1 space-y-2.5 mt-2">
                <template x-if="batchStudentsList.length === 0">
                    <div class="py-12 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-lg mx-auto">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                        <p class="text-xs font-semibold text-theme-text">No Students Enrolled Yet</p>
                        <p class="text-[11px] text-theme-text-muted max-w-xs mx-auto">
                            Assign students to this batch during enrollment or from their student profile.
                        </p>
                    </div>
                </template>

                <template x-for="std in batchStudentsList" :key="std.id">
                    <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3 hover:bg-subtle transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <template x-if="std.profile_picture">
                                <img :src="'/' + std.profile_picture" class="w-9 h-9 rounded-full object-cover shrink-0">
                            </template>
                            <template x-if="!std.profile_picture">
                                <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                     x-text="(std.fname ? std.fname[0] : 'S').toUpperCase()">
                                </div>
                            </template>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-semibold text-theme-text truncate" x-text="std.fname + ' ' + (std.lname || '')"></h4>
                                    <template x-if="std.roll_number">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-primary text-theme-text-muted font-normal"
                                              x-text="'Roll: ' + std.roll_number"></span>
                                    </template>
                                </div>
                                <p class="text-[10px] text-theme-text-muted truncate mt-0.5" x-text="std.email || std.number || 'No contact info'"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-normal"
                                  :class="std.admission_status === 'enrolled' ? 'bg-success-bg text-success' : 'bg-secondary text-theme-text-muted'"
                                  x-text="std.admission_status ? std.admission_status.toUpperCase() : 'ACTIVE'">
                            </span>
                            <a :href="'/admin/students/' + std.id"
                               target="_blank"
                               class="w-7 h-7 rounded-full bg-primary text-theme-text hover:text-brand flex items-center justify-center text-xs transition"
                               title="Open Student Profile">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div class="pt-4 border-t border-theme-border/20 flex justify-end shrink-0">
                <button type="button" @click="studentsModalOpen = false"
                        class="px-5 py-2.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- 6. MODAL: DELETE SAFEGUARD                               --}}
    {{-- ======================================================== --}}
    <div x-show="deleteModalOpen"
         x-transition
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/75" @click="deleteModalOpen = false"></div>

        {{-- Dialog --}}
        <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10"
             @click.stop>
            <div class="flex items-center justify-between pb-3 mb-3">
                <h3 class="text-sm font-semibold text-theme-text">Confirm Batch Deletion</h3>
                <button type="button" @click="deleteModalOpen = false"
                        class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Case 1: Enrolled Students Block --}}
            <template x-if="deleteEnrolledCount > 0">
                <div class="space-y-4">
                    <div class="p-3.5 rounded-lg bg-danger-bg text-danger text-xs space-y-1">
                        <div class="flex items-center gap-2 font-semibold">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Deletion Blocked</span>
                        </div>
                        <p class="text-[11px] leading-relaxed">
                            Batch <strong x-text="'\"' + deleteBatchName + '\"'"></strong> currently has
                            <strong x-text="deleteEnrolledCount"></strong> actively enrolled student(s).
                            To protect student records, batches with active enrollments cannot be deleted.
                        </p>
                    </div>

                    <p class="text-xs text-theme-text-muted leading-relaxed">
                        Please reassign or remove all students from this batch before attempting to delete it.
                    </p>

                    <div class="flex justify-end pt-2">
                        <button type="button" @click="deleteModalOpen = false"
                                class="px-5 py-2.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                            Understood
                        </button>
                    </div>
                </div>
            </template>

            {{-- Case 2: 0 Enrolled Students Safe to Delete --}}
            <template x-if="deleteEnrolledCount === 0">
                <form :action="deleteActionUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <p class="text-xs text-theme-text leading-relaxed">
                        Are you sure you want to delete batch <strong x-text="'\"' + deleteBatchName + '\"'"></strong>? This action will remove the batch and all its scheduled periods.
                    </p>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="deleteModalOpen = false"
                                class="px-4 py-2.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-full bg-danger-bg text-danger text-xs hover:opacity-90 transition font-normal shadow-sm">
                            Delete Batch
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>

{{-- Alpine Component Logic for Index Page --}}
<script>
    function batchesIndexComponent() {
        return {
            studentsModalOpen: false,
            deleteModalOpen: false,

            activeBatchName: '',
            batchStudentsList: [],
            studentsLoading: false,

            deleteActionUrl: '',
            deleteBatchName: '',
            deleteEnrolledCount: 0,

            openStudentsModal(batchId, batchName) {
                this.activeBatchName = batchName;
                this.batchStudentsList = [];
                this.studentsLoading = true;
                this.studentsModalOpen = true;

                fetch('/admin/batches/' + batchId + '/students')
                    .then(res => res.json())
                    .then(data => {
                        this.batchStudentsList = data.students || [];
                        this.studentsLoading = false;
                    })
                    .catch(err => {
                        console.error('Failed to load students:', err);
                        this.studentsLoading = false;
                    });
            },

            openDeleteModal(batchId, batchName, enrolledCount) {
                this.deleteActionUrl = '/admin/batches/' + batchId + '/delete';
                this.deleteBatchName = batchName;
                this.deleteEnrolledCount = enrolledCount;
                this.deleteModalOpen = true;
            }
        };
    }
</script>
@endsection
