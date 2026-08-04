@extends('layouts.student')
@section('title', 'Attendance & Schedule - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-calendar-check text-primary"></i> Attendance & Class Schedule
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your monthly class attendance percentage and daily logs.</p>
        </div>
    </div>

    <!-- ATTENDANCE OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Attendance Rate</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $attendanceRate }}%</div>
            <p class="text-[11px] text-secondary">Class presence record</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Days Present</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $presentDays }} Days</div>
            <p class="text-[11px] text-secondary">Attended lectures</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Days Absent</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $absentDays }} Days</div>
            <p class="text-[11px] text-secondary">Missed classes</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Late / Half Day</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $lateDays + $halfDayDays }} Days</div>
            <p class="text-[11px] text-secondary">Late entry or partial logs</p>
        </div>
    </div>

    <!-- TIMETABLE & LOGS GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daily Timetable Schedule -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs lg:col-span-1">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2 border-bottom pb-2">
                <i class="fa-solid fa-clock text-tertiary"></i> Daily Batch Timetable
            </h3>

            <div class="space-y-3">
                <div class="p-3 border-rounded border-primary bg-hover-secondary space-y-1">
                    <span class="text-[9px] font-mono font-bold text-primary uppercase">09:00 AM - 10:30 AM</span>
                    <h4 class="font-bold text-xs text-primary">Physics Theory Lecture</h4>
                    <p class="text-[10px] text-tertiary">Room 102 · Dr. Sharma</p>
                </div>

                <div class="p-3 border-rounded border-primary bg-hover-secondary space-y-1">
                    <span class="text-[9px] font-mono font-bold text-primary uppercase">11:00 AM - 12:30 PM</span>
                    <h4 class="font-bold text-xs text-primary">Chemistry Lab & Formulas</h4>
                    <p class="text-[10px] text-tertiary">Lab 2 · Prof. Verma</p>
                </div>

                <div class="p-3 border-rounded border-primary bg-hover-secondary space-y-1">
                    <span class="text-[9px] font-mono font-bold text-primary uppercase">02:00 PM - 03:30 PM</span>
                    <h4 class="font-bold text-xs text-primary">Maths Problem Solving Batch</h4>
                    <p class="text-[10px] text-tertiary">Room 204 · Er. Gupta</p>
                </div>
            </div>
        </div>

        <!-- Attendance Logs History Table -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs lg:col-span-2">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2 border-bottom pb-2">
                <i class="fa-solid fa-history text-tertiary"></i> Attendance Daily History Log
            </h3>

            <div class="overflow-x-auto max-h-[350px] overflow-y-auto scrollbar">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="border-bottom text-tertiary uppercase font-extrabold text-[10px]">
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Class</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3">Remarks / Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @forelse($logs as $log)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">
                                    {{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $log->classCourse->name ?? 'Class Batch' }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($log->status === 'present')
                                        <span class="px-2 py-0.5 rounded-[3px] text-[9px] font-extrabold uppercase bg-invert text-invert border border-primary">
                                            Present
                                        </span>
                                    @elseif($log->status === 'absent')
                                        <span class="px-2 py-0.5 rounded-[3px] text-[9px] font-extrabold uppercase bg-tertiary text-primary border border-primary">
                                            Absent
                                        </span>
                                    @elseif($log->status === 'late')
                                        <span class="px-2 py-0.5 rounded-[3px] text-[9px] font-extrabold uppercase border border-primary text-secondary">
                                            Late
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-[3px] text-[9px] font-extrabold uppercase border border-primary text-secondary">
                                            Half-Day
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-tertiary">
                                    {{ $log->remarks ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-tertiary">No daily attendance logs found in database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
