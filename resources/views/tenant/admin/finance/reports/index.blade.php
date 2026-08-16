@extends('layouts.admin')
@section('title', 'Financial Reports & Analytics')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-chart-line text-primary text-base"></i>
            Financial Reports & Analytics
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Overview of revenue trends, gateway volume, and best-performing courses & books</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <a href="{{ route('admin.finance.orders') }}"
            class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
            <i class="fa-solid fa-shopping-cart"></i>
            Orders
        </a>
        <a href="{{ route('admin.finance.invoices') }}"
            class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
            <i class="fa-solid fa-file-invoice"></i>
            Invoices
        </a>
    </div>
</div>

{{-- Metric Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Today's Revenue</span>
        <div class="text-xl font-bold text-primary">₹{{ number_format($stats['today_sales'] ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">Real-time daily collection</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">This Month</span>
        <div class="text-xl font-bold text-primary">₹{{ number_format($stats['month_sales'] ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">Current month sales</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">This Year</span>
        <div class="text-xl font-bold text-primary">₹{{ number_format($stats['year_sales'] ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">Year-to-date total</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">All-Time Revenue</span>
        <div class="text-xl font-bold text-emerald-600">₹{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">Total gross receipts</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4">
    
    {{-- Gateway Breakdown --}}
    <div class="lg:col-span-5 bg-primary border-primary border-rounded overflow-hidden">
        <div class="p-4 border-bottom bg-primary flex items-center justify-between">
            <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                <i class="fa-solid fa-wallet text-secondary"></i> Payment Gateway Breakdown
            </h3>
        </div>

        <div class="p-4 space-y-4">
            @forelse($gatewayBreakdown as $gb)
                @php
                    $percentage = $stats['total_revenue'] > 0 ? round(($gb->total / $stats['total_revenue']) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-primary uppercase">
                            @if($gb->payment_gateway === 'cod')
                                Cash Pay
                            @elseif($gb->payment_gateway === 'manual_bank')
                                Manual Bank / UPI
                            @else
                                {{ $gb->payment_gateway }}
                            @endif
                            <span class="text-[10px] text-secondary font-normal">({{ $gb->count }} orders)</span>
                        </span>
                        <span class="text-primary font-bold">₹{{ number_format($gb->total, 2) }} ({{ $percentage }}%)</span>
                    </div>
                    <div class="w-full bg-secondary rounded-full h-2 overflow-hidden border border-primary">
                        <div class="bg-invert h-full rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-secondary">No payment data recorded yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Top Performing Courses & Books --}}
    <div class="lg:col-span-7 bg-primary border-primary border-rounded overflow-hidden">
        <div class="p-4 border-bottom bg-primary flex items-center justify-between">
            <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i> Top Best-Selling Items
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-secondary text-secondary border-bottom">
                        <th class="p-3">Item Title</th>
                        <th class="p-3">Type</th>
                        <th class="p-3 text-center">Units Sold</th>
                        <th class="p-3 text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topItems as $item)
                        <tr class="border-bottom hover-primary transition-all text-xs">
                            <td class="p-3 font-semibold text-primary">
                                {{ $item->item_name }}
                            </td>
                            <td class="p-3">
                                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-secondary text-primary border border-primary">
                                    {{ class_basename($item->purchasable_type ?? 'Item') }}
                                </span>
                            </td>
                            <td class="p-3 text-center font-bold text-primary">
                                {{ $item->total_qty }}
                            </td>
                            <td class="p-3 text-right font-bold text-emerald-600">
                                ₹{{ number_format($item->total_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-xs text-secondary">
                                No sales data recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Recent Transaction Ledger --}}
<div class="bg-primary border-primary border-rounded overflow-hidden">
    <div class="p-4 border-bottom bg-primary flex items-center justify-between">
        <h3 class="font-bold text-primary text-sm flex items-center gap-2">
            <i class="fa-solid fa-list-check text-secondary"></i> Recent Transactions Log
        </h3>
        <span class="text-xs text-secondary">Latest 10 payment events</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3">Tx ID / Order #</th>
                    <th class="p-3">Gateway</th>
                    <th class="p-3">Reference / UTR</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $tx)
                    <tr class="border-bottom hover-primary transition-all text-xs">
                        <td class="p-3">
                            <span class="font-mono font-bold text-primary block">{{ $tx->transaction_id ?? ('TX-' . $tx->id) }}</span>
                            @if($tx->order)
                                <a href="{{ route('admin.finance.orders.show', $tx->order->id) }}" class="text-[11px] text-secondary hover:text-primary underline">
                                    #{{ $tx->order->order_number }}
                                </a>
                            @endif
                        </td>
                        <td class="p-3 uppercase font-semibold text-primary">
                            {{ str_replace('_', ' ', $tx->gateway) }}
                        </td>
                        <td class="p-3 font-mono text-secondary">
                            {{ $tx->reference_number ?? 'N/A' }}
                        </td>
                        <td class="p-3 uppercase text-secondary">
                            {{ $tx->type ?? 'charge' }}
                        </td>
                        <td class="p-3 font-bold text-primary">
                            ₹{{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="p-3">
                            @if($tx->status === 'success')
                                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                    Success
                                </span>
                            @elseif($tx->status === 'pending')
                                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                    Pending
                                </span>
                            @else
                                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-right text-secondary">
                            {{ $tx->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-xs text-secondary">
                            No recent transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
