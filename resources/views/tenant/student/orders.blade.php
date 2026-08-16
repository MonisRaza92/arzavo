@extends('layouts.student')
@section('title', 'My Orders & Invoices - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping text-blue-600"></i> My Orders & Invoices
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your digital purchases, payments, and printable invoices.</p>
        </div>
    </div>

    <!-- ORDERS TABLE -->
    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Order Number</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Items Purchased</th>
                            <th class="py-2.5 px-3">Total Amount</th>
                            <th class="py-2.5 px-3">Payment Status</th>
                            <th class="py-2.5 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($orders as $order)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono font-bold text-primary">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-3 text-primary">
                                    <div class="space-y-0.5">
                                        @foreach($order->items as $item)
                                            <div class="font-medium">• {{ $item->item_name }} (x{{ $item->quantity }})</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-primary text-sm">
                                    ₹{{ number_format($order->grand_total, 2) }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($order->payment_status === 'paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">
                                            Paid
                                        </span>
                                    @elseif($order->payment_status === 'verification_pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase">
                                            Pending Verification
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 border border-rose-500/20 uppercase">
                                            {{ $order->payment_status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($order->payment_status === 'paid')
                                        <a href="{{ route('item.download', ['type' => 'book', 'id' => $order->items->first()->purchasable_id ?? 1, 'order_id' => $order->id]) }}" class="px-2.5 py-1 bg-secondary text-primary border border-primary border-rounded font-semibold text-[11px] hover:bg-hover-secondary transition">
                                            Access Items
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-2">
                <i class="fa-solid fa-bag-shopping text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No purchase orders placed yet.</p>
                <p>Browse our digital books and courses to get started.</p>
            </div>
        @endif
    </div>

    @if($orders->hasPages())
        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
