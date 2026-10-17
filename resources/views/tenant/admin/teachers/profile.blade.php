@extends('layouts.admin')
@section('title', 'Faculty Profile - ' . $teacher->fname . ' ' . $teacher->lname)

@section('content')
<style>
    [x-cloak] { display: none !important; }
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; padding: 0 !important; color: black !important; }
        .payslip-print-container { display: block !important; border: 1px solid #e5e7eb !important; box-shadow: none !important; border-radius: 8px !important; }
    }
</style>

@php
    $currentPayroll = $payrolls->first(fn($p) => $p->month == now()->month && $p->year == now()->year);
    $currentBreakdown = $currentPayroll ? ($currentPayroll->breakdown ?: ['deductions' => [], 'bonuses' => [], 'reimbursements' => []]) : ['deductions' => [], 'bonuses' => [], 'reimbursements' => []];
@endphp

<script>
    window.teacherProfilePayrollData = {
        teacherId: {{ $teacher->id }},
        teacherName: @json($teacher->fname . ' ' . $teacher->lname),
        designation: @json($teacher->designation ?: 'Faculty Member'),
        joiningDate: @json($teacher->joining_date ? $teacher->joining_date->format('Y-m-d') : ''),
        baseSalary: {{ (float)$baseSalary }},
        salaryType: @json($salaryStructure?->salary_type ?: ($teacher->salary_type ?: 'monthly')),
        fixedDeductions: @json($fixedDeductions),
        fixedBonuses: @json($fixedBonuses),
        currentMonth: {{ now()->month }},
        currentYear: {{ now()->year }},
        currentMonthName: @json(now()->format('F')),
        currentDeductions: @json($currentBreakdown['deductions'] ?? []),
        currentBonuses: @json($currentBreakdown['bonuses'] ?? []),
        currentReimbursements: @json($currentBreakdown['reimbursements'] ?? []),
        currentStatus: @json($currentPayroll ? $currentPayroll->status : 'pending')
    };

    function teacherProfileConsole() {
        return {
            activeTab: 'payroll',
            editBioModalOpen: false,
            payslipModalOpen: false,
            selectedSlip: null,

            // Payroll Modals
            isSalaryModalOpen: false,
            isBonusModalOpen: false,
            isDeductionModalOpen: false,
            isReimbursementModalOpen: false,
            isPayoutModalOpen: false,

            // 1. Salary Structure Form
            salaryForm: {
                baseSalary: window.teacherProfilePayrollData.baseSalary,
                salaryType: window.teacherProfilePayrollData.salaryType,
                designation: window.teacherProfilePayrollData.designation,
                joiningDate: window.teacherProfilePayrollData.joiningDate,
                fixedDeductionsList: JSON.parse(JSON.stringify(window.teacherProfilePayrollData.fixedDeductions || [])),
                fixedBonusesList: JSON.parse(JSON.stringify(window.teacherProfilePayrollData.fixedBonuses || []))
            },
            openSalaryModal() {
                this.salaryForm = {
                    baseSalary: window.teacherProfilePayrollData.baseSalary,
                    salaryType: window.teacherProfilePayrollData.salaryType,
                    designation: window.teacherProfilePayrollData.designation,
                    joiningDate: window.teacherProfilePayrollData.joiningDate,
                    fixedDeductionsList: JSON.parse(JSON.stringify(window.teacherProfilePayrollData.fixedDeductions || [])),
                    fixedBonusesList: JSON.parse(JSON.stringify(window.teacherProfilePayrollData.fixedBonuses || []))
                };
                this.isSalaryModalOpen = true;
            },
            closeSalaryModal() { this.isSalaryModalOpen = false; },
            addFixedDeduction(name = '', amount = '') {
                this.salaryForm.fixedDeductionsList.push({ id: Date.now() + Math.random(), name, amount: amount !== '' ? parseFloat(amount) : '' });
            },
            removeFixedDeduction(idx) { this.salaryForm.fixedDeductionsList.splice(idx, 1); },
            addFixedBonus(name = '', amount = '') {
                this.salaryForm.fixedBonusesList.push({ id: Date.now() + Math.random(), name, amount: amount !== '' ? parseFloat(amount) : '' });
            },
            removeFixedBonus(idx) { this.salaryForm.fixedBonusesList.splice(idx, 1); },

            // 2. Monthly Bonuses Form
            bonusForm: {
                month: window.teacherProfilePayrollData.currentMonth,
                year: window.teacherProfilePayrollData.currentYear,
                monthName: window.teacherProfilePayrollData.currentMonthName,
                baseSalary: window.teacherProfilePayrollData.baseSalary,
                fixedBonusesList: window.teacherProfilePayrollData.fixedBonuses || [],
                list: [],
                total: 0
            },
            openBonusModal(month = null, year = null, monthName = null, existingList = null) {
                const m = month || window.teacherProfilePayrollData.currentMonth;
                const y = year || window.teacherProfilePayrollData.currentYear;
                const mName = monthName || window.teacherProfilePayrollData.currentMonthName;
                const list = existingList !== null ? existingList : window.teacherProfilePayrollData.currentBonuses;
                this.bonusForm = {
                    month: m,
                    year: y,
                    monthName: mName,
                    baseSalary: window.teacherProfilePayrollData.baseSalary,
                    fixedBonusesList: window.teacherProfilePayrollData.fixedBonuses || [],
                    list: JSON.parse(JSON.stringify(list || [])),
                    total: 0
                };
                this.recalcBonus();
                this.isBonusModalOpen = true;
            },
            closeBonusModal() { this.isBonusModalOpen = false; },
            addBonus(name = '', amount = '') {
                this.bonusForm.list.push({ id: Date.now() + Math.random(), name, amount: amount !== '' ? parseFloat(amount) : '' });
                this.recalcBonus();
            },
            removeBonus(idx) {
                this.bonusForm.list.splice(idx, 1);
                this.recalcBonus();
            },
            recalcBonus() {
                const tot = this.bonusForm.list.reduce((sum, i) => sum + (parseFloat(i.amount) || 0), 0);
                this.bonusForm.total = Math.round(tot * 100) / 100;
            },

            // 3. Monthly Variable Deductions Form
            deductionForm: {
                month: window.teacherProfilePayrollData.currentMonth,
                year: window.teacherProfilePayrollData.currentYear,
                monthName: window.teacherProfilePayrollData.currentMonthName,
                baseSalary: window.teacherProfilePayrollData.baseSalary,
                fixedDeductionsList: window.teacherProfilePayrollData.fixedDeductions || [],
                list: [],
                total: 0
            },
            openDeductionModal(month = null, year = null, monthName = null, existingList = null) {
                const m = month || window.teacherProfilePayrollData.currentMonth;
                const y = year || window.teacherProfilePayrollData.currentYear;
                const mName = monthName || window.teacherProfilePayrollData.currentMonthName;
                const list = existingList !== null ? existingList : window.teacherProfilePayrollData.currentDeductions;
                this.deductionForm = {
                    month: m,
                    year: y,
                    monthName: mName,
                    baseSalary: window.teacherProfilePayrollData.baseSalary,
                    fixedDeductionsList: window.teacherProfilePayrollData.fixedDeductions || [],
                    list: JSON.parse(JSON.stringify(list || [])),
                    total: 0
                };
                this.recalcDeduction();
                this.isDeductionModalOpen = true;
            },
            closeDeductionModal() { this.isDeductionModalOpen = false; },
            addDeduction(name = '', amount = '') {
                this.deductionForm.list.push({ id: Date.now() + Math.random(), name, amount: amount !== '' ? parseFloat(amount) : '' });
                this.recalcDeduction();
            },
            removeDeduction(idx) {
                this.deductionForm.list.splice(idx, 1);
                this.recalcDeduction();
            },
            recalcDeduction() {
                const tot = this.deductionForm.list.reduce((sum, i) => sum + (parseFloat(i.amount) || 0), 0);
                this.deductionForm.total = Math.round(tot * 100) / 100;
            },

            // 4. Reimbursement Form
            reimbursementForm: {
                month: window.teacherProfilePayrollData.currentMonth,
                year: window.teacherProfilePayrollData.currentYear,
                monthName: window.teacherProfilePayrollData.currentMonthName,
                list: [],
                total: 0
            },
            openReimbursementModal(month = null, year = null, monthName = null, existingList = null) {
                const m = month || window.teacherProfilePayrollData.currentMonth;
                const y = year || window.teacherProfilePayrollData.currentYear;
                const mName = monthName || window.teacherProfilePayrollData.currentMonthName;
                const list = existingList !== null ? existingList : window.teacherProfilePayrollData.currentReimbursements;
                this.reimbursementForm = {
                    month: m,
                    year: y,
                    monthName: mName,
                    list: JSON.parse(JSON.stringify(list || [])),
                    total: 0
                };
                this.recalcReimbursement();
                this.isReimbursementModalOpen = true;
            },
            closeReimbursementModal() { this.isReimbursementModalOpen = false; },
            addReimbursement(name = '', amount = '') {
                this.reimbursementForm.list.push({ id: Date.now() + Math.random(), name, amount: amount !== '' ? parseFloat(amount) : '' });
                this.recalcReimbursement();
            },
            removeReimbursement(idx) {
                this.reimbursementForm.list.splice(idx, 1);
                this.recalcReimbursement();
            },
            recalcReimbursement() {
                const tot = this.reimbursementForm.list.reduce((sum, i) => sum + (parseFloat(i.amount) || 0), 0);
                this.reimbursementForm.total = Math.round(tot * 100) / 100;
            },

            // 5. Disbursement Payout Form
            payoutForm: {
                month: window.teacherProfilePayrollData.currentMonth,
                year: window.teacherProfilePayrollData.currentYear,
                monthName: window.teacherProfilePayrollData.currentMonthName,
                baseSalary: window.teacherProfilePayrollData.baseSalary,
                fixedDeductions: window.teacherProfilePayrollData.fixedDeductions || [],
                fixedBonuses: window.teacherProfilePayrollData.fixedBonuses || [],
                variableDeductions: [],
                variableBonuses: [],
                reimbursements: [],
                deductionsTotal: 0,
                bonusesTotal: 0,
                reimbursementsTotal: 0,
                netSalary: 0,
                paymentMethod: 'bank_transfer',
                transactionRef: '',
                notes: ''
            },
            openPayoutModal(month = null, year = null, monthName = null, varDeds = null, varBons = null, reims = null, rowBaseSalary = null) {
                const m = month || window.teacherProfilePayrollData.currentMonth;
                const y = year || window.teacherProfilePayrollData.currentYear;
                const mName = monthName || window.teacherProfilePayrollData.currentMonthName;
                const base = rowBaseSalary !== null ? (parseFloat(rowBaseSalary) || 0) : window.teacherProfilePayrollData.baseSalary;

                const fixedDeds = window.teacherProfilePayrollData.fixedDeductions || [];
                const fixedBons = window.teacherProfilePayrollData.fixedBonuses || [];
                const fixedDedsSum = fixedDeds.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0);
                const fixedBonsSum = fixedBons.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0);

                const vDeds = varDeds !== null ? varDeds : window.teacherProfilePayrollData.currentDeductions;
                const vBons = varBons !== null ? varBons : window.teacherProfilePayrollData.currentBonuses;
                const vReims = reims !== null ? reims : window.teacherProfilePayrollData.currentReimbursements;

                const vDedsSum = vDeds.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0);
                const vBonsSum = vBons.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0);
                const vReimsSum = vReims.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0);

                const totalDeds = Math.round((fixedDedsSum + vDedsSum) * 100) / 100;
                const totalBons = Math.round((fixedBonsSum + vBonsSum) * 100) / 100;
                const net = Math.max(0, Math.round((base - totalDeds + totalBons + vReimsSum) * 100) / 100);

                this.payoutForm = {
                    month: m,
                    year: y,
                    monthName: mName,
                    baseSalary: base,
                    fixedDeductions: fixedDeds,
                    fixedBonuses: fixedBons,
                    variableDeductions: vDeds,
                    variableBonuses: vBons,
                    reimbursements: vReims,
                    deductionsTotal: totalDeds,
                    bonusesTotal: totalBons,
                    reimbursementsTotal: vReimsSum,
                    netSalary: net,
                    paymentMethod: 'bank_transfer',
                    transactionRef: '',
                    notes: ''
                };
                this.isPayoutModalOpen = true;
            },
            closePayoutModal() { this.isPayoutModalOpen = false; },

            viewPayslip(item) {
                this.selectedSlip = item;
                this.payslipModalOpen = true;
            },
            printSlip() {
                window.print();
            }
        };
    }
