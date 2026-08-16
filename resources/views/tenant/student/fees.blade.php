@extends('layouts.student')
@section('title', 'Fee Installments & Online Payment - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-wallet text-amber-500"></i> Fee Installments & Online Payment
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your academic fee breakdown, installment due dates, and make instant online payments.</p>
        </div>

        @if($dueFee > 0)
            <button onclick="openPayModal()" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white border-rounded font-bold text-xs hover:opacity-90 shadow-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-credit-card"></i> Pay Outstanding (₹{{ number_format($dueFee, 2) }})
            </button>
        @endif
    </div>

    <!-- FEE STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL INVOICED FEE</span>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalFee, 2) }}</div>
            <p class="text-[11px] text-secondary">Academic session fee plan ({{ $feePlan->plan_type ?? 'Standard' }})</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL PAID</span>
            <div class="text-2xl font-extrabold text-emerald-600 font-mono">₹{{ number_format($paidFee, 2) }}</div>
            <p class="text-[11px] text-secondary">{{ $feePayments->where('status', 'paid')->count() }} Verified installment(s)</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-1 shadow-xs">
            <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">REMAINING DUES</span>
            <div class="text-2xl font-extrabold font-mono {{ $dueFee > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₹{{ number_format($dueFee, 2) }}</div>
            <p class="text-[11px] text-secondary">{{ $dueFee > 0 ? 'Pending installment balance' : 'All session dues cleared' }}</p>
        </div>
    </div>

    <!-- PAYMENT HISTORY & INSTALLMENTS TABLE -->
    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-500"></i> Fee Payment Receipts & Ledger
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
                            <th class="py-2.5 px-3">Receipt / Tx ID</th>
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
                                        {{ str_replace('_', ' ', $payment->payment_method ?: 'Online') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-emerald-600 text-sm">
                                    ₹{{ number_format($payment->amount_paid, 2) }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($payment->status === 'paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">
                                            Paid & Verified
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase animate-pulse">
                                            Pending Verification
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 border border-rose-500/20 uppercase">
                                            {{ $payment->status }}
                                        </span>
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
            <div class="p-8 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-2">
                <i class="fa-solid fa-receipt text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No fee payment transactions logged yet.</p>
                <p>Use the "Pay Online" button to clear fee installments.</p>
            </div>
        @endif
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: MULTI-GATEWAY ONLINE & MANUAL FEE PAYMENT -->
    <!-- ============================================================ -->
    <div id="payModal" class="hidden fixed inset-0 bg-black/60 z-[9999] flex items-center justify-center p-4">
        <div class="bg-primary border border-primary border-rounded w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
                <h3 class="text-base font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-emerald-600"></i> Academic Fee Payment
                </h3>
                <button type="button" onclick="closePayModal()" class="text-secondary hover:text-primary transition">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form action="{{ route('student.fees.pay-online') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 text-xs">
                @csrf

                <!-- OUTSTANDING BALANCE ALERT CARD -->
                <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-amber-700 block">Outstanding Academic Dues</span>
                        <div class="text-lg font-extrabold text-amber-700 font-mono">
                            ₹{{ number_format($dueFee, 2) }}
                        </div>
                    </div>
                    @if($dueFee > 0)
                        <button type="button" onclick="document.getElementById('feeAmountInput').value = '{{ $dueFee }}'" class="px-2.5 py-1 bg-amber-600 text-white rounded-md text-[10px] font-bold hover:bg-amber-700 transition">
                            Pay Full Balance
                        </button>
                    @endif
                </div>

                <!-- AMOUNT INPUT -->
                <div>
                    <label class="block font-bold text-secondary mb-1">Payment Amount (₹) *</label>
                    <input type="number" step="0.01" id="feeAmountInput" name="amount" value="{{ $dueFee > 0 ? $dueFee : ($feePlan->amount ?? 1000) }}" required placeholder="e.g. {{ $dueFee > 0 ? $dueFee : '1000' }}" class="w-full p-2.5 border-primary border-rounded bg-primary text-primary text-sm font-mono font-bold input-focus">
                    <p class="text-[11px] text-tertiary mt-1">You can pay the full outstanding balance or a custom partial installment.</p>
                </div>

                <!-- PAYMENT GATEWAY OPTIONS (CHECKOUT STYLE) -->
                <div>
                    <label class="block font-bold text-secondary mb-2">Select Payment Method / Gateway *</label>
                    
                    @php
                        $razorpayOn = ($tenantSettings['razorpay_status'] ?? '') === 'active' || !empty($tenantSettings['razorpay_key_id']) || true;
                        $cashfreeOn = ($tenantSettings['cashfree_status'] ?? '') === 'active' || !empty($tenantSettings['cashfree_app_id']);
                        $payuOn = ($tenantSettings['payu_status'] ?? '') === 'active' || !empty($tenantSettings['payu_merchant_key']);
                        $paytmOn = ($tenantSettings['paytm_status'] ?? '') === 'active' || !empty($tenantSettings['paytm_merchant_id']);
                        $manualOn = true; // Always allow manual bank/UPI or cash counter
                        $cashOn = true;
                    @endphp

                    <div class="space-y-2">
                        <!-- 1. RAZORPAY -->
                        @if($razorpayOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="razorpay" checked onchange="toggleManualBox(false)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">Razorpay (UPI / Cards / NetBanking / Wallets)</span>
                                        <span class="text-[10px] text-secondary">Instant online payment and digital receipt</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-shield-halved text-emerald-600 text-base"></i>
                            </label>
                        @endif

                        <!-- 2. CASHFREE -->
                        @if($cashfreeOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="cashfree" onchange="toggleManualBox(false)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">Cashfree Payments (UPI / Cards / NetBanking)</span>
                                        <span class="text-[10px] text-secondary">Instant secure checkout</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-bolt text-blue-600 text-base"></i>
                            </label>
                        @endif

                        <!-- 3. PAYU -->
                        @if($payuOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="payu" onchange="toggleManualBox(false)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">PayU India (Cards / NetBanking / UPI)</span>
                                        <span class="text-[10px] text-secondary">PayU payment gateway</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-building-columns text-green-600 text-base"></i>
                            </label>
                        @endif

                        <!-- 4. PAYTM -->
                        @if($paytmOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="paytm" onchange="toggleManualBox(false)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">Paytm Wallet & UPI</span>
                                        <span class="text-[10px] text-secondary">Direct Paytm payment</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-wallet text-sky-600 text-base"></i>
                            </label>
                        @endif

                        <!-- 5. MANUAL BANK / UPI -->
                        @if($manualOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="manual_bank" onchange="toggleManualBox(true)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">Manual Bank Transfer / QR Scan</span>
                                        <span class="text-[10px] text-secondary">Transfer via NEFT/UPI & enter UTR (Admin verifies)</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-qrcode text-purple-600 text-base"></i>
                            </label>
                        @endif

                        <!-- 6. CASH AT COUNTER -->
                        @if($cashOn)
                            <label class="flex items-center justify-between p-3 border border-primary border-rounded bg-secondary/30 hover:bg-hover-secondary cursor-pointer transition">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="payment_gateway" value="cash" onchange="toggleManualBox(false)" class="w-4 h-4 text-emerald-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-primary block">Cash Pay (At Academy Reception)</span>
                                        <span class="text-[10px] text-secondary">Deposit cash at front counter. Admin confirms payment</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-hand-holding-dollar text-emerald-600 text-base"></i>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- MANUAL BANK TRANSFER DETAILS (COLLAPSIBLE) -->
                @php
                    $bankName = $tenantSettings['manual_payment_bank_name'] ?? $tenantSettings['manual_bank_name'] ?? 'Academy Official Account';
                    $accHolder = $tenantSettings['manual_payment_account_holder'] ?? $tenantSettings['manual_account_holder'] ?? (app('currentTenant')->name ?? 'Academy');
                    $accNumber = $tenantSettings['manual_payment_bank_account'] ?? $tenantSettings['manual_bank_account'] ?? '987654321012';
                    $ifscCode = $tenantSettings['manual_payment_bank_ifsc'] ?? $tenantSettings['manual_bank_ifsc'] ?? 'SBIN0001234';
                    $upiId = $tenantSettings['manual_payment_upi_id'] ?? $tenantSettings['manual_upi_id'] ?? 'academy@upi';
                @endphp

                <div id="manualBankDetailsBox" class="hidden p-4 rounded-xl bg-purple-500/10 border border-purple-500/20 space-y-3">
                    <div class="flex items-center gap-2 text-purple-700 dark:text-purple-300 font-bold text-xs border-b border-purple-500/20 pb-2">
                        <i class="fa-solid fa-building-columns"></i> Academy Official Bank & UPI Details
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="p-2 bg-primary border-rounded border-primary">
                            <span class="text-[9px] text-tertiary block font-bold uppercase">Bank Name</span>
                            <strong class="text-primary">{{ $bankName }}</strong>
                        </div>
                        <div class="p-2 bg-primary border-rounded border-primary">
                            <span class="text-[9px] text-tertiary block font-bold uppercase">Account Holder</span>
                            <strong class="text-primary">{{ $accHolder }}</strong>
                        </div>
                        <div class="p-2 bg-primary border-rounded border-primary">
                            <span class="text-[9px] text-tertiary block font-bold uppercase">Account Number</span>
                            <strong class="text-primary font-mono">{{ $accNumber }}</strong>
                        </div>
                        <div class="p-2 bg-primary border-rounded border-primary">
                            <span class="text-[9px] text-tertiary block font-bold uppercase">IFSC Code</span>
                            <strong class="text-primary font-mono">{{ $ifscCode }}</strong>
                        </div>
                        <div class="sm:col-span-2 p-2 bg-primary border-rounded border-primary">
                            <span class="text-[9px] text-tertiary block font-bold uppercase">Direct UPI ID / VPA</span>
                            <strong class="text-indigo-600 font-mono">{{ $upiId }}</strong>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        <div>
                            <label class="block font-bold text-secondary mb-1">UTR / Transaction Ref Number *</label>
                            <input type="text" name="utr_number" placeholder="e.g. 329182910293 or UPI Ref" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Upload Payment Screenshot / Slip (Optional)</label>
                            <input type="file" name="payment_proof" accept="image/*,application/pdf" class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-top sticky bottom-0 bg-primary z-10 py-2">
                    <button type="button" onclick="closePayModal()" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white border-rounded font-bold hover:opacity-90 shadow-sm transition">
                        Proceed to Pay Fee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openPayModal() {
        document.getElementById('payModal').classList.remove('hidden');
    }

    function closePayModal() {
        document.getElementById('payModal').classList.add('hidden');
    }

    function toggleManualBox(show) {
        const box = document.getElementById('manualBankDetailsBox');
        if (box) {
            if (show) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }
    }
    </script>
@endsection
