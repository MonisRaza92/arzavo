@extends('layouts.admin')
@section('title', 'Order #' . $order->order_number . ' Details')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-receipt text-primary text-base"></i>
            Order #{{ $order->order_number }}
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <a href="{{ route('admin.finance.orders') }}"
            class="px-3 py-2 text-xs font-bold bg-secondary text-primary border border-primary border-rounded hover:bg-hover-secondary flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Orders
        </a>
        @if($order->payment_status === 'paid')
            <a href="{{ route('admin.finance.invoices.show', $order->id) }}" target="_blank"
                class="px-3 py-2 text-xs font-bold bg-invert text-invert border-primary border-rounded hover-invert flex items-center gap-1.5 transition">
                <i class="fa-solid fa-print"></i>
                Print Invoice
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    
    {{-- LEFT: ITEMS & PROOF --}}
    <div class="lg:col-span-8 space-y-4">
        
        {{-- Order Items Table --}}
        <div class="bg-primary border-primary border-rounded overflow-hidden">
            <div class="p-4 border-bottom bg-primary flex items-center justify-between">
                <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                    <i class="fa-solid fa-box-open text-secondary"></i> Purchased Items
                </h3>
                <span class="text-xs font-mono font-bold text-primary">{{ $order->items->count() }} Item(s)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-secondary text-secondary border-bottom">
                            <th class="p-3">Item Details</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Qty</th>
                            <th class="p-3 text-right">Price</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-bottom hover-primary transition-all">
                                <td class="p-3">
                                    <div class="font-bold text-primary text-sm">{{ $item->item_name }}</div>
                                    @if($item->variant)
                                        <div class="text-xs text-secondary">{{ $item->variant->title }}</div>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <span class="text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-secondary text-primary border border-primary">
                                        {{ str_replace('_', ' ', $item->fulfillment_type ?? 'digital') }}
                                    </span>
                                </td>
                                <td class="p-3 text-secondary text-xs">
                                    x{{ $item->quantity }}
                                </td>
                                <td class="p-3 text-right text-xs text-secondary">
                                    ₹{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="p-3 text-right font-bold text-primary text-sm">
                                    ₹{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary Footer --}}
            <div class="p-4 border-top bg-secondary/20 space-y-2">
                <div class="flex justify-between text-xs text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-semibold text-primary">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs text-secondary">
                    <span>Tax & Platform Fee:</span>
                    <span class="font-semibold text-emerald-600">Included</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-primary pt-2 border-top border-primary">
                    <span>Grand Total:</span>
                    <span class="text-base text-primary">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- MANUAL BANK PAYMENT PROOF CARD --}}
        @php
            $tx = $order->transactions->first();
        @endphp
        @if($order->payment_gateway === 'manual_bank' || ($tx && $tx->proof_file_path))
            <div class="p-5 bg-primary border-primary border-rounded space-y-4">
                <div class="flex items-center justify-between border-b border-primary pb-3">
                    <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                        <i class="fa-solid fa-qrcode text-purple-600"></i> Manual Payment Verification & Proof
                    </h3>
                    @if($order->payment_status === 'paid')
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                            Verified & Approved
                        </span>
                    @else
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">
                            Verification Pending
                        </span>
                    @endif
                </div>

                @if($tx && !empty($tx->reference_number))
                    <div class="p-3 bg-secondary/40 border border-primary border-rounded text-xs flex items-center justify-between">
                        <span class="text-secondary">Submitted UTR / Transaction ID:</span>
                        <span class="font-mono font-bold text-primary text-sm select-all">{{ $tx->reference_number }}</span>
                    </div>
                @endif

                @if($tx && $tx->proof_file_path)
                    <div class="space-y-2">
                        <span class="text-xs text-secondary font-semibold block">Customer Uploaded Payment Receipt:</span>
                        <a href="{{ media($tx->proof_file_path) }}" target="_blank" class="block border border-primary border-rounded overflow-hidden max-w-sm hover:opacity-95 transition">
                            <img src="{{ media($tx->proof_file_path) }}" alt="Receipt Proof" class="w-full max-h-64 object-contain bg-secondary">
                        </a>
                    </div>
                @endif

                @if($order->payment_status !== 'paid')
                    <form action="{{ route('admin.finance.orders.approve', $order->id) }}" method="POST"
                          onsubmit="return confirm('Approve this manual payment and immediately unlock digital access for this user?')">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs border-rounded flex items-center gap-2 transition">
                            <i class="fa-solid fa-check"></i>
                            Approve Payment & Unlock Access
                        </button>
                    </form>
                @endif
            </div>
        @endif

    </div>

    {{-- RIGHT: STATUS & ACTIONS --}}
    <div class="lg:col-span-4 space-y-4">
        
        {{-- Customer Info Card --}}
        <div class="p-4 bg-primary border-primary border-rounded space-y-3">
            <h3 class="font-bold text-primary text-sm border-bottom pb-2 flex items-center gap-2">
                <i class="fa-solid fa-user text-secondary"></i> Customer Information
            </h3>
            
            <div class="space-y-2 text-xs">
                <div>
                    <span class="text-[11px] text-secondary block font-medium">Customer Name:</span>
                    <strong class="text-primary text-xs">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span class="text-[11px] text-secondary block font-medium">Email Address:</span>
                    <span class="text-primary font-mono text-xs">{{ $order->customer_email }}</span>
                </div>
                @if(!empty($order->customer_phone))
                    <div>
                        <span class="text-[11px] text-secondary block font-medium">Phone Number:</span>
                        <span class="text-primary font-mono text-xs">{{ $order->customer_phone }}</span>
                    </div>
                @endif
                <div>
                    <span class="text-[11px] text-secondary block font-medium">User Account Type:</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase bg-secondary text-primary border border-primary">
                        {{ $order->user ? ($order->user->role ?? 'User') : 'Guest Checkout' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Payment Status Card --}}
        <div class="p-4 bg-primary border-primary border-rounded space-y-3">
            <h3 class="font-bold text-primary text-sm border-bottom pb-2 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-secondary"></i> Payment Status & Actions
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <span class="text-secondary">Gateway:</span>
                    <span class="font-bold uppercase text-primary">
                        @if($order->payment_gateway === 'cod')
                            Cash Pay
                        @elseif($order->payment_gateway === 'manual_bank')
                            Manual Bank
                        @else
                            {{ $order->payment_gateway }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-secondary">Status:</span>
                    @if($order->payment_status === 'paid')
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">Paid</span>
                    @elseif($order->payment_status === 'verification_pending')
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">Pending Verification</span>
                    @else
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-rose-500/10 text-rose-600 border border-rose-500/20">{{ ucfirst($order->payment_status) }}</span>
                    @endif
                </div>
            </div>

            @if($order->payment_status !== 'paid')
                <div class="pt-3 border-top space-y-2">
                    <p class="text-[11px] text-secondary leading-relaxed">
                        Received offline payment or cash at counter? Click below to confirm:
                    </p>
                    <form action="{{ route('admin.finance.orders.approve', $order->id) }}" method="POST"
                          onsubmit="return confirm('Confirm payment receipt and grant immediate access?')">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs border-rounded flex items-center justify-center gap-1.5 transition">
                            <i class="fa-solid fa-check"></i>
                            Confirm & Mark as Paid
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Fulfillment Card --}}
        <div class="p-4 bg-primary border-primary border-rounded space-y-3">
            <h3 class="font-bold text-primary text-sm border-bottom pb-2 flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-secondary"></i> Fulfillment Status
            </h3>

            <form action="{{ route('admin.finance.orders.fulfillment', $order->id) }}" method="POST" class="space-y-3">
                @csrf
                <select name="fulfillment_status" class="w-full text-xs border-rounded p-2 border-primary bg-primary text-primary outline-none">
                    <option value="unfulfilled" {{ $order->fulfillment_status == 'unfulfilled' ? 'selected' : '' }}>Unfulfilled</option>
                    <option value="fulfilled" {{ $order->fulfillment_status == 'fulfilled' ? 'selected' : '' }}>Fulfilled / Digital Active</option>
                    <option value="shipped" {{ $order->fulfillment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="cancelled" {{ $order->fulfillment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="w-full py-2 bg-secondary text-primary font-bold text-xs border border-primary border-rounded hover:bg-hover-secondary transition">
                    Update Fulfillment Status
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
