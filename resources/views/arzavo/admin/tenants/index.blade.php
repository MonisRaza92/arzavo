@extends('layouts.arzavo')
@section('title', 'Tenants Management')

@section('content')

    @include('arzavo.admin.tenants.header')

    {{-- LIST --}}
    <div class="flex flex-col gap-3">

        @forelse($tenants as $tenant)

            <div class="bg-primary border-primary border-rounded p-4 hover:bg-hover-secondary transition group">

                <div class="flex justify-between items-start gap-4">

                    {{-- LEFT --}}
                    <div class="flex flex-col flex-1">

                        {{-- TOP ROW --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            <h3 class="font-semibold text-sm text-primary">
                                {{ $tenant->name }}
                            </h3>

                            {{-- STATUS --}}
                            <span class="text-[10px] px-2 py-0.5 rounded-full
                                {{ $tenant->status === 'active'
                                    ? 'bg-green-500/10 text-green-600'
                                    : 'bg-red-500/10 text-red-600' }}">
                                {{ ucfirst($tenant->status) }}
                            </span>

                            {{-- DOMAIN VERIFIED --}}
                            @if($tenant->domain_verified)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600">
                                    Verified
                                </span>
                            @endif

                            {{-- DELETE ON EXPIRY BADGE --}}
                            @if($tenant->subscription && $tenant->subscription->delete_on_expiry)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500/15 text-red-600 font-medium">
                                    <i class="fa-solid fa-fire text-[9px] mr-0.5"></i> Auto-Delete on Expiry
                                </span>
                            @endif

                        </div>

                        {{-- DOMAIN --}}
                        <div class="text-xs text-tertiary mt-1 break-all">
                            {{ $tenant->url ?? 'No domain configured' }}
                        </div>

                        {{-- ADMIN --}}
                        <div class="text-xs text-tertiary mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-user text-[10px]"></i>
                            <span>
                                {{ $tenant->admin->full_name ?? '—' }}
                                <span class="text-tertiary">
                                    ({{ $tenant->admin->email ?? '—' }})
                                </span>
                            </span>
                        </div>

                        {{-- META --}}
                        <div class="flex flex-wrap gap-3 mt-3 text-[11px]">

                            {{-- PLAN --}}
                            <span class="px-2 py-0.5 border-rounded font-medium
                                {{ $tenant->subscription && $tenant->subscription->plan
                                    ? 'bg-blue-500/10 text-blue-600'
                                    : 'bg-gray-500/10 text-gray-400' }}">
                                <i class="fa-solid fa-credit-card mr-1 text-[10px]"></i>
                                {{ $tenant->subscription?->plan?->name ?? 'No Plan Assigned' }}
                            </span>

                            {{-- EXPIRY / STATUS --}}
                            @if($tenant->subscription)
                                <span class="text-tertiary flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    @if($tenant->subscription->ends_at)
                                        Expires: {{ $tenant->subscription->ends_at->format('d M Y') }}
                                        ({{ $tenant->subscription->ends_at->isPast() ? 'Expired' : $tenant->subscription->ends_at->diffForHumans() }})
                                    @else
                                        Lifetime Access
                                    @endif
                                </span>
                            @endif

                            {{-- CREATED --}}
                            <span class="text-tertiary flex items-center gap-1">
                                <i class="fa-solid fa-calendar text-[10px]"></i>
                                {{ $tenant->created_at->format('d M Y') }}
                            </span>

                        </div>

                    </div>

                    {{-- RIGHT ACTIONS --}}
                    <div class="flex flex-col gap-2 items-end">

                        {{-- VISIT --}}
                        @if($tenant->url)
                            <a href="{{ $tenant->url }}" target="_blank"
                                class="px-3 py-1 text-xs w-28 text-center border-primary border-rounded hover-primary">
                                Website
                            </a>
                        @endif

                        {{-- ASSIGN / CHANGE PLAN --}}
                        <button type="button" onclick="openAssignPlanModal({{ json_encode([
                            'id' => $tenant->id,
                            'name' => $tenant->name,
                            'plan_id' => $tenant->subscription?->plan_id,
                            'status' => $tenant->subscription?->status ?? 'active',
                            'custom_price' => $tenant->subscription?->custom_price,
                            'delete_on_expiry' => (bool) ($tenant->subscription?->delete_on_expiry ?? false),
                            'ends_at' => $tenant->subscription?->ends_at ? $tenant->subscription->ends_at->format('Y-m-d') : null,
                        ]) }})"
                            class="px-3 py-1 text-xs w-28 bg-invert text-invert border-rounded hover-invert text-center cursor-pointer">
                            Assign Plan
                        </button>

                        {{-- TOGGLE STATUS --}}
                        <form action="{{ route('arzavo.admin.tenants.update', $tenant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <button class="px-3 py-1 text-xs w-28 bg-tertiary border-primary border-rounded hover-primary cursor-pointer">
                                {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>

                        {{-- DELETE --}}
                        <form action="{{ route('arzavo.admin.tenants.destroy', $tenant->id) }}" method="POST"
                            onsubmit="return confirm('Delete this tenant and all its data?')">
                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 text-xs w-28 bg-red-500/10 text-red-600 border border-red-500/20 border-rounded hover:bg-red-500/20 cursor-pointer">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-primary border-primary border-rounded p-6 text-center text-tertiary">
                No tenants found
            </div>

        @endforelse

    </div>

    {{-- ASSIGN PLAN MODAL --}}
    <div id="assignPlanModal" class="fixed inset-0 flex bg-black/80 hidden items-center justify-center z-100 p-4">
        <div class="modal-content bg-primary border-primary border-rounded w-full max-w-lg shadow-2xl p-6 space-y-5">
            
            <div class="flex justify-between items-start pb-3 border-bottom">
                <div>
                    <h2 class="text-lg font-bold text-primary">Assign / Change Plan</h2>
                    <p id="assignTenantName" class="text-xs text-tertiary mt-0.5">Manage subscription & access for tenant</p>
                </div>
                <button type="button" onclick="closeModel('assignPlanModal')" class="text-tertiary hover:text-primary text-lg cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="assignPlanForm" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1">Select Plan</label>
                    <select name="plan_id" id="assign_plan_id" class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                        <option value="">-- Choose a Plan --</option>
                        @foreach($plans as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} (₹{{ number_format($p->monthly_price, 0) }}/mo)
                                @if($p->is_hidden) [🔒 Admin Only] @endif
                                @if($p->is_coming_soon) [⏳ Coming Soon] @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Status</label>
                        <select name="status" id="assign_status" class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                            <option value="active">Active</option>
                            <option value="trial">Trial</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1">Duration</label>
                        <select name="duration_type" id="assign_duration_type" onchange="toggleCustomEndDate(this.value)" class="input border-primary border-rounded p-2.5 bg-primary text-xs w-full text-primary" required>
                            <option value="7_days">7 Days Trial</option>
                            <option value="1_month" selected>1 Month (30 Days)</option>
                            <option value="3_months">3 Months</option>
                            <option value="6_months">6 Months</option>
                            <option value="1_year">1 Year (365 Days)</option>
                            <option value="lifetime">Lifetime (Unlimited)</option>
                            <option value="custom">Custom Date</option>
                        </select>
                    </div>
                </div>

                <div id="customDateContainer" class="hidden">
                    <label class="text-xs font-semibold text-secondary block mb-1">Custom Expiry Date</label>
                    <input type="date" name="custom_ends_at" id="assign_custom_ends_at" class="input border-primary border-rounded p-2 bg-primary text-xs w-full text-primary">
                </div>

                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1">Custom Price Override (Optional ₹)</label>
                    <input type="number" step="0.01" name="custom_price" id="assign_custom_price" placeholder="Leave empty for standard plan price"
                        class="input border-primary border-rounded p-2 bg-primary text-xs w-full text-primary">
                </div>

                {{-- AUTO DELETE TOGGLE --}}
                <div class="flex items-center justify-between border-primary border-rounded p-3 bg-secondary">
                    <div class="pr-3">
                        <p class="font-semibold text-xs text-primary flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can text-red-500"></i> Auto-Delete on Expiry
                        </p>
                        <p class="text-[11px] text-tertiary">
                            Automatically delete this tenant as soon as the plan expires.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="delete_on_expiry" id="assign_delete_on_expiry" value="1" class="sr-only peer">
                        <div class="w-9 h-5 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-top">
                    <button type="button" onclick="closeModel('assignPlanModal')" class="px-4 py-2 text-xs border-primary border-rounded bg-primary text-secondary hover-primary">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs bg-invert text-invert border-rounded hover-invert font-semibold">
                        Confirm & Assign
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
    function openAssignPlanModal(tenant) {
        const form = document.getElementById('assignPlanForm');
        form.action = `/tenants/${tenant.id}/assign-plan`;

        document.getElementById('assignTenantName').innerText = `Tenant: ${tenant.name}`;
        document.getElementById('assign_plan_id').value = tenant.plan_id || '';
        document.getElementById('assign_status').value = tenant.status || 'active';
        document.getElementById('assign_custom_price').value = tenant.custom_price || '';
        document.getElementById('assign_delete_on_expiry').checked = Boolean(tenant.delete_on_expiry);

        if (tenant.ends_at) {
            document.getElementById('assign_duration_type').value = 'custom';
            document.getElementById('assign_custom_ends_at').value = tenant.ends_at;
            document.getElementById('customDateContainer').classList.remove('hidden');
        } else {
            document.getElementById('assign_duration_type').value = '1_month';
            document.getElementById('customDateContainer').classList.add('hidden');
        }

        openModel('assignPlanModal');
    }

    function toggleCustomEndDate(val) {
        const container = document.getElementById('customDateContainer');
        if (val === 'custom') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
    </script>

@endsection