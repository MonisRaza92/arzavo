@extends('layouts.admin')
@section('title', 'Users Directory')

@section('content')
<div x-data="usersManagement()" class="space-y-6">

    {{-- TOP BANNER / HEADER (CONSISTENT CURRICULUM ARCHITECTURE HEADER) --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Users Directory
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Standard registered users on your academy portal. Promote any active user to an enrolled student with curriculum tiers and fee plan.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.students') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-user-graduate text-[11px] text-brand"></i>
                <span>Students Directory</span>
            </a>
            <span class="px-4 py-2.5 rounded-full text-xs font-normal bg-secondary text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-user-check text-brand text-[11px]"></i>
                <span x-text="filteredUsers.length + ' Displayed'"></span>
            </span>
        </div>
    </div>

    {{-- STATS CARDS (FLAT, NO BORDERS, THEME TOKENS, MAX FONT-SEMIBOLD) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total Users</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $users->count() }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Registered accounts</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Active Users</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $users->where('status', 'active')->count() }}</p>
                <p class="text-[11px] text-success mt-0.5 font-normal flex items-center gap-1">
                    <i class="fa-solid fa-circle text-[6px]"></i> Ready to enroll
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Digital Buyers</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $payingUsersCount ?? 0 }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Purchased books / courses</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Suspended</p>
                <p class="text-2xl font-semibold mt-1 text-danger">{{ $users->where('status', 'suspended')->count() }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Disabled accounts</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-danger-bg flex items-center justify-center text-danger text-lg">
                <i class="fa-solid fa-user-slash"></i>
            </div>
        </div>
    </div>

    {{-- USERS DIRECTORY CONTAINER (FLAT, NO BORDERS, ROUNDED-LG) --}}
    <div class="rounded-lg bg-primary p-5 space-y-4">
        {{-- TOOLBAR: SEARCH & STATUS FILTER --}}
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Single Live Search Bar (py-3, rounded-full, bg-secondary, NO border) --}}
                <div class="relative w-full sm:w-80">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Search by name, email, phone or username..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-10 rounded-full focus:outline-none font-normal">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
                </div>

                {{-- Status Filter Pills (rounded-full, bg-secondary, NO border) --}}
                <div class="flex items-center gap-1 bg-secondary p-1 rounded-full text-xs">
                    <button type="button"
                            @click="statusFilter = 'all'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition"
                            :class="statusFilter === 'all' ? 'bg-primary text-brand font-semibold' : 'text-theme-text-muted hover:text-theme-text'">
                        All
                    </button>
                    <button type="button"
                            @click="statusFilter = 'active'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition"
                            :class="statusFilter === 'active' ? 'bg-primary text-brand font-semibold' : 'text-theme-text-muted hover:text-theme-text'">
                        Active
                    </button>
                    <button type="button"
                            @click="statusFilter = 'suspended'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition"
                            :class="statusFilter === 'suspended' ? 'bg-primary text-brand font-semibold' : 'text-theme-text-muted hover:text-theme-text'">
                        Suspended
                    </button>
                </div>
            </div>

            <div class="text-xs text-theme-text-muted font-normal text-right">
                Showing <span class="font-semibold text-theme-text" x-text="filteredUsers.length"></span> of <span class="font-semibold text-theme-text">{{ $users->count() }}</span> users
            </div>
        </div>

        {{-- SEARCH FEEDBACK STRIP (SECTION 6) --}}
        <div x-show="searchQuery.trim().length > 0" class="p-3.5 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text font-normal">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-brand text-xs"></i>
                <span>Showing results for <span class="font-semibold text-theme-text" x-text="'&ldquo;' + searchQuery + '&rdquo;'"></span> &middot; <span x-text="filteredUsers.length"></span> matches found</span>
            </div>
            <button type="button" @click="searchQuery = ''" class="text-xs text-theme-text-muted hover:text-theme-text font-normal cursor-pointer">
                Clear
            </button>
        </div>

        {{-- TABLE LAYOUT (CLEAN CONTRAST, NO BORDERS) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal">
                        <th class="py-3 px-4 font-normal rounded-l-lg">User Profile</th>
                        <th class="py-3 px-4 font-normal">Contact Info</th>
                        <th class="py-3 px-4 font-normal">Purchases</th>
                        <th class="py-3 px-4 font-normal text-center">Status</th>
                        <th class="py-3 px-4 font-normal text-center hidden md:table-cell">Joined</th>
                        <th class="py-3 pr-4 font-normal text-right rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr class="hover:bg-secondary transition">
                            {{-- Profile / Name --}}
                            <td class="py-3 px-4 text-left">
                                <div class="flex items-center gap-3">
                                    <template x-if="user.profile_picture">
                                        <img :src="resolveMedia(user.profile_picture)" :alt="user.fname" class="w-9 h-9 rounded-full object-cover shrink-0">
                                    </template>
                                    <template x-if="!user.profile_picture">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs uppercase shrink-0 bg-secondary text-brand font-semibold">
                                            <span x-text="user.fname.charAt(0) + (user.lname ? user.lname.charAt(0) : '')"></span>
                                        </div>
                                    </template>
                                    <div>
                                        <h4 class="font-semibold text-theme-text text-xs leading-tight" x-text="user.fname + ' ' + (user.lname || '')"></h4>
                                        <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal" x-text="'@' + user.username"></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact Details --}}
                            <td class="py-3 px-4 text-left">
                                <div class="space-y-0.5">
                                    <p class="text-theme-text font-normal flex items-center gap-1.5">
                                        <i class="fa-regular fa-envelope text-theme-text-muted text-[10px]"></i>
                                        <span x-text="user.email"></span>
                                    </p>
                                    <p class="text-theme-text-muted font-normal flex items-center gap-1.5">
                                        <i class="fa-solid fa-phone text-theme-text-muted text-[10px]"></i>
                                        <span x-text="user.number || 'No phone provided'"></span>
                                    </p>
                                </div>
                            </td>

                            {{-- Purchases / Value --}}
                            <td class="py-3 px-4 text-left">
                                <template x-if="user.paid_orders_count > 0 || user.entitlements_count > 0">
                                    <div class="px-3 py-1.5 rounded-full bg-secondary inline-flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-success-bg text-success font-normal inline-flex items-center gap-1">
                                            <i class="fa-solid fa-bag-shopping text-[9px]"></i>
                                            <span x-text="user.paid_orders_count + ' Orders'"></span>
                                        </span>
                                        <span class="text-xs font-normal text-theme-text" x-text="'₹' + user.total_spent.toFixed(2)"></span>
                                    </div>
                                </template>
                                <template x-if="!user.paid_orders_count && !user.entitlements_count">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-normal text-theme-text-muted bg-secondary">
                                        No purchases
                                    </span>
                                </template>
                            </td>

                            {{-- Status Badge (Semantic tokens, font-normal, rounded-full) --}}
                            <td class="py-3 px-4 text-center">
                                <button type="button"
                                        @click="toggleStatus(user)"
                                        class="px-2.5 py-0.5 rounded-full text-[11px] font-normal transition cursor-pointer"
                                        :class="user.status === 'active' ? 'bg-success-bg text-success hover:opacity-80' : 'bg-danger-bg text-danger hover:opacity-80'"
                                        x-text="user.status === 'active' ? 'Active' : 'Suspended'">
                                </button>
                            </td>

                            {{-- Joined --}}
                            <td class="py-3 px-4 text-center text-theme-text-muted font-normal hidden md:table-cell" x-text="user.joined_formatted"></td>

                            {{-- Actions --}}
                            <td class="py-3 pr-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Primary CTA Button (bg-brand text-primary rounded-full font-normal) --}}
                                    <button type="button"
                                            @click="openPromoteModal(user)"
                                            class="px-4 py-2 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5"
                                            title="Promote this user to Student">
                                        <i class="fa-solid fa-graduation-cap text-[11px]"></i>
                                        <span>Promote to Student</span>
                                    </button>

                                    {{-- Secondary Action Button (bg-secondary text-theme-text rounded-full) --}}
                                    <button type="button"
                                            @click="toggleStatus(user)"
                                            class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle transition flex items-center justify-center text-xs"
                                            :title="user.status === 'active' ? 'Suspend User' : 'Activate User'">
                                        <i class="fa-solid fa-user-slash text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="filteredUsers.length === 0">
                        <td colspan="6" class="py-12 text-center text-theme-text-muted">
                            <i class="fa-solid fa-users text-3xl mb-2 text-theme-text-muted/40 block"></i>
                            <p class="text-xs font-normal">No users found matching your criteria.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- PROMOTE USER TO STUDENT MODAL (SHADOW-2XL POPUP EXCEPTION) --}}
    {{-- ======================================================== --}}
    <div x-cloak
         x-show="promoteModalOpen"
         @keydown.escape.window="promoteModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="promoteModalOpen = false"
             class="relative w-full max-w-xl rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header (NO BORDERS) --}}
            <div class="px-6 py-5 bg-primary flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-base">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-theme-text">Promote User to Student</h3>
                        <p class="text-xs text-theme-text-muted font-normal">Assign curriculum tiers, batch and fee plan</p>
                    </div>
                </div>
                <button type="button" @click="promoteModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            {{-- Modal Form (Flat fields, rounded-full inputs, py-3) --}}
            <form action="{{ route('admin.users.promote') }}" method="POST" class="flex-1 overflow-y-auto px-6 pb-6 space-y-5 scrollbar">
                @csrf
                <input type="hidden" name="id" :value="selectedUser ? selectedUser.id : ''">

                {{-- USER SUMMARY BADGE (Inner Sub-block bg-secondary) --}}
                <div class="p-4 rounded-lg bg-secondary flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                        <span x-text="selectedUser ? selectedUser.fname.charAt(0) : 'U'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-xs text-theme-text truncate" x-text="selectedUser ? (selectedUser.fname + ' ' + (selectedUser.lname || '')) : ''"></p>
                        <p class="text-[11px] text-theme-text-muted truncate font-normal" x-text="selectedUser ? (selectedUser.email + ' • ' + (selectedUser.number || 'No phone')) : ''"></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[11px] bg-brand text-primary font-normal uppercase shrink-0">
                        User Account
                    </span>
                </div>

                {{-- SECTION 1: ACADEMIC PLACEMENT (CASCADING DIRECTLY FROM CURRICULUM) --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-layer-group"></i> 1. Academic Placement
                    </h4>

                    {{-- 1. Academic Category --}}
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Academic Category</label>
                        <select name="academic_category_id"
                                x-model="selectedCategoryId"
                                @change="onCategoryChange()"
                                required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="">Select Category</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- 2. Class / Course --}}
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Class / Course</label>
                        <select name="class_id"
                                x-model="selectedClassId"
                                @change="onClassChange()"
                                :disabled="!availableClasses.length"
                                required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                            <option value="" x-text="availableClasses.length ? 'Select Class' : 'Select Category first'"></option>
                            <template x-for="cls in availableClasses" :key="cls.id">
                                <option :value="cls.id" x-text="cls.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- 3. Subject --}}
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Subject</label>
                            <select name="subject_id"
                                    x-model="selectedSubjectId"
                                    @change="onSubjectChange()"
                                    :disabled="!availableSubjects.length"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                                <option value="" x-text="availableSubjects.length ? 'All Subjects / Primary' : 'Select Class first'"></option>
                                <template x-for="sub in availableSubjects" :key="sub.id">
                                    <option :value="sub.id" x-text="sub.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 4. Batch with Teacher & Timing --}}
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Assign Batch</label>
                            <select name="batch_id"
                                    x-model="selectedBatchId"
                                    :disabled="!availableBatches.length"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                                <option value="" x-text="availableBatches.length ? 'Select Batch' : 'Select Subject first'"></option>
                                <template x-for="b in availableBatches" :key="b.id">
                                    <option :value="b.id" x-text="b.name + ' (' + (b.teacher ? b.teacher.fname : 'No Teacher') + ' • ' + (b.start_time || 'TBD') + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: IDENTIFICATION (OPTIONAL) --}}
                <div class="space-y-3 pt-1">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-id-card"></i> 2. Identification (Optional)
                    </h4>
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Aadhaar / National ID</label>
                        <input type="text"
                               name="aadhaar_number"
                               placeholder="e.g. 1234 5678 9012"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                {{-- SECTION 3: FEE PLAN CONFIGURATION --}}
                <div class="space-y-3 pt-1">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-wallet"></i> 3. Fee Plan Setup (Optional)
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Billing Cycle</label>
                            <select name="fee_plan_type" class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                                <option value="onetime">One-Time</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Fee Amount (₹)</label>
                            <input type="number"
                                   step="0.01"
                                   name="fee_amount"
                                   placeholder="e.g. 2500"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Due Day (1-31)</label>
                            <input type="number"
                                   name="fee_due_day"
                                   value="10"
                                   min="1"
                                   max="31"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer (Standard Component Buttons) --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="promoteModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        <span>Promote &amp; Enroll Student</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden form for status toggling --}}
    <form id="userStatusToggleForm" action="{{ route('admin.update-user-status') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="id" id="statusToggleUserId">
    </form>

