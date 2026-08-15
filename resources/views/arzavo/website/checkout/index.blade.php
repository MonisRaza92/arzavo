@extends('layouts.app')

@section('title', 'Checkout • ' . ($plan->name ?? 'Plan') . ' - ' . config('app.name'))

@section('content')

@php
    $user = Auth::guard('web')->user();
    $isFreePlan = ($plan->monthly_price ?? 0) == 0;
    $monthlyPrice = (float) ($plan->monthly_price ?? 0);
    $yearlyPrice = (float) ($plan->yearly_price ?? ($monthlyPrice * 12 * 0.8));
@endphp

<div class="min-h-screen bg-gray-50/50 pb-20">

    {{-- Top Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('pricing') }}" class="flex items-center gap-2 text-xs font-semibold text-dark/60 hover:text-dark transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to Plans</span>
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="{{ config('app.name') }}" class="h-6 w-auto">
                </a>
            </div>

            <div class="flex items-center gap-2 text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200/60">
                <i class="fa-solid fa-shield-halved text-emerald-600 text-xs"></i>
                <span>256-bit Secure Checkout</span>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 pt-8">

        {{-- Page Heading --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-dark tracking-tight">Complete your subscription</h1>
            <p class="text-sm text-dark/60 mt-1">Assign tier to your institution and get instant access.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ══════════ LEFT COLUMN: STEPS (7 Cols) ══════════ --}}
            <div class="lg:col-span-7 space-y-6">

                <form id="checkoutForm" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="billing_cycle" id="billingCycleInput" value="monthly">
                    <input type="hidden" name="tenant_id" id="selectedTenantId" value="{{ old('tenant_id') }}">

                    {{-- ── STEP 1: Select Institution (Tenant) ── --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-dark text-white text-xs font-bold flex items-center justify-center">1</span>
                                <h2 class="text-sm font-bold text-dark">Select Institution / Tenant</h2>
                            </div>
                            <a href="{{ route('tenants.create') }}" target="_blank" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> Create New
                            </a>
                        </div>

                        <div class="p-6">
                            @if($tenants->isEmpty())
                                <div class="text-center py-8 px-4 border border-dashed border-gray-200 rounded-lg bg-gray-50/50">
                                    <i class="fa-solid fa-school text-3xl text-gray-400 mb-2 block"></i>
                                    <p class="text-sm font-semibold text-dark">No institutions found</p>
                                    <p class="text-xs text-dark/50 mt-1 mb-4 max-w-xs mx-auto">Create your school or coaching institute before subscribing to this plan.</p>
                                    <a href="{{ route('tenants.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-dark text-white rounded-md text-xs font-semibold hover:bg-dark/90 transition">
                                        <i class="fa-solid fa-plus"></i> Create Institution
                                    </a>
                                </div>
                            @else
                                {{-- Tenant Search Filter --}}
                                <div class="relative mb-3">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                    <input type="text" id="tenantSearchInput" oninput="filterTenants(this.value)" placeholder="Search institution by name or subdomain..."
                                        class="w-full pl-9 pr-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                                </div>

                                {{-- Tenants List --}}
                                <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1" id="tenantsListContainer">
                                    @foreach($tenants as $t)
                                        @php
                                            $isActiveOnThisPlan = $t->subscription && $t->subscription->plan_id == $plan->id && $t->subscription->status === 'active';
                                        @endphp
                                        <div class="tenant-item border rounded-lg p-3.5 flex items-center justify-between transition cursor-pointer {{ $isActiveOnThisPlan ? 'bg-gray-100 border-gray-200 opacity-60 cursor-not-allowed' : 'hover:border-dark border-gray-200 bg-white' }}"
                                             data-id="{{ $t->id }}"
                                             data-name="{{ $t->name }}"
                                             data-disabled="{{ $isActiveOnThisPlan ? 'true' : 'false' }}"
                                             data-search="{{ strtolower($t->name . ' ' . $t->subdomain . ' ' . $t->url) }}"
                                             @if(!$isActiveOnThisPlan) onclick="selectTenantItem({{ $t->id }}, '{{ addslashes($t->name) }}')" @endif>
                                            
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-dark text-sm shrink-0">
                                                    {{ strtoupper(substr($t->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-dark truncate">{{ $t->name }}</p>
                                                    <p class="text-[11px] text-dark/50 truncate">{{ $t->url ?? ($t->subdomain . '.' . config('app.domain')) }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($isActiveOnThisPlan)
                                                    <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Active Plan</span>
                                                @else
                                                    <div class="tenant-radio w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                        <div class="tenant-radio-dot w-2 h-2 rounded-full bg-dark hidden"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    <div id="noTenantMatch" class="hidden text-center py-6 text-xs text-dark/40">
                                        No institutions match your search.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── STEP 2: Contact Information ── --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-dark text-white text-xs font-bold flex items-center justify-center">2</span>
                            <h2 class="text-sm font-bold text-dark">Contact Information</h2>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="contact_first_name" required value="{{ old('first_name', $user->fname ?? '') }}" placeholder="Enter first name"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" id="contact_last_name" required value="{{ old('last_name', $user->lname ?? '') }}" placeholder="Enter last name"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="contact_email" required value="{{ old('email', $user->email ?? '') }}" placeholder="user@example.com"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" id="contact_phone" required value="{{ old('phone', $user->number ?? '') }}" placeholder="e.g. 9876543210"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>
                        </div>
                    </div>

                    {{-- ── STEP 3: Billing Address (Optional) ── --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                        <div class="px-6 py-4 flex items-center justify-between cursor-pointer" onclick="toggleBillingAddress()">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-gray-200 text-dark/60 text-xs font-bold flex items-center justify-center">3</span>
                                <div>
                                    <h2 class="text-sm font-bold text-dark flex items-center gap-2">
                                        Billing Address & GSTIN
                                        <span class="text-[10px] bg-gray-100 text-dark/60 font-semibold px-2 py-0.5 rounded-full">Optional</span>
                                    </h2>
                                    <p class="text-xs text-dark/40">Add details if you require a formal tax invoice</p>
                                </div>
                            </div>
                            <span id="billingAddressToggleIcon" class="text-xs font-bold text-dark/50">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>

                        <div id="billingAddressFields" class="hidden px-6 pb-6 pt-2 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-dark/70 block mb-1">Street Address</label>
                                <input type="text" name="address_line1" placeholder="Flat, building, street..."
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">City</label>
                                <input type="text" name="city" placeholder="City"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">State / Province</label>
                                <input type="text" name="state" placeholder="State"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">PIN / Postal Code</label>
                                <input type="text" name="pincode" placeholder="Postal Code"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-dark/70 block mb-1">GSTIN Number</label>
                                <input type="text" name="gstin" placeholder="GSTIN (optional)"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-dark transition">
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            {{-- ══════════ RIGHT COLUMN: ORDER SUMMARY (5 Cols) ══════════ --}}
            <div class="lg:col-span-5 lg:sticky lg:top-24 space-y-4">

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Card Header --}}
                    <div class="p-6 bg-dark text-white">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-white/70">Order Summary</span>
                            @if($plan->is_popular)
                                <span class="text-[10px] bg-accent px-2 py-0.5 rounded font-bold uppercase tracking-widest text-white">★ Popular</span>
                            @elseif($plan->is_limited_time)
                                <span class="text-[10px] bg-orange-600 px-2 py-0.5 rounded font-bold uppercase tracking-widest text-white animate-pulse">🔥 Limited Offer</span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                        <p class="text-xs text-white/70 mt-1 leading-relaxed">{{ $plan->short_description ?? 'Full tier access for your academy.' }}</p>
                    </div>

                    {{-- Billing Cycle Selector (if paid) --}}
                    @if(!$isFreePlan && $plan->yearly_price)
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-dark/50 block mb-2">Billing Frequency</label>
                            <div class="grid grid-cols-2 gap-2 bg-gray-200/70 p-1 rounded-lg">
                                <button type="button" id="btnCycleMonthly" onclick="changeBillingCycle('monthly')"
                                    class="py-2 text-xs font-bold rounded-md bg-white text-dark shadow-xs transition">
                                    Monthly
                                </button>
                                <button type="button" id="btnCycleYearly" onclick="changeBillingCycle('yearly')"
                                    class="py-2 text-xs font-medium rounded-md text-dark/60 transition flex items-center justify-center gap-1">
                                    <span>Annual</span>
                                    <span class="text-[10px] font-extrabold bg-emerald-600 text-white px-1.5 py-0.2 rounded">-20%</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Price Calculation --}}
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-dark/60">Plan Base Rate</span>
                            <span id="summaryBasePrice" class="font-bold text-dark">
                                {{ $isFreePlan ? 'Free' : '₹' . number_format($monthlyPrice, 2) }}
                            </span>
                        </div>

                        @if(!$isFreePlan)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-dark/60">Applicable GST (18%)</span>
                                <span id="summaryTaxPrice" class="font-bold text-dark">
                                    ₹{{ number_format($monthlyPrice * 0.18, 2) }}
                                </span>
                            </div>

                            <div class="pt-3 border-t border-gray-100 flex justify-between items-baseline">
                                <div>
                                    <span class="text-sm font-bold text-dark block">Total Amount Due</span>
                                    <span id="summaryCycleLabel" class="text-[11px] text-dark/40 font-normal">Billed monthly</span>
                                </div>
                                <span id="summaryTotalPrice" class="text-2xl font-extrabold text-dark">
                                    ₹{{ number_format($monthlyPrice * 1.18, 2) }}
                                </span>
                            </div>
                        @else
                            <div class="pt-3 border-t border-gray-100 flex justify-between items-baseline">
                                <span class="text-sm font-bold text-dark">Total Due</span>
                                <span class="text-2xl font-extrabold text-emerald-600">Free</span>
                            </div>
                        @endif

                        {{-- Selected Tenant Indicator --}}
                        <div id="selectedTenantNotice" class="hidden p-3 rounded-lg bg-blue-50 border border-blue-100 text-xs text-blue-900 items-center gap-2 mt-3">
                            <i class="fa-solid fa-circle-check text-blue-600"></i>
                            <span>Selected: <strong id="selectedTenantNoticeName">None</strong></span>
                        </div>
                    </div>

                    {{-- Included Features Brief --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 space-y-2">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-dark/40">Tier Highlights</p>
                        <ul class="space-y-1.5 text-xs text-dark/70">
                            @php $shownCount = 0; @endphp
                            @foreach(config('plan.features') as $key => $label)
                                @if(($plan->features[$key] ?? false) && $shownCount < 4)
                                    <li class="flex items-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-600 text-[10px]"></i>
                                        <span>{{ $label }}</span>
                                    </li>
                                    @php $shownCount++; @endphp
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA Action Button --}}
                    <div class="p-6 pt-4">
                        <button type="button" id="btnSubmitCheckout" onclick="processCheckout()"
                            class="w-full py-3.5 px-4 bg-dark text-white rounded-lg font-bold text-sm hover:bg-dark/90 transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                            <span id="btnCheckoutText">{{ $isFreePlan ? 'Activate Free Plan' : 'Proceed to Payment' }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <div class="mt-4 grid grid-cols-3 gap-2 text-center text-[11px] text-dark/50">
                            <div class="flex flex-col items-center gap-1">
                                <i class="fa-solid fa-bolt text-dark/40"></i>
                                <span>Instant Access</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <i class="fa-solid fa-arrow-rotate-left text-dark/40"></i>
                                <span>Cancel Anytime</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <i class="fa-solid fa-headset text-dark/40"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<script>
const planData = {
    isFree: {{ $isFreePlan ? 'true' : 'false' }},
    monthly: {{ $monthlyPrice }},
    yearly: {{ $yearlyPrice }},
};

let currentCycle = 'monthly';

function changeBillingCycle(cycle) {
    currentCycle = cycle;
    document.getElementById('billingCycleInput').value = cycle;

    const btnMonthly = document.getElementById('btnCycleMonthly');
    const btnYearly = document.getElementById('btnCycleYearly');

    if (cycle === 'yearly') {
        btnYearly.className = "py-2 text-xs font-bold rounded-md bg-white text-dark shadow-xs transition flex items-center justify-center gap-1";
        btnMonthly.className = "py-2 text-xs font-medium rounded-md text-dark/60 transition";
    } else {
        btnMonthly.className = "py-2 text-xs font-bold rounded-md bg-white text-dark shadow-xs transition";
        btnYearly.className = "py-2 text-xs font-medium rounded-md text-dark/60 transition flex items-center justify-center gap-1";
    }

    updateSummaryPricing();
}

function updateSummaryPricing() {
    if (planData.isFree) return;

    const base = currentCycle === 'yearly' ? planData.yearly : planData.monthly;
    const tax = Math.round(base * 0.18 * 100) / 100;
    const total = Math.round((base + tax) * 100) / 100;

    const fmt = n => '₹' + new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);

    document.getElementById('summaryBasePrice').textContent = fmt(base);
    document.getElementById('summaryTaxPrice').textContent = fmt(tax);
    document.getElementById('summaryTotalPrice').textContent = fmt(total);
    document.getElementById('summaryCycleLabel').textContent = currentCycle === 'yearly' ? 'Billed annually' : 'Billed monthly';
}

function selectTenantItem(id, name) {
    document.getElementById('selectedTenantId').value = id;

    document.querySelectorAll('.tenant-item').forEach(el => {
        el.classList.remove('border-dark', 'bg-blue-50/50', 'ring-1', 'ring-dark');
        const dot = el.querySelector('.tenant-radio-dot');
        if (dot) dot.classList.add('hidden');
    });

    const activeEl = document.querySelector(`.tenant-item[data-id="${id}"]`);
    if (activeEl) {
        activeEl.classList.add('border-dark', 'bg-blue-50/50', 'ring-1', 'ring-dark');
        const dot = activeEl.querySelector('.tenant-radio-dot');
        if (dot) dot.classList.remove('hidden');
    }

    const notice = document.getElementById('selectedTenantNotice');
    notice.classList.remove('hidden');
    notice.classList.add('flex');
    document.getElementById('selectedTenantNoticeName').textContent = name;
}

function filterTenants(query) {
    const q = query.toLowerCase().trim();
    let matches = 0;

    document.querySelectorAll('.tenant-item').forEach(el => {
        const search = el.getAttribute('data-search') || '';
        const visible = !q || search.includes(q);
        el.style.display = visible ? 'flex' : 'none';
        if (visible) matches++;
    });

    const empty = document.getElementById('noTenantMatch');
    if (empty) empty.classList.toggle('hidden', matches > 0);
}

function toggleBillingAddress() {
    const fields = document.getElementById('billingAddressFields');
    const icon = document.getElementById('billingAddressToggleIcon');
    const isHidden = fields.classList.contains('hidden');

    if (isHidden) {
        fields.classList.remove('hidden');
        icon.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
    } else {
        fields.classList.add('hidden');
        icon.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
    }
}

async function processCheckout() {
    const tenantId = document.getElementById('selectedTenantId').value;
    const firstName = document.getElementById('contact_first_name').value.trim();
    const lastName = document.getElementById('contact_last_name').value.trim();
    const email = document.getElementById('contact_email').value.trim();
    const phone = document.getElementById('contact_phone').value.trim();

    if (!tenantId) {
        alert("Please select an institution / tenant to apply this plan.");
        document.getElementById('tenantsListContainer')?.scrollIntoView({ behavior: 'smooth' });
        return;
    }

    if (!firstName || !lastName || !email || !phone) {
        alert("Please provide all required contact details (First Name, Last Name, Email, and Phone).");
        return;
    }

    const btn = document.getElementById('btnSubmitCheckout');
    const btnText = document.getElementById('btnCheckoutText');
    btn.disabled = true;
    btnText.textContent = "Processing...";

    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);

    try {
        if (planData.isFree) {
            // Free plan direct subscription
            const res = await fetch("{{ route('subscribe', $plan->slug) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            });

            if (res.ok) {
                alert("Free plan activated successfully!");
                window.location.href = "{{ route('dashboard') }}";
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data.message || "Failed to activate plan.");
                btn.disabled = false;
                btnText.textContent = "Activate Free Plan";
            }
            return;
        }

        // Paid Plan PayU Checkout
        const res = await fetch("{{ route('payment.payu.init', $plan->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        });

        const data = await res.json();

        if (!res.ok || !data.success || !data.action || !data.params) {
            throw new Error(data.error || data.message || "PayU payment initialization failed.");
        }

        // Dynamically create & submit PayU form
        const payuForm = document.createElement('form');
        payuForm.method = 'POST';
        payuForm.action = data.action;

        for (const [key, value] of Object.entries(data.params)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = value;
            payuForm.appendChild(hiddenField);
        }

        document.body.appendChild(payuForm);
        btnText.textContent = "Redirecting to PayU...";
        payuForm.submit();

    } catch (err) {
        console.error(err);
        alert(err.message || "An error occurred during checkout.");
        btn.disabled = false;
        btnText.textContent = planData.isFree ? "Activate Free Plan" : "Proceed to Payment";
    }
}

// Auto-select single tenant if only 1 exists
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.tenant-item:not([data-disabled="true"])');
    if (items.length === 1) {
        const first = items[0];
        selectTenantItem(first.dataset.id, first.dataset.name);
    }
});
</script>

@endsection