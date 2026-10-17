@extends('layouts.admin')
@section('title', 'Class & Subject Allocations')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $teachersJson = $teachers->map(function($t) {
        return [
            'id' => $t->id,
            'name' => trim($t->fname . ' ' . $t->lname),
            'designation' => $t->designation ?: 'Faculty',
            'assigned_subject_ids' => $t->assignedSubjects->pluck('id')->toArray(),
        ];
    });

    $initialSubjectTeachers = $allSubjects->pluck('teacher_id', 'id')->map(fn($v) => (string)($v ?? ''))->toArray();

    $totalClassesCount = $categories->sum(fn($c) => $c->classCourses->count());
    $totalSubjectsCount = $allSubjects->count();
    $assignedSubjectsCount = $allSubjects->whereNotNull('teacher_id')->count();
@endphp

<script>
    window.allocationsData = {
        saveUrl: '{{ route('admin.teachers.allocations.assign') }}',
        bulkUrl: '{{ route('admin.teachers.allocations.bulk-assign') }}',
        csrfToken: '{{ csrf_token() }}',
        teachers: @json($teachersJson),
        initialSubjectTeachers: @json($initialSubjectTeachers)
    };

    window.academicAllocations = function academicAllocations() {
        return {
            searchQuery: '',
            saveState: {},
            globalNotice: '',
            globalNoticeType: 'success',
            globalNoticeTitle: '',
            noticeTimeout: null,
            subjectTeachers: { ...window.allocationsData.initialSubjectTeachers },

            get assignedSubjectsCount() {
                return Object.values(this.subjectTeachers).filter(v => v !== null && v !== '' && v !== undefined && v !== '0').length;
            },

            isSubjectAssigned(subjectId) {
                const val = this.subjectTeachers[subjectId];
                return val !== null && val !== '' && val !== undefined && val !== '0';
            },

            // Multi-subject bulk modal
            isBulkModalOpen: false,
            isSubmittingBulk: false,
            bulkModalError: '',
            selectedTeacherId: '',
            selectedSubjectIds: [],

            showNotice(msg, type = 'success', duration = 4000, title = '') {
                this.globalNotice = msg;
                this.globalNoticeType = type;
                this.globalNoticeTitle = title || (type === 'danger' ? 'Schedule Conflict' : 'Success');
                if (this.noticeTimeout) {
                    clearTimeout(this.noticeTimeout);
                }
                this.noticeTimeout = setTimeout(() => {
                    this.globalNotice = '';
                }, duration);
            },

            updateAllocation(type, targetId, selectElement) {
                const key = type + '_' + targetId;
                const newTeacherId = selectElement.value;
                const previousValue = (selectElement.dataset.previousValue !== undefined && selectElement.dataset.previousValue !== '')
                    ? selectElement.dataset.previousValue
                    : (selectElement.getAttribute('data-initial-value') || '');

                this.saveState[key] = 'saving';

                // Instantly update badge in UI
                if (type === 'subject_teacher') {
                    this.subjectTeachers[targetId] = newTeacherId || '';
                }

                fetch(window.allocationsData.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.allocationsData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        type: type,
                        target_id: targetId,
                        teacher_id: newTeacherId || null
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        // Immediately revert the select dropdown value so the conflict is not visually retained
                        const revertTo = (data.previous_teacher_id !== undefined && data.previous_teacher_id !== null)
                            ? String(data.previous_teacher_id)
                            : previousValue;
                        selectElement.value = revertTo;
                        selectElement.dataset.previousValue = revertTo;

                        // Revert reactive badge state
                        if (type === 'subject_teacher') {
                            this.subjectTeachers[targetId] = revertTo || '';
                        }

                        const err = new Error(data.message || 'Failed to update allocation.');
                        err.isConflict = !!data.conflict;
                        throw err;
                    }
                    return data;
                })
                .then(data => {
                    this.saveState[key] = 'saved';
                    selectElement.dataset.previousValue = newTeacherId;
                    if (type === 'subject_teacher') {
                        this.subjectTeachers[targetId] = newTeacherId || '';
                    }
                    this.showNotice(data.message, 'success', 3000, 'Allocation Updated');
                    setTimeout(() => {
                        if (this.saveState[key] === 'saved') {
                            this.saveState[key] = '';
                        }
                    }, 2500);
                })
                .catch(err => {
                    this.saveState[key] = 'error';
                    this.showNotice(err.message, 'danger', err.isConflict ? 10000 : 5000, err.isConflict ? 'Schedule Conflict' : 'Assignment Error');
                    // Smoothly scroll to the alert notice at the top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            },

            openBulkModal(teacherId = null) {
                this.bulkModalError = '';
                if (teacherId) {
                    this.selectedTeacherId = teacherId;
                    this.onTeacherChange();
                } else if (window.allocationsData.teachers.length > 0) {
                    this.selectedTeacherId = window.allocationsData.teachers[0].id;
                    this.onTeacherChange();
                }
                this.isBulkModalOpen = true;
            },

            closeBulkModal() {
                this.isBulkModalOpen = false;
                this.bulkModalError = '';
                this.selectedTeacherId = '';
                this.selectedSubjectIds = [];
            },

            onTeacherChange() {
                this.bulkModalError = '';
                const t = window.allocationsData.teachers.find(item => item.id == this.selectedTeacherId);
                if (t) {
                    this.selectedSubjectIds = [...t.assigned_subject_ids];
                } else {
                    this.selectedSubjectIds = [];
                }
            },

            toggleSubjectSelection(id) {
                this.bulkModalError = '';
                const idx = this.selectedSubjectIds.indexOf(id);
                if (idx > -1) {
                    this.selectedSubjectIds.splice(idx, 1);
                } else {
                    this.selectedSubjectIds.push(id);
                }
            },

            isSubjectSelected(id) {
                return this.selectedSubjectIds.includes(id);
            },

            submitBulkModal() {
                if (!this.selectedTeacherId) {
                    this.bulkModalError = 'Please select a faculty member first.';
                    return;
                }

                this.isSubmittingBulk = true;
                this.bulkModalError = '';

                fetch(window.allocationsData.bulkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.allocationsData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        teacher_id: this.selectedTeacherId,
                        subject_ids: this.selectedSubjectIds
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Failed to apply subject allocations.');
                    }
                    return data;
                })
                .then(data => {
                    this.isSubmittingBulk = false;
                    this.closeBulkModal();
                    this.showNotice(data.message, 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 600);
                })
                .catch(err => {
                    this.isSubmittingBulk = false;
                    this.bulkModalError = err.message;
                });
            }
        };
    };
