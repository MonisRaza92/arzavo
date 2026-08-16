@extends('layouts.admin')
@section('title', 'Student Fee Payments & Ledger')

@section('content')
<!-- TOP STATS CARDS -->
<div class="statics grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Fee Invoiced</h2>
                <p class="text-2xl font-bold mt-1 text-primary font-mono">₹{{ number_format($stats['total_invoiced'], 2) }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-file-invoice-dollar text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Collected</h2>
                <p class="text-2xl font-bold mt-1 text-emerald-600 font-mono">₹{{ number_format($stats['total_collected'], 2) }}</p>
            </div>
            <div class="bg-emerald-500/10 border-rounded p-3"><i class="fas fa-wallet text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Pending Dues</h2>
                <p class="text-2xl font-bold mt-1 text-amber-600 font-mono">₹{{ number_format($stats['pending_dues'], 2) }}</p>
            </div>
            <div class="bg-amber-500/10 border-rounded p-3"><i class="fas fa-clock text-lg text-amber-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Paid Transactions</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $stats['paid_count'] }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-check-double text-lg text-primary"></i></div>
        </div>
    </div>
</div>

<!-- ACTIONS & FILTERS BAR -->
<div class="bg-primary border-rounded border-primary p-4 mb-4">
    <form method="GET" action="{{ route('admin.finance.fees') }}" class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2 grow max-w-3xl">
            <!-- Search -->
            <div class="relative min-w-[220px] grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search receipt, student name or email..." 
                       class="border text-xs py-2 px-3 pl-8 border-primary border-rounded bg-primary text-primary w-full input-focus">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-tertiary text-xs"></i>
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="border text-xs py-2 px-3 border-primary border-rounded bg-primary text-primary input-focus">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>

            <!-- Payment Method Filter -->
            <select name="payment_method" onchange="this.form.submit()" class="border text-xs py-2 px-3 border-primary border-rounded bg-primary text-primary input-focus">
                <option value="">All Methods</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI / QR</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="online" {{ request('payment_method') === 'online' ? 'selected' : '' }}>Online Gateway</option>
                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
            </select>

            @if(request()->anyFilled(['search', 'status', 'payment_method']))
                <a href="{{ route('admin.finance.fees') }}" class="px-3 py-2 text-xs font-bold text-rose-600 bg-rose-500/10 border border-rose-500/20 border-rounded hover:bg-rose-500/20 transition">
                    Clear Filters
                </a>
            @endif
        </div>

        <div>
            <button type="button" onclick="openRecordPaymentModal()" class="px-4 py-2 bg-invert text-invert border-rounded text-xs font-bold hover-invert flex items-center gap-1.5 transition">
                <i class="fa-solid fa-plus"></i> Record Fee Payment
            </button>
        </div>
    </form>
</div>

<!-- FEE PAYMENTS TABLE -->
<div class="bg-primary border-rounded border-primary overflow-hidden">
    <div class="px-4 py-3 border-bottom flex items-center justify-between">
        <h3 class="text-primary text-base font-bold flex items-center gap-2">
            <i class="fa-solid fa-money-bill-wave text-primary"></i> Fee Payments & Collection Ledger
        </h3>
        <span class="text-xs text-tertiary">Showing {{ $feePayments->total() }} records</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3.5 pl-4 text-left">Receipt / Txn #</th>
                    <th class="p-3.5 text-left">Student Profile</th>
                    <th class="p-3.5 text-left">Class & Academics</th>
                    <th class="p-3.5 text-left">Amount Paid</th>
                    <th class="p-3.5 text-left">Payment Mode</th>
                    <th class="p-3.5 text-left">Date</th>
                    <th class="p-3.5 text-center">Status</th>
                    <th class="p-3.5 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feePayments as $fee)
                    <tr class="border-bottom hover-primary transition text-xs">
                        <!-- Receipt ID -->
                        <td class="p-3.5 pl-4 font-mono font-bold text-primary">
                            {{ $fee->transaction_id ?: ('FEE-' . str_pad($fee->id, 5, '0', STR_PAD_LEFT)) }}
                        </td>

                        <!-- Student -->
                        <td class="p-3.5 text-left">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-secondary text-primary border border-primary">
                                    {{ substr($fee->student->fname ?? 'S', 0, 1) }}{{ substr($fee->student->lname ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.admin-student-profile', $fee->student->username ?? '') }}" class="font-bold text-primary hover:underline block leading-tight">
                                        {{ $fee->student->fname ?? 'Unknown' }} {{ $fee->student->lname ?? '' }}
                                    </a>
                                    <p class="text-[10px] text-tertiary font-mono">{{ $fee->student->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Class -->
                        <td class="p-3.5 text-left text-secondary">
                            <div class="font-semibold text-primary">{{ $fee->student->class->name ?? 'No Class' }}</div>
                            <div class="text-[10px] text-tertiary">{{ $fee->student->subject->name ?? 'General' }}</div>
                        </td>

                        <!-- Amount -->
                        <td class="p-3.5 font-mono font-bold text-primary text-sm">
                            ₹{{ number_format($fee->amount_paid, 2) }}
                        </td>

                        <!-- Mode -->
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-secondary text-primary border border-primary inline-flex items-center gap-1">
                                @if(in_array($fee->payment_method, ['cash', 'manual']))
                                    <i class="fa-solid fa-money-bill text-emerald-600"></i> Cash
                                @elseif($fee->payment_method === 'upi')
                                    <i class="fa-solid fa-qrcode text-indigo-600"></i> UPI
                                @elseif($fee->payment_method === 'bank_transfer')
                                    <i class="fa-solid fa-building-columns text-blue-600"></i> Bank Transfer
                                @elseif($fee->payment_method === 'cheque')
                                    <i class="fa-solid fa-money-check text-amber-600"></i> Cheque
                                @else
                                    <i class="fa-solid fa-credit-card text-purple-600"></i> {{ ucfirst($fee->payment_method ?: 'Online') }}
                                @endif
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="p-3.5 text-secondary font-mono">
                            {{ $fee->payment_date ? \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y') : $fee->created_at->format('M d, Y') }}
                        </td>

                        <!-- Status -->
                        <td class="p-3.5 text-center">
                            @if($fee->status === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                    Paid
                                </span>
                            @elseif($fee->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                    Pending
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right pr-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Quick Status Update -->
                                <button type="button" 
                                        onclick="openUpdateStatusModal({{ $fee->id }}, '{{ $fee->status }}', '{{ $fee->payment_method }}', '{{ addslashes($fee->student->fname ?? 'Student') }}', '{{ number_format($fee->amount_paid, 2) }}')" 
                                        class="px-2.5 py-1 bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary font-bold text-[11px] transition"
                                        title="Update Status / Mark as Paid">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Status
                                </button>

                                <a href="{{ route('admin.admin-student-profile', $fee->student->username ?? '') }}" 
                                   class="w-7 h-7 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                   title="View Student Profile">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>

                                <form action="{{ route('admin.finance.fees.destroy', $fee->id) }}" method="POST" onsubmit="return confirm('Delete this payment record permanently?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-secondary text-rose-600 border border-primary hover:bg-rose-500/10 flex items-center justify-center text-xs transition" title="Delete Record">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-tertiary text-xs">
                            <i class="fa-solid fa-money-bill-wave text-2xl mb-2 block opacity-50"></i>
                            No fee payments recorded found matching criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($feePayments->hasPages())
        <div class="p-4 border-top bg-primary">
            {{ $feePayments->links() }}
        </div>
    @endif
</div>

<!-- ============================================================ -->
<!-- MODAL: RECORD NEW FEE PAYMENT -->
<!-- ============================================================ -->
<div id="recordPaymentModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-lg shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-emerald-600"></i> Record Student Fee Payment
            </h3>
            <button type="button" onclick="closeModal('recordPaymentModal')" class="text-secondary hover:text-primary transition text-sm">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.finance.fees.record') }}" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-secondary mb-1">Select Student *</label>
                <select name="student_id" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->fname }} {{ $st->lname }} ({{ $st->email }}) - {{ $st->class->name ?? 'No Class' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">Amount (₹) *</label>
                <input type="number" step="0.01" name="amount_paid" required placeholder="e.g. 5000.00" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Method *</label>
                    <select name="payment_method" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="cash">Cash (Counter)</option>
                        <option value="upi">UPI / QR Transfer</option>
                        <option value="bank_transfer">Bank Transfer / NEFT</option>
                        <option value="cheque">Cheque</option>
                        <option value="online">Online Payment</option>
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
                    <input type="text" name="transaction_id" placeholder="e.g. REC-84920" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                </div>
                <div>
                    <label class="block font-bold text-secondary mb-1">Status *</label>
                    <select name="status" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="paid">Paid & Verified</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">Notes / Remarks</label>
                <input type="text" name="notes" placeholder="e.g. Installment 1 for Class 12 Science" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-top">
                <button type="button" onclick="closeModal('recordPaymentModal')" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded font-bold hover-invert transition">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: UPDATE PAYMENT STATUS -->
<!-- ============================================================ -->
<div id="updateStatusModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-600"></i> Update Payment Status
            </h3>
            <button type="button" onclick="closeModal('updateStatusModal')" class="text-secondary hover:text-primary transition text-sm">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form id="updateStatusForm" action="" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <div class="p-3 bg-secondary/40 border border-primary border-rounded space-y-1">
                <p class="text-xs font-bold text-primary" id="modalStudentName">Student</p>
                <p class="text-xs font-mono font-bold text-emerald-600" id="modalAmount">₹0.00</p>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">Change Status To *</label>
                <select name="status" id="modalStatusSelect" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    <option value="paid">Paid & Verified</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-secondary mb-1">Payment Method / Source</label>
                <select name="payment_method" id="modalMethodSelect" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    <option value="cash">Cash (Counter)</option>
                    <option value="upi">UPI / QR Scan</option>
                    <option value="bank_transfer">Bank Transfer / NEFT</option>
                    <option value="cheque">Cheque</option>
                    <option value="online">Online Payment</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-top">
                <button type="button" onclick="closeModal('updateStatusModal')" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded font-bold hover-invert transition">
                    Save Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRecordPaymentModal() {
    document.getElementById('recordPaymentModal').classList.remove('hidden');
}

function openUpdateStatusModal(id, status, method, studentName, amount) {
    document.getElementById('updateStatusForm').action = "{{ url('admin/finance/fees/update-status') }}/" + id;
    document.getElementById('modalStudentName').textContent = studentName;
    document.getElementById('modalAmount').textContent = '₹' + amount;
    document.getElementById('modalStatusSelect').value = status;
    if (method) {
        document.getElementById('modalMethodSelect').value = method;
    }
    document.getElementById('updateStatusModal').classList.remove('hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('hidden');
}
</script>

@endsection
