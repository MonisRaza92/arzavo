@extends('layouts.admin')
@section('title', 'Student Admissions & KYC Verification')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        window.admissionsCategoriesData = @json($categories);
        window.admissionsInitialCategory = '{{ request('category_id') }}';
        window.admissionsInitialClass = '{{ request('class_id') }}';

        window.admissionsManagement = function admissionsManagement() {
            return {
                categories: window.admissionsCategoriesData || [],
                filterCategoryId: window.admissionsInitialCategory || '',
                filterClassId: window.admissionsInitialClass || '',
                directModalOpen: false,
                rejectModalOpen: false,
                docPreviewModalOpen: false,
                rejectId: null,
                rejectStudentName: '',
                previewDocUrl: '',
                previewDocTitle: '',
                previewDocIsPdf: false,

                // Direct Admission form reactive states
                selectedCategoryId: '',
                selectedClassId: '',
                enrollmentType: 'batch',
                selectedBatchId: '',
                selectedSubjectIds: [],
                passwordVisible: false,
                formData: {
                    fname: '',
                    lname: '',
                    username: '',
                    password: '',
                },

                // Filter Bar: compute classes for chosen category
                get filterClasses() {
                    if (!this.filterCategoryId) return [];
                    const cat = this.categories.find(c => String(c.id) === String(this.filterCategoryId));
                    return cat ? (cat.class_courses || []) : [];
                },

                onFilterCategoryChange() {
                    this.filterClassId = '';
                    this.$nextTick(() => {
                        this.$refs.filterForm.submit();
                    });
                },

                onFilterClassChange() {
                    this.$refs.filterForm.submit();
                },

                // Modal dependent lists
                get currentClasses() {
                    if (!this.selectedCategoryId) return [];
                    const cat = this.categories.find(c => String(c.id) === String(this.selectedCategoryId));
                    return cat ? (cat.class_courses || []) : [];
                },

                get currentBatches() {
                    if (!this.selectedClassId) return [];
                    const cls = this.currentClasses.find(c => String(c.id) === String(this.selectedClassId));
                    return cls ? (cls.batches || []) : [];
                },

                get currentSubjects() {
                    if (!this.selectedClassId) return [];
                    const cls = this.currentClasses.find(c => String(c.id) === String(this.selectedClassId));
                    return cls ? (cls.subjects || []) : [];
                },

                get selectedBatchDetails() {
                    if (!this.selectedBatchId) return null;
                    return this.currentBatches.find(b => String(b.id) === String(this.selectedBatchId)) || null;
                },

                onCategoryChange() {
                    this.selectedClassId = '';
                    this.selectedBatchId = '';
                    this.selectedSubjectIds = [];
                },

                onClassChange() {
                    this.selectedBatchId = '';
                    this.selectedSubjectIds = [];
                    if (this.currentBatches.length > 0) {
                        this.selectedBatchId = this.currentBatches[0].id;
                    }
                },

                generateUsername() {
                    const f = (this.formData.fname || '').toLowerCase().replace(/[^a-z0-9]/g, '');
                    const l = (this.formData.lname || '').toLowerCase().replace(/[^a-z0-9]/g, '');
                    if (f) {
                        const rand = Math.floor(100 + Math.random() * 900);
                        this.formData.username = l ? `${f}_${l}` : `${f}${rand}`;
                    }
                },

                generatePassword() {
                    const f = (this.formData.fname || 'Student').trim();
                    const clean = f.charAt(0).toUpperCase() + f.slice(1).toLowerCase().replace(/[^a-zA-Z]/g, '');
                    const rand = Math.floor(1000 + Math.random() * 9000);
                    this.formData.password = `${clean || 'Student'}@${rand}`;
                },

                openDirectModal() {
                    this.directModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeDirectModal() {
                    this.directModalOpen = false;
                    document.body.style.overflow = 'auto';
                },

                openRejectModal(id, name) {
                    this.rejectId = id;
                    this.rejectStudentName = name;
                    this.rejectModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeRejectModal() {
                    this.rejectModalOpen = false;
                    this.rejectId = null;
                    document.body.style.overflow = 'auto';
                },

                openDocPreview(url, title) {
                    this.previewDocUrl = url;
                    this.previewDocTitle = title;
                    this.previewDocIsPdf = url.toLowerCase().includes('.pdf');
                    this.docPreviewModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeDocPreview() {
                    this.docPreviewModalOpen = false;
                    this.previewDocUrl = '';
                    this.previewDocTitle = '';
                    document.body.style.overflow = 'auto';
                }
            };
        };
    </script>

    <div x-data="admissionsManagement()" x-cloak class="space-y-5">

        <!-- STANDARD PAGE HEADER BANNER -->
        <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div
                    class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h1 class="text-base font-semibold text-theme-text leading-tight">
                        Admissions & KYC Verification Applications
                    </h1>
                    <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                        Review and verify student enrollment applications, inspect Aadhaar and marksheet KYC documents, and
                        directly admit registered students with custom curriculum placement.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
                <a href="{{ route('admin.students') }}"
                    class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-users text-[11px] text-brand"></i>
                    <span>Students Directory</span>
                </a>
                <button type="button" @click="openDirectModal()"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                    <span>+ Admission</span>
                </button>
            </div>
        </div>

        <!-- STATS CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Applications -->
            <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-normal text-theme-text-muted">Total Applications</p>
                    <h3 class="text-xl font-semibold text-theme-text mt-1">{{ number_format($stats['total']) }}</h3>
                    <p class="text-[11px] text-theme-text-muted mt-1">All recorded entries</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
            </div>

            <!-- Pending Verification -->
            <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-normal text-theme-text-muted">Pending Verification</p>
                    <h3 class="text-xl font-semibold text-warning mt-1">{{ number_format($stats['pending']) }}</h3>
                    <p class="text-[11px] text-theme-text-muted mt-1">Awaiting review & approval</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-warning-bg text-warning flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <!-- Approved Students -->
            <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-normal text-theme-text-muted">Approved Students</p>
                    <h3 class="text-xl font-semibold text-success mt-1">{{ number_format($stats['approved']) }}</h3>
                    <p class="text-[11px] text-theme-text-muted mt-1">Active enrolled students</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-success-bg text-success flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="rounded-lg bg-primary p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-normal text-theme-text-muted">Rejected Applications</p>
                    <h3 class="text-xl font-semibold text-danger mt-1">{{ number_format($stats['rejected']) }}</h3>
                    <p class="text-[11px] text-theme-text-muted mt-1">Ineligible or invalid KYC</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-danger-bg text-danger flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
            </div>
        </div>

        <!-- FILTER & SEARCH BAR WITH DEPENDENT CATEGORY -> CLASS SELECT -->
        <div class="rounded-lg bg-primary p-4">
            <form x-ref="filterForm" method="GET" action="{{ route('admin.students.admissions') }}"
                class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5 grow">
                    <!-- Search Input -->
                    <div class="relative grow sm:grow-0 sm:w-72">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by student name, roll, phone..."
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-9 rounded-full focus:outline-none">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs"></i>
                    </div>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                        class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                        <option value="">All Statuses ({{ $stats['total'] }})</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Verification
                            ({{ $stats['pending'] }})</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                            ({{ $stats['approved'] }})</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                            ({{ $stats['rejected'] }})</option>
                    </select>

                    <!-- Academic Category Filter -->
                    <select name="category_id" x-model="filterCategoryId" @change="onFilterCategoryChange()"
                        class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                        <option value="">All Academic Categories</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" :selected="String(cat.id) === String(filterCategoryId)"
                                x-text="cat.name"></option>
                        </template>
                    </select>

                    <!-- Dependent Class Filter (Visible when Category is chosen) -->
                    <template x-if="filterCategoryId && filterClasses.length > 0">
                        <select name="class_id" x-model="filterClassId" @change="onFilterClassChange()"
                            class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                            <option value="">All Classes in Category</option>
                            <template x-for="cls in filterClasses" :key="cls.id">
                                <option :value="cls.id" :selected="String(cls.id) === String(filterClassId)"
                                    x-text="cls.name"></option>
                            </template>
                        </select>
                    </template>

                    @if(request()->anyFilled(['search', 'status', 'category_id', 'class_id']))
                        <a href="{{ route('admin.students.admissions') }}"
                            class="px-4 py-3 rounded-full bg-danger-bg text-danger text-xs font-normal hover:opacity-85 transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark text-[11px]"></i> Clear Filters
                        </a>
                    @endif
                </div>

                <div class="text-xs text-theme-text-muted self-end lg:self-center shrink-0">
                    Showing {{ $admissions->firstItem() ?? 0 }}-{{ $admissions->lastItem() ?? 0 }} of
                    {{ $admissions->total() }} applications
                </div>
            </form>
        </div>

        <!-- ADMISSIONS TABLE -->
        <div class="rounded-lg bg-primary overflow-hidden">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-theme-text">Admissions & Verification Ledger</h3>
                    <p class="text-xs text-theme-text-muted mt-0.5">Click document chips to preview KYC attachments without
                        leaving this page</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-secondary text-xs text-theme-text-muted font-normal">
                    {{ $admissions->total() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-secondary text-theme-text-muted font-normal">
                            <th class="py-3 px-4 pl-5">Applicant</th>
                            <th class="py-3 px-4">Academic Placement</th>
                            <th class="py-3 px-4">KYC & Documents</th>
                            <th class="py-3 px-4">Guardian & Contact</th>
                            <th class="py-3 px-4">Applied Date</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 pr-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0">
                        @forelse($admissions as $adm)
                            @php
                                $user = $adm->user;
                                $userFname = $user->fname ?? 'Applicant';
                                $userLname = $user->lname ?? '';
                                $fullName = trim($userFname . ' ' . $userLname);
                                $initials = strtoupper(substr($userFname, 0, 1) . substr($userLname ?: 'A', 0, 1));
                                $categoryName = $adm->academicCategory->name ?? ($user->academicCategory->name ?? 'General');
                                $className = $adm->classCourse->name ?? ($user->class->name ?? 'Class Not Assigned');
                                $batch = $user->batch ?? null;
                                $directSubjects = $user->directSubjects ?? collect();
                                $aadhaarNumber = $adm->aadhaar_number ?: ($user->aadhaar_number ?? null);
                                $aadhaarFront = $adm->aadhaar_front ?: ($user->aadhaar_front ?? null);
                                $aadhaarBack = $adm->aadhaar_back ?: ($user->aadhaar_back ?? null);
                                $marksheet = $adm->previous_marksheet ?: ($user->previous_marksheet ?? null);
                            @endphp
                            <tr class="hover:bg-secondary/40 transition">
                                <!-- Applicant Profile -->
                                <td class="py-3.5 px-4 pl-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center font-semibold text-xs shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-theme-text flex items-center gap-2">
                                                <span>{{ $fullName }}</span>
                                                @if(!empty($user->roll_number))
                                                    <span
                                                        class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text-muted font-normal">
                                                        Roll: {{ $user->roll_number }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-theme-text-muted flex items-center gap-2 mt-0.5">
                                                <span>&#64;{{ $user->username ?? 'user' }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $user->number ?? 'No phone' }}</span>
                                            </div>
                                            @if(!empty($user->email))
                                                <p class="text-[10px] text-theme-text-muted truncate max-w-[200px]">
                                                    {{ $user->email }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Academic Placement -->
                                <td class="py-3.5 px-4">
                                    <div class="space-y-1">
                                        <div class="font-semibold text-theme-text">
                                            {{ $className }}
                                        </div>
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text-muted font-normal">
                                                {{ $categoryName }}
                                            </span>
                                            @if($batch)
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-normal flex items-center gap-1">
                                                    <i class="fa-solid fa-layer-group text-[9px]"></i>
                                                    <span>{{ $batch->name }}</span>
                                                </span>
                                            @elseif($directSubjects->count() > 0)
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-secondary text-theme-text text-[10px] font-normal flex items-center gap-1">
                                                    <i class="fa-solid fa-book-open text-[9px] text-brand"></i>
                                                    <span>{{ $directSubjects->count() }} Subjects</span>
                                                </span>
                                            @elseif($adm->subject)
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-secondary text-theme-text text-[10px] font-normal">
                                                    {{ $adm->subject->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($adm->previous_school))
                                            <p class="text-[10px] text-theme-text-muted truncate max-w-[220px]"
                                                title="{{ $adm->previous_school }}">
                                                <i class="fa-solid fa-school text-[9px] mr-0.5"></i> {{ $adm->previous_school }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- KYC & Documents -->
                                <td class="py-3.5 px-4">
                                    <div class="space-y-1.5">
                                        <div class="text-[11px] text-theme-text flex items-center gap-1.5">
                                            <i class="fa-solid fa-id-card text-theme-text-muted text-[10px]"></i>
                                            <span
                                                class="font-normal">{{ $aadhaarNumber ? chunk_split($aadhaarNumber, 4, ' ') : 'No Aadhaar' }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @if($aadhaarFront)
                                                <button type="button"
                                                    @click="openDocPreview('{{ asset('storage/' . $aadhaarFront) }}', 'Aadhaar Card (Front) - {{ addslashes($fullName) }}')"
                                                    class="px-2.5 py-1 rounded-full bg-secondary text-brand text-[10px] font-normal hover:bg-subtle transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-eye text-[9px]"></i> Aadhaar Front
                                                </button>
                                            @endif
                                            @if($aadhaarBack)
                                                <button type="button"
                                                    @click="openDocPreview('{{ asset('storage/' . $aadhaarBack) }}', 'Aadhaar Card (Back) - {{ addslashes($fullName) }}')"
                                                    class="px-2.5 py-1 rounded-full bg-secondary text-brand text-[10px] font-normal hover:bg-subtle transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-eye text-[9px]"></i> Aadhaar Back
                                                </button>
                                            @endif
                                            @if($marksheet)
                                                <button type="button"
                                                    @click="openDocPreview('{{ asset('storage/' . $marksheet) }}', 'Previous Marksheet - {{ addslashes($fullName) }}')"
                                                    class="px-2.5 py-1 rounded-full bg-secondary text-theme-text text-[10px] font-normal hover:bg-subtle transition inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-file-invoice text-[9px] text-brand"></i> Marksheet
                                                </button>
                                            @endif
                                            @if(!$aadhaarFront && !$aadhaarBack && !$marksheet)
                                                <span class="text-[10px] text-theme-text-muted">No files uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Guardian & Contact -->
                                <td class="py-3.5 px-4">
                                    <div class="space-y-0.5">
                                        @if(!empty($user->father_name))
                                            <div class="text-xs text-theme-text font-normal flex items-center gap-1.5">
                                                <i class="fa-solid fa-user-shield text-[10px] text-theme-text-muted"></i>
                                                <span>{{ $user->father_name }}</span>
                                            </div>
                                        @elseif(!empty($user->mother_name))
                                            <div class="text-xs text-theme-text font-normal flex items-center gap-1.5">
                                                <i class="fa-solid fa-user-shield text-[10px] text-theme-text-muted"></i>
                                                <span>{{ $user->mother_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-theme-text-muted">No Guardian Listed</span>
                                        @endif

                                        @if(!empty($user->parent_phone))
                                            <div class="text-[11px] text-theme-text-muted flex items-center gap-1">
                                                <i class="fa-brands fa-whatsapp text-success text-[10px]"></i>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->parent_phone) }}"
                                                    target="_blank" class="hover:underline">
                                                    {{ $user->parent_phone }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Applied Date -->
                                <td class="py-3.5 px-4 text-theme-text-muted">
                                    <div class="text-xs">
                                        {{ $adm->applied_at ? $adm->applied_at->format('d M, Y') : $adm->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="text-[10px] text-theme-text-muted mt-0.5">
                                        {{ $adm->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($adm->status === 'approved')
                                        <span
                                            class="px-3 py-1 rounded-full text-[11px] font-normal bg-success-bg text-success inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-check text-[10px]"></i> Approved
                                        </span>
                                    @elseif($adm->status === 'pending')
                                        <span
                                            class="px-3 py-1 rounded-full text-[11px] font-normal bg-warning-bg text-warning inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-warning animate-pulse"></span> Pending Review
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-[11px] font-normal bg-danger-bg text-danger inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Rejected
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 pr-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($adm->status === 'pending')
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.students.admissions.approve', $adm->id) }}" method="POST"
                                                onsubmit="return confirm('Approve admission and activate {{ addslashes($fullName) }} as student?');">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3.5 py-1.5 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition inline-flex items-center gap-1.5">
                                                    <i class="fa-solid fa-check text-[10px]"></i> Approve
                                                </button>
                                            </form>

                                            <!-- Reject Trigger -->
                                            <button type="button"
                                                @click="openRejectModal({{ $adm->id }}, '{{ addslashes($fullName) }}')"
                                                class="px-3.5 py-1.5 rounded-full bg-secondary text-danger text-xs font-normal hover:bg-danger-bg transition inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Reject
                                            </button>
                                        @elseif($adm->status === 'approved' && $user)
                                            <a href="{{ route('admin.admin-student-profile', $user->username) }}"
                                                class="px-3.5 py-1.5 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition inline-flex items-center gap-1.5">
                                                <span>Profile</span>
                                                <i class="fa-solid fa-arrow-right text-[10px] text-brand"></i>
                                            </a>
                                        @elseif($adm->status === 'rejected')
                                            <span class="text-[11px] text-theme-text-muted italic"
                                                title="{{ $adm->admin_remarks }}">
                                                {{ Str::limit($adm->admin_remarks ?: 'Rejected', 18) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center text-lg mx-auto mb-3">
                                        <i class="fa-solid fa-file-circle-question"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-theme-text">No Admission Applications Found</h4>
                                    <p class="text-xs text-theme-text-muted mt-1 max-w-sm mx-auto">
                                        There are no pending or registered student applications matching your filter criteria.
                                    </p>
                                    <button type="button" @click="openDirectModal()"
                                        class="mt-4 px-5 py-2.5 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition inline-flex items-center gap-2">
                                        <i class="fa-solid fa-user-plus text-[11px]"></i> Add Student Directly
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($admissions->hasPages())
                <div class="p-4 bg-primary">
                    {{ $admissions->links() }}
                </div>
            @endif
        </div>

        <!-- ============================================================ -->
        <!-- MODAL 1: DIRECT STUDENT ADMISSION (FULL DETAILS POPUP)       -->
        <!-- ============================================================ -->
        <div x-show="directModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition"
            @keydown.escape.window="closeDirectModal()">

            <div class="rounded-lg bg-primary w-full max-w-4xl max-h-[92vh] flex flex-col shadow-2xl overflow-hidden"
                @click.outside="closeDirectModal()">

                <!-- Modal Header -->
                <div class="p-5 flex items-center justify-between shrink-0 bg-primary">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-theme-text leading-tight">
                                Direct Student Admission & Onboarding
                            </h2>
                            <p class="text-xs text-theme-text-muted mt-0.5">
                                Fill comprehensive student identity, curriculum placement, guardian, and KYC documents.
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="closeDirectModal()"
                        class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Form Container -->
                <form action="{{ route('admin.students.admissions.store') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col min-h-0 grow overflow-hidden">
                    @csrf

                    <!-- Scrollable Form Body -->
                    <div class="p-6 overflow-y-auto space-y-6 text-xs grow">

                    <!-- SECTION 1: STUDENT IDENTITY & LOGIN CREDENTIALS -->
                    <div class="rounded-lg bg-secondary p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-brand text-primary text-[11px] font-semibold flex items-center justify-center">1</span>
                                <h3 class="text-sm font-semibold text-theme-text">Student Identity & Login Credentials</h3>
                            </div>
                            <span class="text-[11px] text-theme-text-muted font-normal">* Indicates required field</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <!-- First Name -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">First Name *</label>
                                <input type="text" name="fname" x-model="formData.fname" @input="generateUsername()"
                                    required placeholder="e.g. Rahul"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Last Name *</label>
                                <input type="text" name="lname" x-model="formData.lname" @input="generateUsername()"
                                    required placeholder="e.g. Sharma"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Username with Auto-Suggest -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-xs font-normal text-theme-text">Username *</label>
                                    <button type="button" @click="generateUsername()"
                                        class="text-[10px] text-brand hover:underline font-normal">
                                        Auto-Generate
                                    </button>
                                </div>
                                <input type="text" name="username" x-model="formData.username" required
                                    placeholder="e.g. rahul_sharma"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Email Address *</label>
                                <input type="email" name="email" required placeholder="student@example.com"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Student Mobile Number
                                    *</label>
                                <input type="tel" name="number" required placeholder="10-digit mobile number"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Password with Generator & Toggle -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-xs font-normal text-theme-text">Login Password *</label>
                                    <button type="button" @click="generatePassword()"
                                        class="text-[10px] text-brand hover:underline font-normal">
                                        Generate Standard
                                    </button>
                                </div>
                                <div class="relative">
                                    <input :type="passwordVisible ? 'text' : 'password'" name="password"
                                        x-model="formData.password" required minlength="6" placeholder="Min 6 characters"
                                        class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pr-10 rounded-full focus:outline-none">
                                    <button type="button" @click="passwordVisible = !passwordVisible"
                                        class="absolute right-3.5 top-3.5 text-theme-text-muted hover:text-theme-text">
                                        <i class="fa-solid" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Date of Birth</label>
                                <input type="date" name="dob"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Gender</label>
                                <select name="gender"
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Blood Group -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Blood Group</label>
                                <select name="blood_group"
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>

                            <!-- Roll Number -->
                            <div class="sm:col-span-2 md:col-span-1">
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Roll Number / Reg ID</label>
                                <input type="text" name="roll_number" placeholder="e.g. 2026-X-042"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: ACADEMIC PROGRAM & CURRICULUM ENROLLMENT -->
                    <div class="rounded-lg bg-secondary p-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-brand text-primary text-[11px] font-semibold flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-semibold text-theme-text">Academic Program & Curriculum Placement
                                </h3>
                                <p class="text-[11px] text-theme-text-muted mt-0.5">Select category and class, then choose
                                    between standard batch enrollment or direct subject selection</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Category Select -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Academic Category *</label>
                                <select name="academic_category_id" x-model="selectedCategoryId"
                                    @change="onCategoryChange()" required
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                    <option value="">-- Choose Category --</option>
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Class / Course Select -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Class / Course *</label>
                                <select name="class_id" x-model="selectedClassId" @change="onClassChange()" required
                                    :disabled="!selectedCategoryId"
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer disabled:opacity-50">
                                    <option value="">-- Choose Class --</option>
                                    <template x-for="cls in currentClasses" :key="cls.id">
                                        <option :value="cls.id" x-text="cls.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Enrollment Mode Switcher -->
                        <div class="space-y-3 pt-2" x-show="selectedClassId">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-normal text-theme-text">Curriculum Enrollment Mode:</span>
                                <div class="inline-flex rounded-full bg-primary p-1">
                                    <button type="button" @click="enrollmentType = 'batch'"
                                        :class="enrollmentType === 'batch' ? 'bg-brand text-primary' : 'text-theme-text-muted hover:text-theme-text'"
                                        class="px-4 py-1.5 rounded-full text-xs font-normal transition">
                                        <i class="fa-solid fa-layer-group text-[10px] mr-1"></i> Enroll in Batch
                                    </button>
                                    <button type="button" @click="enrollmentType = 'subjects'"
                                        :class="enrollmentType === 'subjects' ? 'bg-brand text-primary' : 'text-theme-text-muted hover:text-theme-text'"
                                        class="px-4 py-1.5 rounded-full text-xs font-normal transition">
                                        <i class="fa-solid fa-book-open text-[10px] mr-1"></i> Direct Subject Selection
                                    </button>
                                </div>
                                <input type="hidden" name="enrollment_type" :value="enrollmentType">
                            </div>

                            <!-- Mode A: Batch Enrollment -->
                            <div x-show="enrollmentType === 'batch'" class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-normal text-theme-text mb-1.5">Select Batch</label>
                                        <select name="batch_id" x-model="selectedBatchId"
                                            class="w-full bg-primary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                                            <option value="">-- Select Batch (Optional) --</option>
                                            <template x-for="b in currentBatches" :key="b.id">
                                                <option :value="b.id"
                                                    x-text="b.name + (b.start_time ? ' (' + b.start_time.slice(0, 5) + ' - ' + (b.end_time ? b.end_time.slice(0, 5) : '') + ')' : '')">
                                                </option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Live Batch Preview Card -->
                                <template x-if="selectedBatchDetails">
                                    <div class="rounded-lg bg-primary p-3 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-layer-group text-brand text-xs"></i>
                                                <span class="font-semibold text-theme-text"
                                                    x-text="selectedBatchDetails.name"></span>
                                            </div>
                                            <span
                                                class="px-2.5 py-0.5 rounded-full bg-secondary text-[11px] text-theme-text-muted"
                                                x-text="(selectedBatchDetails.batch_subjects || []).length + ' Subjects included'"></span>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            <template x-for="bs in (selectedBatchDetails.batch_subjects || [])"
                                                :key="bs.id">
                                                <span
                                                    class="px-2.5 py-1 rounded-full bg-secondary text-theme-text text-[11px] flex items-center gap-1.5">
                                                    <i class="fa-solid fa-check text-[9px] text-brand"></i>
                                                    <span x-text="bs.subject ? bs.subject.name : 'Subject'"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Mode B: Direct Subject Selection -->
                            <div x-show="enrollmentType === 'subjects'" class="space-y-2">
                                <label class="block text-xs font-normal text-theme-text">Choose Subjects for Direct
                                    Enrollment:</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                    <template x-for="sub in currentSubjects" :key="sub.id">
                                        <label
                                            class="rounded-lg bg-primary p-3 flex items-start gap-2.5 cursor-pointer hover:bg-subtle transition">
                                            <input type="checkbox" name="subject_ids[]" :value="sub.id"
                                                class="rounded-sm text-brand mt-0.5 focus:ring-0">
                                            <div class="grow">
                                                <div class="font-semibold text-theme-text" x-text="sub.name"></div>
                                                <div
                                                    class="text-[11px] text-theme-text-muted flex items-center gap-2 mt-0.5">
                                                    <span
                                                        x-text="sub.teacher ? sub.teacher.name : 'Mentor unassigned'"></span>
                                                    <span x-show="sub.start_time"
                                                        x-text="'&bull; ' + sub.start_time.slice(0, 5)"></span>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                                <p x-show="currentSubjects.length === 0" class="text-xs text-theme-text-muted italic">
                                    No subjects created under this class yet.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: PARENT & GUARDIAN INFORMATION -->
                    <div class="rounded-lg bg-secondary p-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-brand text-primary text-[11px] font-semibold flex items-center justify-center">3</span>
                            <div>
                                <h3 class="text-sm font-semibold text-theme-text">Parent & Guardian Information</h3>
                                <p class="text-[11px] text-theme-text-muted mt-0.5">Crucial for automated WhatsApp/SMS fee
                                    reminders and attendance alert routing</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Father Name -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Father's Name</label>
                                <input type="text" name="father_name" placeholder="Father's full name"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Mother Name -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Mother's Name</label>
                                <input type="text" name="mother_name" placeholder="Mother's full name"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Parent Phone / WhatsApp -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">
                                    Parent Phone / WhatsApp
                                </label>
                                <input type="tel" name="parent_phone" placeholder="WhatsApp enabled number"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Emergency Contact -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Emergency Contact
                                    Number</label>
                                <input type="tel" name="emergency_contact" placeholder="Alternate emergency phone"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: RESIDENTIAL ADDRESS & PREVIOUS EDUCATION -->
                    <div class="rounded-lg bg-secondary p-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-brand text-primary text-[11px] font-semibold flex items-center justify-center">4</span>
                            <h3 class="text-sm font-semibold text-theme-text">Residential Address & Previous Education</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Street Address</label>
                                <input type="text" name="address" placeholder="House/Flat No, Landmark, Street"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">City / Town</label>
                                <input type="text" name="city" placeholder="e.g. Mumbai"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- State -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">State</label>
                                <input type="text" name="state" placeholder="e.g. Maharashtra"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Pincode -->
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Pincode</label>
                                <input type="text" name="pincode" placeholder="e.g. 400001"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Previous School -->
                            <div class="sm:col-span-2 md:col-span-3">
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Previous School / College /
                                    Institute</label>
                                <input type="text" name="previous_school" placeholder="e.g. St. Xavier High School"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: KYC DOCUMENTS UPLOAD -->
                    <div class="rounded-lg bg-secondary p-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-brand text-primary text-[11px] font-semibold flex items-center justify-center">5</span>
                            <div>
                                <h3 class="text-sm font-semibold text-theme-text">KYC Verification & Document Uploads</h3>
                                <p class="text-[11px] text-theme-text-muted mt-0.5">Upload official identity and academic
                                    records (Images or PDF up to 5MB)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <!-- Aadhaar Card Number -->
                            <div class="sm:col-span-2 md:col-span-3">
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Aadhaar Card Number (12
                                    Digits)</label>
                                <input type="text" name="aadhaar_number" maxlength="14" placeholder="XXXX XXXX XXXX"
                                    class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            </div>

                            <!-- Aadhaar Front -->
                            <div class="rounded-lg bg-primary p-3 space-y-1.5">
                                <label class="block text-xs font-normal text-theme-text">Aadhaar Front (Image/PDF)</label>
                                <input type="file" name="aadhaar_front" accept="image/*,application/pdf"
                                    class="w-full text-[11px] text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:bg-secondary file:text-theme-text hover:file:bg-subtle cursor-pointer">
                            </div>

                            <!-- Aadhaar Back -->
                            <div class="rounded-lg bg-primary p-3 space-y-1.5">
                                <label class="block text-xs font-normal text-theme-text">Aadhaar Back (Image/PDF)</label>
                                <input type="file" name="aadhaar_back" accept="image/*,application/pdf"
                                    class="w-full text-[11px] text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:bg-secondary file:text-theme-text hover:file:bg-subtle cursor-pointer">
                            </div>

                            <!-- Marksheet -->
                            <div class="rounded-lg bg-primary p-3 space-y-1.5">
                                <label class="block text-xs font-normal text-theme-text">Previous Marksheet / TC
                                    (Image/PDF)</label>
                                <input type="file" name="previous_marksheet" accept="image/*,application/pdf"
                                    class="w-full text-[11px] text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:bg-secondary file:text-theme-text hover:file:bg-subtle cursor-pointer">
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Modal Actions Fixed at Card Bottom -->
                    <div class="p-4 bg-primary flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="closeDirectModal()"
                            class="px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 transition inline-flex items-center gap-2">
                            <i class="fa-solid fa-check text-[11px]"></i>
                            <span>Register & Admit Student</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL 2: REJECT ADMISSION APPLICATION                       -->
        <!-- ============================================================ -->
        <div x-show="rejectModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition"
            @keydown.escape.window="closeRejectModal()">

            <div class="rounded-lg bg-primary w-full max-w-md shadow-2xl overflow-hidden"
                @click.outside="closeRejectModal()">

                <div class="p-5 flex items-center justify-between shrink-0 bg-primary">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-full bg-danger-bg text-danger flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-danger">
                            Reject Admission Application
                        </h3>
                    </div>
                    <button type="button" @click="closeRejectModal()"
                        class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form :action="'{{ url('admin/students/admissions') }}/' + rejectId + '/reject'" method="POST"
                    class="p-5 space-y-4 text-xs">
                    @csrf
                    <p class="text-theme-text-muted">
                        You are rejecting the admission application for <strong class="text-theme-text font-semibold"
                            x-text="rejectStudentName"></strong>. Please provide a clear rejection reason for feedback.
                    </p>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Rejection Remarks / Reason *</label>
                        <textarea name="remarks" required rows="3"
                            placeholder="e.g. Uploaded Aadhaar photo is unreadable. Please upload a clear photo or PDF."
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs p-3 rounded-lg focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button type="button" @click="closeRejectModal()"
                            class="px-5 py-3 rounded-full bg-secondary text-theme-text text-xs font-normal hover:bg-subtle transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 rounded-full bg-danger text-white text-xs font-normal hover:opacity-90 transition">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL 3: INSTANT DOCUMENT & KYC PREVIEW MODAL               -->
        <!-- ============================================================ -->
        <div x-show="docPreviewModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs transition"
            @keydown.escape.window="closeDocPreview()">

            <div class="rounded-lg bg-primary w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden"
                @click.outside="closeDocPreview()">

                <div class="p-4 flex items-center justify-between shrink-0 bg-primary">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text truncate max-w-lg" x-text="previewDocTitle"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="previewDocUrl" target="_blank"
                            class="px-3.5 py-1.5 rounded-full bg-secondary text-brand text-xs font-normal hover:bg-subtle transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Open in New Tab
                        </a>
                        <button type="button" @click="closeDocPreview()"
                            class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4 bg-secondary flex items-center justify-center grow overflow-auto min-h-[350px]">
                    <template x-if="previewDocIsPdf">
                        <iframe :src="previewDocUrl" class="w-full h-[65vh] rounded-lg"></iframe>
                    </template>
                    <template x-if="!previewDocIsPdf">
                        <img :src="previewDocUrl" :alt="previewDocTitle"
                            class="max-w-full max-h-[70vh] object-contain rounded-lg">
                    </template>
                </div>
            </div>
        </div>

    </div>
@endsection