<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-{{ $order->order_number }} - {{ app('currentTenant')->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-box { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8 font-sans antialiased text-gray-800">

    {{-- Top Action Toolbar --}}
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('admin.finance.invoices') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold text-xs rounded-lg hover:bg-gray-50 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-lg hover:bg-indigo-700 flex items-center gap-1.5 shadow-sm transition">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
        </div>
    </div>

    {{-- Invoice Paper Container --}}
    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 p-8 sm:p-12 invoice-box shadow-xs">
        
        {{-- Header: Academy & Invoice Info --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b border-gray-200 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ app('currentTenant')->name }}</h1>
                <p class="text-xs text-gray-500 mt-1">Official Tax Invoice / Payment Receipt</p>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-xs font-mono font-bold text-indigo-600">INVOICE #INV-{{ $order->order_number }}</div>
                <div class="text-xs text-gray-500 mt-1">Date: {{ $order->created_at->format('M d, Y') }}</div>
                <span class="inline-block px-2.5 py-0.5 mt-2 text-[11px] font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase">
                    Paid
                </span>
            </div>
        </div>

        {{-- Bill To & Payment Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-8 border-b border-gray-100 text-xs">
            <div>
                <span class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Billed To</span>
                <strong class="text-gray-900 text-sm block">{{ $order->customer_name }}</strong>
                <span class="text-gray-600 block mt-0.5">{{ $order->customer_email }}</span>
                @if(!empty($order->customer_phone))
                    <span class="text-gray-600 block">{{ $order->customer_phone }}</span>
                @endif
            </div>
            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Payment Method</span>
                <strong class="text-gray-900 text-sm uppercase block">
                    @if($order->payment_gateway === 'cod')
                        Cash Pay (At Counter)
                    @elseif($order->payment_gateway === 'manual_bank')
                        Manual Bank / UPI Transfer
                    @else
                        {{ $order->payment_gateway }}
                    @endif
                </strong>
                @php $tx = $order->transactions->first(); @endphp
                @if($tx && !empty($tx->reference_number))
                    <span class="text-gray-500 block mt-0.5">Ref / UTR: {{ $tx->reference_number }}</span>
                @endif
                <span class="text-gray-500 block">Status: Completed</span>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="py-6 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3">Item Description</th>
                        <th class="py-3">Type</th>
                        <th class="py-3 text-center">Qty</th>
                        <th class="py-3 text-right">Unit Price</th>
                        <th class="py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4">
                                <strong class="text-gray-900 text-sm block">{{ $item->item_name }}</strong>
                                @if($item->variant)
                                    <span class="text-gray-500 text-[11px]">{{ $item->variant->title }}</span>
                                @endif
                            </td>
                            <td class="py-4 text-gray-500 uppercase text-[10px]">
                                {{ str_replace('_', ' ', $item->fulfillment_type ?? 'Digital') }}
                            </td>
                            <td class="py-4 text-center text-gray-700 font-semibold">
                                {{ $item->quantity }}
                            </td>
                            <td class="py-4 text-right text-gray-700 font-mono">
                                ₹{{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="py-4 text-right font-bold text-gray-900 font-mono text-sm">
                                ₹{{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="pt-6 border-t border-gray-200 flex justify-end">
            <div class="w-full sm:w-64 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-bold font-mono">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Taxes & Fees:</span>
                    <span class="font-bold font-mono">₹0.00</span>
                </div>
                <div class="flex justify-between text-base font-black text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total Paid:</span>
                    <span class="text-indigo-600 font-mono">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Invoice Footer --}}
        <div class="mt-12 pt-6 border-t border-gray-100 text-center text-[11px] text-gray-400">
            <p>This is a computer-generated tax invoice and requires no signature.</p>
            <p class="mt-1">Thank you for your business with {{ app('currentTenant')->name }}!</p>
        </div>

    </div>

</body>
</html>
