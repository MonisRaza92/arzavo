@extends('layouts.arzavo.admin')
@section('title', 'Users Management')

@section('content')
<div x-data="{
    filter: 'all',
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    selectedUser: null,
    userToDelete: null,
    openEdit(u) {
        this.selectedUser = u;
        this.editModalOpen = true;
        this.$nextTick(() => {
            const form = document.getElementById('editUserForm');
            if (form) form.action = `/users/${u.id}`;
            const nameInput = document.getElementById('edit_user_name');
            if (nameInput) nameInput.value = u.name || (u.fname + ' ' + (u.lname || '')).trim();
            const emailInput = document.getElementById('edit_user_email');
            if (emailInput) emailInput.value = u.email || '';
            const roleSelect = document.getElementById('edit_user_role');
            if (roleSelect) roleSelect.value = u.role || 'user';
            const statusSelect = document.getElementById('edit_user_status');
            if (statusSelect) statusSelect.value = u.status || 'active';
            const pwdInput = document.getElementById('edit_user_password');
            if (pwdInput) pwdInput.value = '';
        });
    },
    openDelete(u) {
        this.userToDelete = u;
        this.deleteModalOpen = true;
    }
}" class="space-y-6">

    <!-- 1. HERO HEADER WITH LIVE SEARCH & ACTIONS -->
    <div class="rounded-lg bg-primary p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-normal bg-brand/10 text-brand">
                    Identity & Access
                </span>
                <span class="text-xs text-theme-text-muted">
                    • {{ $users->count() }} Total Registered Accounts
                </span>
            </div>
            <h1 class="text-2xl font-semibold text-theme-text leading-tight">
                Platform Users & Administrators
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 max-w-2xl leading-relaxed">
                Oversee centralized accounts, manage user roles, audit multi-tenant ownership, and maintain account security across the ecosystem.
            </p>

            <!-- Search Feedback Strip -->
            <div x-show="search && search.length > 0" class="mt-2 text-xs text-theme-text-muted">
                Showing matching accounts for <span class="text-theme-text font-medium" x-text="'&ldquo;' + search + '&rdquo;'"></span>
                <button type="button" @click="search = ''" class="ml-2 text-brand hover:underline">Clear search</button>
            </div>
        </div>

        <!-- Action: Create User -->
        <button type="button" @click="createModalOpen = true"
                class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add User</span>
        </button>
    </div>

    <!-- 2. FILTER PILLS BAR -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <button type="button" @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            All Users ({{ $users->count() }})
        </button>
        <button type="button" @click="filter = 'admins'"
                :class="filter === 'admins' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Admins ({{ $users->whereIn('role', ['admin', 'super_admin'])->count() }})
        </button>
        <button type="button" @click="filter = 'standard'"
                :class="filter === 'standard' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Standard Users ({{ $users->where('role', 'user')->count() }})
        </button>
        <button type="button" @click="filter = 'active'"
                :class="filter === 'active' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Active ({{ $users->where('status', 'active')->count() }})
        </button>
        <button type="button" @click="filter = 'suspended'"
                :class="filter === 'suspended' ? 'bg-brand text-primary' : 'bg-primary text-theme-text-muted hover:text-theme-text hover:bg-subtle'"
                class="px-4 py-2 rounded-full text-xs font-normal transition shrink-0 cursor-pointer">
            Suspended ({{ $users->where('status', 'suspended')->count() }})
        </button>
    </div>

    <!-- 3. USERS TABLE LIST VIEW -->
    <div class="rounded-lg bg-primary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-theme-text">
                <thead class="bg-secondary text-[11px] text-theme-text-muted uppercase tracking-wider font-normal">
                    <tr>
                        <th class="p-4 pl-6">User Account</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Institutes</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Joined</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary">
                    @forelse($users as $user)
                        @php
                            $isSelf = $user->id === Auth::guard('web')->user()->id;
                            $isAdminRole = in_array($user->role, ['admin', 'super_admin']);
                        @endphp
                        <tr x-show="(!search || '{{ strtolower(($user->full_name ?? $user->name) . ' ' . $user->email . ' ' . $user->username) }}'.includes(search.toLowerCase())) &&
                                     (filter === 'all' || 
                                      (filter === 'admins' && {{ $isAdminRole ? 'true' : 'false' }}) || 
                                      (filter === 'standard' && '{{ $user->role }}' === 'user') || 
                                      (filter === 'active' && '{{ $user->status }}' === 'active') || 
                                      (filter === 'suspended' && '{{ $user->status }}' === 'suspended'))">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    {{-- MANUAL USER IMAGE / INITIAL FALLBACK (NEVER DEFAULT TO LOGGED-IN USER) --}}
                                    @if(!empty($user->profile_picture))
                                        <img src="{{ media($user->profile_picture) }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-brand text-primary flex items-center justify-center text-xs font-semibold shrink-0 uppercase">
                                            {{ strtoupper(substr($user->fname ?? ($user->name ?? 'U'), 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-semibold text-theme-text truncate">{{ $user->full_name ?? ($user->name ?? 'User') }}</p>
                                            @if($isSelf)
                                                <span class="text-[9px] px-1.5 py-0.2 rounded-full bg-brand text-primary">You</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-theme-text-muted truncate">{{ '@' . ($user->username ?? 'user') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-theme-text">{{ $user->email }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] {{ $isAdminRole ? 'bg-brand/10 text-brand' : 'bg-secondary text-theme-text-muted' }}">
                                    {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                </span>
                            </td>
                            <td class="p-4 text-theme-text">
                                <span class="px-2.5 py-1 rounded-full bg-secondary text-theme-text text-[11px] font-normal">
                                    {{ $user->tenants_count ?? $user->tenants->count() }} {{ Str::plural('tenant', $user->tenants_count ?? $user->tenants->count()) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] {{ $user->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-theme-text-muted">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="p-4 pr-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" @click="openEdit({{ json_encode($user) }})"
                                            class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle transition text-xs font-normal cursor-pointer">
                                        Edit
                                    </button>
                                    @if(!$isSelf)
                                        <form action="{{ route('arzavo.admin.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="toggle_status" value="1">
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-full text-xs font-normal transition cursor-pointer {{ $user->status === 'active' ? 'bg-secondary text-warning hover:bg-subtle' : 'bg-success-bg text-success' }}">
                                                {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                        @if($user->role !== 'super_admin')
                                            <button type="button" @click="openDelete({{ json_encode($user) }})"
                                                    class="p-2 rounded-full bg-secondary text-danger hover:bg-danger-bg transition text-xs cursor-pointer"
                                                    title="Delete User">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-theme-text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. CREATE USER MODAL (Rule 4.2: z-[999] dialog exception) -->
    <div x-show="createModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-lg p-6 space-y-5 my-auto relative"
             @click.away="createModalOpen = false">

            <div class="flex items-start justify-between pb-2">
                <div>
                    <h3 class="text-base font-semibold text-theme-text">Create Platform User</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5">Provision an account with specified access credentials.</p>
                </div>
                <button type="button" @click="createModalOpen = false" class="text-theme-text-muted hover:text-theme-text text-sm p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('arzavo.admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Full Name</label>
                    <input type="text" name="name" required placeholder="e.g. John Doe"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Email Address</label>
                    <input type="email" name="email" required placeholder="name@domain.com"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Initial Password (Min 6 characters)</label>
                    <input type="password" name="password" required minlength="6" placeholder="••••••••"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Account Role</label>
                        <select name="role" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            <option value="user" selected>Standard User</option>
                            <option value="admin">Administrator</option>
                            <option value="super_admin">Super Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Initial Status</label>
                        <select name="status" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            <option value="active" selected>Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="createModalOpen = false"
                            class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. EDIT USER MODAL (Rule 4.2: z-[999] dialog exception) -->
    <div x-show="editModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-lg p-6 space-y-5 my-auto relative"
             @click.away="editModalOpen = false">

            <div class="flex items-start justify-between pb-2">
                <div>
                    <h3 class="text-base font-semibold text-theme-text">Modify User Account</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5" x-text="selectedUser ? 'Updating account details for ' + selectedUser.email : ''"></p>
                </div>
                <button type="button" @click="editModalOpen = false" class="text-theme-text-muted hover:text-theme-text text-sm p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editUserForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Full Name</label>
                    <input type="text" name="name" id="edit_user_name" required
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Email Address</label>
                    <input type="email" name="email" id="edit_user_email" required
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div>
                    <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Reset Password (Leave blank to keep current)</label>
                    <input type="password" name="password" id="edit_user_password" minlength="6" placeholder="Enter new password if updating..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Account Role</label>
                        <select name="role" id="edit_user_role" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            <option value="user">Standard User</option>
                            <option value="admin">Administrator</option>
                            <option value="super_admin">Super Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-normal text-theme-text-muted block mb-1.5">Account Status</label>
                        <select name="status" id="edit_user_status" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="editModalOpen = false"
                            class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. DELETE USER CONFIRMATION MODAL (Rule 4.2: z-[999] dialog exception) -->
    <div x-show="deleteModalOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/70 z-[999] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        <div class="rounded-lg bg-primary shadow-2xl w-full max-w-md p-6 space-y-4 my-auto relative"
             @click.away="deleteModalOpen = false">

            <div class="w-12 h-12 rounded-full bg-danger-bg text-danger flex items-center justify-center text-lg mx-auto">
                <i class="fa-solid fa-user-xmark"></i>
            </div>

            <div class="text-center">
                <h3 class="text-base font-semibold text-theme-text">Delete User Account?</h3>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed">
                    Are you sure you want to delete <span class="font-semibold text-theme-text" x-text="userToDelete ? userToDelete.email : ''"></span>? Their academy associations and sessions will be revoked.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>
                <form :action="userToDelete ? '/users/' + userToDelete.id : '#'" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-danger text-white rounded-full font-normal text-xs transition hover:opacity-90 cursor-pointer">
                        Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection