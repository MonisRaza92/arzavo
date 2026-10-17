@extends('layouts.admin')
@section('title', 'Teacher & Staff Attendance Cockpit')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $teacherStateList = $teachers->map(function($t) use ($existingLogs) {
        $log = $existingLogs->get($t->id);
        return [
            'id' => $t->id,
            'name' => trim($t->fname . ' ' . $t->lname),
            'initials' => strtoupper(substr($t->fname, 0, 1) . substr($t->lname ?: 'T', 0, 1)),
            'designation' => $t->designation ?: 'Faculty Member',
            'username' => $t->username,
            'status' => $log ? $log->status : null,
            'remarks' => $log ? ($log->remarks ?? '') : '',
            'save_state' => '',
            'error_msg' => ''
        ];
    });
@endphp

<script>
    window.staffAttendanceData = {
        date: '{{ $date }}',
        today: '{{ $today }}',
        isHoliday: {{ $activeHoliday ? 'true' : 'false' }},
        quickMarkUrl: '{{ route('admin.teachers.attendance.quick-mark') }}',
        csrfToken: '{{ csrf_token() }}',
        teachers: @json($teacherStateList)
    };

    window.staffAttendanceCockpit = function staffAttendanceCockpit() {
        return {
            date: window.staffAttendanceData.date,
            today: window.staffAttendanceData.today,
            isHoliday: window.staffAttendanceData.isHoliday,
            teachers: window.staffAttendanceData.teachers || [],
            globalNotice: '',
            globalNoticeType: 'success',

            showNotice(msg, type = 'success') {
                this.globalNotice = msg;
                this.globalNoticeType = type;
                setTimeout(() => {
                    this.globalNotice = '';
                }, 4000);
            },

            markTeacher(teacher, status) {
                if (this.isHoliday) {
                    this.showNotice('This date is an official declared holiday. Attendance is automatically marked for all faculty.', 'warning');
                    return;
                }
                teacher.status = status;
                this.saveSingleTeacher(teacher);
            },

            saveRemarks(teacher) {
                if (this.isHoliday) return;
                if (teacher.status) {
                    this.saveSingleTeacher(teacher);
                }
            },

            saveSingleTeacher(teacher) {
                teacher.save_state = 'saving';
                teacher.error_msg = '';

                fetch(window.staffAttendanceData.quickMarkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.staffAttendanceData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: teacher.id,
                        date: this.date,
                        status: teacher.status,
                        remarks: teacher.remarks || null
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed to save attendance.');
                    return data;
                })
                .then(data => {
                    teacher.save_state = 'saved';
                    setTimeout(() => {
                        if (teacher.save_state === 'saved') {
                            teacher.save_state = '';
                        }
                    }, 2500);
                })
                .catch(err => {
                    teacher.save_state = 'error';
                    teacher.error_msg = err.message;
                    this.showNotice(err.message, 'danger');
                });
            },

            bulkMark(status) {
                if (this.isHoliday) {
                    this.showNotice('This date is an official declared holiday. Attendance is automatically marked for all faculty.', 'warning');
                    return;
                }
                this.teachers.forEach(t => {
                    t.status = status;
                    this.saveSingleTeacher(t);
                });
                this.showNotice('Auto-saving ' + status + ' for all ' + this.teachers.length + ' faculty members...', 'success');
            }
        };
    };
</script>

