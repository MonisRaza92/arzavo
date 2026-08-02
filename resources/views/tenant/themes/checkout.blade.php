@extends('layouts.website')

@section('title', 'Checkout')

@section('content')
<style>
    :root {
        --checkout-primary: {{ $customizes['checkout_primary_color'] ?? '#4f46e5' }};
        --checkout-btn-text: {{ $customizes['checkout_btn_text_color'] ?? '#ffffff' }};
        --checkout-text: {{ $customizes['checkout_text_color'] ?? '#111827' }};
        --checkout-input-bg: {{ $customizes['checkout_input_bg'] ?? '#ffffff' }};
        --checkout-input-text: {{ $customizes['checkout_input_text_color'] ?? '#111827' }};
        --checkout-radius: {{ $customizes['checkout_border_radius'] ?? '12' }}px;
        --checkout-border: {{ $customizes['checkout_input_border_color'] ?? '#e5e7eb' }};
        
        --checkout-header-bg: {{ $customizes['checkout_header_bg'] ?? '#ffffff' }};
        --checkout-form-bg: {{ $customizes['checkout_form_bg'] ?? '#ffffff' }};
        --checkout-summary-bg: {{ $customizes['checkout_summary_bg'] ?? '#fafafa' }};
        
        --checkout-logo-width: {{ $customizes['checkout_logo_width'] ?? 150 }}px;
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

    .checkout-card {
        background-color: var(--checkout-form-bg);
        border-radius: var(--checkout-radius);
        border: 1px solid var(--checkout-border);
    }
    .checkout-input {
        background-color: var(--checkout-input-bg) !important;
        color: var(--checkout-input-text) !important;
        border: 1px solid var(--checkout-border) !important;
        border-radius: var(--checkout-radius) !important;
    }
    .checkout-input:focus {
        border-color: var(--checkout-primary) !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }
    .checkout-btn {
        background-color: var(--checkout-primary) !important;
        color: var(--checkout-btn-text) !important;
        border-radius: var(--checkout-radius);
        transition: all 0.2s ease-in-out;
    }
    .checkout-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .checkout-text-primary {
        color: var(--checkout-primary);
    }
    .checkout-border-sep {
        border-color: var(--checkout-border);
    }
    .order-summary-box {
        background-color: var(--checkout-summary-bg);
        border-radius: var(--checkout-radius);
        border: 1px solid var(--checkout-border);
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

<div class="max-w-6xl mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- ?? CHECKOUT FORM --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 checkout-card shadow-sm">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user checkout-text-primary"></i> Contact Information
                </h2>

                <form id="checkout-form" action="{{ route('checkout.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($item)
                        <input type="hidden" name="items[0][purchasable_type]" value="{{ get_class($item) }}">
                        <input type="hidden" name="items[0][purchasable_id]" value="{{ $item->id }}">
                        <input type="hidden" name="items[0][variant_id]" value="{{ $variant?->id }}">
                        <input type="hidden" name="items[0][item_name]" value="{{ $item->title ?? $item->name }}">
                        <input type="hidden" name="items[0][unit_price]" value="{{ $variant?->price ?? $item->sale_price ?? $item->price }}">
                        <input type="hidden" name="items[0][fulfillment_type]" value="{{ $variant?->fulfillment_type ?? 'digital_download' }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Full Name *</label>
                            <input type="text" name="customer_name" required value="{{ auth()->user()?->name }}" 
                                   class="w-full px-4 py-2.5 outline-none checkout-input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Email Address *</label>
                            <input type="email" name="customer_email" required value="{{ auth()->user()?->email }}" 
                                   class="w-full px-4 py-2.5 outline-none checkout-input">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold mb-1">Phone Number</label>
                        <input type="tel" name="customer_phone" placeholder="+91 9876543210" 
                               class="w-full px-4 py-2.5 outline-none checkout-input">
                    </div>

                    {{-- ?? SHIPPING ADDRESS (Shown for physical delivery items) --}}
                    @if(!$variant || $variant->fulfillment_type === 'physical_shipping')
                        <div class="mt-6 pt-6 border-t checkout-border-sep">
                            <h3 class="text-md font-bold mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-truck-fast checkout-text-primary"></i> Shipping Address
                            </h3>
                            <div class="space-y-3">
                                <input type="text" name="shipping_address[street]" placeholder="House No, Street, Landmark" 
                                       class="w-full px-4 py-2.5 outline-none checkout-input">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <input type="text" name="shipping_address[city]" placeholder="City" class="px-4 py-2.5 outline-none checkout-input">
                                    <input type="text" name="shipping_address[state]" placeholder="State" class="px-4 py-2.5 outline-none checkout-input">
                                    <input type="text" name="shipping_address[pincode]" placeholder="Pincode" class="px-4 py-2.5 outline-none checkout-input">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ?? PAYMENT GATEWAYS --}}
                    <div class="mt-6 pt-6 border-t checkout-border-sep">
                        <h3 class="text-md font-bold mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card checkout-text-primary"></i> Select Payment Method
                        </h3>

                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:opacity-90 transition-all checkout-input">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="razorpay" checked class="focus:ring-0">
                                    <div>
                                        <span class="font-bold block">Online Payment (Razorpay / UPI / Cards / NetBanking)</span>
                                        <span class="text-xs opacity-75">Instant activation & secure checkout</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-shield-halved text-green-600"></i>
                            </label>

                            <label class="flex items-center justify-between p-4 cursor-pointer hover:opacity-90 transition-all checkout-input">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="cod" class="focus:ring-0">
                                    <div>
                                        <span class="font-bold block">Cash on Delivery (COD)</span>
                                        <span class="text-xs opacity-75">Pay when physical item is delivered</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-hand-holding-dollar text-indigo-600"></i>
                            </label>

                            <label class="flex items-center justify-between p-4 cursor-pointer hover:opacity-90 transition-all checkout-input" onclick="document.getElementById('manual-payment-box').classList.remove('hidden')">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="manual_bank" class="focus:ring-0">
                                    <div>
                                        <span class="font-bold block">Manual Bank Transfer / QR Code / UPI</span>
                                        <span class="text-xs opacity-75">Pay via Bank/QR & upload payment receipt screenshot</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-qrcode text-purple-600"></i>
                            </label>
                        </div>

                        {{-- MANUAL BANK PROOF UPLOAD --}}
                        <div id="manual-payment-box" class="hidden mt-4 p-4 rounded-xl bg-purple-50 border border-purple-100 space-y-3">
                            <p class="text-xs font-semibold text-purple-900">Scan QR or Transfer to Bank Account below, then upload screenshot:</p>
                            <div class="text-xs text-gray-700 bg-white p-3 rounded-lg border">
                                <strong>UPI ID:</strong> pay@arzavo.com<br>
                                <strong>Bank Account:</strong> HDFC Bank | A/C: 50200012345678 | IFSC: HDFC0001234
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Transaction Ref / UTR Number</label>
                                <input type="text" name="reference_number" placeholder="e.g. 319203910293" class="w-full px-3 py-2 text-xs rounded-lg border outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Screenshot Proof</label>
                                <input type="file" name="payment_proof_file" accept="image/*" class="text-xs w-full">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full py-4 font-bold shadow-lg hover:shadow-xl checkout-btn">
                        Complete Order
                    </button>
                </form>
            </div>
        </div>

        {{-- ?? ORDER SUMMARY --}}
        <div class="lg:col-span-5">
            <div class="p-6 order-summary-box shadow-sm sticky top-6">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>

                @if($item)
                    <div class="flex gap-4 pb-4 border-b checkout-border-sep">
                        @if($item->cover_image || $item->thumbnail)
                            <img src="{{ media($item->cover_image ?? $item->thumbnail) }}" class="w-16 h-20 object-cover rounded-lg border shadow-sm">
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">{{ $item->title ?? $item->name }}</h4>
                            @if($variant)
                                <span class="inline-block px-2 py-0.5 mt-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded">
                                    {{ $variant->title }}
                                </span>
                            @endif
                            <div class="mt-2 text-sm font-bold checkout-text-primary">
                                ?{{ number_format($variant?->price ?? $item->sale_price ?? $item->price, 2) }}
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm opacity-75">Your cart items will appear here.</p>
                @endif

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between opacity-90">
                        <span>Subtotal</span>
                        <span>?{{ number_format($variant?->price ?? $item->sale_price ?? $item->price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between opacity-90">
                        <span>Shipping</span>
                        <span class="text-green-600 font-semibold">FREE</span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-3 border-t checkout-border-sep">
                        <span>Total Payable</span>
                        <span class="checkout-text-primary">?{{ number_format($variant?->price ?? $item->sale_price ?? $item->price ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
