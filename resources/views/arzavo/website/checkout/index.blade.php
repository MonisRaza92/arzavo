@extends('layouts.app')

@section('title', 'Plan Checkout - Arzavo Educational Management Platform')

@section('content')

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.4s ease both;
        }

        .fade-up-1 {
            animation: fadeUp 0.4s ease 0.05s both;
        }

        .fade-up-2 {
            animation: fadeUp 0.4s ease 0.12s both;
        }

        /* Tenant list scrollbar */
        .tenant-list {
            max-height: 264px;
            overflow-y: auto;
        }

        .tenant-list::-webkit-scrollbar {
            width: 4px;
        }

        .tenant-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .tenant-list::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        /* Selected tenant */
        .tenant-item.selected {
            background: #eff6ff !important;
            border-color: #93c5fd !important;
        }

        .tenant-item.selected .t-radio {
            border-color: #3b82f6;
            background: #3b82f6;
        }

        .tenant-item.selected .t-radio-dot {
            display: block;
        }
    </style>


    <div class="min-h-screen bg-secondary">

        {{-- Top Nav --}}
        <nav class="bg-primary border-bottom fade-up">
            <div class="max-w-6xl mx-auto px-5 h-14 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="w-8 h-8 flex items-center justify-center border-rounded border border-slate-200 text-slate-400 hover:text-slate-600 hover:border-slate-300 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center gap-1.5 text-sm text-slate-400">
                        <span>Plans</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="font-semibold text-slate-700">Checkout</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Secured checkout
                </div>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto px-4 sm:px-5 py-8">

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_352px] gap-6 items-start">

                {{-- ══════════ LEFT ══════════ --}}
                <div class="flex flex-col gap-5 fade-up-1">

                    <form method="POST" action="{{ route('checkout.process', ['slug' => $plan->slug]) }}" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="billing_cycle" id="billingCycleInput" value="monthly">
                        <input type="hidden" name="tenant_id" id="selectedTenantId" value="{{ old('tenant_id') }}">

                        {{-- ── STEP 1: Tenant ── --}}
                        <div class="bg-white border-rounded border overflow-hidden mb-5">

                            {{-- Header --}}
                            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                                <span
                                    class="w-8 h-8 border-rounded bg-invert flex items-center justify-center text-white text-xl font-bold shrink-0">1</span>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Select Tenant</p>
                                    <p class="text-xs text-slate-400">Choose the account this plan is for</p>
                                </div>
                            </div>

                            {{-- Search --}}
                            <div class="px-5 pt-4 pb-3">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" id="tenantSearch" placeholder="Search by name or ID…"
                                        oninput="filterTenants(this.value)"
                                        class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            {{-- List --}}
                            <div class="tenant-list px-5 pb-5 flex flex-col gap-2" id="tenantList">
                                @foreach($tenants as $tenant)
                                                            @php
                                                                $isCurrentPlan = $tenant->subscription
                                                                    && $tenant->subscription->plan_id == $plan->id
                                                                    && $tenant->subscription->status === 'active';
                                                            @endphp
                                                            <div class="tenant-item group flex items-center gap-3 px-4 py-3 border-rounded border transition-all duration-150
                                    {{ $isCurrentPlan ? 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-60' : 'cursor-pointer hover:border-slate-300 hover:bg-slate-50' }}
                                    {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}" data-id="{{ $tenant->id }}"
                                                                data-name="{{ $tenant->name }}" data-disabled="{{ $isCurrentPlan ? 'true' : 'false' }}"
                                                                data-search="{{ strtolower($tenant->name) }} {{ $tenant->id }}" @if(!$isCurrentPlan)
                                                                onclick="selectTenant({{ $tenant->id }}, '{{ addslashes($tenant->name) }}')" @endif>
                                                                {{-- Avatar --}}
                                                                <div
                                                                    class="w-10 h-10 border-rounded bg-invert border border-blue-100! flex items-center justify-center shrink-0 text-invert font-bold text-xl select-none">
                                                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                                                </div>

                                                                {{-- Text --}}
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $tenant->name }}</p>
                                                                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                                                        {{ str_replace('https://', '', $tenant->url) }}
                                                                    </p>
                                                                </div>
                                                                @if($isCurrentPlan)
                                                                    <p class="text-xs text-red-400 mt-1 font-medium">
                                                                        Already on this plan
                                                                    </p>
                                                                @endif

                                                                {{-- Radio --}}
                                                                <div
                                                                    class="t-radio w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                                                                    <div class="t-radio-dot hidden w-2 h-2 rounded-full bg-white"></div>
                                                                </div>
                                                            </div>
                                @endforeach

                                {{-- Empty --}}
                                <div id="tenantEmpty" class="hidden py-10 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm text-slate-400">No tenants found</p>
                                </div>
                            </div>

                            @error('tenant_id')
                                <div class="px-5 pb-4">
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </div>

                        {{-- ── STEP 2: Contact Details ── --}}
                        <div class="bg-white border-rounded border border-slate-200 overflow-hidden mb-5">
                            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                                <span
                                    class="w-8 h-8 border-rounded bg-invert flex items-center justify-center text-white text-xl font-bold shrink-0">2</span>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Contact Details</p>
                                    <p class="text-xs text-slate-400">Primary contact for this subscription</p>
                                </div>
                            </div>

                            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">First Name <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="John"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300 @error('first_name') border-red-300 @enderror">
                                    @error('first_name')<span
                                    class="text-xs text-red-500 mt-0.5">{{ $message }}</span>@enderror
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">Last Name <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Doe"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300 @error('last_name') border-red-300 @enderror">
                                    @error('last_name')<span
                                    class="text-xs text-red-500 mt-0.5">{{ $message }}</span>@enderror
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">Email Address <span
                                            class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            placeholder="john@company.com"
                                            class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300 @error('email') border-red-300 @enderror">
                                    </div>
                                    @error('email')<span class="text-xs text-red-500 mt-0.5">{{ $message }}</span>@enderror
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">Phone <span
                                            class="text-slate-300 font-normal">(optional)</span></label>
                                    <div class="relative">
                                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            placeholder="+91 98765 43210"
                                            class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1.5 sm:col-span-2">
                                    <label class="text-xs font-semibold text-slate-600">Company <span
                                            class="text-slate-300 font-normal">(optional)</span></label>
                                    <div class="relative">
                                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 8h2" />
                                        </svg>
                                        <input type="text" name="company" value="{{ old('company') }}"
                                            placeholder="Acme Corp"
                                            class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ── STEP 3: Billing Address ── --}}
                        <div class="bg-white border-rounded border border-slate-200 overflow-hidden">
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-[11px] font-bold shrink-0">3</span>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                            Billing Address
                                            <span
                                                class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full uppercase tracking-wide">Optional</span>
                                        </p>
                                        <p class="text-xs text-slate-400">Required for GST invoice</p>
                                    </div>
                                </div>
                                <button type="button" id="billingToggleBtn" onclick="toggleBilling()"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                    + Add Address
                                </button>
                            </div>

                            <div id="billingSection"
                                class="hidden border-t border-slate-100 px-6 pb-6 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div class="flex flex-col gap-1.5 sm:col-span-2">
                                    <label class="text-xs font-semibold text-slate-600">Address Line 1</label>
                                    <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                                        placeholder="Street, flat no."
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                                <div class="flex flex-col gap-1.5 sm:col-span-2">
                                    <label class="text-xs font-semibold text-slate-600">Address Line 2 <span
                                            class="text-slate-300 font-normal">(optional)</span></label>
                                    <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                                        placeholder="Area, landmark"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">City</label>
                                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">State</label>
                                    <input type="text" name="state" value="{{ old('state') }}" placeholder="Maharashtra"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">Pincode</label>
                                    <input type="text" name="pincode" value="{{ old('pincode') }}" placeholder="400001"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-semibold text-slate-600">GSTIN <span
                                            class="text-slate-300 font-normal">(optional)</span></label>
                                    <input type="text" name="gstin" value="{{ old('gstin') }}" placeholder="22AAAAA0000A1Z5"
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 border-rounded outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                {{-- ══════════ RIGHT: PRICE OVERVIEW ══════════ --}}
                <div class="lg:sticky lg:top-6 flex flex-col gap-4 fade-up-2">

                    <div class="bg-white border-rounded border border-slate-200 overflow-hidden">

                        {{-- Plan Header --}}
                        <div class="bg-invert px-6 pt-6 pb-5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 text-white text-[10px] font-bold uppercase tracking-wider mb-3">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 3l14 9-14 9V3z" />
                                </svg>
                                {{ $plan->name ?? 'Pro' }} Plan
                            </span>
                            <h3 class="text-lg font-bold text-white">{{ $plan->name }}</h3>
                            <p class="text-blue-100 text-xs mt-1 leading-relaxed">
                                {{ $plan->description ?? 'Everything you need to scale.' }}
                            </p>
                        </div>

                        {{-- Billing Toggle --}}
                        <div class="px-5 pt-5">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Billing Cycle</p>
                            <div class="flex gap-1 p-1 bg-slate-100 border-rounded">
                                <button type="button" id="btn-monthly" onclick="setCycle('monthly')"
                                    class="flex-1 py-2 text-sm font-semibold bg-white text-slate-800 border-rounded shadow-sm transition-all duration-200">
                                    Monthly
                                </button>
                                <button type="button" id="btn-yearly" onclick="setCycle('yearly')"
                                    class="flex-1 py-2 text-sm font-medium text-slate-500 border-rounded transition-all duration-200 flex items-center justify-center gap-1.5">
                                    Yearly
                                    <span
                                        class="text-[10px] font-bold bg-amber-400 text-amber-900 rounded px-1.5 py-0.5">-20%</span>
                                </button>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="px-5 py-4 flex items-end gap-1">
                            <span class="text-base font-semibold text-slate-400 mb-1">₹</span>
                            <span id="displayPrice"
                                class="text-4xl font-extrabold text-slate-800 leading-none tracking-tight">
                                {{ number_format($plan->monthly_price ?? $plan->price, 0) }}
                            </span>
                            <span id="displayPeriod" class="text-sm text-slate-400 mb-1">/ mo</span>
                        </div>

                        <div class="mx-5 h-px bg-slate-100"></div>


                        <div class="mx-5 h-px bg-slate-100"></div>

                        {{-- Breakdown --}}
                        <div class="px-5 py-4 flex flex-col gap-0.5">
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-sm text-slate-500">Subtotal</span>
                                <span id="subtotalDisplay" class="text-sm font-medium text-slate-700">
                                    ₹{{ number_format($plan->monthly_price ?? $plan->price, 0) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-sm text-slate-500">GST (18%)</span>
                                <span id="taxDisplay" class="text-sm font-medium text-slate-700">
                                    ₹{{ number_format(($plan->monthly_price ?? $plan->price) * 0.18, 0) }}
                                </span>
                            </div>
                            <div class="h-px bg-slate-100 my-2"></div>
                            <div class="flex justify-between items-center py-1">
                                <span class="text-sm font-bold text-slate-800">Total Due</span>
                                <span id="totalDisplay" class="text-xl font-extrabold text-slate-800">
                                    ₹{{ number_format(($plan->monthly_price ?? $plan->price) * 1.18, 0) }}
                                </span>
                            </div>
                        </div>

                        {{-- Selected Tenant Preview --}}
                        <div id="tenantConfirm"
                            class="hidden mx-5 mb-4 px-4 py-3 border-rounded bg-blue-50 border border-blue-100 flex items-center gap-3">
                            <div id="tenantConfirmAvatar"
                                class="w-8 h-8 border-rounded bg-blue-200 flex items-center justify-center text-blue-700 text-xs font-bold shrink-0">
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-blue-500 uppercase tracking-wider">Subscribing for
                                </p>
                                <p id="tenantConfirmName" class="text-sm font-semibold text-slate-700 truncate"></p>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="px-5 pb-5">
                            @php
                                $isFreePlan = ($plan->monthly_price ?? $plan->price) == 0;
                            @endphp

                            <button type="button" id="pay-btn" disabled data-free="{{ $isFreePlan ? 'true' : 'false' }}"
                                class="w-full flex items-center justify-center gap-2 py-3.5 border-rounded
        bg-zinc-400 cursor-not-allowed text-white text-sm font-bold transition-all">

                                {{ $isFreePlan ? 'Activate Free Plan' : 'Confirm & Pay' }}
                            </button>

                            {{-- Trust --}}
                            <div class="flex items-center justify-center gap-4 mt-4 flex-wrap">
                                <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    SSL Secure
                                </span>
                                <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Cancel Anytime
                                </span>
                                <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Instant Access
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <script>
        const cashfree = Cashfree({
            mode: "{{ config('services.cashfree.env') === 'production' ? 'production' : 'sandbox' }}"
        });

        document.getElementById("pay-btn").addEventListener("click", async function () {

    if (!validateCheckout()) {
        alert("Please fill all required details");
        return;
    }

    const btn = this;
    const isFree = btn.dataset.free === "true";

    btn.disabled = true;
    btn.innerText = isFree ? "Activating..." : "Processing...";

    try {

        const form = document.getElementById("checkoutForm");
        const formData = new FormData(form);

        // 🔥 FREE PLAN FLOW
        if (isFree) {

            const res = await fetch("{{ route('subscribe', $plan->slug) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            });

            const data = await res.text();

            // simple reload / redirect
            window.location.reload();

            return;
        }

        // 🔥 PAID FLOW (existing)
        const res = await fetch("{{ route('payment.session', $plan->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: formData
        });

        const data = await res.json();

        if (!data.payment_session_id) {
            throw new Error("Session error");
        }

        await cashfree.checkout({
            paymentSessionId: data.payment_session_id,
            redirectTarget: "_self"
        });

    } catch (err) {
        console.error(err);
        alert("Something went wrong");

        btn.disabled = false;
        btn.innerText = isFree ? "Activate Free Plan" : "Confirm & Pay";
    }
});
    </script>
    <script>

        function validateCheckout() {
            const tenantId = document.getElementById('selectedTenantId').value;
            const firstName = document.querySelector('[name="first_name"]').value.trim();
            const lastName = document.querySelector('[name="last_name"]').value.trim();
            const email = document.querySelector('[name="email"]').value.trim();

            return tenantId && firstName && lastName && email;
        }

        function togglePayButton() {
            const btn = document.getElementById('pay-btn');

            if (validateCheckout()) {
                btn.disabled = false;
                btn.classList.remove('bg-zinc-400', 'cursor-not-allowed');
                btn.classList.add('bg-zinc-950', 'hover:bg-zinc-800');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-zinc-400', 'cursor-not-allowed');
                btn.classList.remove('bg-zinc-950', 'hover:bg-zinc-800');
            }
        }
        document.addEventListener('turbo:load', () => {

            document.querySelectorAll('#checkoutForm input').forEach(input => {
                input.addEventListener('input', togglePayButton);
            });

            togglePayButton(); // initial check
        });
        // ─── Pricing ───────────────────────────────────
        const planPricing = {
            monthly: {{ $plan->monthly_price ?? $plan->price ?? 0 }},
            yearly:  {{ $plan->yearly_price ?? (($plan->monthly_price ?? $plan->price ?? 0) * 12 * 0.8) }},
        };
        let currentCycle = 'monthly';

        function setCycle(cycle) {
            currentCycle = cycle;
            document.getElementById('billingCycleInput').value = cycle;

            const active = 'flex-1 py-2 text-sm font-semibold bg-white text-slate-800 border-rounded shadow-sm transition-all duration-200';
            const inactive = 'flex-1 py-2 text-sm font-medium text-slate-500 border-rounded transition-all duration-200 flex items-center justify-center gap-1.5';

            document.getElementById('btn-monthly').className = cycle === 'monthly' ? active : inactive;
            document.getElementById('btn-yearly').className = cycle === 'yearly' ? active : inactive;

            // Re-add badge to yearly if it becomes inactive
            if (cycle === 'monthly') {
                const btn = document.getElementById('btn-yearly');
                if (!btn.querySelector('span')) {
                    btn.innerHTML = 'Yearly <span class="text-[10px] font-bold bg-amber-400 text-amber-900 rounded px-1.5 py-0.5">-20%</span>';
                }
            }

            updatePriceDisplay();
        }

        function updatePriceDisplay() {
            const base = planPricing[currentCycle];
            const tax = Math.round(base * 0.18);
            const total = Math.round(base + tax);
            const fmt = n => new Intl.NumberFormat('en-IN').format(n);

            document.getElementById('displayPrice').textContent = fmt(base);
            document.getElementById('displayPeriod').textContent = currentCycle === 'monthly' ? '/ mo' : '/ yr';
            document.getElementById('subtotalDisplay').textContent = '₹' + fmt(base);
            document.getElementById('taxDisplay').textContent = '₹' + fmt(tax);
            document.getElementById('totalDisplay').textContent = '₹' + fmt(total);
        }

        // ─── Tenant Selection ──────────────────────────
        function selectTenant(id, name) {
            document.getElementById('selectedTenantId').value = id;

            document.querySelectorAll('.tenant-item').forEach(el => el.classList.remove('selected'));
            document.querySelector(`.tenant-item[data-id="${id}"]`)?.classList.add('selected');

            const initials = name.substring(0, 2).toUpperCase();
            document.getElementById('tenantConfirmAvatar').textContent = initials;
            document.getElementById('tenantConfirmName').textContent = name + ' · #' + id;

            const confirm = document.getElementById('tenantConfirm');
            confirm.classList.remove('hidden');
            confirm.classList.add('flex');
            togglePayButton();
        }

        // ─── Tenant Search ─────────────────────────────
        function filterTenants(q) {
            q = q.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.tenant-item').forEach(el => {
                const show = !q || el.dataset.search.includes(q);
                el.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('tenantEmpty').classList.toggle('hidden', visible > 0);
        }

        // ─── Billing Address Toggle ────────────────────
        function toggleBilling() {
            const sec = document.getElementById('billingSection');
            const btn = document.getElementById('billingToggleBtn');
            const hidden = sec.classList.contains('hidden');
            sec.classList.toggle('hidden', !hidden);
            btn.textContent = hidden ? '− Hide Address' : '+ Add Address';
        }

        // ─── On Load: restore old() value ─────────────
        document.addEventListener('turbo:load', () => {
            const old = document.getElementById('selectedTenantId').value;
            if (old) {
                const item = document.querySelector(`.tenant-item[data-id="${old}"]`);
                if (item) selectTenant(old, item.dataset.name);
            }
        });
    </script>
@endsection