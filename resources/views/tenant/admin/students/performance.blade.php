@extends('layouts.admin')
@section('title', 'Admin - Student Academic Performance & Progress')

@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block Card -->
    <div class="mb-4 p-4 sm:p-5 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-indigo-500"></i> Student Academic & LMS Progress Reports
            </h1>
            <p class="text-xs text-secondary mt-0.5">Real-time academic portfolio, batch course enrollments, attendance reliability, and learning activity.</p>
        </div>
    </div>

    <!-- PERFORMANCE DIRECTORY TABLE -->
    <div class="bg-primary border-primary border-rounded p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-bottom pb-4">
            <div>
                <h3 class="text-sm font-bold text-primary">Student Performance & Engagement Index</h3>
                <p class="text-xs text-secondary">Verified records of enrolled courses, study materials, attendance rate, and fee compliance.</p>
            </div>
            <input type="text" id="perfSearch" placeholder="Search student name or roll..." class="border-rounded border-primary bg-primary text-primary px-3 py-1.5 text-xs w-full sm:w-64 input-focus">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse" id="perfTable">
                <thead>
                    <tr class="border-bottom text-tertiary text-[10px] uppercase font-extrabold tracking-wider">
                        <th class="py-3 px-3">Student / Roll</th>
                        <th class="py-3 px-3">Academic Stream</th>
                        <th class="py-3 px-3 text-center">Enrolled Courses</th>
                        <th class="py-3 px-3 text-center">Digital Library</th>
                        <th class="py-3 px-3 text-center">Attendance Rate</th>
                        <th class="py-3 px-3">Fee Status</th>
                        <th class="py-3 px-3 text-right">Portfolio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @forelse($students as $student)
                        <tr class="hover:bg-hover-secondary transition perf-student-row">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center justify-center font-bold text-xs shrink-0 uppercase">
                                        {{ substr($student->fname, 0, 1) }}{{ substr($student->lname, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-primary text-xs student-name">{{ $student->fname }} {{ $student->lname }}</div>
                                        <div class="text-[10px] text-tertiary font-mono">{{ $student->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-secondary">
                                <div class="font-bold text-primary">{{ $student->class->name ?? 'Null' }}</div>
                                <div class="text-[10px] text-tertiary">{{ $student->academicCategory->name ?? 'Null' }} • {{ $student->subject->name ?? 'Null' }}</div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2.5 py-1 rounded font-bold font-mono text-xs bg-indigo-500/10 text-indigo-600 border border-indigo-500/20">
                                    {{ $student->enrolledCourses->count() }} Course(s)
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2.5 py-1 rounded font-bold font-mono text-xs bg-purple-500/10 text-purple-600 border border-purple-500/20">
                                    {{ $student->entitlements->count() }} Item(s)
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded font-bold font-mono text-xs {{ $student->attendance_rate >= 75 ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }}">
                                    {{ $student->attendance_rate }}%
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                @if(($student->due_fee ?? 0) <= 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                        Cleared
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-600 border border-amber-500/20 font-mono">
                                        Due ₹{{ number_format($student->due_fee, 0) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="px-3 py-1.5 bg-secondary text-primary border border-primary border-rounded font-bold text-xs hover:bg-hover-secondary transition inline-block">
                                    Full Profile &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-tertiary text-xs">
                                No registered students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('perfSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.perf-student-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
