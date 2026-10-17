@extends('layouts.admin')
@section('title', 'Teachers & Faculty Directory')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $teachersJson = $teachers->map(function($t) {
        return [
            'id' => $t->id,
            'fname' => $t->fname,
            'lname' => $t->lname,
            'name' => trim($t->fname . ' ' . $t->lname),
            'username' => $t->username,
            'email' => $t->email,
            'number' => $t->number,
            'designation' => $t->designation ?: 'Faculty Member',
            'qualification' => $t->qualification ?: 'Not Specified',
            'joining_date' => $t->joining_date ? $t->joining_date->format('Y-m-d') : '',
            'joining_date_formatted' => $t->joining_date ? $t->joining_date->format('d M Y') : 'N/A',
            'base_salary' => (float) ($t->salaryStructure?->base_salary ?: ($t->base_salary ?: 0)),
            'salary_type' => $t->salaryStructure?->salary_type ?: ($t->salary_type ?: 'monthly'),
            'fixed_deductions' => ($t->salaryStructure && is_array($t->salaryStructure->fixed_deductions)) ? $t->salaryStructure->fixed_deductions : [],
            'fixed_bonuses' => ($t->salaryStructure && is_array($t->salaryStructure->fixed_bonuses)) ? $t->salaryStructure->fixed_bonuses : [],
            'status' => $t->status,
            'gender' => $t->gender ?: '',
            'blood_group' => $t->blood_group ?: '',
            'address' => $t->address ?: '',
            'city' => $t->city ?: '',
            'state' => $t->state ?: '',
            'pincode' => $t->pincode ?: '',
            'initials' => strtoupper(substr($t->fname, 0, 1) . substr($t->lname ?: 'T', 0, 1)),
            'profile_picture' => $t->profile_picture ? asset('storage/' . $t->profile_picture) : null,
            'managed_classes' => $t->managedClasses->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values(),
            'assigned_subjects' => $t->assignedSubjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'class' => $s->classCourse?->name])->values(),
            'assigned_batches' => $t->assignedBatches->map(fn($b) => ['id' => $b->id, 'name' => $b->name])->values(),
        ];
    });
@endphp

