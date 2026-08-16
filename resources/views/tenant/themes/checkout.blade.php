@extends('layouts.website')

@section('title', 'Checkout')

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

    .checkout-btn {
        background-color: var(--checkout-primary) !important;
        color: var(--checkout-btn-text) !important;
        border-radius: var(--checkout-radius);
        transition: all 0.2s ease-in-out;
    }

    .checkout-btn:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    .checkout-text-primary {
        color: var(--checkout-primary);
    }

    .checkout-border-sep {
        border-color: var(--checkout-border);
    }
</style>

{{-- 🏛️ CHECKOUT HEADER --}}
<header class="checkout-header py-5 mb-8">
    <div class="max-w-5xl mx-auto px-4">
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

<div class="max-w-5xl mx-auto px-4 pb-16">
    @php
        $authUser = auth('tenant')->user() ?? auth()->user();
        $itemPrice = (float) ($variant?->price ?? $item?->sale_price ?? $item?->price ?? 0);
    @endphp

    @if(!empty($isPurchased) && $isPurchased)
        {{-- 🎉 ALREADY PURCHASED BANNER --}}
        <div class="mb-8 p-6 sm:p-8 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-xs text-center space-y-4">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wider">Item Already Purchased</span>
                <h2 class="text-2xl font-black text-emerald-950 mt-2">Aap Yeh Item Pehle Hi Buy Kar Chuke Hain!</h2>
                <p class="text-sm text-emerald-700 mt-1 max-w-lg mx-auto">
                    Yeh {{ ($purchasableType ?? '') === 'course' ? 'course' : 'book' }} aapke account me already active hai. Aap direct ise access kar sakte hain.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                @if(($purchasableType ?? '') === 'course' || ($item instanceof \App\Models\Tenant\Course))
                    <a href="{{ route('student.courses') }}" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap"></i> Go to My Courses
                    </a>
                @else
                    <a href="{{ route('user.downloads') }}" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-book-open"></i> View in My Downloads
                    </a>
                @endif
                <a href="{{ route_to('home') }}" class="px-6 py-3 rounded-xl bg-white border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                    Browse Other Items
                </a>
            </div>
        </div>
    @endif

    <form id="checkout-form" action="{{ route('checkout.submit') }}" method="POST" enctype="multipart/form-data" class="{{ !empty($isPurchased) && $isPurchased ? 'opacity-50 pointer-events-none' : '' }}">
        @csrf

        @if($item)
            <input type="hidden" name="items[0][purchasable_type]" value="{{ get_class($item) }}">
            <input type="hidden" name="items[0][purchasable_id]" value="{{ $item->id }}">
            <input type="hidden" name="items[0][variant_id]" value="{{ $variant?->id }}">
            <input type="hidden" name="items[0][item_name]" value="{{ $item->title ?? $item->name }}">
            <input type="hidden" name="items[0][unit_price]" value="{{ $itemPrice }}">
            <input type="hidden" name="items[0][fulfillment_type]" value="{{ $variant?->fulfillment_type ?? 'digital_download' }}">
        @endif

        {{-- Hidden Auto-Filled Customer Details --}}
        <input type="hidden" name="customer_name" value="{{ $authUser ? ($authUser->name ?? trim(($authUser->fname ?? '') . ' ' . ($authUser->lname ?? ''))) : 'Student' }}">
        <input type="hidden" name="customer_email" value="{{ $authUser?->email ?? ('student@' . request()->getHost()) }}">
        <input type="hidden" name="customer_phone" value="{{ $authUser?->number ?? $authUser?->phone ?? '' }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- 📦 LEFT: PRODUCT OVERVIEW CARD --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="p-6 checkout-card shadow-sm">
                    <h2 class="text-sm uppercase tracking-wider text-gray-500 font-bold mb-4">
                        Selected Item Overview
                    </h2>

                    @if($item)
                        <div class="flex flex-col sm:flex-row gap-5 items-start">
                            @if($item->cover_image || $item->thumbnail)
                                <img src="{{ media($item->cover_image ?? $item->thumbnail) }}" 
                                     class="w-full sm:w-28 sm:h-36 object-cover rounded-xl border border-gray-100 shadow-sm shrink-0">
                            @endif

                            <div class="flex-1 space-y-2">
                                <h3 class="text-lg font-bold text-gray-900 leading-snug">
                                    {{ $item->title ?? $item->name }}
                                </h3>

                                @if($item->bookCategory || $item->academicCategory || $item->subject)
                                    <div class="flex flex-wrap gap-2 pt-1">
                                        @if($item->bookCategory)
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $item->bookCategory->name }}
                                            </span>
                                        @endif
                                        @if($item->subject)
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                                {{ $item->subject->name }}
                                            </span>
                                        @endif
                                        @if($variant)
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $variant->title }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                @if(!empty($item->description) || !empty($item->short_description))
                                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed pt-1">
                                        {{ strip_tags($item->short_description ?? $item->description) }}
                                    </p>
                                @endif

                                <div class="flex items-center gap-3 pt-2 text-xs text-gray-500">
                                    <span class="flex items-center gap-1.5 font-medium text-emerald-600">
                                        <i class="fa-solid fa-circle-check"></i> Instant Access
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1.5 font-medium text-gray-600">
                                        <i class="fa-solid fa-lock"></i> 100% Secure Checkout
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No item selected.</p>
                    @endif
                </div>

                {{-- 💳 PAYMENT GATEWAY SELECTION --}}
                @php
                    $tenantSettings = \App\Models\Tenant\Settings::pluck('value', 'key')->toArray();
                    $onlineEnabled = ($tenantSettings['payment_mode_online'] ?? '1') == '1';
                    $codEnabled = ($tenantSettings['payment_mode_cod'] ?? '0') == '1';
                    $manualEnabled = ($tenantSettings['payment_mode_manual'] ?? '1') == '1';

                    $razorpayOn = ($tenantSettings['razorpay_enabled'] ?? '1') == '1';
                    $cashfreeOn = ($tenantSettings['cashfree_enabled'] ?? '0') == '1';
                    $payuOn = ($tenantSettings['payu_enabled'] ?? '0') == '1';
                    $paytmOn = ($tenantSettings['paytm_enabled'] ?? '0') == '1';

                    $selectedGateway = 'razorpay';
                    if ($onlineEnabled) {
                        if ($razorpayOn) $selectedGateway = 'razorpay';
                        elseif ($cashfreeOn) $selectedGateway = 'cashfree';
                        elseif ($payuOn) $selectedGateway = 'payu';
                        elseif ($paytmOn) $selectedGateway = 'paytm';
                    } elseif ($manualEnabled) {
                        $selectedGateway = 'manual_bank';
                    } elseif ($codEnabled) {
                        $selectedGateway = 'cod';
                    }
                @endphp

                <div class="p-6 checkout-card shadow-sm">
                    <h2 class="text-sm uppercase tracking-wider text-gray-500 font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-credit-card checkout-text-primary"></i> Select Payment Method
                    </h2>

                    <div class="space-y-3">
                        @if($onlineEnabled && $razorpayOn)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="razorpay" {{ $selectedGateway === 'razorpay' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">Razorpay (UPI / Cards / NetBanking / Wallets)</span>
                                        <span class="text-xs text-gray-500">Instant digital access after payment</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-shield-halved text-emerald-600 text-lg"></i>
                            </label>
                        @endif

                        @if($onlineEnabled && $cashfreeOn)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="cashfree" {{ $selectedGateway === 'cashfree' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">Cashfree Payments (UPI / Cards / NetBanking)</span>
                                        <span class="text-xs text-gray-500">Fast checkout with instant confirmation</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-bolt text-blue-600 text-lg"></i>
                            </label>
                        @endif

                        @if($onlineEnabled && $payuOn)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="payu" {{ $selectedGateway === 'payu' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">PayU India (Cards / NetBanking / UPI)</span>
                                        <span class="text-xs text-gray-500">Secure gateway powered by PayU</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-building-columns text-green-600 text-lg"></i>
                            </label>
                        @endif

                        @if($onlineEnabled && $paytmOn)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="paytm" {{ $selectedGateway === 'paytm' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">Paytm Wallet & UPI</span>
                                        <span class="text-xs text-gray-500">Pay directly with Paytm</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-wallet text-sky-600 text-lg"></i>
                            </label>
                        @endif

                        @if($manualEnabled)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all gateway-option-label">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="manual_bank" {{ $selectedGateway === 'manual_bank' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">Manual UPI / QR / Bank Transfer</span>
                                        <span class="text-xs text-gray-500">Pay via scanner/bank transfer & upload UTR transaction proof</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-qrcode text-purple-600 text-lg"></i>
                            </label>
                        @endif

                        @if($codEnabled)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50/80 rounded-xl border border-gray-200 transition-all gateway-option-label">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="cod" {{ $selectedGateway === 'cod' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-0">
                                    <div>
                                        <span class="font-bold text-sm block text-gray-900">Cash Pay (Pay at Counter / Center)</span>
                                        <span class="text-xs text-gray-500">Pay cash at academy reception. Access activated upon admin verification</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-hand-holding-dollar text-emerald-600 text-lg"></i>
                            </label>
                        @endif
                    </div>

                    {{-- 🏛️ MANUAL BANK & UPI DETAILS CARD --}}
                    @php
                        $bankName = $tenantSettings['manual_payment_bank_name'] ?? $tenantSettings['manual_bank_name'] ?? '';
                        $accHolder = $tenantSettings['manual_payment_account_holder'] ?? $tenantSettings['manual_account_holder'] ?? '';
                        $accNumber = $tenantSettings['manual_payment_bank_account'] ?? $tenantSettings['manual_bank_account'] ?? '';
                        $ifscCode = $tenantSettings['manual_payment_bank_ifsc'] ?? $tenantSettings['manual_bank_ifsc'] ?? '';
                        $upiId = $tenantSettings['manual_payment_upi_id'] ?? $tenantSettings['manual_upi_id'] ?? '';
                        $swiftCode = $tenantSettings['manual_payment_swift_code'] ?? $tenantSettings['manual_swift_code'] ?? '';
                    @endphp

                    <div id="manual-payment-box" class="{{ $selectedGateway === 'manual_bank' ? '' : 'hidden' }} mt-5 p-5 rounded-2xl bg-gradient-to-br from-purple-50/80 via-white to-purple-50/40 border border-purple-200/80 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-purple-100 pb-3">
                            <div class="flex items-center gap-2 text-purple-900 font-bold text-sm">
                                <i class="fa-solid fa-building-columns text-purple-600"></i> Academy Bank Account & UPI Details
                            </div>
                            <span class="text-[11px] font-semibold text-purple-700 bg-purple-100 px-2.5 py-0.5 rounded-full">Direct Payment</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            @if(!empty($bankName))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Bank Name</span>
                                    <span class="font-bold text-gray-900 text-xs">{{ $bankName }}</span>
                                </div>
                            @endif

                            @if(!empty($accHolder))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Account Holder</span>
                                    <span class="font-bold text-gray-900 text-xs">{{ $accHolder }}</span>
                                </div>
                            @endif

                            @if(!empty($accNumber))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Account Number</span>
                                    <span class="font-mono font-bold text-purple-900 text-xs select-all">{{ $accNumber }}</span>
                                </div>
                            @endif

                            @if(!empty($ifscCode))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">IFSC Code</span>
                                    <span class="font-mono font-bold text-purple-900 text-xs select-all">{{ $ifscCode }}</span>
                                </div>
                            @endif

                            @if(!empty($upiId))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs sm:col-span-2 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-purple-600 block">UPI ID / VPA</span>
                                        <span class="font-mono font-black text-gray-900 text-sm select-all">{{ $upiId }}</span>
                                    </div>
                                    <span class="px-2 py-1 bg-purple-50 text-purple-700 font-bold text-[10px] rounded-lg">GPay / PhonePe / Paytm</span>
                                </div>
                            @endif

                            @if(!empty($swiftCode))
                                <div class="bg-white p-2.5 rounded-xl border border-purple-100/80 shadow-2xs">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">SWIFT / BIC Code</span>
                                    <span class="font-mono font-bold text-gray-900 text-xs">{{ $swiftCode }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="pt-2 border-t border-purple-100 space-y-2">
                            <p class="text-xs font-semibold text-purple-950 flex items-center gap-1.5">
                                <i class="fa-solid fa-receipt text-purple-600"></i> Submit payment transaction proof after transferring:
                            </p>
                            <div class="grid sm:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Transaction Ref / 12-digit UTR No.</label>
                                    <input type="text" name="reference_number" placeholder="e.g. 423982739102" 
                                           class="w-full px-3 py-2 text-xs rounded-xl border border-purple-200 outline-none bg-white focus:border-purple-600 focus:ring-1 focus:ring-purple-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Payment Screenshot</label>
                                    <input type="file" name="payment_proof_file" accept="image/*" class="text-xs w-full py-1.5 px-2 bg-white rounded-xl border border-purple-200">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 💰 RIGHT: ORDER SUMMARY & PAY BUTTON --}}
            <div class="lg:col-span-5">
                <div class="p-6 checkout-card shadow-sm sticky top-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-3 border-b checkout-border-sep flex items-center justify-between">
                        <span>Payment Summary</span>
                        <span class="text-xs font-normal text-emerald-600 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-shield"></i> Verified Order
                        </span>
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Item Price</span>
                            <span>₹{{ number_format($itemPrice, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Platform Fee & Tax</span>
                            <span class="text-emerald-600 font-medium">Included</span>
                        </div>

                        <div class="flex justify-between text-lg font-black text-gray-900 pt-3 border-t checkout-border-sep">
                            <span>Total Payable</span>
                            <span class="checkout-text-primary">₹{{ number_format($itemPrice, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" id="pay-button" class="mt-6 w-full py-3.5 px-4 font-bold text-sm shadow-md hover:shadow-lg checkout-btn flex items-center justify-center gap-2 cursor-pointer transition">
                        <span id="pay-button-text">Pay ₹{{ number_format($itemPrice, 2) }} & Get Access</span>
                        <i id="pay-button-icon" class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                    <p class="text-center text-[11px] text-gray-400 mt-3 flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                        Encrypted 256-bit secure checkout
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

{{-- Hidden Verification Form for Razorpay --}}
<form id="direct_razorpay_verify_form" method="POST" action="{{ route('checkout.razorpay.verify') }}" style="display: none;">
    @csrf
    <input type="hidden" name="order_number" id="rzp_order_number">
    <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
    <input type="hidden" name="razorpay_order_id" id="rzp_order_id">
    <input type="hidden" name="razorpay_signature" id="rzp_signature">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const payBtn = document.getElementById('pay-button');
    const payBtnText = document.getElementById('pay-button-text');
    const payBtnIcon = document.getElementById('pay-button-icon');

    if (!form || !payBtn) return;

    // Toggle manual payment box based on radio selection
    const manualBox = document.getElementById('manual-payment-box');
    const gatewayRadios = form.querySelectorAll('input[name="payment_gateway"]');

    function updateManualBox() {
        const checkedRadio = form.querySelector('input[name="payment_gateway"]:checked');
        if (manualBox) {
            if (checkedRadio && checkedRadio.value === 'manual_bank') {
                manualBox.classList.remove('hidden');
            } else {
                manualBox.classList.add('hidden');
            }
        }
    }

    gatewayRadios.forEach(radio => {
        radio.addEventListener('change', updateManualBox);
    });
    updateManualBox();

    form.addEventListener('submit', function(e) {
        const selectedGatewayInput = form.querySelector('input[name="payment_gateway"]:checked');
        const gateway = selectedGatewayInput ? selectedGatewayInput.value : 'razorpay';

        // Set Loading State
        payBtn.disabled = true;
        payBtn.classList.add('opacity-75', 'cursor-not-allowed');
        payBtnText.textContent = 'Processing Payment...';
        payBtnIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';

        // For Razorpay, open modal seamlessly via AJAX
        if (gateway === 'razorpay') {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.key) {
                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": data.currency || "INR",
                        "name": "{{ app('currentTenant')->name ?? 'Academy' }}",
                        "description": "Payment for " + (data.order_id || "Order"),
                        "prefill": {
                            "name": data.name || "",
                            "email": data.email || "",
                            "contact": data.phone || ""
                        },
                        "theme": {
                            "color": "{{ $customizes['checkout_primary_color'] ?? '#4f46e5' }}"
                        },
                        "handler": function (response) {
                            payBtnText.textContent = 'Verifying Payment...';
                            document.getElementById('rzp_order_number').value = data.order_id || '';
                            document.getElementById('rzp_payment_id').value = response.razorpay_payment_id || '';
                            document.getElementById('rzp_order_id').value = response.razorpay_order_id || '';
                            document.getElementById('rzp_signature').value = response.razorpay_signature || '';
                            document.getElementById('direct_razorpay_verify_form').submit();
                        },
                        "modal": {
                            "ondismiss": function() {
                                payBtn.disabled = false;
                                payBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                                payBtnText.textContent = 'Pay ₹{{ number_format($itemPrice, 2) }} & Get Access';
                                payBtnIcon.className = 'fa-solid fa-arrow-right text-xs';
                            }
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.on('payment.failed', function(resp) {
                        alert('Payment failed: ' + (resp.error.description || 'Unknown error'));
                        payBtn.disabled = false;
                        payBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        payBtnText.textContent = 'Pay ₹{{ number_format($itemPrice, 2) }} & Get Access';
                        payBtnIcon.className = 'fa-solid fa-arrow-right text-xs';
                    });
                    rzp.open();
                } else if (data && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Fallback to normal form submit if no key
                    form.submit();
                }
            })
            .catch(err => {
                console.error('Checkout error:', err);
                // Fallback to standard form submit
                form.submit();
            });
        }
    });
});
</script>
@endsection
