@extends('layouts.admin')
@section('title', 'Student Profile - ' . $studentProfile->fname . ' ' . $studentProfile->lname)

@section('content')
    <div x-data="studentProfileView()" class="space-y-6">

        {{-- TOP BANNER / HEADER (STATIC PAGE OVERVIEW) --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div
                    class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <div>
                    <h1 class="text-base font-semibold text-theme-text leading-tight">
                        Student 360° Profile &amp; Records Console
                    </h1>
                    <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl font-normal">
                        Comprehensive student management overview covering academic placement, cumulative tuition fee
                        ledger, attendance history, and verified KYC documentation.
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.students') }}"
                    class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                    <span>Back to Students</span>
                </a>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- MULTIPLE GRIDS (OUTSIDE TABS): STUDENT DOSSIER & ACTIONS --}}
        {{-- ======================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- BLOCK 1: STUDENT PERSONAL DOSSIER & IDENTITY (4 COLS) --}}
            <div class="lg:col-span-4 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
                <div>
                    {{-- Header with Avatar, Full Name, Username, Status & Edit CTA --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            @if($studentProfile->profile_picture)
                                <img src="{{ asset($studentProfile->profile_picture) }}" alt="{{ $studentProfile->fname }}"
                                    class="w-14 h-14 rounded-full object-cover shrink-0 shadow-xs">
                            @else
                                <div
                                    class="w-14 h-14 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg uppercase shrink-0 font-semibold shadow-xs">
                                    {{ strtoupper(substr($studentProfile->fname, 0, 1) . substr($studentProfile->lname ?: $studentProfile->fname, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="font-semibold text-base text-theme-text truncate leading-tight">
                                        {{ $studentProfile->fname }} {{ $studentProfile->lname }}
                                    </h2>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-normal {{ $studentProfile->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                        <i
                                            class="fa-solid fa-circle text-[6px] mr-1"></i>{{ ucfirst($studentProfile->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    <span
                                        class="text-xs text-theme-text-muted font-normal">&#64;{{ $studentProfile->username }}</span>
                                    <span class="text-[10px] text-theme-text-muted">&bull;</span>
                                    <span class="text-[11px] text-brand font-normal">ID: #{{ $studentProfile->id }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="editBioModalOpen = true"
                            class="px-2.5 py-1.5 bg-secondary text-brand hover:bg-subtle rounded-full text-xs font-normal cursor-pointer inline-flex items-center gap-1.5 shrink-0 transition"
                            title="Edit Personal Details">
                            <i class="fa-solid fa-user-pen text-[11px]"></i>
                            <span>Edit</span>
                        </button>
                    </div>

                    {{-- All Personal Details in Structured Grid Tiles (2 Columns) --}}
                    <div class="grid grid-cols-2 gap-2 mt-3.5 text-xs">
                        {{-- 1. First Name --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-regular fa-user text-brand mr-1"></i> First Name
                            </span>
                            <p class="font-semibold text-theme-text truncate">{{ $studentProfile->fname }}</p>
                        </div>

                        {{-- 2. Last Name --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-regular fa-user text-brand mr-1"></i> Last Name
                            </span>
                            <p class="font-semibold text-theme-text truncate">{{ $studentProfile->lname ?: 'Not recorded' }}
                            </p>
                        </div>

                        {{-- 3. Student Phone / Mobile --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-solid fa-phone text-brand mr-1"></i> Student Phone
                            </span>
                            @if($studentProfile->number)
                                <p class="font-semibold text-theme-text truncate">{{ $studentProfile->number }}</p>
                            @else
                                <p class="font-normal text-theme-text-muted truncate">Not provided</p>
                            @endif
                        </div>

                        {{-- 4. Email Address --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-regular fa-envelope text-brand mr-1"></i> Email Address
                            </span>
                            <p class="font-semibold text-theme-text truncate">{{ $studentProfile->email }}</p>
                        </div>

                        {{-- 5. Blood Group --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-solid fa-droplet text-danger mr-1"></i> Blood Group
                            </span>
                            <p
                                class="font-semibold {{ $studentProfile->blood_group ? 'text-danger' : 'text-theme-text-muted' }} truncate">
                                {{ $studentProfile->blood_group ?: 'Not recorded' }}
                            </p>
                        </div>

                        {{-- 6. Date of Birth --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-regular fa-calendar text-brand mr-1"></i> Date of Birth
                            </span>
                            <p class="font-semibold text-theme-text truncate">
                                {{ $studentProfile->dob ? \Carbon\Carbon::parse($studentProfile->dob)->format('d M, Y') : 'Not set' }}
                            </p>
                        </div>

                        {{-- 7. City & State --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-solid fa-city text-brand mr-1"></i> City / State
                            </span>
                            <p class="font-normal text-theme-text truncate">
                                {{ ($studentProfile->city || $studentProfile->state) ? trim(($studentProfile->city ?? '') . ($studentProfile->city && $studentProfile->state ? ', ' : '') . ($studentProfile->state ?? '')) : 'Not recorded' }}
                            </p>
                        </div>

                        {{-- 8. Pincode & Country --}}
                        <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-solid fa-map-pin text-brand mr-1"></i> Pincode / Country
                            </span>
                            <p class="font-normal text-theme-text truncate">
                                {{ ($studentProfile->pincode || $studentProfile->country) ? trim(($studentProfile->pincode ?? '') . ($studentProfile->pincode && $studentProfile->country ? ' - ' : '') . ($studentProfile->country ?? '')) : 'Not recorded' }}
                            </p>
                        </div>

                        {{-- 9. Full Street Address (Full Width Span 2) --}}
                        <div class="col-span-2 p-2.5 bg-secondary rounded-lg space-y-0.5">
                            <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                <i class="fa-solid fa-house-chimney text-brand mr-1"></i> Residential Street Address
                            </span>
                            <p class="font-normal text-theme-text leading-relaxed text-xs">
                                {{ $studentProfile->address ?: 'No residential street address recorded in profile.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Note / Record Badge --}}
                <div class="pt-1 flex items-center justify-between text-[11px] text-theme-text-muted font-normal">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-fingerprint text-brand"></i>
                        <span>Record ID: #{{ $studentProfile->id }}</span>
                    </span>
                    <span>
                        Status: <span class="text-success font-semibold">Verified</span>
                    </span>
                </div>
            </div>

            {{-- BLOCK 2: ACADEMIC PLACEMENT & PARENT INFO MERGED (5 COLS) --}}
            <div class="lg:col-span-5 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    {{-- Header with Title & Action Triggers --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-brand text-xs"></i>
                            <div>
                                <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                                    Academic &amp; Guardian
                                </h3>
                                <!-- <p class="text-[11px] text-theme-text-muted font-normal">Curriculum assignment &amp; emergency parent contacts</p> -->
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="editAcademicModalOpen = true"
                                class="px-2.5 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] font-normal cursor-pointer inline-flex items-center gap-1 transition"
                                title="Edit Academic Curriculum">
                                <i class="fa-solid fa-layer-group text-[9px]"></i>
                                <span>Edit Curriculum</span>
                            </button>
                            <button type="button" @click="editParentModalOpen = true"
                                class="px-2.5 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] font-normal cursor-pointer inline-flex items-center gap-1 transition"
                                title="Edit Parent & Guardian Details">
                                <i class="fa-solid fa-users text-[9px]"></i>
                                <span>Edit Parents</span>
                            </button>
                        </div>
                    </div>

                    {{-- Part 1: Academic Curriculum & Placement Metadata --}}
                    <div class="space-y-2.5">
                        {{-- Academic Placement Metadata Tiles (6 balanced tiles) --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                            {{-- 1. Category --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-layer-group text-brand text-[8px] mr-1"></i> Category
                                </span>
                                <p class="font-semibold text-theme-text truncate text-xs">
                                    {{ $studentProfile->academicCategory->name ?? 'Not Assigned' }}
                                </p>
                            </div>

                            {{-- 2. Class / Course --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-book-open text-brand text-[8px] mr-1"></i> Class / Grade
                                </span>
                                <p class="font-semibold text-theme-text truncate text-xs">
                                    {{ $studentProfile->class->name ?? 'Not Assigned' }}
                                </p>
                            </div>

                            {{-- 3. Class Teacher / Mentor --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-chalkboard-user text-brand text-[8px] mr-1"></i> Class Mentor
                                </span>
                                <p class="font-normal text-theme-text truncate text-xs">
                                    {{ ($studentProfile->class && $studentProfile->class->classTeacher) ? ($studentProfile->class->classTeacher->fname . ' ' . ($studentProfile->class->classTeacher->lname ?? '')) : 'Not Assigned' }}
                                </p>
                            </div>

                            {{-- 4. Batch / Placement --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-users-rectangle text-brand text-[8px] mr-1"></i> Batch / Placement
                                </span>
                                <p
                                    class="font-semibold {{ $studentProfile->batch ? 'text-brand' : 'text-theme-text' }} truncate text-xs">
                                    @if($studentProfile->batch)
                                        {{ $studentProfile->batch->name }}
                                    @elseif($studentProfile->directSubjects->isNotEmpty())
                                        Direct ({{ $studentProfile->directSubjects->count() }} Subjects)
                                    @elseif($studentProfile->subject)
                                        {{ $studentProfile->subject->name }}
                                    @else
                                        <span class="text-theme-text-muted font-normal italic">Not Assigned</span>
                                    @endif
                                </p>
                            </div>

                            {{-- 5. Roll No / Student ID --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-hashtag text-brand text-[8px] mr-1"></i> Roll Number / ID
                                </span>
                                <p
                                    class="font-semibold {{ $studentProfile->roll_number ? 'text-brand' : 'text-theme-text-muted' }} truncate text-xs">
                                    {{ $studentProfile->roll_number ?: 'Not assigned' }}
                                </p>
                            </div>

                            {{-- 6. Admission Status --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-user-check text-brand text-[8px] mr-1"></i> Admission Status
                                </span>
                                <p class="font-semibold text-theme-text truncate text-xs">
                                    {{ ucfirst($studentProfile->admission_status ?: 'Confirmed') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Part 2: Parent & Guardian Contacts (Merged) --}}
                    <div>
                        <span
                            class="text-xs font-semibold text-theme-text uppercase tracking-wider block mb-4 flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-brand text-xs"></i> Parent &amp; Guardian
                        </span>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            {{-- Father's Name --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-user-tie text-brand text-[8px] mr-1"></i> Father's Name
                                </span>
                                <p class="font-semibold text-theme-text truncate text-xs">
                                    {{ $studentProfile->father_name ?: 'Not recorded' }}
                                </p>
                            </div>

                            {{-- Mother's Name --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-person-breastfeeding text-brand text-[8px] mr-1"></i> Mother's
                                    Name
                                </span>
                                <p class="font-semibold text-theme-text truncate text-xs">
                                    {{ $studentProfile->mother_name ?: 'Not recorded' }}
                                </p>
                            </div>

                            {{-- Parent WhatsApp --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-brands fa-whatsapp text-success text-[9px] mr-1"></i> Parent WhatsApp
                                </span>
                                @if($studentProfile->parent_phone)
                                    <div class="flex items-center justify-between gap-1">
                                        <span
                                            class="font-semibold text-theme-text truncate text-xs">{{ $studentProfile->parent_phone }}</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $studentProfile->parent_phone) }}"
                                            target="_blank"
                                            class="text-success hover:opacity-80 inline-flex items-center shrink-0"
                                            title="Chat on WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-sm"></i>
                                        </a>
                                    </div>
                                @else
                                    <p class="font-normal text-theme-text-muted text-xs">Not provided</p>
                                @endif
                            </div>

                            {{-- Emergency Phone --}}
                            <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                                <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                                    <i class="fa-solid fa-phone-volume text-brand text-[8px] mr-1"></i> Emergency Phone
                                </span>
                                <p class="font-normal text-theme-text truncate text-xs">
                                    {{ $studentProfile->emergency_contact ?: 'Not provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="pt-2 flex items-center justify-between flex-wrap gap-2 text-[10px] text-theme-text-muted font-normal">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-calendar-check text-brand text-[9px]"></i>
                        <span>Schedule:
                            {{ $studentProfile->batch ? ($studentProfile->batch->days ?: 'Mon - Sat') : 'Regular Batches' }}</span>
                    </span>
                    <span class="flex items-center gap-1 text-success">
                        <i class="fa-solid fa-circle-check text-[7px]"></i>
                        <span>Fee alerts &amp; notices route to parent WhatsApp</span>
                    </span>
                </div>
            </div>

            {{-- BLOCK 3: QUICK ACTIONS & OPERATIONS (3 COLS) --}}
            <div class="lg:col-span-3 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-brand"></i>
                            <span>Operations</span>
                        </h3>
                        <span
                            class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center text-[10px] text-brand">
                            <i class="fa-solid fa-sliders"></i>
                        </span>
                    </div>

                    {{-- Fee Mini Overview Widget --}}
                    <div class="p-3.5 bg-secondary rounded-lg space-y-2">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-[10px] text-theme-text-muted uppercase font-normal tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-receipt text-brand text-[9px]"></i> Fee Overview
                            </span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] font-normal {{ $feeSummary['total_due'] > 0 ? 'bg-warning-bg text-warning' : 'bg-success-bg text-success' }}">
                                {{ $feeSummary['total_due'] > 0 ? 'Dues Pending' : 'All Cleared' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[9px] text-theme-text-muted block font-normal">Cumulative Balance Due</span>
                            <p
                                class="text-xl font-semibold {{ $feeSummary['total_due'] > 0 ? 'text-warning' : 'text-success' }} leading-tight">
                                ₹{{ number_format($feeSummary['total_due'], 2) }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-[10px] pt-1">
                            <div>
                                <span class="text-[9px] text-theme-text-muted block font-normal">Collected</span>
                                <span
                                    class="font-semibold text-success">₹{{ number_format($feeSummary['total_paid'], 2) }}</span>
                            </div>
                            <div>
                                <span class="text-[9px] text-theme-text-muted block font-normal">Total Assessed</span>
                                <span
                                    class="font-semibold text-theme-text">₹{{ number_format($feeSummary['total_accrued'], 2) }}</span>
                            </div>
                        </div>

                        @php
                            $paidPct = $feeSummary['total_accrued'] > 0 ? min(100, round(($feeSummary['total_paid'] / $feeSummary['total_accrued']) * 100)) : 100;
                        @endphp
                        <div class="space-y-1 pt-0.5">
                            <div class="w-full bg-primary rounded-full h-1.5 overflow-hidden">
                                <div class="bg-brand h-1.5 rounded-full transition-all duration-300"
                                    style="width: {{ $paidPct }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[9px] text-theme-text-muted font-normal">
                                <span>{{ $paidPct }}% Received</span>
                                <span>{{ ucfirst($feeSummary['plan_type']) }} Cycle</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-2">
                        {{-- 1. Record Fee Payment --}}
                        <button type="button" @click="feeModalOpen = true"
                            class="w-full px-4 py-2.5 bg-brand text-primary rounded-full font-medium text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                            <i class="fa-solid fa-wallet text-[11px]"></i>
                            <span>Record Fee Payment</span>
                        </button>

                        {{-- 2. WhatsApp Fee Reminder (if balance due and phone present) --}}
                        @if($feeSummary['total_due'] > 0 && ($studentProfile->parent_phone || $studentProfile->number))
                            @php
                                $phoneToRemind = preg_replace('/[^0-9]/', '', $studentProfile->parent_phone ?: $studentProfile->number);
                                $dueAmt = number_format($feeSummary['total_due'], 2);
                                $waText = urlencode("Dear Parent/Student, this is an official reminder regarding {$studentProfile->fname}'s outstanding academy fee of ₹{$dueAmt}. Kindly arrange the payment at your earliest convenience. Thank you!");
                            @endphp
                            <a href="https://wa.me/{{ $phoneToRemind }}?text={{ $waText }}" target="_blank"
                                class="w-full px-4 py-2.5 bg-success text-white rounded-full font-medium text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2 cursor-pointer"
                                title="Send WhatsApp Fee Reminder">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Send Fee Reminder</span>
                            </a>
                        @endif

                        {{-- 3. Edit Personal Bio Details --}}
                        <button type="button" @click="editBioModalOpen = true"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-user-pen text-[11px] text-brand"></i>
                            <span>Edit Personal Details</span>
                        </button>

                        {{-- 4. Override Fee Plan --}}
                        <button type="button" @click="editFeePlanModalOpen = true"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-sliders text-[11px] text-brand"></i>
                            <span>Override Fee Plan</span>
                        </button>

                        {{-- 5. Upload / Update KYC Documents --}}
                        <button type="button" @click="editKYCModalOpen = true"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-file-shield text-[11px] text-brand"></i>
                            <span>Upload KYC &amp; Documents</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- ROW 2 (OUTSIDE TABS): TIMETABLE & KYC IN SAME ROW --}}
        {{-- ======================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- ======================================================== --}}
            {{-- CARD A: CURRICULUM TIMETABLE & BATCH / SUBJECT SCHEDULE (7 COLS) --}}
            {{-- ======================================================== --}}
            <div class="lg:col-span-7 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-subtle">
                        <div class="flex items-center gap-2.5">
                            <span
                                class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                                        Curriculum Timetable &amp; Schedule
                                    </h3>
                                    @if($studentProfile->batch)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-brand/10 text-brand inline-flex items-center gap-1">
                                            <i class="fa-solid fa-users-rectangle text-[8px]"></i> Batch Mode
                                        </span>
                                    @elseif($studentProfile->directSubjects->isNotEmpty() || $studentProfile->subject)
                                        @php
                                            $subCount = $studentProfile->directSubjects->isNotEmpty()
                                                ? $studentProfile->directSubjects->count()
                                                : ($studentProfile->subject ? 1 : 0);
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-brand/10 text-brand inline-flex items-center gap-1">
                                            <i class="fa-solid fa-book-open text-[8px]"></i> {{ $subCount }}
                                            {{ \Illuminate\Support\Str::plural('Subject', $subCount) }} Enrolled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="editAcademicModalOpen = true"
                            class="px-3.5 py-1.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                            <span>Manage Placement</span>
                        </button>
                    </div>

                    {{-- Schedule Body: Direct Batch Or Direct Subjects --}}
                    <div class="mt-3.5">
                        @if($studentProfile->batch)
                            {{-- ============================================== --}}
                            {{-- SCENARIO A: BATCH ASSIGNED (Direct Hero & Table) --}}
                            {{-- ============================================== --}}
                            <div class="space-y-3">
                                {{-- Direct Batch Hero Card --}}
                                <div
                                    class="p-3 bg-secondary rounded-lg flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                                            <i class="fa-solid fa-users-rectangle"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-semibold text-sm text-theme-text">
                                                    {{ $studentProfile->batch->name }}
                                                </h4>
                                                @if($studentProfile->batch->code)
                                                    <span
                                                        class="px-2 py-0.5 rounded-full bg-primary text-theme-text-muted text-[10px] font-mono">
                                                        {{ $studentProfile->batch->code }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p
                                                class="text-[11px] text-theme-text-muted font-normal flex items-center gap-2 mt-0.5">
                                                <span>
                                                    <i class="fa-solid fa-chalkboard-user text-brand text-[9px] mr-1"></i>
                                                    Batch Mentor:
                                                    {{ $studentProfile->batch->teacher ? ($studentProfile->batch->teacher->fname . ' ' . ($studentProfile->batch->teacher->lname ?? '')) : 'No Mentor' }}
                                                </span>
                                                @if($studentProfile->batch->room_number)
                                                    <span>&bull; Room: {{ $studentProfile->batch->room_number }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Total Batch Span & Duration --}}
                                    <div class="text-left sm:text-right">
                                        <div class="text-xs font-semibold text-brand flex items-center sm:justify-end gap-1.5">
                                            <i class="fa-regular fa-clock text-[10px]"></i>
                                            <span>
                                                @if($studentProfile->batch->start_time && $studentProfile->batch->end_time)
                                                    {{ \Carbon\Carbon::parse($studentProfile->batch->start_time)->format('h:i A') }}
                                                    - {{ \Carbon\Carbon::parse($studentProfile->batch->end_time)->format('h:i A') }}
                                                @else
                                                    Schedule Flexible
                                                @endif
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-theme-text-muted mt-0.5">
                                            @if($studentProfile->batch->total_duration_minutes)
                                                <span>Duration: {{ $studentProfile->batch->formatted_duration }}</span>
                                            @endif
                                            @if($studentProfile->batch->break_duration_minutes > 0)
                                                <span class="text-warning">({{ $studentProfile->batch->break_duration_minutes }}m
                                                    break)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                {{-- Direct Batch Subject Periods Table --}}
                                @if($studentProfile->batch->batchSubjects->isNotEmpty())
                                    <div class="overflow-x-auto scrollbar rounded-lg border border-subtle">
                                        <table class="w-full text-left text-xs">
                                            <thead class="bg-secondary/60">
                                                <tr
                                                    class="text-[10px] uppercase tracking-wider text-theme-text-muted border-b border-subtle">
                                                    <th class="py-2 px-2.5 font-normal">Period</th>
                                                    <th class="py-2 px-2.5 font-normal">Subject</th>
                                                    <th class="py-2 px-2.5 font-normal">Period Timing</th>
                                                    <th class="py-2 px-2.5 font-normal">Assigned Teacher</th>
                                                    <th class="py-2 px-2.5 font-normal">Days</th>
                                                    <th class="py-2 px-2.5 font-normal">Room</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-subtle/50">
                                                @foreach($studentProfile->batch->batchSubjects as $pIndex => $bs)
                                                    @php
                                                        $t = $bs->effective_teacher;
                                                        $tName = $t ? ($t->fname . ' ' . ($t->lname ?? '')) : 'Pending';
                                                        $startTime = $bs->effective_start_time ? \Carbon\Carbon::parse($bs->effective_start_time)->format('h:i A') : 'TBD';
                                                        $endTime = $bs->effective_end_time ? \Carbon\Carbon::parse($bs->effective_end_time)->format('h:i A') : 'TBD';
                                                        $rawDays = $bs->effective_days;
                                                        $dayList = is_array($rawDays) ? $rawDays : (is_string($rawDays) ? array_map('trim', explode(',', $rawDays)) : []);
                                                        $allDays = [
                                                            ['key' => 'Sun', 'letter' => 'S'],
                                                            ['key' => 'Mon', 'letter' => 'M'],
                                                            ['key' => 'Tue', 'letter' => 'T'],
                                                            ['key' => 'Wed', 'letter' => 'W'],
                                                            ['key' => 'Thu', 'letter' => 'T'],
                                                            ['key' => 'Fri', 'letter' => 'F'],
                                                            ['key' => 'Sat', 'letter' => 'S'],
                                                        ];
                                                    @endphp
                                                    <tr class="hover:bg-secondary/40 transition">
                                                        <td class="py-2.5 px-2.5 font-medium text-theme-text whitespace-nowrap">
                                                            <span
                                                                class="w-6 h-6 rounded-full bg-brand/10 text-brand inline-flex items-center justify-center text-[11px] font-semibold">
                                                                {{ $pIndex + 1 }}
                                                            </span>
                                                        </td>
                                                        <td class="py-2.5 px-2.5 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fa-solid fa-book-open text-brand text-[10px]"></i>
                                                                <span class="font-semibold text-theme-text text-xs">
                                                                    {{ $bs->subject->name ?? 'Subject #' . ($pIndex + 1) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="py-2.5 px-2.5 whitespace-nowrap">
                                                            <span
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary text-[10px] font-medium text-theme-text">
                                                                <i class="fa-regular fa-clock text-brand text-[9px]"></i>
                                                                <span>{{ $startTime }} - {{ $endTime }}</span>
                                                                @if($bs->duration_minutes > 0)
                                                                    <span
                                                                        class="text-theme-text-muted">({{ $bs->duration_minutes }}m)</span>
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td class="py-2.5 px-2.5 whitespace-nowrap">
                                                            <div class="flex items-center gap-1.5 text-theme-text text-xs">
                                                                <i
                                                                    class="fa-solid fa-chalkboard-user text-theme-text-muted text-[10px]"></i>
                                                                <span>{{ $tName }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="py-2.5 px-2.5 whitespace-nowrap">
                                                            <div class="flex items-center gap-1">
                                                                @foreach($allDays as $d)
                                                                    @php
                                                                        $isActive = in_array($d['key'], $dayList) || in_array(strtolower($d['key']), array_map('strtolower', $dayList));
                                                                    @endphp
                                                                    <span
                                                                        class="w-4 h-4 rounded-full text-[8px] flex items-center justify-center font-medium {{ $isActive ? 'bg-brand text-primary font-semibold' : 'bg-secondary text-theme-text-muted opacity-40' }}"
                                                                        title="{{ $d['key'] }}">
                                                                        {{ $d['letter'] }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="py-2.5 px-2.5 whitespace-nowrap text-theme-text-muted text-xs">
                                                            {{ $bs->room_number ?? $studentProfile->batch->room_number ?? 'Main Room' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-3 bg-secondary rounded-lg text-center text-xs text-theme-text-muted italic">
                                        No individual subject periods have been added to this batch yet.
                                    </div>
                                @endif
                            </div>

                        @elseif($studentProfile->directSubjects->isNotEmpty() || $studentProfile->subject)
                            {{-- ============================================== --}}
                            {{-- SCENARIO B: MULTIPLE DIRECT SUBJECTS (Direct Grid) --}}
                            {{-- ============================================== --}}
                            @php
                                $enrolledSubjects = $studentProfile->directSubjects->isNotEmpty()
                                    ? $studentProfile->directSubjects
                                    : collect([$studentProfile->subject])->filter();
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($enrolledSubjects as $sub)
                                    @php
                                        $t = $sub->teacher;
                                        $tName = $t ? ($t->fname . ' ' . ($t->lname ?? '')) : 'Not Assigned';
                                        $startTime = $sub->start_time ? \Carbon\Carbon::parse($sub->start_time)->format('h:i A') : null;
                                        $endTime = $sub->end_time ? \Carbon\Carbon::parse($sub->end_time)->format('h:i A') : null;
                                        $rawDays = $sub->days;
                                        $dayList = is_array($rawDays) ? $rawDays : (is_string($rawDays) ? array_map('trim', explode(',', $rawDays)) : []);
                                        $allDays = [
                                            ['key' => 'Sun', 'letter' => 'S'],
                                            ['key' => 'Mon', 'letter' => 'M'],
                                            ['key' => 'Tue', 'letter' => 'T'],
                                            ['key' => 'Wed', 'letter' => 'W'],
                                            ['key' => 'Thu', 'letter' => 'T'],
                                            ['key' => 'Fri', 'letter' => 'F'],
                                            ['key' => 'Sat', 'letter' => 'S'],
                                        ];
                                    @endphp
                                    <div class="p-3.5 bg-secondary rounded-lg space-y-2.5 transition">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-[11px] shrink-0">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-xs text-theme-text truncate">
                                                        {{ $sub->name }}
                                                    </h4>
                                                    @if($sub->code)
                                                        <span
                                                            class="text-[9px] text-theme-text-muted font-mono uppercase">{{ $sub->code }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($sub->price > 0)
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-primary text-theme-text text-[10px] font-semibold">
                                                    ₹{{ number_format($sub->price, 0) }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Timings --}}
                                        <div class="flex items-center gap-2 text-[11px] text-theme-text">
                                            <i class="fa-regular fa-clock text-brand text-[10px]"></i>
                                            @if($startTime && $endTime)
                                                <span class="font-medium">{{ $startTime }} - {{ $endTime }}</span>
                                            @else
                                                <span class="text-theme-text-muted italic">Flexible Schedule</span>
                                            @endif
                                        </div>

                                        {{-- Teacher --}}
                                        <div class="flex items-center gap-2 text-[11px] text-theme-text-muted">
                                            <i class="fa-solid fa-chalkboard-user text-brand text-[10px]"></i>
                                            <span class="truncate">{{ $tName }}</span>
                                        </div>

                                        {{-- Active Days --}}
                                        <div class="flex items-center gap-1 pt-2 border-t border-subtle">
                                            @foreach($allDays as $d)
                                                @php
                                                    $isActive = in_array($d['key'], $dayList) || in_array(strtolower($d['key']), array_map('strtolower', $dayList));
                                                @endphp
                                                <span
                                                    class="w-4 h-4 rounded-full text-[8px] flex items-center justify-center font-medium {{ $isActive ? 'bg-brand text-primary font-semibold' : 'bg-primary text-theme-text-muted opacity-40' }}"
                                                    title="{{ $d['key'] }}">
                                                    {{ $d['letter'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            {{-- ============================================== --}}
                            {{-- SCENARIO C: NEITHER BATCH NOR SUBJECTS ASSIGNED --}}
                            {{-- ============================================== --}}
                            <div
                                class="p-4 bg-secondary rounded-lg flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-warning-bg text-warning flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-theme-text">No Batch or Subjects Assigned</p>
                                        <p class="text-[11px] text-theme-text-muted font-normal">
                                            Assign this student to a cohort batch (school mode) or select individual subjects
                                            (coaching mode).
                                        </p>
                                    </div>
                                </div>
                                <button type="button" @click="editAcademicModalOpen = true"
                                    class="px-4 py-2 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition cursor-pointer shrink-0">
                                    <i class="fa-solid fa-plus text-[10px] mr-1"></i> Assign Placement
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="pt-2 flex items-center justify-between flex-wrap gap-2 text-[10px] text-theme-text-muted font-normal border-t border-subtle">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-calendar-check text-brand text-[9px]"></i>
                        <span>Schedule:
                            {{ $studentProfile->batch ? ($studentProfile->batch->days ?: 'Mon - Sat') : 'Subject-wise' }}</span>
                    </span>
                    <span class="text-theme-text-muted">
                        Last updated:
                        {{ $studentProfile->updated_at ? $studentProfile->updated_at->diffForHumans() : 'Recent' }}
                    </span>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- CARD B: KYC & ADMISSION DOCUMENTS CARD (5 COLS) --}}
            {{-- ======================================================== --}}
            <div class="lg:col-span-5 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
                <div>
                    @php
                        $getDocUrl = function ($path) {
                            if (!$path)
                                return '';
                            if (\Illuminate\Support\Str::startsWith($path, 'http'))
                                return $path;
                            if (\Illuminate\Support\Str::startsWith($path, 'storage/'))
                                return asset($path);
                            return asset('storage/' . $path);
                        };
                        $isPdf = function ($path) {
                            return $path && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
                        };
                        $hasAadhaar = !empty($studentProfile->aadhaar_number);
                        $hasFront = !empty($studentProfile->aadhaar_front);
                        $hasBack = !empty($studentProfile->aadhaar_back);
                        $hasMarksheet = !empty($studentProfile->previous_marksheet);
                        $hasSchool = !empty($studentProfile->previous_school);
                        $allDocsReady = $hasAadhaar && $hasFront && $hasBack && $hasMarksheet;
                    @endphp

                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-subtle">
                        <div class="flex items-center gap-2.5">
                            <span
                                class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-file-shield"></i>
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                                        KYC &amp; Documents
                                    </h3>
                                    @if($allDocsReady)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[7px]"></i> Verified
                                        </span>
                                    @elseif($hasAadhaar || $hasFront || $hasBack || $hasMarksheet || $hasSchool)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning inline-flex items-center gap-1">
                                            <i class="fa-solid fa-clock-rotate-left text-[7px]"></i> Partial
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-normal bg-danger-bg text-danger inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[7px]"></i> Missing
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="editKYCModalOpen = true"
                            class="px-3.5 py-1.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <i class="fa-solid fa-cloud-arrow-up text-[10px]"></i>
                            <span>Upload / Edit</span>
                        </button>
                    </div>

                    <div class="space-y-3.5 mt-3.5">
                        {{-- Sub-panel 1: Aadhaar Details & Cards --}}
                        <div class="p-3.5 bg-secondary rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                    <i class="fa-solid fa-id-card text-brand text-[10px]"></i> Aadhaar UID &amp; Scanned
                                    Cards
                                </span>
                                @if($hasAadhaar)
                                    <span
                                        class="text-[10px] font-mono text-theme-text font-semibold">{{ chunk_split($studentProfile->aadhaar_number, 4, ' ') }}</span>
                                @else
                                    <span class="text-[10px] text-danger font-normal italic">UID Missing</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                {{-- Front Card --}}
                                <div class="p-2.5 bg-primary rounded-lg space-y-1.5">
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="font-normal text-theme-text-muted">Front Side</span>
                                        @if($hasFront)
                                            <span class="text-success"><i class="fa-solid fa-check text-[8px]"></i>
                                                Uploaded</span>
                                        @else
                                            <span class="text-theme-text-muted">Pending</span>
                                        @endif
                                    </div>
                                    @if($hasFront)
                                        @php
                                            $frontUrl = $getDocUrl($studentProfile->aadhaar_front);
                                            $frontIsPdf = $isPdf($studentProfile->aadhaar_front);
                                        @endphp
                                        <div class="rounded-md overflow-hidden bg-secondary cursor-pointer h-20 flex items-center justify-center"
                                            @click="openDocPreview('{{ $frontUrl }}', 'Aadhaar Card - Front')">
                                            @if(!$frontIsPdf)
                                                <img src="{{ $frontUrl }}" alt="Front"
                                                    class="w-full h-20 object-cover hover:scale-105 transition">
                                            @else
                                                <div class="text-center p-2"><i
                                                        class="fa-solid fa-file-pdf text-danger text-xl"></i>
                                                    <p class="text-[9px] text-theme-text-muted">PDF</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1 pt-0.5">
                                            <button type="button"
                                                @click="openDocPreview('{{ $frontUrl }}', 'Aadhaar Card - Front')"
                                                class="flex-1 py-1 bg-secondary text-theme-text hover:bg-subtle rounded-full text-[10px] text-center cursor-pointer">View</button>
                                            <a href="{{ $frontUrl }}" download
                                                class="py-1 px-2 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] cursor-pointer"><i
                                                    class="fa-solid fa-download"></i></a>
                                        </div>
                                    @else
                                        <div
                                            class="h-20 rounded-md bg-secondary flex flex-col items-center justify-center p-2 text-center">
                                            <p class="text-[10px] text-theme-text-muted">No front photo</p>
                                            <button type="button" @click="editKYCModalOpen = true"
                                                class="mt-1 text-[9px] text-brand hover:underline cursor-pointer">+
                                                Upload</button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Back Card --}}
                                <div class="p-2.5 bg-primary rounded-lg space-y-1.5">
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="font-normal text-theme-text-muted">Back Side</span>
                                        @if($hasBack)
                                            <span class="text-success"><i class="fa-solid fa-check text-[8px]"></i>
                                                Uploaded</span>
                                        @else
                                            <span class="text-theme-text-muted">Pending</span>
                                        @endif
                                    </div>
                                    @if($hasBack)
                                        @php
                                            $backUrl = $getDocUrl($studentProfile->aadhaar_back);
                                            $backIsPdf = $isPdf($studentProfile->aadhaar_back);
                                        @endphp
                                        <div class="rounded-md overflow-hidden bg-secondary cursor-pointer h-20 flex items-center justify-center"
                                            @click="openDocPreview('{{ $backUrl }}', 'Aadhaar Card - Back')">
                                            @if(!$backIsPdf)
                                                <img src="{{ $backUrl }}" alt="Back"
                                                    class="w-full h-20 object-cover hover:scale-105 transition">
                                            @else
                                                <div class="text-center p-2"><i
                                                        class="fa-solid fa-file-pdf text-danger text-xl"></i>
                                                    <p class="text-[9px] text-theme-text-muted">PDF</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1 pt-0.5">
                                            <button type="button"
                                                @click="openDocPreview('{{ $backUrl }}', 'Aadhaar Card - Back')"
                                                class="flex-1 py-1 bg-secondary text-theme-text hover:bg-subtle rounded-full text-[10px] text-center cursor-pointer">View</button>
                                            <a href="{{ $backUrl }}" download
                                                class="py-1 px-2 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] cursor-pointer"><i
                                                    class="fa-solid fa-download"></i></a>
                                        </div>
                                    @else
                                        <div
                                            class="h-20 rounded-md bg-secondary flex flex-col items-center justify-center p-2 text-center">
                                            <p class="text-[10px] text-theme-text-muted">No back photo</p>
                                            <button type="button" @click="editKYCModalOpen = true"
                                                class="mt-1 text-[9px] text-brand hover:underline cursor-pointer">+
                                                Upload</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Sub-panel 2: Past Education & Marksheet --}}
                        <div class="p-3.5 bg-secondary rounded-lg space-y-2.5">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                        <i class="fa-solid fa-graduation-cap text-brand text-[10px]"></i> Previous School
                                    </span>
                                    <button type="button" @click="editKYCModalOpen = true"
                                        class="text-[9px] text-brand hover:underline cursor-pointer">Change</button>
                                </div>
                                <div class="p-2 bg-primary rounded-md">
                                    <p class="text-xs font-semibold text-theme-text truncate">
                                        {{ $studentProfile->previous_school ?: 'Not recorded' }}</p>
                                </div>
                            </div>

                            <div class="p-2.5 bg-primary rounded-lg space-y-1.5">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="font-normal text-theme-text-muted flex items-center gap-1">
                                        <i class="fa-solid fa-file-lines text-brand text-[9px]"></i> Previous Marksheet
                                    </span>
                                    @if($hasMarksheet)
                                        <span class="text-success"><i class="fa-solid fa-check text-[8px]"></i> Uploaded</span>
                                    @else
                                        <span class="text-theme-text-muted">Pending</span>
                                    @endif
                                </div>

                                @if($hasMarksheet)
                                    @php
                                        $marksheetUrl = $getDocUrl($studentProfile->previous_marksheet);
                                        $marksheetIsPdf = $isPdf($studentProfile->previous_marksheet);
                                    @endphp
                                    <div class="flex items-center justify-between gap-2 p-1.5 bg-secondary rounded-md">
                                        <div class="flex items-center gap-2 truncate cursor-pointer"
                                            @click="openDocPreview('{{ $marksheetUrl }}', 'Previous Marksheet')">
                                            <i
                                                class="fa-solid {{ $marksheetIsPdf ? 'fa-file-pdf text-danger' : 'fa-file-image text-brand' }} text-base"></i>
                                            <span class="text-[11px] font-normal text-theme-text truncate">Marksheet File</span>
                                        </div>
                                        <div class="flex items-center gap-1 shrink-0">
                                            <button type="button"
                                                @click="openDocPreview('{{ $marksheetUrl }}', 'Previous Marksheet')"
                                                class="px-2 py-0.5 bg-primary text-theme-text hover:bg-subtle rounded-full text-[10px] cursor-pointer">View</button>
                                            <a href="{{ $marksheetUrl }}" download
                                                class="px-2 py-0.5 bg-primary text-brand hover:bg-subtle rounded-full text-[10px] cursor-pointer"><i
                                                    class="fa-solid fa-download"></i></a>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="py-2.5 px-2 rounded-md bg-secondary flex items-center justify-between text-center">
                                        <span class="text-[10px] text-theme-text-muted">No marksheet file</span>
                                        <button type="button" @click="editKYCModalOpen = true"
                                            class="text-[10px] text-brand hover:underline cursor-pointer">+ Upload</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KYC Card Footer --}}
                <div
                    class="pt-2 flex items-center justify-between flex-wrap gap-2 text-[10px] text-theme-text-muted font-normal border-t border-subtle">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-brand text-[9px]"></i>
                        <span>Encrypted &amp; Secure Storage</span>
                    </span>
                    <span class="flex items-center gap-1 {{ $allDocsReady ? 'text-success' : 'text-warning' }}">
                        <i
                            class="fa-solid {{ $allDocsReady ? 'fa-circle-check text-[7px]' : 'fa-circle-exclamation text-[7px]' }}"></i>
                        <span>{{ $allDocsReady ? 'All Documents Verified' : 'Pending Verification' }}</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- STANDALONE FLOATING PILL TABS BAR (OUTSIDE OF BLOCKS) --}}
        {{-- ======================================================== --}}
        <div class="flex items-center gap-2 p-1.5 bg-secondary rounded-full w-full sm:w-fit overflow-x-auto scrollbar">
            <button type="button" @click="activeTab = 'ledger'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'ledger' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
                <i class="fa-solid fa-file-invoice-dollar text-[12px]"></i>
                <span>Fee Statement &amp; Ledger</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]"
                    :class="activeTab === 'ledger' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                    {{ $studentProfile->feePayments->count() }}
                </span>
            </button>

            <button type="button" @click="activeTab = 'attendance'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'attendance' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
                <i class="fa-solid fa-calendar-check text-[12px]"></i>
                <span>Attendance Logs</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]"
                    :class="activeTab === 'attendance' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                    {{ $totalAttendance }}
                </span>
            </button>

            <button type="button" @click="activeTab = 'orders'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'orders' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
                <i class="fa-solid fa-bag-shopping text-[12px]"></i>
                <span>Digital Store Orders</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]"
                    :class="activeTab === 'orders' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                    {{ $studentProfile->orders->count() }}
                </span>
            </button>

            <button type="button" @click="activeTab = 'activity'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'activity' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
                <i class="fa-solid fa-shield-halved text-[12px]"></i>
                <span>Account &amp; Security</span>
            </button>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 1: FEE STATEMENT & PAYMENTS LEDGER (CUMULATIVE DUES) --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'ledger'" class="space-y-5">
            {{-- MULTIPLE GRIDS: FEE SUMMARY METRIC CARDS (MOVED FROM TOP INTO FEE TAB) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Card 1: Total Assessed Fee --}}
                <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total
                                Assessed Fee</span>
                            <span
                                class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </span>
                        </div>
                        <p class="text-2xl font-semibold text-theme-text mt-1">
                            ₹{{ number_format($feeSummary['total_accrued'], 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1">
                        <span>{{ $feeSummary['cycles_count'] }}
                            {{ \Illuminate\Support\Str::plural('cycle', $feeSummary['cycles_count']) }}
                            ({{ ucfirst($feeSummary['plan_type']) }})</span>
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-brand font-normal">Accrued</span>
                    </div>
                </div>

                {{-- Card 2: Fee Payments Received --}}
                <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Fee
                                Payments Received</span>
                            <span
                                class="w-8 h-8 rounded-full bg-success-bg text-success flex items-center justify-center text-xs">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                        </div>
                        <p class="text-2xl font-semibold text-success mt-1">
                            ₹{{ number_format($feeSummary['total_paid'], 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1">
                        <span class="text-success flex items-center gap-1">
                            <i class="fa-solid fa-circle text-[6px]"></i> Verified receipts
                        </span>
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text font-normal">{{ $studentProfile->feePayments->count() }}
                            {{ \Illuminate\Support\Str::plural('receipt', $studentProfile->feePayments->count()) }}</span>
                    </div>
                </div>

                {{-- Card 3: Cumulative Balance Due --}}
                <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Cumulative
                                Balance Due</span>
                            <span
                                class="w-8 h-8 rounded-full {{ $feeSummary['total_due'] > 0 ? 'bg-warning-bg text-warning' : 'bg-success-bg text-success' }} flex items-center justify-center text-xs">
                                <i
                                    class="fa-solid {{ $feeSummary['total_due'] > 0 ? 'fa-clock-rotate-left' : 'fa-check' }}"></i>
                            </span>
                        </div>
                        <div class="flex items-baseline gap-2 mt-1">
                            <p
                                class="text-2xl font-semibold {{ $feeSummary['total_due'] > 0 ? 'text-warning' : 'text-success' }}">
                                ₹{{ number_format($feeSummary['total_due'], 2) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-normal pt-1">
                        <span class="text-theme-text-muted">Carried over balance</span>
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] {{ $feeSummary['total_due'] > 0 ? 'bg-warning-bg text-warning' : 'bg-success-bg text-success' }}">
                            {{ $feeSummary['total_due'] > 0 ? 'Balance Due' : 'All Cleared' }}
                        </span>
                    </div>
                </div>

                {{-- Card 4: Fee Plan Specification & Actions --}}
                <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Fee Plan
                                Specification</span>
                            <button type="button" @click="editFeePlanModalOpen = true"
                                class="px-2.5 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] font-normal cursor-pointer inline-flex items-center gap-1 transition"
                                title="Override Plan">
                                <i class="fa-solid fa-sliders text-[9px]"></i>
                                <span>Override</span>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <p class="text-xl font-semibold text-theme-text">{{ ucfirst($feeSummary['plan_type']) }} Plan
                            </p>
                            @if($feeSummary['is_override'])
                                <span
                                    class="px-2 py-0.5 rounded-full text-[9px] font-normal bg-brand/10 text-brand inline-flex items-center gap-1">
                                    <i class="fa-solid fa-asterisk text-[7px]"></i> Override
                                </span>
                            @elseif($feeSummary['has_plan'])
                                <span
                                    class="px-2 py-0.5 rounded-full text-[9px] font-normal bg-secondary text-theme-text-muted">{{ $feeSummary['source_label'] }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-normal bg-danger-bg text-danger">No
                                    Plan</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-[11px] text-theme-text-muted font-normal space-y-0.5 pt-1">
                        <div class="flex items-center justify-between">
                            <span>Rate: <strong
                                    class="text-theme-text font-semibold">₹{{ number_format($feeSummary['cycle_amount'] ?? 0, 2) }}</strong></span>
                            <span>Due: <strong class="text-theme-text font-semibold">Every
                                    {{ $feeSummary['due_day'] ? $feeSummary['due_day'] . 'th' : '1st' }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Month-by-month Statement Table Block --}}
            <div class="rounded-lg bg-primary p-5 space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <h3
                            class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-days text-brand"></i> Month-by-Month Statement &amp; Accrual
                            History
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">
                            Tuition accrues monthly without resetting. Unpaid past dues carry over to reflect total
                            cumulative liability.
                        </p>
                    </div>
                    <button type="button" @click="feeModalOpen = true"
                        class="px-4 py-2 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Record Payment</span>
                    </button>
                </div>

                <div class="overflow-x-auto scrollbar">
                    <table class="w-full min-w-[680px] text-xs text-left">
                        <thead>
                            <tr
                                class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal whitespace-nowrap">
                                <th class="py-3 px-4 font-normal rounded-l-lg"><i
                                        class="fa-regular fa-calendar-days text-brand mr-1"></i> Billing Cycle</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-regular fa-clock text-brand mr-1"></i> Due
                                    Date</th>
                                <th class="py-3 px-4 font-normal"><i
                                        class="fa-solid fa-indian-rupee-sign text-brand mr-1"></i> Billed Amount</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-circle-check text-brand mr-1"></i>
                                    Amount Paid</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-scale-balanced text-brand mr-1"></i>
                                    Cycle Balance</th>
                                <th class="py-3 pr-4 font-normal text-right rounded-r-lg"><i
                                        class="fa-solid fa-signal text-brand mr-1"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            @forelse($feeSummary['statement'] as $cycle)
                                <tr class="hover:bg-secondary transition whitespace-nowrap">
                                    <td class="py-3 px-4 text-theme-text font-semibold">
                                        {{ $cycle['cycle_name'] }}
                                        @if(!empty($cycle['is_current']))
                                            <span
                                                class="ml-1.5 px-2 py-0.5 text-[9px] rounded-full bg-brand text-primary font-normal">Current
                                                Cycle</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted font-normal">
                                        {{ \Carbon\Carbon::parse($cycle['due_date'])->format('d M, Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text font-normal">
                                        ₹{{ number_format($cycle['billed'], 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-success font-normal">
                                        ₹{{ number_format($cycle['paid'], 2) }}
                                    </td>
                                    <td
                                        class="py-3 px-4 font-normal {{ $cycle['balance'] > 0 ? 'text-warning font-semibold' : 'text-theme-text-muted' }}">
                                        ₹{{ number_format($cycle['balance'], 2) }}
                                    </td>
                                    <td class="py-3 pr-4 text-right">
                                        @if($cycle['status'] === 'paid')
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success">Paid</span>
                                        @elseif($cycle['status'] === 'partial')
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning">Partial</span>
                                        @elseif($cycle['status'] === 'overdue')
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-danger-bg text-danger">Overdue</span>
                                        @else
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-secondary text-theme-text">Upcoming</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-theme-text-muted font-normal">
                                        No billing cycles accrued yet. Assign a fee plan to activate the ledger.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment Receipts History Table Block --}}
            <div class="rounded-lg bg-primary p-5 space-y-4">
                <div>
                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-receipt text-brand"></i> Payment Receipts &amp; Transaction Slips
                    </h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">
                        Every physical slip, online transaction, or cash fee payment recorded in the institute.
                    </p>
                </div>

                <div class="overflow-x-auto scrollbar">
                    <table class="w-full min-w-[680px] text-xs text-left">
                        <thead>
                            <tr
                                class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal whitespace-nowrap">
                                <th class="py-3 px-4 font-normal rounded-l-lg"><i
                                        class="fa-solid fa-barcode text-brand mr-1"></i> Receipt ID / Ref</th>
                                <th class="py-3 px-4 font-normal"><i
                                        class="fa-regular fa-calendar-check text-brand mr-1"></i> Payment Date</th>
                                <th class="py-3 px-4 font-normal"><i
                                        class="fa-solid fa-indian-rupee-sign text-brand mr-1"></i> Amount Paid</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-credit-card text-brand mr-1"></i>
                                    Method</th>
                                <th class="py-3 px-4 font-normal text-center"><i
                                        class="fa-solid fa-shield-check text-brand mr-1"></i> Status</th>
                                <th class="py-3 pr-4 font-normal text-right rounded-r-lg"><i
                                        class="fa-solid fa-print text-brand mr-1"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            @forelse($studentProfile->feePayments as $payment)
                                                    <tr class="hover:bg-secondary transition whitespace-nowrap">
                                                        <td class="py-3 px-4 text-theme-text font-normal">
                                                            <span
                                                                class="font-semibold">{{ $payment->transaction_id ?: ('FEE-' . $payment->id) }}</span>
                                                            @if($payment->notes)
                                                                <span
                                                                    class="block text-[10px] text-theme-text-muted font-normal">{{ $payment->notes }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 px-4 text-theme-text-muted font-normal">
                                                            {{ \Carbon\Carbon::parse($payment->payment_date ?: $payment->created_at)->format('d M, Y') }}
                                                        </td>
                                                        <td class="py-3 px-4 text-success font-semibold">
                                                            ₹{{ number_format($payment->amount_paid, 2) }}
                                                        </td>
                                                        <td class="py-3 px-4 text-theme-text-muted font-normal">
                                                            {{ $payment->payment_method ?: 'Cash' }}
                                                        </td>
                                                        <td class="py-3 px-4 text-center">
                                                            <span
                                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal {{ $payment->status === 'paid' ? 'bg-success-bg text-success' : 'bg-warning-bg text-warning' }}">
                                                                {{ ucfirst($payment->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="py-3 pr-4 text-right">
                                                            <button type="button" @click="openReceiptModal({{ json_encode([
                                    'transaction_id' => $payment->transaction_id ?: ('FEE-' . $payment->id),
                                    'student_name' => $studentProfile->fname . ' ' . ($studentProfile->lname ?? ''),
                                    'roll_number' => $studentProfile->roll_number ?: 'N/A',
                                    'email' => $studentProfile->email,
                                    'phone' => $studentProfile->number ?: 'N/A',
                                    'category' => $studentProfile->academicCategory->name ?? 'General',
                                    'class' => $studentProfile->class->name ?? 'N/A',
                                    'batch' => $studentProfile->batch->name ?? 'General',
                                    'amount_paid' => number_format($payment->amount_paid, 2),
                                    'payment_date' => \Carbon\Carbon::parse($payment->payment_date ?: $payment->created_at)->format('d M, Y'),
                                    'payment_method' => ucfirst($payment->payment_method ?: 'Cash'),
                                    'status' => ucfirst($payment->status),
                                ]) }})"
                                                                class="px-3.5 py-1.5 bg-secondary text-theme-text hover:bg-subtle transition rounded-full text-xs font-normal inline-flex items-center gap-1.5 cursor-pointer">
                                                                <i class="fa-solid fa-receipt text-[11px] text-brand"></i>
                                                                <span>Print Slip</span>
                                                            </button>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-theme-text-muted font-normal">
                                        No fee payment transactions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 3: ATTENDANCE HISTORY LOGS (MULTI-GRID BLOCKS) --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'attendance'" class="space-y-5">
            {{-- ATTENDANCE STATS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-lg bg-primary p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Attendance
                            Rate</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-semibold text-theme-text">
                        {{ $attendanceRate !== null ? ($attendanceRate . '%') : 'No logs' }}
                    </p>
                    <p class="text-[11px] text-theme-text-muted font-normal">Active session percentage</p>
                </div>
                <div class="rounded-lg bg-primary p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Sessions
                            Attended</span>
                        <span
                            class="w-8 h-8 rounded-full bg-success-bg flex items-center justify-center text-success text-xs">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-semibold text-success">{{ $presentAttendance }}</p>
                    <p class="text-[11px] text-success font-normal">Marked present</p>
                </div>
                <div class="rounded-lg bg-primary p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Missed
                            Sessions</span>
                        <span
                            class="w-8 h-8 rounded-full bg-danger-bg flex items-center justify-center text-danger text-xs">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-semibold text-danger">{{ $absentAttendance }}</p>
                    <p class="text-[11px] text-theme-text-muted font-normal">{{ $lateAttendance }} marked late</p>
                </div>
            </div>

            {{-- Attendance Logs Table Block --}}
            <div class="rounded-lg bg-primary p-5 space-y-4">
                <div>
                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-clipboard-user text-brand"></i> Detailed Attendance Logs
                    </h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">
                        Daily classroom attendance records for assigned batch lectures.
                    </p>
                </div>

                <div class="overflow-x-auto scrollbar">
                    <table class="w-full min-w-[550px] text-xs text-left">
                        <thead>
                            <tr
                                class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal whitespace-nowrap">
                                <th class="py-3 px-4 font-normal rounded-l-lg"><i
                                        class="fa-regular fa-calendar-days text-brand mr-1"></i> Date</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-users text-brand mr-1"></i> Class /
                                    Batch</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-user-check text-brand mr-1"></i>
                                    Marked By</th>
                                <th class="py-3 pr-4 font-normal text-right rounded-r-lg"><i
                                        class="fa-solid fa-signal text-brand mr-1"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            @forelse($attendances as $att)
                                <tr class="hover:bg-secondary transition whitespace-nowrap">
                                    <td class="py-3 px-4 text-theme-text font-semibold">
                                        {{ \Carbon\Carbon::parse($att->date ?: $att->created_at)->format('d M, Y (l)') }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted font-normal">
                                        {{ $studentProfile->batch->name ?? 'Default Batch' }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted font-normal">
                                        Teacher / Admin
                                    </td>
                                    <td class="py-3 pr-4 text-right">
                                        @php $st = strtolower($att->status); @endphp
                                        @if(in_array($st, ['present', 'p']))
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-success-bg text-success">Present</span>
                                        @elseif(in_array($st, ['late', 'l']))
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning">Late</span>
                                        @else
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-danger-bg text-danger">Absent</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-theme-text-muted font-normal">
                                        No attendance sessions logged for this student yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 4: DIGITAL ORDERS & PURCHASES (MULTI-GRID BLOCKS) --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'orders'" class="space-y-5">
            {{-- ORDERS OVERVIEW STATS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-lg bg-primary p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Total
                            Purchases</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-semibold text-theme-text">{{ $studentProfile->orders->count() }}</p>
                    <p class="text-[11px] text-theme-text-muted font-normal">Digital store and bookstore orders</p>
                </div>
                <div class="rounded-lg bg-primary p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Lifetime
                            Spend</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-receipt"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-semibold text-brand">₹{{ number_format($totalDigitalSpend, 2) }}</p>
                    <p class="text-[11px] text-theme-text-muted font-normal">Cumulative transaction volume</p>
                </div>
            </div>

            {{-- Digital Orders Table Block --}}
            <div class="rounded-lg bg-primary p-5 space-y-4">
                <div>
                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-bag-shopping text-brand"></i> Digital Store Orders
                    </h3>
                    <p class="text-[11px] text-theme-text-muted font-normal mt-0.5">
                        Order history, digital downloads, and package enrollments.
                    </p>
                </div>

                <div class="overflow-x-auto scrollbar">
                    <table class="w-full min-w-[550px] text-xs text-left">
                        <thead>
                            <tr
                                class="bg-secondary text-theme-text-muted uppercase tracking-wider text-[11px] font-normal whitespace-nowrap">
                                <th class="py-3 px-4 font-normal rounded-l-lg"><i
                                        class="fa-solid fa-box text-brand mr-1"></i> Order ID</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-regular fa-calendar text-brand mr-1"></i>
                                    Date</th>
                                <th class="py-3 px-4 font-normal"><i class="fa-solid fa-cart-flatbed text-brand mr-1"></i>
                                    Items</th>
                                <th class="py-3 px-4 font-normal"><i
                                        class="fa-solid fa-indian-rupee-sign text-brand mr-1"></i> Total Amount</th>
                                <th class="py-3 pr-4 font-normal text-right rounded-r-lg"><i
                                        class="fa-solid fa-shield-check text-brand mr-1"></i> Payment Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            @forelse($studentProfile->orders as $order)
                                <tr class="hover:bg-secondary transition whitespace-nowrap">
                                    <td class="py-3 px-4 text-theme-text font-semibold">
                                        #{{ $order->order_number ?: $order->id }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted font-normal">
                                        {{ $order->created_at ? $order->created_at->format('d M, Y') : 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text font-normal">
                                        {{ $order->items->count() }} item{{ $order->items->count() == 1 ? '' : 's' }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text font-semibold">
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </td>
                                    <td class="py-3 pr-4 text-right">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-normal {{ $order->payment_status === 'paid' ? 'bg-success-bg text-success' : 'bg-warning-bg text-warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-theme-text-muted font-normal">
                                        No digital store purchases recorded.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 5: ACCOUNT & LOGIN HISTORY (MULTI-GRID BLOCKS) --}}
        {{-- ======================================================== --}}
        <div x-show="activeTab === 'activity'" class="space-y-5">
            {{-- SECURITY STATS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-5 rounded-lg bg-primary space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal">Last Authentication</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </span>
                    </div>
                    <p class="font-semibold text-theme-text text-lg">
                        {{ $studentProfile->last_login ? \Carbon\Carbon::parse($studentProfile->last_login)->diffForHumans() : 'Never' }}
                    </p>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        {{ $studentProfile->last_login ? \Carbon\Carbon::parse($studentProfile->last_login)->format('d M, Y h:i A') : 'No sessions recorded' }}
                    </p>
                </div>

                <div class="p-5 rounded-lg bg-primary space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal">Account Created</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-user-plus"></i>
                        </span>
                    </div>
                    <p class="font-semibold text-theme-text text-lg">
                        {{ $studentProfile->created_at ? $studentProfile->created_at->format('d M, Y') : 'N/A' }}
                    </p>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        {{ $studentProfile->created_at ? $studentProfile->created_at->diffForHumans() : '' }}
                    </p>
                </div>

                <div class="p-5 rounded-lg bg-primary space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal">Email Status</span>
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-xs">
                            <i class="fa-solid fa-envelope-circle-check"></i>
                        </span>
                    </div>
                    <p
                        class="font-semibold text-lg {{ $studentProfile->email_verified_at ? 'text-success' : 'text-theme-text-muted' }}">
                        {{ $studentProfile->email_verified_at ? 'Verified' : 'Unverified' }}
                    </p>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        {{ $studentProfile->email_verified_at ? $studentProfile->email_verified_at->format('d M, Y') : 'Direct academy registration' }}
                    </p>
                </div>
            </div>

            {{-- Security Actions Block --}}
            <div
                class="rounded-lg bg-primary p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h4 class="text-xs font-semibold text-theme-text flex items-center gap-2">
                        <i class="fa-solid fa-key text-brand"></i>
                        <span>Student Login Credentials &amp; Access Recovery</span>
                    </h4>
                    <p class="text-[11px] text-theme-text-muted font-normal">
                        Direct password overwrite by institute admin. Generates immediate login access for student or
                        parent.
                    </p>
                </div>
                <button type="button" @click="resetPasswordModalOpen = true"
                    class="px-5 py-2.5 bg-secondary text-theme-text hover:bg-subtle transition rounded-full text-xs font-normal inline-flex items-center gap-2 cursor-pointer shrink-0">
                    <i class="fa-solid fa-key text-[11px] text-brand"></i>
                    <span>Reset Password</span>
                </button>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- 1. EDIT STUDENT BIO & IDENTITY MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="editBioModalOpen" @keydown.escape.window="editBioModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="editBioModalOpen = false"
                class="relative w-full max-w-xl rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Update Student Bio</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Edit personal profile, contact information
                                &amp; status</p>
                        </div>
                    </div>
                    <button type="button" @click="editBioModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.admin-student-update', $studentProfile->id) }}" method="POST"
                    class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf
                    <input type="hidden" name="form_section" value="bio">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">First Name <span
                                    class="text-danger font-semibold">*</span></label>
                            <input type="text" name="fname" value="{{ $studentProfile->fname }}" required
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Last Name <span
                                    class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text" name="lname" value="{{ $studentProfile->lname }}"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Email Address <span
                                    class="text-danger font-semibold">*</span></label>
                            <input type="email" name="email" value="{{ $studentProfile->email }}" required
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Student Phone <span
                                    class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text" name="number" value="{{ $studentProfile->number }}"
                                placeholder="e.g. 09198599490"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Roll No / ID</label>
                            <input type="text" name="roll_number" value="{{ $studentProfile->roll_number }}"
                                placeholder="e.g. STU-042"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Blood Group</label>
                            <select name="blood_group"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="">Select Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                    <option value="{{ $bg }}" {{ $studentProfile->blood_group === $bg ? 'selected' : '' }}>
                                        {{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Date of Birth</label>
                            <input type="date" name="dob"
                                value="{{ $studentProfile->dob ? \Carbon\Carbon::parse($studentProfile->dob)->format('Y-m-d') : '' }}"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Aadhaar / National ID</label>
                            <input type="text" name="aadhaar_number" value="{{ $studentProfile->aadhaar_number }}"
                                placeholder="e.g. 1234 5678 9012"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Account Status <span
                                    class="text-danger font-semibold">*</span></label>
                            <select name="status" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="active" {{ $studentProfile->status === 'active' ? 'selected' : '' }}>Active
                                    (Enrolled)</option>
                                <option value="suspended" {{ $studentProfile->status === 'suspended' ? 'selected' : '' }}>
                                    Suspended (Restricted)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Previous School / Institute</label>
                        <input type="text" name="previous_school" value="{{ $studentProfile->previous_school }}"
                            placeholder="Previous school or college attended"
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Residential Street Address</label>
                        <input type="text" name="address" value="{{ $studentProfile->address }}"
                            placeholder="House / Street / Colony"
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">City</label>
                            <input type="text" name="city" value="{{ $studentProfile->city }}" placeholder="City"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">State</label>
                            <input type="text" name="state" value="{{ $studentProfile->state }}" placeholder="State"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Pincode</label>
                            <input type="text" name="pincode" value="{{ $studentProfile->pincode }}" placeholder="Pincode"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editBioModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            <span>Save Personal Details</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- 2. EDIT PARENT & GUARDIAN MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="editParentModalOpen" @keydown.escape.window="editParentModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="editParentModalOpen = false"
                class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Parent &amp; Guardian Details</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Update guardian contacts and emergency
                                telephone</p>
                        </div>
                    </div>
                    <button type="button" @click="editParentModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.admin-student-update', $studentProfile->id) }}" method="POST"
                    class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf
                    <input type="hidden" name="form_section" value="parents">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Father's Name</label>
                            <input type="text" name="father_name" value="{{ $studentProfile->father_name }}"
                                placeholder="Father / Guardian full name"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Mother's Name</label>
                            <input type="text" name="mother_name" value="{{ $studentProfile->mother_name }}"
                                placeholder="Mother full name"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Parent WhatsApp / Phone</label>
                            <input type="text" name="parent_phone" value="{{ $studentProfile->parent_phone }}"
                                placeholder="e.g. 09198599490"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                            <span class="text-[10px] text-theme-text-muted font-normal mt-1 block">Used for direct WhatsApp
                                fee reminders.</span>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Emergency Contact</label>
                            <input type="text" name="emergency_contact" value="{{ $studentProfile->emergency_contact }}"
                                placeholder="Alternate emergency phone"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editParentModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            <span>Save Parent Details</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- 3. EDIT ACADEMIC PLACEMENT MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="editAcademicModalOpen" @keydown.escape.window="editAcademicModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="editAcademicModalOpen = false"
                class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Curriculum &amp; Batch Placement</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Assign student to academic category, class,
                                subject &amp; batch</p>
                        </div>
                    </div>
                    <button type="button" @click="editAcademicModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.admin-student-update', $studentProfile->id) }}" method="POST"
                    class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf
                    <input type="hidden" name="form_section" value="academic">
                    <input type="hidden" name="academic_category_id" :value="selectedCategoryId || ''">
                    <input type="hidden" name="class_id" :value="selectedClassId || ''">
                    <input type="hidden" name="batch_id" :value="enrollmentType === 'batch' ? (selectedBatchId || '') : ''">
                    <template x-if="enrollmentType === 'subjects'">
                        <template x-for="sId in selectedSubjectIds" :key="sId">
                            <input type="hidden" name="subject_ids[]" :value="sId">
                        </template>
                    </template>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Academic Category</label>
                        <select x-model="selectedCategoryId" @change="onCategoryChange()"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="">Select Category</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name" :selected="cat.id == selectedCategoryId"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Class / Grade / Course</label>
                        <select x-model="selectedClassId" @change="onClassChange()" :disabled="!availableClasses.length"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                            <option value="" x-text="availableClasses.length ? 'Select Class' : 'Select Category first'">
                            </option>
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
                            <button type="button" @click="enrollmentType = 'batch'"
                                :class="enrollmentType === 'batch' ? 'bg-brand text-primary font-medium shadow-xs' : 'text-theme-text-muted hover:text-theme-text font-normal'"
                                class="py-2 text-xs rounded-full transition text-center cursor-pointer">
                                <i class="fa-solid fa-users-rectangle mr-1.5 text-[11px]"></i> Batch / Section
                            </button>
                            <button type="button" @click="enrollmentType = 'subjects'"
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
                            <select x-model="selectedBatchId" :disabled="!availableBatches.length"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                                <option value=""
                                    x-text="availableBatches.length ? 'Select Batch / Section' : 'No batches in this class'">
                                </option>
                                <template x-for="b in availableBatches" :key="b.id">
                                    <option :value="b.id"
                                        x-text="b.name + ' (' + (b.teacher ? b.teacher.fname : 'No Mentor') + (b.start_time ? ' • ' + b.start_time : '') + ')'"
                                        :selected="b.id == selectedBatchId"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Batch Preview of Subjects and Timings --}}
                        <template
                            x-if="selectedBatch && selectedBatch.batch_subjects && selectedBatch.batch_subjects.length > 0">
                            <div class="p-3 bg-secondary rounded-lg space-y-2 text-xs">
                                <div class="flex items-center justify-between text-theme-text">
                                    <span class="font-medium text-brand flex items-center gap-1.5">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Batch Schedule &amp; Periods
                                    </span>
                                    <span class="text-[10px] text-theme-text-muted"
                                        x-text="selectedBatch.batch_subjects.length + ' subject(s) in batch'"></span>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="bs in selectedBatch.batch_subjects" :key="bs.id">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary text-[10px] text-theme-text">
                                            <i class="fa-solid fa-book-open text-brand text-[9px]"></i>
                                            <span class="font-medium"
                                                x-text="bs.subject ? bs.subject.name : 'Subject'"></span>
                                            <span class="text-theme-text-muted" x-show="bs.start_time"
                                                x-text="'(' + bs.start_time + ' - ' + (bs.end_time || '') + ')'"></span>
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
                            <span class="text-[10px] text-brand font-medium"
                                x-text="selectedSubjectIds.length + ' selected'"></span>
                        </div>

                        <template x-if="availableSubjects.length === 0">
                            <p class="text-xs text-theme-text-muted italic p-3 bg-secondary rounded-lg">No subjects
                                configured for this class yet.</p>
                        </template>

                        <div class="space-y-1.5 max-h-48 overflow-y-auto scrollbar">
                            <template x-for="sub in availableSubjects" :key="sub.id">
                                <div @click="toggleSubject(sub.id)"
                                    class="flex items-center justify-between p-2.5 rounded-lg bg-secondary hover:bg-subtle transition cursor-pointer text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" :checked="selectedSubjectIds.includes(Number(sub.id))"
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Roll No / Student ID</label>
                            <input type="text" name="roll_number" value="{{ $studentProfile->roll_number }}"
                                placeholder="e.g. STU-042"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Admission Status</label>
                            <select name="admission_status"
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="confirmed" {{ strtolower($studentProfile->admission_status ?? 'confirmed') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="provisional" {{ strtolower($studentProfile->admission_status ?? '') === 'provisional' ? 'selected' : '' }}>Provisional</option>
                                <option value="pending" {{ strtolower($studentProfile->admission_status ?? '') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                            </select>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editAcademicModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            <span>Save Placement</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- 4. STUDENT FEE OVERRIDE / CONCESSION & INHERITANCE MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="editFeePlanModalOpen" @keydown.escape.window="editFeePlanModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="editFeePlanModalOpen = false"
                class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Student Fee Override / Concession</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Apply custom rate for scholarships or reset
                                to curriculum hierarchy</p>
                        </div>
                    </div>
                    <button type="button" @click="editFeePlanModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.students.fee-plans.override', $studentProfile->id) }}" method="POST"
                    class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf

                    {{-- Student Summary Card --}}
                    <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between gap-3 text-xs">
                        <div>
                            <div class="font-semibold text-theme-text">{{ $studentProfile->fname }}
                                {{ $studentProfile->lname }}</div>
                            <div class="text-[10px] text-theme-text-muted">
                                {{ $studentProfile->class ? $studentProfile->class->name : 'No Class' }} &bull;
                                {{ $studentProfile->academicCategory ? $studentProfile->academicCategory->name : 'No Category' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-theme-text-muted block">Current Resolution:</span>
                            <span class="font-semibold text-brand text-xs">{{ $feeSummary['source_label'] }}</span>
                        </div>
                    </div>

                    {{-- Action Hidden Input --}}
                    <input type="hidden" name="action" :value="overrideIsCustom ? 'override' : 'reset'">

                    {{-- Override Mode Toggles --}}
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="overrideIsCustom = true"
                            class="px-4 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="overrideIsCustom ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Custom Rate / Concession
                        </button>
                        <button type="button" @click="overrideIsCustom = false"
                            class="px-4 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="!overrideIsCustom ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                            Inherit Hierarchy Plan
                        </button>
                    </div>

                    {{-- 1. Custom Override Form --}}
                    <div x-show="overrideIsCustom" class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Custom Fee (₹) <span
                                        class="text-danger font-semibold">*</span></label>
                                <input type="number" step="0.01" min="0" name="amount"
                                    value="{{ $feePlan ? $feePlan->amount : ($feeSummary['cycle_amount'] ?? '') }}"
                                    :required="overrideIsCustom" :disabled="!overrideIsCustom"
                                    placeholder="e.g. 0 for Free or 500"
                                    class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                                <span class="text-[10px] text-theme-text-muted mt-1 block">Set ₹0 for 100% scholarship.
                                    Overrides master plan.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Cadence <span
                                        class="text-danger font-semibold">*</span></label>
                                <select name="plan_type" :required="overrideIsCustom" :disabled="!overrideIsCustom"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                    <option value="monthly" {{ (($feePlan && $feePlan->plan_type === 'monthly') || ($feeSummary['plan_type'] ?? '') === 'monthly') ? 'selected' : '' }}>Monthly Cadence
                                    </option>
                                    <option value="quarterly" {{ (($feePlan && $feePlan->plan_type === 'quarterly') || ($feeSummary['plan_type'] ?? '') === 'quarterly') ? 'selected' : '' }}>Quarterly
                                        Cadence</option>
                                    <option value="yearly" {{ (($feePlan && $feePlan->plan_type === 'yearly') || ($feeSummary['plan_type'] ?? '') === 'yearly') ? 'selected' : '' }}>Yearly Cadence
                                    </option>
                                    <option value="onetime" {{ (($feePlan && $feePlan->plan_type === 'onetime') || ($feeSummary['plan_type'] ?? '') === 'onetime') ? 'selected' : '' }}>One-Time
                                        Assessment</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Monthly Due Day (1-31) <span
                                        class="text-danger font-semibold">*</span></label>
                                <input type="number" name="due_day" min="1" max="31"
                                    value="{{ $feePlan ? $feePlan->due_day : ($feeSummary['due_day'] ?? 10) }}"
                                    :required="overrideIsCustom" :disabled="!overrideIsCustom"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                            </div>
                            <div>
                                <label class="block text-xs font-normal text-theme-text mb-1.5">Start Date <span
                                        class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                                <input type="date" name="start_date"
                                    value="{{ $feePlan && $feePlan->start_date ? \Carbon\Carbon::parse($feePlan->start_date)->format('Y-m-d') : ($studentProfile->created_at ? $studentProfile->created_at->format('Y-m-d') : date('Y-m-d')) }}"
                                    :disabled="!overrideIsCustom"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Reason / Concession Note <span
                                    class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="text" name="override_reason"
                                value="{{ $feePlan ? $feePlan->override_reason : ($feeSummary['override_reason'] ?? '') }}"
                                :disabled="!overrideIsCustom" placeholder="e.g. Merit Scholarship, Staff Child, Concession"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    {{-- 2. Reset to Inherit Form --}}
                    <div x-show="!overrideIsCustom" class="p-4 bg-secondary rounded-lg space-y-2 text-xs">
                        <div class="flex items-center gap-2 text-brand font-semibold">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Inherit Dynamic Hierarchy Plan</span>
                        </div>
                        <p class="text-theme-text-muted font-normal leading-relaxed">
                            By resetting, <strong>{{ $studentProfile->fname }}</strong> will stop having a custom rate and
                            will dynamically inherit whatever fee plan is set for their <strong>Category, Class, Subject, or
                                Batch</strong>. If you later change the Class fee, this student will update automatically!
                        </p>
                        @if($feeSummary['has_plan'] && !$feeSummary['is_override'])
                            <div class="p-2.5 rounded-md bg-primary/40 text-[11px] text-success flex items-center gap-2">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>This student is currently already inheriting from
                                    {{ $feeSummary['source_label'] }}.</span>
                            </div>
                        @endif
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editFeePlanModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                            <i class="fa-solid fa-check"></i>
                            <span x-text="overrideIsCustom ? 'Save Fee Override' : 'Confirm Reset to Inherited'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- RESET PASSWORD MODAL (POPUP EXCEPTION: SHADOW-2XL) --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="resetPasswordModalOpen" @keydown.escape.window="resetPasswordModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="resetPasswordModalOpen = false"
                class="relative w-full max-w-md rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Reset Student Password</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Set new credentials for
                                {{ $studentProfile->fname }}</p>
                        </div>
                    </div>
                    <button type="button" @click="resetPasswordModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.admin-student-reset-password', $studentProfile->id) }}" method="POST"
                    class="p-6 pt-2 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">New Password <span
                                class="text-danger font-semibold">*</span></label>
                        <input type="password" name="password" required minlength="6"
                            placeholder="Enter at least 6 characters"
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        <p class="text-[11px] text-theme-text-muted mt-1.5 font-normal">Student can instantly log into their
                            dashboard using this new password.</p>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="button" @click="resetPasswordModalOpen = false"
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
        {{-- PRINTABLE FEE RECEIPT MODAL (POPUP EXCEPTION: SHADOW-2XL) --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="receiptModalOpen" @keydown.escape.window="receiptModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="receiptModalOpen = false"
                class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Printable Slip Section --}}
                <div id="printableFeeReceipt" class="p-6 space-y-5 bg-primary text-theme-text">
                    {{-- Slip Header --}}
                    <div class="flex items-start justify-between pb-4 border-b-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm text-theme-text">ACADEMY TUITION FEE RECEIPT</h3>
                                <p class="text-[11px] text-theme-text-muted font-normal">Official verified payment voucher
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-success-bg text-success"
                                x-text="currentReceipt ? currentReceipt.status : 'PAID'"></span>
                            <p class="text-[10px] text-theme-text-muted font-normal mt-1"
                                x-text="'Ref: ' + (currentReceipt ? currentReceipt.transaction_id : '')"></p>
                        </div>
                    </div>

                    {{-- Student & Placement Card --}}
                    <div class="p-4 rounded-lg bg-secondary space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-theme-text-muted">Student Name:</span>
                            <span class="font-semibold text-theme-text"
                                x-text="currentReceipt ? currentReceipt.student_name : ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-theme-text-muted">Roll No / Student ID:</span>
                            <span class="font-normal text-theme-text"
                                x-text="currentReceipt ? currentReceipt.roll_number : 'N/A'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-theme-text-muted">Academic Class &amp; Batch:</span>
                            <span class="font-normal text-theme-text"
                                x-text="(currentReceipt ? currentReceipt.class : '') + ' &bull; ' + (currentReceipt ? currentReceipt.batch : '')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-theme-text-muted">Email / Phone:</span>
                            <span class="font-normal text-theme-text"
                                x-text="(currentReceipt ? currentReceipt.email : '') + ' / ' + (currentReceipt ? currentReceipt.phone : '')"></span>
                        </div>
                    </div>

                    {{-- Payment Details --}}
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1">
                            <span class="text-theme-text-muted">Payment Date:</span>
                            <span class="text-theme-text font-normal"
                                x-text="currentReceipt ? currentReceipt.payment_date : ''"></span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-theme-text-muted">Payment Method:</span>
                            <span class="text-theme-text font-normal"
                                x-text="currentReceipt ? currentReceipt.payment_method : 'Cash'"></span>
                        </div>
                        <div class="flex justify-between py-2 border-t-0 bg-secondary px-3 rounded-lg text-sm mt-2">
                            <span class="font-semibold text-theme-text">Total Amount Paid:</span>
                            <span class="font-semibold text-success text-base"
                                x-text="'₹' + (currentReceipt ? currentReceipt.amount_paid : '0.00')"></span>
                        </div>
                    </div>

                    <p class="text-[10px] text-theme-text-muted text-center italic">
                        This is a computer-generated fee acknowledgement receipt. No signature is required.
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="p-4 bg-secondary flex items-center justify-end gap-3">
                    <button type="button" @click="receiptModalOpen = false"
                        class="px-5 py-2.5 bg-primary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Close
                    </button>
                    <button type="button" @click="printReceipt()"
                        class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-print"></i>
                        <span>Print Receipt</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- COLLECT FEE PAYMENT MODAL (POPUP EXCEPTION: SHADOW-2XL) --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="feeModalOpen" @keydown.escape.window="feeModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="feeModalOpen = false"
                class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">Record Student Payment</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Collect tuition fee and credit student
                                balance</p>
                        </div>
                    </div>
                    <button type="button" @click="feeModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form action="{{ route('admin.admin-student-fee-payment', $studentProfile->id) }}" method="POST"
                    class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf

                    <div class="p-4 rounded-lg bg-secondary flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-xs text-theme-text">{{ $studentProfile->fname }}
                                {{ $studentProfile->lname }}</p>
                            <p class="text-[11px] text-theme-text-muted font-normal">{{ $studentProfile->email }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-[10px] text-theme-text-muted uppercase tracking-wider block font-normal">Current
                                Balance Due</span>
                            <span
                                class="font-semibold text-sm text-warning">₹{{ number_format($feeSummary['total_due'], 2) }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Amount to Collect (₹) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount_paid"
                            value="{{ $feeSummary['total_due'] > 0 ? $feeSummary['total_due'] : '' }}" required
                            placeholder="Enter fee amount..."
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select name="payment_method" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
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
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Transaction Ref / Slip
                                No.</label>
                            <input type="text" name="transaction_id" placeholder="e.g. UPI-92817291"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Status <span
                                    class="text-danger">*</span></label>
                            <select name="status" required
                                class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="paid">Paid (Confirmed)</option>
                                <option value="pending">Pending Verification</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="feeModalOpen = false"
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

        {{-- ======================================================== --}}
        {{-- EDIT & UPLOAD KYC / ADMISSION DOCUMENTS MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="editKYCModalOpen" @keydown.escape.window="editKYCModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.outside="editKYCModalOpen = false"
                class="relative w-full max-w-xl rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                {{-- Modal Header --}}
                <div class="px-6 py-5 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-theme-text">KYC &amp; Admission Documents</h3>
                            <p class="text-xs text-theme-text-muted font-normal">Aadhaar UID, scanned identification cards,
                                and marksheet transcript</p>
                        </div>
                    </div>
                    <button type="button" @click="editKYCModalOpen = false"
                        class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.admin-student-update', $studentProfile->id) }}" method="POST"
                    enctype="multipart/form-data" class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                    @csrf
                    <input type="hidden" name="form_section" value="kyc">

                    {{-- 1. Aadhaar UID --}}
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">
                            Aadhaar Number (12-Digit UID)
                        </label>
                        <input type="text" name="aadhaar_number" value="{{ $studentProfile->aadhaar_number }}"
                            placeholder="e.g. 1234 5678 9012" maxlength="20"
                            class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal font-mono">
                        <span class="text-[10px] text-theme-text-muted font-normal mt-1 block">Enter official 12-digit
                            Indian National Identification number.</span>
                    </div>

                    {{-- 2. Aadhaar Front & Back Documents --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                        {{-- Aadhaar Front File --}}
                        <div class="p-3.5 bg-secondary rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-medium text-theme-text">Aadhaar Front Side</label>
                                @if($studentProfile->aadhaar_front)
                                    <span class="text-[10px] text-success font-normal"><i
                                            class="fa-solid fa-check text-[8px]"></i> Uploaded</span>
                                @else
                                    <span class="text-[10px] text-theme-text-muted font-normal">Optional</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal">Photo &amp; name face (JPG, PNG, PDF up
                                to 10MB)</p>
                            <input type="file" name="aadhaar_front" accept="image/jpeg,image/png,image/webp,application/pdf"
                                class="w-full text-xs text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:font-normal file:bg-primary file:text-theme-text file:hover:bg-subtle file:cursor-pointer cursor-pointer">
                            @if($studentProfile->aadhaar_front)
                                <div class="pt-1 text-[11px] text-theme-text-muted flex items-center gap-1">
                                    <i class="fa-solid fa-paperclip text-brand text-[10px]"></i>
                                    <span class="truncate">Current: {{ basename($studentProfile->aadhaar_front) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Aadhaar Back File --}}
                        <div class="p-3.5 bg-secondary rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-medium text-theme-text">Aadhaar Back Side</label>
                                @if($studentProfile->aadhaar_back)
                                    <span class="text-[10px] text-success font-normal"><i
                                            class="fa-solid fa-check text-[8px]"></i> Uploaded</span>
                                @else
                                    <span class="text-[10px] text-theme-text-muted font-normal">Optional</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal">Address details face (JPG, PNG, PDF up
                                to 10MB)</p>
                            <input type="file" name="aadhaar_back" accept="image/jpeg,image/png,image/webp,application/pdf"
                                class="w-full text-xs text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:font-normal file:bg-primary file:text-theme-text file:hover:bg-subtle file:cursor-pointer cursor-pointer">
                            @if($studentProfile->aadhaar_back)
                                <div class="pt-1 text-[11px] text-theme-text-muted flex items-center gap-1">
                                    <i class="fa-solid fa-paperclip text-brand text-[10px]"></i>
                                    <span class="truncate">Current: {{ basename($studentProfile->aadhaar_back) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. Previous School & Marksheet --}}
                    <div class="space-y-3.5 pt-2">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Previous School / Coaching
                                Name</label>
                            <input type="text" name="previous_school" value="{{ $studentProfile->previous_school }}"
                                placeholder="e.g. St. Xavier's High School / Allen Career Institute"
                                class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>

                        <div class="p-3.5 bg-secondary rounded-lg space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-medium text-theme-text">Previous Marksheet /
                                    Certificate</label>
                                @if($studentProfile->previous_marksheet)
                                    <span class="text-[10px] text-success font-normal"><i
                                            class="fa-solid fa-check text-[8px]"></i> Uploaded</span>
                                @else
                                    <span class="text-[10px] text-theme-text-muted font-normal">Optional</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal">Previous class transcript or result
                                sheet (JPG, PNG, PDF up to 10MB)</p>
                            <input type="file" name="previous_marksheet"
                                accept="image/jpeg,image/png,image/webp,application/pdf"
                                class="w-full text-xs text-theme-text file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:font-normal file:bg-primary file:text-theme-text file:hover:bg-subtle file:cursor-pointer cursor-pointer">
                            @if($studentProfile->previous_marksheet)
                                <div class="pt-1 text-[11px] text-theme-text-muted flex items-center gap-1">
                                    <i class="fa-solid fa-paperclip text-brand text-[10px]"></i>
                                    <span class="truncate">Current: {{ basename($studentProfile->previous_marksheet) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editKYCModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            <span>Save KYC &amp; Documents</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- DOCUMENT FULL-SIZE PREVIEW & LIGHTBOX MODAL --}}
        {{-- ======================================================== --}}
        <div x-cloak x-show="docPreviewModalOpen" @keydown.escape.window="docPreviewModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-xs">
            <div @click.outside="docPreviewModalOpen = false"
                class="relative w-full max-w-4xl rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[92vh]">

                {{-- Modal Header --}}
                <div class="px-5 py-4 bg-primary flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-brand text-sm">
                            <i class="fa-solid fa-file-shield"></i>
                        </span>
                        <h3 class="font-semibold text-sm text-theme-text" x-text="docPreviewTitle"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="docPreviewUrl" target="_blank"
                            class="px-3.5 py-1.5 bg-secondary text-theme-text hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1.5 transition"
                            title="Open original document in new browser tab">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-brand"></i>
                            <span>New Tab</span>
                        </a>
                        <a :href="docPreviewUrl" download
                            class="px-3.5 py-1.5 bg-secondary text-theme-text hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1.5 transition"
                            title="Download original file">
                            <i class="fa-solid fa-download text-[11px] text-brand"></i>
                            <span>Download</span>
                        </a>
                        <button type="button" @click="docPreviewModalOpen = false"
                            class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-sm transition cursor-pointer ml-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal Body: Full Image or Iframe PDF --}}
                <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-secondary/30 min-h-[400px]">
                    <template x-if="!docPreviewIsPdf">
                        <img :src="docPreviewUrl" :alt="docPreviewTitle"
                            class="max-w-full max-h-[75vh] object-contain rounded-md shadow-xs">
                    </template>
                    <template x-if="docPreviewIsPdf">
                        <iframe :src="docPreviewUrl" class="w-full h-[75vh] rounded-md border-0 bg-primary"></iframe>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <script>
        function studentProfileView() {
            return {
                activeTab: 'ledger',
                feeModalOpen: false,
                editModalOpen: false,
                editBioModalOpen: false,
                editParentModalOpen: false,
                editAcademicModalOpen: false,
                editFeePlanModalOpen: false,
                editKYCModalOpen: false,
                docPreviewModalOpen: false,
                docPreviewUrl: '',
                docPreviewTitle: '',
                docPreviewIsPdf: false,

                openDocPreview(url, title) {
                    this.docPreviewUrl = url;
                    this.docPreviewTitle = title;
                    this.docPreviewIsPdf = (url || '').toLowerCase().split('?')[0].endsWith('.pdf');
                    this.docPreviewModalOpen = true;
                },
                overrideIsCustom: true,
                resetPasswordModalOpen: false,
                receiptModalOpen: false,
                currentReceipt: null,

                openReceiptModal(data) {
                    this.currentReceipt = data;
                    this.receiptModalOpen = true;
                },
                printReceipt() {
                    window.print();
                },

                categories: @json($categories),

                selectedCategoryId: '{{ $studentProfile->academic_category_id }}',
                selectedClassId: '{{ $studentProfile->class_id }}',
                selectedBatchId: '{{ $studentProfile->batch_id }}',
                selectedSubjectIds: @json($studentProfile->directSubjects->isNotEmpty() ? $studentProfile->directSubjects->pluck('id')->toArray() : ($studentProfile->subject_id ? [(int) $studentProfile->subject_id] : [])),
                enrollmentType: '{{ $studentProfile->batch_id ? 'batch' : ($studentProfile->directSubjects->isNotEmpty() || $studentProfile->subject_id ? 'subjects' : 'batch') }}',

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
                }
            };
        }
    </script>
@endsection