</script>

<div x-data="academicAllocations()" x-cloak class="space-y-6">

    <!-- LARAVEL SESSION FLASH MESSAGES -->
    @if(session('error'))
        <div class="rounded-lg p-4 text-xs font-normal flex items-start justify-between bg-danger-bg text-danger transition-all">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-sm shrink-0 mt-0.5"></i>
                <div class="space-y-0.5">
                    <span class="block font-semibold">Schedule Conflict</span>
                    <span class="leading-relaxed block">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="rounded-lg p-4 text-xs font-normal flex items-center justify-between bg-success-bg text-success transition-all">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
                <span class="leading-relaxed">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- GLOBAL DYNAMIC TOAST / ALERT NOTICE -->
    <template x-if="globalNotice">
        <div class="rounded-lg p-4 text-xs font-normal flex items-start justify-between transition-all"
             :class="{
                 'bg-success-bg text-success': globalNoticeType === 'success',
                 'bg-danger-bg text-danger': globalNoticeType === 'danger'
             }">
            <div class="flex items-start gap-3">
                <i class="fa-solid shrink-0 mt-0.5 text-sm"
                   :class="globalNoticeType === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'"></i>
                <div class="space-y-0.5">
                    <span class="block font-semibold" x-text="globalNoticeTitle"></span>
                    <span class="leading-relaxed block" x-text="globalNotice"></span>
                </div>
            </div>
            <button type="button" @click="globalNotice = ''" class="hover:opacity-75 transition shrink-0 ml-3">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    </template>

    <!-- COMPACT PAGE HEADER BANNER (FLAT, ZERO BORDERS) -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Class &amp; Subject Allocations
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Assign Class Teachers (authorized for student attendance &amp; class supervision) and Subject Faculty members on a single screen. Timetable conflicts are automatically validated.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <button type="button" @click="openBulkModal()"
                    class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus-minus text-[11px]"></i>
                <span>Assign Multiple Subjects to Teacher</span>
            </button>
            <a href="{{ route('admin.teachers') }}"
               class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Teachers Directory</span>
            </a>
        </div>
    </div>

    <!-- SIMPLE SEARCH & SUMMARY BAR (CONTAINER BG-PRIMARY -> INPUT BG-SECONDARY) -->
    <div class="rounded-lg bg-primary p-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <!-- Search filter -->
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs"></i>
            <input type="text" x-model="searchQuery" placeholder="Filter by class or subject name..."
                   class="w-full bg-secondary text-theme-text text-xs pl-9 pr-4 py-2 rounded-full focus:outline-none">
        </div>

        <!-- Inline Quick Counts -->
        <div class="flex items-center gap-3 text-theme-text-muted text-xs flex-wrap self-start sm:self-center">
            <span>Classes: <strong class="text-theme-text font-medium">{{ $totalClassesCount }}</strong></span>
            <span>&bull;</span>
            <span>Subjects: <strong class="text-theme-text font-medium" x-text="assignedSubjectsCount + '/{{ $totalSubjectsCount }} Assigned'">{{ $assignedSubjectsCount }}/{{ $totalSubjectsCount }} Assigned</strong></span>
            <span>&bull;</span>
            <span>Faculty Pool: <strong class="text-theme-text font-medium">{{ $teachers->count() }} Teachers</strong></span>
        </div>
    </div>

    <!-- MAIN ALLOCATION MATRIX: CATEGORY BLOCKS -->
    <div class="space-y-6">
        @forelse($categories as $category)
            <!-- 1. CATEGORY BLOCK (ROUNDED-LG BG-PRIMARY P-6) -->
            <div class="rounded-lg bg-primary p-6 space-y-5"
                 x-show="!searchQuery || '{{ strtolower($category->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($category->classCourses->pluck('name')->implode(' ')) }}'.includes(searchQuery.toLowerCase())">
                
                <!-- Category Block Header (bg-secondary card with helping text) -->
                <div class="rounded-lg bg-secondary p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0 mt-0.5 font-medium">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand"></span>
                                <h2 class="text-sm font-semibold text-theme-text uppercase tracking-wide">
                                    {{ $category->name }}
                                </h2>
                                <span class="px-2.5 py-0.5 rounded-full bg-primary text-[11px] text-theme-text-muted font-normal">
                                    {{ $category->classCourses->count() }} Classes
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full bg-primary text-[11px] text-theme-text-muted font-normal">
                                    {{ $category->classCourses->sum(fn($c) => $c->subjects->count()) }} Subjects
                                </span>
                            </div>
                            <p class="text-xs text-theme-text-muted mt-1 leading-relaxed">
                                {{ $category->description ?: "Academic stream for {$category->name}. Manage attendance-authorized Class Teachers and subject faculty allocations for all underlying classes." }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                        <span class="px-3 py-1 rounded-full bg-primary text-[11px] font-normal text-theme-text-muted inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-graduation-cap text-brand text-[10px]"></i>
                            <span>Academic Curriculum Stream</span>
                        </span>
                    </div>
                </div>

                <!-- 2. CLASSES INSIDE THIS CATEGORY BLOCK -->
                <div class="space-y-5">
                    @forelse($category->classCourses as $cc)
                        <!-- CLASS CONTAINER (ROUNDED-LG BG-SECONDARY P-5) -->
                        <div class="rounded-lg bg-secondary p-5 space-y-4"
                             x-show="!searchQuery || '{{ strtolower($cc->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($cc->subjects->pluck('name')->implode(' ')) }}'.includes(searchQuery.toLowerCase())">
                            
                            <!-- CLASS HEADER ROW: TITLE & CLASS TEACHER (ATTENDANCE INCHARGE) -->
                            <!-- Note: Container is bg-secondary, so input is bg-primary! -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0 font-medium">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-theme-text leading-tight">{{ $cc->name }}</h3>
                                        <span class="text-[11px] text-theme-text-muted">
                                            {{ $cc->subjects->count() }} Subjects &bull; {{ $cc->batches->count() }} Batches/Sections
                                        </span>
                                    </div>
                                </div>

                                <!-- Class Teacher Selector Dropdown (Input bg-primary for contrast) -->
                                <div class="flex items-center gap-2 self-stretch sm:self-auto">
                                    <label class="text-xs font-normal text-theme-text shrink-0 inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-check text-brand text-xs"></i>
                                        <span>Class Teacher (Attendance):</span>
                                    </label>
                                    <div class="relative w-full sm:w-60">
                                        <select @change="updateAllocation('class_teacher', {{ $cc->id }}, $event.target)"
                                                data-initial-value="{{ $cc->class_teacher_id ?? '' }}"
                                                class="w-full bg-primary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none cursor-pointer">
                                            <option value="">No Class Teacher Assigned</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ $cc->class_teacher_id == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->fname }} {{ $teacher->lname }} ({{ $teacher->designation ?: 'Faculty' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Auto-save feedback -->
                                    <span x-show="saveState['class_teacher_{{ $cc->id }}'] === 'saving'" class="text-theme-text-muted text-[10px] animate-pulse shrink-0">
                                        <i class="fa-solid fa-circle-notch fa-spin text-brand"></i>
                                    </span>
                                    <span x-show="saveState['class_teacher_{{ $cc->id }}'] === 'saved'" class="text-success text-xs shrink-0">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>
                                    <span x-show="saveState['class_teacher_{{ $cc->id }}'] === 'error'" class="text-danger text-xs shrink-0" title="Assignment Failed">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- 3. SUBJECTS SECTION UNDER THIS CLASS -->
                            <div class="space-y-2">
                                <div class="flex bg-primary p-4 rounded-lg items-center justify-between">
                                    <h4 class="text-[11px] font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                        <span>Subjects in {{ $cc->name }} ({{ $cc->subjects->count() }})</span>
                                    </h4>
                                    <span class="text-[10px] text-theme-text-muted">Strict timing collision validation enabled</span>
                                </div>

                                <div class="space-y-2">
                                    @forelse($cc->subjects as $subject)
                                        <!-- SUBJECT ROW (CONTAINER BG-PRIMARY -> INPUT BG-SECONDARY) -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-lg bg-primary">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-book"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-xs font-medium text-theme-text block truncate">{{ $subject->name }}</span>
                                                    @if($subject->start_time && $subject->end_time)
                                                        <span class="text-[10px] text-theme-text-muted inline-flex items-center gap-1">
                                                            <i class="fa-regular fa-clock text-[9px] text-brand"></i>
                                                            {{ date('h:i A', strtotime($subject->start_time)) }} - {{ date('h:i A', strtotime($subject->end_time)) }}
                                                            @if($subject->days) &bull; {{ $subject->days }} @endif
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] text-theme-text-muted">Timings unassigned</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3 self-stretch sm:self-auto">
                                                <!-- Dropdown: in bg-primary container, so input is bg-secondary -->
                                                <div class="w-full sm:w-64">
                                                    <select @change="updateAllocation('subject_teacher', {{ $subject->id }}, $event.target)"
                                                            data-initial-value="{{ $subject->teacher_id ?? '' }}"
                                                            class="w-full bg-secondary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none cursor-pointer">
                                                        <option value="">Unassigned Faculty</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ $subject->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->fname }} {{ $teacher->lname }} ({{ $teacher->designation ?: 'Faculty' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Status Badge & Feedback -->
                                                <div class="w-24 text-right shrink-0 flex items-center justify-end gap-1.5">
                                                    <span x-show="saveState['subject_teacher_{{ $subject->id }}'] === 'saving'" class="text-theme-text-muted text-[10px] animate-pulse">
                                                        <i class="fa-solid fa-circle-notch fa-spin text-brand text-[8px]"></i>
                                                    </span>
                                                    <span x-show="saveState['subject_teacher_{{ $subject->id }}'] === 'saved'" class="text-success text-xs">
                                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    </span>
                                                    <span x-show="saveState['subject_teacher_{{ $subject->id }}'] === 'error'" class="text-danger text-xs" title="Schedule Conflict">
                                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                    </span>

                                                    <span x-show="isSubjectAssigned({{ $subject->id }})"
                                                          class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-normal inline-flex items-center gap-1">
                                                        <i class="fa-solid fa-check text-[8px]"></i> Assigned
                                                    </span>
                                                    <span x-show="!isSubjectAssigned({{ $subject->id }})"
                                                          class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-normal inline-flex items-center gap-1">
                                                        <i class="fa-solid fa-circle-dot text-[7px]"></i> Unassigned
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-3 bg-primary rounded-lg text-center text-xs text-theme-text-muted">
                                            No subjects configured under this class yet.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- 4. SECTIONS & BATCHES SECTION (HAR BATCH KE PERIODS) -->
                            @if($cc->batches->isNotEmpty())
                                <!-- DIVIDER BEFORE BATCHES -->
                                <div class="border-t border-subtle/50"></div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-[11px] font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-users-rectangle text-blue-500 text-[10px]"></i>
                                            <span>Sections &amp; Batches in {{ $cc->name }} ({{ $cc->batches->count() }})</span>
                                        </h4>
                                        <span class="text-[10px] text-theme-text-muted">Batch Mentor &amp; Subject Periods</span>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach($cc->batches as $batch)
                                            <!-- BATCH BLOCK (ROUNDED-LG BG-PRIMARY P-4) -->
                                            <div class="rounded-lg bg-primary p-4 space-y-3">
                                                
                                                <!-- Batch Header Row: Name, Timing & Batch Mentor Dropdown -->
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-subtle/40">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-7 h-7 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center text-xs shrink-0">
                                                            <i class="fa-solid fa-users"></i>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs font-semibold text-theme-text block">{{ $batch->name }}</span>
                                                            <span class="text-[10px] text-theme-text-muted">
                                                                {{ $batch->start_time ? date('h:i A', strtotime($batch->start_time)) : 'Timings N/A' }}
                                                                @if($batch->room_number) &bull; Room {{ $batch->room_number }} @endif
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Batch Mentor Dropdown (in bg-primary container, so input is bg-secondary) -->
                                                    <div class="flex items-center gap-2 self-stretch sm:self-auto">
                                                        <label class="text-xs text-theme-text-muted shrink-0 font-normal">Batch Mentor:</label>
                                                        <div class="w-full sm:w-56">
                                                            <select @change="updateAllocation('batch_teacher', {{ $batch->id }}, $event.target)"
                                                                    data-initial-value="{{ $batch->teacher_id ?? '' }}"
                                                                    class="w-full bg-secondary text-theme-text text-xs px-3.5 py-1.5 rounded-full focus:outline-none cursor-pointer">
                                                                <option value="">No Batch Mentor</option>
                                                                @foreach($teachers as $teacher)
                                                                    <option value="{{ $teacher->id }}" {{ $batch->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                        {{ $teacher->fname }} {{ $teacher->lname }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span x-show="saveState['batch_teacher_{{ $batch->id }}'] === 'saved'" class="text-success text-xs shrink-0">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        </span>
                                                        <span x-show="saveState['batch_teacher_{{ $batch->id }}'] === 'error'" class="text-danger text-xs shrink-0" title="Assignment Failed">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Batch Specific Periods / Subjects (Har batch ke sab periods) -->
                                                @if($batch->batchSubjects->isNotEmpty())
                                                    <div class="space-y-1.5 pt-1">
                                                        <span class="text-[10px] text-theme-text-muted block font-medium">Batch Periods &amp; Faculty:</span>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                            @foreach($batch->batchSubjects as $bs)
                                                                <div class="p-2.5 bg-secondary rounded-lg flex items-center justify-between gap-2 text-xs">
                                                                    <div class="min-w-0">
                                                                        <span class="font-medium text-theme-text block truncate">{{ $bs->subject ? $bs->subject->name : 'Subject' }}</span>
                                                                        <span class="text-[10px] text-theme-text-muted">
                                                                            {{ $bs->start_time ? date('h:i A', strtotime($bs->start_time)) : ($bs->subject?->start_time ? date('h:i A', strtotime($bs->subject->start_time)) : '') }}
                                                                            @if($bs->room_number) &bull; R: {{ $bs->room_number }} @endif
                                                                        </span>
                                                                    </div>
                                                                    <!-- Dropdown in bg-secondary is bg-primary! -->
                                                                    <div class="w-40 shrink-0 flex items-center gap-1.5">
                                                                        <select @change="updateAllocation('batch_subject_teacher', {{ $bs->id }}, $event.target)"
                                                                                data-initial-value="{{ $bs->teacher_id ?? '' }}"
                                                                                class="w-full bg-primary text-theme-text text-[11px] px-2.5 py-1 rounded-full focus:outline-none cursor-pointer">
                                                                            <option value="">Default Faculty</option>
                                                                            @foreach($teachers as $teacher)
                                                                                <option value="{{ $teacher->id }}" {{ $bs->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                                    {{ $teacher->fname }} {{ $teacher->lname }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span x-show="saveState['batch_subject_teacher_{{ $bs->id }}'] === 'saved'" class="text-success text-xs shrink-0">
                                                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                                        </span>
                                                                        <span x-show="saveState['batch_subject_teacher_{{ $bs->id }}'] === 'error'" class="text-danger text-xs shrink-0" title="Schedule Conflict">
                                                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-[11px] text-theme-text-muted pt-0.5">
                                                        <i class="fa-solid fa-info-circle text-[10px] mr-1 text-brand"></i>
                                                        Follows the default subject faculty assigned to {{ $cc->name }}.
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    @empty
                        <div class="p-6 bg-secondary rounded-lg text-center text-xs text-theme-text-muted">
                            No classes configured under this stream.
                        </div>
                    @endforelse
                </div>

            </div>
        @empty
            <div class="p-12 bg-primary rounded-lg text-center text-xs text-theme-text-muted">
                No academic curriculum configured yet.
            </div>
        @endforelse
    </div>

    <!-- BULK MULTI-SUBJECT ASSIGNMENT MODAL -->
    <div x-show="isBulkModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="closeBulkModal()">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeBulkModal()">

            <!-- Modal Header -->
            <div class="p-5 flex items-center justify-between bg-secondary/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Assign Multiple Subjects to Teacher</h2>
                        <p class="text-[11px] text-theme-text-muted">Select faculty and assign subjects. Schedules are automatically validated against time conflicts.</p>
                    </div>
                </div>
                <button type="button" @click="closeBulkModal()" class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body (Container bg-primary -> Input bg-secondary) -->
            <form @submit.prevent="submitBulkModal()" class="flex-1 flex flex-col overflow-hidden">
                <div class="p-5 space-y-4 overflow-y-auto flex-1">
                    
                    <!-- Modal Error Notice -->
                    <div x-show="bulkModalError"
                         style="display: none;"
                         class="p-3.5 bg-danger-bg text-danger text-xs rounded-lg flex items-start gap-2.5 font-normal">
                        <i class="fa-solid fa-triangle-exclamation text-sm shrink-0 mt-0.5"></i>
                        <div class="space-y-0.5">
                            <span class="block font-semibold">Schedule Conflict</span>
                            <span class="leading-relaxed block" x-text="bulkModalError"></span>
                        </div>
                    </div>

                    <!-- Teacher Selector -->
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Select Faculty Member *</label>
                        <select name="teacher_id" x-model="selectedTeacherId" @change="onTeacherChange()" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none cursor-pointer">
                            <option value="">Choose Teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->fname }} {{ $teacher->lname }} ({{ $teacher->designation ?: 'Faculty' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subjects Checkboxes Grouped by Class -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-theme-text">Select Subjects to Assign:</span>
                            <span class="text-[11px] text-brand font-medium" x-text="selectedSubjectIds.length + ' Subjects Selected'"></span>
                        </div>

                        @foreach($categories as $category)
                            @foreach($category->classCourses as $cc)
                                @if($cc->subjects->isNotEmpty())
                                    <div class="rounded-lg bg-secondary p-3 space-y-2">
                                        <div class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                            <i class="fa-solid fa-chalkboard text-brand text-[10px]"></i>
                                            <span>{{ $cc->name }} ({{ $category->name }})</span>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                            @foreach($cc->subjects as $subj)
                                                <label class="flex items-center justify-between p-2 rounded-lg bg-primary cursor-pointer hover:bg-subtle transition text-xs gap-2">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <input type="checkbox" value="{{ $subj->id }}"
                                                               :checked="isSubjectSelected({{ $subj->id }})"
                                                               @change="toggleSubjectSelection({{ $subj->id }})"
                                                               class="w-4 h-4 rounded text-brand focus:ring-0 cursor-pointer shrink-0">
                                                        <span class="text-theme-text font-normal truncate">{{ $subj->name }}</span>
                                                    </div>
                                                    @if($subj->start_time && $subj->end_time)
                                                        <span class="text-[10px] text-theme-text-muted shrink-0 pl-1">
                                                            {{ date('h:i A', strtotime($subj->start_time)) }} - {{ date('h:i A', strtotime($subj->end_time)) }}
                                                        </span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>

                </div>

                <!-- Footer -->
                <div class="p-4 bg-secondary/50 flex items-center justify-end gap-2.5">
                    <button type="button" @click="closeBulkModal()"
                            class="px-5 py-2.5 bg-secondary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition">
                        Cancel
                    </button>
                    <button type="submit"
                            :disabled="isSubmittingBulk"
                            class="px-6 py-2.5 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition inline-flex items-center gap-2">
                        <span x-show="isSubmittingBulk" class="animate-spin"><i class="fa-solid fa-circle-notch text-xs"></i></span>
                        <span x-text="isSubmittingBulk ? 'Validating & Saving...' : 'Save Allocations'"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
