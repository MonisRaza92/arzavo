@extends('layouts.website')

@section('title', ($order->payment_status === 'paid' ? 'Order Confirmed' : 'Order Placed - Awaiting Confirmation') . ' - #' . ($order->order_number ?? ''))

@section('content')
<style>
    :root {
        --checkout-primary: {{ $customizes['checkout_primary_color'] ?? '#4f46e5' }};
        --checkout-btn-text: {{ $customizes['checkout_btn_text_color'] ?? '#ffffff' }};
        --checkout-text: {{ $customizes['checkout_text_color'] ?? '#111827' }};
        --checkout-radius: {{ $customizes['checkout_border_radius'] ?? '12' }}px;
        --checkout-border: {{ $customizes['checkout_input_border_color'] ?? '#e5e7eb' }};
        --checkout-header-bg: {{ $customizes['checkout_header_bg'] ?? '#ffffff' }};
        --checkout-form-bg: {{ $customizes['checkout_form_bg'] ?? '#ffffff' }};
        --checkout-logo-width: {{ $customizes['checkout_logo_width'] ?? 150 }}px;
        --success-icon-color: {{ $customizes['checkout_success_icon_color'] ?? '#16a34a' }};
    }

    body {
        background-color: var(--checkout-form-bg) !important;
        color: var(--checkout-text) !important;
        font-family: var(--arz-paragraph-font, 'Outfit'), sans-serif;
    }

    .checkout-header {
        background-color: var(--checkout-header-bg);
        border-bottom: 1px solid var(--checkout-border);
    }
    
    .checkout-logo-container {
        text-align: {{ $customizes['checkout_logo_align'] ?? 'left' }};
    }

    .checkout-logo-container img {
        width: var(--checkout-logo-width);
        display: inline-block;
    }

    .success-container {
        color: var(--checkout-text);
    }
    .success-card {
        background-color: var(--checkout-form-bg);
        border-radius: var(--checkout-radius);
        border: 1px solid var(--checkout-border);
    }
    .success-icon-wrapper {
        background-color: #f0fdf4;
        color: var(--success-icon-color);
    }
    .pending-icon-wrapper {
        background-color: #fffbeb;
        color: #d97706;
    }
    .success-btn {
        background-color: var(--checkout-primary) !important;
        color: var(--checkout-btn-text) !important;
        border-radius: var(--checkout-radius);
        transition: all 0.2s ease-in-out;
    }
    .success-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .success-text-primary {
        color: var(--checkout-primary);
    }
    .success-border-sep {
        border-color: var(--checkout-border);
    }
</style>

{{-- BRANDING HEADER --}}
<header class="checkout-header py-6 mb-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="checkout-logo-container">
            @if(!empty($customizes['checkout_logo']))
                <a href="{{ route_to('home') }}">
                    <img src="{{ media($customizes['checkout_logo']) }}" alt="{{ app('currentTenant')->name }} Logo">
                </a>
            @elseif(!empty($customizes['logo']))
                <a href="{{ route_to('home') }}">
                    <img src="{{ media($customizes['logo']) }}" alt="{{ app('currentTenant')->name }} Logo">
                </a>
            @else
                <a href="{{ route_to('home') }}" class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ app('currentTenant')->name }}
                </a>
            @endif
        </div>
    </div>
</header>

@php
    $isPaid = $order->payment_status === 'paid';
    $isManualOrCash = in_array($order->payment_gateway, ['manual_bank', 'cod', 'cash'], true);
    $transaction = $order->transactions->first();
@endphp

<div class="max-w-3xl mx-auto px-4 py-8 text-center success-container">
    
    @if($isPaid)
        <div class="w-20 h-20 success-icon-wrapper rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
            <i class="fa-solid fa-check"></i>
        </div>

        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Order Confirmed!</h1>
        <p class="opacity-80 mb-6 text-gray-600">
            {{ $customizes['checkout_success_subtitle'] ?? 'Thank you for your order. Your order number is' }}
            <strong class="success-text-primary font-mono">#{{ $order->order_number }}</strong>.
        </p>
    @else
        <div class="w-20 h-20 pending-icon-wrapper rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>

        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Order Placed - Waiting for Payment Confirmation</h1>
        <p class="opacity-80 mb-6 text-gray-600 max-w-xl mx-auto text-sm leading-relaxed">
            Your order <strong class="success-text-primary font-mono">#{{ $order->order_number }}</strong> has been placed successfully! Our team will verify your payment details shortly. Once confirmed by administration, your course / book access will be automatically activated in your dashboard.
        </p>
    @endif

    <div class="p-6 success-card text-left max-w-xl mx-auto mb-8 space-y-4 shadow-sm">
        {{-- Items List --}}
        @if($order->items && $order->items->count() > 0)
            <div class="pb-3 border-b success-border-sep space-y-2">
                <span class="text-xs uppercase font-bold text-gray-400">Purchased Items</span>
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-sm font-semibold">
                        <span>{{ $item->item_name }} (x{{ $item->quantity }})</span>
                        <span>₹{{ number_format($item->total_price, 2) }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex justify-between items-center pb-3 border-b success-border-sep">
            <span class="text-sm font-semibold opacity-60">Payment Status:</span>
            @if($isPaid)
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800">Paid</span>
            @elseif($order->payment_status === 'verification_pending')
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-clock mr-1"></i> Awaiting Verification
                </span>
            @else
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-hourglass-half mr-1"></i> Waiting for Payment Confirmation
                </span>
            @endif
        </div>

        <div class="flex justify-between items-center pb-3 border-b success-border-sep">
            <span class="text-sm font-semibold opacity-60">Total Amount:</span>
            <span class="text-lg font-bold text-emerald-600">₹{{ number_format($order->grand_total, 2) }}</span>
        </div>

        <div class="flex justify-between items-center pb-3 border-b success-border-sep">
            <span class="text-sm font-semibold opacity-60">Payment Method:</span>
            <span class="text-sm font-bold uppercase">
                @if($order->payment_gateway === 'cod')
                    Cash Pay (Pay at Counter)
                @elseif($order->payment_gateway === 'manual_bank')
                    Manual Bank / UPI Transfer
                @else
                    {{ str_replace('_', ' ', $order->payment_gateway) }}
                @endif
            </span>
        </div>

        @if($transaction && !empty($transaction->reference_number))
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold opacity-60">Submitted UTR / Ref:</span>
                <span class="text-sm font-mono font-bold text-gray-800">{{ $transaction->reference_number }}</span>
            </div>
        @endif

        @if(!$isPaid && $isManualOrCash)
            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200/80 text-xs text-amber-900 leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                @if($order->payment_gateway === 'cod')
                    Please visit the academy center reception with Order <strong>#{{ $order->order_number }}</strong> to complete payment.
                @else
                    Your submitted UTR / transaction screenshot has been sent to our billing department for manual approval.
                @endif
            </div>
        @endif
    </div>

    @php
        $firstItem = $order->items->first();
        $isCourse = $firstItem && in_array(strtolower(class_basename($firstItem->purchasable_type)), ['course', 'courses', 'app\models\tenant\course']);
    @endphp

    <div class="flex flex-wrap justify-center gap-4">
        @if($isPaid)
            @if($isCourse)
                <a href="{{ route('student.courses') }}" class="px-6 py-3 font-bold success-btn flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i> Access My Courses
                </a>
            @else
                <a href="{{ route('user.downloads') }}" class="px-6 py-3 font-bold success-btn flex items-center gap-2">
                    <i class="fa-solid fa-book-open"></i> Read / Download in Dashboard
                </a>
            @endif
        @else
            <a href="{{ route('user.orders') }}" class="px-6 py-3 font-bold success-btn flex items-center gap-2">
                <i class="fa-solid fa-receipt"></i> View in My Orders
            </a>
            <a href="{{ route('student.dashboard') }}" class="px-6 py-3 font-bold rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fa-solid fa-user-graduate"></i> Student Dashboard
            </a>
        @endif
        <a href="{{ route_to('home') }}" class="px-6 py-3 font-bold rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition">
            Return to Home
        </a>
    </div>
</div>
@endsection
