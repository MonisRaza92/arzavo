@extends('layouts.website')

@section('title', 'Order Placed')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center">
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
        <i class="fa-solid fa-check"></i>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Order Confirmed!</h1>
    <p class="text-gray-600 mb-6">Thank you for your order. Your order number is <strong class="text-indigo-600">#{{ $order->order_number }}</strong>.</p>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left max-w-xl mx-auto mb-8 space-y-4">
        <div class="flex justify-between items-center pb-3 border-b">
            <span class="text-sm font-semibold text-gray-500">Payment Status:</span>
            {!! $order->payment_status_badge !!}
        </div>
        <div class="flex justify-between items-center pb-3 border-b">
            <span class="text-sm font-semibold text-gray-500">Total Amount:</span>
            <span class="text-lg font-bold text-gray-900">₹{{ number_format($order->grand_total, 2) }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-500">Payment Method:</span>
            <span class="text-sm font-bold uppercase text-gray-800">{{ str_replace('_', ' ', $order->payment_gateway) }}</span>
        </div>
    </div>

    <div class="flex justify-center gap-4">
        <a href="{{ route_to('home') }}" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all">
            Return to Home
        </a>
    </div>
</div>
@endsection
