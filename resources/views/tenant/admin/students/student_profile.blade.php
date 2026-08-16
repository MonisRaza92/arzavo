@extends('layouts.admin')
@section('title', 'Student Profile - '.$studentProfile->fname.' '.$studentProfile->lname)

@section('content')
<div class="my-4 space-y-4">
    
    <!-- Top Breadcrumb & Action Header -->
    <div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
        <div>
            <h2 class="text-lg font-bold text-primary mb-0.5 flex items-center gap-2">
                <i class="fa-solid fa-user-graduate text-primary"></i>
                {{ $studentProfile->fname }} {{ $studentProfile->lname }}
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase {{ $studentProfile->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20' }}">
                    {{ $studentProfile->status }}
                </span>
            </h2>
            <p class="text-xs text-secondary font-mono">{{ $studentProfile->email }} • {{ $studentProfile->username }}</p>
        </div>

        <div class="right-content flex flex-wrap gap-2 items-center">
            <a href="{{ route('admin.admin-students') }}"
                class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-left"></i> Back to Students
            </a>
            <button onclick="openModal('profileEditModal')"
                class="px-3 py-2 text-xs font-bold bg-primary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </button>
            <button onclick="openModal('feePlanModal')"
                class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
                <i class="fa-solid fa-wallet"></i> Fee Plan
            </button>
            <button onclick="openModal('recordPaymentModal')"
                class="px-3 py-2 text-xs font-bold bg-invert text-invert border-primary border-rounded hover-invert flex items-center gap-1.5 transition">
                <i class="fa-solid fa-plus"></i> Record Payment
            </button>
        </div>
    </div>

    <!-- Profile Overview Card -->
    <div class="bg-primary border-primary border-rounded overflow-hidden">
        <!-- Banner -->
        <div class="w-full bg-secondary h-28 sm:h-36 relative overflow-hidden">
            @if(!empty($studentProfile->banner))
                <img src="{{ asset($studentProfile->banner) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-indigo-900/40 via-purple-900/30 to-indigo-900/40 flex items-center justify-center">
                    <span class="text-xs text-secondary font-mono uppercase tracking-widest flex items-center gap-2"><i class="fa-solid fa-graduation-cap text-base"></i> Student Portal Profile</span>
                </div>
            @endif
        </div>

        <!-- Profile Details (Avatar + Info + Badges) -->
        <div class="px-6 pb-4 pt-3 bg-primary">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 -mt-12 sm:-mt-14 rounded-2xl overflow-hidden border-4 border-white dark:border-gray-900 bg-secondary shadow-lg shrink-0 flex items-center justify-center relative z-20">
                        @if ($studentProfile->profile_picture)
                            <img src="{{ asset($studentProfile->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full font-black text-3xl flex items-center justify-center bg-secondary text-primary">
                                {{ strtoupper(substr($studentProfile->fname, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-primary leading-tight">{{ $studentProfile->fname }} {{ $studentProfile->lname }}</h1>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase {{ $studentProfile->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20' }}">
                                {{ $studentProfile->status }}
                            </span>
                        </div>
                        <p class="text-xs text-secondary font-medium">{{ $studentProfile->headline ?: 'Student at ' . (app('currentTenant')->name ?? 'Academy') }}</p>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-tertiary pt-0.5 font-mono">
                            <span><i class="fa-solid fa-envelope text-tertiary mr-1"></i>{{ $studentProfile->email }}</span>
                            @if($studentProfile->number)
                                <span><i class="fa-solid fa-phone text-tertiary mr-1"></i>{{ $studentProfile->number }}</span>
                            @endif
                            <span><i class="fa-solid fa-calendar text-tertiary mr-1"></i>Joined {{ $studentProfile->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Academic Badges -->
                <div class="flex flex-wrap gap-2 items-center">
                    <div class="px-3 py-1.5 bg-secondary border border-primary border-rounded text-xs">
                        <span class="text-[9px] text-tertiary block font-bold uppercase">Class / Course</span>
                        <strong class="text-primary">{{ $studentProfile->class->name ?? 'None' }}</strong>
                    </div>
                    <div class="px-3 py-1.5 bg-secondary border border-primary border-rounded text-xs">
                        <span class="text-[9px] text-tertiary block font-bold uppercase">Subject</span>
                        <strong class="text-primary">{{ $studentProfile->subject->name ?? 'None' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Navigation -->
        <div class="border-top px-6 bg-primary">
            <div class="flex whitespace-nowrap overflow-x-auto gap-2 py-3">
                <button onclick="switchTab('overview')" id="tab-btn-overview" class="tab-btn px-4 py-2 border-rounded text-xs font-bold bg-invert text-invert transition">
                    <i class="fa-solid fa-gauge mr-1.5"></i> Academic & Fee Overview
                </button>
                <button onclick="switchTab('orders')" id="tab-btn-orders" class="tab-btn px-4 py-2 border-rounded text-xs font-bold bg-secondary text-primary border border-primary hover:bg-hover-secondary transition">
                    <i class="fa-solid fa-bag-shopping mr-1.5"></i> Digital Orders & Purchases ({{ $studentProfile->orders->count() }})
                </button>
                <button onclick="switchTab('courses')" id="tab-btn-courses" class="tab-btn px-4 py-2 border-rounded text-xs font-bold bg-secondary text-primary border border-primary hover:bg-hover-secondary transition">
                    <i class="fa-solid fa-book-open mr-1.5"></i> Enrolled Courses & Books ({{ $studentProfile->enrolledCourses->count() + $studentProfile->entitlements->count() }})
                </button>
                <button onclick="switchTab('ledger')" id="tab-btn-ledger" class="tab-btn px-4 py-2 border-rounded text-xs font-bold bg-secondary text-primary border border-primary hover:bg-hover-secondary transition">
                    <i class="fa-solid fa-receipt mr-1.5"></i> Fee Payments Log ({{ $studentProfile->feePayments->count() }})
                </button>
                <button onclick="switchTab('attendance')" id="tab-btn-attendance" class="tab-btn px-4 py-2 border-rounded text-xs font-bold bg-secondary text-primary border border-primary hover:bg-hover-secondary transition">
                    <i class="fa-solid fa-clipboard-user mr-1.5"></i> Attendance Records
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 1: OVERVIEW & ACADEMICS -->
    <!-- ============================================================ -->
    <div id="tab-content-overview" class="tab-content space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            <!-- Left: Personal & Academic Details -->
            <div class="lg:col-span-7 space-y-4">
                <div class="p-5 bg-primary border-primary border-rounded space-y-4">
                    <div class="flex justify-between items-center border-bottom pb-3">
                        <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-secondary"></i> Student Personal & Contact Information
                        </h3>
                        <button onclick="openModal('profileEditModal')" class="text-xs font-bold text-indigo-600 hover:underline">
                            Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">First Name</span>
                            <span class="text-primary font-semibold text-sm">{{ $studentProfile->fname }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Last Name</span>
                            <span class="text-primary font-semibold text-sm">{{ $studentProfile->lname }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Email Address</span>
                            <span class="text-primary font-mono font-medium">{{ $studentProfile->email }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Phone Number</span>
                            <span class="text-primary font-mono font-medium">{{ $studentProfile->number ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Date of Birth</span>
                            <span class="text-primary font-medium">{{ $studentProfile->dob ? \Carbon\Carbon::parse($studentProfile->dob)->format('M d, Y') : 'Not set' }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Account Status</span>
                            <span class="text-xs font-bold uppercase {{ $studentProfile->status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $studentProfile->status }}</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-[11px] text-tertiary block font-bold uppercase">Street Address / Location</span>
                            <span class="text-primary font-medium">{{ $studentProfile->address ?: 'N/A' }}{{ $studentProfile->city ? ', '.$studentProfile->city : '' }}{{ $studentProfile->state ? ', '.$studentProfile->state : '' }}{{ $studentProfile->pincode ? ' - '.$studentProfile->pincode : '' }}</span>
                        </div>
                        @if($studentProfile->about)
                            <div class="sm:col-span-2">
                                <span class="text-[11px] text-tertiary block font-bold uppercase">About / Student Notes</span>
                                <p class="text-secondary text-xs leading-relaxed mt-0.5 bg-secondary/30 p-2.5 rounded-lg border border-primary">{{ $studentProfile->about }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Fee Plan Structure & Stats -->
            <div class="lg:col-span-5 space-y-4">
                <div class="p-5 bg-primary border-primary border-rounded space-y-4">
                    <div class="flex justify-between items-center border-bottom pb-3">
                        <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-secondary"></i> Fee Plan & Billing Structure
                        </h3>
                        <button onclick="openModal('feePlanModal')" class="text-xs font-bold text-indigo-600 hover:underline">
                            {{ $feePlan ? 'Edit Plan' : 'Setup Plan' }}
                        </button>
                    </div>

                    @php
                        $totalFeeInvoiced = $studentProfile->feePlans->sum('amount');
                        $totalFeeCollected = $studentProfile->feePayments->where('status', 'paid')->sum('amount_paid');
                        $pendingFeeDues = max(0, $totalFeeInvoiced - $totalFeeCollected);
                    @endphp

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-secondary/40 border border-primary border-rounded space-y-1">
                            <span class="text-[10px] uppercase font-bold text-tertiary block">Plan Amount</span>
                            <div class="text-lg font-bold text-primary font-mono">₹{{ number_format($feePlan->amount ?? 0, 2) }}</div>
                            <span class="text-[10px] text-secondary capitalize">{{ $feePlan->plan_type ?? 'No recurring plan' }}</span>
                        </div>
                        <div class="p-3 bg-secondary/40 border border-primary border-rounded space-y-1">
                            <span class="text-[10px] uppercase font-bold text-tertiary block">Due Day</span>
                            <div class="text-lg font-bold text-primary font-mono">{{ $feePlan && $feePlan->due_day ? $feePlan->due_day.'th' : 'N/A' }}</div>
                            <span class="text-[10px] text-secondary">Of each billing cycle</span>
                        </div>
                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 border-rounded space-y-1">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Total Paid</span>
                            <div class="text-lg font-bold text-emerald-600 font-mono">₹{{ number_format($totalFeeCollected, 2) }}</div>
                            <span class="text-[10px] text-emerald-700">{{ $studentProfile->feePayments->where('status', 'paid')->count() }} Transaction(s)</span>
                        </div>
                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 border-rounded space-y-1">
                            <span class="text-[10px] uppercase font-bold text-amber-700 block">Outstanding</span>
                            <div class="text-lg font-bold text-amber-600 font-mono">₹{{ number_format($pendingFeeDues, 2) }}</div>
                            <span class="text-[10px] text-amber-700">Remaining Balance</span>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-between items-center">
                        <button onclick="openModal('recordPaymentModal')" class="w-full py-2 bg-invert text-invert border-rounded text-xs font-bold text-center hover-invert transition">
                            <i class="fa-solid fa-plus mr-1"></i> Record Offline Fee Payment
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 2: DIGITAL ORDERS & PURCHASES -->
    <!-- ============================================================ -->
    <div id="tab-content-orders" class="tab-content space-y-4 hidden">
        <div class="bg-primary border-primary border-rounded overflow-hidden">
            <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping text-secondary"></i> Student Checkout Orders Ledger
                </h3>
                <span class="text-xs font-mono font-bold text-primary">Total Spend: ₹{{ number_format($totalDigitalSpend ?? 0, 2) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-secondary text-secondary border-bottom">
                            <th class="p-3">Order #</th>
                            <th class="p-3">Items Purchased</th>
                            <th class="p-3">Gateway</th>
                            <th class="p-3">Amount</th>
                            <th class="p-3">Payment Status</th>
                            <th class="p-3">Date</th>
                            <th class="p-3 text-right">Invoice & Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentProfile->orders as $order)
                            <tr class="border-bottom hover-primary transition text-xs">
                                <td class="p-3 font-mono font-bold text-primary">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="p-3 text-secondary">
                                    <div class="space-y-0.5">
                                        @foreach($order->items as $item)
                                            <div class="font-semibold text-primary">• {{ $item->item_name }} (x{{ $item->quantity }})</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-secondary text-primary border border-primary">
                                        {{ str_replace('_', ' ', $order->payment_gateway) }}
                                    </span>
                                </td>
                                <td class="p-3 font-bold text-primary font-mono text-sm">
                                    ₹{{ number_format($order->grand_total, 2) }}
                                </td>
                                <td class="p-3">
                                    @if($order->payment_status === 'paid')
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">Paid</span>
                                    @elseif($order->payment_status === 'verification_pending')
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">Pending Verification</span>
                                    @else
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-secondary">
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.finance.orders.show', $order->id) }}" class="px-2.5 py-1 bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary text-[11px] font-bold transition">
                                            View Order
                                        </a>
                                        @if($order->payment_status === 'paid')
                                            <a href="{{ route('admin.finance.invoices.show', $order->id) }}" target="_blank" class="px-2.5 py-1 bg-invert text-invert border-rounded text-[11px] font-bold hover-invert transition">
                                                <i class="fa-solid fa-print mr-1"></i> Print
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-tertiary text-xs">
                                    No digital store orders placed by this student yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 3: ENROLLED COURSES & DIGITAL BOOKS -->
    <!-- ============================================================ -->
    <div id="tab-content-courses" class="tab-content space-y-4 hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            
            <!-- Active Course Enrollments -->
            <div class="bg-primary border-primary border-rounded overflow-hidden">
                <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                    <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-secondary"></i> Enrolled Courses ({{ $studentProfile->enrolledCourses->count() }})
                    </h3>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($studentProfile->enrolledCourses as $course)
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-primary text-xs">{{ $course->title }}</h4>
                                <p class="text-[11px] text-tertiary mt-0.5">Enrolled on {{ $course->pivot->enrolled_at ? \Carbon\Carbon::parse($course->pivot->enrolled_at)->format('M d, Y') : 'Active' }}</p>
                            </div>
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 capitalize">
                                {{ $course->pivot->status ?? 'active' }}
                            </span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-tertiary text-xs">
                            No enrolled courses found for this student.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Digital Library Entitlements -->
            <div class="bg-primary border-primary border-rounded overflow-hidden">
                <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                    <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-secondary"></i> Digital Books & Notes Access ({{ $studentProfile->entitlements->count() }})
                    </h3>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($studentProfile->entitlements as $ent)
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-primary text-xs">{{ $ent->entitable->title ?? ('Item #' . $ent->entitable_id) }}</h4>
                                <p class="text-[11px] text-tertiary mt-0.5">Granted via Order #{{ $ent->order_id }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-500/10 text-indigo-600 border border-indigo-500/20">
                                    Full Access
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-tertiary text-xs">
                            No digital library books entitled yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 4: FEE PAYMENTS LEDGER -->
    <!-- ============================================================ -->
    <div id="tab-content-ledger" class="tab-content space-y-4 hidden">
        <div class="bg-primary border-primary border-rounded overflow-hidden">
            <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-secondary"></i> Offline & Academy Fee Payments Log
                </h3>
                <button onclick="openModal('recordPaymentModal')" class="px-3 py-1.5 bg-invert text-invert border-rounded text-xs font-bold hover-invert transition">
                    + Record New Payment
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-secondary text-secondary border-bottom">
                            <th class="p-3">Receipt / Tx ID</th>
                            <th class="p-3">Amount Paid</th>
                            <th class="p-3">Payment Method</th>
                            <th class="p-3">Payment Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Recorded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentProfile->feePayments as $payment)
                            <tr class="border-bottom hover-primary transition text-xs">
                                <td class="p-3 font-mono font-bold text-primary">
                                    {{ $payment->transaction_id ?: ('FEE-'.$payment->id) }}
                                </td>
                                <td class="p-3 font-mono font-bold text-emerald-600 text-sm">
                                    ₹{{ number_format($payment->amount_paid, 2) }}
                                </td>
                                <td class="p-3 uppercase font-semibold text-secondary">
                                    {{ $payment->payment_method ?: 'Cash' }}
                                </td>
                                <td class="p-3 text-primary">
                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="p-3">
                                    @if($payment->status === 'paid')
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">Paid</span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right text-secondary">
                                    {{ $payment->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-tertiary text-xs">
                                    No offline fee payment records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 5: ATTENDANCE RECORDS -->
    <!-- ============================================================ -->
    <div id="tab-content-attendance" class="tab-content space-y-4 hidden">
        <div class="bg-primary border-primary border-rounded overflow-hidden">
            <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-user text-secondary"></i> Student Attendance Log (Recent 30 Days)
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-secondary text-secondary border-bottom">
                            <th class="p-3">Date</th>
                            <th class="p-3">Class / Course</th>
                            <th class="p-3">Attendance Status</th>
                            <th class="p-3">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentProfile->attendances as $att)
                            <tr class="border-bottom hover-primary transition text-xs">
                                <td class="p-3 font-medium text-primary">
                                    {{ $att->attendance_date ? \Carbon\Carbon::parse($att->attendance_date)->format('M d, Y (l)') : $att->created_at->format('M d, Y') }}
                                </td>
                                <td class="p-3 text-secondary">
                                    {{ $att->class->name ?? $studentProfile->class->name ?? 'Standard Class' }}
                                </td>
                                <td class="p-3">
                                    @if(in_array(strtolower($att->status), ['present', 'p']))
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">Present</span>
                                    @elseif(in_array(strtolower($att->status), ['absent', 'a']))
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20 uppercase">Absent</span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase">{{ $att->status }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-secondary">
                                    {{ $att->remarks ?: 'No remarks' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-tertiary text-xs">
                                    No attendance records logged yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- MODAL 1: EDIT PROFILE -->
<!-- ============================================================ -->
<div id="profileEditModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Student Profile Info
            </h3>
            <button type="button" onclick="closeModal('profileEditModal')" class="text-secondary hover:text-primary transition text-sm">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.admin-student-profile-info-update', $studentProfile->id) }}" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-secondary mb-1">First Name *</label>
                    <input type="text" name="fname" value="{{ $studentProfile->fname }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Last Name *</label>
                    <input type="text" name="lname" value="{{ $studentProfile->lname }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Username *</label>
                    <input type="text" name="username" value="{{ $studentProfile->username }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ $studentProfile->email }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Phone Number *</label>
                    <input type="text" name="number" value="{{ $studentProfile->number }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Date of Birth</label>
                    <input type="date" name="dob" value="{{ $studentProfile->dob }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Class / Course</label>
                    <select name="class_id" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ $studentProfile->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Subject</label>
                    <select name="subject_id" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ $studentProfile->subject_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-secondary mb-1">Headline / Title</label>
                    <input type="text" name="headline" value="{{ $studentProfile->headline }}" placeholder="e.g. Science Batch Aspirant" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-secondary mb-1">Street Address</label>
                    <input type="text" name="address" value="{{ $studentProfile->address }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">City</label>
                    <input type="text" name="city" value="{{ $studentProfile->city }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">State</label>
                    <input type="text" name="state" value="{{ $studentProfile->state }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Country</label>
                    <input type="text" name="country" value="{{ $studentProfile->country ?: 'India' }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Pincode</label>
                    <input type="text" name="pincode" value="{{ $studentProfile->pincode }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-secondary mb-1">About / Bio Notes</label>
                    <textarea name="about" rows="2" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">{{ $studentProfile->about }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-top">
                <button type="button" onclick="closeModal('profileEditModal')" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded font-bold hover-invert transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL 2: SETUP FEE PLAN -->
<!-- ============================================================ -->
<div id="feePlanModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-lg shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet"></i> Setup / Edit Student Fee Plan
            </h3>
            <button type="button" onclick="closeModal('feePlanModal')" class="text-secondary hover:text-primary transition text-sm">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.admin-student-fee-update', $studentProfile->id) }}" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-secondary mb-1">Billing Interval *</label>
                <select name="plan_type" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    <option value="monthly" {{ ($feePlan->plan_type ?? '') === 'monthly' ? 'selected' : '' }}>Monthly Recurring</option>
                    <option value="quarterly" {{ ($feePlan->plan_type ?? '') === 'quarterly' ? 'selected' : '' }}>Quarterly (Every 3 Months)</option>
                    <option value="yearly" {{ ($feePlan->plan_type ?? '') === 'yearly' ? 'selected' : '' }}>Yearly Recurring</option>
                    <option value="onetime" {{ ($feePlan->plan_type ?? '') === 'onetime' ? 'selected' : '' }}>One-Time Full Payment</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">Plan Fee Amount (₹) *</label>
                <input type="number" step="0.01" name="amount" value="{{ $feePlan->amount ?? '' }}" required placeholder="e.g. 3500.00" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-secondary mb-1">Cycle Due Day (1-31) *</label>
                    <input type="number" name="due_day" min="1" max="31" value="{{ $feePlan->due_day ?? 10 }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Start Date *</label>
                    <input type="date" name="start_date" value="{{ $feePlan->start_date ?? date('Y-m-d') }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">End Date (Optional)</label>
                <input type="date" name="end_date" value="{{ $feePlan->end_date ?? '' }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-top">
                <button type="button" onclick="closeModal('feePlanModal')" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded font-bold hover-invert transition">
                    Save Fee Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL 3: RECORD OFFLINE PAYMENT -->
<!-- ============================================================ -->
<div id="recordPaymentModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-lg shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-plus text-emerald-600"></i> Record Academy Fee Payment
            </h3>
            <button type="button" onclick="closeModal('recordPaymentModal')" class="text-secondary hover:text-primary transition text-sm">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.admin-student-fee-payment', $studentProfile->id) }}" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-secondary mb-1">Amount Paid (₹) *</label>
                <input type="number" step="0.01" name="amount_paid" required placeholder="e.g. 3500.00" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Method *</label>
                    <select name="payment_method" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="cash">Cash (Counter)</option>
                        <option value="bank_transfer">Bank Transfer / NEFT</option>
                        <option value="upi">UPI / QR Scan</option>
                        <option value="cheque">Cheque</option>
                        <option value="card">Card (POS Machine)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-secondary mb-1">Receipt / Ref Number</label>
                    <input type="text" name="transaction_id" placeholder="e.g. REC-92019" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Status *</label>
                    <select name="status" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="paid">Paid & Verified</option>
                        <option value="pending">Pending Clearing</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-top">
                <button type="button" onclick="closeModal('recordPaymentModal')" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded font-bold hover-invert transition">
                    Confirm & Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-invert', 'text-invert');
        btn.classList.add('bg-secondary', 'text-primary', 'border', 'border-primary');
    });

    // Show active tab
    const targetContent = document.getElementById('tab-content-' + tabId);
    const targetBtn = document.getElementById('tab-btn-' + tabId);

    if (targetContent) targetContent.classList.remove('hidden');
    if (targetBtn) {
        targetBtn.classList.remove('bg-secondary', 'text-primary', 'border', 'border-primary');
        targetBtn.classList.add('bg-invert', 'text-invert');
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('hidden');
}
</script>

@endsection