</script>

<div x-data="teacherProfileConsole()" x-cloak class="space-y-6">

    {{-- TOP BANNER / HEADER (STATIC PAGE OVERVIEW) --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Faculty 360° Profile &amp; Records Console
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl font-normal">
                    Comprehensive faculty dossier covering verified joining records, academic subject allocations, class mentorships, lifetime attendance logs, salary structure, and monthly disbursement history.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.teachers') }}"
               class="px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Back to Faculty</span>
            </a>
            <a href="{{ route('admin.teachers.payroll') }}"
               class="px-4 py-2 bg-brand text-primary rounded-full font-normal text-xs hover:opacity-90 transition inline-flex items-center gap-2 cursor-pointer shadow-xs">
                <i class="fa-solid fa-wallet text-[11px]"></i>
                <span>Faculty Payroll</span>
            </a>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MULTIPLE GRIDS: TEACHER DOSSIER & ACTIONS               --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- BLOCK 1: TEACHER PERSONAL DOSSIER & IDENTITY (4 COLS) --}}
        <div class="lg:col-span-4 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            <div>
                {{-- Header with Avatar, Full Name, Designation, Status & Edit CTA --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        @if($teacher->profile_picture)
                            <img src="{{ asset($teacher->profile_picture) }}" alt="{{ $teacher->fname }}"
                                 class="w-14 h-14 rounded-full object-cover shrink-0 shadow-xs">
                        @else
                            <div class="w-14 h-14 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg uppercase shrink-0 font-semibold shadow-xs">
                                {{ strtoupper(substr($teacher->fname, 0, 1) . substr($teacher->lname ?: $teacher->fname, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="font-semibold text-base text-theme-text truncate leading-tight">
                                    {{ $teacher->fname }} {{ $teacher->lname }}
                                </h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-normal {{ $teacher->status === 'active' ? 'bg-success-bg text-success' : 'bg-danger-bg text-danger' }}">
                                    <i class="fa-solid fa-circle text-[6px] mr-1"></i>{{ ucfirst($teacher->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                <span class="text-xs text-theme-text-muted font-normal">&#64;{{ $teacher->username }}</span>
                                <span class="text-[10px] text-theme-text-muted">&bull;</span>
                                <span class="text-[11px] text-brand font-normal">{{ $teacher->designation ?: 'Faculty Member' }}</span>
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
                        <p class="font-semibold text-theme-text truncate">{{ $teacher->fname }}</p>
                    </div>

                    {{-- 2. Last Name --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-regular fa-user text-brand mr-1"></i> Last Name
                        </span>
                        <p class="font-semibold text-theme-text truncate">{{ $teacher->lname ?: 'Not recorded' }}</p>
                    </div>

                    {{-- 3. Phone / Mobile --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-phone text-brand mr-1"></i> Phone / Mobile
                        </span>
                        @if($teacher->number)
                            <p class="font-semibold text-theme-text truncate font-mono">{{ $teacher->number }}</p>
                        @else
                            <p class="font-normal text-theme-text-muted truncate">Not provided</p>
                        @endif
                    </div>

                    {{-- 4. Email Address --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-regular fa-envelope text-brand mr-1"></i> Email Address
                        </span>
                        <p class="font-semibold text-theme-text truncate" title="{{ $teacher->email }}">{{ $teacher->email }}</p>
                    </div>

                    {{-- 5. Blood Group --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-droplet text-brand mr-1"></i> Blood Group
                        </span>
                        <p class="font-semibold text-theme-text truncate">{{ $teacher->blood_group ?: 'Not recorded' }}</p>
                    </div>

                    {{-- 6. Date of Birth --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-regular fa-calendar text-brand mr-1"></i> Date of Birth
                        </span>
                        <p class="font-normal text-theme-text truncate">
                            {{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->format('d M Y') : 'Not recorded' }}
                        </p>
                    </div>

                    {{-- 7. Joining Date (Important) --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-calendar-check text-brand mr-1"></i> Joining Date
                        </span>
                        <p class="font-semibold text-theme-text truncate">
                            {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : ($teacher->created_at ? $teacher->created_at->format('d M Y') : 'Not recorded') }}
                        </p>
                    </div>

                    {{-- 8. Qualification --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-graduation-cap text-brand mr-1"></i> Qualification
                        </span>
                        <p class="font-semibold text-theme-text truncate">{{ $teacher->qualification ?: 'Not Specified' }}</p>
                    </div>

                    {{-- 9. Emergency Contact --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-phone-volume text-brand mr-1"></i> Emergency Contact
                        </span>
                        <p class="font-normal text-theme-text truncate font-mono">{{ $teacher->emergency_contact ?: 'Not provided' }}</p>
                    </div>

                    {{-- 10. City & State --}}
                    <div class="p-2.5 bg-secondary rounded-lg space-y-0.5">
                        <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                            <i class="fa-solid fa-location-dot text-brand mr-1"></i> Location
                        </span>
                        <p class="font-normal text-theme-text truncate">
                            {{ trim(($teacher->city ? $teacher->city . ', ' : '') . ($teacher->state ?: '')) ?: 'Not recorded' }}
                        </p>
                    </div>
                </div>

                {{-- Residential Address Full Row --}}
                <div class="p-2.5 bg-secondary rounded-lg mt-2 text-xs space-y-0.5">
                    <span class="text-[9px] text-theme-text-muted uppercase block font-normal truncate">
                        <i class="fa-solid fa-house text-brand mr-1"></i> Residential Address
                    </span>
                    <p class="font-normal text-theme-text text-[11px] leading-relaxed">
                        {{ $teacher->address ?: 'No residential address recorded on file.' }}
                        @if($teacher->pincode)
                            <span class="text-theme-text-muted font-mono">({{ $teacher->pincode }})</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Footer Note / Record Badge --}}
            <div class="pt-1 flex items-center justify-between text-[11px] text-theme-text-muted font-normal">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-fingerprint text-brand"></i>
                    <span>Faculty ID: #{{ $teacher->id }}</span>
                </span>
                <span>
                    Status: <span class="text-success font-semibold">Verified Staff</span>
                </span>
            </div>
        </div>

        {{-- BLOCK 2: ACADEMIC APPOINTMENTS & TEACHING SCOPE (5 COLS) --}}
        <div class="lg:col-span-5 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                {{-- Header with Title & Action Triggers --}}
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard text-brand text-xs"></i>
                        <div>
                            <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                                Academic Allocations &amp; Scope
                            </h3>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.teachers.allocations') }}"
                           class="px-2.5 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-[10px] font-normal cursor-pointer inline-flex items-center gap-1 transition"
                           title="Manage Allocations">
                            <i class="fa-solid fa-sliders text-[9px]"></i>
                            <span>Manage Allocations</span>
                        </a>
                    </div>
                </div>

                {{-- Part 1: Primary Class Mentorships --}}
                <div class="space-y-2">
                    <span class="text-[10px] text-theme-text-muted uppercase font-normal block tracking-wider">
                        Primary Class Mentorship (Class Teacher Role)
                    </span>
                    @if($managedClasses->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($managedClasses as $c)
                                <div class="p-2.5 bg-secondary rounded-lg flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="text-xs font-semibold text-theme-text truncate">{{ $c->name }}</div>
                                        <div class="text-[10px] text-theme-text-muted truncate">
                                            {{ $c->academicCategory->name ?? 'Standard Category' }}
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-normal shrink-0">
                                        Class Mentor
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-secondary rounded-lg text-xs text-theme-text-muted font-normal italic">
                            Not assigned as the primary class teacher/mentor for any classes.
                        </div>
                    @endif
                </div>

                {{-- Part 2: Assigned Subjects (Teaching Roster) --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal block tracking-wider">
                            Assigned Subjects ({{ $assignedSubjects->count() }})
                        </span>
                        <span class="text-[10px] text-brand font-normal">Active Teaching Roster</span>
                    </div>

                    @if($assignedSubjects->isNotEmpty())
                        <div class="max-h-48 overflow-y-auto space-y-1.5 scrollbar pr-1">
                            @foreach($assignedSubjects as $sub)
                                <div class="p-2 bg-secondary rounded-lg flex items-center justify-between gap-2 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-brand/10 text-brand flex items-center justify-center text-[10px] shrink-0 font-semibold">
                                            <i class="fa-solid fa-book-open"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-theme-text truncate block text-xs">{{ $sub->name }}</span>
                                            <span class="text-[10px] text-theme-text-muted truncate block">
                                                Class: {{ $sub->classCourse->name ?? 'All Tiers' }}
                                                @if($sub->code)
                                                    &bull; <span class="font-mono text-brand">{{ $sub->code }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-theme-text-muted shrink-0 font-mono">
                                        {{ $sub->academic_type ?? 'Core' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-secondary rounded-lg text-xs text-theme-text-muted font-normal italic">
                            No subjects currently assigned to this faculty member.
                        </div>
                    @endif
                </div>

                {{-- Part 3: Assigned Batches --}}
                <div class="space-y-2">
                    <span class="text-[10px] text-theme-text-muted uppercase font-normal block tracking-wider">
                        Assigned Batches ({{ $assignedBatches->count() }})
                    </span>
                    @if($assignedBatches->isNotEmpty())
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @foreach($assignedBatches as $b)
                                <span class="px-2.5 py-1 rounded-full bg-secondary text-theme-text text-xs font-normal inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-users-rectangle text-brand text-[10px]"></i>
                                    <span>{{ $b->name }}</span>
                                    @if($b->timing)
                                        <span class="text-theme-text-muted text-[10px]">({{ $b->timing }})</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="p-2.5 bg-secondary rounded-lg text-xs text-theme-text-muted font-normal italic">
                            No specific cohort batches assigned.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="pt-2 flex items-center justify-between flex-wrap gap-2 text-[10px] text-theme-text-muted font-normal border-t border-subtle">
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-users text-brand text-[9px]"></i>
                    <span>Student Footprint: <span class="font-semibold text-theme-text">{{ $totalStudentsCount }}</span> active learners</span>
                </span>
                <span class="text-success inline-flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[7px]"></i>
                    <span>Allocations Synced</span>
                </span>
            </div>
        </div>

        {{-- BLOCK 3: QUICK OPERATIONS & ACTIONS (3 COLS) --}}
        <div class="lg:col-span-3 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            <div class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-brand"></i>
                        <span>Faculty Actions</span>
                    </h3>
                    <span class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center text-[10px] text-brand">
                        <i class="fa-solid fa-sliders"></i>
                    </span>
                </div>

                <div class="space-y-2">
                    {{-- 1. Disburse Current Month Salary --}}
                    <button type="button" @click="openPayoutModal()"
                            class="w-full px-4 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-hand-holding-dollar text-[11px]"></i>
                        <span>Disburse Current Month</span>
                    </button>

                    {{-- 2. Edit Salary Structure --}}
                    <button type="button" @click="openSalaryModal()"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-wallet text-[11px] text-brand"></i>
                        <span>Edit Salary Structure</span>
                    </button>

                    {{-- 3. Monthly Deductions & Bonuses --}}
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="openDeductionModal()"
                                class="px-3 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-shield-halved text-[10px] text-danger"></i>
                            <span>Deductions</span>
                        </button>
                        <button type="button" @click="openBonusModal()"
                                class="px-3 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-gift text-[10px] text-success"></i>
                            <span>Bonuses</span>
                        </button>
                    </div>

                    {{-- 4. Expense Claim --}}
                    <button type="button" @click="openReimbursementModal()"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-receipt text-[11px] text-info"></i>
                        <span>Expense Claims</span>
                    </button>

                    {{-- 5. WhatsApp Faculty Member --}}
                    @if($teacher->number)
                        @php
                            $phoneClean = preg_replace('/[^0-9]/', '', $teacher->number);
                        @endphp
                        <a href="https://wa.me/{{ $phoneClean }}" target="_blank"
                           class="w-full px-4 py-2 bg-success-bg text-success rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Message on WhatsApp</span>
                        </a>
                    @endif

                    {{-- 6. Edit Personal Details --}}
                    <button type="button" @click="editBioModalOpen = true"
                            class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-user-pen text-[11px] text-brand"></i>
                        <span>Edit Personal Details</span>
                    </button>

                    {{-- 7. Manage Subject Allocations --}}
                    <a href="{{ route('admin.teachers.allocations') }}"
                       class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-layer-group text-[11px] text-brand"></i>
                        <span>Manage Allocations</span>
                    </a>

                    {{-- 8. Mark / Inspect Attendance --}}
                    <a href="{{ route('admin.teachers.attendance') }}"
                       class="w-full px-4 py-2 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-calendar-check text-[11px] text-brand"></i>
                        <span>Attendance Register</span>
                    </a>
                </div>
            </div>

            <div class="p-3 bg-secondary rounded-lg text-xs space-y-1 font-normal">
                <span class="text-[10px] text-theme-text-muted uppercase block font-normal">
                    <i class="fa-solid fa-circle-info text-brand mr-1"></i> Quick Note
                </span>
                <p class="text-[11px] text-theme-text-muted leading-relaxed">
                    Salary slips and payment history are permanently recorded under the Payroll &amp; Ledger tab below.
                </p>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- 4 KPI SUMMARY METRIC CARDS                               --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Monthly Base Stipend --}}
        <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Base Salary</span>
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </span>
                </div>
                <p class="text-2xl font-semibold text-theme-text mt-1">
                    ₹{{ number_format($baseSalary, 2) }}
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1 border-t border-subtle/50">
                <span class="capitalize">{{ str_replace('_', ' ', $salaryStructure?->salary_type ?: $teacher->salary_type ?: 'monthly') }}</span>
                <span>Fixed Deds: ₹{{ number_format($fixedDeductionsTotal, 2) }}</span>
            </div>
        </div>

        {{-- Card 2: Attendance Rate --}}
        <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Attendance Rate</span>
                    <span class="w-8 h-8 rounded-full bg-success-bg text-success flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                </div>
                <p class="text-2xl font-semibold text-theme-text mt-1">
                    {{ $attendanceRate !== null ? $attendanceRate . '%' : 'N/A' }}
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1 border-t border-subtle/50">
                <span>{{ $presentAttendance }} Present / {{ $totalAttendance }} Logged</span>
                <span>{{ $absentAttendance }} Absent</span>
            </div>
        </div>

        {{-- Card 3: Lifetime Disbursed Payout --}}
        <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Lifetime Disbursed</span>
                    <span class="w-8 h-8 rounded-full bg-info-bg text-info flex items-center justify-center text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </span>
                </div>
                <p class="text-2xl font-semibold text-theme-text mt-1">
                    ₹{{ number_format($totalDisbursed, 2) }}
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1 border-t border-subtle/50">
                <span>{{ $paidPayrolls->count() }} Paid Cycles</span>
                <span>Deds: ₹{{ number_format($totalDeductionsHistorical, 2) }}</span>
            </div>
        </div>

        {{-- Card 4: Academic Footprint --}}
        <div class="rounded-lg bg-primary p-5 space-y-2 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Teaching Scope</span>
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </span>
                </div>
                <p class="text-2xl font-semibold text-theme-text mt-1">
                    {{ $assignedSubjects->count() }} Subjects
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-theme-text-muted font-normal pt-1 border-t border-subtle/50">
                <span>{{ $managedClasses->count() }} Classes Mentored</span>
                <span>{{ $totalStudentsCount }} Students</span>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- STANDALONE FLOATING PILL TABS BAR                        --}}
    {{-- ======================================================== --}}
    <div class="flex items-center gap-2 p-1.5 bg-secondary rounded-full w-full sm:w-fit overflow-x-auto scrollbar">
        <button type="button" @click="activeTab = 'payroll'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'payroll' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
            <i class="fa-solid fa-file-invoice-dollar text-[12px]"></i>
            <span>Salary &amp; Payroll Ledger</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]"
                  :class="activeTab === 'payroll' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                {{ $payrolls->count() }}
            </span>
        </button>

        <button type="button" @click="activeTab = 'allocations'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'allocations' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
            <i class="fa-solid fa-book-open text-[12px]"></i>
            <span>Curriculum &amp; Timetable</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]"
                  :class="activeTab === 'allocations' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                {{ $assignedSubjects->count() }}
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

        <button type="button" @click="activeTab = 'bio'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'bio' 
                        ? 'bg-brand text-primary font-semibold shadow-xs' 
                        : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
            <i class="fa-solid fa-user text-[12px]"></i>
            <span>Professional Bio</span>
        </button>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 1: SALARY STRUCTURE & HISTORICAL PAYROLL LEDGER      --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'payroll'" class="space-y-5">
        {{-- Salary Structure Configuration Card --}}
        <div class="rounded-lg bg-primary p-5 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Configured Salary Structure &amp; Recurring Adjustments
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Baseline stipend, fixed monthly statutory deductions (PF, ESI), and recurring monthly allowances.
                        </p>
                    </div>
                </div>
                <button type="button" @click="openSalaryModal()"
                   class="px-3 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1.5 transition cursor-pointer">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    <span>Edit Salary Structure</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Base Stipend Tile --}}
                <div class="p-4 bg-secondary rounded-lg space-y-1">
                    <span class="text-[10px] text-theme-text-muted uppercase font-normal block">Monthly Baseline Salary</span>
                    <div class="text-xl font-semibold text-theme-text">₹{{ number_format($baseSalary, 2) }}</div>
                    <span class="text-[10px] text-brand capitalize font-normal">{{ str_replace('_', ' ', $salaryStructure?->salary_type ?: $teacher->salary_type ?: 'monthly') }} Basis</span>
                </div>

                {{-- Recurring Fixed Deductions Tile --}}
                <div class="p-4 bg-secondary rounded-lg space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal">Recurring Fixed Deductions</span>
                        <span class="text-xs font-semibold text-danger">- ₹{{ number_format($fixedDeductionsTotal, 2) }}</span>
                    </div>
                    @if(!empty($fixedDeductions))
                        <div class="space-y-1 text-xs">
                            @foreach($fixedDeductions as $fd)
                                <div class="flex justify-between text-theme-text font-normal text-[11px]">
                                    <span>{{ $fd['name'] ?? 'Fixed Deduction' }}</span>
                                    <span class="font-mono text-danger">- ₹{{ number_format((float)($fd['amount'] ?? 0), 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-[11px] text-theme-text-muted italic">No recurring fixed deductions (e.g. PF, ESI) configured.</p>
                    @endif
                </div>

                {{-- Recurring Fixed Bonuses Tile --}}
                <div class="p-4 bg-secondary rounded-lg space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal">Recurring Allowances / Bonuses</span>
                        <span class="text-xs font-semibold text-success">+ ₹{{ number_format($fixedBonusesTotal, 2) }}</span>
                    </div>
                    @if(!empty($fixedBonuses))
                        <div class="space-y-1 text-xs">
                            @foreach($fixedBonuses as $fb)
                                <div class="flex justify-between text-theme-text font-normal text-[11px]">
                                    <span>{{ $fb['name'] ?? 'Allowance' }}</span>
                                    <span class="font-mono text-success">+ ₹{{ number_format((float)($fb['amount'] ?? 0), 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-[11px] text-theme-text-muted italic">No recurring allowances configured.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Historical Monthly Payroll Ledger Table --}}
        <div class="rounded-lg bg-primary overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Complete Lifetime Payroll Ledger &amp; Disbursement History
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            All monthly salary cycles, breakdown of deductions, bonuses, payment modes, and official payslips.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="openPayoutModal()"
                            class="px-3.5 py-1.5 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i>
                        <span>Disburse Payout</span>
                    </button>
                    <span class="px-3 py-1 rounded-full bg-secondary text-theme-text text-xs font-normal">
                        {{ $payrolls->count() }} Recorded Cycles
                    </span>
                </div>
            </div>

            @if($payrolls->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-secondary text-theme-text-muted font-normal text-[10px] uppercase tracking-wider">
                                <th class="py-3 px-4 pl-5">Month &amp; Year</th>
                                <th class="py-3 px-4">Base Salary</th>
                                <th class="py-3 px-4">Deductions</th>
                                <th class="py-3 px-4">Bonuses &amp; Claims</th>
                                <th class="py-3 px-4">Net Disbursed</th>
                                <th class="py-3 px-4">Payout Status</th>
                                <th class="py-3 px-4">Payment Details</th>
                                <th class="py-3 px-4 pr-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-subtle/50">
                            @php
                                $monthNames = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                            @endphp
                            @foreach($payrolls as $pr)
                                @php
                                    $mName = $monthNames[$pr->month] ?? "Month {$pr->month}";
                                    $isPaid = $pr->status === 'paid';
                                    $brk = is_array($pr->breakdown) ? $pr->breakdown : (json_decode($pr->breakdown, true) ?: []);
                                    $varDeds = $brk['deductions'] ?? [];
                                    $varBons = $brk['bonuses'] ?? [];
                                    $claims = $brk['reimbursements'] ?? [];
                                    $jsonPayload = json_encode([
                                        'month_name' => $mName,
                                        'year' => $pr->year,
                                        'teacher_name' => $teacher->fname . ' ' . $teacher->lname,
                                        'designation' => $teacher->designation ?: 'Faculty Member',
                                        'username' => $teacher->username,
                                        'base_salary' => (float)$pr->base_salary,
                                        'deductions' => (float)$pr->deductions,
                                        'bonuses' => (float)$pr->bonuses,
                                        'reimbursements' => (float)($pr->reimbursements ?? 0),
                                        'net_salary' => (float)$pr->net_salary,
                                        'status' => $pr->status,
                                        'paid_at' => $pr->paid_at ? \Carbon\Carbon::parse($pr->paid_at)->format('d M Y, h:i A') : 'Pending',
                                        'payment_method' => $pr->payment_method ? ucwords(str_replace('_', ' ', $pr->payment_method)) : 'N/A',
                                        'transaction_reference' => $pr->transaction_reference ?: 'N/A',
                                        'notes' => $pr->notes ?: '',
                                        'fixed_deductions' => $fixedDeductions,
                                        'fixed_bonuses' => $fixedBonuses,
                                        'variable_deductions' => $varDeds,
                                        'variable_bonuses' => $varBons,
                                        'claims' => $claims,
                                    ]);
                                @endphp
                                <tr class="hover:bg-secondary/40 transition">
                                    {{-- Month & Year --}}
                                    <td class="py-3.5 px-4 pl-5 whitespace-nowrap">
                                        <div class="font-semibold text-theme-text text-xs">{{ $mName }} {{ $pr->year }}</div>
                                        <div class="text-[10px] text-theme-text-muted">Cycle #{{ $pr->id }}</div>
                                    </td>

                                    {{-- Base Salary --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap font-normal text-theme-text">
                                        ₹{{ number_format((float)$pr->base_salary, 2) }}
                                    </td>

                                    {{-- Deductions --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if((float)$pr->deductions > 0)
                                            <span class="font-normal text-danger">- ₹{{ number_format((float)$pr->deductions, 2) }}</span>
                                            @if(!empty($varDeds))
                                                <div class="text-[10px] text-theme-text-muted">
                                                    {{ count($varDeds) }} variable item{{ count($varDeds) > 1 ? 's' : '' }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-theme-text-muted font-normal">₹0.00</span>
                                        @endif
                                    </td>

                                    {{-- Bonuses & Claims --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $totalAdditions = (float)$pr->bonuses + (float)($pr->reimbursements ?? 0);
                                        @endphp
                                        @if($totalAdditions > 0)
                                            <span class="font-normal text-success">+ ₹{{ number_format($totalAdditions, 2) }}</span>
                                            @if((float)($pr->reimbursements ?? 0) > 0)
                                                <div class="text-[10px] text-info">
                                                    Incl. ₹{{ number_format((float)$pr->reimbursements, 2) }} claims
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-theme-text-muted font-normal">₹0.00</span>
                                        @endif
                                    </td>

                                    {{-- Net Disbursed --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="font-semibold text-theme-text text-sm">
                                            ₹{{ number_format((float)$pr->net_salary, 2) }}
                                        </span>
                                    </td>

                                    {{-- Payout Status --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($isPaid)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-normal bg-success-bg text-success inline-flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-[7px]"></i> Disbursed
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-normal bg-warning-bg text-warning inline-flex items-center gap-1">
                                                <i class="fa-solid fa-clock text-[7px]"></i> Pending
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Payment Details --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap text-[11px]">
                                        @if($isPaid)
                                            <div class="font-normal text-theme-text capitalize">{{ ucwords(str_replace('_', ' ', $pr->payment_method)) }}</div>
                                            <div class="text-[10px] text-theme-text-muted font-mono">
                                                {{ $pr->transaction_reference ?: 'Direct Transfer' }}
                                            </div>
                                            <div class="text-[9px] text-theme-text-muted">
                                                {{ $pr->paid_at ? \Carbon\Carbon::parse($pr->paid_at)->format('d M Y') : '' }}
                                            </div>
                                        @else
                                            <span class="text-theme-text-muted italic">Pending Payout</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-3.5 px-4 pr-5 text-right whitespace-nowrap">
                                        @if($isPaid)
                                            <button type="button"
                                                    @click='viewPayslip({!! $jsonPayload !!})'
                                                    class="px-3 py-1.5 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs font-normal inline-flex items-center gap-1.5 transition cursor-pointer">
                                                <i class="fa-solid fa-file-invoice text-brand text-[10px]"></i>
                                                <span>Payslip</span>
                                            </button>
                                        @else
                                            <div class="inline-flex items-center gap-1.5">
                                                <button type="button"
                                                        @click='openPayoutModal({{ $pr->month }}, {{ $pr->year }}, @json($mName), {{ json_encode($varDeds) }}, {{ json_encode($varBons) }}, {{ json_encode($claims) }}, {{ (float)$pr->base_salary }})'
                                                        class="px-3 py-1.5 rounded-full bg-brand text-primary text-xs font-normal hover:opacity-90 inline-flex items-center gap-1 transition cursor-pointer shadow-xs">
                                                    <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i>
                                                    <span>Disburse</span>
                                                </button>
                                                <button type="button"
                                                        @click='openDeductionModal({{ $pr->month }}, {{ $pr->year }}, @json($mName), {{ json_encode($varDeds) }})'
                                                        class="px-2.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs font-normal inline-flex items-center gap-1 transition cursor-pointer"
                                                        title="Manage Variable Deductions">
                                                    <i class="fa-solid fa-shield-halved text-danger text-[10px]"></i>
                                                    <span>Deds</span>
                                                </button>
                                                <button type="button"
                                                        @click='openBonusModal({{ $pr->month }}, {{ $pr->year }}, @json($mName), {{ json_encode($varBons) }})'
                                                        class="px-2.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs font-normal inline-flex items-center gap-1 transition cursor-pointer"
                                                        title="Manage Monthly Bonuses">
                                                    <i class="fa-solid fa-gift text-success text-[10px]"></i>
                                                    <span>Bonus</span>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-theme-text-muted text-xs font-normal space-y-2">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl opacity-40 block"></i>
                    <p>No historical payroll cycles recorded yet for this faculty member.</p>
                    <a href="{{ route('admin.teachers.payroll') }}"
                       class="inline-block mt-2 px-4 py-2 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 transition">
                        Open Monthly Payroll Board
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 2: CURRICULUM, SUBJECTS & TIMETABLE                  --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'allocations'" class="space-y-5">
        {{-- Section 1: Mentored Classes --}}
        <div class="rounded-lg bg-primary p-5 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chalkboard"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Class Teacher Appointments
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Classes where {{ $teacher->fname }} serves as the primary mentor and class guide.
                        </p>
                    </div>
                </div>
            </div>

            @if($managedClasses->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($managedClasses as $c)
                        <div class="p-4 bg-secondary rounded-lg space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-semibold text-theme-text text-sm">{{ $c->name }}</h4>
                                    <span class="text-[10px] text-theme-text-muted font-normal">{{ $c->academicCategory->name ?? 'Standard Category' }}</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-normal">
                                    Class Mentor
                                </span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-subtle/50 text-[11px] text-theme-text-muted">
                                <span>Total Students Enrolled:</span>
                                <span class="font-semibold text-theme-text">{{ $c->students_count ?? $c->students()->count() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 bg-secondary rounded-lg text-center text-xs text-theme-text-muted font-normal italic">
                    No primary class mentorship assignments recorded.
                </div>
            @endif
        </div>

        {{-- Section 2: Assigned Subjects Table --}}
        <div class="rounded-lg bg-primary overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-book-open"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Teaching Syllabus &amp; Assigned Subjects
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Complete list of course subjects assigned to this teacher across academic classes.
                        </p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full bg-secondary text-theme-text text-xs font-normal">
                    {{ $assignedSubjects->count() }} Subjects Assigned
                </span>
            </div>

            @if($assignedSubjects->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-secondary text-theme-text-muted font-normal text-[10px] uppercase tracking-wider">
                                <th class="py-3 px-4 pl-5">Subject Name</th>
                                <th class="py-3 px-4">Subject Code</th>
                                <th class="py-3 px-4">Class / Course</th>
                                <th class="py-3 px-4">Academic Category</th>
                                <th class="py-3 px-4">Subject Type</th>
                                <th class="py-3 px-4 pr-5 text-right">Scope</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-subtle/50">
                            @foreach($assignedSubjects as $s)
                                <tr class="hover:bg-secondary/40 transition">
                                    <td class="py-3.5 px-4 pl-5 font-semibold text-theme-text">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-book text-brand text-xs"></i>
                                            <span>{{ $s->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-brand">
                                        {{ $s->code ?: 'N/A' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text">
                                        {{ $s->classCourse->name ?? 'All Tiers' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text-muted">
                                        {{ $s->classCourse->academicCategory->name ?? 'General' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text-muted capitalize">
                                        {{ $s->academic_type ?? 'Core' }}
                                    </td>
                                    <td class="py-3.5 px-4 pr-5 text-right">
                                        <span class="px-2.5 py-0.5 rounded-full bg-success-bg text-success text-[10px]">
                                            Active
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-theme-text-muted text-xs font-normal italic">
                    No subjects currently assigned to this faculty member.
                </div>
            @endif
        </div>

        {{-- Section 3: Assigned Batches Table --}}
        <div class="rounded-lg bg-primary overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-users-rectangle"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Assigned Batches &amp; Timings
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Cohort groups and study batches assigned for regular or competitive instruction.
                        </p>
                    </div>
                </div>
            </div>

            @if($assignedBatches->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-secondary text-theme-text-muted font-normal text-[10px] uppercase tracking-wider">
                                <th class="py-3 px-4 pl-5">Batch Name</th>
                                <th class="py-3 px-4">Assigned Class</th>
                                <th class="py-3 px-4">Timing &amp; Schedule</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4 pr-5 text-right">Students</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-subtle/50">
                            @foreach($assignedBatches as $batch)
                                <tr class="hover:bg-secondary/40 transition">
                                    <td class="py-3.5 px-4 pl-5 font-semibold text-theme-text">
                                        {{ $batch->name }}
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text">
                                        {{ $batch->classCourse->name ?? 'Standard Class' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text-muted">
                                        {{ $batch->timing ?: ($batch->start_time ? $batch->start_time . ' - ' . $batch->end_time : 'Standard Schedule') }}
                                        @if($batch->days)
                                            <span class="block text-[10px] text-brand">{{ $batch->days }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-theme-text-muted">
                                        {{ $batch->room_number ?: 'Main Facility' }}
                                    </td>
                                    <td class="py-3.5 px-4 pr-5 text-right font-semibold text-theme-text">
                                        {{ $batch->students()->count() }} Enrolled
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-theme-text-muted text-xs font-normal italic">
                    No specific batches assigned.
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 3: ATTENDANCE HISTORY & COMPLETE LOGS                --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'attendance'" class="space-y-5">
        {{-- Attendance Statistics Strip --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="p-3.5 bg-primary rounded-lg text-center space-y-1">
                <span class="text-[10px] text-theme-text-muted uppercase font-normal">Present Days</span>
                <div class="text-xl font-semibold text-success">{{ $presentAttendance }}</div>
            </div>
            <div class="p-3.5 bg-primary rounded-lg text-center space-y-1">
                <span class="text-[10px] text-theme-text-muted uppercase font-normal">Half-Days</span>
                <div class="text-xl font-semibold text-warning">{{ $halfDayAttendance }}</div>
            </div>
            <div class="p-3.5 bg-primary rounded-lg text-center space-y-1">
                <span class="text-[10px] text-theme-text-muted uppercase font-normal">Approved Leaves</span>
                <div class="text-xl font-semibold text-info">{{ $leaveAttendance }}</div>
            </div>
            <div class="p-3.5 bg-primary rounded-lg text-center space-y-1">
                <span class="text-[10px] text-theme-text-muted uppercase font-normal">Unpaid Absences</span>
                <div class="text-xl font-semibold text-danger">{{ $absentAttendance }}</div>
            </div>
            <div class="p-3.5 bg-primary rounded-lg text-center space-y-1">
                <span class="text-[10px] text-theme-text-muted uppercase font-normal">Overall Rate</span>
                <div class="text-xl font-semibold text-theme-text">{{ $attendanceRate !== null ? $attendanceRate . '%' : 'N/A' }}</div>
            </div>
        </div>

        {{-- Attendance History Log Table --}}
        <div class="rounded-lg bg-primary overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Daily Staff Attendance Register Logs
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Chronological attendance record recorded by administrators.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.teachers.attendance') }}"
                   class="px-3 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-calendar-days text-[10px]"></i>
                    <span>Attendance Register</span>
                </a>
            </div>

            @if($attendances->isNotEmpty())
                <div class="overflow-x-auto max-h-[500px] scrollbar">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-secondary text-theme-text-muted font-normal text-[10px] uppercase tracking-wider sticky top-0 z-10">
                                <th class="py-3 px-4 pl-5">Date</th>
                                <th class="py-3 px-4">Day of Week</th>
                                <th class="py-3 px-4">Attendance Status</th>
                                <th class="py-3 px-4">Remarks / Notes</th>
                                <th class="py-3 px-4 pr-5 text-right">Logged At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-subtle/50">
                            @foreach($attendances as $att)
                                @php
                                    $d = \Carbon\Carbon::parse($att->date);
                                    $statusMap = [
                                        'present' => ['bg-success-bg text-success', 'fa-check', 'Present'],
                                        'half_day' => ['bg-warning-bg text-warning', 'fa-adjust', 'Half Day'],
                                        'leave' => ['bg-info-bg text-info', 'fa-calendar-minus', 'Approved Leave'],
                                        'late' => ['bg-warning-bg text-warning', 'fa-clock', 'Late Arrival'],
                                        'absent' => ['bg-danger-bg text-danger', 'fa-xmark', 'Absent (Unpaid)'],
                                        'holiday' => ['bg-secondary text-theme-text-muted', 'fa-umbrella-beach', 'Holiday'],
                                    ];
                                    $cfg = $statusMap[$att->status] ?? ['bg-secondary text-theme-text', 'fa-circle', ucfirst($att->status)];
                                @endphp
                                <tr class="hover:bg-secondary/40 transition">
                                    <td class="py-3 px-4 pl-5 font-semibold text-theme-text">
                                        {{ $d->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted">
                                        {{ $d->format('l') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal inline-flex items-center gap-1 {{ $cfg[0] }}">
                                            <i class="fa-solid {{ $cfg[1] }} text-[8px]"></i>
                                            <span>{{ $cfg[2] }}</span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-theme-text-muted">
                                        {{ $att->remarks ?: 'Regular staff marking' }}
                                    </td>
                                    <td class="py-3 px-4 pr-5 text-right text-theme-text-muted text-[10px]">
                                        {{ $att->created_at ? $att->created_at->format('h:i A') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-theme-text-muted text-xs font-normal italic">
                    No attendance records logged yet for this faculty member.
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 4: PROFESSIONAL BIO & CREDENTIALS                    --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'bio'" class="space-y-5">
        <div class="rounded-lg bg-primary p-5 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-subtle">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm">
                        <i class="fa-solid fa-address-card"></i>
                    </span>
                    <div>
                        <h3 class="text-xs font-semibold text-theme-text uppercase tracking-wider">
                            Faculty Bio &amp; Professional Background
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal">
                            Teaching profile, academic history, qualifications, and biography notes.
                        </p>
                    </div>
                </div>
                <button type="button" @click="editBioModalOpen = true"
                        class="px-3 py-1 bg-secondary text-brand hover:bg-subtle rounded-full text-xs font-normal inline-flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    <span>Edit Bio</span>
                </button>
            </div>

            <div class="space-y-4 text-xs font-normal">
                <div class="p-4 bg-secondary rounded-lg space-y-1.5">
                    <span class="text-[10px] text-theme-text-muted uppercase font-normal block">Professional Statement / About</span>
                    <p class="text-theme-text leading-relaxed text-xs">
                        {{ $teacher->about ?: 'No biographical summary recorded. Click "Edit Bio" to record qualifications, teaching philosophy, and special achievements.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-secondary rounded-lg space-y-2">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal block">Academic Qualifications</span>
                        <div class="text-sm font-semibold text-theme-text">{{ $teacher->qualification ?: 'Not Specified' }}</div>
                        <p class="text-[11px] text-theme-text-muted">Primary verified degrees and certifications.</p>
                    </div>

                    <div class="p-4 bg-secondary rounded-lg space-y-2">
                        <span class="text-[10px] text-theme-text-muted uppercase font-normal block">Emergency &amp; Residential Info</span>
                        <div class="text-[11px] text-theme-text">Emergency Contact: <span class="font-semibold font-mono">{{ $teacher->emergency_contact ?: 'Not provided' }}</span></div>
                        <div class="text-[11px] text-theme-text">City / State: <span class="font-semibold">{{ trim(($teacher->city ? $teacher->city . ', ' : '') . ($teacher->state ?: '')) ?: 'Not recorded' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- OFFICIAL PAYSLIP MODAL (PRINTABLE)                       --}}
    {{-- ======================================================== --}}
    <div x-show="payslipModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs no-print"
         @keydown.escape.window="payslipModalOpen = false">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[95vh]"
             @click.away="payslipModalOpen = false">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Official Faculty Salary Payslip</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            Verified salary disbursement voucher for <span x-text="selectedSlip?.month_name"></span> <span x-text="selectedSlip?.year"></span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="printSlip()" class="px-3 py-1.5 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition inline-flex items-center gap-1.5 font-normal cursor-pointer shadow-xs">
                        <i class="fa-solid fa-print text-[10px]"></i>
                        <span>Print</span>
                    </button>
                    <button type="button" @click="payslipModalOpen = false" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Printable Payslip Body -->
            <div class="p-6 overflow-y-auto space-y-5 payslip-print-container" x-show="selectedSlip">
                {{-- School / Academy Header --}}
                <div class="flex items-start justify-between border-b border-subtle pb-4">
                    <div>
                        <h3 class="text-base font-semibold text-theme-text">{{ config('app.name', 'Academy') }}</h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">Faculty Salary Disbursement &amp; Remuneration Voucher</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-normal"
                              :class="selectedSlip?.status === 'paid' ? 'bg-success-bg text-success' : 'bg-warning-bg text-warning'">
                            <span class="capitalize" x-text="selectedSlip?.status"></span>
                        </span>
                        <div class="text-[10px] text-theme-text-muted mt-1 font-mono">
                            Cycle: <span x-text="selectedSlip?.month_name"></span> <span x-text="selectedSlip?.year"></span>
                        </div>
                    </div>
                </div>

                {{-- Teacher Identification Strip --}}
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-secondary rounded-lg text-xs font-normal">
                    <div>
                        <span class="text-[10px] text-theme-text-muted uppercase block">Faculty Member</span>
                        <span class="font-semibold text-theme-text" x-text="selectedSlip?.teacher_name"></span>
                        <span class="text-[11px] text-theme-text-muted block" x-text="selectedSlip?.designation"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-theme-text-muted uppercase block">Payment Method &amp; Ref</span>
                        <span class="font-normal text-theme-text" x-text="selectedSlip?.payment_method"></span>
                        <span class="text-[11px] text-theme-text-muted font-mono block" x-text="selectedSlip?.transaction_reference"></span>
                    </div>
                </div>

                {{-- Earnings vs Deductions Table --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Earnings Column --}}
                    <div class="space-y-2">
                        <div class="text-xs font-semibold text-success uppercase tracking-wider pb-1 border-b border-subtle flex justify-between">
                            <span>Earnings / Additions</span>
                            <span>Amount</span>
                        </div>
                        <div class="space-y-1.5 text-xs font-normal">
                            <div class="flex justify-between py-1">
                                <span class="text-theme-text">Base Stipend</span>
                                <span class="font-mono text-theme-text">₹<span x-text="Number(selectedSlip?.base_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>

                            <template x-for="b in (selectedSlip?.fixed_bonuses || [])" :key="b.id">
                                <div class="flex justify-between py-1 text-[11px]">
                                    <span class="text-theme-text-muted" x-text="b.name + ' (Fixed)'"></span>
                                    <span class="font-mono text-success">+ ₹<span x-text="Number(b.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                                </div>
                            </template>

                            <template x-for="vb in (selectedSlip?.variable_bonuses || [])" :key="vb.id">
                                <div class="flex justify-between py-1 text-[11px]">
                                    <span class="text-theme-text-muted" x-text="vb.name + ' (Bonus)'"></span>
                                    <span class="font-mono text-success">+ ₹<span x-text="Number(vb.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                                </div>
                            </template>

                            <template x-for="c in (selectedSlip?.claims || [])" :key="c.id">
                                <div class="flex justify-between py-1 text-[11px]">
                                    <span class="text-theme-text-muted" x-text="c.name + ' (Claim)'"></span>
                                    <span class="font-mono text-info">+ ₹<span x-text="Number(c.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Deductions Column --}}
                    <div class="space-y-2">
                        <div class="text-xs font-semibold text-danger uppercase tracking-wider pb-1 border-b border-subtle flex justify-between">
                            <span>Statutory &amp; Deductions</span>
                            <span>Amount</span>
                        </div>
                        <div class="space-y-1.5 text-xs font-normal">
                            <template x-for="fd in (selectedSlip?.fixed_deductions || [])" :key="fd.id">
                                <div class="flex justify-between py-1 text-[11px]">
                                    <span class="text-theme-text-muted" x-text="fd.name + ' (Fixed PF/ESI)'"></span>
                                    <span class="font-mono text-danger">- ₹<span x-text="Number(fd.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                                </div>
                            </template>

                            <template x-for="vd in (selectedSlip?.variable_deductions || [])" :key="vd.id">
                                <div class="flex justify-between py-1 text-[11px]">
                                    <span class="text-theme-text-muted" x-text="vd.name + ' (Variable)'"></span>
                                    <span class="font-mono text-danger">- ₹<span x-text="Number(vd.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                                </div>
                            </template>

                            <template x-if="(!selectedSlip?.fixed_deductions || selectedSlip?.fixed_deductions.length === 0) && (!selectedSlip?.variable_deductions || selectedSlip?.variable_deductions.length === 0)">
                                <div class="text-[11px] text-theme-text-muted italic py-1">
                                    No deductions applied for this cycle.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Net Disbursed Highlight Card --}}
                <div class="p-4 bg-brand text-primary rounded-lg flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase block tracking-wider opacity-90">Net Disbursed Take-Home</span>
                        <div class="text-2xl font-semibold mt-0.5">
                            ₹<span x-text="Number(selectedSlip?.net_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                    <div class="text-right text-xs opacity-90">
                        <div>Disbursed On: <span x-text="selectedSlip?.paid_at"></span></div>
                        <div class="text-[10px]">Verified Digital Record</div>
                    </div>
                </div>

                {{-- Signatures Footer --}}
                <div class="pt-8 grid grid-cols-2 gap-8 text-center text-xs text-theme-text-muted font-normal">
                    <div>
                        <div class="border-t border-subtle pt-2">Authorized Signatory / Administrator</div>
                    </div>
                    <div>
                        <div class="border-t border-subtle pt-2">Faculty Member Signature</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 bg-secondary flex items-center justify-end gap-3">
                <button type="button" @click="payslipModalOpen = false"
                        class="px-5 py-2.5 bg-primary text-theme-text rounded-full text-xs font-normal hover:bg-subtle transition cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- EDIT PERSONAL DETAILS MODAL                              --}}
    {{-- ======================================================== --}}
    <div x-show="editBioModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="editBioModalOpen = false">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="editBioModalOpen = false">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Edit Faculty Profile Details</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            Update verified personal, professional and emergency contact information
                        </p>
                    </div>
                </div>
                <button type="button" @click="editBioModalOpen = false" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" action="{{ route('admin.teachers.profile.update', $teacher->id) }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">First Name *</label>
                        <input type="text" name="fname" value="{{ $teacher->fname }}" required
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Last Name</label>
                        <input type="text" name="lname" value="{{ $teacher->lname }}"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Designation</label>
                        <input type="text" name="designation" value="{{ $teacher->designation }}" placeholder="e.g. Senior Physics Faculty"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Highest Qualification</label>
                        <input type="text" name="qualification" value="{{ $teacher->qualification }}" placeholder="e.g. M.Sc, B.Ed"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ $teacher->email }}" required
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Mobile / Phone</label>
                        <input type="text" name="number" value="{{ $teacher->number }}" placeholder="e.g. 9876543210"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Blood Group</label>
                        <input type="text" name="blood_group" value="{{ $teacher->blood_group }}" placeholder="e.g. B+"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->format('Y-m-d') : '' }}"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Joining Date</label>
                        <input type="date" name="joining_date" value="{{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('Y-m-d') : '' }}"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact" value="{{ $teacher->emergency_contact }}" placeholder="Emergency number"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">City</label>
                        <input type="text" name="city" value="{{ $teacher->city }}" placeholder="City"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">State</label>
                        <input type="text" name="state" value="{{ $teacher->state }}" placeholder="State"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Pincode</label>
                        <input type="text" name="pincode" value="{{ $teacher->pincode }}" placeholder="PIN code"
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">Residential Address</label>
                    <textarea name="address" rows="2" placeholder="Full residential street address"
                              class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-lg focus:outline-none font-normal">{{ $teacher->address }}</textarea>
                </div>

                <div>
                    <label class="text-[10px] text-theme-text-muted uppercase font-normal block mb-1">About / Bio Notes</label>
                    <textarea name="about" rows="3" placeholder="Professional biography notes and academic background..."
                              class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-lg focus:outline-none font-normal">{{ $teacher->about }}</textarea>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="editBioModalOpen = false"
                            class="px-5 py-2.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 1: SALARY STRUCTURE MODAL                          --}}
    {{-- ======================================================== --}}
    <div x-show="isSalaryModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closeSalaryModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-xl overflow-hidden flex flex-col max-h-[92vh]"
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
            <form method="POST" action="{{ route('admin.teachers.update-salary', $teacher->id) }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="fixed_deductions_json" :value="JSON.stringify(salaryForm.fixedDeductionsList)">
                <input type="hidden" name="fixed_bonuses_json" :value="JSON.stringify(salaryForm.fixedBonusesList)">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-theme-text">{{ $teacher->fname }} {{ $teacher->lname }}</div>
                        <div class="text-[11px] text-theme-text-muted font-normal" x-text="salaryForm.designation || 'Faculty Member'"></div>
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

    {{-- ======================================================== --}}
    {{-- MODAL 2: MONTHLY BONUSES & INCENTIVES                    --}}
    {{-- ======================================================== --}}
    <div x-show="isBonusModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closeBonusModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeBonusModal()">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-success-bg text-success flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Monthly Bonuses &amp; Incentives</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            For <span x-text="bonusForm.monthName"></span> <span x-text="bonusForm.year"></span> &bull; Award performance or festival bonus
                        </p>
                    </div>
                </div>
                <button type="button" @click="closeBonusModal()" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" action="{{ route('admin.teachers.payroll.save-component') }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $teacher->id }}">
                <input type="hidden" name="month" :value="bonusForm.month">
                <input type="hidden" name="year" :value="bonusForm.year">
                <input type="hidden" name="type" value="bonus">
                <input type="hidden" name="items_json" :value="JSON.stringify(bonusForm.list)">

                <!-- Faculty & Total Salary Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-theme-text">{{ $teacher->fname }} {{ $teacher->lname }}</div>
                            <div class="text-[11px] text-theme-text-muted font-normal">{{ $teacher->designation ?: 'Faculty Member' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-theme-text-muted font-normal">Base Contract Salary</div>
                        <div class="text-xs font-semibold text-theme-text">
                            ₹<span x-text="Number(bonusForm.baseSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                </div>

                <!-- 1. FIXED RECURRING ALLOWANCES (READ-ONLY ITEMIZED LIST) -->
                <div class="bg-secondary p-4 rounded-lg space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gift text-brand text-xs"></i>
                            <span class="text-xs font-semibold text-theme-text">Fixed Monthly Allowances</span>
                        </div>
                        <span class="text-[10px] text-theme-text-muted bg-primary px-2.5 py-0.5 rounded-full font-normal">
                            Recurring Monthly
                        </span>
                    </div>

                    <!-- Itemized List (Like Disbursement Form) -->
                    <div class="space-y-1.5 pt-1 border-t border-subtle/40">
                        <template x-for="fb in bonusForm.fixedBonusesList" :key="'fb_' + (fb.id || fb.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success/60 shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="fb.name"></span>
                                    <span class="text-[10px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full font-normal">Fixed</span>
                                </div>
                                <span class="font-semibold text-success">
                                    + ₹<span x-text="Number(fb.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                </span>
                            </div>
                        </template>

                        <template x-if="!bonusForm.fixedBonusesList || bonusForm.fixedBonusesList.length === 0">
                            <div class="text-[11px] text-theme-text-muted font-normal py-1">
                                No recurring allowances (HRA, Conveyance) configured for this faculty.
                            </div>
                        </template>
                    </div>

                    <!-- Instruction Note & Action to Edit in Salary Structure -->
                    <div class="p-3 bg-primary rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 mt-2">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-brand text-xs mt-0.5 shrink-0"></i>
                            <p class="text-[11px] text-theme-text-muted leading-relaxed font-normal">
                                Fixed allowances apply automatically every month. To add, edit, or remove recurring allowances, update them in <span class="text-theme-text font-semibold">Salary Structure</span>.
                            </p>
                        </div>
                        <button type="button"
                                @click="closeBonusModal(); openSalaryModal()"
                                class="px-3 py-1.5 bg-brand/10 hover:bg-brand text-brand hover:text-primary text-[11px] rounded-full transition font-normal inline-flex items-center gap-1.5 shrink-0 cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            <span>Edit in Salary Structure</span>
                        </button>
                    </div>
                </div>

                <!-- 2. VARIABLE BONUSES (FOR CURRENT MONTH ONLY) -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <div class="text-xs font-semibold text-theme-text flex items-center gap-1.5">
                                <i class="fa-solid fa-award text-success text-xs"></i>
                                <span>Monthly Variable Bonuses (<span x-text="bonusForm.monthName"></span> <span x-text="bonusForm.year"></span>)</span>
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal mt-0.5">
                                Temporary one-off incentives for this month only. Resets next month.
                            </p>
                        </div>
                        <span class="px-2.5 py-1 bg-success-bg text-success rounded-full text-xs font-normal">
                            + ₹<span x-text="Number(bonusForm.total).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </span>
                    </div>

                    <!-- Quick Add Chips -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-theme-text-muted mr-1 font-normal">Quick Add:</span>
                        <button type="button" @click="addBonus('Best Performance Bonus', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Best Performance
                        </button>
                        <button type="button" @click="addBonus('Joining Bonus', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Joining Bonus
                        </button>
                        <button type="button" @click="addBonus('Festival Bonus', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Festival Bonus
                        </button>
                        <button type="button" @click="addBonus('Academic Excellence', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Academic Excellence
                        </button>
                        <button type="button" @click="addBonus('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom Bonus
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in bonusForm.list" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Bonus title (e.g. Best Performance Award)"
                                       class="flex-1 bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                                <div class="relative w-32">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-theme-text-muted">₹</span>
                                    <input type="number" step="0.01" min="0" x-model.number="item.amount" @input="recalcBonus()" placeholder="0.00"
                                           class="w-full bg-primary text-success text-xs pl-7 pr-4 py-2.5 rounded-full focus:outline-none text-right font-normal">
                                </div>
                                <button type="button" @click="removeBonus(index)"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs shrink-0 transition cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="bonusForm.list.length === 0">
                            <div class="text-[11px] text-theme-text-muted py-3 text-center bg-primary rounded-lg font-normal">
                                No variable bonuses added for this month. Click any preset chip above to add one.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeBonusModal()"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Bonuses for <span x-text="bonusForm.monthName"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 3: MONTHLY VARIABLE DEDUCTIONS                     --}}
    {{-- ======================================================== --}}
    <div x-show="isDeductionModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closeDeductionModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeDeductionModal()">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-danger-bg text-danger flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Monthly Variable Deductions</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            For <span x-text="deductionForm.monthName"></span> <span x-text="deductionForm.year"></span> &bull; Manage leaves, penalties, or advance deductions
                        </p>
                    </div>
                </div>
                <button type="button" @click="closeDeductionModal()" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" action="{{ route('admin.teachers.payroll.save-component') }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $teacher->id }}">
                <input type="hidden" name="month" :value="deductionForm.month">
                <input type="hidden" name="year" :value="deductionForm.year">
                <input type="hidden" name="type" value="deduction">
                <input type="hidden" name="items_json" :value="JSON.stringify(deductionForm.list)">

                <!-- Faculty & Total Salary Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-theme-text">{{ $teacher->fname }} {{ $teacher->lname }}</div>
                            <div class="text-[11px] text-theme-text-muted font-normal">{{ $teacher->designation ?: 'Faculty Member' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-theme-text-muted font-normal">Base Contract Salary</div>
                        <div class="text-xs font-semibold text-theme-text">
                            ₹<span x-text="Number(deductionForm.baseSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                </div>

                <!-- 1. FIXED STATUTORY DEDUCTIONS (READ-ONLY ITEMIZED LIST) -->
                <div class="bg-secondary p-4 rounded-lg space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-brand text-xs"></i>
                            <span class="text-xs font-semibold text-theme-text">Fixed Statutory Deductions</span>
                        </div>
                        <span class="text-[10px] text-theme-text-muted bg-primary px-2.5 py-0.5 rounded-full font-normal">
                            Recurring Monthly
                        </span>
                    </div>

                    <!-- Itemized List (Like Disbursement Form) -->
                    <div class="space-y-1.5 pt-1 border-t border-subtle/40">
                        <template x-for="fd in deductionForm.fixedDeductionsList" :key="'fd_' + (fd.id || fd.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-danger/60 shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="fd.name"></span>
                                    <span class="text-[10px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full font-normal">Fixed</span>
                                </div>
                                <span class="font-semibold text-danger">
                                    - ₹<span x-text="Number(fd.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                </span>
                            </div>
                        </template>

                        <template x-if="!deductionForm.fixedDeductionsList || deductionForm.fixedDeductionsList.length === 0">
                            <div class="text-[11px] text-theme-text-muted font-normal py-1">
                                No recurring fixed deductions (PF, ESI) configured for this faculty.
                            </div>
                        </template>
                    </div>

                    <!-- Instruction Note & Action to Edit in Salary Structure -->
                    <div class="p-3 bg-primary rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 mt-2">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-brand text-xs mt-0.5 shrink-0"></i>
                            <p class="text-[11px] text-theme-text-muted leading-relaxed font-normal">
                                Fixed deductions (PF, ESI) apply automatically every month. To add, edit, or remove recurring fixed deductions, update them in <span class="text-theme-text font-semibold">Salary Structure</span>.
                            </p>
                        </div>
                        <button type="button"
                                @click="closeDeductionModal(); openSalaryModal()"
                                class="px-3 py-1.5 bg-brand/10 hover:bg-brand text-brand hover:text-primary text-[11px] rounded-full transition font-normal inline-flex items-center gap-1.5 shrink-0 cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            <span>Edit in Salary Structure</span>
                        </button>
                    </div>
                </div>

                <!-- 2. VARIABLE DEDUCTIONS (FOR CURRENT MONTH ONLY) -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <div class="text-xs font-semibold text-theme-text flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-days text-danger text-xs"></i>
                                <span>Monthly Variable Deductions (<span x-text="deductionForm.monthName"></span> <span x-text="deductionForm.year"></span>)</span>
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal mt-0.5">
                                Temporary deductions for this month only (leaves, penalties, advance recovery). Resets next month.
                            </p>
                        </div>
                        <span class="px-2.5 py-1 bg-danger-bg text-danger rounded-full text-xs font-normal">
                            - ₹<span x-text="Number(deductionForm.total).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </span>
                    </div>

                    <!-- Quick Add Chips -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-theme-text-muted mr-1 font-normal">Quick Add:</span>
                        <button type="button" @click="addDeduction('Unpaid Leave Deduction', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Unpaid Leave
                        </button>
                        <button type="button" @click="addDeduction('Late / Discipline Fine', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Late Fine
                        </button>
                        <button type="button" @click="addDeduction('Salary Advance Recovery', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Advance Recovery
                        </button>
                        <button type="button" @click="addDeduction('Professional Tax', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-danger"></i> Prof. Tax
                        </button>
                        <button type="button" @click="addDeduction('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in deductionForm.list" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Deduction title (e.g. Unpaid Leave, Salary Advance)"
                                       class="flex-1 bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                                <div class="relative w-32">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-theme-text-muted">₹</span>
                                    <input type="number" step="0.01" min="0" x-model.number="item.amount" @input="recalcDeduction()" placeholder="0.00"
                                           class="w-full bg-primary text-danger text-xs pl-7 pr-4 py-2.5 rounded-full focus:outline-none text-right font-normal">
                                </div>
                                <button type="button" @click="removeDeduction(index)"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs shrink-0 transition cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="deductionForm.list.length === 0">
                            <div class="text-[11px] text-theme-text-muted py-3 text-center bg-primary rounded-lg font-normal">
                                No variable deductions added for this month.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeDeductionModal()"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Deductions for <span x-text="deductionForm.monthName"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 4: EXPENSE CLAIMS & REIMBURSEMENTS                 --}}
    {{-- ======================================================== --}}
    <div x-show="isReimbursementModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closeReimbursementModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeReimbursementModal()">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-info-bg text-info flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Expense Claims &amp; Reimbursements</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            Personal money spent by teacher for school returned with salary
                        </p>
                    </div>
                </div>
                <button type="button" @click="closeReimbursementModal()" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" action="{{ route('admin.teachers.payroll.save-component') }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $teacher->id }}">
                <input type="hidden" name="month" :value="reimbursementForm.month">
                <input type="hidden" name="year" :value="reimbursementForm.year">
                <input type="hidden" name="type" value="reimbursement">
                <input type="hidden" name="items_json" :value="JSON.stringify(reimbursementForm.list)">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-theme-text">{{ $teacher->fname }} {{ $teacher->lname }}</div>
                        <div class="text-[11px] text-theme-text-muted font-normal">{{ $teacher->designation ?: 'Faculty Member' }}</div>
                    </div>
                    <span class="px-3 py-1 bg-info-bg text-info rounded-full text-xs font-normal">
                        + ₹<span x-text="Number(reimbursementForm.total).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                    </span>
                </div>

                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <!-- Quick Add Chips -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-theme-text-muted mr-1 font-normal">Quick Add:</span>
                        <button type="button" @click="addReimbursement('Teaching Materials / Books', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-info"></i> Books / Materials
                        </button>
                        <button type="button" @click="addReimbursement('Lab Supplies', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-info"></i> Lab Supplies
                        </button>
                        <button type="button" @click="addReimbursement('Travel & Transport Claim', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-info"></i> Travel / Fuel
                        </button>
                        <button type="button" @click="addReimbursement('Event / Refreshments Expense', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-info"></i> Refreshments
                        </button>
                        <button type="button" @click="addReimbursement('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom Claim
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in reimbursementForm.list" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Expense description (e.g. Lab items purchased, Train ticket)"
                                       class="flex-1 bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                                <div class="relative w-32">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-theme-text-muted">₹</span>
                                    <input type="number" step="0.01" min="0" x-model.number="item.amount" @input="recalcReimbursement()" placeholder="0.00"
                                           class="w-full bg-primary text-info text-xs pl-7 pr-4 py-2.5 rounded-full focus:outline-none text-right font-normal">
                                </div>
                                <button type="button" @click="removeReimbursement(index)"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs shrink-0 transition cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="reimbursementForm.list.length === 0">
                            <div class="text-[11px] text-theme-text-muted py-3 text-center bg-primary rounded-lg font-normal">
                                No reimbursement claims added. If faculty paid personal amount for institute needs, add it here.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeReimbursementModal()"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Reimbursements</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 5: FINAL SALARY DISBURSEMENT & SETTLEMENT          --}}
    {{-- ======================================================== --}}
    <div x-show="isPayoutModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs no-print"
         @keydown.escape.window="closePayoutModal()">
        
        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-xl overflow-hidden flex flex-col max-h-[92vh]"
             @click.away="closePayoutModal()">

            <!-- Header -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm font-semibold">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-theme-text">Faculty Salary Payout &amp; Settlement</h2>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                            Monthly compensation overview, breakdown review, and disbursement
                        </p>
                    </div>
                </div>
                <button type="button" @click="closePayoutModal()" class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.teachers.payroll.mark-paid') }}" class="p-5 overflow-y-auto space-y-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $teacher->id }}">
                <input type="hidden" name="month" :value="payoutForm.month">
                <input type="hidden" name="year" :value="payoutForm.year">
                <input type="hidden" name="base_salary" :value="payoutForm.baseSalary">
                <input type="hidden" name="deductions" :value="payoutForm.deductionsTotal">
                <input type="hidden" name="bonuses" :value="payoutForm.bonusesTotal">
                <input type="hidden" name="reimbursements" :value="payoutForm.reimbursementsTotal">
                <input type="hidden" name="net_salary" :value="payoutForm.netSalary">
                <input type="hidden" name="breakdown_json" :value="JSON.stringify({ deductions: payoutForm.variableDeductions, bonuses: payoutForm.variableBonuses, reimbursements: payoutForm.reimbursements })">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-theme-text">{{ $teacher->fname }} {{ $teacher->lname }}</div>
                            <div class="text-[11px] text-theme-text-muted font-normal">{{ $teacher->designation ?: 'Faculty Member' }}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-primary text-theme-text rounded-full text-xs font-normal">
                        <span x-text="payoutForm.monthName"></span> <span x-text="payoutForm.year"></span>
                    </span>
                </div>

                <!-- 1. EARNINGS & COMPENSATION OVERVIEW -->
                <div class="bg-secondary p-4 rounded-lg space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-coins text-brand text-xs"></i>
                            <span class="text-xs font-semibold text-theme-text">Earnings &amp; Compensation</span>
                        </div>
                        <span class="text-xs font-semibold text-success">
                          Total + ₹<span x-text="Number(payoutForm.baseSalary + payoutForm.bonusesTotal + payoutForm.reimbursementsTotal).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </span>
                    </div>

                    <div class="space-y-1.5 pt-1 border-t border-subtle/40">
                        <!-- Base Stipend Item -->
                        <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand shrink-0"></span>
                                <span class="text-theme-text font-normal">Contract Base Monthly Stipend</span>
                                <span class="text-[10px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full font-normal">Base</span>
                            </div>
                            <span class="font-semibold text-theme-text">₹<span x-text="Number(payoutForm.baseSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                        </div>

                        <!-- Fixed Recurring Allowances -->
                        <template x-for="fb in payoutForm.fixedBonuses" :key="'fb_' + (fb.id || fb.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success/60 shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="fb.name"></span>
                                    <span class="text-[10px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full font-normal">Fixed</span>
                                </div>
                                <span class="font-semibold text-success">+ ₹<span x-text="Number(fb.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>
                        </template>

                        <!-- Variable Monthly Bonuses -->
                        <template x-for="b in payoutForm.variableBonuses" :key="'bonus_' + (b.id || b.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="b.name"></span>
                                    <span class="text-[10px] text-success bg-success-bg px-2 py-0.5 rounded-full font-normal">Bonus</span>
                                </div>
                                <span class="font-semibold text-success">+ ₹<span x-text="Number(b.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>
                        </template>

                        <!-- Individual Expense Claims / Reimbursements -->
                        <template x-for="r in payoutForm.reimbursements" :key="'claim_' + (r.id || r.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-info shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="r.name"></span>
                                    <span class="text-[10px] text-info bg-info-bg px-2 py-0.5 rounded-full font-normal">Reimbursement</span>
                                </div>
                                <span class="font-semibold text-info">+ ₹<span x-text="Number(r.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>
                        </template>

                        <template x-if="payoutForm.bonusesTotal === 0 && payoutForm.reimbursementsTotal === 0">
                            <div class="text-[11px] text-theme-text-muted font-normal pt-0.5">
                                Standard monthly stipend without additional bonuses or reimbursement claims.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 2. DEDUCTIONS OVERVIEW -->
                <div class="bg-secondary p-4 rounded-lg space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-danger text-xs"></i>
                            <span class="text-xs font-semibold text-theme-text">Deductions Overview</span>
                        </div>
                        <span class="text-xs font-semibold text-danger">
                           Total - ₹<span x-text="Number(payoutForm.deductionsTotal).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </span>
                    </div>

                    <div class="space-y-1.5 pt-1 border-t border-subtle/40">
                        <!-- Fixed Statutory Deductions (PF, ESI) -->
                        <template x-for="fd in payoutForm.fixedDeductions" :key="'fixed_' + (fd.id || fd.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-danger/60 shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="fd.name"></span>
                                    <span class="text-[10px] text-theme-text-muted bg-primary px-2 py-0.5 rounded-full font-normal">Fixed</span>
                                </div>
                                <span class="font-semibold text-danger">- ₹<span x-text="Number(fd.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>
                        </template>

                        <!-- Variable Deductions (Fines, Leaves, Advance) -->
                        <template x-for="td in payoutForm.variableDeductions" :key="'temp_' + (td.id || td.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="td.name"></span>
                                    <span class="text-[10px] text-warning bg-warning-bg px-2 py-0.5 rounded-full font-normal">Variable</span>
                                </div>
                                <span class="font-semibold text-danger">- ₹<span x-text="Number(td.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                            </div>
                        </template>

                        <template x-if="payoutForm.deductionsTotal === 0">
                            <div class="text-[11px] text-theme-text-muted font-normal py-1">
                                No deductions applied. 100% full stipend eligible.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. FINAL NET PAYOUT HIGHLIGHT CARD -->
                <div class="p-4 bg-brand text-primary rounded-lg space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-normal opacity-90">Calculated Final Net Payout</span>
                        <div class="text-2xl font-semibold">
                            ₹<span x-text="Number(payoutForm.netSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-primary/20 text-xs font-normal opacity-90 flex items-center justify-between flex-wrap gap-1">
                        <span>
                            ₹<span x-text="Number(payoutForm.baseSalary).toLocaleString('en-IN')"></span> (Base)
                            - ₹<span x-text="Number(payoutForm.deductionsTotal).toLocaleString('en-IN')"></span> (Deductions)
                            <template x-if="(payoutForm.bonusesTotal + payoutForm.reimbursementsTotal) > 0">
                                <span>+ ₹<span x-text="Number(payoutForm.bonusesTotal + payoutForm.reimbursementsTotal).toLocaleString('en-IN')"></span> (Additions)</span>
                            </template>
                        </span>
                        <span class="font-semibold text-xs">Total: ₹<span x-text="Number(payoutForm.netSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                    </div>
                </div>

                <!-- 4. DISBURSEMENT PAYMENT METHOD & CONFIRMATION -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-brand text-xs"></i>
                        <span class="text-xs font-semibold text-theme-text">Disbursement Payment Settlement</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Method *</label>
                            <select name="payment_method" x-model="payoutForm.paymentMethod" required
                                    class="w-full bg-primary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="bank_transfer">Bank Transfer / NEFT / IMPS</option>
                                <option value="upi">UPI / Instant Pay</option>
                                <option value="cash">Cash in Hand</option>
                                <option value="cheque">Cheque Payout</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Transaction Reference / UTR #</label>
                            <input type="text" name="transaction_reference" x-model="payoutForm.transactionRef" placeholder="e.g. UTR-987410294 or Cheque #"
                                   class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none font-normal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Disbursement Remarks / Notes (Optional)</label>
                        <textarea name="notes" x-model="payoutForm.notes" rows="2" placeholder="e.g. Monthly salary disbursed via bank transfer."
                                  class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-lg focus:outline-none font-normal resize-none"></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closePayoutModal()"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span>Confirm &amp; Mark as Paid (₹<span x-text="Number(payoutForm.netSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
