@extends('layouts.website')

@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- 📦 CHECKOUT FORM --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user text-indigo-600"></i> Contact Information
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="customer_name" required value="{{ auth()->user()?->name }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="customer_email" required value="{{ auth()->user()?->email }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="customer_phone" placeholder="+91 9876543210" 
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    </div>

                    {{-- 🚚 SHIPPING ADDRESS (Shown for physical delivery items) --}}
                    @if(!$variant || $variant->fulfillment_type === 'physical_shipping')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-md font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-indigo-600"></i> Shipping Address
                            </h3>
                            <div class="space-y-3">
                                <input type="text" name="shipping_address[street]" placeholder="House No, Street, Landmark" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <input type="text" name="shipping_address[city]" placeholder="City" class="px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
                                    <input type="text" name="shipping_address[state]" placeholder="State" class="px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
                                    <input type="text" name="shipping_address[pincode]" placeholder="Pincode" class="px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 💳 PAYMENT GATEWAYS --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-md font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-indigo-600"></i> Select Payment Method
                        </h3>

                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-indigo-500 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="razorpay" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="font-bold text-gray-900 block">Online Payment (Razorpay / UPI / Cards / NetBanking)</span>
                                        <span class="text-xs text-gray-500">Instant activation & secure checkout</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-shield-halved text-green-600"></i>
                            </label>

                            <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-indigo-500 transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="cod" class="text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="font-bold text-gray-900 block">Cash on Delivery (COD)</span>
                                        <span class="text-xs text-gray-500">Pay when physical item is delivered</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-hand-holding-dollar text-indigo-600"></i>
                            </label>

                            <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-indigo-500 transition-all" onclick="document.getElementById('manual-payment-box').classList.remove('hidden')">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_gateway" value="manual_bank" class="text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="font-bold text-gray-900 block">Manual Bank Transfer / QR Code / UPI</span>
                                        <span class="text-xs text-gray-500">Pay via Bank/QR & upload payment receipt screenshot</span>
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

                    <button type="submit" class="mt-6 w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-center">
                        Complete Order
                    </button>
                </form>
            </div>
        </div>

        {{-- 🛒 ORDER SUMMARY --}}
        <div class="lg:col-span-5">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>

                @if($item)
                    <div class="flex gap-4 pb-4 border-b border-gray-100">
                        @if($item->cover_image || $item->thumbnail)
                            <img src="{{ media($item->cover_image ?? $item->thumbnail) }}" class="w-16 h-20 object-cover rounded-lg border shadow-sm">
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 text-sm">{{ $item->title ?? $item->name }}</h4>
                            @if($variant)
                                <span class="inline-block px-2 py-0.5 mt-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded">
                                    {{ $variant->title }}
                                </span>
                            @endif
                            <div class="mt-2 text-sm font-bold text-indigo-600">
                                ₹{{ number_format($variant?->price ?? $item->sale_price ?? $item->price, 2) }}
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Your cart items will appear here.</p>
                @endif

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($variant?->price ?? $item->sale_price ?? $item->price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span class="text-green-600 font-semibold">FREE</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t">
                        <span>Total Payable</span>
                        <span class="text-indigo-600">₹{{ number_format($variant?->price ?? $item->sale_price ?? $item->price ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
