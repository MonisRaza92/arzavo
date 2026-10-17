@extends('layouts.admin')
@section('title', 'Student ID Card Generator')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @media print {
        @page {
            margin: 10mm;
            size: A4 portrait;
        }
        body {
            background: #ffffff !important;
            color: #0f172a !important;
        }
        /* Hide layout elements during printing */
        .no-print,
        nav,
        aside,
        header,
        #sidebar,
        #topNavbar {
            display: none !important;
        }
        .print-hidden {
            display: none !important;
        }
        #idCardsGrid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 18px !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .printable-id-card {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px dashed #94a3b8 !important;
            border-radius: 12px !important;
            padding: 16px !important;
            box-shadow: none !important;
        }
        .printable-id-card * {
            color: #0f172a !important;
        }
        .printable-id-card .print-badge {
            border: 1px solid #059669 !important;
            color: #059669 !important;
        }
        .printable-id-card .print-hide {
            display: none !important;
        }
    }
</style>

@php
    $currentTenant = app()->bound('currentTenant') ? app('currentTenant') : null;
    $academyName = $currentTenant?->name ?? 'Academy';
    $academicSession = date('Y') . ' - ' . (date('Y') + 1);
@endphp

<script>
    window.idCardCategories = @json($categories);
    window.idCardSelectedCat = '{{ request('category_id') }}';
    window.idCardSelectedClass = '{{ request('class_id') }}';

    window.idCardManagement = function idCardManagement() {
        return {
            categories: window.idCardCategories || [],
            categoryId: window.idCardSelectedCat || '',
            classId: window.idCardSelectedClass || '',

            get classes() {
                if (!this.categoryId) return [];
                const cat = this.categories.find(c => String(c.id) === String(this.categoryId));
                return cat ? (cat.class_courses || []) : [];
            },

            onCategoryChange() {
                this.classId = '';
                this.$refs.filterForm.submit();
            },

            printSingle(cardId) {
                const cards = document.querySelectorAll('.printable-id-card');
                cards.forEach(c => {
                    if (c.id !== cardId) {
                        c.classList.add('print-hidden');
                    }
                });

                window.print();

                // Restore visibility after print dialog closes
                setTimeout(() => {
                    cards.forEach(c => c.classList.remove('print-hidden'));
                }, 800);
            },

            printAll() {
                document.querySelectorAll('.printable-id-card').forEach(c => c.classList.remove('print-hidden'));
                window.print();
            }
        };
    };
</script>

