@extends('layouts.user')
@section('title', 'My Orders - Customer Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping text-blue-500"></i> My Orders & Purchases
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track your order history and payment receipts.</p>
        </div>
    </div>

    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Order ID</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Items</th>
                            <th class="py-2.5 px-3">Total Amount</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-right">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($orders as $order)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">#{{ $order->order_number ?? 'ORD-' . $order->id }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="py-3 px-3 text-primary font-semibold">Study Material / Book Order</td>
                                <td class="py-3 px-3 font-mono font-bold text-primary">₹{{ number_format($order->grand_total, 2) }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('user.invoices') }}" class="px-3 py-1.5 bg-hover-secondary text-primary border-primary border-rounded font-bold text-xs hover-primary transition">
                                        View Invoice
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $orders->links() }}
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-bag-shopping text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No orders placed yet.</p>
                <p>When you purchase books or courses, your orders will appear here.</p>
            </div>
        @endif
    </div>
@endsection
