{{-- SECTION 5: RECENT ACTIVITY & DIRECTORY FEEDS --}}
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex bg-primary p-4 rounded-lg items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-brand text-sm"></i>
                Recent Activity & Feeds
            </h2>
            <p class="text-[11px] text-theme-text-muted mt-0.5">
                Real-time updates of student enrollments, faculty onboarding, admission applications, and store orders
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-secondary text-xs text-theme-text font-normal">
                Live Records
            </span>
        </div>
    </div>

    {{-- Feeds Grid: 2 Columns on desktop --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        {{-- 1. Recent Student Registrations --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                            <i class="fa-solid fa-user-graduate text-brand"></i>
                            Recent Students
                        </h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">
                            Latest pupils registered in the institute
                        </p>
                    </div>
                    <a href="{{ route('admin.students') }}"
                       class="text-xs text-brand hover:underline font-normal inline-flex items-center gap-1">
                        <span>Directory</span>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentStudents as $student)
                        <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Avatar: Profile picture or first letter initial --}}
                                @if(!empty($student->profile_picture))
                                    <img src="{{ media($student->profile_picture) }}"
                                         alt="{{ $student->fname }}"
                                         class="w-9 h-9 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center shrink-0 uppercase">
                                        {{ strtoupper(substr($student->fname ?? 'S', 0, 1)) }}
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-theme-text truncate leading-tight">
                                        {{ $student->fname }} {{ $student->lname }}
                                    </p>
                                    <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                        {{ $student->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                @if($student->status === 'active' || $student->status === 1 || $student->status === '1')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-secondary text-theme-text-muted">
                                        Inactive
                                    </span>
                                @endif
                                <p class="text-[10px] text-theme-text-muted mt-1">
                                    {{ $student->created_at ? $student->created_at->format('d M Y') : 'Enrolled' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-theme-text-muted">
                            <i class="fa-solid fa-user-slash text-2xl mb-2 opacity-40"></i>
                            <p>No students enrolled yet.</p>
                            <a href="{{ route('admin.students.admissions') }}" class="text-brand hover:underline mt-1 inline-block">
                                Start new admission
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($recentStudents->count() > 0)
                <div class="pt-3 mt-3 text-right">
                    <a href="{{ route('admin.students') }}"
                       class="px-4 py-2 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                        <span>All {{ $totalStudents }} students</span>
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- 2. Recent Teachers --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-user text-amber-500"></i>
                            Recent Teachers
                        </h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">
                            Assigned teaching staff and instructors
                        </p>
                    </div>
                    <a href="{{ route('admin.users') }}"
                       class="text-xs text-brand hover:underline font-normal inline-flex items-center gap-1">
                        <span>Faculty</span>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentTeachers as $teacher)
                        <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                @if(!empty($teacher->profile_picture))
                                    <img src="{{ media($teacher->profile_picture) }}"
                                         alt="{{ $teacher->fname }}"
                                         class="w-9 h-9 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-amber-500 text-white font-semibold text-xs flex items-center justify-center shrink-0 uppercase">
                                        {{ strtoupper(substr($teacher->fname ?? 'T', 0, 1)) }}
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-theme-text truncate leading-tight">
                                        {{ $teacher->fname }} {{ $teacher->lname }}
                                    </p>
                                    <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                        {{ $teacher->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                @if($teacher->status === 'active' || $teacher->status === 1 || $teacher->status === '1')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-secondary text-theme-text-muted">
                                        Inactive
                                    </span>
                                @endif
                                <p class="text-[10px] text-theme-text-muted mt-1">
                                    Joined {{ $teacher->created_at ? $teacher->created_at->format('d M Y') : 'Recently' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-theme-text-muted">
                            <i class="fa-solid fa-chalkboard text-2xl mb-2 opacity-40"></i>
                            <p>No teachers added yet.</p>
                            <a href="{{ route('admin.users') }}" class="text-brand hover:underline mt-1 inline-block">
                                Add teacher account
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($recentTeachers->count() > 0)
                <div class="pt-3 mt-3 text-right">
                    <a href="{{ route('admin.users') }}"
                       class="px-4 py-2 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                        <span>All {{ $totalTeachers }} teachers</span>
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- Row 2: Admissions & Orders Feeds --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- 3. Admissions Applications --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                            <i class="fa-solid fa-file-signature text-sky-500"></i>
                            Admissions Pipeline
                        </h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">
                            Incoming student registration applications
                        </p>
                    </div>
                    <a href="{{ route('admin.students.admissions') }}"
                       class="text-xs text-brand hover:underline font-normal inline-flex items-center gap-1">
                        <span>Manage</span>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentAdmissions as $admission)
                        <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-theme-text truncate leading-tight">
                                    {{ $admission->user?->fname ?? 'Applicant' }} {{ $admission->user?->lname ?? '' }}
                                </p>
                                <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                    Course: {{ $admission->classCourse?->name ?? ($admission->academicCategory?->name ?? 'General Course') }}
                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                @if($admission->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                        Approved
                                    </span>
                                @elseif($admission->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-danger-bg text-danger">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning">
                                        Pending Review
                                    </span>
                                @endif
                                <p class="text-[10px] text-theme-text-muted mt-1">
                                    {{ $admission->created_at ? $admission->created_at->format('d M Y') : 'Pending' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-theme-text-muted">
                            <i class="fa-solid fa-file-circle-check text-2xl mb-2 opacity-40"></i>
                            <p>No admission applications submitted yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 mt-3 text-right">
                <a href="{{ route('admin.students.admissions') }}"
                   class="px-4 py-2 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                    <span>View all applications</span>
                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
            </div>
        </div>

        {{-- 4. Recent Store Orders & Sales --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-emerald-500"></i>
                            Recent Orders & Store Sales
                        </h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">
                            Purchases of courses, notes, and curriculum books
                        </p>
                    </div>
                    <a href="{{ route('admin.finance.orders') }}"
                       class="text-xs text-brand hover:underline font-normal inline-flex items-center gap-1">
                        <span>All Orders</span>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentOrders as $order)
                        <div class="p-3 rounded-lg bg-secondary flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-theme-text truncate leading-tight">
                                    {{ $order->order_number }}
                                </p>
                                <p class="text-[11px] text-theme-text-muted truncate mt-0.5">
                                    {{ $order->customer_name ?: ($order->user ? $order->user->fname . ' ' . $order->user->lname : 'Customer') }}
                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-xs font-semibold text-theme-text">
                                    ₹{{ number_format((float) $order->grand_total, 2) }}
                                </p>
                                @if($order->payment_status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success mt-1 inline-block">
                                        Paid
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning mt-1 inline-block">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-theme-text-muted">
                            <i class="fa-solid fa-receipt text-2xl mb-2 opacity-40"></i>
                            <p>No store orders recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 mt-3 text-right">
                <a href="{{ route('admin.finance.orders') }}"
                   class="px-4 py-2 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5">
                    <span>View all {{ $totalOrders }} orders</span>
                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
            </div>
        </div>

    </div>
</div>
