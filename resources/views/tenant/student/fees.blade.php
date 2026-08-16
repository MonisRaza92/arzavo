@extends('layouts.student')
@section('title', 'Fee Installments & Dues - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-amber-500"></i> Fee Installments & Online Payment
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your academic fee breakdown, installment due dates, and make instant online payments.</p>
        </div>

        @if($dueFee > 0)
            <button onclick="openPayModal()" class="px-4 py-2 bg-emerald-600 text-white border-rounded font-bold text-xs hover:bg-emerald-700 shadow-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-credit-card"></i> Pay Online ₹{{ number_format($dueFee, 2) }}
            </button>
        @endif
    </div>

    <!-- FEE STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL COURSE FEE</span>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Academic session fee structure</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL PAID</span>
            <div class="text-2xl font-extrabold text-emerald-600 font-mono">₹{{ number_format($paidFee, 2) }}</div>
            <p class="text-[11px] text-secondary">{{ $feePayments->where('status', 'paid')->count() }} Received installment(s)</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">REMAINING DUES</span>
            <div class="text-2xl font-extrabold font-mono {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₹{{ number_format($dueFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Pending installment balance</p>
        </div>
    </div>

    <!-- PAYMENT HISTORY & INSTALLMENTS TABLE -->
    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-500"></i> Fee Payment Receipts & History
            </h3>
            @if($dueFee > 0)
                <button onclick="openPayModal()" class="px-3 py-1.5 bg-emerald-600 text-white border-rounded font-bold text-xs hover:bg-emerald-700 transition">
                    + Pay Installment
                </button>
            @endif
        </div>

        @if($feePayments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Receipt #</th>
                            <th class="py-2.5 px-3">Payment Date</th>
                            <th class="py-2.5 px-3">Payment Mode</th>
                            <th class="py-2.5 px-3">Amount Paid</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-right">Fee Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($feePayments as $payment)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">
                                    {{ $payment->transaction_id ?: ('REC-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT)) }}
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : $payment->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-secondary text-primary border border-primary">
                                        {{ $payment->payment_method ?: 'Online' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-emerald-600 text-sm">
                                    ₹{{ number_format($payment->amount_paid, 2) }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($payment->status === 'paid')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">Paid</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <button onclick="window.print();" class="px-3 py-1 bg-secondary text-primary border border-primary border-rounded font-semibold text-[11px] hover:bg-hover-secondary transition">
                                        <i class="fa-solid fa-print mr-1"></i> Print Receipt
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-wallet text-2xl text-tertiary block opacity-50 mb-1"></i>
                <p class="font-semibold text-primary">No fee payment receipts recorded yet.</p>
                <p>Fee installment receipts will appear here once submitted.</p>
            </div>
        @endif
    </div>

    <!-- ONLINE PAYMENT MODAL -->
    <div id="onlinePayModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-primary border border-primary border-rounded w-full max-w-md shadow-2xl overflow-hidden">
            <div class="flex justify-between items-center p-4 border-bottom bg-primary sticky top-0">
                <h3 class="text-base font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-lock text-emerald-600"></i> Online Fee Payment
                </h3>
                <button type="button" onclick="closePayModal()" class="text-secondary hover:text-primary transition">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form action="{{ route('student.fees.pay-online') }}" method="POST" class="p-5 space-y-4 text-xs">
                @csrf
                <div class="p-3 bg-secondary/40 border border-primary border-rounded space-y-1">
                    <span class="text-[10px] text-tertiary uppercase font-bold">Outstanding Academic Balance</span>
                    <div class="text-xl font-bold font-mono text-primary">₹{{ number_format($dueFee, 2) }}</div>
                </div>

                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Amount (₹) *</label>
                    <input type="number" step="0.01" name="amount" value="{{ $dueFee > 0 ? $dueFee : 1000 }}" max="{{ $dueFee > 0 ? $dueFee : 100000 }}" min="1" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    <p class="text-[10px] text-tertiary mt-1">You can pay the full amount or a partial installment.</p>
                </div>

                <div>
                    <label class="block font-bold text-secondary mb-1">Select Payment Gateway / Method *</label>
                    <select name="payment_method" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        <option value="razorpay">Razorpay (UPI / Cards / NetBanking / Wallets)</option>
                        <option value="payu">PayU India</option>
                        <option value="cashfree">Cashfree Payments</option>
                        <option value="upi">Direct UPI / QR Transfer</option>
                    </select>
                </div>

                <div class="pt-2 flex justify-end gap-2 border-top">
                    <button type="button" onclick="closePayModal()" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white border-rounded font-bold hover:bg-emerald-700 shadow-sm transition">
                        Proceed to Pay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openPayModal() {
        document.getElementById('onlinePayModal').classList.remove('hidden');
    }
    function closePayModal() {
        document.getElementById('onlinePayModal').classList.add('hidden');
    }
    </script>
@endsection