</div>

<script>
    function usersManagement() {
        return {
            users: @json($usersData),
            categories: @json($categories),

            searchQuery: '',
            statusFilter: 'all',

            // Modal state
            promoteModalOpen: false,
            selectedUser: null,

            // Cascader state
            selectedCategoryId: '',
            selectedClassId: '',
            selectedSubjectId: '',
            selectedBatchId: '',

            get filteredUsers() {
                const query = this.searchQuery.toLowerCase().trim();
                return this.users.filter(u => {
                    // Status filter
                    if (this.statusFilter !== 'all' && u.status !== this.statusFilter) {
                        return false;
                    }
                    // Search query
                    if (!query) return true;
                    const fullName = (u.fname + ' ' + (u.lname || '')).toLowerCase();
                    const email = (u.email || '').toLowerCase();
                    const phone = (u.number || '').toLowerCase();
                    const username = (u.username || '').toLowerCase();
                    return fullName.includes(query) || email.includes(query) || phone.includes(query) || username.includes(query);
                });
            },

            get selectedCategory() {
                if (!this.selectedCategoryId) return null;
                return this.categories.find(c => c.id == this.selectedCategoryId) || null;
            },
            get availableClasses() {
                return this.selectedCategory ? (this.selectedCategory.class_courses || []) : [];
            },
            get selectedClass() {
                if (!this.selectedClassId) return null;
                return this.availableClasses.find(cls => cls.id == this.selectedClassId) || null;
            },
            get availableSubjects() {
                return this.selectedClass ? (this.selectedClass.subjects || []) : [];
            },
            get selectedSubject() {
                if (!this.selectedSubjectId) return null;
                return this.availableSubjects.find(sub => sub.id == this.selectedSubjectId) || null;
            },
            get availableBatches() {
                return this.selectedSubject ? (this.selectedSubject.batches || []) : [];
            },

            onCategoryChange() {
                this.selectedClassId = '';
                this.selectedSubjectId = '';
                this.selectedBatchId = '';
                if (this.availableClasses.length > 0) {
                    this.selectedClassId = this.availableClasses[0].id;
                    this.onClassChange();
                }
            },
            onClassChange() {
                this.selectedSubjectId = '';
                this.selectedBatchId = '';
                if (this.availableSubjects.length > 0) {
                    this.selectedSubjectId = this.availableSubjects[0].id;
                    this.onSubjectChange();
                }
            },
            onSubjectChange() {
                this.selectedBatchId = '';
                if (this.availableBatches.length > 0) {
                    this.selectedBatchId = this.availableBatches[0].id;
                }
            },

            openPromoteModal(user) {
                this.selectedUser = user;
                this.selectedCategoryId = this.categories.length > 0 ? this.categories[0].id : '';
                this.onCategoryChange();
                this.promoteModalOpen = true;
            },

            toggleStatus(user) {
                if (confirm(`Change status of ${user.fname}?`)) {
                    const form = document.getElementById('userStatusToggleForm');
                    document.getElementById('statusToggleUserId').value = user.id;
                    form.submit();
                }
            },

            resolveMedia(path) {
                if (!path) return '';
                if (path.startsWith('http://') || path.startsWith('https://')) return path;
                return '{{ asset('') }}' + path.replace(/^\//, '');
            }
        };
    }
</script>
@endsection
