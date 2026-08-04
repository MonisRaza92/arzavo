@extends('layouts.student')
@section('title', 'Student Portal - Academic Dashboard')

@section('content')
    <!-- WELCOME BANNER CARD (MOBILE FIRST) -->
    <div class="mb-4 p-4 sm:p-6 border-rounded bg-primary border-primary shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20 uppercase tracking-wider">
                        Student Portal
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] bg-hover-secondary text-primary font-bold border border-primary font-mono">
                        {{ $classCourse ? $classCourse->name : 'Class: 11th' }} {{ $subject ? '· ' . $subject->name : '' }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight mt-2 flex items-center gap-2">
                    Welcome back, {{ $user->fname ?? 'Student' }}! 🎓
                </h1>
                <p class="text-xs text-secondary mt-1">
                    Track your class lectures, fee installments, homework assignments, and attendance.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('student.courses') }}" class="w-full sm:w-auto px-4 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-circle-play"></i> My Courses
                </a>
            </div>
        </div>
    </div>

    <!-- RESUME LEARNING QUICK CARD (MOBILE FIRST) -->
    @if($enrolledCoursesCount > 0)
        <div class="p-4 sm:p-5 border-rounded bg-emerald-500/5 border-emerald-500/20 space-y-3 shadow-xs mb-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-bolt"></i> RESUME LAST LESSON
                </span>
                <span class="text-[10px] font-mono font-bold text-emerald-600">Active Course Progress</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    @if($lastLesson)
                        <h3 class="text-sm font-bold text-primary">{{ $lastLesson->title }}</h3>
                        <p class="text-xs text-secondary mt-0.5">{{ $lastLesson->course->name ?? 'Physics Course' }} · Duration: {{ $lastLesson->duration ?? '0' }} mins</p>
                    @else
                        <h3 class="text-sm font-bold text-primary">Start your first lesson</h3>
                        <p class="text-xs text-secondary mt-0.5">Assigned Batch Course curriculum lectures ready.</p>
                    @endif
                </div>
                <a href="{{ route('student.courses') }}" class="px-4 py-2 bg-emerald-600 text-white border-rounded font-bold text-xs hover:bg-emerald-700 transition text-center shrink-0">
                    Continue &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="p-4 sm:p-5 border-rounded bg-blue-500/5 border-blue-500/20 space-y-2 shadow-xs mb-6">
            <h3 class="text-sm font-bold text-primary">Welcome to your learning journey! 🚀</h3>
            <p class="text-xs text-secondary">
                You are not enrolled in any courses yet. Once the administrator assigns batch classes or you purchase a course, they will show up here.
            </p>
        </div>
    @endif

    <!-- STATS CARDS GRID (MOBILE FIRST 1 COL -> 2 COL -> 4 COL) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">MY COURSES</span>
                <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 flex items-center justify-center text-sm border border-blue-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $enrolledCoursesCount }}</div>
            <p class="text-[11px] text-secondary">Active enrolled subjects</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">FEE DUES</span>
                <div class="w-8 h-8 rounded {{ $dueFee > 0 ? 'bg-rose-500/10 text-rose-600 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' }} flex items-center justify-center text-sm border">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold font-mono {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₹{{ number_format($dueFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Remaining balance fee</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">ATTENDANCE</span>
                <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm border border-emerald-500/20">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $attendanceRate }}%</div>
            <p class="text-[11px] text-secondary">Present this month</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">ASSIGNMENTS</span>
                <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-600 flex items-center justify-center text-sm border border-purple-500/20">
                    <i class="fa-solid fa-pen-ruler"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $pendingAssignments }} Pending</div>
            <p class="text-[11px] text-secondary">Homework tasks due soon</p>
        </div>
    </div>

    <!-- QUICK ACADEMIC LINKS (MOBILE FIRST) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- FEE INSTALLMENTS QUICK CARD -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-amber-500"></i> Fee Installment Details
                </h3>
                <a href="{{ route('student.fees') }}" class="text-xs font-bold text-blue-600 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-bottom">
                    <span class="text-tertiary">Total Course Fee:</span>
                    <span class="font-mono font-bold text-primary">₹{{ number_format($totalFee, 2) }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-bottom">
                    <span class="text-tertiary">Amount Paid:</span>
                    <span class="font-mono font-bold text-emerald-600">₹{{ number_format($paidFee, 2) }}</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-tertiary">Remaining Due:</span>
                    <span class="font-mono font-bold {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₹{{ number_format($dueFee, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- ACADEMIC NOTICE BOARD -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-indigo-500"></i> Class Announcements
                </h3>
            </div>

            <div class="space-y-3 text-xs">
                @forelse($announcements as $ann)
                    <div class="p-3 border-rounded bg-hover-secondary border-primary space-y-1">
                        <span class="text-[10px] font-mono text-tertiary font-bold">{{ $ann->created_at->format('d M Y, h:i A') }}</span>
                        <h4 class="font-bold text-primary">{{ $ann->title }}</h4>
                        <p class="text-secondary leading-relaxed truncate">{{ strip_tags($ann->content) }}</p>
                    </div>
                @empty
                    <div class="p-3 border-rounded bg-hover-secondary border-primary text-center text-tertiary text-xs">
                        No recent announcements or class updates.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