<div x-data="idCardManagement()" x-cloak class="space-y-5">

    <!-- STANDARD PAGE HEADER BANNER (NO BORDERS, NO SHADOW) -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 no-print">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Student ID Card Generator
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Official printable student identification credentials with verified institute details, roll numbers, and academic stream.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.students') }}" class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Back to Students</span>
            </a>
            <button type="button" @click="printAll()" class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-print text-[11px]"></i>
                <span>Print All ID Cards</span>
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="rounded-lg bg-primary p-5 no-print">
        <form method="GET" action="{{ route('admin.students.id-card') }}" x-ref="filterForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search Input -->
            <div>
                <label for="idSearch" class="block text-xs font-normal text-theme-text mb-1.5">Search Student</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs"></i>
                    <input type="text" name="search" id="idSearch" value="{{ request('search') }}"
                           placeholder="Search name, roll, phone..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs pl-10 pr-4 py-3 rounded-full focus:outline-none">
                </div>
            </div>

            <!-- Academic Category Filter -->
            <div>
                <label for="filterCategory" class="block text-xs font-normal text-theme-text mb-1.5">Academic Category</label>
                <select name="category_id" id="filterCategory" x-model="categoryId" @change="onCategoryChange()"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                    <option value="">All Categories</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name" :selected="String(cat.id) === String(categoryId)"></option>
                    </template>
                </select>
            </div>

            <!-- Dependent Cohort Class Filter -->
            <div>
                <label for="filterClass" class="block text-xs font-normal text-theme-text mb-1.5">Cohort Class</label>
                <select name="class_id" id="filterClass" x-model="classId" @change="$refs.filterForm.submit()"
                        :disabled="!categoryId"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="">All Classes</option>
                    <template x-for="cc in classes" :key="cc.id">
                        <option :value="cc.id" x-text="cc.name" :selected="String(cc.id) === String(classId)"></option>
                    </template>
                </select>
            </div>

            <!-- Actions: Filter Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-3 bg-secondary text-theme-text text-xs font-normal rounded-full hover:bg-subtle transition inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-[11px] text-brand"></i>
                    <span>Apply Filter</span>
                </button>
                @if(request()->hasAny(['search', 'category_id', 'class_id']))
                    <a href="{{ route('admin.students.id-card') }}" class="px-4 py-3 bg-secondary text-theme-text-muted hover:text-theme-text text-xs font-normal rounded-full transition inline-flex items-center justify-center" title="Reset Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- METRIC / COUNT SUMMARY STRIP -->
    <div class="rounded-lg bg-primary p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-theme-text-muted no-print">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-brand"></span>
            <span>Showing <strong class="text-theme-text font-semibold">{{ $students->count() }}</strong> Student Identity {{ $students->count() === 1 ? 'Card' : 'Cards' }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-check text-brand text-[11px]"></i>
                <span>Academic Session: <strong class="text-theme-text font-semibold">{{ $academicSession }}</strong></span>
            </span>
        </div>
    </div>

    <!-- ID CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="idCardsGrid">
        @forelse($students as $student)
            @php
                $initials = strtoupper(substr($student->fname, 0, 1) . substr($student->lname ?: 'S', 0, 1));
                $cardId = 'student-card-' . $student->id;
            @endphp
            <div class="printable-id-card rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4"
                 id="{{ $cardId }}">
                
                <!-- TOP HEADER BAND (Institute Name & Badge) -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0 font-semibold">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs font-semibold text-theme-text truncate uppercase tracking-wide">
                                {{ $academyName }}
                            </h3>
                            <span class="text-[10px] text-theme-text-muted block font-normal">
                                Student Identity Card
                            </span>
                        </div>
                    </div>
                    <span class="print-badge px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success shrink-0 inline-flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                        <span>Verified</span>
                    </span>
                </div>

                <!-- STUDENT DETAILS BODY -->
                <div class="flex items-start gap-3.5">
                    <!-- Photo / Avatar -->
                    <div class="w-20 h-24 rounded-lg bg-secondary text-brand flex items-center justify-center font-semibold text-lg shrink-0 overflow-hidden">
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $student->fname }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ $initials }}</span>
                        @endif
                    </div>

                    <!-- Information Ledger -->
                    <div class="space-y-1.5 grow min-w-0 text-xs">
                        <div>
                            <h4 class="font-semibold text-sm text-theme-text truncate leading-tight">
                                {{ $student->fname }} {{ $student->lname }}
                            </h4>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-brand font-normal">
                                    {{ $student->roll_number ? 'Roll: ' . $student->roll_number : '@' . $student->username }}
                                </span>
                            </div>
                        </div>

                        <!-- Structured Data Rows -->
                        <div class="space-y-1 pt-1 text-[11px]">
                            <div class="flex items-center justify-between text-theme-text-muted">
                                <span>Category:</span>
                                <span class="text-theme-text font-normal truncate max-w-[130px] text-right">
                                    {{ $student->academicCategory->name ?? 'Not Assigned' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-theme-text-muted">
                                <span>Class:</span>
                                <span class="text-theme-text font-normal truncate max-w-[130px] text-right">
                                    {{ $student->class->name ?? 'Not Assigned' }}
                                </span>
                            </div>
                            @if($student->subject)
                                <div class="flex items-center justify-between text-theme-text-muted">
                                    <span>Subject:</span>
                                    <span class="text-theme-text font-normal truncate max-w-[130px] text-right">
                                        {{ $student->subject->name }}
                                    </span>
                                </div>
                            @endif
                            @if($student->batch)
                                <div class="flex items-center justify-between text-theme-text-muted">
                                    <span>Batch:</span>
                                    <span class="text-theme-text font-normal truncate max-w-[130px] text-right">
                                        {{ $student->batch->name }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between text-theme-text-muted">
                                <span>Contact:</span>
                                <span class="text-theme-text font-normal">
                                    {{ $student->number ?: 'N/A' }}
                                </span>
                            </div>
                            @if($student->blood_group)
                                <div class="flex items-center justify-between text-theme-text-muted">
                                    <span>Blood Group:</span>
                                    <span class="text-theme-text font-normal">
                                        {{ $student->blood_group }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- BOTTOM FOOTER & ACTIONS STRIP -->
                <div class="pt-2 flex items-center justify-between gap-2 text-xs">
                    <span class="text-[10px] text-theme-text-muted font-normal inline-flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-[9px] text-brand"></i>
                        <span>Valid: {{ $academicSession }}</span>
                    </span>

                    <div class="flex items-center gap-1.5 print-hide">
                        <!-- Print Single Card -->
                        <button type="button" @click="printSingle('{{ $cardId }}')"
                                title="Print this ID Card"
                                class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-print text-[10px] text-brand"></i>
                            <span>Print</span>
                        </button>

                        <!-- View Profile -->
                        <a href="{{ route('admin.admin-student-profile', $student->username) }}"
                           title="View Student Profile"
                           class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-theme-text-muted"></i>
                            <span>Profile</span>
                        </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full rounded-lg bg-primary p-12 text-center text-xs text-theme-text-muted space-y-2">
                <div class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-xl mx-auto mb-2">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <h4 class="text-sm font-semibold text-theme-text">No Student ID Cards Found</h4>
                <p class="max-w-md mx-auto text-theme-text-muted">
                    No enrolled students match your filter criteria. Try clearing search filters or add students from the Admissions module.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.students.id-card') }}" class="px-5 py-2.5 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate text-[11px] text-brand"></i>
                        <span>Reset Filters</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
