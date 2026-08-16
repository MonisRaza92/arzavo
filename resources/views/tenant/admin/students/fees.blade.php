@extends('layouts.admin')
@section('title', 'Admin - Student Fees & Billing Plans')

@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block Card -->
    <div class="mb-4 p-4 sm:p-5 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-indigo-500"></i> Student Fees & Billing Plans
            </h1>
            <p class="text-xs text-secondary mt-0.5">Monitor fee installments, verified collections, and outstanding student balances.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.fees') }}" class="bg-invert text-invert px-4 py-2 border-rounded text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 transition hover-invert shadow-sm">
                <i class="fa-solid fa-receipt"></i> Open Fee Verification Center
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL INVOICED FEE PLANS</span>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalPlanned, 2) }}</div>
            <p class="text-[11px] text-secondary">Sum of all enrolled student billing plans</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">VERIFIED COLLECTIONS</span>
            <div class="text-2xl font-extrabold text-emerald-600 font-mono">₹{{ number_format($totalCollected, 2) }}</div>
            <p class="text-[11px] text-secondary">Verified online & manual fee receipts</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL OUTSTANDING DUES</span>
            <div class="text-2xl font-extrabold {{ $totalPending > 0 ? 'text-rose-600' : 'text-emerald-600' }} font-mono">₹{{ number_format($totalPending, 2) }}</div>
            <p class="text-[11px] text-secondary">Remaining balance across all students</p>
        </div>
    </div>

    <!-- STUDENT FEES DIRECTORY TABLE -->
    <div class="bg-primary border-primary border-rounded p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-bottom pb-4">
            <div>
                <h3 class="text-sm font-bold text-primary">Student Fee Billing Ledger</h3>
                <p class="text-xs text-secondary">Real-time breakdown of individual student fee plans and payment records.</p>
            </div>
            <input type="text" id="feeSearch" placeholder="Search by student name or roll..." class="border-rounded border-primary bg-primary text-primary px-3 py-1.5 text-xs w-full sm:w-64 input-focus">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse" id="feesTable">
                <thead>
                    <tr class="border-bottom text-tertiary text-[10px] uppercase font-extrabold tracking-wider">
                        <th class="py-3 px-3">Student / Roll</th>
                        <th class="py-3 px-3">Academic Stream</th>
                        <th class="py-3 px-3">Plan Type</th>
                        <th class="py-3 px-3">Planned Fee</th>
                        <th class="py-3 px-3">Paid Amount</th>
                        <th class="py-3 px-3">Remaining Dues</th>
                        <th class="py-3 px-3">Payment Status</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @forelse($students as $student)
                        @php
                            $plan = $student->feePlans->first();
                            $planned = $student->feePlans->sum('amount');
                            $paid = $student->feePayments->where('status', 'paid')->sum('amount_paid');
                            $due = max(0, $planned - $paid);
                        @endphp
                        <tr class="hover:bg-hover-secondary transition fee-student-row">
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
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-secondary text-primary border border-primary">
                                    {{ $plan->plan_type ?? 'Standard' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-primary">
                                ₹{{ number_format($planned, 2) }}
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-emerald-600">
                                ₹{{ number_format($paid, 2) }}
                            </td>
                            <td class="py-3 px-3 font-mono font-bold {{ $due > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                ₹{{ number_format($due, 2) }}
                            </td>
                            <td class="py-3 px-3">
                                @if($planned > 0 && $due <= 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                        Paid in Full
                                    </span>
                                @elseif($paid > 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                        Partial Paid
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="px-3 py-1.5 bg-secondary text-primary border border-primary border-rounded font-bold text-xs hover:bg-hover-secondary transition inline-block">
                                    Manage Plan & Pay &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-tertiary text-xs">
                                No registered students found in directory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('feeSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.fee-student-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
