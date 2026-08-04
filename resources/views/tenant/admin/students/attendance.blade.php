@extends('layouts.admin')
@section('title', 'Admin - Student Attendance')
@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-primary">Student Attendance Log</h2>
            <p class="text-xs text-secondary mt-1">Track aggregate attendance sheets and student presence statistics.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.attendance.mark') }}" class="bg-invert text-invert px-4 py-2 border-rounded text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 transition hover:opacity-90">
                <i class="fa-solid fa-user-check"></i> Mark Daily Attendance
            </a>
        </div>
    </div>

    <!-- Attendance Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Overall Present Rate</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $overallAttendanceRate }}%</div>
            <p class="text-[10px] text-secondary">Aggregate across all cohorts</p>
        </div>
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Total Checked Classes</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $workingDays }} Days</div>
            <p class="text-[10px] text-secondary">Unique calendar days logged</p>
        </div>
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Total Absent Logs</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $absentLogsCount }}</div>
            <p class="text-[10px] text-secondary">Excused & unexcused leaves</p>
        </div>
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs border-l-4" style="border-left-color: var(--text-color);">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">Low Attendance Alerts</span>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $lowAttendanceCount }} Students</div>
            <p class="text-[10px] text-secondary">Attendance rate below 75%</p>
        </div>
    </div>

    <!-- Student Attendance Records List -->
    <div class="bg-primary border-primary border-rounded p-6 shadow-xs">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-primary">Attendance Registry Directory</h3>
            <!-- Fast search field -->
            <input type="text" id="attendanceSearch" placeholder="Filter by student name..." class="border-rounded border-primary bg-primary text-primary px-3 py-1.5 text-xs w-64 input-focus">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse" id="attendanceTable">
                <thead>
                    <tr class="border-bottom text-tertiary text-xs uppercase font-extrabold">
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Batch / Course mapping</th>
                        <th class="py-3 px-4 text-center">Days Logged</th>
                        <th class="py-3 px-4 text-center">Rate</th>
                        <th class="py-3 px-4">Alert Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @forelse($students as $student)
                        <tr class="hover:bg-hover-secondary transition student-row">
                            <td class="py-3 px-4 font-bold text-primary student-name">
                                {{ $student->fname }} {{ $student->lname }}
                            </td>
                            <td class="py-3 px-4 text-secondary">
                                {{ $student->class->academicCategory->name ?? 'No Category' }} - {{ $student->class->name ?? 'No Class' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-primary font-medium">
                                {{ $student->total_days }} Days
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-primary">
                                {{ $student->attendance_rate }}%
                            </td>
                            <td class="py-3 px-4">
                                @if($student->attendance_rate < 75)
                                    <span class="px-2 py-0.5 rounded-[3px] text-[10px] font-bold border border-primary bg-primary text-primary font-mono uppercase">
                                        Low Attendance
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-[3px] text-[10px] font-bold bg-invert text-invert font-mono uppercase">
                                        Good Stand
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="border-rounded border-primary bg-primary text-primary hover:bg-hover-secondary px-3 py-1.5 text-xs font-bold transition inline-block">
                                    Profile View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-tertiary text-xs">No registered students found in the workspace directory.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("turbo:load", () => {
        const searchInput = document.getElementById('attendanceSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.student-row');

                rows.forEach(row => {
                    const name = row.querySelector('.student-name').textContent.toLowerCase();
                    if (name.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
