@extends('layouts.website')

@section('title', 'Order Placed')

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
        --checkout-summary-bg: {{ $customizes['checkout_summary_bg'] ?? '#fafafa' }};
        
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

{{-- ??? CHECKOUT HEADER / BRANDING AREA --}}
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

<div class="max-w-3xl mx-auto px-4 py-8 text-center success-container">
    <div class="w-20 h-20 success-icon-wrapper rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
        <i class="fa-solid fa-check"></i>
    </div>

    <h1 class="text-3xl font-extrabold mb-2">Order Confirmed!</h1>
    <p class="opacity-80 mb-6">
        {{ $customizes['checkout_success_subtitle'] ?? 'Thank you for your order. Your order number is' }}
        <strong class="success-text-primary">#{{ $order->order_number }}</strong>.
    </p>

    <div class="p-6 success-card text-left max-w-xl mx-auto mb-8 space-y-4 shadow-sm">
        <div class="flex justify-between items-center pb-3 border-b success-border-sep">
            <span class="text-sm font-semibold opacity-60">Payment Status:</span>
            {!! $order->payment_status_badge !!}
        </div>
        <div class="flex justify-between items-center pb-3 border-b success-border-sep">
            <span class="text-sm font-semibold opacity-60">Total Amount:</span>
            <span class="text-lg font-bold">?{{ number_format($order->grand_total, 2) }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm font-semibold opacity-60">Payment Method:</span>
            <span class="text-sm font-bold uppercase">{{ str_replace('_', ' ', $order->payment_gateway) }}</span>
        </div>
    </div>

    <div class="flex justify-center gap-4">
        <a href="{{ route_to('home') }}" class="px-6 py-3 font-bold success-btn">
            Return to Home
        </a>
    </div>
</div>
@endsection
