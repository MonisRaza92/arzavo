@extends('layouts.app')
@section('title', 'Dashboard - Arzavo')

@section('content')
<div class="min-h-screen bg-secondary">
    <!-- Top Navigation -->
    <nav class="bg-primary border-bottom sticky top-0 z-50">
        <div class="container">
            <div class="flex items-center justify-between py-4">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo" class="logo">
                    <div class="hidden md:block">
                        <h1 class="text-xl font-bold text-primary">Dashboard</h1>
                        <p class="text-tertiary text-sm">Manage your educational platforms</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative text-tertiary hover:text-primary transition-colors">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-accent text-invert text-xs w-5 h-5 border-rounded flex items-center justify-center">3</span>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-accent border-rounded flex items-center justify-center">
                            <i class="fa-solid fa-user text-invert"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-primary font-medium">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</div>
                            <div class="text-tertiary text-sm">{{ Auth::user()->email }}</div>
                        </div>
                        <button class="text-tertiary hover:text-primary">
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-primary border-primary border-rounded p-6 sticky top-24">
                    <nav class="space-y-2">
                        <a href="#overview" class="flex items-center space-x-3 px-4 py-3 bg-accent text-invert border-rounded font-medium">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Overview</span>
                        </a>
                        <a href="#tenants" class="flex items-center space-x-3 px-4 py-3 text-secondary hover:bg-secondary hover:text-primary border-rounded transition-all">
                            <i class="fa-solid fa-building"></i>
                            <span>My Tenants</span>
                        </a>
                        <a href="#subscriptions" class="flex items-center space-x-3 px-4 py-3 text-secondary hover:bg-secondary hover:text-primary border-rounded transition-all">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Subscriptions</span>
                        </a>
                        <a href="#billing" class="flex items-center space-x-3 px-4 py-3 text-secondary hover:bg-secondary hover:text-primary border-rounded transition-all">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Billing</span>
                        </a>
                        <a href="#support" class="flex items-center space-x-3 px-4 py-3 text-secondary hover:bg-secondary hover:text-primary border-rounded transition-all">
                            <i class="fa-solid fa-headset"></i>
                            <span>Support</span>
                        </a>
                        <a href="#settings" class="flex items-center space-x-3 px-4 py-3 text-secondary hover:bg-secondary hover:text-primary border-rounded transition-all">
                            <i class="fa-solid fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </nav>

                    <!-- Quick Actions -->
                    <div class="mt-8 pt-6 border-t border-tertiary">
                        <h3 class="text-primary font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="openCreateTenantModal()" class="w-full bg-accent text-invert px-4 py-3 border-rounded font-medium hover-invert transition-all">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Create Tenant
                            </button>
                            <a href="{{ route('documentation') }}" class="block w-full bg-secondary text-primary px-4 py-3 border-rounded font-medium text-center hover-primary transition-all">
                                <i class="fa-solid fa-book mr-2"></i>
                                Documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                @include('arzavo.dashboard.partials.overview')
                @include('arzavo.dashboard.partials.tenants')
                @include('arzavo.dashboard.partials.subscriptions')
                @include('arzavo.dashboard.partials.billing')
                @include('arzavo.dashboard.partials.support')
            </div>
        </div>
    </div>
</div>

<!-- Create Tenant Modal -->
@include('arzavo.dashboard.partials.create-tenant-modal')

<script>
function openCreateTenantModal() {
    document.getElementById('createTenantModal').classList.remove('hidden');
}

function closeCreateTenantModal() {
    document.getElementById('createTenantModal').classList.add('hidden');
}

// Smooth scrolling for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection