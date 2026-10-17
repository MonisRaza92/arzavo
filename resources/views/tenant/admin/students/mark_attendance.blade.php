@extends('layouts.admin')
@section('title', 'Mark Daily Attendance Cockpit')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $studentStateList = $students->map(function($s) use ($existingLogs, $isPastDate) {
        $log = $existingLogs->get($s->id);
        return [
            'id' => $s->id,
            'name' => trim($s->fname . ' ' . $s->lname),
            'initials' => strtoupper(substr($s->fname, 0, 1) . substr($s->lname ?: 'S', 0, 1)),
            'roll' => $s->roll_number,
            'username' => $s->username,
            'status' => $log ? $log->status : null,
            'remarks' => $log ? ($log->remarks ?? '') : '',
            'is_locked' => ($isPastDate && $log !== null),
            'save_state' => '',
            'error_msg' => ''
        ];
    });
@endphp

<script>
    window.attendanceCockpitData = {
        classId: {{ $classId ?? 'null' }},
        date: '{{ $date }}',
        today: '{{ $today }}',
        isPastDate: {{ $isPastDate ? 'true' : 'false' }},
        isHoliday: {{ $activeHoliday ? 'true' : 'false' }},
        quickMarkUrl: '{{ route('admin.students.attendance.quick-mark') }}',
        csrfToken: '{{ csrf_token() }}',
        students: @json($studentStateList)
    };

    window.dailyAttendanceCockpit = function dailyAttendanceCockpit() {
        return {
            classId: window.attendanceCockpitData.classId,
            date: window.attendanceCockpitData.date,
            today: window.attendanceCockpitData.today,
            isPastDate: window.attendanceCockpitData.isPastDate,
            isHoliday: window.attendanceCockpitData.isHoliday,
            students: window.attendanceCockpitData.students || [],
            globalNotice: '',
            globalNoticeType: 'success',

            // Check if all unlocked students are present/absent
            get allPresent() {
                const unlocked = this.students.filter(s => !s.is_locked);
                return unlocked.length > 0 && unlocked.every(s => s.status === 'present');
            },

            showNotice(msg, type = 'success') {
                this.globalNotice = msg;
                this.globalNoticeType = type;
                setTimeout(() => {
                    this.globalNotice = '';
                }, 4000);
            },

            markStudent(student, status) {
                if (this.isHoliday) {
                    this.showNotice('This date is an official declared holiday. Attendance is automatically marked for all students.', 'warning');
                    return;
                }

                if (student.is_locked) {
                    this.showNotice('Past attendance for ' + student.name + ' is permanently locked and cannot be modified.', 'warning');
                    return;
                }

                // If already this status, do nothing
                if (student.status === status && student.save_state === 'saved') {
                    return;
                }

                student.status = status;
                this.saveSingleStudent(student);
            },

            saveRemarks(student) {
                if (this.isHoliday || student.is_locked) return;
                // Only send if student has a status set
                if (student.status) {
                    this.saveSingleStudent(student);
                }
            },

            saveSingleStudent(student) {
                student.save_state = 'saving';
                student.error_msg = '';

                fetch(window.attendanceCockpitData.quickMarkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.attendanceCockpitData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        student_id: student.id,
                        class_id: this.classId,
                        date: this.date,
                        status: student.status,
                        remarks: student.remarks || null
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Failed to save attendance record.');
                    }
                    return data;
                })
                .then(data => {
                    student.save_state = 'saved';
                    if (data.is_locked) {
                        student.is_locked = true;
                    }
                    setTimeout(() => {
                        if (student.save_state === 'saved') {
                            student.save_state = '';
                        }
                    }, 2500);
                })
                .catch(err => {
                    student.save_state = 'error';
                    student.error_msg = err.message;
                    this.showNotice(err.message, 'danger');
                });
            },

            bulkMark(status) {
                if (this.isHoliday) {
                    this.showNotice('This date is an official declared holiday. Attendance is automatically marked for all students.', 'warning');
                    return;
                }

                const targetStudents = this.students.filter(s => !s.is_locked);
                if (targetStudents.length === 0) {
                    this.showNotice('All students on this date are locked or no students available.', 'warning');
                    return;
                }

                targetStudents.forEach(student => {
                    student.status = status;
                    this.saveSingleStudent(student);
                });

                this.showNotice('Auto-saving ' + status + ' for ' + targetStudents.length + ' students...', 'success');
            }
        };
    };
</script>

