@extends('layouts.tenants')
@section('title', $tenant->name . ' - Workspace Cockpit')

@section('content')
    <!-- BREADCRUMB HEADER BANNER -->
    <div class="breadcrumb mb-4 flex flex-wrap justify-between items-center p-4 border-rounded bg-primary border-primary  gap-4">
        <div>
            <h1 class="text-2xl font-extrabold flex items-center gap-2.5 text-primary tracking-tight">
                <i class="fa-solid fa-layer-group"></i> Workspace: {{ $tenant->name }}
            </h1>
            <p class="text-xs text-secondary mt-1">
                Here you can manage the workspace settings, plan, billing, domains, and users.
            </p>
            <div class="links flex flex-wrap items-center gap-1.5 text-xs font-medium mt-3 text-secondary">
                <a href="{{ route('home') }}" class="hover:text-primary transition-all flex items-center gap-1">
                    <i class="fas fa-home"></i> Home
                </a>
                <i class="fas fa-angle-right text-[10px] opacity-70"></i>
                <a href="{{ route('tenants.index') }}" class="hover:text-primary transition-all">Workspaces</a>
                <i class="fas fa-angle-right text-[10px] opacity-70"></i>
                <span class="text-primary font-semibold">{{ $tenant->name }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a target="_blank" href="{{ $tenant->url }}/admin/dashboard" 
               class="px-4 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition  flex items-center gap-1.5">
                Open workspace <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- COCKPIT CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        
        <!-- CARD 1: DOMAIN SETUP -->
        <div id="domain" class="p-5 border-rounded bg-primary border-primary space-y-4  flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-globe text-blue-500"></i> Domain & Routing
                    </h3>
                    @if($tenant->custom_domain && $tenant->domain_verified)
                        <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20">✓ Verified</span>
                    @elseif($tenant->custom_domain)
                        <span class="px-2 py-0.5 rounded text-[10px] bg-amber-500/10 text-amber-600 font-bold border border-amber-500/20">▲ Unverified</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] bg-hover-secondary text-tertiary font-bold border border-primary">Default</span>
                    @endif
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">PRIMARY SUBDOMAIN</span>
                        <a target="_blank" href="{{ $tenant->url }}" class="font-mono text-primary hover:underline font-semibold block mt-0.5 truncate">
                            {{ $tenant->subdomain }}
                        </a>
                    </div>

                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">CUSTOM DOMAIN</span>
                        <span class="font-mono font-semibold block mt-0.5 truncate {{ $tenant->custom_domain ? ($tenant->domain_verified ? 'text-emerald-600' : 'text-amber-600') : 'text-tertiary' }}">
                            {{ $tenant->custom_domain ?: 'Not configured' }}
                        </span>
                    </div>
                </div>
            </div>

            <button id="connectDomainBtn-{{ $tenant->id }}" 
                    class="w-full py-2.5 px-3 bg-hover-secondary text-primary border-primary border-rounded font-bold text-xs hover-primary transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-gear text-[11px]"></i> Configure Custom Domain
            </button>
        </div>

        <!-- CARD 2: SUBSCRIPTION & PLAN -->
        <div id="billing" class="p-5 border-rounded bg-primary border-primary space-y-4  flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-purple-500"></i> Plan & Subscription
                    </h3>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-indigo-500/10 text-indigo-600 font-bold border border-indigo-500/20">
                        {{ $plan ? $plan->name : ($tenant->isTrialActive() ? 'Trial' : 'Active') }}
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">CURRENT PLAN</span>
                        <span class="font-semibold text-primary block mt-0.5">{{ $plan ? $plan->name : 'Growth (Trial)' }}</span>
                    </div>

                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">RENEWAL / EXPIRATION</span>
                        <span class="font-mono text-primary font-medium block mt-0.5">
                            {{ $subscription && $subscription->ends_at ? $subscription->ends_at->format('d M Y') : ($tenant->isTrialActive() ? 'Trial (' . $tenant->trialDaysLeft() . ' days left)' : 'Lifetime / Active') }}
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('pricing') }}" 
               class="w-full py-2.5 px-3 bg-hover-secondary text-primary border-primary border-rounded font-bold text-xs hover-primary transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-up-right-dots text-[11px]"></i> Upgrade / Change Plan
            </a>
        </div>

        <!-- CARD 3: ACCOUNT OWNER & CREDENTIALS -->
        <div id="owner" class="p-5 border-rounded bg-primary border-primary space-y-4  flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-emerald-500"></i> Account Owner
                    </h3>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20">Admin</span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">OWNER NAME</span>
                        <span class="font-semibold text-primary block mt-0.5">{{ $tenant->admin ? ($tenant->admin->fname . ' ' . $tenant->admin->lname) : Auth::guard('web')->user()->fname . ' ' . Auth::guard('web')->user()->lname }}</span>
                    </div>

                    <div>
                        <span class="text-tertiary text-[10px] font-bold uppercase tracking-wider block">OWNER EMAIL</span>
                        <span class="font-mono text-primary block mt-0.5 truncate">{{ $tenant->admin ? $tenant->admin->email : Auth::guard('web')->user()->email }}</span>
                    </div>
                </div>
            </div>

            <button onclick="document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.remove('hidden')" 
                    class="w-full py-2.5 px-3 bg-hover-secondary text-primary border-primary border-rounded font-bold text-xs hover-primary transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-key text-[11px]"></i> Reset Tenant Admin Password
            </button>
        </div>

        <!-- CARD 4: FINANCIALS & INVOICES -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4  md:col-span-2 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-amber-500"></i> Financials & Invoices
                </h3>
                <span class="font-mono text-xs font-bold {{ $pendingAmount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                    Pending: ₹{{ number_format($pendingAmount, 2) }}
                </span>
            </div>

            @if($invoices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                                <th class="py-2">Invoice ID</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Amount</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary">
                            @foreach($invoices->take(5) as $invoice)
                                <tr>
                                    <td class="py-2.5 font-mono text-primary font-bold">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-2.5 text-secondary">{{ $invoice->created_at->format('d M Y') }}</td>
                                    <td class="py-2.5 font-mono font-bold text-primary">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="py-2.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $invoice->status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : ($invoice->status === 'pending' ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-tertiary py-2">No invoices found for this workspace.</p>
            @endif
        </div>

        <!-- CARD 5: DANGER ZONE -->
        <div class="p-5 border-rounded bg-rose-500/5 border-rose-500/20 space-y-4  flex flex-col justify-between">
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-rose-600 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                </h3>
                <p class="text-xs text-secondary leading-relaxed">
                    Deleting a workspace permanently drops its database schema, files, and users. This action is irreversible and requires password confirmation.
                </p>
            </div>
            
            <button onclick="document.getElementById('deleteWorkspacePopup-{{ $tenant->id }}').classList.remove('hidden')" 
                    class="w-full py-2.5 px-3 bg-rose-600 text-white border-rounded font-bold text-xs hover:bg-rose-700 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-trash-can text-[11px]"></i> Delete Workspace
            </button>
        </div>

    </div>

    <!-- DOMAIN VERIFY MODAL -->
    @include('arzavo.tenants.domain-verify', ['tenant' => $tenant])

    <!-- RESET PASSWORD MODAL -->
    <div id="resetPasswordPopup-{{ $tenant->id }}" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[999]">
        <div class="bg-primary border-rounded w-full max-w-md p-6 relative border-primary ">
            <button onclick="document.getElementById('resetPasswordPopup-{{ $tenant->id }}').classList.add('hidden')" class="absolute right-4 top-4 text-secondary hover:text-primary text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 class="text-lg font-bold text-primary mb-2">Reset Tenant Admin Password</h3>
            <p class="text-xs text-tertiary mb-4">Enter a new password for the workspace database administrator user.</p>
            
            <form action="{{ route('tenants.reset-password', $tenant->subdomain) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-primary block mb-1">New Password</label>
                    <input type="password" name="password" required minlength="6" class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="text-xs font-semibold text-primary block mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required minlength="6" class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary focus:outline-none focus:border-primary">
                </div>
                <button type="submit" class="w-full py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition">
                    Reset Admin Password
                </button>
            </form>
        </div>
    </div>

    <!-- DELETE WORKSPACE MODAL (WITH PASSWORD CONFIRMATION) -->
    <div id="deleteWorkspacePopup-{{ $tenant->id }}" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-[999]">
        <div class="bg-primary border-rounded w-full max-w-md p-6 relative border-primary ">
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
@endsection