<script>
    window.teachersData = @json($teachersJson);

    window.teacherDirectory = function teacherDirectory() {
        return {
            teachers: window.teachersData || [],
            searchQuery: '',
            statusFilter: 'all',

            // Modal states
            isAddModalOpen: {{ $errors->any() ? 'true' : 'false' }},
            isEditModalOpen: false,
            isPasswordModalOpen: false,
            isSalaryModalOpen: false,

            // Dropdown menu state
            openActionMenuId: null,

            // Salary Structure Form
            salaryForm: {
                userId: '',
                teacherName: '',
                designation: '',
                joiningDate: '',
                baseSalary: 0,
                salaryType: 'monthly',
                fixedDeductionsList: [],
                fixedBonusesList: []
            },

            openSalaryModal(teacher) {
                const fixedDeds = teacher.fixed_deductions && teacher.fixed_deductions.length
                    ? JSON.parse(JSON.stringify(teacher.fixed_deductions))
                    : [];
                const fixedBons = teacher.fixed_bonuses && teacher.fixed_bonuses.length
                    ? JSON.parse(JSON.stringify(teacher.fixed_bonuses))
                    : [];

                this.salaryForm = {
                    userId: teacher.id,
                    teacherName: teacher.name,
                    designation: teacher.designation || 'Faculty Member',
                    joiningDate: teacher.joining_date || '',
                    baseSalary: parseFloat(teacher.base_salary) || 0,
                    salaryType: teacher.salary_type || 'monthly',
                    fixedDeductionsList: fixedDeds,
                    fixedBonusesList: fixedBons
                };
                this.isSalaryModalOpen = true;
                this.openActionMenuId = null;
            },

            closeSalaryModal() {
                this.isSalaryModalOpen = false;
            },

            addFixedDeduction(name = '', amount = '') {
                this.salaryForm.fixedDeductionsList.push({
                    id: Date.now() + Math.random(),
                    name: name,
                    amount: amount !== '' ? parseFloat(amount) : ''
                });
            },

            removeFixedDeduction(idx) {
                this.salaryForm.fixedDeductionsList.splice(idx, 1);
            },

            addFixedBonus(name = '', amount = '') {
                this.salaryForm.fixedBonusesList.push({
                    id: Date.now() + Math.random(),
                    name: name,
                    amount: amount !== '' ? parseFloat(amount) : ''
                });
            },

            removeFixedBonus(idx) {
                this.salaryForm.fixedBonusesList.splice(idx, 1);
            },

            // Active teacher for edit / password reset
            selectedTeacher: null,
            passwordForm: {
                teacherId: null,
                teacherName: '',
                password: '',
                actionUrl: ''
            },

            editForm: {
                id: null,
                actionUrl: '',
                fname: '',
                lname: '',
                email: '',
                number: '',
                designation: '',
                qualification: '',
                joining_date: '',
                base_salary: 0,
                salary_type: 'monthly',
                gender: '',
                blood_group: '',
                address: '',
                city: '',
                state: '',
                pincode: ''
            },

            get filteredTeachers() {
                return this.teachers.filter(t => {
                    // Status filter
                    if (this.statusFilter !== 'all' && t.status !== this.statusFilter) {
                        return false;
                    }
                    // Search filter
                    if (!this.searchQuery.trim()) {
                        return true;
                    }
                    const q = this.searchQuery.toLowerCase().trim();
                    return (
                        t.name.toLowerCase().includes(q) ||
                        t.email.toLowerCase().includes(q) ||
                        t.number.toLowerCase().includes(q) ||
                        t.username.toLowerCase().includes(q) ||
                        t.designation.toLowerCase().includes(q) ||
                        t.qualification.toLowerCase().includes(q)
                    );
                });
            },

            openAddModal() {
                this.isAddModalOpen = true;
            },

            closeAddModal() {
                this.isAddModalOpen = false;
            },

            openEditModal(teacher) {
                this.selectedTeacher = teacher;
                this.editForm = {
                    id: teacher.id,
                    actionUrl: '{{ url('admin/teachers/update') }}/' + teacher.id,
                    fname: teacher.fname,
                    lname: teacher.lname,
                    email: teacher.email,
                    number: teacher.number,
                    designation: teacher.designation,
                    qualification: teacher.qualification === 'Not Specified' ? '' : teacher.qualification,
                    joining_date: teacher.joining_date,
                    base_salary: teacher.base_salary,
                    salary_type: teacher.salary_type,
                    gender: teacher.gender,
                    blood_group: teacher.blood_group,
                    address: teacher.address,
                    city: teacher.city,
                    state: teacher.state,
                    pincode: teacher.pincode
                };
                this.isEditModalOpen = true;
            },

            closeEditModal() {
                this.isEditModalOpen = false;
                this.selectedTeacher = null;
            },

            openPasswordModal(teacher) {
                this.passwordForm = {
                    teacherId: teacher.id,
                    teacherName: teacher.name,
                    password: '',
                    actionUrl: '{{ url('admin/teachers/reset-password') }}/' + teacher.id
                };
                this.isPasswordModalOpen = true;
            },

            closePasswordModal() {
                this.isPasswordModalOpen = false;
            }
        };
    };
</script>