<div x-data="dailyAttendanceCockpit()" x-cloak class="space-y-5">

    <!-- GLOBAL NOTICE TOAST -->
    <template x-if="globalNotice">
        <div class="rounded-lg p-4 text-xs font-normal flex items-center justify-between transition-all"
             :class="{
                 'bg-success-bg text-success': globalNoticeType === 'success',
                 'bg-warning-bg text-warning': globalNoticeType === 'warning',
                 'bg-danger-bg text-danger': globalNoticeType === 'danger'
             }">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid" :class="{
                    'fa-circle-check': globalNoticeType === 'success',
                    'fa-triangle-exclamation': globalNoticeType === 'warning',
                    'fa-circle-exclamation': globalNoticeType === 'danger'
                }"></i>
                <span x-text="globalNotice"></span>
            </div>
            <button type="button" @click="globalNotice = ''" class="hover:opacity-75 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </template>

    <!-- STANDARD PAGE HEADER BANNER -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Daily Attendance Marking Cockpit
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Tap to mark attendance instantly with automatic background saving. Future dates are restricted, and past records become permanently locked once marked.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.students.attendance') }}" class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Back to Attendance Ledger</span>
            </a>
        </div>
    </div>

    <!-- COHORT & DATE CONFIGURATION FORM -->
    <div class="rounded-lg bg-primary p-5">
        <form method="GET" action="{{ route('admin.students.attendance.mark') }}" id="cockpitFilterForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 items-end">
            <!-- Date Picker (Restricted: max="today") -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="dateSelect" class="text-xs font-normal text-theme-text">Session Date</label>
                    @if($date !== $today)
                        <a href="{{ route('admin.students.attendance.mark', ['date' => $today, 'class_id' => $classId]) }}"
                           class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                            <i class="fa-solid fa-bolt text-[10px]"></i> Jump to Today
                        </a>
                    @else
                        <span class="text-[10px] text-success font-normal inline-flex items-center gap-1">
                            <i class="fa-solid fa-circle-dot text-[8px] animate-pulse"></i> Today's Live Date
                        </span>
                    @endif
                </div>
                <input type="date" name="date" id="dateSelect" value="{{ $date }}"
                       max="{{ $today }}"
                       onchange="document.getElementById('cockpitFilterForm').submit()"
                       class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
            </div>

            <!-- Cohort Class Selector -->
            <div>
                <label for="classSelect" class="block text-xs font-normal text-theme-text mb-1.5">Cohort Class</label>
                <select name="class_id" id="classSelect"
                        onchange="document.getElementById('cockpitFilterForm').submit()"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                    @foreach($categories as $cat)
                        @if($cat->classCourses->isNotEmpty())
                            <optgroup label="{{ $cat->name }}">
                                @foreach($cat->classCourses as $cc)
                                    <option value="{{ $cc->id }}" {{ $classId == $cc->id ? 'selected' : '' }}>
                                        {{ $cat->name }} &bull; {{ $cc->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate text-[11px] text-brand"></i>
                    <span>Refresh Session Sheet</span>
                </button>
            </div>
        </form>
    </div>

    @if($activeHoliday)
        <!-- OFFICIAL HOLIDAY BANNER -->
        <div class="rounded-lg bg-emerald-500/10 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-sm font-semibold shrink-0">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400 text-sm">{{ $activeHoliday->title }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 text-[10px] font-medium">{{ $activeHoliday->type_label }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-primary text-theme-text-muted text-[10px]">Zero-Click Auto Attendance</span>
                    </div>
                    <p class="text-[11px] text-theme-text-muted mt-0.5">
                        Official holiday declared for this session date. All enrolled cohort students are automatically marked with Holiday status.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.holidays.index') }}"
               class="px-4 py-1.5 rounded-full bg-primary text-theme-text hover:bg-subtle text-xs transition inline-flex items-center gap-1.5 self-start sm:self-auto shadow-xs">
                <i class="fa-solid fa-calendar-days text-[11px] text-brand"></i>
                <span>Manage Holidays</span>
            </a>
        </div>
    @endif

    @if($selectedClass)
        <div class="rounded-lg bg-primary overflow-hidden space-y-0">
            <!-- Toolbar Header -->
            <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-3 border-b-0">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-semibold text-theme-text">Class Roster Sheet</h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                            {{ $selectedClass->name }}
                        </span>
                        @if($activeHoliday)
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 text-[10px] font-medium inline-flex items-center gap-1">
                                <i class="fa-solid fa-umbrella-beach text-[9px]"></i> Declared Holiday
                            </span>
                        @elseif($isPastDate)
                            <span class="px-2.5 py-0.5 rounded-full bg-warning-bg text-warning text-[10px] font-normal inline-flex items-center gap-1">
                                <i class="fa-solid fa-lock text-[9px]"></i> Past Date (Locked records protected)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-success-bg text-success text-[10px] font-normal inline-flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[9px]"></i> Today's Live Attendance
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-theme-text-muted">
                        Session: {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }} &bull; {{ $students->count() }} enrolled students
                    </p>
                </div>

                <!-- Quick Bulk Actions (Only marks unlocked students) -->
                <div class="flex items-center gap-2 flex-wrap">
                    @if(!$activeHoliday)
                        <button type="button" @click="bulkMark('present')"
                                class="px-3.5 py-2 rounded-full bg-success-bg text-success text-xs font-normal hover:opacity-85 transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-[10px]"></i> Mark All Present
                        </button>
                        <button type="button" @click="bulkMark('absent')"
                                class="px-3.5 py-2 rounded-full bg-danger-bg text-danger text-xs font-normal hover:opacity-85 transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark text-[10px]"></i> Mark All Absent
                        </button>
                        <button type="button" @click="bulkMark('late')"
                                class="px-3.5 py-2 rounded-full bg-warning-bg text-warning text-xs font-normal hover:opacity-85 transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-[10px]"></i> Mark All Late
                        </button>
                    @else
                        <span class="px-3.5 py-2 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-normal inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-umbrella-beach text-[10px]"></i> All Students Auto-Marked for Holiday
                        </span>
                    @endif
                </div>
            </div>

            <!-- DESKTOP TABLE VIEW (hidden on mobile, visible on md and up) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-secondary text-theme-text-muted font-normal">
                            <th class="py-3 px-4 pl-5">Student</th>
                            <th class="py-3 px-4">Presence Status (Tap to Auto-Save)</th>
                            <th class="py-3 px-4 pr-5">Session Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0">
                        <template x-for="student in students" :key="student.id">
                            <tr class="hover:bg-secondary/40 transition">
                                <!-- Student Info -->
                                <td class="py-3.5 px-4 pl-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                             x-text="student.initials">
                                        </div>
                                        <div>
                                            <div class="font-semibold text-theme-text flex items-center gap-2">
                                                <span x-text="student.name"></span>
                                                <template x-if="student.roll">
                                                    <span class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text-muted font-normal"
                                                          x-text="'Roll: ' + student.roll"></span>
                                                </template>
                                            </div>
                                            <div class="text-[11px] text-theme-text-muted flex items-center gap-2 mt-0.5">
                                                <span x-text="'@' + student.username"></span>
                                                <!-- Auto-save state feedback -->
                                                <span x-show="student.save_state === 'saving'" class="text-theme-text-muted animate-pulse inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-notch fa-spin text-brand text-[9px]"></i> Saving...
                                                </span>
                                                <span x-show="student.save_state === 'saved'" class="text-success inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Auto-Saved
                                                </span>
                                                <span x-show="student.save_state === 'error'" class="text-danger inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Save Failed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Presence Status (Checkbox / Radio Buttons with Dynamic Color on Click) -->
                                <td class="py-3.5 px-4">
                                    @if($activeHoliday)
                                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold text-xs">
                                            <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                            <span>Official Holiday (Auto-Marked)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <!-- Locked Notice for past marked records -->
                                            <template x-if="student.is_locked">
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary text-theme-text-muted text-[11px]">
                                                    <i class="fa-solid fa-lock text-[10px] text-brand"></i>
                                                    <span>Locked (Marked)</span>
                                                </div>
                                            </template>

                                            <template x-if="student.status === 'holiday'">
                                                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold text-xs">
                                                    <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                                    <span>Official Holiday</span>
                                                </div>
                                            </template>

                                            <!-- Interactive Pills (Active when not locked) -->
                                            <div class="inline-flex items-center p-1 bg-secondary rounded-full gap-1">
                                                <!-- PRESENT (Turns Vibrant Green when checked) -->
                                                <button type="button"
                                                        @click="markStudent(student, 'present')"
                                                        :disabled="student.is_locked"
                                                        :class="{
                                                            'bg-emerald-600 text-white shadow-xs font-semibold': student.status === 'present',
                                                            'text-theme-text-muted hover:text-theme-text': student.status !== 'present',
                                                            'cursor-not-allowed opacity-75': student.is_locked
                                                        }"
                                                        class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-check text-[10px]" x-show="student.status === 'present'"></i>
                                                    <span>Present</span>
                                                </button>

                                                <!-- ABSENT (Turns Vibrant Red when checked) -->
                                                <button type="button"
                                                        @click="markStudent(student, 'absent')"
                                                        :disabled="student.is_locked"
                                                        :class="{
                                                            'bg-rose-600 text-white shadow-xs font-semibold': student.status === 'absent',
                                                            'text-theme-text-muted hover:text-theme-text': student.status !== 'absent',
                                                            'cursor-not-allowed opacity-75': student.is_locked
                                                        }"
                                                        class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-xmark text-[10px]" x-show="student.status === 'absent'"></i>
                                                    <span>Absent</span>
                                                </button>

                                                <!-- LATE (Turns Vibrant Amber when checked) -->
                                                <button type="button"
                                                        @click="markStudent(student, 'late')"
                                                        :disabled="student.is_locked"
                                                        :class="{
                                                            'bg-amber-600 text-white shadow-xs font-semibold': student.status === 'late',
                                                            'text-theme-text-muted hover:text-theme-text': student.status !== 'late',
                                                            'cursor-not-allowed opacity-75': student.is_locked
                                                        }"
                                                        class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-clock text-[10px]" x-show="student.status === 'late'"></i>
                                                    <span>Late</span>
                                                </button>

                                                <!-- HALF-DAY (Turns Vibrant Blue when checked) -->
                                                <button type="button"
                                                        @click="markStudent(student, 'half_day')"
                                                        :disabled="student.is_locked"
                                                        :class="{
                                                            'bg-sky-600 text-white shadow-xs font-semibold': student.status === 'half_day',
                                                            'text-theme-text-muted hover:text-theme-text': student.status !== 'half_day',
                                                            'cursor-not-allowed opacity-75': student.is_locked
                                                        }"
                                                        class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-cloud-sun text-[10px]" x-show="student.status === 'half_day'"></i>
                                                    <span>Half-Day</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <!-- Session Remarks Input (Auto-saves on blur) -->
                                <td class="py-3.5 px-4 pr-5">
                                    <input type="text"
                                           x-model="student.remarks"
                                           @blur="saveRemarks(student)"
                                           @keydown.enter.prevent="saveRemarks(student)"
                                           @if($activeHoliday) disabled @else :disabled="student.is_locked" @endif
                                           placeholder="{{ $activeHoliday ? 'Official Holiday (' . $activeHoliday->title . ')' : ($isPastDate ? 'Locked record' : 'Notes (auto-saves on blur)...') }}"
                                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed">
                                </td>
                            </tr>
                        </template>

                        <template x-if="students.length === 0">
                            <tr>
                                <td colspan="3" class="py-12 px-4 text-center">
                                    <div class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-lg mx-auto mb-3">
                                        <i class="fa-solid fa-users-slash"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-theme-text">No Enrolled Students</h4>
                                    <p class="text-xs text-theme-text-muted mt-1 max-w-sm mx-auto">
                                        There are no active students assigned to this cohort class.
                                    </p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- MOBILE CARD VIEW (Optimized for Phones & Touchscreens < 768px) -->
            <div class="block md:hidden p-4 space-y-3">
                <template x-for="student in students" :key="student.id">
                    <div class="rounded-lg bg-secondary/50 p-4 space-y-3">
                        <!-- Student Header -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                     x-text="student.initials">
                                </div>
                                <div>
                                    <div class="font-semibold text-theme-text text-xs flex items-center gap-1.5">
                                        <span x-text="student.name"></span>
                                        <template x-if="student.roll">
                                            <span class="px-1.5 py-0.2 rounded-full bg-primary text-[10px] text-theme-text-muted font-normal"
                                                  x-text="'#' + student.roll"></span>
                                        </template>
                                    </div>
                                    <p class="text-[10px] text-theme-text-muted" x-text="'@' + student.username"></p>
                                </div>
                            </div>

                            <!-- Mobile Auto-save indicator -->
                            <div>
                                <span x-show="student.save_state === 'saving'" class="text-[10px] text-theme-text-muted animate-pulse">
                                    <i class="fa-solid fa-circle-notch fa-spin text-brand text-[8px]"></i> Saving
                                </span>
                                <span x-show="student.save_state === 'saved'" class="text-[10px] text-success">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Saved
                                </span>
                                <span x-show="student.is_locked" class="px-2 py-0.5 rounded-full bg-secondary text-theme-text-muted text-[10px]">
                                    <i class="fa-solid fa-lock text-[8px] text-brand"></i> Locked
                                </span>
                            </div>
                        </div>

                    @if($activeHoliday)
                        <div class="py-2.5 px-3 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-semibold flex items-center justify-center gap-1.5 w-full">
                            <i class="fa-solid fa-umbrella-beach text-[11px]"></i>
                            <span>Official Holiday (Auto-Marked)</span>
                        </div>
                    @else
                        <template x-if="student.status === 'holiday'">
                            <div class="py-2 px-3 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-semibold flex items-center justify-center gap-1.5 w-full mb-1">
                                <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                <span>Official Holiday</span>
                            </div>
                        </template>

                        <!-- Mobile Status Buttons (Touch-friendly 2x2 Grid) -->
                        <div class="grid grid-cols-2 gap-1.5">
                            <!-- Present -->
                            <button type="button"
                                    @click="markStudent(student, 'present')"
                                    :disabled="student.is_locked"
                                    :class="{
                                        'bg-emerald-600 text-white font-semibold': student.status === 'present',
                                        'bg-primary text-theme-text-muted': student.status !== 'present',
                                        'opacity-60 cursor-not-allowed': student.is_locked
                                    }"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-check text-[10px]" x-show="student.status === 'present'"></i>
                                <span>Present</span>
                            </button>

                            <!-- Absent -->
                            <button type="button"
                                    @click="markStudent(student, 'absent')"
                                    :disabled="student.is_locked"
                                    :class="{
                                        'bg-rose-600 text-white font-semibold': student.status === 'absent',
                                        'bg-primary text-theme-text-muted': student.status !== 'absent',
                                        'opacity-60 cursor-not-allowed': student.is_locked
                                    }"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-xmark text-[10px]" x-show="student.status === 'absent'"></i>
                                <span>Absent</span>
                            </button>

                            <!-- Late -->
                            <button type="button"
                                    @click="markStudent(student, 'late')"
                                    :disabled="student.is_locked"
                                    :class="{
                                        'bg-amber-600 text-white font-semibold': student.status === 'late',
                                        'bg-primary text-theme-text-muted': student.status !== 'late',
                                        'opacity-60 cursor-not-allowed': student.is_locked
                                    }"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-clock text-[10px]" x-show="student.status === 'late'"></i>
                                <span>Late</span>
                            </button>

                            <!-- Half-Day -->
                            <button type="button"
                                    @click="markStudent(student, 'half_day')"
                                    :disabled="student.is_locked"
                                    :class="{
                                        'bg-sky-600 text-white font-semibold': student.status === 'half_day',
                                        'bg-primary text-theme-text-muted': student.status !== 'half_day',
                                        'opacity-60 cursor-not-allowed': student.is_locked
                                    }"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-cloud-sun text-[10px]" x-show="student.status === 'half_day'"></i>
                                <span>Half-Day</span>
                            </button>
                        </div>
                    @endif

                    <!-- Mobile Remarks Input -->
                    <div>
                        <input type="text"
                               x-model="student.remarks"
                               @blur="saveRemarks(student)"
                               @keydown.enter.prevent="saveRemarks(student)"
                               @if($activeHoliday) disabled @else :disabled="student.is_locked" @endif
                               placeholder="{{ $activeHoliday ? 'Official Holiday (' . $activeHoliday->title . ')' : ($isPastDate ? 'Record locked' : 'Leave remarks (auto-saves on blur)...') }}"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-3.5 py-2 rounded-full focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed">
                    </div>
                    </div>
                </template>

                <template x-if="students.length === 0">
                    <div class="py-8 text-center text-xs text-theme-text-muted">
                        No students enrolled in this cohort class.
                    </div>
                </template>
            </div>

            <!-- Bottom Auto-Save Status Bar (No manual save button required!) -->
            <div class="p-4 bg-primary flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-theme-text-muted border-t-0">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                    <span>Live Auto-Save Active: Every tap records attendance instantly over AJAX.</span>
                </div>
                <div class="text-[11px] text-theme-text-muted">
                    <span>* Unchecked students have not been recorded yet.</span>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-lg bg-primary p-8 text-center text-xs text-theme-text-muted">
            <i class="fa-solid fa-graduation-cap text-2xl mb-2 block opacity-50"></i>
            No class cohorts registered in the system. Please configure academic categories and classes first.
        </div>
    @endif

</div>
@endsection
