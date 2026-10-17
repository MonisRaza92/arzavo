@extends('layouts.admin')
@section('title', 'Create Batch / Section')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="batchCreateComponent()" x-init="init()" class="space-y-5">

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
                    Create New Batch / Section
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Configure classroom sections for schools and specialized subject batches for coaching centers. Add multiple subject periods, customize period teachers & timings, and review the automatically computed batch schedule.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.batches.index') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Back to Batches</span>
            </a>
            <button type="button"
                    @click="submitForm()"
                    :disabled="hasCollision || periods.length === 0"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition inline-flex items-center gap-2"
                    :class="(hasCollision || periods.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'">
                <i class="fa-solid fa-check text-[11px]"></i>
                <span>Save & Create Batch</span>
            </button>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('error'))
        <div class="p-4 rounded-lg bg-danger-bg text-danger text-xs flex items-center gap-2.5">
            <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Realtime Overlap / Collision Warning Banner --}}
    <div x-show="hasCollision" x-cloak
         class="p-4 rounded-lg bg-danger-bg text-danger text-xs flex items-start gap-3 shadow-sm transition">
        <i class="fa-solid fa-triangle-exclamation text-base shrink-0 mt-0.5"></i>
        <div class="space-y-1">
            <p class="font-semibold">Schedule Time Collision Detected!</p>
            <p class="text-[11px] leading-relaxed" x-text="collisionMessage"></p>
            <p class="text-[10px] opacity-80">
                Rule: A subject period must finish before the next subject begins. Please adjust the start or end timings to resolve the collision.
            </p>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- 2. MAIN FORM CONTAINER                                   --}}
    {{-- ======================================================== --}}
    <form id="batchCreateForm" action="{{ route('admin.batches.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT COLUMN: BATCH DETAILS & SUBJECT PERIODS (2 COLS) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- CARD 1: GENERAL BATCH IDENTITY & CLASS SELECTION --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-2">
                        <i class="fa-solid fa-id-card text-brand text-xs"></i>
                        <h2 class="text-sm font-semibold text-theme-text">Batch Identity & Class Allocation</h2>
                    </div>

                    {{-- Academic Track: Category & Class Cascading --}}
                    <div class="p-4 rounded-lg bg-secondary space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Academic Category --}}
                            <div>
                                <label class="block text-xs font-semibold text-theme-text mb-1">
                                    Academic Category <span class="text-danger">*</span>
                                </label>
                                <select x-model="selectedCategoryId"
                                        @change="onCategoryChange()"
                                        class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal"
                                        required>
                                    <option value="">-- Select Category --</option>
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Class / Course --}}
                            <div>
                                <label class="block text-xs font-semibold text-theme-text mb-1">
                                    Class / Course <span class="text-danger">*</span>
                                </label>
                                <select name="class_course_id"
                                        x-model="selectedClassId"
                                        @change="onClassChange()"
                                        class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal"
                                        :disabled="!selectedCategoryId"
                                        required>
                                    <option value="">-- Select Class / Course --</option>
                                    <template x-for="cls in filteredClasses" :key="cls.id">
                                        <option :value="cls.id" x-text="cls.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Batch Name & Code --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <x-input.text name="name" label="Batch / Section Name" required placeholder="e.g. Section A (Morning), Batch Alpha - PCM, NEET 2026" />
                        </div>
                        <div>
                            <x-input.text name="code" label="Batch Code (Optional)" placeholder="e.g. SEC-A" />
                        </div>
                    </div>

                    {{-- Dedicated Head Mentor / Class Teacher (MANDATORY RULE) --}}
                    <div>
                        <label class="block text-xs font-semibold text-theme-text mb-1">
                            Batch Head Mentor / Class Teacher <span class="text-danger">*</span>
                        </label>
                        <select name="teacher_id"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal"
                                required>
                            <option value="">-- Select Active Teacher / Faculty --</option>
                            <template x-for="t in teachers" :key="t.id">
                                <option :value="t.id" x-text="t.fname + ' ' + (t.lname || '') + ' (' + (t.email || '') + ')'"></option>
                            </template>
                        </select>
                        <p class="text-[10px] text-theme-text-muted mt-1">
                            Overall faculty mentor responsible for this batch/section's attendance, discipline & parent communication.
                        </p>
                    </div>

                    {{-- Room & Max Capacity --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <x-input.text name="room_number" label="Homeroom / Classroom (Optional)" placeholder="e.g. Room 101, Hall A" />
                        <x-input.text type="number" name="max_students" label="Max Seat Capacity (Optional)" placeholder="e.g. 40" value="35" min="1" />
                    </div>

                    {{-- Description --}}
                    <x-input.textarea name="description" label="Description / Academic Focus (Optional)" rows="2" placeholder="Key goals, prerequisite criteria, or section specialization..." />

                    {{-- Status Toggle --}}
                    <x-input.toggle name="status" label="Active Status" hint="Open for student enrollments" :value="true" />
                </div>

                {{-- CARD 2: MULTI-SUBJECT PERIODS & TIMETABLE SCHEDULE (THE CORE SYSTEM!) --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-brand text-xs"></i>
                                <h2 class="text-sm font-semibold text-theme-text">Subject Periods & Timetable Schedule</h2>
                            </div>
                            <p class="text-[11px] text-theme-text-muted">
                                Add all subjects taught in this batch. Customize each period's teacher, timings & days (or inherit default subject values).
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] bg-secondary text-theme-text-muted font-normal"
                                  x-text="periods.length + ' Subject ' + (periods.length === 1 ? 'Period' : 'Periods')">
                            </span>
                        </div>
                    </div>

                    {{-- Add Subject Period Selector Bar --}}
                    <div class="p-3.5 rounded-lg bg-secondary flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <select x-model="subjectToAdd"
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal"
                                    :disabled="!selectedClassId || availableSubjects.length === 0">
                                <option value="">-- Choose Subject to Add as Period --</option>
                                <template x-for="sub in availableSubjects" :key="sub.id">
                                    <option :value="sub.id" x-text="sub.name + (sub.teacher ? ' (Teacher: ' + sub.teacher.fname + ')' : '')"></option>
                                </template>
                            </select>
                        </div>
                        <button type="button"
                                @click="addPeriod()"
                                :disabled="!subjectToAdd"
                                class="px-5 py-2.5 rounded-full bg-brand text-primary text-xs font-normal transition inline-flex items-center justify-center gap-1.5 shrink-0"
                                :class="!subjectToAdd ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Period</span>
                        </button>
                    </div>

                    {{-- Empty State when 0 periods --}}
                    <template x-if="periods.length === 0">
                        <div class="p-8 rounded-lg bg-secondary text-center space-y-2">
                            <div class="w-10 h-10 rounded-full bg-primary text-theme-text-muted flex items-center justify-center text-sm mx-auto">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </div>
                            <p class="text-xs font-semibold text-theme-text">No Subject Periods Added</p>
                            <p class="text-[11px] text-theme-text-muted max-w-sm mx-auto">
                                Select a Class / Course above, then pick subjects to schedule periods into this batch.
                            </p>
                        </div>
                    </template>

                    {{-- List of Added Subject Periods --}}
                    <div class="space-y-3.5">
                        <template x-for="(period, index) in periods" :key="period.uid">
                            <div class="p-4 rounded-lg bg-secondary space-y-3 transition"
                                 :class="period.hasError ? 'ring-2 ring-danger/40 bg-danger-bg/10' : ''">

                                {{-- Period Header Row --}}
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-brand text-primary flex items-center justify-center text-[10px] font-semibold shrink-0"
                                              x-text="index + 1"></span>
                                        <div class="min-w-0">
                                            <h3 class="text-xs font-semibold text-theme-text truncate" x-text="period.subject_name"></h3>
                                            <template x-if="period.is_overridden">
                                                <span class="text-[9px] text-brand bg-brand/10 px-2 py-0.5 rounded-full inline-block">
                                                    Custom Batch Schedule
                                                </span>
                                            </template>
                                            <template x-if="!period.is_overridden">
                                                <span class="text-[9px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full inline-block">
                                                    Inherited from Subject
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        {{-- Duration Badge --}}
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary text-theme-text font-normal"
                                              x-text="getPeriodDuration(period)"></span>

                                        {{-- Remove Button --}}
                                        <button type="button"
                                                @click="removePeriod(index)"
                                                class="w-7 h-7 rounded-full bg-primary text-danger hover:bg-danger-bg flex items-center justify-center text-xs transition"
                                                title="Remove Period">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Hidden Form Input Fields --}}
                                <input type="hidden" :name="'periods[' + index + '][subject_id]'" :value="period.subject_id">
                                <input type="hidden" :name="'periods[' + index + '][days]'" :value="period.days">

                                {{-- Period Fields: Teacher, Timings, Room --}}
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    {{-- Period Teacher Override --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-theme-text mb-1">
                                            Period Faculty / Teacher
                                        </label>
                                        <select :name="'periods[' + index + '][teacher_id]'"
                                                x-model="period.teacher_id"
                                                @change="onPeriodChange()"
                                                class="w-full bg-primary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none font-normal">
                                            <option value="">-- Use Subject Default --</option>
                                            <template x-for="t in teachers" :key="t.id">
                                                <option :value="t.id" x-text="t.fname + ' ' + (t.lname || '')"></option>
                                            </template>
                                        </select>
                                    </div>

                                    {{-- Start Time --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-theme-text mb-1">
                                            Start Time <span class="text-danger">*</span>
                                        </label>
                                        <input type="time"
                                               :name="'periods[' + index + '][start_time]'"
                                               x-model="period.start_time"
                                               @input="onPeriodChange()"
                                               class="w-full bg-primary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none font-normal"
                                               required>
                                    </div>

                                    {{-- End Time --}}
                                    <div>
                                        <label class="block text-[11px] font-semibold text-theme-text mb-1">
                                            End Time <span class="text-danger">*</span>
                                        </label>
                                        <input type="time"
                                               :name="'periods[' + index + '][end_time]'"
                                               x-model="period.end_time"
                                               @input="onPeriodChange()"
                                               class="w-full bg-primary text-theme-text text-xs px-3.5 py-2 rounded-full focus:outline-none font-normal"
                                               required>
                                    </div>
                                </div>

                                {{-- Period Days Strip (Interactive S M T W T F S) --}}
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-theme-text-muted">Schedule Days:</span>
                                        <span class="text-[10px] text-brand font-medium" x-text="period.days || 'All Days'"></span>
                                    </div>

                                    <div class="flex items-center justify-between gap-1 p-1 rounded-full bg-primary">
                                        <template x-for="day in weekDays" :key="day.key">
                                            <button type="button"
                                                    @click="togglePeriodDay(period, day.key)"
                                                    class="flex-1 py-1 rounded-full text-center text-[10px] transition select-none cursor-pointer"
                                                    :class="isDaySelected(period, day.key)
                                                        ? 'bg-brand text-primary font-semibold shadow-xs'
                                                        : 'text-theme-text-muted hover:text-theme-text font-normal'"
                                                    x-text="day.letter"
                                                    :title="day.key">
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Optional Room --}}
                                <div class="pt-1">
                                    <input type="text"
                                           :name="'periods[' + index + '][room_number]'"
                                           x-model="period.room_number"
                                           placeholder="Classroom / Lab (e.g. Physics Lab, Room 204)"
                                           class="w-full bg-primary text-theme-text text-xs px-3.5 py-1.5 rounded-full focus:outline-none font-normal">
                                </div>

                            </div>
                        </template>
                    </div>

                </div>

            </div>

            {{-- RIGHT COLUMN: REAL-TIME SCHEDULE CALCULATION SUMMARY & PREVIEW (1 COL) --}}
            <div class="space-y-5">

                {{-- CARD 3: REAL-TIME TIMINGS & SCHEDULE CALCULATION --}}
                <div class="rounded-lg bg-primary p-5 space-y-4 sticky top-20">
                    <div class="flex items-center justify-between pb-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calculator text-brand text-xs"></i>
                            <h2 class="text-sm font-semibold text-theme-text">Batch Schedule Summary</h2>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-brand/10 text-brand font-normal">
                            Auto-Calculated
                        </span>
                    </div>

                    {{-- Overall Calculated Timing Metrics --}}
                    <div class="p-4 rounded-lg bg-secondary space-y-3">
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="p-2.5 rounded-lg bg-primary">
                                <span class="text-[10px] text-theme-text-muted block">Batch Start</span>
                                <span class="text-xs font-semibold text-theme-text" x-text="computedSchedule.startTime || '--:--'"></span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-primary">
                                <span class="text-[10px] text-theme-text-muted block">Batch End</span>
                                <span class="text-xs font-semibold text-theme-text" x-text="computedSchedule.endTime || '--:--'"></span>
                            </div>
                        </div>

                        {{-- Total Duration & Break Hours --}}
                        <div class="space-y-1.5 pt-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-theme-text-muted">Total Batch Span:</span>
                                <span class="font-semibold text-theme-text" x-text="computedSchedule.totalDurationStr"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-theme-text-muted">Class Study Time:</span>
                                <span class="font-normal text-theme-text" x-text="computedSchedule.teachingTimeStr"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-theme-text-muted">Recess / Break Time:</span>
                                <span class="font-semibold"
                                      :class="computedSchedule.breakMinutes > 0 ? 'text-brand' : 'text-theme-text-muted'"
                                      x-text="computedSchedule.breakTimeStr"></span>
                            </div>
                        </div>

                        {{-- Break Callout --}}
                        <template x-if="computedSchedule.breakMinutes > 0">
                            <div class="p-2 rounded-lg bg-primary text-[11px] text-brand flex items-center gap-2">
                                <i class="fa-solid fa-mug-hot"></i>
                                <span x-text="'Includes ' + computedSchedule.breakTimeStr + ' break between subject periods.'"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Chronological Periods & Breaks Timeline --}}
                    <div class="space-y-2">
                        <span class="text-[11px] font-semibold text-theme-text-muted uppercase tracking-wider block">
                            Daily Schedule Timeline
                        </span>

                        <template x-if="computedSchedule.timeline.length === 0">
                            <p class="text-xs text-theme-text-muted p-3 bg-secondary rounded-lg text-center">
                                Add periods to see the chronological timeline with breaks.
                            </p>
                        </template>

                        <div class="space-y-1.5">
                            <template x-for="item in computedSchedule.timeline" :key="item.uid">
                                <div>
                                    {{-- Class Period Row --}}
                                    <template x-if="item.type === 'period'">
                                        <div class="p-2.5 rounded-lg bg-secondary flex items-center justify-between gap-2 text-xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <i class="fa-solid fa-book-open text-brand text-[10px]"></i>
                                                <span class="font-semibold text-theme-text truncate" x-text="item.name"></span>
                                            </div>
                                            <span class="text-[11px] text-theme-text-muted font-normal shrink-0"
                                                  x-text="item.startStr + ' - ' + item.endStr"></span>
                                        </div>
                                    </template>

                                    {{-- Recess / Break Row --}}
                                    <template x-if="item.type === 'break'">
                                        <div class="p-2 rounded-lg bg-brand/10 text-brand flex items-center justify-between gap-2 text-[11px] my-1">
                                            <div class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-mug-hot text-[10px]"></i>
                                                <span class="font-medium" x-text="'Break (' + item.durationStr + ')'"></span>
                                            </div>
                                            <span class="text-[10px] opacity-80" x-text="item.startStr + ' - ' + item.endStr"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Architecture Information Note --}}
                    <div class="p-3.5 rounded-lg bg-secondary text-[11px] text-theme-text-muted space-y-1.5 leading-relaxed">
                        <p class="font-semibold text-theme-text flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-brand text-xs"></i>
                            <span>How Scheduling Works</span>
                        </p>
                        <p>
                            • <strong>Batch Enrolled:</strong> If a student is assigned to this batch, they follow these customized periods, teachers, and timings.
                        </p>
                        <p>
                            • <strong>Subject Direct:</strong> If a student only chooses individual subjects (coaching mode), they get the subject's default teacher and timings.
                        </p>
                        <p>
                            • <strong>Batch Timings:</strong> The batch start and end hours are calculated from the first and last subject periods.
                        </p>
                    </div>

                    {{-- Submit CTA Button --}}
                    <button type="button"
                            @click="submitForm()"
                            :disabled="hasCollision || periods.length === 0"
                            class="w-full py-3 rounded-full bg-brand text-primary text-xs font-normal transition flex items-center justify-center gap-2 shadow-sm"
                            :class="(hasCollision || periods.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'">
                        <i class="fa-solid fa-plus text-[11px]"></i>
                        <span>Create Batch & Save Schedule</span>
                    </button>
                </div>

            </div>

        </div>

    </form>

