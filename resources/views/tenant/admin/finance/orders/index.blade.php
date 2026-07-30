@extends('layouts.app')

@section('title', 'Finance & Orders')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders & Finance Ledger</h1>
            <p class="text-sm text-gray-500">Track purchases, verify manual bank payments, and process order fulfillments.</p>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.finance.orders') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order # or Customer..." 
                   class="px-4 py-2 text-sm border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 w-64">

            <select name="payment_status" onchange="this.form.submit()" class="px-4 py-2 text-sm border rounded-lg outline-none">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="verification_pending" {{ request('payment_status') == 'verification_pending' ? 'selected' : '' }}>Awaiting Verification</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 text-sm">Filter</button>
        </form>
    </div>

    {{-- ORDERS TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 font-bold border-b">
                        <th class="py-3.5 px-4">Order #</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Total</th>
                        <th class="py-3.5 px-4">Gateway</th>
                        <th class="py-3.5 px-4">Payment Status</th>
                        <th class="py-3.5 px-4">Fulfillment</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-all">
                            <td class="py-3.5 px-4 font-bold text-indigo-600">
                                #{{ $order->order_number }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-gray-900">
                                ₹{{ number_format($order->grand_total, 2) }}
                            </td>
                            <td class="py-3.5 px-4 uppercase font-semibold text-xs text-gray-600">
                                {{ str_replace('_', ' ', $order->payment_gateway) }}
                            </td>
                            <td class="py-3.5 px-4">
                                {!! $order->payment_status_badge !!}
                            </td>
                            <td class="py-3.5 px-4 capitalize font-semibold text-xs">
                                {{ $order->fulfillment_status }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-gray-500">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('tenant.admin.finance.orders.show', $order->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 font-bold rounded-lg hover:bg-indigo-100 text-xs inline-flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
