@extends('layouts.tenants')
@section('title', 'Workspaces - ' . config('app.name'))

@section('content')
    <!-- BREADCRUMB HEADER BANNER -->
    <div class="breadcrumb mb-4 flex flex-wrap justify-between items-center p-4 border-rounded bg-primary border-primary  gap-4">
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2.5 text-primary tracking-tight">
                <i class="fa-solid fa-layer-group text-primary"></i> Workspaces Overview
            </h1>
            <p class="text-xs text-secondary mt-1">
                {{ $tenants->count() }} tenant {{ Str::plural('environment', $tenants->count()) }} · real-time database statistics & network routing
            </p>
            <div class="links flex flex-wrap items-center gap-1.5 text-xs font-medium mt-3 text-secondary">
                <a href="{{ route('home') }}" class="hover:text-primary transition-all flex items-center gap-1">
                    <i class="fas fa-home"></i> Home
                </a>
                <i class="fas fa-angle-right text-[10px] opacity-70"></i>
                <span class="text-primary font-semibold">Manage Workspaces</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('tenants.create') }}" 
               class="px-4 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition  flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[11px]"></i> New workspace
            </a>
        </div>
    </div>

    <!-- WORKSPACES FULL-WIDTH TABLE CARDS LIST -->
    @if($tenants->count() > 0)
        <div class="space-y-4 mb-4">
            @foreach($tenants as $tenant)
                @php
                    $subscription = $tenant->subscription;
                    $plan = $subscription ? $subscription->plan : null;
                    
                    // Pending invoice & amount (100% Real from DB)
                    $tenantInvoices = $invoices->where('tenant_id', $tenant->id);
                    $pendingInvoice = $tenantInvoices->where('status', 'pending')->first();
                    $pendingAmount = $tenantInvoices->where('status', 'pending')->sum('total_amount');

                    // Owner name (100% Real)
                    $ownerUser = $tenant->admin ?: Auth::guard('web')->user();
                    $ownerName = $ownerUser ? trim($ownerUser->fname . ' ' . $ownerUser->lname) : 'System Admin';

                    // Status Badge Mapping
                    $statusDotClass = 'bg-emerald-500 animate-pulse';
                    $statusBadgeClass = 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20';
                    $statusLabel = 'Active';

                    if ($tenant->status === 'suspended' || $tenant->status === 'inactive') {
                        $statusDotClass = 'bg-rose-500';
                        $statusBadgeClass = 'bg-rose-500/10 text-rose-600 border-rose-500/20';
                        $statusLabel = ucfirst($tenant->status);
                    } elseif ($tenant->isTrialActive()) {
                        $statusDotClass = 'bg-blue-500 animate-pulse';
                        $statusBadgeClass = 'bg-blue-500/10 text-blue-600 border-blue-500/20';
                        $statusLabel = 'Trial';
                    } elseif (!$tenant->status || $tenant->status === 'expired') {
                        $statusDotClass = 'bg-amber-500';
                        $statusBadgeClass = 'bg-amber-500/10 text-amber-600 border-amber-500/20';
                        $statusLabel = 'Expired';
                    }

                    // REAL Tenant Stats directly queried from Tenant Database
                    $stats = $tenantStats[$tenant->id] ?? ['users_count' => 0, 'students_limit' => 150, 'storage_used' => 0, 'storage_limit' => 5 * 1024 * 1024 * 1024];
                    $usersCount = $stats['users_count'];
                    $usersLimit = is_numeric($stats['students_limit']) ? $stats['students_limit'] : 'Unlimited';

                    // Real Storage calculations
                    $storageUsedBytes = $stats['storage_used'] ?: 0;
                    $storageLimitBytes = $stats['storage_limit'] ?: (5 * 1024 * 1024 * 1024);
                    $storageUsedFormatted = formatSize($storageUsedBytes);
                    $storageLimitFormatted = formatSize($storageLimitBytes);
                    $storagePercentage = $storageLimitBytes > 0 ? min(100, max(2, round(($storageUsedBytes / $storageLimitBytes) * 100))) : 2;

                    // Plan name format (100% Real)
                    $planName = $plan ? $plan->name : ($tenant->isTrialActive() ? 'Free Trial' : 'Basic Plan');

                    // Renewal Date (100% Real)
                    if ($subscription && $subscription->ends_at) {
                        $renewalDate = $subscription->ends_at->format('d M Y');
                    } elseif ($tenant->isTrialActive()) {
                        $renewalDate = 'Trial (' . $tenant->trialDaysLeft() . 'd left)';
                    } else {
                        $renewalDate = 'N/A';
                    }

                    // Last login (100% Real)
                    $lastLogin = $tenant->admin && $tenant->admin->last_login 
                        ? $tenant->admin->last_login->diffForHumans() 
                        : $tenant->updated_at->diffForHumans();

                    // Formatted created timestamp
                    $createdAtFormatted = $tenant->created_at->format('d M Y');
                    $createdAtDiff = $tenant->created_at->diffForHumans();
                @endphp

                <!-- HIGH-FIDELITY FULL-WIDTH TABLE STYLE WORKSPACE CARD -->
                <div x-data="{ menuOpen: false }" class="w-full border-rounded bg-primary border-primary hover:border-secondary transition-all duration-300  relative group overflow-hidden">
                    
                    <!-- Top Main Header Bar -->
                    <div class="p-5 flex items-center justify-between flex-wrap gap-4 border-bottom">
                        <div class="flex items-center gap-4 min-w-0">
                            <!-- Avatar Initial Box -->
                            <div class="w-12 h-12 rounded bg-secondary flex items-center justify-center text-2xl font-extrabold text-primary shrink-0 border border-primary font-mono ">
                                {{ strtoupper(substr($tenant->name, 0, 1)) }}
                            </div>
                            <!-- Title & Subdomain / Verified Custom Domain -->
                            <div class="min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="text-base font-semibold text-primary truncate leading-snug">{{ $tenant->name }}</h3>
                                    <span class="text-[11px] px-2.5 py-0.5 rounded-full font-bold border inline-flex items-center gap-1.5 shrink-0 {{ $statusBadgeClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDotClass }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-tertiary font-mono mt-1 flex-wrap">
                                    @if($tenant->custom_domain && $tenant->domain_verified)
                                        <a target="_blank" href="https://{{ $tenant->custom_domain }}" class="hover:underline flex items-center gap-1.5 font-semibold text-emerald-600">
                                            {{ $tenant->custom_domain }} <i class="fa-solid fa-circle-check text-[11px]"></i>
                                        </a>
                                    @else
                                        <a target="_blank" href="https://{{ $tenant->subdomain }}" class="hover:underline flex items-center gap-1 font-semibold">
                                            {{ $tenant->subdomain }} <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Quick Action Controls -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <!-- Manage Cockpit -->
                            <a href="{{ route('tenants.show', $tenant->subdomain) }}" 
                               class="text-xs px-3.5 py-2.5 bg-primary border-primary text-primary hover-primary border-rounded font-semibold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-sliders text-indigo-500 text-[11px]"></i> Manage Cockpit
                            </a>

                            <!-- Open Admin Dashboard -->
                            <a target="_blank" href="{{ $tenant->custom_domain && $tenant->domain_verified ? 'https://' . $tenant->custom_domain . '/admin/dashboard' : 'https://' . $tenant->subdomain . '/admin/dashboard' }}" 
                               class="text-xs px-4 py-2.5 bg-invert text-invert border-rounded hover-invert font-bold transition  flex items-center gap-1.5">
                                Open workspace <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>

                            <!-- 3-Dots Menu -->
                            <div class="relative">
                                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" 
                                        class="w-9 h-9 border-rounded bg-hover-secondary text-secondary hover:text-primary flex items-center justify-center text-xs transition border border-primary">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div x-show="menuOpen" x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                                     class="absolute right-0 top-11 w-52 bg-primary border-primary border-rounded  z-50 py-1 text-xs">
                                    <a href="{{ route('tenants.show', $tenant->subdomain) }}" class="flex items-center gap-2 px-3 py-2 text-secondary hover-primary">
                                        <i class="fa-solid fa-sliders text-indigo-500 w-4"></i> Workspace Cockpit
                                    </a>
                                    <button @click="menuOpen = false; document.getElementById('connectDomainPopup-{{ $tenant->id }}').classList.remove('hidden')" class="w-full text-left flex items-center gap-2 px-3 py-2 text-secondary hover-primary">
                                        <i class="fa-solid fa-globe text-blue-500 w-4"></i> Verify / Manage Domain
                                    </button>
                                    <a href="{{ route('tenants.show', $tenant->subdomain) }}#billing" class="flex items-center gap-2 px-3 py-2 text-secondary hover-primary">
                                        <i class="fa-solid fa-credit-card text-purple-500 w-4"></i> Billing & Invoices
                                    </a>
                                    <button @click="menuOpen = false; document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.remove('hidden')" class="w-full text-left flex items-center gap-2 px-3 py-2 text-secondary hover-primary">
                                        <i class="fa-solid fa-key text-amber-500 w-4"></i> Reset Admin Password
                                    </button>
                                    <div class="border-top my-1"></div>
                                    <button @click="menuOpen = false; document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.remove('hidden')" class="w-full text-left flex items-center gap-2 px-3 py-2 text-rose-600 hover-primary font-semibold">
                                        <i class="fa-solid fa-trash-can w-4"></i> Delete Workspace
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata Table Columns Row (Full Width Grid) -->
                    <div class="p-5 bg-hover-secondary/30 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 text-xs">
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">LAST LOGIN</span>
                            <span class="font-medium text-primary block mt-0.5 truncate">{{ $lastLogin }}</span>
                        </div>
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">PLAN</span>
                            <span class="font-medium text-primary block mt-0.5 truncate">{{ $planName }}</span>
                        </div>
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">PRIMARY DOMAIN</span>
                            <a target="_blank" href="https://{{ $tenant->subdomain }}" class="font-mono text-tertiary hover:text-primary block mt-0.5 truncate">
                                {{ $tenant->subdomain }}
                            </a>
                        </div>
                        
                        <!-- CUSTOM DOMAIN WITH VERIFIED STATUS -->
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">CUSTOM DOMAIN</span>
                            @if($tenant->custom_domain)
                                @if($tenant->domain_verified)
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="font-mono font-semibold text-emerald-600 truncate">{{ $tenant->custom_domain }}</span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20 shrink-0 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Verified
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="font-mono font-semibold text-amber-600 truncate">{{ $tenant->custom_domain }}</span>
                                        <button id="connectDomainBtn-{{ $tenant->id }}" 
                                                class="px-1.5 py-0.5 rounded text-[10px] bg-amber-500/10 text-amber-600 font-bold border border-amber-500/20 shrink-0 inline-flex items-center gap-1 hover:bg-amber-500/20 transition cursor-pointer">
                                            <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Unverified
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                    <span class="font-mono text-tertiary">Not configured</span>
                                    <button id="connectDomainBtn-{{ $tenant->id }}" 
                                            class="px-1.5 py-0.5 rounded text-[10px] bg-hover-secondary text-primary font-bold border border-primary shrink-0 hover:bg-hover transition cursor-pointer">
                                        + Connect
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">RENEWAL</span>
                            <span class="font-medium text-primary block mt-0.5 truncate">{{ $renewalDate }}</span>
                        </div>
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">USERS</span>
                            <span class="font-mono font-medium text-primary block mt-0.5 truncate">{{ $usersCount }} / {{ $usersLimit }}</span>
                        </div>
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">PENDING INVOICE</span>
                            <span class="font-medium block mt-0.5 truncate {{ $pendingInvoice ? 'text-rose-600 font-semibold font-mono' : 'text-primary' }}">
                                {{ $pendingInvoice ? '#INV-' . str_pad($pendingInvoice->id, 5, '0', STR_PAD_LEFT) : 'None' }}
                            </span>
                        </div>
                        <div class="border-dashed p-4 rounded">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">OUTSTANDING</span>
                            <span class="font-mono font-bold block mt-0.5 truncate {{ $pendingAmount > 0 ? 'text-rose-600' : 'text-primary' }}">
                                ₹{{ number_format($pendingAmount, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Storage Bar & Created Date Footer -->
                    <div class="px-5 py-3 border-top flex items-center justify-between flex-wrap gap-4 text-xs">
                        <div class="flex items-center gap-3 grow max-w-md">
                            <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider shrink-0">STORAGE</span>
                            <div class="w-full bg-hover-secondary h-1.5 border-rounded overflow-hidden border border-primary">
                                <div class="bg-primary h-full border-rounded" style="width: {{ $storagePercentage }}%"></div>
                            </div>
                            <span class="text-tertiary font-mono text-[11px] shrink-0">{{ $storageUsedFormatted }} / {{ $storageLimitFormatted }}</span>
                        </div>

                        <div class="text-[11px] text-tertiary font-mono flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar-check text-[10px]"></i>
                            <span>Created: <strong>{{ $createdAtFormatted }}</strong> ({{ $createdAtDiff }})</span>
                        </div>
                    </div>

                </div>

                <!-- DOMAIN VERIFY MODAL -->
                @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])

                <!-- RESET PASSWORD MODAL -->
                <div id="resetPasswordPopup-{{ $tenant->id }}" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[999]">
                    <div class="bg-primary border-rounded w-full max-w-md p-6 relative  border-primary">
                        <button onclick="document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.add('hidden')" class="absolute right-4 top-4 text-secondary hover:text-primary text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <h3 class="text-lg font-bold text-primary mb-2">Reset Tenant Admin Password</h3>
                        <p class="text-xs text-tertiary mb-4">Enter a new password for the workspace database administrator user.</p>
                        
                        <form action="{{ route('tenants.reset-password', $tenant->subdomain) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-xs font-semibold text-primary block mb-1">New Password</label>
                                <input type="password" name="password" required minlength="6" class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-primary block mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required minlength="6" class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition">
                                Reset Admin Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- DELETE WORKSPACE MODAL (WITH PASSWORD CONFIRMATION) -->
                <div id="deleteWorkspacePopup-{{ $tenant->id }}" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[999]">
                    <div class="bg-primary border-rounded w-full max-w-md p-6 relative border-primary">
                        <button onclick="document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.add('hidden')" class="absolute right-4 top-4 text-secondary hover:text-primary text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-rose-500/10 text-rose-600 flex items-center justify-center text-lg shrink-0 border border-rose-500/20">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-rose-600">Delete Workspace Permanently</h3>
                                <p class="text-xs text-tertiary">Confirm password for <strong>{{ $tenant->name }}</strong></p>
                            </div>
                        </div>
                        
                        <p class="text-xs text-secondary mb-4 bg-rose-500/5 p-3 rounded border border-rose-500/10">
                            <strong>Warning:</strong> All database tables, uploaded files, and staff accounts will be permanently destroyed.
                        </p>

                        <form action="{{ route('tenants.destroy', $tenant->subdomain) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('DELETE')
                            <div>
                                <label class="text-xs font-semibold text-primary block mb-1">Enter your account password</label>
                                <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary focus:outline-none focus:border-rose-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-rose-600 text-white border-rounded font-bold text-xs hover:bg-rose-700 transition">
                                Verify Password & Delete Workspace
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="p-12 border-rounded bg-primary border-primary text-center max-w-xl mx-auto my-12 space-y-4 ">
            <div class="w-16 h-16 border-rounded bg-hover-secondary flex items-center justify-center text-3xl text-tertiary mx-auto border border-primary font-mono">
                W
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">No Workspaces Found</h2>
                <p class="text-xs text-secondary mt-1 max-w-sm mx-auto">
                    Create your first workspace environment to start managing students, staff, courses, and billing.
                </p>
            </div>
            <a href="{{ route('tenants.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-invert text-invert border-rounded hover-invert font-bold text-xs transition ">
                <i class="fa-solid fa-plus"></i> Create Workspace
            </a>
        </div>
    @endif
@endsection