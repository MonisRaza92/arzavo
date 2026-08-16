@extends('layouts.admin')
@section('title', 'Orders & Sales Ledger')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-shopping-cart text-primary text-base"></i>
            Orders & Sales <span class="hidden sm:inline">Ledger</span>
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Track customer orders, verify manual bank proofs, and manage fulfillment</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <a href="{{ route('admin.finance.invoices') }}"
            class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
            <i class="fa-solid fa-file-invoice"></i>
            Invoices
        </a>
        <a href="{{ route('admin.finance.reports') }}"
            class="px-3 py-2 text-xs font-bold bg-invert text-invert border-primary border-rounded hover-invert flex items-center gap-1.5 transition">
            <i class="fa-solid fa-chart-line"></i>
            Reports
        </a>
    </div>
</div>

{{-- Metric Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Total Revenue</span>
        <div class="text-xl font-bold text-primary">₹{{ number_format($stats['total_sales'] ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">From paid checkouts</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Paid Orders</span>
        <div class="text-xl font-bold text-emerald-600">{{ $stats['paid_count'] ?? 0 }}</div>
        <span class="text-[10px] text-tertiary">Successfully settled</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Pending Verifications</span>
        <div class="text-xl font-bold text-amber-600">{{ $stats['pending_count'] ?? 0 }}</div>
        <span class="text-[10px] text-tertiary">Manual bank / cash orders</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Total Placed Orders</span>
        <div class="text-xl font-bold text-primary">{{ $stats['total_orders'] ?? 0 }}</div>
        <span class="text-[10px] text-tertiary">All-time transactions</span>
    </div>
</div>

{{-- Filters and Search Bar --}}
<div class="p-3 bg-primary border-rounded border-primary mb-4 flex flex-wrap items-center justify-between gap-3">
    <form action="{{ route('admin.finance.orders') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order # or customer..." 
                   class="w-full text-xs border-rounded px-3 py-2 input-focus border-primary bg-primary text-primary">
        </div>

        <select name="payment_status" onchange="this.form.submit()" 
                class="text-xs border-rounded px-3 py-2 border-primary bg-primary text-primary outline-none">
            <option value="">All Payment Statuses</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="verification_pending" {{ request('payment_status') == 'verification_pending' ? 'selected' : '' }}>Awaiting Verification</option>
            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>

        <select name="gateway" onchange="this.form.submit()" 
                class="text-xs border-rounded px-3 py-2 border-primary bg-primary text-primary outline-none">
            <option value="">All Gateways</option>
            <option value="razorpay" {{ request('gateway') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
            <option value="cashfree" {{ request('gateway') == 'cashfree' ? 'selected' : '' }}>Cashfree</option>
            <option value="payu" {{ request('gateway') == 'payu' ? 'selected' : '' }}>PayU</option>
            <option value="paytm" {{ request('gateway') == 'paytm' ? 'selected' : '' }}>Paytm</option>
            <option value="manual_bank" {{ request('gateway') == 'manual_bank' ? 'selected' : '' }}>Manual Bank</option>
            <option value="cod" {{ request('gateway') == 'cod' ? 'selected' : '' }}>Cash Pay</option>
        </select>

        <button type="submit" class="px-3 py-2 bg-secondary text-primary font-bold text-xs border border-primary border-rounded hover:bg-hover-secondary transition">
            Filter
        </button>

        @if(request()->hasAny(['search', 'payment_status', 'gateway']))
            <a href="{{ route('admin.finance.orders') }}" class="text-xs text-secondary hover:text-primary font-semibold">
                Clear Filters
            </a>
        @endif
    </form>
</div>

{{-- Orders Table --}}
<div class="bg-primary border-primary border-rounded overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3">Order #</th>
                    <th class="p-3">Customer</th>
                    <th class="p-3">Items</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Gateway</th>
                    <th class="p-3">Payment Status</th>
                    <th class="p-3">Fulfillment</th>
                    <th class="p-3">Date</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-bottom hover-primary transition-all">
                        <td class="p-3 font-mono font-bold text-primary">
                            #{{ $order->order_number }}
                        </td>
                        <td class="p-3">
                            <div class="font-semibold text-primary text-xs">{{ $order->customer_name }}</div>
                            <div class="text-[11px] text-secondary">{{ $order->customer_email }}</div>
                        </td>
                        <td class="p-3 text-xs text-secondary">
                            {{ $order->items->count() }} item(s)
                        </td>
                        <td class="p-3 font-bold text-primary">
                            ₹{{ number_format($order->grand_total, 2) }}
                        </td>
                        <td class="p-3">
                            <span class="text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-secondary text-primary border border-primary">
                                @if($order->payment_gateway === 'cod')
                                    Cash Pay
                                @elseif($order->payment_gateway === 'manual_bank')
                                    Manual Bank
                                @else
                                    {{ $order->payment_gateway }}
                                @endif
                            </span>
                        </td>
                        <td class="p-3">
                            @if($order->payment_status === 'paid')
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                    Paid
                                </span>
                            @elseif($order->payment_status === 'verification_pending')
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                    Awaiting Approval
                                </span>
                            @else
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-xs text-secondary capitalize">
                            {{ $order->fulfillment_status }}
                        </td>
                        <td class="p-3 text-xs text-secondary">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('admin.finance.orders.show', $order->id) }}" 
                               class="px-3 py-1.5 bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary text-xs font-bold transition inline-flex items-center gap-1">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-secondary text-xs">
                            <i class="fa-solid fa-shopping-cart text-2xl text-tertiary mb-2 block"></i>
                            No orders found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="p-3 border-top bg-primary">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
