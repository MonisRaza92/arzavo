@extends('layouts.admin')
@section('title', 'Student Attendance & Presence Registry')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    window.attendanceCategoriesData = @json($categories);
    window.attendanceInitialCategory = '{{ request('category_id') }}';
    window.attendanceInitialClass = '{{ request('class_id') }}';

    window.attendanceManagement = function attendanceManagement() {
        return {
            categories: window.attendanceCategoriesData || [],
            filterCategoryId: window.attendanceInitialCategory || '',
            filterClassId: window.attendanceInitialClass || '',

            get filterClasses() {
                if (!this.filterCategoryId) return [];
                const cat = this.categories.find(c => String(c.id) === String(this.filterCategoryId));
                return cat ? (cat.class_courses || []) : [];
            },

            onFilterCategoryChange() {
                this.filterClassId = '';
                this.$nextTick(() => {
                    this.$refs.attendanceFilterForm.submit();
                });
            },

            onFilterClassChange() {
                this.$refs.attendanceFilterForm.submit();
            }
        };
    };
</script>

<div x-data="attendanceManagement()" x-cloak class="space-y-5">

    <!-- STANDARD PAGE HEADER BANNER -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Student Attendance & Presence Registry
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Track overall student presence rates, monitor critical low-attendance warnings (&lt;75%), and log daily class attendance sheets.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.students') }}" class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-users text-[11px] text-brand"></i>
                <span>Students Directory</span>
            </a>
            <a href="{{ route('admin.students.attendance.mark') }}" class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-user-check text-[11px]"></i>
                <span>+ Mark Daily Attendance</span>
            </a>
        </div>
    </div>

    <!-- ATTENDANCE STATS CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Overall Present Rate -->
        <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-normal text-theme-text-muted">Overall Present Rate</p>
                <h3 class="text-xl font-semibold text-theme-text mt-1">{{ $overallAttendanceRate }}%</h3>
                <p class="text-[11px] text-theme-text-muted mt-1">Aggregate across all cohorts</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>

        <!-- Working Sessions Logged -->
        <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-normal text-theme-text-muted">Working Sessions Logged</p>
                <h3 class="text-xl font-semibold text-theme-text mt-1">{{ $workingDays }} Days</h3>
                <p class="text-[11px] text-theme-text-muted mt-1">Unique session dates recorded</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-info-bg text-info flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <!-- Total Absent Logs -->
        <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-normal text-theme-text-muted">Total Absent Logs</p>
                <h3 class="text-xl font-semibold text-warning mt-1">{{ number_format($absentLogsCount) }}</h3>
                <p class="text-[11px] text-theme-text-muted mt-1">Excused & unexcused absences</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-warning-bg text-warning flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>

        <!-- Low Attendance Alerts -->
        <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-normal text-theme-text-muted">Low Attendance Alerts</p>
                <h3 class="text-xl font-semibold text-danger mt-1">{{ $lowAttendanceCount }} Students</h3>
                <p class="text-[11px] text-theme-text-muted mt-1">Rate below 75% threshold</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-danger-bg text-danger flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR WITH DEPENDENT CATEGORY -> CLASS SELECT -->
    <div class="rounded-lg bg-primary p-4">
        <form x-ref="attendanceFilterForm" method="GET" action="{{ route('admin.students.attendance') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 grow">
                <!-- Search Input -->
                <div class="relative grow sm:grow-0 sm:w-72">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by student name, roll, email..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-9 rounded-full focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs"></i>
                </div>

                <!-- Academic Category Filter -->
                <select name="category_id"
                        x-model="filterCategoryId"
                        @change="onFilterCategoryChange()"
                        class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                    <option value="">All Academic Categories</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" :selected="String(cat.id) === String(filterCategoryId)" x-text="cat.name"></option>
                    </template>
                </select>

                <!-- Dependent Class Filter (Visible when Category is chosen) -->
                <template x-if="filterCategoryId && filterClasses.length > 0">
                    <select name="class_id"
                            x-model="filterClassId"
                            @change="onFilterClassChange()"
                            class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                        <option value="">All Classes in Category</option>
                        <template x-for="cls in filterClasses" :key="cls.id">
                            <option :value="cls.id" :selected="String(cls.id) === String(filterClassId)" x-text="cls.name"></option>
                        </template>
                    </select>
                </template>

                @if(request()->anyFilled(['search', 'category_id', 'class_id']))
                    <a href="{{ route('admin.students.attendance') }}" class="px-4 py-3 rounded-full bg-danger-bg text-danger text-xs font-normal hover:opacity-85 transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark text-[11px]"></i> Clear Filters
                    </a>
                @endif
            </div>

            <div class="text-xs text-theme-text-muted self-end lg:self-center shrink-0">
                Showing {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
            </div>
        </form>
    </div>

    <!-- STUDENT ATTENDANCE DIRECTORY TABLE -->
    <div class="rounded-lg bg-primary overflow-hidden">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Attendance Registry Directory</h3>
                <p class="text-xs text-theme-text-muted mt-0.5">Calculated presence standing and historical session summaries</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-secondary text-xs text-theme-text-muted font-normal">
                    {{ $students->total() }} Students Listed
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted font-normal">
                        <th class="py-3 px-4 pl-5">Student</th>
                        <th class="py-3 px-4">Academic Cohort</th>
                        <th class="py-3 px-4 text-center">Sessions Logged</th>
                        <th class="py-3 px-4">Daily Breakdown</th>
                        <th class="py-3 px-4 text-center">Attendance Rate</th>
                        <th class="py-3 px-4 text-center">Alert Standing</th>
                        <th class="py-3 px-4 pr-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    @forelse($students as $student)
                        @php
                            $fname = $student->fname ?? 'Student';
                            $lname = $student->lname ?? '';
                            $fullName = trim($fname . ' ' . $lname);
                            $initials = strtoupper(substr($fname, 0, 1) . substr($lname ?: 'S', 0, 1));
                            $categoryName = $student->academicCategory->name ?? 'General';
                            $className = $student->class->name ?? 'Unassigned';
                            $rate = $student->attendance_rate;
                            $rateColor = $rate >= 85 ? 'text-success' : ($rate >= 75 ? 'text-brand' : 'text-danger');
                            $barColor = $rate >= 85 ? 'bg-success' : ($rate >= 75 ? 'bg-brand' : 'bg-danger');
                        @endphp
                        <tr class="hover:bg-secondary/40 transition">
                            <!-- Student Profile -->
                            <td class="py-3.5 px-4 pl-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-theme-text flex items-center gap-2">
                                            <span>{{ $fullName }}</span>
                                            @if(!empty($student->roll_number))
                                                <span class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text-muted font-normal">
                                                    Roll: {{ $student->roll_number }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-theme-text-muted flex items-center gap-2 mt-0.5">
                                            <span>&#64;{{ $student->username ?? 'user' }}</span>
                                            @if(!empty($student->number))
                                                <span>&bull;</span>
                                                <span>{{ $student->number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Academic Cohort -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    <div class="font-semibold text-theme-text">
                                        {{ $className }}
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text-muted font-normal">
                                            {{ $categoryName }}
                                        </span>
                                        @if($student->batch)
                                            <span class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-normal flex items-center gap-1">
                                                <i class="fa-solid fa-layer-group text-[9px]"></i>
                                                <span>{{ $student->batch->name }}</span>
                                            </span>
                                        @elseif($student->subject)
                                            <span class="px-2 py-0.5 rounded-full bg-secondary text-theme-text text-[10px] font-normal">
                                                {{ $student->subject->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Sessions Logged -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-semibold text-theme-text">{{ $student->total_days }}</span>
                                <span class="text-[11px] text-theme-text-muted ml-0.5">Days</span>
                            </td>

                            <!-- Daily Breakdown (Present, Absent, Late, Half-Day) -->
                            <td class="py-3.5 px-4">
                                @if($student->total_days > 0)
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-success-bg text-success text-[10px] font-normal" title="Present Days">
                                            {{ $student->present_days }} P
                                        </span>
                                        @if($student->absent_days > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-danger-bg text-danger text-[10px] font-normal" title="Absent Days">
                                                {{ $student->absent_days }} A
                                            </span>
                                        @endif
                                        @if($student->late_days > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-warning-bg text-warning text-[10px] font-normal" title="Late Arrivals">
                                                {{ $student->late_days }} L
                                            </span>
                                        @endif
                                        @if($student->half_day_days > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-info-bg text-info text-[10px] font-normal" title="Half-Day Logs">
                                                {{ $student->half_day_days }} HD
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[11px] text-theme-text-muted italic">No attendance records</span>
                                @endif
                            </td>

                            <!-- Attendance Rate & Progress Bar -->
                            <td class="py-3.5 px-4 text-center">
                                @if($student->total_days > 0)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="font-semibold text-xs {{ $rateColor }}">{{ $rate }}%</span>
                                        <div class="w-16 h-1.5 bg-secondary rounded-full overflow-hidden mt-1">
                                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ min(100, max(0, $rate)) }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[11px] text-theme-text-muted">--</span>
                                @endif
                            </td>

                            <!-- Alert Standing -->
                            <td class="py-3.5 px-4 text-center">
                                @if($student->total_days == 0)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-normal bg-secondary text-theme-text-muted">
                                        No Sessions
                                    </span>
                                @elseif($student->attendance_rate < 75)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-normal bg-danger-bg text-danger inline-flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Low Presence
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-normal bg-success-bg text-success inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check text-[9px]"></i> Good Standing
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 pr-5 text-right">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}"
                                   class="px-3.5 py-1.5 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition inline-flex items-center gap-1.5">
                                    <span>Profile</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-brand"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-lg mx-auto mb-3">
                                    <i class="fa-solid fa-calendar-xmark"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-theme-text">No Students Found</h4>
                                <p class="text-xs text-theme-text-muted mt-1 max-w-sm mx-auto">
                                    No student records match your selected category, class, or search filter.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 bg-primary">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
