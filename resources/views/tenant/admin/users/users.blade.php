@extends('layouts.admin')
@section('title', 'Admin Users')
@section('content')

<!-- STATS CARDS -->
<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
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
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-check text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Inactive Users</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $users->where('status', 'inactive')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-times text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Suspended</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $users->where('status', 'suspended')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-slash text-lg text-primary"></i></div>
        </div>
    </div>
</div>

<!-- USERS LIST CONTAINER -->
<div class="bg-primary border-rounded border-primary mt-4">
    <div class="px-4 py-3 flex flex-wrap justify-between items-center gap-3">
        <h3 class="text-primary text-lg font-bold flex items-center gap-2">
            <span><i class="fa-solid fa-users"></i> User Accounts</span>
        </h3>
        <div class="relative">
            <input type="text" id="userSearchInput" onkeyup="filterUsersTable()" placeholder="Search users..." 
                   class="border text-xs p-2 border-primary border-rounded bg-primary text-primary w-64 pr-8">
            <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-tertiary text-xs"></i>
        </div>
    </div>

    <!-- TABLE LAYOUT -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-tertiary">
                    <th class="p-3 pl-4 text-left">User</th>
                    <th class="p-3 text-left">Contact Info</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3 text-center">Role</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center hidden md:table-cell">Joined</th>
                    <th class="p-3 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users->sortByDesc('id') as $user)
                    <tr class="border-top user-row hover:bg-hover-secondary transition">
                        <!-- Profile/Name -->
                        <td class="p-3 pl-4 text-left">
                            <div class="flex items-center gap-3">
                                @if ($user->profile_picture)
                                    <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->fname }}" class="w-8 h-8 rounded-full object-cover border border-primary shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-tertiary text-primary border border-primary">
                                        {{ substr($user->fname, 0, 1) }}{{ substr($user->lname, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-primary text-xs user-fullname">{{ $user->fname }} {{ $user->lname }}</h4>
                                    <p class="text-[10px] text-tertiary font-mono user-username">{{ $user->username }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="p-3 text-left text-xs text-secondary font-mono">
                            <div class="space-y-0.5">
                                <p class="user-email">{{ $user->email }}</p>
                                <p class="text-[10px] text-tertiary">{{ $user->number ?: 'No phone' }}</p>
                            </div>
                        </td>

                        <!-- Location -->
                        <td class="p-3 text-left text-xs text-secondary">
                            {{ $user->city ?: 'N/A' }}{{ $user->state ? ', '.$user->state : '' }}
                        </td>

                        <!-- Role -->
                        <td class="p-3 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-0.5 rounded text-[10px] font-bold text-primary bg-primary hover:bg-hover-secondary uppercase"
                                  onclick="if(confirm('Change user role of {{ $user->fname }} to student?')) { event.preventDefault(); document.getElementById('userRoleForm{{ $user->id }}').submit(); }">
                                {{ $user->role }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="p-3 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-0.5 rounded text-[10px] font-bold uppercase transition
                                  {{ $user->status === 'active' ? 'bg-invert text-invert' : 'bg-tertiary text-primary' }}"
                                  onclick="if(confirm('Change status of {{ $user->fname }}?')) { event.preventDefault(); document.getElementById('userStatusForm{{ $user->id }}').submit(); }">
                                {{ $user->status }}
                            </span>
                        </td>

                        <!-- Joined -->
                        <td class="p-3 text-center text-xs text-tertiary font-mono hidden md:table-cell">
                            {{ $user->created_at ? $user->created_at->format('d-m-Y') : 'N/A' }}
                        </td>

                        <!-- Actions -->
                        <td class="p-3 text-right pr-4">
                            <div class="flex items-center justify-end gap-3">
                                <button onclick="if(confirm('Promote {{ $user->fname }} to Student?')) { document.getElementById('userRoleForm{{ $user->id }}').submit(); }" 
                                        class="text-tertiary hover:text-primary transition text-sm" title="Promote to Student">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </button>
                                <button onclick="if(confirm('Change status of {{ $user->fname }}?')) { document.getElementById('userStatusForm{{ $user->id }}').submit(); }" 
                                        class="text-tertiary hover:text-primary transition text-sm" title="Toggle Status">
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
                        <td colspan="7" class="p-4 text-center text-tertiary text-xs">
                            No registered users found in the system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterUsersTable() {
    let input = document.getElementById("userSearchInput");
    let filter = input.value.toLowerCase();
    let rows = document.getElementsByClassName("user-row");

    for (let i = 0; i < rows.length; i++) {
        let fullname = rows[i].querySelector(".user-fullname").innerText.toLowerCase();
        let username = rows[i].querySelector(".user-username").innerText.toLowerCase();
        let email = rows[i].querySelector(".user-email").innerText.toLowerCase();

        if (fullname.includes(filter) || username.includes(filter) || email.includes(filter)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>

@endsection
