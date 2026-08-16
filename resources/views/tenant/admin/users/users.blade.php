@extends('layouts.admin')
@section('title', 'Admin Users')
@section('content')

<!-- STATS CARDS -->
<div class="statics grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Users</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $users->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-users text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Active Users</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $users->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-check text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Paying Customers</h2>
                <p class="text-2xl font-bold mt-1 text-emerald-600">{{ $payingUsersCount ?? 0 }}</p>
            </div>
            <div class="bg-emerald-500/10 border-rounded p-3"><i class="fas fa-shopping-bag text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Suspended</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $users->where('status', 'suspended')->count() }}</p>
            </div>
            <div class="bg-rose-500/10 border-rounded p-3"><i class="fas fa-user-slash text-lg text-rose-600"></i></div>
        </div>
    </div>
</div>

<!-- USERS LIST CONTAINER -->
<div class="bg-primary border-rounded border-primary mt-4 overflow-hidden">
    <div class="px-4 py-3 border-bottom flex flex-wrap justify-between items-center gap-3 bg-primary">
        <h3 class="text-primary text-base font-bold flex items-center gap-2">
            <i class="fa-solid fa-users text-primary"></i> User Accounts
        </h3>
        <div class="relative w-full sm:w-72">
            <input type="text" id="userSearchInput" onkeyup="filterUsersTable()" placeholder="Search by name, username, email..." 
                   class="border text-xs py-2 px-3 pl-8 border-primary border-rounded bg-primary text-primary w-full input-focus">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-tertiary text-xs"></i>
        </div>
    </div>

    <!-- TABLE LAYOUT -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3.5 pl-4 text-left">User</th>
                    <th class="p-3.5 text-left">Contact Info</th>
                    <th class="p-3.5 text-left">Purchases & Lifetime Value</th>
                    <th class="p-3.5 text-center">Role</th>
                    <th class="p-3.5 text-center">Status</th>
                    <th class="p-3.5 text-center hidden md:table-cell">Joined</th>
                    <th class="p-3.5 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users->sortByDesc('id') as $user)
                    @php
                        $paidOrders = $user->orders ? $user->orders->where('payment_status', 'paid') : collect();
                        $paidOrdersCount = $paidOrders->count();
                        $totalSpent = $paidOrders->sum('grand_total');
                        $entitlementsCount = $user->entitlements ? $user->entitlements->count() : 0;
                    @endphp
                    <tr class="border-bottom user-row hover:bg-hover-secondary transition">
                        <!-- Profile/Name -->
                        <td class="p-3.5 pl-4 text-left">
                            <div class="flex items-center gap-3">
                                @if ($user->profile_picture)
                                    <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->fname }}" class="w-9 h-9 rounded-full object-cover border border-primary shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-secondary text-primary border border-primary">
                                        {{ substr($user->fname ?? 'U', 0, 1) }}{{ substr($user->lname ?? '', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-primary text-xs user-fullname leading-tight">{{ $user->fname }} {{ $user->lname }}</h4>
                                    <p class="text-[10px] text-tertiary font-mono user-username mt-0.5">{{ $user->username }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="p-3.5 text-left text-xs font-mono">
                            <div class="space-y-0.5">
                                <p class="user-email text-primary font-medium">{{ $user->email }}</p>
                                <p class="text-[11px] text-tertiary">{{ $user->number ?: 'No phone' }}</p>
                            </div>
                        </td>

                        <!-- Purchases & Lifetime Value -->
                        <td class="p-3.5 text-left">
                            @if($paidOrdersCount > 0 || $entitlementsCount > 0)
                                <div class="p-2.5 rounded-xl bg-secondary/40 border border-primary space-y-1.5 min-w-[210px]">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-500/15 text-emerald-600 border border-emerald-500/20 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-bag-shopping text-[9px]"></i>
                                            {{ $paidOrdersCount }} Paid Order{{ $paidOrdersCount > 1 ? 's' : '' }}
                                        </span>
                                        <span class="text-xs font-mono font-bold text-primary">₹{{ number_format($totalSpent, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-secondary border-t border-primary/50 pt-1">
                                        <span>
                                            <i class="fa-solid fa-book-bookmark text-indigo-500 mr-0.5"></i>
                                            {{ $entitlementsCount }} Digital Access
                                        </span>
                                        <a href="{{ route('admin.finance.orders', ['search' => $user->email]) }}" 
                                           class="font-bold text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-0.5">
                                            Orders <i class="fa-solid fa-arrow-right text-[8px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <span class="px-2.5 py-1 text-[11px] font-medium text-tertiary bg-secondary/30 rounded-lg border border-primary/50 inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-basket-shopping text-tertiary/60 text-[10px]"></i> No Purchases
                                </span>
                            @endif
                        </td>

                        <!-- Role -->
                        <td class="p-3.5 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-1 rounded-lg text-[10px] font-bold text-primary bg-secondary hover:bg-hover-secondary uppercase transition"
                                  title="Click to promote to student"
                                  onclick="if(confirm('Change user role of {{ $user->fname }} to Student?')) { event.preventDefault(); document.getElementById('userRoleForm{{ $user->id }}').submit(); }">
                                {{ $user->role }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="p-3.5 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase transition
                                  {{ $user->status === 'active' ? 'bg-invert text-invert' : 'bg-secondary text-primary' }}"
                                  title="Click to toggle status"
                                  onclick="if(confirm('Change status of {{ $user->fname }}?')) { event.preventDefault(); document.getElementById('userStatusForm{{ $user->id }}').submit(); }">
                                {{ $user->status }}
                            </span>
                        </td>

                        <!-- Joined -->
                        <td class="p-3.5 text-center text-xs text-tertiary font-mono hidden md:table-cell">
                            {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right pr-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.finance.orders', ['search' => $user->email]) }}" 
                                   class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                   title="View Customer Order History">
                                    <i class="fa-solid fa-receipt"></i>
                                </a>
                                <button onclick="if(confirm('Promote {{ $user->fname }} to Student?')) { document.getElementById('userRoleForm{{ $user->id }}').submit(); }" 
                                        class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                        title="Promote to Student">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </button>
                                <button onclick="if(confirm('Change status of {{ $user->fname }}?')) { document.getElementById('userStatusForm{{ $user->id }}').submit(); }" 
                                        class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                        title="Toggle User Status">
                                    <i class="fa-solid fa-user-slash"></i>
                                </button>
                            </div>

                            <form id="userRoleForm{{ $user->id }}" action="{{ route('admin.update-user-role') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                            </form>
                            <form id="userStatusForm{{ $user->id }}" action="{{ route('admin.update-user-status') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-tertiary text-xs">
                            <i class="fa-solid fa-users text-2xl mb-2 text-tertiary block opacity-50"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterUsersTable() {
    let input = document.getElementById('userSearchInput');
    let filter = input.value.toLowerCase();
    let rows = document.querySelectorAll('.user-row');

    rows.forEach(row => {
        let name = row.querySelector('.user-fullname') ? row.querySelector('.user-fullname').innerText.toLowerCase() : '';
        let username = row.querySelector('.user-username') ? row.querySelector('.user-username').innerText.toLowerCase() : '';
        let email = row.querySelector('.user-email') ? row.querySelector('.user-email').innerText.toLowerCase() : '';

        if (name.includes(filter) || username.includes(filter) || email.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

@endsection
