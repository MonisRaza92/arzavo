@extends('layouts.student')
@section('title', 'Attendance & Records - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-calendar-check text-emerald-600"></i> Attendance & Academic Records
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your official class lecture attendance and daily recorded logs.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-secondary text-primary border border-primary font-mono">
                Roll: {{ $user->username }}
            </span>
        </div>
    </div>

    <!-- ATTENDANCE OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Attendance Rate</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $attendanceRate }}%</div>
            <p class="text-[11px] text-secondary">Verified session presence</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Days Present</span>
            <div class="text-2xl font-extrabold text-emerald-600 font-mono">{{ $presentDays }} Days</div>
            <p class="text-[11px] text-secondary">Attended sessions</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Days Absent</span>
            <div class="text-2xl font-extrabold text-rose-600 font-mono">{{ $absentDays }} Days</div>
            <p class="text-[11px] text-secondary">Missed classes</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Late / Half Day</span>
            <div class="text-2xl font-extrabold text-amber-600 font-mono">{{ $lateDays + $halfDayDays }} Days</div>
            <p class="text-[11px] text-secondary">Partial attendance logs</p>
        </div>
    </div>

    <!-- MAIN TWO COLUMN: BATCH ACADEMIC INFO + ATTENDANCE HISTORY -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Assigned Academic Batch Info -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs lg:col-span-4">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2 border-bottom pb-2">
                <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Assigned Academic Batch
            </h3>

            <div class="space-y-3 text-xs">
                <div class="p-3 border border-primary border-rounded bg-secondary/30 space-y-1">
                    <span class="text-[10px] text-tertiary uppercase font-bold block">Academic Category</span>
                    <strong class="text-primary text-sm">{{ $user->academicCategory->name ?? 'General Category' }}</strong>
                </div>

                <div class="p-3 border border-primary border-rounded bg-secondary/30 space-y-1">
                    <span class="text-[10px] text-tertiary uppercase font-bold block">Class / Course</span>
                    <strong class="text-primary text-sm">{{ $user->class->name ?? 'Standard Class' }}</strong>
                </div>

                <div class="p-3 border border-primary border-rounded bg-secondary/30 space-y-1">
                    <span class="text-[10px] text-tertiary uppercase font-bold block">Subject / Stream</span>
                    <strong class="text-primary text-sm">{{ $user->subject->name ?? 'All Subjects' }}</strong>
                </div>

                <div class="p-3 border border-primary border-rounded bg-secondary/30 space-y-1">
                    <span class="text-[10px] text-tertiary uppercase font-bold block">Student ID / Roll</span>
                    <strong class="text-primary font-mono">{{ $user->username }}</strong>
                </div>
            </div>
        </div>

        <!-- Attendance Logs History Table -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs lg:col-span-8">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2 border-bottom pb-2">
                <i class="fa-solid fa-history text-indigo-600"></i> Attendance Daily History Log
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="border-bottom text-tertiary uppercase font-extrabold text-[10px]">
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Class / Batch</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3">Remarks / Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @forelse($logs as $log)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">
                                    {{ $log->date ? \Carbon\Carbon::parse($log->date)->format('d M Y') : $log->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $log->classCourse->name ?? ($user->class->name ?? 'Batch Class') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if(in_array(strtolower($log->status), ['present', 'p']))
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                            Present
                                        </span>
                                    @elseif(in_array(strtolower($log->status), ['absent', 'a']))
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                            Absent
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                            {{ $log->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $log->remarks ?: 'Regular Session' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-tertiary text-xs">
                                    <i class="fa-solid fa-clipboard-user text-2xl mb-1.5 block opacity-50"></i>
                                    No attendance sessions logged in database yet for your student record.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
