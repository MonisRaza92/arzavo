@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="p-6 max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('tenant.admin.finance.orders') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg text-sm">
            &larr; Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- ITEMS & PAYMENT PROOF --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-md font-bold text-gray-900 mb-4">Purchased Items</h3>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $item->item_name }}</h4>
                                <span class="text-xs text-gray-500">Format: {{ $item->fulfillment_type }} | Qty: {{ $item->quantity }}</span>
                            </div>
                            <div class="font-bold text-gray-900 text-sm">
                                ₹{{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t flex justify-between font-bold text-base text-gray-900">
                    <span>Total Amount:</span>
                    <span class="text-indigo-600">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>

            {{-- MANUAL PAYMENT PROOF INSPECTION --}}
            @if($order->payment_gateway === 'manual_bank' || $order->latestTransaction?->proof_file_path)
                <div class="bg-purple-50 p-6 rounded-xl border border-purple-100">
                    <h3 class="text-md font-bold text-purple-900 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-qrcode"></i> Manual Payment Verification
                    </h3>
                    <p class="text-xs text-purple-700 mb-4">Customer uploaded bank transfer / UPI screenshot proof below:</p>

                    @if($order->latestTransaction?->proof_file_path)
                        <div class="mb-4">
                            <a href="{{ media($order->latestTransaction->proof_file_path) }}" target="_blank">
                                <img src="{{ media($order->latestTransaction->proof_file_path) }}" class="max-h-64 rounded-lg border shadow-sm hover:opacity-90">
                            </a>
                        </div>
                    @endif

                    @if($order->payment_status === 'verification_pending' || $order->payment_status === 'unpaid')
                        <form action="{{ route('tenant.admin.finance.orders.approve', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-xs rounded-lg shadow transition-all">
                                ✓ Approve & Confirm Payment
                            </button>
                        </form>
                    @else
                        <span class="px-3 py-1 bg-green-200 text-green-800 text-xs font-bold rounded">Payment Verified & Approved</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- CUSTOMER DETAILS & STATUS ACTIONS --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4 text-sm">
                <h3 class="font-bold text-gray-900 text-md">Customer Info</h3>
                <div>
                    <span class="text-xs text-gray-500 block">Name:</span>
                    <strong class="text-gray-900">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Email:</span>
                    <strong class="text-gray-900">{{ $order->customer_email }}</strong>
                </div>
                @if($order->shipping_address)
                    <div>
                        <span class="text-xs text-gray-500 block">Shipping Address:</span>
                        <p class="text-xs text-gray-700 bg-gray-50 p-2.5 rounded border mt-1">
                            {{ implode(', ', array_filter($order->shipping_address)) }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4 text-sm">
                <h3 class="font-bold text-gray-900 text-md">Update Fulfillment</h3>
                <form action="{{ route('tenant.admin.finance.orders.fulfillment', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <select name="fulfillment_status" class="w-full px-3 py-2 text-xs border rounded-lg outline-none">
                        <option value="unfulfilled" {{ $order->fulfillment_status == 'unfulfilled' ? 'selected' : '' }}>Unfulfilled</option>
                        <option value="shipped" {{ $order->fulfillment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="fulfilled" {{ $order->fulfillment_status == 'fulfilled' ? 'selected' : '' }}>Fulfilled / Completed</option>
                        <option value="cancelled" {{ $order->fulfillment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
