@extends('layouts.app')
@section('title', 'Create Tenants - ' . config('app.name'))

@section('content')
<div class="flex items-start justify-start relative h-dvh w-full">
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
                    <button onclick="toggleTenantStatus({{ $tenant->id }});"
                        class="text-[10px] font-semibold px-2 py-1 rounded-full
                        {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                        {{ $tenant->status }}
                    </button>
                </div>

                <!-- domain -->
                <div class="flex items-center text-sm py-1 border-bottom">
                    <div class="p-3 border-right mr-3">
                        <i class="fa-solid fa-globe text-tertiary"></i>
                    </div>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Subdomain:</strong><br>
                        <a target="_blank"
                            href="https://{{$tenant->subdomain}}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->subdomain }}
                        </a>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div class="flex items-center text-sm py-1 border-bottom">
                    <div class="p-3 border-right mr-3">
                        <i class="fa-solid fa-link text-tertiary"></i>
                    </div>
                    <div>
                        <strong class="text-secondary text-xs leading-none">Custom Domain:</strong><br>

                        @if($tenant->custom_domain && $tenant->domain_verified)
                        <a target="_blank"
                            href="https://{{ $tenant->custom_domain }}"
                            class="text-xs font-medium text-indigo-600 leading-none hover:underline">
                            {{ $tenant->custom_domain }}
                        </a>
                        <span class="text-[10px] text-green-600 font-semibold ml-1">✔ Verified</span>
                        @else
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-red-600 font-medium">
                                {{ $tenant->custom_domain ?? 'Not Connected' }}
                            </span>
                            <div class="text-xs text-gray-500">
                                <button type="button" id="connectDomainBtn-{{ $tenant->id }}"
                                    class="text-indigo-600 hover:underline">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                            </div>
                        </div>
                        @include('arzavo.tenants.domain-verify')
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center p-1">
                    <div class="flex">

                        <a href="#" class="text-sm text-tertiary p-2 pr-3 border-right"><i class="fa-solid fa-pen-to-square"></i></a>

                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm py-2 px-3 text-tertiary border-right" onclick="return confirm('Are you sure you want to delete this tenant?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>
                    @if($tenant->custom_domain && $tenant->domain_verified )
                    <a target="_blank" href="https://{{$tenant->custom_domain}}/admin/dashboard"
                        class="text-sm text-indigo-600 hover:underline p-2 border-left font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        View Dashboard
                    </a>
                    @else
                    <a target="_blank" href="https://{{$tenant->subdomain}}/admin/dashboard"
                        class="text-sm text-indigo-600 hover:underline p-2 border-left font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        View Dashboard
                    </a>
                    @endif
                </div>

            </div>
            @endforeach
            @endif
            @include('arzavo.tenants.create')
        </div>
    </div>
</div>

@endsection