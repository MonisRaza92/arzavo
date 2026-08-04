@extends('layouts.user')
@section('title', 'Billing & Invoices - Customer Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-file-invoice-dollar text-emerald-500"></i> Billing & Tax Invoices
            </h1>
            <p class="text-xs text-secondary mt-0.5">Download official payment receipts and tax invoices.</p>
        </div>
    </div>

    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        @if($invoices->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Invoice #</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Description</th>
                            <th class="py-2.5 px-3">Amount</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-right">Download</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $invoice->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-3 text-primary font-semibold">Store Order #{{ $invoice->order_number ?? $invoice->id }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-primary">₹{{ number_format($invoice->grand_total, 2) }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $invoice->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                                        {{ ucfirst($invoice->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <button onclick="window.print();" class="px-3 py-1.5 bg-hover-secondary text-primary border-primary border-rounded font-bold text-xs hover-primary transition inline-flex items-center gap-1">
                                        <i class="fa-solid fa-download text-[11px]"></i> Print / PDF
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-file-invoice text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No invoices generated yet.</p>
                <p>Tax invoices will automatically be generated upon store purchases.</p>
            </div>
        @endif
    </div>
@endsection