</div>

{{-- Alpine Component Logic --}}
<script>
    function batchCreateComponent() {
        return {
            categories: @json($categories),
            classes: @json($classes),
            teachers: @json($teachers),

            selectedCategoryId: '',
            selectedClassId: '',
            filteredClasses: [],
            availableSubjects: [],
            subjectToAdd: '',

            weekDays: [
                { key: 'Sun', letter: 'S' },
                { key: 'Mon', letter: 'M' },
                { key: 'Tue', letter: 'T' },
                { key: 'Wed', letter: 'W' },
                { key: 'Thu', letter: 'T' },
                { key: 'Fri', letter: 'F' },
                { key: 'Sat', letter: 'S' },
            ],

            periods: [],
            hasCollision: false,
            collisionMessage: '',

            computedSchedule: {
                startTime: '',
                endTime: '',
                totalDurationStr: '0m',
                teachingTimeStr: '0m',
                breakTimeStr: 'No Break',
                breakMinutes: 0,
                timeline: []
            },

            init() {
                if (this.categories && this.categories.length > 0) {
                    this.selectedCategoryId = this.categories[0].id;
                    this.onCategoryChange();
                }
            },

            onCategoryChange() {
                const catId = parseInt(this.selectedCategoryId);
                this.selectedClassId = '';
                this.filteredClasses = [];
                this.availableSubjects = [];
                this.subjectToAdd = '';
                this.periods = [];

                if (!catId) return;
                const foundCat = this.categories.find(c => c.id === catId);
                if (foundCat && foundCat.class_courses) {
                    this.filteredClasses = foundCat.class_courses;
                    if (this.filteredClasses.length > 0) {
                        this.selectedClassId = this.filteredClasses[0].id;
                        this.onClassChange();
                    }
                }
            },

            onClassChange() {
                const clsId = parseInt(this.selectedClassId);
                this.availableSubjects = [];
                this.subjectToAdd = '';
                this.periods = [];

                if (!clsId) return;
                const foundCls = this.classes.find(c => c.id === clsId);
                if (foundCls && foundCls.subjects) {
                    this.availableSubjects = foundCls.subjects;
                    if (this.availableSubjects.length > 0) {
                        this.subjectToAdd = this.availableSubjects[0].id;
                    }
                }
                this.recalculateAll();
            },

            addPeriod() {
                const subId = parseInt(this.subjectToAdd);
                if (!subId) return;

                const sub = this.availableSubjects.find(s => s.id === subId);
                if (!sub) return;

                // Smart timing: calculate proposed start after previous period if available
                let defaultStart = sub.start_time ? sub.start_time.substring(0, 5) : '09:00';
                let defaultEnd = sub.end_time ? sub.end_time.substring(0, 5) : '10:00';

                if (this.periods.length > 0) {
                    const lastPeriod = this.periods[this.periods.length - 1];
                    if (lastPeriod.end_time) {
                        defaultStart = lastPeriod.end_time;
                        // Default 1 hour later
                        const parts = defaultStart.split(':').map(Number);
                        let endH = parts[0] + 1;
                        let endM = parts[1];
                        if (endH >= 24) endH = 23;
                        defaultEnd = String(endH).padStart(2, '0') + ':' + String(endM).padStart(2, '0');
                    }
                }

                const uid = 'p_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);

                this.periods.push({
                    uid: uid,
                    subject_id: sub.id,
                    subject_name: sub.name,
                    teacher_id: sub.teacher_id || '',
                    start_time: defaultStart,
                    end_time: defaultEnd,
                    days: sub.days || 'Mon, Tue, Wed, Thu, Fri, Sat',
                    room_number: '',
                    hasError: false,
                    is_overridden: false
                });

                // Set next available subject in dropdown
                const unused = this.availableSubjects.filter(s => !this.periods.some(p => p.subject_id === s.id));
                this.subjectToAdd = unused.length > 0 ? unused[0].id : (this.availableSubjects[0]?.id || '');

                this.recalculateAll();
            },

            removePeriod(index) {
                this.periods.splice(index, 1);
                this.recalculateAll();
            },

            onPeriodChange() {
                this.recalculateAll();
            },

            togglePeriodDay(period, dayKey) {
                let current = period.days ? period.days.split(',').map(d => d.trim()) : [];
                if (current.includes(dayKey)) {
                    current = current.filter(d => d !== dayKey);
                } else {
                    const order = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    current.push(dayKey);
                    current.sort((a, b) => order.indexOf(a) - order.indexOf(b));
                }
                period.days = current.join(', ');
                this.recalculateAll();
            },

            isDaySelected(period, dayKey) {
                if (!period.days) return true;
                const arr = period.days.split(',').map(d => d.trim().toLowerCase());
                return arr.includes(dayKey.toLowerCase());
            },

            getPeriodDuration(period) {
                if (!period.start_time || !period.end_time) return '0m';
                const s = this.timeToMinutes(period.start_time);
                const e = this.timeToMinutes(period.end_time);
                if (e > s) {
                    const diff = e - s;
                    const h = Math.floor(diff / 60);
                    const m = diff % 60;
                    return (h > 0 ? h + 'h ' : '') + (m > 0 ? m + 'm' : (h === 0 ? '0m' : ''));
                }
                return 'Invalid';
            },

            timeToMinutes(timeStr) {
                if (!timeStr) return 0;
                const parts = timeStr.split(':').map(Number);
                return (parts[0] * 60) + (parts[1] || 0);
            },

            minutesToTimeStr(mins) {
                const h = Math.floor(mins / 60) % 24;
                const m = mins % 60;
                const ampm = h >= 12 ? 'PM' : 'AM';
                const dispH = h % 12 === 0 ? 12 : h % 12;
                return dispH + ':' + String(m).padStart(2, '0') + ' ' + ampm;
            },

            recalculateAll() {
                this.hasCollision = false;
                this.collisionMessage = '';

                // Reset period error markers
                this.periods.forEach(p => p.hasError = false);

                if (this.periods.length === 0) {
                    this.computedSchedule = {
                        startTime: '',
                        endTime: '',
                        totalDurationStr: '0m',
                        teachingTimeStr: '0m',
                        breakTimeStr: 'No Break',
                        breakMinutes: 0,
                        timeline: []
                    };
                    return;
                }

                // 1. Basic validation: end > start
                for (let i = 0; i < this.periods.length; i++) {
                    const p = this.periods[i];
                    const s = this.timeToMinutes(p.start_time);
                    const e = this.timeToMinutes(p.end_time);
                    if (e <= s) {
                        p.hasError = true;
                        this.hasCollision = true;
                        this.collisionMessage = `Period #${i + 1} (${p.subject_name}): End Time must be strictly after Start Time!`;
                        return;
                    }
                }

                // 2. Pairwise Collision / Overlap Check on common days
                const allDaysList = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                for (let i = 0; i < this.periods.length; i++) {
                    for (let j = i + 1; j < this.periods.length; j++) {
                        const pA = this.periods[i];
                        const pB = this.periods[j];

                        const daysA = pA.days ? pA.days.split(',').map(d => d.trim()) : allDaysList;
                        const daysB = pB.days ? pB.days.split(',').map(d => d.trim()) : allDaysList;

                        const common = daysA.filter(d => daysB.includes(d));
                        if (common.length > 0) {
                            const startA = this.timeToMinutes(pA.start_time);
                            const endA = this.timeToMinutes(pA.end_time);
                            const startB = this.timeToMinutes(pB.start_time);
                            const endB = this.timeToMinutes(pB.end_time);

                            // Strict Overlap Condition: max(startA, startB) < min(endA, endB)
                            if (Math.max(startA, startB) < Math.min(endA, endB)) {
                                pA.hasError = true;
                                pB.hasError = true;
                                this.hasCollision = true;
                                this.collisionMessage = `Conflict: Period #${i + 1} (${pA.subject_name}, ${this.minutesToTimeStr(startA)} - ${this.minutesToTimeStr(endA)}) overlaps with Period #${j + 1} (${pB.subject_name}, ${this.minutesToTimeStr(startB)} - ${this.minutesToTimeStr(endB)}) on [${common.join(', ')}]. A subject must finish before the next subject begins!`;
                                return;
                            }
                        }
                    }
                }

                // 3. Compute Schedule (Timeline, Span, Teaching Time, Break Time)
                const sorted = [...this.periods].sort((a, b) => this.timeToMinutes(a.start_time) - this.timeToMinutes(b.start_time));
                const earliestMin = this.timeToMinutes(sorted[0].start_time);
                const latestMin = this.timeToMinutes(sorted[sorted.length - 1].end_time);

                let totalClassMins = 0;
                sorted.forEach(p => {
                    const diff = this.timeToMinutes(p.end_time) - this.timeToMinutes(p.start_time);
                    if (diff > 0) totalClassMins += diff;
                });

                const totalSpan = Math.max(0, latestMin - earliestMin);
                const breakMins = Math.max(0, totalSpan - totalClassMins);

                // Build timeline with breaks
                const timeline = [];
                for (let i = 0; i < sorted.length; i++) {
                    const curr = sorted[i];
                    const currStart = this.timeToMinutes(curr.start_time);
                    const currEnd = this.timeToMinutes(curr.end_time);

                    // If gap between previous period end and current period start, insert Break
                    if (i > 0) {
                        const prevEnd = this.timeToMinutes(sorted[i - 1].end_time);
                        if (currStart > prevEnd) {
                            const gap = currStart - prevEnd;
                            const h = Math.floor(gap / 60);
                            const m = gap % 60;
                            const gapStr = (h > 0 ? h + 'h ' : '') + (m > 0 ? m + 'm' : '');
                            timeline.push({
                                uid: 'break_' + i,
                                type: 'break',
                                name: 'Recess / Break',
                                startStr: this.minutesToTimeStr(prevEnd),
                                endStr: this.minutesToTimeStr(currStart),
                                durationStr: gapStr
                            });
                        }
                    }

                    timeline.push({
                        uid: curr.uid,
                        type: 'period',
                        name: curr.subject_name,
                        startStr: this.minutesToTimeStr(currStart),
                        endStr: this.minutesToTimeStr(currEnd),
                        durationStr: this.getPeriodDuration(curr)
                    });
                }

                const spanH = Math.floor(totalSpan / 60);
                const spanM = totalSpan % 60;
                const totalSpanStr = (spanH > 0 ? spanH + 'h ' : '') + (spanM > 0 ? spanM + 'm' : (spanH === 0 ? '0m' : ''));

                const teachH = Math.floor(totalClassMins / 60);
                const teachM = totalClassMins % 60;
                const teachStr = (teachH > 0 ? teachH + 'h ' : '') + (teachM > 0 ? teachM + 'm' : (teachH === 0 ? '0m' : ''));

                const breakH = Math.floor(breakMins / 60);
                const breakM = breakMins % 60;
                const breakStr = breakMins > 0 ? ((breakH > 0 ? breakH + 'h ' : '') + (breakM > 0 ? breakM + 'm' : '')) : 'No Break';

                this.computedSchedule = {
                    startTime: this.minutesToTimeStr(earliestMin),
                    endTime: this.minutesToTimeStr(latestMin),
                    totalDurationStr: totalSpanStr,
                    teachingTimeStr: teachStr,
                    breakTimeStr: breakStr,
                    breakMinutes: breakMins,
                    timeline: timeline
                };
            },

            submitForm() {
                if (this.hasCollision) {
                    alert(this.collisionMessage);
                    return;
                }
                if (this.periods.length === 0) {
                    alert('Please add at least one subject period to the batch.');
                    return;
                }
                document.getElementById('batchCreateForm').submit();
            }
        };
    }
</script>
@endsection
