@extends('layouts.app')
@section('title', 'Create Tenants - ' . config('app.name'))

@section('content')
<div class="flex items-start justify-start relative h-dvh w-full">
    <img src="{{ asset('images/banner/tenant-banner.webp') }}" alt="Arzavo Logo" class="absolute top-0 inset-0 left-0 w-full h-dvh object-cover">
    <div class="border-right bg-primary z-10 h-full w-full md:w-96 overflow-auto pb-4 scrollbar">
        <div class="logo-container sticky top-0 bg-primary flex justify-between items-center border-bottom p-4 mb-4">
            <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="logo">
            <a href="#" class="font-bold bg-invert text-invert border-rounded px-4 py-2">Upgrade</a>
        </div>
        <div class="content px-4 w-full">
            <!-- Notification Badge -->
            <div id="tenant-notification" class="w-full border-primary bg-hover-primary cursor-default border-rounded p-2 flex justify-between items-center text-sm transition-all duration-200">
                <div class="text flex gap-2 items-center">
                    <i class="fa-regular fa-check-circle text-2xl text-green-500"></i>
                    <p class="text-xs text-tertiary">You can create only one tenant.<br> Please upgrade your plan to add more tenants.</p>
                </div>
                <button onclick="document.getElementById('tenant-notification').classList.add('hidden')" class="text-tertiary text-lg"> <i class="fa-solid fa-xmark"></i> </button>
            </div>
            <!-- Tenant List -->
            @if($tenants->count() > 0)
            @foreach($tenants as $tenant)
            <div class="tenant-item border-primary border-rounded mt-4 w-full">

                <!-- Header -->
                <div class="flex justify-between items-center p-3 border-bottom">
                    <h2 class="text-lg font-bold text--primary flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-base"></i>
                        {{ $tenant->name }}
                    </h2>

                    <!-- Status Toggle Button -->
                    <button onclick="toggleTenantStatus({{ $tenant->id }})"
                        class="text-[10px] font-semibold px-2 py-1 rounded-full
                        {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </div>

                <!-- Subdomain -->
                <div class="flex items-center gap-2 text-sm p-3 border-bottom">
                    <i class="fa-solid fa-globe text-tertiary"></i>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Subdomain:</strong><br>
                        <a target="_blank"
                            href="https://{{ $tenant->subdomain }}.{{ config('app.domain') }}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->subdomain }}.{{ config('app.domain') }}
                        </a>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div class="flex items-center gap-2 text-sm p-3 border-bottom">
                    <i class="fa-solid fa-link text-tertiary"></i>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Custom Domain:</strong><br>

                        @if($tenant->domain)
                        <a target="_blank"
                            href="https://{{ $tenant->domain }}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->domain }}
                        </a>
                        <span class="text-[10px] text-green-600 font-semibold ml-1">✔ Verified</span>
                        @else
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-red-600 font-medium">Not Connected</span>
                            <div class="text-xs text-gray-500">
                                <a href="#" class="text-indigo-600 hover:underline">
                                    <i class="fa-solid fa-link"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center p-1">
                    <div class="flex">

                        <a href="#" class="text-sm text-tertiary p-2 pr-3 border-right"><i class="fa-solid fa-pen-to-square"></i></a>

                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm py-2 px-3 text-tertiary border-right" onclick="return confirm('Are you sure you want to delete this tenant?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>

                    <!-- Visit Dashboard -->
                    <a href="{{ route('admin.dashboard', ['tenant' => $tenant->subdomain]) }}"
                        class="text-sm  text-indigo-600 hover:underline p-2 border-left font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        View Dashboard
                    </a>
                </div>

            </div>
            @endforeach
            @else
            <p class="text-gray-500 text-sm mt-3">No tenants found.</p>
            @endif
            <!-- Tenant Creation Form -->
            @if(Auth::user()->tenants()->count() < 1)
                <div class="tenant-form border-rounded p-3 mt-4 border-primary w-full">
                <h2 class="text-xl font-bold text-primary mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building-columns text-base"></i> Create New Tenant
                </h2>

                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf
                    <!-- Tenant Name -->
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-secondary mb-1">Tenant Name</label>
                        <input type="text" name="name" required
                            value="{{ old('name') }}"
                            class="w-full border-primary border-rounded p-2 text-tertiary"
                            placeholder="e.g. Arzavo Academy, Arzaq School">
                        <p class="text-[11px] text-tertiary mt-1">This is your organization’s official display name.</p>
                    </div>

                    <!-- Subdomain -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-secondary mb-1">Tenant Subdomain</label>
                        <input type="text" name="subdomain" required
                            value="{{ old('subdomain') }}"
                            class="w-full border-primary border-rounded p-2 text-tertiary"
                            placeholder="e.g. arzavo">

                        <div class="mt-1">
                            <p class="text-[11px] text-tertiary">Your tenant will be available at <b class="text-primary">{{ old('subdomain') ? old('subdomain') : 'subdomain' }}.{{ config('app.domain') }}</b></p>
                            <p class="text-[11px] text-tertiary"><i class="fa-regular fa-check-circle text-green-500"></i> Recommended for most users<br><i class="fa-regular fa-check-circle text-green-500"></i> No DNS configuration needed
                            </p>
                        </div>
                    </div>

                    <!-- Custom Domain (Optional with Verify button) -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-semibold text-secondary mb-1">Custom Domain (Optional)</label>
                        <input type="text" name="domain"
                            value="{{ old('domain') }}"
                            class="w-full border-primary border-rounded p-2 text-tertiary pr-24"
                            placeholder="e.g. arzavo.com">

                        <button type="button"
                            class="absolute right-1 top-6 mt-px text-xs bg-accent text-invert font-semibold py-2 px-4 border-rounded transition">
                            Verify
                        </button>

                        <ul class="text-[11px] text-tertiary mt-1 list-disc pl-4">
                            <li>Requires domain ownership</li>
                            <li>You must add DNS CNAME record pointing to <span class="font-semibold">{{ config('app.domain') }}</span></li>
                            <li>SSL certificate will be auto-generated after verification</li>
                        </ul>
                    </div>

                    <!-- Create Button -->
                    <button type="submit"
                        class="w-full py-2.5 bg-invert text-invert font-bold border-rounded transition">
                        Create Tenant
                    </button>
                </form>

                <!-- Additional Info Box -->
                <div class="mt-5 bg-accent-subtle border-primary border-rounded p-3 text-[12px] text-accent" style="border-color: var(--bg-accent);">
                    <b>Need Help?</b><br>
                    You can start with a subdomain and later switch to a custom domain anytime.
                </div>
        </div>
        @endif
    </div>
</div>
</div>

@endsection