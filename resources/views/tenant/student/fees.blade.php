@extends('layouts.student')
@section('title', 'Fee Installments & Dues - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-amber-500"></i> Fee Installments & Payment Dues
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your academic fee breakdown, installment due dates, and fee receipts.</p>
        </div>
    </div>

    <!-- FEE STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL COURSE FEE</span>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Academic session fee plan</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL PAID</span>
            <div class="text-2xl font-extrabold text-emerald-600 font-mono">₹{{ number_format($paidFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Received fee installments</p>
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
                <button onclick="alert('Redirecting to online fee payment gateway...');" class="px-3 py-1.5 bg-emerald-600 text-white border-rounded font-bold text-xs hover:bg-emerald-700 transition">
                    Pay Online ₹{{ number_format($dueFee, 2) }}
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
                            <th class="py-2.5 px-3 text-right">Fee Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($feePayments as $payment)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">REC-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                                <td class="py-3 px-3 text-primary uppercase font-bold text-[10px]">{{ $payment->payment_mode ?? 'Online / Cash' }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-emerald-600">₹{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-3 px-3 text-right">
                                    <button onclick="window.print();" class="px-3 py-1 bg-hover-secondary text-primary border-primary border-rounded font-semibold text-[11px] hover-primary transition">
                                        <i class="fa-solid fa-print"></i> Print Receipt
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-wallet text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No fee payment receipts recorded yet.</p>
                <p>Fee installment receipts will appear here once submitted.</p>
            </div>
        @endif
    </div>
@endsection
