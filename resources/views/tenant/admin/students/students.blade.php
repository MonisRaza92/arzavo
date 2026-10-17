@extends('layouts.admin')
@section('title', 'Students Directory')

@section('content')
<div x-data="studentsManagement()" class="space-y-6">

    {{-- TOP BANNER / HEADER (CONSISTENT CURRICULUM ARCHITECTURE HEADER) --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Students Directory
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Enrolled academy students, curriculum placements, batch assignments, and instant tuition fee collection.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.curriculum.board') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-[11px] text-brand"></i>
                <span>Curriculum Board</span>
            </a>
            <a href="{{ route('admin.students.admissions') }}"
               class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-[11px]"></i>
                <span>Admissions &amp; Direct Add</span>
            </a>
        </div>
    </div>

    {{-- STATS CARDS (FLAT, NO BORDERS, THEME TOKENS, MAX FONT-SEMIBOLD) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total Students</p>
                <p class="text-2xl font-semibold mt-1 text-theme-text">{{ $students->count() }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Enrolled candidates</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center text-brand text-lg">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Active Students</p>
                <p class="text-2xl font-semibold mt-1 text-success">{{ $students->where('status', 'active')->count() }}</p>
                <p class="text-[11px] text-success mt-0.5 font-normal flex items-center gap-1">
                    <i class="fa-solid fa-circle text-[6px]"></i> Attending batches
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Fees Collected</p>
                <p class="text-2xl font-semibold mt-1 text-success">₹{{ number_format($collectedFees ?? 0, 2) }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Collection ratio: {{ $collectionRatio }}%</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-success-bg flex items-center justify-center text-success text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="rounded-lg bg-primary p-5 flex items-center justify-between transition hover:opacity-95">
            <div>
                <p class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Pending Dues</p>
                <p class="text-2xl font-semibold mt-1 text-warning">₹{{ number_format($pendingFees ?? 0, 2) }}</p>
                <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal">Cumulative outstanding balance</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-warning-bg flex items-center justify-center text-warning text-lg">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    {{-- STUDENTS DIRECTORY CONTAINER (FLAT, NO BORDERS, ROUNDED-LG) --}}
    <div class="rounded-lg bg-primary p-5 space-y-4">
        {{-- TOOLBAR: SEARCH & STATUS FILTER --}}
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Single Live Search Bar (py-3, rounded-full, bg-secondary, NO border) --}}
                <div class="relative w-full sm:w-80">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Search by student, email, phone, class, batch..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 pl-10 rounded-full focus:outline-none font-normal">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
                </div>

                {{-- Status Filter Pills (rounded-full, bg-secondary, NO border) --}}
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
                Showing <span class="font-semibold text-theme-text" x-text="filteredStudents.length"></span> of <span class="font-semibold text-theme-text">{{ $students->count() }}</span> students
            </div>
        </div>

        {{-- SEARCH FEEDBACK STRIP (SECTION 6) --}}
        <div x-show="searchQuery.trim().length > 0" class="p-3.5 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text font-normal">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-brand text-xs"></i>
                <span>Showing results for <span class="font-semibold text-theme-text" x-text="'&ldquo;' + searchQuery + '&rdquo;'"></span> &middot; <span x-text="filteredStudents.length"></span> matches found</span>
            </div>
            <button type="button" @click="searchQuery = ''" class="text-xs text-theme-text-muted hover:text-theme-text font-normal cursor-pointer">
                Clear
            </button>
        </div>

        {{-- TABLE LAYOUT (CLEAN CONTRAST, NO BORDERS, NO DROPDOWN CLIPPING) --}}
        <div class="overflow-x-auto lg:overflow-visible min-h-[380px] pb-36">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal">
                        <th class="py-3 px-4 font-normal rounded-l-lg"><i class="fa-regular fa-user text-brand mr-1"></i> Student Profile</th>
                        <th class="py-3 px-4 font-normal"><i class="fa-solid fa-graduation-cap text-brand mr-1"></i> Curriculum &amp; Batch</th>
                        <th class="py-3 px-4 font-normal"><i class="fa-solid fa-indian-rupee-sign text-brand mr-1"></i> Fee Status &amp; Dues</th>
                        <th class="py-3 px-4 font-normal text-center"><i class="fa-regular fa-calendar-check text-brand mr-1"></i> Attendance</th>
                        <th class="py-3 px-4 font-normal text-center"><i class="fa-solid fa-signal text-brand mr-1"></i> Status</th>
                        <th class="py-3 pr-4 font-normal text-right rounded-r-lg"><i class="fa-solid fa-ellipsis text-brand mr-1"></i> Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0">
                    <template x-for="student in filteredStudents" :key="student.id">
                        <tr class="hover:bg-secondary transition">
                            {{-- Student Profile --}}
                            <td class="py-3 px-4 text-left">
                                <div class="flex items-center gap-3">
                                    <template x-if="student.profile_picture">
                                        <img :src="resolveMedia(student.profile_picture)" :alt="student.fname" class="w-9 h-9 rounded-full object-cover shrink-0">
                                    </template>
                                    <template x-if="!student.profile_picture">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs uppercase shrink-0 bg-secondary text-brand font-semibold">
                                            <span x-text="student.fname.charAt(0) + (student.lname ? student.lname.charAt(0) : '')"></span>
                                        </div>
                                    </template>
                                    <div>
                                        <a :href="'{{ url('admin/student/profile') }}/' + student.username"
                                           class="font-semibold text-theme-text text-xs leading-tight hover:text-brand transition block"
                                           x-text="student.fname + ' ' + (student.lname || '')"></a>
                                        <p class="text-[11px] text-theme-text-muted mt-0.5 font-normal flex items-center gap-1.5">
                                            <span x-text="student.email"></span>
                                            <span class="text-theme-text-muted/60">&bull;</span>
                                            <span x-text="student.number || 'No phone'"></span>
                                        </p>
                                        <template x-if="student.aadhaar_number">
                                            <p class="text-[10px] text-theme-text-muted font-normal mt-0.5">
                                                ID: <span x-text="student.aadhaar_number"></span>
                                            </p>
                                        </template>
                                    </div>
                                </div>
                            </td>

                            {{-- Curriculum & Batch Placement --}}
                            <td class="py-3 px-4 text-left">
                                <div class="space-y-1.5 min-w-[200px]">
                                    {{-- Class & Category Header --}}
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-semibold text-theme-text text-xs" x-text="student.class_name || 'No Class Assigned'"></span>
                                        <template x-if="student.category_name">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-secondary text-brand"
                                                  x-text="student.category_name"></span>
                                        </template>
                                        <template x-if="student.class_teacher_name">
                                            <span class="text-[10px] text-theme-text-muted font-normal" :title="'Class Teacher: ' + student.class_teacher_name">
                                                &bull; <i class="fa-solid fa-chalkboard-user text-[9px] mr-0.5"></i><span x-text="student.class_teacher_name"></span>
                                            </span>
                                        </template>
                                    </div>

                                    {{-- CASE 1: Student is in a Batch --}}
                                    <template x-if="student.batch_name">
                                        <div class="space-y-1">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-medium flex-wrap">
                                                <i class="fa-solid fa-users-rectangle text-[9px]"></i>
                                                <span x-text="student.batch_name"></span>
                                                <template x-if="student.batch_timings">
                                                    <span class="opacity-80" x-text="'• ' + student.batch_timings"></span>
                                                </template>
                                                <template x-if="student.teacher_name">
                                                    <span class="opacity-80" x-text="'• ' + student.teacher_name"></span>
                                                </template>
                                            </div>

                                            {{-- Batch Scheduled Subjects & Individual Timings --}}
                                            <template x-if="student.batch_periods && student.batch_periods.length > 0">
                                                <div class="flex flex-wrap gap-1 mt-0.5">
                                                    <template x-for="(p, pIdx) in student.batch_periods" :key="pIdx">
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text">
                                                            <span class="font-medium" x-text="p.subject_name"></span>
                                                            <template x-if="p.timing">
                                                                <span class="text-theme-text-muted text-[9px]" x-text="'(' + p.timing + ')'"></span>
                                                            </template>
                                                        </span>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- CASE 2: Student has Multiple Direct Subjects (Coaching Mode) --}}
                                    <template x-if="!student.batch_name && student.direct_subjects && student.direct_subjects.length > 0">
                                        <div class="space-y-1">
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary text-brand text-[10px] font-medium">
                                                <i class="fa-solid fa-book-open text-[9px]"></i>
                                                <span>Direct Subjects (<span x-text="student.direct_subjects.length"></span>)</span>
                                            </div>
                                            <div class="flex flex-wrap gap-1 mt-0.5">
                                                <template x-for="(sub, sIdx) in student.direct_subjects" :key="sIdx">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary text-[10px] text-theme-text">
                                                        <span class="font-medium" x-text="sub.subject_name"></span>
                                                        <template x-if="sub.timing">
                                                            <span class="text-theme-text-muted text-[9px]" x-text="'(' + sub.timing + ')'"></span>
                                                        </template>
                                                        <template x-if="sub.teacher_name">
                                                            <span class="text-theme-text-muted text-[9px]" x-text="'• ' + sub.teacher_name"></span>
                                                        </template>
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- CASE 3: No Batch and No Subjects Assigned --}}
                                    <template x-if="!student.batch_name && (!student.direct_subjects || student.direct_subjects.length === 0)">
                                        <span class="text-[10px] text-theme-text-muted italic block">
                                            No batch or subjects assigned
                                        </span>
                                    </template>
                                </div>
                            </td>

                            {{-- Fee Status & Cumulative Dues --}}
                            <td class="py-3 px-4 text-left">
                                <template x-if="student.total_fee > 0">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-xs text-theme-text" x-text="'₹' + student.total_fee.toFixed(2)"></span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal"
                                                  :class="student.due_fee <= 0 ? 'bg-success-bg text-success' : 'bg-warning-bg text-warning'"
                                                  x-text="student.due_fee <= 0 ? 'Paid Up' : ('Due: ₹' + student.due_fee.toFixed(2))"></span>
                                        </div>
                                        <div class="text-[10px] text-theme-text-muted font-normal">
                                            Paid: ₹<span x-text="student.paid_fee.toFixed(2)"></span>
                                            <template x-if="student.fee_due_day">
                                                <span> &bull; Due on <span x-text="student.fee_due_day"></span>th</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="student.total_fee <= 0">
                                    <span class="px-2.5 py-0.5 text-[10px] font-normal text-theme-text-muted bg-secondary rounded-full">
                                        No Fee Plan
                                    </span>
                                </template>
                            </td>

                            {{-- Attendance --}}
                            <td class="py-3 px-4 text-center">
                                <template x-if="student.attendance_rate !== null">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal inline-flex items-center gap-1"
                                          :class="student.attendance_rate >= 75 ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger'">
                                        <i class="fa-solid fa-calendar-check text-[9px]"></i>
                                        <span x-text="student.attendance_rate + '%'"></span>
                                    </span>
                                </template>
                                <template x-if="student.attendance_rate === null">
                                    <span class="text-[11px] text-theme-text-muted font-normal">No logs</span>
                                </template>
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3 px-4 text-center">
                                <button type="button"
                                        @click="toggleStatus(student)"
                                        class="px-2.5 py-0.5 rounded-full text-[11px] font-normal transition cursor-pointer"
                                        :class="student.status === 'active' ? 'bg-success-bg text-success hover:opacity-80' : 'bg-danger-bg text-danger hover:opacity-80'"
                                        x-text="student.status === 'active' ? 'Active' : 'Suspended'">
                                </button>
                            </td>

                            {{-- Actions (Collect Fee Outside + All other actions in 3-dots Dropdown) --}}
                            <td class="py-3 pr-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Primary Action: Collect Fee (Remains outside as requested) --}}
                                    <button type="button"
                                            @click="openCollectFeeModal(student)"
                                            class="px-3.5 py-1.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5 cursor-pointer"
                                            title="Record Fee Payment">
                                        <i class="fa-solid fa-wallet text-[11px]"></i>
                                        <span>Collect Fee</span>
                                    </button>

                                    {{-- Secondary Actions Dropdown --}}
                                    <div x-data="{ open: false, dropUp: false }" @click.outside="open = false" class="relative inline-block text-left">
                                        <button type="button"
                                                @click="
                                                    open = !open;
                                                    if (open) {
                                                        $nextTick(() => {
                                                            const rect = $el.getBoundingClientRect();
                                                            dropUp = (window.innerHeight - rect.bottom) < 260;
                                                        });
                                                    }
                                                "
                                                class="w-8 h-8 rounded-full bg-secondary text-theme-text hover:bg-subtle transition flex items-center justify-center text-xs cursor-pointer"
                                                title="Student Actions">
                                            <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                        </button>

                                        <div x-cloak
                                             x-show="open"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             :class="dropUp ? 'bottom-full mb-2 origin-bottom-right' : 'top-full mt-2 origin-top-right'"
                                             class="absolute right-0 w-52 rounded-lg bg-primary shadow-2xl p-1.5 z-50 space-y-0.5 text-left">

                                            {{-- Edit Details (Popup) --}}
                                            <button type="button"
                                                    @click="openEditStudentModal(student); open = false"
                                                    class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2 transition cursor-pointer">
                                                <i class="fa-solid fa-pen-to-square text-brand text-[11px] w-4 text-center"></i>
                                                <span>Edit Student Details</span>
                                            </button>

                                            {{-- 360° Profile --}}
                                            <a :href="'{{ url('admin/student/profile') }}/' + student.username"
                                               class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2 transition">
                                                <i class="fa-solid fa-user-circle text-theme-text-muted text-[11px] w-4 text-center"></i>
                                                <span>View 360° Profile</span>
                                            </a>

                                            {{-- WhatsApp Fee Reminder (if dues exist and phone available) --}}
                                            <template x-if="student.due_fee > 0 && (student.parent_phone || student.number)">
                                                <a :href="getWhatsAppReminderUrl(student)"
                                                   target="_blank"
                                                   class="w-full px-3 py-2 text-xs font-normal text-success hover:bg-success-bg rounded-md flex items-center gap-2 transition">
                                                    <i class="fa-brands fa-whatsapp text-success text-[11px] w-4 text-center"></i>
                                                    <span>WhatsApp Fee Reminder</span>
                                                </a>
                                            </template>

                                            {{-- Reset Password --}}
                                            <button type="button"
                                                    @click="openResetPasswordModal(student); open = false"
                                                    class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2 transition cursor-pointer">
                                                <i class="fa-solid fa-key text-brand text-[11px] w-4 text-center"></i>
                                                <span>Reset Password</span>
                                            </button>

                                            {{-- Toggle Status --}}
                                            <button type="button"
                                                    @click="toggleStatus(student); open = false"
                                                    class="w-full px-3 py-2 text-xs font-normal text-theme-text hover:bg-secondary rounded-md flex items-center gap-2 transition cursor-pointer">
                                                <i class="fa-solid fa-power-off text-warning text-[11px] w-4 text-center"></i>
                                                <span x-text="student.status === 'active' ? 'Suspend Student' : 'Activate Student'"></span>
                                            </button>

                                            {{-- Demote to User --}}
                                            <button type="button"
                                                    @click="demoteToUser(student); open = false"
                                                    class="w-full px-3 py-2 text-xs font-normal text-danger hover:bg-danger-bg rounded-md flex items-center gap-2 transition cursor-pointer">
                                                <i class="fa-solid fa-user-minus text-[11px] w-4 text-center"></i>
                                                <span>Demote to User</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="filteredStudents.length === 0">
                        <td colspan="6" class="py-12 text-center text-theme-text-muted">
                            <i class="fa-solid fa-user-graduate text-3xl mb-2 text-theme-text-muted/40 block"></i>
                            <p class="text-xs font-normal">No students found matching your criteria.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- EDIT STUDENT DETAILS MODAL (POPUP EXCEPTION: SHADOW-2XL)  --}}
    {{-- ======================================================== --}}
    <div x-cloak
         x-show="editModalOpen"
         @keydown.escape.window="editModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="editModalOpen = false"
             class="relative w-full max-w-xl rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-6 py-5 bg-primary flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-theme-text">Update Student Details</h3>
                        <p class="text-xs text-theme-text-muted font-normal">Edit student information, academic tier placement &amp; fee plan</p>
                    </div>
                </div>
                <button type="button" @click="editModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form :action="'{{ url('admin/student/update') }}/' + (editingStudent ? editingStudent.id : '')"
                  method="POST"
                  class="flex-1 overflow-y-auto px-6 pb-6 space-y-5 scrollbar">
                @csrf

                {{-- Hidden fallbacks for safe parameter submission --}}
                <input type="hidden" name="academic_category_id" :value="selectedCategoryId || ''">
                <input type="hidden" name="class_id" :value="selectedClassId || ''">
                <input type="hidden" name="batch_id" :value="enrollmentType === 'batch' ? (selectedBatchId || '') : ''">
                <template x-if="enrollmentType === 'subjects'">
                    <template x-for="sId in selectedSubjectIds" :key="sId">
                        <input type="hidden" name="subject_ids[]" :value="sId">
                    </template>
                </template>

                {{-- SECTION 1: PERSONAL & IDENTIFICATION --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-id-badge"></i> 1. Personal &amp; Identification
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">First Name <span class="text-danger font-semibold">*</span></label>
                            <input type="text"
                                   name="fname"
                                   x-model="editForm.fname"
                                   required
                                   placeholder="e.g. Rahul"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Last Name <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="lname"
                                   x-model="editForm.lname"
                                   placeholder="e.g. Sharma"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Email Address <span class="text-danger font-semibold">*</span></label>
                            <input type="email"
                                   name="email"
                                   x-model="editForm.email"
                                   required
                                   placeholder="student@example.com"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Phone Number <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="number"
                                   x-model="editForm.number"
                                   placeholder="e.g. 9876543210"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Roll Number / Student ID <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="roll_number"
                                   x-model="editForm.roll_number"
                                   placeholder="e.g. STU-2026-042"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Blood Group <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <select name="blood_group"
                                    x-model="editForm.blood_group"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
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
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Aadhaar / National ID <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="aadhaar_number"
                                   x-model="editForm.aadhaar_number"
                                   placeholder="e.g. 1234 5678 9012"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Account Status <span class="text-danger font-semibold">*</span></label>
                            <select name="status"
                                    x-model="editForm.status"
                                    required
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="active">Active (Enrolled)</option>
                                <option value="suspended">Suspended (Restricted)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PARENT & GUARDIAN DETAILS --}}
                <div class="space-y-3 pt-2">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-users"></i> 2. Parent &amp; Guardian Information
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Father's Name <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="father_name"
                                   x-model="editForm.father_name"
                                   placeholder="Father / Guardian full name"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Mother's Name <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="mother_name"
                                   x-model="editForm.mother_name"
                                   placeholder="Mother full name"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Parent WhatsApp / Phone <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="parent_phone"
                                   x-model="editForm.parent_phone"
                                   placeholder="For fee alerts &amp; reminders"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Emergency Contact <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text"
                                   name="emergency_contact"
                                   x-model="editForm.emergency_contact"
                                   placeholder="Emergency alternate contact"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: ACADEMIC PLACEMENT (BATCH OR MULTIPLE SUBJECTS) --}}
                <div class="space-y-3 pt-2">
                    <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-layer-group"></i> 3. Academic Placement &amp; Timetable <span class="text-theme-text-muted text-[10px] font-normal lowercase">(optional)</span>
                    </h4>

                    {{-- Category --}}
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Academic Category <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                        <select x-model="selectedCategoryId"
                                @change="onCategoryChange()"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="">Select Category</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name" :selected="cat.id == selectedCategoryId"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Class --}}
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Class / Course <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                        <select x-model="selectedClassId"
                                @change="onClassChange()"
                                :disabled="!availableClasses.length"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                            <option value="" x-text="availableClasses.length ? 'Select Class' : 'Select Category first'"></option>
                            <template x-for="cls in availableClasses" :key="cls.id">
                                <option :value="cls.id"
                                        x-text="cls.name + (cls.class_teacher ? ' • Mentor: ' + cls.class_teacher.fname : '')"
                                        :selected="cls.id == selectedClassId"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Mode Toggle (Batch vs Subjects) --}}
                    <div class="space-y-2 pt-1" x-show="selectedClassId">
                        <label class="block text-xs font-normal text-theme-text">Curriculum Assignment Flow</label>
                        <div class="grid grid-cols-2 gap-2 p-1 bg-secondary rounded-full">
                            <button type="button"
                                    @click="enrollmentType = 'batch'"
                                    :class="enrollmentType === 'batch' ? 'bg-brand text-primary font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text font-normal'"
                                    class="py-2 text-xs rounded-full transition text-center cursor-pointer">
                                <i class="fa-solid fa-users-rectangle mr-1.5 text-[11px]"></i> Batch / Section
                            </button>
                            <button type="button"
                                    @click="enrollmentType = 'subjects'"
                                    :class="enrollmentType === 'subjects' ? 'bg-brand text-primary font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text font-normal'"
                                    class="py-2 text-xs rounded-full transition text-center cursor-pointer">
                                <i class="fa-solid fa-book-open mr-1.5 text-[11px]"></i> Multiple Subjects
                            </button>
                        </div>
                    </div>

                    {{-- OPTION 1: BATCH ASSIGNMENT --}}
                    <div x-show="selectedClassId && enrollmentType === 'batch'" class="space-y-2.5">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Assign Batch / Section</label>
                            <select x-model="selectedBatchId"
                                    :disabled="!availableBatches.length"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                                <option value="" x-text="availableBatches.length ? 'Select Batch / Section' : 'No batches in this class'"></option>
                                <template x-for="b in availableBatches" :key="b.id">
                                    <option :value="b.id"
                                            x-text="b.name + ' (' + (b.teacher ? b.teacher.fname : 'No Mentor') + (b.start_time ? ' • ' + b.start_time : '') + ')'"
                                            :selected="b.id == selectedBatchId"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Batch Preview of Subjects and Timings --}}
                        <template x-if="selectedBatch && selectedBatch.batch_subjects && selectedBatch.batch_subjects.length > 0">
                            <div class="p-3 bg-secondary rounded-lg space-y-2 text-xs">
                                <div class="flex items-center justify-between text-theme-text">
                                    <span class="font-medium text-brand flex items-center gap-1.5">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Batch Schedule &amp; Periods
                                    </span>
                                    <span class="text-[10px] text-theme-text-muted" x-text="selectedBatch.batch_subjects.length + ' subject(s) in batch'"></span>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="bs in selectedBatch.batch_subjects" :key="bs.id">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary text-[10px] text-theme-text">
                                            <i class="fa-solid fa-book-open text-brand text-[9px]"></i>
                                            <span class="font-medium" x-text="bs.subject ? bs.subject.name : 'Subject'"></span>
                                            <span class="text-theme-text-muted" x-show="bs.start_time" x-text="'(' + bs.start_time + ' - ' + (bs.end_time || '') + ')'"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- OPTION 2: MULTIPLE INDIVIDUAL SUBJECTS (Coaching Mode) --}}
                    <div x-show="selectedClassId && enrollmentType === 'subjects'" class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-normal text-theme-text">Select Enrolled Subjects</label>
                            <span class="text-[10px] text-brand font-medium" x-text="selectedSubjectIds.length + ' selected'"></span>
                        </div>

                        <template x-if="availableSubjects.length === 0">
                            <p class="text-xs text-theme-text-muted italic p-3 bg-secondary rounded-lg">No subjects configured for this class yet.</p>
                        </template>

                        <div class="space-y-1.5 max-h-48 overflow-y-auto scrollbar">
                            <template x-for="sub in availableSubjects" :key="sub.id">
                                <div @click="toggleSubject(sub.id)"
                                     class="flex items-center justify-between p-2.5 rounded-lg bg-secondary hover:bg-subtle transition cursor-pointer text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox"
                                               :checked="selectedSubjectIds.includes(Number(sub.id))"
                                               class="w-4 h-4 rounded text-brand focus:ring-0 cursor-pointer pointer-events-none">
                                        <div>
                                            <p class="font-medium text-theme-text" x-text="sub.name"></p>
                                            <p class="text-[10px] text-theme-text-muted" x-show="sub.teacher">
                                                <i class="fa-solid fa-chalkboard-user text-[9px] mr-0.5"></i>
                                                <span x-text="sub.teacher ? sub.teacher.fname : ''"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-normal text-theme-text-muted"
                                              x-text="(sub.start_time && sub.end_time) ? (sub.start_time + ' - ' + sub.end_time) : 'Timing: Flexible'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: CUSTOM FEE OVERRIDE / SCHOLARSHIP --}}
                <div class="space-y-3 pt-2">
                    <div>
                        <h4 class="text-xs font-semibold text-brand uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-sliders"></i> 4. Custom Fee Override / Scholarship <span class="text-theme-text-muted text-[10px] font-normal lowercase">(optional)</span>
                        </h4>
                        <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">
                            Entering a fee here sets a custom student override, taking priority over Category, Class, Subject &amp; Batch rates. Leave blank to inherit automatically.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Billing Cadence <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <select name="fee_plan_type"
                                    x-model="editForm.fee_plan_type"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                                <option value="onetime">One-Time</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Override Fee Amount (₹) <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="number"
                                   step="0.01"
                                   name="fee_amount"
                                   x-model="editForm.fee_amount"
                                   placeholder="e.g. 1000 or leave blank to inherit"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Due Day (1-31) <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="number"
                                   name="fee_due_day"
                                   x-model="editForm.fee_due_day"
                                   min="1"
                                   max="31"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Override Reason / Scholarship Note <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                        <input type="text"
                               name="override_reason"
                               x-model="editForm.override_reason"
                               placeholder="e.g. Merit Concession, Staff Child Scholarship"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="editModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- RESET PASSWORD MODAL (POPUP EXCEPTION: SHADOW-2XL)        --}}
    {{-- ======================================================== --}}
    <div x-cloak
         x-show="resetPasswordModalOpen"
         @keydown.escape.window="resetPasswordModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="resetPasswordModalOpen = false"
             class="relative w-full max-w-md rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col">

            {{-- Modal Header --}}
            <div class="px-6 py-5 bg-primary flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-theme-text">Reset Student Password</h3>
                        <p class="text-xs text-theme-text-muted font-normal" x-text="'Set new login credentials for ' + (resettingStudent ? resettingStudent.fname : 'student')"></p>
                    </div>
                </div>
                <button type="button" @click="resetPasswordModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form :action="'{{ url('admin/student/reset-password') }}/' + (resettingStudent ? resettingStudent.id : '')"
                  method="POST"
                  class="p-6 pt-2 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">New Password <span class="text-danger font-semibold">*</span></label>
                    <input type="password"
                           name="password"
                           required
                           minlength="6"
                           placeholder="Enter minimum 6 characters"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    <p class="text-[11px] text-theme-text-muted mt-1.5 font-normal">Student can instantly log into their dashboard using this new password.</p>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="resetPasswordModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- COLLECT FEE PAYMENT MODAL (POPUP EXCEPTION: SHADOW-2XL)  --}}
    {{-- ======================================================== --}}
    <div x-cloak
         x-show="feeModalOpen"
         @keydown.escape.window="feeModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.outside="feeModalOpen = false"
             class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-6 py-5 bg-primary flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base text-theme-text">Collect Student Fee</h3>
                        <p class="text-xs text-theme-text-muted font-normal">Record tuition payment and update student ledger</p>
                    </div>
                </div>
                <button type="button" @click="feeModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form :action="'{{ url('admin/student/fee/payment') }}/' + (selectedStudent ? selectedStudent.id : '')"
                  method="POST"
                  class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                @csrf

                {{-- Student Summary Badge --}}
                <div class="p-4 rounded-lg bg-secondary flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0">
                            <span x-text="selectedStudent ? selectedStudent.fname.charAt(0) : 'S'"></span>
                        </div>
                        <div>
                            <p class="font-semibold text-xs text-theme-text" x-text="selectedStudent ? (selectedStudent.fname + ' ' + (selectedStudent.lname || '')) : ''"></p>
                            <p class="text-[11px] text-theme-text-muted font-normal" x-text="selectedStudent ? selectedStudent.email : ''"></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-theme-text-muted uppercase tracking-wider block font-normal">Pending Due</span>
                        <span class="font-semibold text-sm text-warning" x-text="selectedStudent ? ('₹' + selectedStudent.due_fee.toFixed(2)) : '₹0.00'"></span>
                    </div>
                </div>

                {{-- Payment Amount --}}
                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Amount to Collect (₹) <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.01"
                           name="amount_paid"
                           x-model="paymentAmount"
                           required
                           placeholder="Enter fee amount..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Date <span class="text-danger">*</span></label>
                        <input type="date"
                               name="payment_date"
                               x-model="paymentDate"
                               required
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" required class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="Cash">Cash</option>
                            <option value="UPI / QR">UPI / QR</option>
                            <option value="Online / Net Banking">Online / Net Banking</option>
                            <option value="Debit / Credit Card">Debit / Credit Card</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Transaction Ref / Slip No.</label>
                        <input type="text"
                               name="transaction_id"
                               placeholder="e.g. UPI-92817291"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Status <span class="text-danger">*</span></label>
                        <select name="status" required class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="paid">Paid (Confirmed)</option>
                            <option value="pending">Pending Verification</option>
                        </select>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="feeModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check"></i>
                        <span>Save Payment Record</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden form for status toggling --}}
    <form id="studentStatusToggleForm" action="{{ route('admin.update-student-status') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="id" id="statusToggleStudentId">
    </form>

    {{-- Hidden form for demoting to user --}}
    <form id="studentDemoteForm" action="{{ route('admin.update-student-role') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="id" id="demoteStudentId">
    </form>