<div x-data="staffAttendanceCockpit()" x-cloak class="space-y-5">

    <!-- GLOBAL NOTICE TOAST -->
    <template x-if="globalNotice">
        <div class="rounded-lg p-4 text-xs font-normal flex items-center justify-between transition-all"
             :class="{
                 'bg-success-bg text-success': globalNoticeType === 'success',
                 'bg-danger-bg text-danger': globalNoticeType === 'danger'
             }">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid" :class="globalNoticeType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'"></i>
                <span x-text="globalNotice"></span>
            </div>
            <button type="button" @click="globalNotice = ''" class="hover:opacity-75 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </template>

    <!-- STANDARD PAGE HEADER BANNER (NO BORDERS, FLAT, THEME TOKENS) -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Teacher &amp; Staff Attendance Cockpit
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Log daily presence sheets for faculty members and educators. Future dates are restricted, and entries auto-save in background.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.teachers') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Teachers Directory</span>
            </a>
            <a href="{{ route('admin.teachers.payroll') }}"
               class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-money-check-dollar text-[11px]"></i>
                <span>Monthly Payroll</span>
            </a>
        </div>
    </div>

    <!-- DATE CONFIGURATION -->
    <div class="rounded-lg bg-primary p-5">
        <form method="GET" action="{{ route('admin.teachers.attendance') }}" id="teacherAttendanceDateForm" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-72">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="sessionDate" class="text-xs font-normal text-theme-text">Session Date</label>
                    @if($date !== $today)
                        <a href="{{ route('admin.teachers.attendance', ['date' => $today]) }}"
                           class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                            <i class="fa-solid fa-bolt text-[10px]"></i> Jump to Today
                        </a>
                    @else
                        <span class="text-[10px] text-success font-normal inline-flex items-center gap-1">
                            <i class="fa-solid fa-circle-dot text-[8px] animate-pulse"></i> Today's Live Date
                        </span>
                    @endif
                </div>
                <input type="date" name="date" id="sessionDate" value="{{ $date }}"
                       max="{{ $today }}"
                       onchange="document.getElementById('teacherAttendanceDateForm').submit()"
                       class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
            </div>

            <div>
                <button type="submit" class="px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate text-[11px] text-brand"></i>
                    <span>Refresh Ledger</span>
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
                        Official institute holiday declared for this session date. All registered faculty members are credited with a paid holiday.
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

    <!-- ATTENDANCE ROSTER SHEET -->
    <div class="rounded-lg bg-primary overflow-hidden space-y-0">
        <!-- Toolbar Header -->
        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-3 border-b-0">
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-sm font-semibold text-theme-text">Staff Roster Sheet</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                    </span>
                    @if($activeHoliday)
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 text-[10px] font-medium inline-flex items-center gap-1">
                            <i class="fa-solid fa-umbrella-beach text-[9px]"></i> Declared Holiday
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-success-bg text-success text-[10px] font-normal">
                            {{ $teachers->count() }} Registered Faculty
                        </span>
                    @endif
                </div>
            </div>

            <!-- Bulk Actions -->
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
                @else
                    <span class="px-3.5 py-2 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-normal inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-umbrella-beach text-[10px]"></i> All Staff Auto-Marked for Holiday
                    </span>
                @endif
            </div>
        </div>

        <!-- DESKTOP TABLE VIEW -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted font-normal">
                        <th class="py-3 px-4 pl-5">Teacher / Staff</th>
                        <th class="py-3 px-4">Presence Status (Tap to Auto-Save)</th>
                        <th class="py-3 px-4 pr-5">Session Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    <template x-for="teacher in teachers" :key="teacher.id">
                        <tr class="hover:bg-secondary/40 transition">
                            <!-- Teacher Info -->
                            <td class="py-3.5 px-4 pl-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                         x-text="teacher.initials">
                                    </div>
                                    <div>
                                        <div class="font-semibold text-theme-text text-sm" x-text="teacher.name"></div>
                                        <div class="text-[11px] text-theme-text-muted flex items-center gap-2 mt-0.5">
                                            <span x-text="teacher.designation"></span>
                                            <!-- Auto-save state -->
                                            <span x-show="teacher.save_state === 'saving'" class="text-theme-text-muted animate-pulse inline-flex items-center gap-1">
                                                <i class="fa-solid fa-circle-notch fa-spin text-brand text-[9px]"></i> Saving...
                                            </span>
                                            <span x-show="teacher.save_state === 'saved'" class="text-success inline-flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-[9px]"></i> Auto-Saved
                                            </span>
                                            <span x-show="teacher.save_state === 'error'" class="text-danger inline-flex items-center gap-1">
                                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Save Failed
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Options (Pills with dynamic color change) -->
                            <td class="py-3.5 px-4">
                                @if($activeHoliday)
                                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold text-xs">
                                        <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                        <span>Official Holiday (Auto-Marked)</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <template x-if="teacher.status === 'holiday'">
                                            <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold text-xs">
                                                <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                                <span>Official Holiday</span>
                                            </div>
                                        </template>

                                        <div class="inline-flex items-center p-1 bg-secondary rounded-full gap-1">
                                            <!-- PRESENT -->
                                            <button type="button"
                                                    @click="markTeacher(teacher, 'present')"
                                                    :class="teacher.status === 'present' ? 'bg-emerald-600 text-white font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                                                    class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-check text-[10px]" x-show="teacher.status === 'present'"></i>
                                                <span>Present</span>
                                            </button>

                                            <!-- ABSENT -->
                                            <button type="button"
                                                    @click="markTeacher(teacher, 'absent')"
                                                    :class="teacher.status === 'absent' ? 'bg-rose-600 text-white font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                                                    class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-xmark text-[10px]" x-show="teacher.status === 'absent'"></i>
                                                <span>Absent</span>
                                            </button>

                                            <!-- HALF-DAY -->
                                            <button type="button"
                                                    @click="markTeacher(teacher, 'half_day')"
                                                    :class="teacher.status === 'half_day' ? 'bg-sky-600 text-white font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                                                    class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-cloud-sun text-[10px]" x-show="teacher.status === 'half_day'"></i>
                                                <span>Half-Day</span>
                                            </button>

                                            <!-- PAID LEAVE -->
                                            <button type="button"
                                                    @click="markTeacher(teacher, 'leave')"
                                                    :class="teacher.status === 'leave' ? 'bg-amber-600 text-white font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                                                    class="px-3.5 py-1.5 rounded-full text-xs transition inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-calendar-check text-[10px]" x-show="teacher.status === 'leave'"></i>
                                                <span>Paid Leave</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Remarks Input -->
                            <td class="py-3.5 px-4 pr-5">
                                <input type="text"
                                       x-model="teacher.remarks"
                                       @blur="saveRemarks(teacher)"
                                       @keydown.enter.prevent="saveRemarks(teacher)"
                                       @if($activeHoliday) disabled @endif
                                       placeholder="{{ $activeHoliday ? 'Official Holiday (' . $activeHoliday->title . ')' : 'Notes (e.g. casual leave, late 15m)...' }}"
                                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed">
                            </td>
                        </tr>
                    </template>

                    <template x-if="teachers.length === 0">
                        <tr>
                            <td colspan="3" class="py-12 px-4 text-center text-xs text-theme-text-muted">
                                No registered teachers in the system. Add faculty members in the Teachers Directory.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- MOBILE TOUCH CARDS VIEW -->
        <div class="block md:hidden p-4 space-y-3">
            <template x-for="teacher in teachers" :key="teacher.id">
                <div class="rounded-lg bg-secondary/50 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                 x-text="teacher.initials">
                            </div>
                            <div>
                                <h4 class="font-semibold text-theme-text text-xs" x-text="teacher.name"></h4>
                                <p class="text-[10px] text-theme-text-muted" x-text="teacher.designation"></p>
                            </div>
                        </div>
                        <div>
                            <span x-show="teacher.save_state === 'saving'" class="text-[10px] text-theme-text-muted animate-pulse">
                                <i class="fa-solid fa-circle-notch fa-spin text-brand text-[8px]"></i> Saving
                            </span>
                            <span x-show="teacher.save_state === 'saved'" class="text-[10px] text-success">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Saved
                            </span>
                        </div>
                    </div>

                    @if($activeHoliday)
                        <div class="py-2.5 px-3 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-semibold flex items-center justify-center gap-1.5 w-full">
                            <i class="fa-solid fa-umbrella-beach text-[11px]"></i>
                            <span>Official Holiday (Auto-Marked)</span>
                        </div>
                    @else
                        <template x-if="teacher.status === 'holiday'">
                            <div class="py-2 px-3 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-semibold flex items-center justify-center gap-1.5 w-full mb-1">
                                <i class="fa-solid fa-umbrella-beach text-[10px]"></i>
                                <span>Official Holiday</span>
                            </div>
                        </template>

                        <!-- 2x2 Action Buttons -->
                        <div class="grid grid-cols-2 gap-1.5">
                            <button type="button" @click="markTeacher(teacher, 'present')"
                                    :class="teacher.status === 'present' ? 'bg-emerald-600 text-white font-semibold' : 'bg-primary text-theme-text-muted'"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-check text-[10px]" x-show="teacher.status === 'present'"></i>
                                <span>Present</span>
                            </button>
                            <button type="button" @click="markTeacher(teacher, 'absent')"
                                    :class="teacher.status === 'absent' ? 'bg-rose-600 text-white font-semibold' : 'bg-primary text-theme-text-muted'"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-xmark text-[10px]" x-show="teacher.status === 'absent'"></i>
                                <span>Absent</span>
                            </button>
                            <button type="button" @click="markTeacher(teacher, 'half_day')"
                                    :class="teacher.status === 'half_day' ? 'bg-sky-600 text-white font-semibold' : 'bg-primary text-theme-text-muted'"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-cloud-sun text-[10px]" x-show="teacher.status === 'half_day'"></i>
                                <span>Half-Day</span>
                            </button>
                            <button type="button" @click="markTeacher(teacher, 'leave')"
                                    :class="teacher.status === 'leave' ? 'bg-amber-600 text-white font-semibold' : 'bg-primary text-theme-text-muted'"
                                    class="py-2.5 px-3 rounded-full text-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-calendar-check text-[10px]" x-show="teacher.status === 'leave'"></i>
                                <span>Paid Leave</span>
                            </button>
                        </div>
                    @endif

                    <!-- Remarks Input -->
                    <div>
                        <input type="text"
                               x-model="teacher.remarks"
                               @blur="saveRemarks(teacher)"
                               @keydown.enter.prevent="saveRemarks(teacher)"
                               @if($activeHoliday) disabled @endif
                               placeholder="{{ $activeHoliday ? 'Official Holiday (' . $activeHoliday->title . ')' : 'Leave notes (auto-saves on blur)...' }}"
                               class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-3.5 py-2 rounded-full focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed">
                    </div>
                </div>
            </template>
        </div>

        <!-- Live Status Bar -->
        <div class="p-4 bg-primary flex items-center justify-between text-xs text-theme-text-muted border-t-0">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-success animate-ping"></span>
                <span>Live Staff Attendance Auto-Save Active over AJAX.</span>
            </div>
        </div>
    </div>

</div>
@endsection