<div x-data="teacherDirectory()" x-cloak class="space-y-6">

    <!-- STANDARD PAGE HEADER BANNER (NO BORDERS, FLAT, THEME TOKENS) -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Teachers &amp; Faculty Directory
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Manage certified faculty members, academic workload assignments, salary profiles, and onboarding credentials.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.teachers.allocations') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-[11px] text-brand"></i>
                <span>Class &amp; Subject Allocations</span>
            </a>
            <button type="button" @click="openAddModal()"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-[11px]"></i>
                <span>+ Add New Teacher</span>
            </button>
        </div>
    </div>

    <!-- STATS CARDS (FLAT, NO BORDERS, THEME TOKENS, MAX FONT-SEMIBOLD) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Faculty -->
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total Faculty</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $totalTeachers }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Registered educators</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
        </div>

        <!-- Active Faculty -->
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Active Staff</p>
                <p class="text-2xl font-semibold mt-1 text-success">{{ $activeTeachers }}</p>
                <p class="text-[11px] text-success mt-0.5 font-normal flex items-center gap-1">
                    <i class="fa-solid fa-circle text-[6px]"></i> Ready for sessions
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Classes Mentored -->
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Mentored Classes</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $managedClassesCount }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Assigned class teachers</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-info-bg text-info flex items-center justify-center text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        <!-- Subjects Covered -->
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Assigned Subjects</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $assignedSubjectsCount }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Active course allocations</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-warning-bg text-warning flex items-center justify-center text-lg">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
        </div>
    </div>

    <!-- TEACHERS DIRECTORY CONTAINER -->
    <div class="rounded-lg bg-primary p-5 space-y-4">
        <!-- TOOLBAR: SEARCH & STATUS FILTER -->
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Single Live Search Bar (py-3, rounded-full, bg-secondary, NO border) -->
                <div class="relative w-full sm:w-80">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Search by name, email, phone, designation..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-10 rounded-full focus:outline-none font-normal">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
                </div>

                <!-- Status Filter Pills (rounded-full, bg-secondary, NO border) -->
                <div class="flex items-center gap-1 bg-secondary p-1 rounded-full text-xs">
                    <button type="button"
                            @click="statusFilter = 'all'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition cursor-pointer inline-flex items-center gap-1.5"
                            :class="statusFilter === 'all' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                        <span>All</span>
                    </button>
                    <button type="button"
                            @click="statusFilter = 'active'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition cursor-pointer inline-flex items-center gap-1.5"
                            :class="statusFilter === 'active' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                        <i class="fa-solid fa-circle text-[6px] text-success"></i>
                        <span>Active</span>
                    </button>
                    <button type="button"
                            @click="statusFilter = 'suspended'"
                            class="px-4 py-2 rounded-full font-normal text-xs transition cursor-pointer inline-flex items-center gap-1.5"
                            :class="statusFilter === 'suspended' ? 'bg-primary text-brand font-semibold shadow-xs' : 'text-theme-text-muted hover:text-theme-text'">
                        <i class="fa-solid fa-circle text-[6px] text-danger"></i>
                        <span>Suspended</span>
                    </button>
                </div>
            </div>

            <div class="text-xs text-theme-text-muted font-normal text-right">
                Showing <span class="font-semibold text-theme-text" x-text="filteredTeachers.length"></span> of <span class="font-semibold text-theme-text">{{ $totalTeachers }}</span> teachers
            </div>
        </div>

        <!-- SEARCH FEEDBACK STRIP -->
        <div x-show="searchQuery.trim().length > 0" class="p-3.5 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text font-normal">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-brand text-xs"></i>
                <span>Showing results for <span class="font-semibold text-theme-text" x-text="'&ldquo;' + searchQuery + '&rdquo;'"></span> &middot; <span x-text="filteredTeachers.length"></span> matches found</span>
            </div>
            <button type="button" @click="searchQuery = ''" class="text-xs text-theme-text-muted hover:text-theme-text font-normal cursor-pointer">
                Clear
            </button>
        </div>

        <!-- DESKTOP TABLE VIEW (Visible on md and up) -->
        <div class="hidden md:block overflow-x-auto min-h-[380px] pb-36">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted font-normal text-[10px] uppercase tracking-wider">
                        <th class="py-3 px-4 pl-5">Faculty Member</th>
                        <th class="py-3 px-4">Designation &amp; Qualification</th>
                        <th class="py-3 px-4">Academic Allocations</th>
                        <th class="py-3 px-4">Remuneration</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 pr-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    <template x-for="teacher in filteredTeachers" :key="teacher.id">
                        <tr class="hover:bg-secondary/40 transition">
                            <!-- Faculty Member -->
                            <td class="py-3.5 px-4 pl-5">
                                <div class="flex items-center gap-3">
                                    <template x-if="teacher.profile_picture">
                                        <img :src="teacher.profile_picture" :alt="teacher.name"
                                             class="w-10 h-10 rounded-full object-cover shrink-0">
                                    </template>
                                    <template x-if="!teacher.profile_picture">
                                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                             x-text="teacher.initials">
                                        </div>
                                    </template>
                                    <div>
                                        <div class="font-semibold text-theme-text text-sm flex items-center gap-2">
                                            <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                                               class="hover:text-brand transition"
                                               x-text="teacher.name"
                                               title="View Faculty 360° Profile"></a>
                                        </div>
                                        <div class="text-[11px] text-theme-text-muted mt-0.5 font-normal">
                                            <span x-text="'@' + teacher.username"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Designation & Qualification -->
                            <td class="py-3.5 px-4">
                                <div class="font-normal text-theme-text" x-text="teacher.designation"></div>
                                <div class="text-[11px] text-theme-text-muted mt-0.5" x-text="teacher.qualification"></div>
                            </td>

                            <!-- Academic Allocations (Compact 1-2 items + Count Popover on Hover) -->
                            <td class="py-3.5 px-4 relative" x-data="{ showAllocPopup: false }" @mouseleave="showAllocPopup = false">
                                <div class="space-y-1">
                                    <!-- Mentored Classes (Max 1 pill + overflow) -->
                                    <template x-if="teacher.managed_classes.length > 0">
                                        <div class="flex items-center gap-1 flex-wrap">
                                            <span class="text-[10px] text-theme-text-muted">Mentor:</span>
                                            <span class="px-2 py-0.5 rounded-full bg-secondary text-brand text-[10px] font-normal"
                                                  x-text="teacher.managed_classes[0].name"></span>
                                            <template x-if="teacher.managed_classes.length > 1">
                                                <span class="px-1.5 py-0.5 rounded-full bg-secondary text-brand text-[9px] font-semibold cursor-pointer hover:bg-subtle transition"
                                                      @mouseenter="showAllocPopup = true"
                                                      x-text="'+' + (teacher.managed_classes.length - 1) + ' more'"></span>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Assigned Subjects (Max 2 pills + overflow) -->
                                    <template x-if="teacher.assigned_subjects.length > 0">
                                        <div class="flex items-center gap-1 flex-wrap">
                                            <span class="text-[10px] text-theme-text-muted">Subjects:</span>
                                            <template x-for="(s, sIdx) in teacher.assigned_subjects.slice(0, 2)" :key="s.id">
                                                <span class="px-2 py-0.5 rounded-full bg-info-bg text-info text-[10px] font-normal"
                                                      x-text="s.name"></span>
                                            </template>
                                            <template x-if="teacher.assigned_subjects.length > 2">
                                                <span class="px-1.5 py-0.5 rounded-full bg-info-bg text-info text-[9px] font-semibold cursor-pointer hover:opacity-80 transition"
                                                      @mouseenter="showAllocPopup = true"
                                                      x-text="'+' + (teacher.assigned_subjects.length - 2) + ' more'"></span>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Assigned Batches (Max 1 pill + overflow) -->
                                    <template x-if="teacher.assigned_batches && teacher.assigned_batches.length > 0">
                                        <div class="flex items-center gap-1 flex-wrap">
                                            <span class="text-[10px] text-theme-text-muted">Batches:</span>
                                            <span class="px-2 py-0.5 rounded-full bg-secondary text-theme-text text-[10px] font-normal"
                                                  x-text="teacher.assigned_batches[0].name"></span>
                                            <template x-if="teacher.assigned_batches.length > 1">
                                                <span class="px-1.5 py-0.5 rounded-full bg-secondary text-theme-text text-[9px] font-semibold cursor-pointer hover:bg-subtle transition"
                                                      @mouseenter="showAllocPopup = true"
                                                      x-text="'+' + (teacher.assigned_batches.length - 1) + ' more'"></span>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="teacher.managed_classes.length === 0 && teacher.assigned_subjects.length === 0 && (!teacher.assigned_batches || teacher.assigned_batches.length === 0)">
                                        <a href="{{ route('admin.teachers.allocations') }}"
                                           class="text-[11px] text-theme-text-muted hover:text-brand transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-plus text-[9px]"></i> Assign allocations
                                        </a>
                                    </template>
                                </div>

                                <!-- HOVER POPUP WITH ALL ACADEMIC ALLOCATIONS -->
                                <div x-show="showAllocPopup"
                                     x-transition
                                     style="display: none;"
                                     class="absolute left-4 top-full mt-1.5 w-64 rounded-lg bg-primary shadow-2xl p-3.5 z-40 text-left space-y-2.5">
                                    <div class="flex items-center justify-between pb-1.5 border-b border-subtle">
                                        <span class="text-[10px] font-semibold uppercase text-theme-text tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-graduation-cap text-brand"></i> All Allocations
                                        </span>
                                        <span class="text-[10px] text-theme-text-muted font-normal" x-text="teacher.name"></span>
                                    </div>

                                    <!-- All Mentored Classes -->
                                    <template x-if="teacher.managed_classes.length > 0">
                                        <div class="space-y-1">
                                            <span class="text-[9px] text-theme-text-muted uppercase font-normal block">Class Mentorships:</span>
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <template x-for="c in teacher.managed_classes" :key="'pop_c_' + c.id">
                                                    <span class="px-2 py-0.5 rounded-full bg-secondary text-brand text-[10px] font-normal" x-text="c.name"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- All Subjects -->
                                    <template x-if="teacher.assigned_subjects.length > 0">
                                        <div class="space-y-1">
                                            <span class="text-[9px] text-theme-text-muted uppercase font-normal block">Assigned Subjects:</span>
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <template x-for="s in teacher.assigned_subjects" :key="'pop_s_' + s.id">
                                                    <span class="px-2 py-0.5 rounded-full bg-info-bg text-info text-[10px] font-normal" x-text="s.name + (s.class ? ' (' + s.class + ')' : '')"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- All Batches -->
                                    <template x-if="teacher.assigned_batches && teacher.assigned_batches.length > 0">
                                        <div class="space-y-1">
                                            <span class="text-[9px] text-theme-text-muted uppercase font-normal block">Assigned Batches:</span>
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <template x-for="b in teacher.assigned_batches" :key="'pop_b_' + b.id">
                                                    <span class="px-2 py-0.5 rounded-full bg-secondary text-theme-text text-[10px] font-normal" x-text="b.name"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>

                            <!-- Remuneration (Clickable to edit directly) -->
                            <td class="py-3.5 px-4">
                                <div class="cursor-pointer group" @click="openSalaryModal(teacher)" title="Click to view & edit salary structure">
                                    <div class="font-normal text-theme-text group-hover:text-brand transition flex items-center gap-1.5">
                                        ₹<span x-text="Number(teacher.base_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                        <i class="fa-solid fa-pen text-[9px] opacity-0 group-hover:opacity-100 transition text-brand"></i>
                                    </div>
                                    <div class="text-[10px] text-theme-text-muted capitalize" x-text="teacher.salary_type"></div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-3.5 px-4">
                                <div class="font-normal text-theme-text font-mono" x-text="teacher.number"></div>
                                <div class="text-[11px] text-theme-text-muted" x-text="teacher.email"></div>
                            </td>

                            <!-- Status & Joined Date (Joined Date shown under Status badge) -->
                            <td class="py-3.5 px-4">
                                <div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-normal inline-flex items-center gap-1"
                                          :class="teacher.status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger'">
                                        <i class="fa-solid fa-circle text-[5px]"></i>
                                        <span class="capitalize" x-text="teacher.status"></span>
                                    </span>
                                    <div class="text-[10px] text-theme-text-muted mt-1.5 flex items-center gap-1 font-normal whitespace-nowrap">
                                        <i class="fa-regular fa-calendar-check text-[9px] text-brand"></i>
                                        <span x-text="'Joined: ' + teacher.joining_date_formatted"></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions Menu (CONSOLIDATED INTO SINGLE 3-DOTS BUTTON, OPENS DOWNWARDS WITHOUT CLIPPING) -->
                            <td class="py-3.5 px-4 pr-5 text-right">
                                <div class="relative inline-flex items-center justify-end" @click.away="if(openActionMenuId === teacher.id) openActionMenuId = null">
                                    <button type="button"
                                            @click="openActionMenuId = (openActionMenuId === teacher.id ? null : teacher.id)"
                                            class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center transition cursor-pointer"
                                            title="Faculty Actions">
                                        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                    </button>

                                    <!-- Dropdown Menu Body (Opens downward cleanly so it NEVER hides at top) -->
                                    <div x-show="openActionMenuId === teacher.id"
                                         x-transition
                                         style="display: none;"
                                         class="absolute right-0 top-full mt-1.5 w-56 rounded-lg bg-primary shadow-2xl p-2 z-50 text-left space-y-1">
                                        
                                        <!-- 1. View 360° Profile -->
                                        <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                                           class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-id-card-clip text-brand text-[11px] w-4"></i>
                                            <span>View 360° Profile</span>
                                        </a>

                                        <!-- 2. Edit Profile -->
                                        <button type="button" @click="openEditModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-pen-to-square text-brand text-[11px] w-4"></i>
                                            <span>Edit Profile</span>
                                        </button>

                                        <!-- 3. Salary Structure (POPUP RIGHT HERE - NO REDIRECT) -->
                                        <button type="button" @click="openSalaryModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-wallet text-brand text-[11px] w-4"></i>
                                            <span>Salary Structure</span>
                                        </button>

                                        <!-- 4. Reset Password -->
                                        <button type="button" @click="openPasswordModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-key text-warning text-[11px] w-4"></i>
                                            <span>Reset Password</span>
                                        </button>

                                        <!-- 5. Monthly Payroll Ledger -->
                                        <a :href="'{{ route('admin.teachers.payroll') }}'"
                                           class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-file-invoice-dollar text-brand text-[11px] w-4"></i>
                                            <span>Monthly Payroll</span>
                                        </a>

                                        <div class="border-b border-subtle/50 my-1"></div>

                                        <!-- 6. Toggle Status Form -->
                                        <form method="POST" :action="'{{ url('admin/teachers/toggle-status') }}/' + teacher.id" class="block w-full">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full px-3 py-2 rounded-full text-xs font-normal hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer"
                                                    :class="teacher.status === 'active' ? 'text-danger' : 'text-success'">
                                                <i class="fa-solid w-4 text-[11px]" :class="teacher.status === 'active' ? 'fa-user-slash' : 'fa-user-check'"></i>
                                                <span x-text="teacher.status === 'active' ? 'Suspend Faculty' : 'Activate Faculty'"></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-if="filteredTeachers.length === 0">
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-lg mx-auto mb-3">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-theme-text">No Faculty Members Found</h4>
                                <p class="text-xs text-theme-text-muted mt-1 max-w-sm mx-auto">
                                    No teachers match your search filter. Onboard new educators using the button above.
                                </p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- MOBILE CARDS VIEW (Visible on small screens < md) -->
        <div class="block md:hidden space-y-3">
            <template x-for="teacher in filteredTeachers" :key="teacher.id">
                <div class="rounded-lg bg-secondary/50 p-4 space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <template x-if="teacher.profile_picture">
                                <img :src="teacher.profile_picture" :alt="teacher.name"
                                     class="w-10 h-10 rounded-full object-cover shrink-0">
                            </template>
                            <template x-if="!teacher.profile_picture">
                                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0"
                                     x-text="teacher.initials">
                                </div>
                            </template>
                            <div>
                                <h4 class="font-semibold text-sm text-theme-text" x-text="teacher.name"></h4>
                                <p class="text-[11px] text-theme-text-muted" x-text="teacher.designation"></p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-normal"
                              :class="teacher.status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger'"
                              x-text="teacher.status">
                        </span>
                    </div>

                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between text-theme-text-muted">
                            <span>Phone:</span>
                            <span class="text-theme-text font-mono" x-text="teacher.number"></span>
                        </div>
                        <div class="flex justify-between text-theme-text-muted">
                            <span>Email:</span>
                            <span class="text-theme-text" x-text="teacher.email"></span>
                        </div>
                        <div class="flex justify-between text-theme-text-muted">
                            <span>Base Salary:</span>
                            <span class="text-theme-text">₹<span x-text="Number(teacher.base_salary).toLocaleString('en-IN')"></span></span>
                        </div>
                        <div class="flex justify-between text-theme-text-muted">
                            <span>Joined Date:</span>
                            <span class="text-theme-text" x-text="teacher.joining_date_formatted"></span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2 flex-wrap border-t-0">
                        <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                           class="px-3 py-1.5 rounded-full bg-brand text-primary text-xs transition inline-flex items-center gap-1.5 font-normal">
                            <i class="fa-solid fa-id-card-clip text-[10px]"></i>
                            <span>360° Profile</span>
                        </a>
                        <button type="button" @click="openSalaryModal(teacher)"
                                class="px-3 py-1.5 rounded-full bg-secondary text-brand text-xs hover:bg-subtle transition font-normal inline-flex items-center gap-1 cursor-pointer">
                            <i class="fa-solid fa-wallet text-[10px]"></i>
                            <span>Salary</span>
                        </button>
                        <button type="button" @click="openEditModal(teacher)"
                                class="px-3 py-1.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                            Edit
                        </button>
                        <button type="button" @click="openPasswordModal(teacher)"
                                class="px-3 py-1.5 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                            Password
                        </button>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <!-- ADD TEACHER MODAL (Popup Shadow Hierarchy: shadow-2xl, rounded-lg) -->
    <div x-show="isAddModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="closeAddModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl rounded-lg bg-primary p-6 shadow-2xl space-y-5" @click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text">Register New Faculty Member</h3>
                            <p class="text-xs text-theme-text-muted">Enter credentials and employment details for onboarding.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeAddModal()" class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.teachers.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Names -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">First Name *</label>
                            <input type="text" name="fname" value="{{ old('fname') }}" required placeholder="e.g. Rahul"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('fname')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Last Name *</label>
                            <input type="text" name="lname" value="{{ old('lname') }}" required placeholder="e.g. Verma"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('lname')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact & Login -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="teacher@academy.com"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('email')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Phone Number *</label>
                            <input type="text" name="number" value="{{ old('number') }}" required placeholder="+91 9876543210"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('number')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Login Password *</label>
                            <input type="password" name="password" required minlength="6" placeholder="Min. 6 characters"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('password')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Professional Designation & Qualification -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Senior Physics Faculty"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Qualification</label>
                            <input type="text" name="qualification" value="{{ old('qualification') }}" placeholder="e.g. M.Sc, B.Ed"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Joining Date</label>
                            <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Salary & Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Base Salary (₹)</label>
                            <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary') }}" placeholder="e.g. 35000"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Salary Type</label>
                            <select name="salary_type" class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                <option value="monthly" {{ old('salary_type') === 'monthly' ? 'selected' : '' }}>Monthly Fixed</option>
                                <option value="per_session" {{ old('salary_type') === 'per_session' ? 'selected' : '' }}>Per-Session / Lecture</option>
                                <option value="hourly" {{ old('salary_type') === 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Gender</label>
                            <select name="gender" class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profile Photo -->
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Profile Photo (Optional)</label>
                        <input type="file" name="profile_picture" accept="image/*"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                        @error('profile_picture')
                            <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t-0">
                        <button type="button" @click="closeAddModal()"
                                class="px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-3 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition">
                            Register Faculty Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT TEACHER MODAL -->
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="closeEditModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl rounded-lg bg-primary p-6 shadow-2xl space-y-5" @click.stop>
                <div class="flex items-center justify-between pb-3 border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text">Edit Faculty Member</h3>
                            <p class="text-xs text-theme-text-muted">Update contact, employment and salary details.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditModal()" class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form method="POST" :action="editForm.actionUrl" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Names -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">First Name *</label>
                            <input type="text" name="fname" x-model="editForm.fname" required
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Last Name *</label>
                            <input type="text" name="lname" x-model="editForm.lname" required
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Email Address *</label>
                            <input type="email" name="email" x-model="editForm.email" required
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Phone Number *</label>
                            <input type="text" name="number" x-model="editForm.number" required
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Professional Designation & Qualification -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Designation</label>
                            <input type="text" name="designation" x-model="editForm.designation"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Qualification</label>
                            <input type="text" name="qualification" x-model="editForm.qualification"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Joining Date</label>
                            <input type="date" name="joining_date" x-model="editForm.joining_date"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Salary & Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Base Salary (₹)</label>
                            <input type="number" step="0.01" name="base_salary" x-model="editForm.base_salary"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Salary Type</label>
                            <select name="salary_type" x-model="editForm.salary_type" class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                <option value="monthly">Monthly Fixed</option>
                                <option value="per_session">Per-Session / Lecture</option>
                                <option value="hourly">Hourly Rate</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profile Photo Update -->
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Update Photo (Optional)</label>
                        <input type="file" name="profile_picture" accept="image/*"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none">
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t-0">
                        <button type="button" @click="closeEditModal()"
                                class="px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-3 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RESET PASSWORD MODAL -->
    <div x-show="isPasswordModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="closePasswordModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-lg bg-primary p-6 shadow-2xl space-y-4" @click.stop>
                <div class="flex items-center justify-between pb-2 border-b-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-theme-text">Reset Password</h3>
                            <p class="text-xs text-theme-text-muted" x-text="'For ' + passwordForm.teacherName"></p>
                        </div>
                    </div>
                    <button type="button" @click="closePasswordModal()" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <form method="POST" :action="passwordForm.actionUrl" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">New Password *</label>
                        <input type="password" name="password" required minlength="6" placeholder="Enter at least 6 characters"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button type="button" @click="closePasswordModal()"
                                class="px-4 py-2.5 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition cursor-pointer">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SALARY STRUCTURE & FIXED RECURRING DEDUCTIONS / BONUSES MODAL             -->
    <!-- ========================================================================= -->
    <div x-show="isSalaryModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closeSalaryModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeSalaryModal()">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Faculty Salary Structure</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            Base stipend, recurring fixed deductions (PF, ESI) &amp; allowances
                        </p>
                    </div>
                </div>
                <button type="button" @click="closeSalaryModal()" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" :action="'{{ url('admin/teachers/update-salary') }}/' + salaryForm.userId" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="fixed_deductions_json" :value="JSON.stringify(salaryForm.fixedDeductionsList)">
                <input type="hidden" name="fixed_bonuses_json" :value="JSON.stringify(salaryForm.fixedBonusesList)">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-theme-text" x-text="salaryForm.teacherName"></div>
                        <div class="text-[11px] text-theme-text-muted font-normal" x-text="salaryForm.designation"></div>
                    </div>
                </div>

                <!-- Base Salary & Calculation Type -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-coins text-brand text-sm"></i>
                        <span class="text-xs font-semibold text-theme-text">Base Stipend &amp; Contract Model</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Base Monthly Salary (₹) *</label>
                            <input type="number" step="0.01" min="0" name="base_salary" x-model.number="salaryForm.baseSalary" required
                                   class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Frequency *</label>
                            <select name="salary_type" x-model="salaryForm.salaryType" required
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="monthly">Monthly Fixed Stipend</option>
                                <option value="per_session">Per Session / Lecture</option>
                                <option value="hourly">Hourly Billing Rate</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Faculty Title / Designation</label>
                            <input type="text" name="designation" x-model="salaryForm.designation"
                                   class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Official Joining Date</label>
                            <input type="date" name="joining_date" x-model="salaryForm.joiningDate"
                                   class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- FIXED MONTHLY DEDUCTIONS (CUSTOM RECURRING DEDUCTIONS) -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-danger text-sm"></i>
                            <div>
                                <span class="text-xs font-semibold text-theme-text">Recurring Monthly Deductions</span>
                                <p class="text-[10px] text-theme-text-muted font-normal">Auto-deducted every month (PF, ESI/Insurance, Loan, etc.)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Preset Chips -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-theme-text-muted mr-1 font-normal">Quick Add:</span>
                        <button type="button" @click="addFixedDeduction('Provident Fund (PF)', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> PF (Provident Fund)
                        </button>
                        <button type="button" @click="addFixedDeduction('ESI / Health Insurance', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Health Insurance (ESI)
                        </button>
                        <button type="button" @click="addFixedDeduction('Professional Tax', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Prof. Tax
                        </button>
                        <button type="button" @click="addFixedDeduction('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom Fixed Deduction
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in salaryForm.fixedDeductionsList" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Deduction title (e.g. PF, Health Insurance, Staff Loan)"
                                       class="flex-1 bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                                <div class="relative w-32">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-theme-text-muted">₹</span>
                                    <input type="number" step="0.01" min="0" x-model.number="item.amount" placeholder="0.00"
                                           class="w-full bg-primary text-danger text-xs pl-7 pr-4 py-2.5 rounded-full focus:outline-none text-right font-normal">
                                </div>
                                <button type="button" @click="removeFixedDeduction(index)"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs shrink-0 transition cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="salaryForm.fixedDeductionsList.length === 0">
                            <div class="text-[11px] text-theme-text-muted py-2.5 text-center bg-primary rounded-lg font-normal">
                                No recurring deductions configured. Click any preset above to add one.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FIXED MONTHLY BONUSES & ALLOWANCES -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gift text-brand text-sm"></i>
                            <div>
                                <span class="text-xs font-semibold text-theme-text">Recurring Monthly Allowances</span>
                                <p class="text-[10px] text-theme-text-muted font-normal">Auto-added every month (HRA, Medical Allowance, Travel Allowance, etc.)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Preset Chips -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-theme-text-muted mr-1 font-normal">Quick Add:</span>
                        <button type="button" @click="addFixedBonus('House Rent Allowance (HRA)', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> HRA Allowance
                        </button>
                        <button type="button" @click="addFixedBonus('Conveyance / Travel Allowance', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Travel Allowance
                        </button>
                        <button type="button" @click="addFixedBonus('Medical Allowance', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Medical Allowance
                        </button>
                        <button type="button" @click="addFixedBonus('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom Allowance
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in salaryForm.fixedBonusesList" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Allowance title (e.g. HRA, Travel, Performance Base)"
                                       class="flex-1 bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                                <div class="relative w-32">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-theme-text-muted">₹</span>
                                    <input type="number" step="0.01" min="0" x-model.number="item.amount" placeholder="0.00"
                                           class="w-full bg-primary text-success text-xs pl-7 pr-4 py-2.5 rounded-full focus:outline-none text-right font-normal">
                                </div>
                                <button type="button" @click="removeFixedBonus(index)"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs shrink-0 transition cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="salaryForm.fixedBonusesList.length === 0">
                            <div class="text-[11px] text-theme-text-muted py-2.5 text-center bg-primary rounded-lg font-normal">
                                No recurring allowances configured. Click any preset above to add one.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeSalaryModal()"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Salary Structure</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