</div>

<script>
    function studentsManagement() {
        return {
            students: @json($studentsData),
            categories: @json($categories),

            searchQuery: '',
            statusFilter: 'all',

            // Collect Fee modal state
            feeModalOpen: false,
            selectedStudent: null,
            paymentAmount: '',
            paymentDate: new Date().toISOString().split('T')[0],

            // Edit Student Details modal state
            editModalOpen: false,
            editingStudent: null,
            editForm: {
                fname: '',
                lname: '',
                email: '',
                number: '',
                roll_number: '',
                father_name: '',
                mother_name: '',
                parent_phone: '',
                emergency_contact: '',
                blood_group: '',
                aadhaar_number: '',
                status: 'active',
                fee_plan_type: 'monthly',
                fee_amount: '',
                fee_due_day: 10,
            },

            // Reset Password modal state
            resetPasswordModalOpen: false,
            resettingStudent: null,

            // Cascader state for Edit Modal
            selectedCategoryId: '',
            selectedClassId: '',
            selectedBatchId: '',
            selectedSubjectIds: [],
            enrollmentType: 'batch',

            get filteredStudents() {
                const query = this.searchQuery.toLowerCase().trim();
                return this.students.filter(s => {
                    if (this.statusFilter !== 'all' && s.status !== this.statusFilter) {
                        return false;
                    }
                    if (!query) return true;
                    const fullName = (s.fname + ' ' + (s.lname || '')).toLowerCase();
                    const email = (s.email || '').toLowerCase();
                    const phone = (s.number || '').toLowerCase();
                    const className = (s.class_name || '').toLowerCase();
                    const batchName = (s.batch_name || '').toLowerCase();
                    const aadhaar = (s.aadhaar_number || '').toLowerCase();
                    return fullName.includes(query)
                        || email.includes(query)
                        || phone.includes(query)
                        || className.includes(query)
                        || batchName.includes(query)
                        || aadhaar.includes(query);
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
            get availableBatches() {
                return this.selectedClass ? (this.selectedClass.batches || []) : [];
            },
            get selectedBatch() {
                if (!this.selectedBatchId) return null;
                return this.availableBatches.find(b => b.id == this.selectedBatchId) || null;
            },

            toggleSubject(id) {
                const numId = Number(id);
                if (this.selectedSubjectIds.includes(numId)) {
                    this.selectedSubjectIds = this.selectedSubjectIds.filter(x => x !== numId);
                } else {
                    this.selectedSubjectIds.push(numId);
                }
            },

            onCategoryChange() {
                this.selectedClassId = '';
                this.selectedBatchId = '';
                this.selectedSubjectIds = [];
                if (this.availableClasses.length > 0) {
                    this.selectedClassId = this.availableClasses[0].id;
                    this.onClassChange();
                }
            },
            onClassChange() {
                this.selectedBatchId = '';
                this.selectedSubjectIds = [];
                if (this.enrollmentType === 'batch' && this.availableBatches.length > 0) {
                    this.selectedBatchId = this.availableBatches[0].id;
                }
            },

            openEditStudentModal(student) {
                this.editingStudent = student;
                this.editForm = {
                    fname: student.fname,
                    lname: student.lname || '',
                    email: student.email,
                    number: student.number || '',
                    roll_number: student.roll_number || '',
                    father_name: student.father_name || '',
                    mother_name: student.mother_name || '',
                    parent_phone: student.parent_phone || '',
                    emergency_contact: student.emergency_contact || '',
                    blood_group: student.blood_group || '',
                    aadhaar_number: student.aadhaar_number || '',
                    status: student.status || 'active',
                    fee_plan_type: student.plan_type || 'monthly',
                    fee_amount: student.fee_amount || '',
                    fee_due_day: student.fee_due_day || 10,
                    override_reason: student.override_reason || '',
                };

                this.selectedCategoryId = student.academic_category_id || '';
                this.selectedClassId = student.class_id || '';
                this.selectedBatchId = student.batch_id || '';
                this.selectedSubjectIds = (student.direct_subject_ids && student.direct_subject_ids.length > 0)
                    ? student.direct_subject_ids.map(Number)
                    : (student.subject_id ? [Number(student.subject_id)] : []);
                this.enrollmentType = student.batch_id ? 'batch' : (this.selectedSubjectIds.length > 0 ? 'subjects' : 'batch');

                this.editModalOpen = true;
            },

            openResetPasswordModal(student) {
                this.resettingStudent = student;
                this.resetPasswordModalOpen = true;
            },

            getWhatsAppReminderUrl(student) {
                const phone = (student.parent_phone || student.number || '').replace(/[^0-9]/g, '');
                const dueAmount = Number(student.due_fee || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const text = encodeURIComponent(`Dear Parent/Student, this is a reminder regarding ${student.fname}'s outstanding academy tuition fee of ₹${dueAmount}. Kindly arrange the payment at your earliest convenience. Thank you!`);
                return `https://wa.me/${phone}?text=${text}`;
            },

            openCollectFeeModal(student) {
                this.selectedStudent = student;
                this.paymentAmount = student.due_fee > 0 ? student.due_fee : '';
                this.paymentDate = new Date().toISOString().split('T')[0];
                this.feeModalOpen = true;
            },

            toggleStatus(student) {
                if (confirm(`Change status of student ${student.fname}?`)) {
                    document.getElementById('statusToggleStudentId').value = student.id;
                    document.getElementById('studentStatusToggleForm').submit();
                }
            },

            demoteToUser(student) {
                if (confirm(`Convert student ${student.fname} back to a standard User account? (Academic assignments will remain archived).`)) {
                    document.getElementById('demoteStudentId').value = student.id;
                    document.getElementById('studentDemoteForm').submit();
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
