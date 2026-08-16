@extends('layouts.admin')
@section('title', 'Admin - Mark Attendance')
@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block Card -->
    <div class="mb-4 p-4 sm:p-5 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-user-check text-indigo-500"></i> Daily Student Attendance Cockpit
            </h1>
            <p class="text-xs text-secondary mt-0.5">Select class category and date to log student daily attendance logs.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.attendance') }}" class="bg-secondary text-primary border border-primary px-4 py-2 border-rounded text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 transition hover:bg-hover-secondary shadow-xs">
                <i class="fa-solid fa-arrow-left"></i> Back to Attendance Log
            </a>
        </div>
    </div>

    <!-- Configuration Filters Form -->
    <div class="p-6 bg-primary border-primary border-rounded shadow-xs">
        <form method="GET" action="{{ route('admin.students.attendance.mark') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="dateSelect" class="block text-xs font-semibold text-secondary mb-1">Attendance Log Date</label>
                <input type="date" name="date" id="dateSelect" value="{{ $date }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs input-focus" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div>
                <label for="classSelect" class="block text-xs font-semibold text-secondary mb-1">Select Cohort Class (Grouped by Category)</label>
                <select name="class_id" id="classSelect" class="w-full border-rounded border-primary bg-primary text-primary px-2.5 py-2 text-xs" onchange="document.getElementById('filterForm').submit()">
                    @foreach($categories as $cat)
                        @if($cat->classCourses->isNotEmpty())
                            <optgroup label="{{ $cat->name }}">
                                @foreach($cat->classCourses as $cc)
                                    <option value="{{ $cc->id }}" {{ $classId == $cc->id ? 'selected' : '' }}>
                                        {{ $cat->name }} - {{ $cc->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-primary border border-primary text-primary hover:bg-hover-secondary px-4 py-2 text-xs font-bold uppercase transition">
                    <i class="fa-solid fa-sync"></i> Refresh Cockpit
                </button>
            </div>
        </form>
    </div>

    @if($selectedClass)
        <!-- Attendance Marking Grid -->
        <form method="POST" action="{{ route('admin.students.attendance.save') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="class_id" value="{{ $classId }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="p-6 bg-primary border-primary border-rounded shadow-xs space-y-4">
                <!-- Action Tools Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-bottom pb-4">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-bold text-primary">Class Student List</h3>
                        <p class="text-[11px] text-secondary">Cohort: {{ $selectedClass->name }} · Date: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="bulkMark('present')" class="border-rounded border-primary bg-primary text-primary hover:bg-hover-secondary px-3 py-1.5 text-[11px] font-extrabold transition uppercase">
                            Mark All Present
                        </button>
                        <button type="button" onclick="bulkMark('absent')" class="border-rounded border-primary bg-primary text-primary hover:bg-hover-secondary px-3 py-1.5 text-[11px] font-extrabold transition uppercase">
                            Mark All Absent
                        </button>
                    </div>
                </div>

                <!-- Student List Registry Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="border-bottom text-tertiary text-xs uppercase font-extrabold">
                                <th class="py-3 px-4">Student Info</th>
                                <th class="py-3 px-4">Daily Presence Status</th>
                                <th class="py-3 px-4">Leave / Status Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary">
                            @forelse($students as $student)
                                @php
                                    $log = $existingLogs->get($student->id);
                                    $currentStatus = $log ? $log->status : 'present'; // default to present
                                @endphp
                                <tr class="hover:bg-hover-secondary transition student-mark-row">
                                    <td class="py-3 px-4 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-tertiary border border-primary text-primary flex items-center justify-center font-bold text-xs uppercase">
                                            {{ strtoupper(substr($student->fname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-primary">{{ $student->fname }} {{ $student->lname }}</div>
                                            <div class="text-[10px] text-tertiary font-mono">{{ $student->email }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="inline-flex items-center p-1 bg-secondary rounded-lg border border-primary gap-1">
                                            <!-- PRESENT -->
                                            <label class="relative cursor-pointer select-none">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="present" class="peer sr-only status-radio-present" {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                                <span class="px-3 py-1 rounded-md text-xs font-semibold text-secondary hover:text-primary transition-all peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-xs block">
                                                    Present
                                                </span>
                                            </label>

                                            <!-- ABSENT -->
                                            <label class="relative cursor-pointer select-none">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="absent" class="peer sr-only status-radio-absent" {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                                <span class="px-3 py-1 rounded-md text-xs font-semibold text-secondary hover:text-primary transition-all peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-xs block">
                                                    Absent
                                                </span>
                                            </label>

                                            <!-- LATE -->
                                            <label class="relative cursor-pointer select-none">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="late" class="peer sr-only status-radio-late" {{ $currentStatus === 'late' ? 'checked' : '' }}>
                                                <span class="px-3 py-1 rounded-md text-xs font-semibold text-secondary hover:text-primary transition-all peer-checked:bg-amber-600 peer-checked:text-white peer-checked:shadow-xs block">
                                                    Late
                                                </span>
                                            </label>

                                            <!-- HALF-DAY -->
                                            <label class="relative cursor-pointer select-none">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="half_day" class="peer sr-only status-radio-half_day" {{ $currentStatus === 'half_day' ? 'checked' : '' }}>
                                                <span class="px-3 py-1 rounded-md text-xs font-semibold text-secondary hover:text-primary transition-all peer-checked:bg-sky-600 peer-checked:text-white peer-checked:shadow-xs block">
                                                    Half-Day
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="attendance[{{ $student->id }}][remarks]" value="{{ $log->remarks ?? '' }}" placeholder="Remarks, e.g. Late by 10 mins" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-1 text-xs input-focus">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-tertiary text-xs">No active students enrolled in this category class cohort.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->isNotEmpty())
                    <!-- Submit Container -->
                    <div class="flex justify-between items-center border-top pt-4 mt-6">
                        <span class="text-xs text-tertiary">Logs will update existing logs if already checked.</span>
                        <button type="submit" class="bg-invert text-invert px-5 py-2.5 border-rounded text-xs font-bold uppercase tracking-wider transition hover:opacity-90">
                            Save Attendance Record Sheet
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @else
        <div class="p-6 bg-primary border-primary border-rounded shadow-xs text-center text-tertiary text-xs">
            No class cohorts registered. Please setup your academic class structure.
        </div>
    @endif
</div>

<script>
    function bulkMark(status) {
        document.querySelectorAll(`.status-radio-${status}`).forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection
