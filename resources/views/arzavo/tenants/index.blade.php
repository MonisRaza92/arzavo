@extends('layouts.tenants')
@section('title', 'Manage Workspaces - ' . config('app.name'))

@section('content')
@php
    $tab = request('tab', 'workspaces');
@endphp

<div class="p-6">
    <!-- Welcome Breadcrumb Banner -->
    <div class="breadcrumb mb-6 flex justify-between items-center p-4 border-rounded bg-primary border-primary">
        <div>
            <h1 class="text-3xl font-bold tracking-tight flex items-center gap-2 text-primary">
                <i class="fa-solid fa-layer-group text-xl"></i>
                Welcome Back
            </h1>
            <p class="text-sm mt-1 pl-0.5 font-medium text-secondary">
                Empower learning. Manage with ease. Lead with insight.
            </p>
            <div class="links flex flex-wrap items-center gap-1 text-sm font-medium mt-6 text-secondary">
                <a href="{{ route('home') }}" class="hover:text-primary transition-all duration-200 flex items-center gap-1">
                    <i class="fas fa-home"></i> Home
                </a>
                <i class="fas fa-angle-right text-xs opacity-70"></i>
                <span class="capitalize">Manage Workspaces</span>
            </div>
        </div>
        <div class="right hidden md:block">
            <div class="flex items-baseline justify-end">
                <span id="clock" class="text-5xl font-bold text-right text-primary">00:00:00</span>
            </div>
            <div class="mt-2 text-right">
                <div id="date" class="text-md font-medium text-tertiary">Loading date…</div>
            </div>
            <p id="greeting-text" class="text-primary text-xl font-bold mt-1 flex items-center justify-end gap-2">
                <i class="fas fa-smile text-yellow-400"></i>
                <span>Good Morning!</span>
            </p>
        </div>
    </div>

    <!-- Navigation Tabs & Actions -->
    <div class="flex justify-between items-center border-bottom mb-8 gap-4 flex-wrap">
        <div class="flex gap-6">
            <a href="{{ route('tenants.index', ['tab' => 'workspaces']) }}" 
               class="pb-3 text-xs font-semibold border-b-2 transition duration-200 {{ $tab === 'workspaces' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-primary' }}">
                <i class="fa-solid fa-building-columns mr-1.5"></i> My Workspaces
            </a>
            <a href="{{ route('tenants.index', ['tab' => 'domain']) }}" 
               class="pb-3 text-xs font-semibold border-b-2 transition duration-200 {{ $tab === 'domain' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-primary' }}">
                <i class="fa-solid fa-globe mr-1.5"></i> Domain Verification
            </a>
            <a href="{{ route('tenants.index', ['tab' => 'billing']) }}" 
               class="pb-3 text-xs font-semibold border-b-2 transition duration-200 {{ $tab === 'billing' ? 'border-primary text-primary' : 'border-transparent text-secondary hover:text-primary' }}">
                <i class="fa-solid fa-file-invoice-dollar mr-1.5"></i> Plans & Billing
            </a>
        </div>
        <div class="pb-3">
            <a href="{{ route('tenants.create') }}" class="text-xs px-4 py-2.5 bg-invert text-invert border-rounded hover-invert font-semibold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-plus text-[10px]"></i> New Workspace
            </a>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Workspaces Card -->
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-tertiary text-sm font-medium mb-2">Total Workspaces</p>
                        <h3 class="text-4xl font-bold text-primary">{{ $tenants->count() }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-400 text-xs font-medium">
                                Active status
                            </span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-building-columns text-2xl"></i>
                    </div>
                </div>

                <!-- Mini Bar Chart -->
                <div class="flex items-end gap-1 h-12 mt-4">
                    <div class="flex-1 bg-invert border-rounded opacity-40 chart-bar" style="height: 45%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-50 chart-bar" style="height: 60%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-60 chart-bar" style="height: 35%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-70 chart-bar" style="height: 75%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-80 chart-bar" style="height: 55%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-90 chart-bar" style="height: 85%;"></div>
                    <div class="flex-1 bg-invert border-rounded chart-bar" style="height: 100%;"></div>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Workspaces</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </div>
        </div>

        <!-- Verified Domains Card -->
        @php
            $totalCustom = $tenants->whereNotNull('custom_domain')->count();
            $verified = $tenants->where('domain_verified', true)->count();
            $percent = $totalCustom > 0 ? round(($verified / $totalCustom) * 100) : 0;
        @endphp
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-tertiary text-sm font-medium mb-2">Verified Domains</p>
                        <h3 class="text-4xl font-bold text-primary">{{ $verified }} / {{ $totalCustom }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-400 text-xs font-medium">
                                {{ $percent }}% Verified
                            </span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-globe text-2xl"></i>
                    </div>
                </div>

                <!-- Progress Ring/Bar -->
                <div class="mt-8">
                    <div class="flex items-center justify-between text-xs text-secondary mb-2">
                        <span>Connected domains status</span>
                        <span class="font-medium">{{ $percent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-secondary rounded-full overflow-hidden">
                        <div class="h-full bg-invert rounded-full progress-bar" style="width: {{ $percent }}%;"></div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Verify Settings</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-tertiary text-sm font-medium mb-2">Pending Invoices</p>
                        <h3 class="text-4xl font-bold text-primary">₹{{ number_format($pendingAmount, 2) }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 text-xs font-medium">
                                {{ $invoices->where('status', 'pending')->count() }} Unpaid
                            </span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                    </div>
                </div>

                <!-- Dynamic Stars / Visual rating replacement -->
                <div class="mt-12 flex items-center gap-1">
                    @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star text-primary text-sm opacity-20"></i>
                    @endfor
                    <span class="text-xs text-primary ml-2">Secure billing portal</span>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Billing Portal</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </div>
        </div>
    </div>

    <!-- Live clock script -->
    <script>
        (function() {
            const clockEl = document.getElementById("clock");
            const dateEl = document.getElementById("date");
            const greetingEl = document.getElementById("greeting-text");

            if (!clockEl || !dateEl || !greetingEl) return;

            function updateClock() {
                const now = new Date();
                const pad = n => (n < 10 ? "0" + n : n);
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const seconds = now.getSeconds();

                clockEl.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;

                dateEl.textContent = now.toLocaleDateString("en-US", {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                let greeting = "";
                let icon = "";

                if (hours >= 5 && hours < 12) {
                    greeting = "Good Morning!";
                    icon = "fa-sun";
                } else if (hours >= 12 && hours < 17) {
                    greeting = "Good Afternoon!";
                    icon = "fa-cloud-sun";
                } else if (hours >= 17 && hours < 21) {
                    greeting = "Good Evening!";
                    icon = "fa-moon";
                } else {
                    greeting = "Good Night!";
                    icon = "fa-star";
                }

                greetingEl.innerHTML = `
                    <i class="fas ${icon} text-yellow-400"></i>
                    <span>${greeting}</span>
                `;
            }

            updateClock();
            setInterval(updateClock, 1000);
        })();
    </script>

    <!-- TAB CONTENTS -->
    @if($tab === 'workspaces')
        <!-- Tab 1: Workspaces List -->
        <div class="space-y-4">
            @forelse($tenants as $tenant)
                <div class="p-5 border-rounded bg-primary border-primary flex items-center justify-between flex-wrap gap-4 hover:shadow-md transition duration-300">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 border-rounded bg-hover-secondary flex items-center justify-center text-xl text-primary font-bold shrink-0">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-primary truncate">{{ $tenant->name }}</h3>
                                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-tertiary mt-1.5 flex-wrap">
                                <a target="_blank" href="https://{{ $tenant->subdomain }}" class="hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-link text-[10px]"></i> {{ $tenant->subdomain }}
                                </a>
                                @if($tenant->custom_domain)
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-globe text-[10px]"></i> {{ $tenant->custom_domain }}
                                        @if($tenant->domain_verified)
                                            <span class="text-green-500 text-[10px] font-semibold flex items-center gap-0.5 ml-0.5">
                                                <i class="fa-solid fa-circle-check"></i> Live
                                            </span>
                                        @else
                                            <span class="text-red-400 text-[10px] font-semibold flex items-center gap-0.5 ml-0.5">
                                                <i class="fa-solid fa-circle-xmark"></i> Unverified
                                            </span>
                                        @endif
                                    </span>
                                @endif
                                <span><i class="fa-solid fa-clock text-[10px]"></i> {{ $tenant->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Verify domain trigger -->
                        @if($tenant->custom_domain)
                            <button id="connectDomainBtn-{{ $tenant->id }}" class="text-xs px-3 py-2 bg-secondary text-secondary hover-primary border-primary border-rounded flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved"></i> Verify Domain
                            </button>
                        @else
                            <button id="connectDomainBtn-{{ $tenant->id }}" class="text-xs px-3 py-2 bg-secondary text-secondary hover-primary border-primary border-rounded flex items-center gap-1">
                                <i class="fa-solid fa-plus-circle"></i> Connect Domain
                            </button>
                        @endif

                        <!-- Open dashboard -->
                        <a target="_blank" href="{{ $tenant->custom_domain && $tenant->domain_verified ? 'https://' . $tenant->custom_domain . '/admin/dashboard' : 'https://' . $tenant->subdomain . '/admin/dashboard' }}" 
                           class="text-xs px-3 py-2 bg-invert text-invert border-rounded hover-invert flex items-center gap-1 font-semibold">
                            Open <i class="fa-solid fa-share-from-square text-[10px]"></i>
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this workspace? All data, students, courses and databases will be permanently destroyed!')"
                                    class="text-xs text-red-500 bg-red-50 hover:bg-red-100 border border-red-200 border-rounded p-2" title="Delete Workspace">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Include verify domain modal popup -->
                @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])
            @empty
                <div class="p-12 text-center border-rounded bg-primary border-dashed border-primary">
                    <div class="w-16 h-16 mx-auto mb-4 bg-hover-secondary rounded-full flex items-center justify-center text-primary text-xl">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h3 class="text-base font-bold text-primary">No Workspaces Found</h3>
                    <p class="text-xs text-tertiary mt-1.5 max-w-sm mx-auto">Create your first school or coaching management system instance to start teaching.</p>
                    <a href="{{ route('tenants.create') }}" class="mt-4 inline-block text-xs px-4 py-2 bg-invert text-invert border-rounded hover-invert font-semibold transition">
                        Create Workspace
                    </a>
                </div>
            @endforelse
        </div>

    @elseif($tab === 'domain')
        <!-- Tab 2: Domain Verification -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Instructions Column -->
            <div class="lg:col-span-2 space-y-6">
                <div class="p-5 border-rounded bg-primary border-primary shadow-sm">
                    <h3 class="text-base font-bold text-primary flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-globe text-blue-500"></i> Custom Domain Configuration Instructions
                    </h3>
                    <p class="text-xs text-secondary leading-relaxed mb-4">
                        By default, your workspace runs on a subdomain (e.g. <code>workspace.arzavo.com</code>). You can connect your own brand domain (e.g. <code>academy.yourdomain.com</code>) by pointing DNS settings to our server IP.
                    </p>

                    <div class="p-4 border-rounded bg-hover-secondary border-primary space-y-3">
                        <h4 class="text-xs font-semibold text-primary">Configure DNS Records in Domain Registrar (GoDaddy, Namecheap, Route53 etc.)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div class="p-3 bg-primary border border-slate-200 border-rounded text-xs">
                                <span class="block font-bold text-primary mb-1">A Record:</span>
                                <code class="bg-hover-secondary px-1 border rounded font-mono">@</code> &nbsp;→&nbsp; <strong class="text-primary">{{ env('SERVER_IP', '13.234.98.38') }}</strong>
                            </div>
                            <div class="p-3 bg-primary border border-slate-200 border-rounded text-xs">
                                <span class="block font-bold text-primary mb-1">A Record (www):</span>
                                <code class="bg-hover-secondary px-1 border rounded font-mono">www</code> &nbsp;→&nbsp; <strong class="text-primary">{{ env('SERVER_IP', '13.234.98.38') }}</strong>
                            </div>
                            <div class="p-3 bg-primary border border-slate-200 border-rounded text-xs md:col-span-2">
                                <span class="block font-bold text-primary mb-1">CNAME Record (Verification):</span>
                                <code class="bg-hover-secondary px-1 border rounded font-mono">verify</code> &nbsp;→&nbsp; <strong class="text-primary font-mono">verify.{{ config('app.domain') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 border-rounded flex gap-3 text-xs text-yellow-800">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-yellow-600"></i>
                        <p>DNS changes can take anywhere from a few minutes to 24 hours to propagate globally. Make sure to point DNS records before clicking "Verify Domain".</p>
                    </div>
                </div>

                <!-- Custom Domain Setup for workspaces -->
                <div class="p-5 border-rounded bg-primary border-primary shadow-sm">
                    <h3 class="text-base font-bold text-primary flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-circle-check text-green-500"></i> Connect and Verify Custom Domain
                    </h3>

                    <div class="space-y-4">
                        @foreach($tenants as $tenant)
                            <div class="p-4 border-rounded bg-hover-secondary border-primary flex items-center justify-between flex-wrap gap-4">
                                <div>
                                    <h4 class="text-xs font-bold text-primary">{{ $tenant->name }}</h4>
                                    <p class="text-[11px] text-tertiary mt-1">Subdomain: {{ $tenant->subdomain }}</p>
                                    @if($tenant->custom_domain)
                                        <p class="text-xs mt-1.5 text-primary flex items-center gap-1">
                                            <strong>Custom Domain:</strong> <code>{{ $tenant->custom_domain }}</code>
                                            @if($tenant->domain_verified)
                                                <span class="text-green-600 text-[10px] font-semibold bg-green-50 px-1.5 py-0.5 rounded border border-green-200">
                                                    <i class="fa-solid fa-check-circle"></i> Verified
                                                </span>
                                            @else
                                                <span class="text-red-500 text-[10px] font-semibold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                    <i class="fa-solid fa-times-circle"></i> Unverified
                                                </span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-[11px] text-tertiary mt-1.5">No custom domain connected.</p>
                                    @endif
                                </div>
                                <button id="connectDomainBtn-{{ $tenant->id }}" class="text-xs px-3 py-2 bg-invert text-invert border-rounded hover-invert font-semibold transition">
                                    {{ $tenant->custom_domain ? 'Manage & Verify' : 'Connect Domain' }}
                                </button>
                            </div>
                            
                            @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Server Status Side Info -->
            <div class="space-y-6">
                <div class="p-5 border-rounded bg-primary border-primary shadow-sm">
                    <h3 class="text-sm font-bold text-primary mb-3">Server Configuration</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex justify-between border-bottom pb-2">
                            <span class="text-tertiary">Server IP Address:</span>
                            <span class="font-mono font-bold text-primary">{{ env('SERVER_IP', '13.234.98.38') }}</span>
                        </li>
                        <li class="flex justify-between border-bottom pb-2">
                            <span class="text-tertiary">SSL Certificate:</span>
                            <span class="text-green-600 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Auto-Install (Let's Encrypt)
                            </span>
                        </li>
                        <li class="flex justify-between border-bottom pb-2">
                            <span class="text-tertiary">DNS Checker tool:</span>
                            <a target="_blank" href="https://dnschecker.org" class="text-primary hover:underline flex items-center gap-0.5">
                                DNS Checker <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    @elseif($tab === 'billing')
        <!-- Tab 3: Billing & Payments -->
        <div class="space-y-6">
            <!-- Active Subscriptions -->
            <div class="p-5 border-rounded bg-primary border-primary shadow-sm">
                <h3 class="text-base font-bold text-primary mb-4"><i class="fa-solid fa-box text-blue-500 mr-1.5"></i> Active Subscriptions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($tenants as $tenant)
                        <div class="p-4 border-rounded bg-hover-secondary border-primary flex justify-between items-start gap-4">
                            <div>
                                <h4 class="text-xs font-bold text-primary">{{ $tenant->name }}</h4>
                                <p class="text-[11px] text-tertiary mt-1">{{ $tenant->subdomain }}</p>
                                
                                @if($tenant->subscription && $tenant->subscription->plan)
                                    <div class="mt-3">
                                        <span class="text-[10px] font-bold px-2 py-0.75 bg-blue-100 text-blue-700 rounded-full">
                                            {{ $tenant->subscription->plan->name }}
                                        </span>
                                        <span class="text-xs text-secondary ml-1.5">
                                            @if($tenant->subscription->ends_at)
                                                Expires: {{ $tenant->subscription->ends_at->format('d M Y') }}
                                            @else
                                                Lifetime / Lifetime Free
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <span class="text-[10px] font-bold px-2 py-0.75 bg-gray-100 text-gray-500 rounded-full">
                                            No Plan Active
                                        </span>
                                        <a href="{{ route('pricing') }}" class="text-xs text-primary hover:underline font-semibold ml-2 flex-inline items-center gap-0.5">
                                            Choose Plan <i class="fa-solid fa-angle-right text-[10px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            @if($tenant->subscription && $tenant->subscription->plan)
                                <a href="{{ route('pricing') }}" class="text-xs text-secondary hover-primary transition">Change Plan</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-tertiary col-span-2">No workspaces to display subscription settings.</p>
                    @endforelse
                </div>
            </div>

            <!-- Invoices List -->
            <div class="p-5 border-rounded bg-primary border-primary shadow-sm">
                <h3 class="text-base font-bold text-primary mb-4"><i class="fa-solid fa-file-invoice text-yellow-500 mr-1.5"></i> Invoices & Billing History</h3>
                
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-hover-secondary border-bottom">
                                <th class="p-3 font-semibold text-primary">Invoice ID</th>
                                <th class="p-3 font-semibold text-primary">Workspace</th>
                                <th class="p-3 font-semibold text-primary">Period</th>
                                <th class="p-3 font-semibold text-primary">Amount</th>
                                <th class="p-3 font-semibold text-primary">Status</th>
                                <th class="p-3 font-semibold text-primary text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr class="border-bottom hover:bg-hover-secondary transition">
                                    <td class="p-3 font-mono">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-3 font-semibold text-primary">{{ $invoice->tenant->name }}</td>
                                    <td class="p-3 text-tertiary">
                                        {{ $invoice->billing_period_start ? $invoice->billing_period_start->format('d M Y') : 'N/A' }} 
                                        - 
                                        {{ $invoice->billing_period_end ? $invoice->billing_period_end->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="p-3 font-bold text-primary">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        @if($invoice->status === 'pending')
                                            <button onclick="payInvoice({{ $invoice->tenant_id }}, this)" 
                                                    class="text-[11px] px-3 py-1.5 bg-invert text-invert border-rounded hover-invert font-semibold transition inline-block">
                                                Pay Now
                                            </button>
                                        @else
                                            <span class="text-xs text-tertiary"><i class="fa-solid fa-circle-check text-green-500"></i> Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-tertiary">No invoices or billing history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Load Cashfree payment gateway SDK for billing inline checkout -->
        <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
        <script>
            const cashfree = Cashfree({
                mode: "{{ config('services.cashfree.env') === 'production' ? 'production' : 'sandbox' }}"
            });

            async function payInvoice(tenantId, btn) {
                if (btn.disabled) return;
                
                const originalText = btn.innerText;
                btn.disabled = true;
                btn.innerText = "Initializing...";

                try {
                    const res = await fetch(`/pay/${tenantId}`);
                    const data = await res.json();
                    
                    if (!data.payment_session_id) {
                        throw new Error(data.message || "Payment session initialization failed.");
                    }

                    await cashfree.checkout({
                        paymentSessionId: data.payment_session_id,
                        redirectTarget: "_self"
                    });
                } catch(err) {
                    alert(err.message || "Something went wrong. Please retry.");
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            }
        </script>
    @endif
</div>
@endsection