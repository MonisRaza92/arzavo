@extends('layouts.student')
@section('title', 'Student Portal - Academic Dashboard')

@section('content')
    <!-- WELCOME BANNER CARD -->
    <div class="mb-6 p-4 sm:p-6 border-rounded bg-primary border-primary shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20 uppercase tracking-wider">
                        Student Portal
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] bg-secondary text-primary font-bold border border-primary">
                        {{ $category->name ?? 'General' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] bg-secondary text-primary font-bold border border-primary">
                        {{ $classCourse ? $classCourse->name : 'Standard' }} {{ $subject ? '• ' . $subject->name : '' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono text-tertiary">
                        Roll: {{ $user->username }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-primary tracking-tight flex items-center gap-2">
                    Welcome back, {{ $user->fname }} {{ $user->lname }}! 🎓
                </h1>
                <p class="text-xs text-secondary mt-1">
                    {{ $user->headline ?: 'Student at ' . (app('currentTenant')->name ?? 'Academy') }} • {{ $user->email }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if($dueFee > 0)
                    <a href="{{ route('student.fees') }}" class="px-4 py-2.5 bg-gradient-to-r from-rose-600 to-amber-600 text-white border-rounded font-bold text-xs hover:opacity-90 shadow-sm flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-credit-card"></i> Pay Dues (₹{{ number_format($dueFee, 2) }})
                    </a>
                @endif
                <a href="{{ route('student.courses') }}" class="px-4 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition flex items-center gap-1.5">
                    <i class="fa-solid fa-graduation-cap"></i> My Courses
                </a>
            </div>
        </div>
    </div>

    <!-- STATS CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- 1. Enrolled Courses -->
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">MY ENROLLED COURSES</span>
                <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 flex items-center justify-center text-sm border border-blue-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $enrolledCoursesCount }}</div>
            <p class="text-[11px] text-secondary">Active curriculum batches</p>
        </div>

        <!-- 2. Purchased Books -->
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">DIGITAL BOOKS & NOTES</span>
                <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-600 flex items-center justify-center text-sm border border-purple-500/20">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $purchasedBooks->count() }}</div>
            <p class="text-[11px] text-secondary">Unlocked library materials</p>
        </div>

        <!-- 3. Outstanding Fee -->
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">FEE REMAINING DUES</span>
                <div class="w-8 h-8 rounded {{ $dueFee > 0 ? 'bg-rose-500/10 text-rose-600 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' }} flex items-center justify-center text-sm border">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold font-mono {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                ₹{{ number_format($dueFee, 2) }}
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-secondary">Paid: ₹{{ number_format($paidFee, 2) }}</span>
                @if($dueFee > 0)
                    <a href="{{ route('student.fees') }}" class="font-bold text-rose-600 hover:underline">Pay &rarr;</a>
                @endif
            </div>
        </div>

        <!-- 4. Attendance Rate -->
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">ATTENDANCE RATE</span>
                <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm border border-emerald-500/20">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $attendanceRate }}%</div>
            <p class="text-[11px] text-secondary">Based on {{ $totalDays }} recorded sessions</p>
        </div>
    </div>

    <!-- MAIN TWO COLUMN CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        
        <!-- LEFT 7 COLS: ENROLLED COURSES & BOOKS -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- ENROLLED COURSES SECTION -->
            <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
                <div class="flex items-center justify-between border-bottom pb-3">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-circle-play text-indigo-600"></i> My Enrolled Courses & Batches
                    </h3>
                    <a href="{{ route('student.courses') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All &rarr;</a>
                </div>

                @if($enrolledCourses->count() > 0)
                    <div class="space-y-3">
                        @foreach($enrolledCourses->take(3) as $course)
                            <div class="p-3.5 border border-primary border-rounded bg-secondary/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-hover-secondary transition">
                                <div class="space-y-1 grow">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-primary text-xs">{{ $course->title }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                            Enrolled
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-secondary">
                                        Instructor: {{ $course->author->name ?? 'Faculty' }} • {{ $course->lessons->count() }} Lessons Included
                                    </p>
                                </div>
                                <a href="{{ route('student.courses') }}" class="px-3.5 py-1.5 bg-invert text-invert border-rounded text-xs font-bold hover-invert text-center shrink-0 transition">
                                    Study Now &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-2">
                        <i class="fa-solid fa-graduation-cap text-2xl text-tertiary"></i>
                        <p class="font-semibold text-primary">No active course enrollments yet.</p>
                        <p>When the academy administrator assigns batch courses or you enroll, they will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- PURCHASED DIGITAL BOOKS & NOTES -->
            <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
                <div class="flex items-center justify-between border-bottom pb-3">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-book text-purple-600"></i> My Purchased E-Books & Study Notes
                    </h3>
                    <span class="text-xs text-tertiary">{{ $purchasedBooks->count() }} Unlocked</span>
                </div>

                @if($purchasedBooks->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($purchasedBooks->take(4) as $bookEnt)
                            @php $book = $bookEnt->entitable; @endphp
                            <div class="p-3 border border-primary border-rounded bg-secondary/30 flex items-start gap-3">
                                <div class="w-10 h-14 bg-primary rounded border border-primary flex items-center justify-center shrink-0 text-purple-600 text-base shadow-xs">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="space-y-1 grow min-w-0">
                                    <h4 class="font-bold text-primary text-xs truncate">{{ $book->title ?? ('Book #' . $bookEnt->entitable_id) }}</h4>
                                    <span class="text-[10px] text-tertiary block font-mono">Order #{{ $bookEnt->order_id }}</span>
                                    <div class="pt-1 flex items-center gap-2">
                                        <a href="{{ route('item.download', ['type' => 'book', 'id' => $bookEnt->entitable_id, 'order_id' => $bookEnt->order_id]) }}" class="px-2 py-0.5 bg-purple-600 text-white rounded text-[10px] font-bold hover:bg-purple-700 transition">
                                            Download
                                        </a>
                                        <a href="{{ route('item.read', ['type' => 'book', 'id' => $bookEnt->entitable_id, 'order_id' => $bookEnt->order_id]) }}" target="_blank" class="text-[10px] text-secondary hover:text-primary transition font-bold">
                                            Read Online
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-2">
                        <i class="fa-solid fa-book-open text-2xl text-tertiary"></i>
                        <p class="font-semibold text-primary">No digital study material purchased yet.</p>
                        <p>Purchase books and PDF notes from our academy store to read online.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- RIGHT 5 COLS: RECENT ORDERS & FEE SUMMARY -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- FEE STATUS SUMMARY CARD -->
            <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
                <div class="flex items-center justify-between border-bottom pb-3">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-amber-500"></i> Academic Fee Status
                    </h3>
                    <a href="{{ route('student.fees') }}" class="text-xs font-bold text-indigo-600 hover:underline">Fee Ledger &rarr;</a>
                </div>

                <div class="space-y-3">
                    <div class="p-3.5 rounded-xl bg-secondary/40 border border-primary flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-tertiary block">Plan Invoiced Fee</span>
                            <span class="text-base font-extrabold text-primary font-mono">₹{{ number_format($totalFee, 2) }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-secondary text-primary border border-primary uppercase">
                            {{ $feePlan->plan_type ?? 'Standard' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Total Paid</span>
                            <span class="text-base font-extrabold text-emerald-600 font-mono">₹{{ number_format($paidFee, 2) }}</span>
                        </div>
                        <div class="p-3 rounded-xl {{ $dueFee > 0 ? 'bg-rose-500/10 border-rose-500/20' : 'bg-emerald-500/10 border-emerald-500/20' }} border">
                            <span class="text-[10px] uppercase font-bold {{ $dueFee > 0 ? 'text-rose-700' : 'text-emerald-700' }} block">Remaining Dues</span>
                            <span class="text-base font-extrabold font-mono {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₹{{ number_format($dueFee, 2) }}</span>
                        </div>
                    </div>

                    @if($dueFee > 0)
                        <a href="{{ route('student.fees') }}" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white border-rounded font-bold text-xs text-center block hover:opacity-90 shadow-sm transition">
                            <i class="fa-solid fa-credit-card mr-1"></i> Pay Outstanding Fee Online
                        </a>
                    @else
                        <div class="p-2.5 rounded-lg bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 text-center text-xs font-bold flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-check"></i> All Academic Fees Cleared!
                        </div>
                    @endif
                </div>
            </div>

            <!-- RECENT ORDERS & PURCHASES -->
            <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
                <div class="flex items-center justify-between border-bottom pb-3">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-secondary"></i> Recent Store Purchases
                    </h3>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($recentOrders as $order)
                            <div class="p-3 rounded-xl bg-secondary/30 border border-primary flex items-center justify-between gap-3 text-xs">
                                <div class="space-y-0.5">
                                    <div class="font-bold text-primary font-mono">#{{ $order->order_number }}</div>
                                    <div class="text-[11px] text-secondary">{{ $order->created_at->format('d M Y') }} • {{ $order->items->count() }} item(s)</div>
                                </div>
                                <div class="text-right space-y-1">
                                    <div class="font-bold text-primary font-mono">₹{{ number_format($order->grand_total, 2) }}</div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-tertiary text-xs">
                        No purchase invoices logged yet.
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection
