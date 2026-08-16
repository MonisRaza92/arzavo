@extends('layouts.admin')
@section('title', 'Finance Invoices')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-file-invoice text-primary text-base"></i>
            Tax Invoices & Receipts
        </h2>
        <p class="text-sm text-secondary hidden sm:block">View, download, and print official invoices for paid customer orders</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <a href="{{ route('admin.finance.orders') }}"
            class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
            <i class="fa-solid fa-shopping-cart"></i>
            Orders
        </a>
        <a href="{{ route('admin.finance.reports') }}"
            class="px-3 py-2 text-xs font-bold bg-invert text-invert border-primary border-rounded hover-invert flex items-center gap-1.5 transition">
            <i class="fa-solid fa-chart-line"></i>
            Reports
        </a>
    </div>
</div>

{{-- Metric Card --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Total Invoiced Amount</span>
        <div class="text-xl font-bold text-emerald-600">₹{{ number_format($totalInvoiced ?? 0, 2) }}</div>
        <span class="text-[10px] text-tertiary">All completed & paid tax invoices</span>
    </div>

    <div class="p-4 bg-primary border-rounded border-primary space-y-1">
        <span class="text-[11px] font-semibold text-secondary uppercase tracking-wider block">Total Invoices Generated</span>
        <div class="text-xl font-bold text-primary">{{ $invoices->total() ?? 0 }}</div>
        <span class="text-[10px] text-tertiary">Unique billing transactions</span>
    </div>
</div>

{{-- Filters and Search Bar --}}
<div class="p-3 bg-primary border-rounded border-primary mb-4 flex flex-wrap items-center justify-between gap-3">
    <form action="{{ route('admin.finance.invoices') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-72">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice # or customer..." 
                   class="w-full text-xs border-rounded px-3 py-2 input-focus border-primary bg-primary text-primary">
        </div>

        <button type="submit" class="px-3 py-2 bg-secondary text-primary font-bold text-xs border border-primary border-rounded hover:bg-hover-secondary transition">
            Search
        </button>

        @if(request()->filled('search'))
            <a href="{{ route('admin.finance.invoices') }}" class="text-xs text-secondary hover:text-primary font-semibold">
                Clear Search
            </a>
        @endif
    </form>
</div>

{{-- Invoices Table --}}
<div class="bg-primary border-primary border-rounded overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3">Invoice #</th>
                    <th class="p-3">Customer Name</th>
                    <th class="p-3">Billing Email</th>
                    <th class="p-3">Payment Method</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Invoice Date</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr class="border-bottom hover-primary transition-all">
                        <td class="p-3 font-mono font-bold text-primary">
                            INV-{{ $invoice->order_number }}
                        </td>
                        <td class="p-3 font-semibold text-primary text-xs">
                            {{ $invoice->customer_name }}
                        </td>
                        <td class="p-3 text-xs text-secondary font-mono">
                            {{ $invoice->customer_email }}
                        </td>
                        <td class="p-3">
                            <span class="text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-secondary text-primary border border-primary">
                                @if($invoice->payment_gateway === 'cod')
                                    Cash Pay
                                @elseif($invoice->payment_gateway === 'manual_bank')
                                    Manual Bank
                                @else
                                    {{ $invoice->payment_gateway }}
                                @endif
                            </span>
                        </td>
                        <td class="p-3 font-bold text-emerald-600">
                            ₹{{ number_format($invoice->grand_total, 2) }}
                        </td>
                        <td class="p-3 text-xs text-secondary">
                            {{ $invoice->created_at->format('M d, Y') }}
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('admin.finance.invoices.show', $invoice->id) }}" target="_blank"
                               class="px-3 py-1.5 bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary text-xs font-bold transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-print"></i> View & Print
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-secondary text-xs">
                            <i class="fa-solid fa-file-invoice text-2xl text-tertiary mb-2 block"></i>
                            No paid invoices found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
        <div class="p-3 border-top bg-primary">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
