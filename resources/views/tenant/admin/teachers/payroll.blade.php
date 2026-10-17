@extends('layouts.admin')
@section('title', 'Faculty Salary & Monthly Payroll')

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
    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    $monthName = $monthNames[$currentMonth] ?? date('F');

    $totalTeachersCount = $teachers->count();
    $totalGross = $teachers->sum(fn($t) => (float)($t->salaryStructure ? $t->salaryStructure->base_salary : ($t->base_salary ?: 0)));
    $paidRecords = $teachers->filter(fn($t) => $t->payroll_record && $t->payroll_record->status === 'paid');
    $totalDisbursed = $paidRecords->sum(fn($t) => (float)$t->payroll_record->net_salary);
    $pendingRecords = $teachers->filter(fn($t) => !$t->payroll_record || $t->payroll_record->status !== 'paid');
    $totalPending = $pendingRecords->sum(fn($t) => (float)($t->payroll_record ? $t->payroll_record->net_salary : ($t->computed_net ?? $t->base_salary)));

    $teacherPayrollState = $teachers->map(function($t) use ($daysInMonth, $workingDaysCount, $holidayCount) {
        $p = $t->payroll_record;
        $isPaid = $p && $p->status === 'paid';
        $struct = $t->salary_structure ?: $t->salaryStructure;
        $base = (float)($p ? $p->base_salary : ($struct ? $struct->base_salary : ($t->base_salary ?: 0)));
        $net = $p ? (float)$p->net_salary : (float)($t->computed_net ?? $base);
        $deductions = $p ? (float)$p->deductions : (float)($t->computed_deductions ?? 0);
        $bonuses = $p ? (float)($p->bonuses ?? 0) : (float)($t->computed_bonuses ?? 0);
        $reimbursements = $p ? (float)($p->reimbursements ?? 0) : 0;
        $breakdown = $p && $p->breakdown ? $p->breakdown : ($t->computed_breakdown ?? null);
        $fixedDeductions = ($struct && is_array($struct->fixed_deductions)) ? $struct->fixed_deductions : [];
        $fixedBonuses = ($struct && is_array($struct->fixed_bonuses)) ? $struct->fixed_bonuses : [];

        return [
            'id' => $t->id,
            'name' => trim($t->fname . ' ' . $t->lname),
            'initials' => strtoupper(substr($t->fname, 0, 1) . substr($t->lname ?: 'T', 0, 1)),
            'username' => $t->username,
            'designation' => $t->designation ?: 'Faculty Member',
            'qualification' => $t->qualification ?: 'Not Specified',
            'base_salary' => $base,
            'salary_type' => $struct ? $struct->salary_type : ($t->salary_type ?: 'monthly'),
            'fixed_deductions' => $fixedDeductions,
            'fixed_bonuses' => $fixedBonuses,
            'joining_date_raw' => $t->joining_date ? $t->joining_date->format('Y-m-d') : ($t->created_at ? $t->created_at->format('Y-m-d') : ''),
            'present_days' => (float)($t->present_days ?? 0),
            'unpaid_absent_days' => (int)($t->unpaid_absent_days ?? 0),
            'days_in_month' => $daysInMonth,
            'working_days' => (int)($t->working_days ?? $workingDaysCount),
            'holiday_count' => (int)($t->holiday_count ?? $holidayCount),
            'is_paid' => $isPaid,
            'net_salary' => $net,
            'deductions' => $deductions,
            'bonuses' => $bonuses,
            'reimbursements' => $reimbursements,
            'breakdown' => $breakdown,
            'paid_at' => $isPaid && $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d M Y, h:i A') : null,
            'payment_method' => $isPaid ? ucwords(str_replace('_', ' ', $p->payment_method)) : null,
            'payment_method_raw' => $isPaid ? $p->payment_method : 'upi',
            'transaction_reference' => $isPaid ? ($p->transaction_reference ?? '') : '',
            'notes' => $isPaid ? ($p->notes ?? '') : '',
        ];
    });
@endphp

<script>
    window.teacherPayrollData = {
        month: {{ $currentMonth }},
        year: {{ $currentYear }},
        monthName: '{{ $monthName }}',
        daysInMonth: {{ $daysInMonth }},
        workingDaysCount: {{ $workingDaysCount }},
        holidayCount: {{ $holidayCount }},
        teachers: @json($teacherPayrollState)
    };

    window.teacherPayrollManager = function teacherPayrollManager() {
        return {
            searchQuery: '',
            statusFilter: 'all',
            month: window.teacherPayrollData.month,
            year: window.teacherPayrollData.year,
            monthName: window.teacherPayrollData.monthName,
            daysInMonth: window.teacherPayrollData.daysInMonth,
            workingDaysCount: window.teacherPayrollData.workingDaysCount,
            holidayCount: window.teacherPayrollData.holidayCount,
            teachers: window.teacherPayrollData.teachers || [],

            // Dropdown menu state
            openActionMenuId: null,

            // Modals
            isSalaryModalOpen: false,
            isBonusModalOpen: false,
            isDeductionModalOpen: false,
            isReimbursementModalOpen: false,
            isPayoutModalOpen: false,
            isSlipModalOpen: false,

            // 1. SALARY STRUCTURE & FIXED DEDUCTIONS/BONUSES FORM
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
                    joiningDate: teacher.joining_date_raw || '',
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

            // 2. MONTHLY BONUSES MODAL (ONLY VARIABLE / SPOT BONUSES FOR THIS MONTH)
            bonusForm: {
                userId: '',
                teacherName: '',
                designation: '',
                baseSalary: 0,
                fixedBonusesList: [],
                teacherRaw: null,
                month: window.teacherPayrollData.month,
                year: window.teacherPayrollData.year,
                list: [],
                total: 0
            },

            openBonusModal(teacher) {
                // Fixed allowances & recurring bonuses are managed under Salary Structure.
                // This popup ONLY manages variable / spot bonuses for this specific month.
                const fixedBonusList = teacher.fixed_bonuses && teacher.fixed_bonuses.length
                    ? JSON.parse(JSON.stringify(teacher.fixed_bonuses))
                    : [];
                const fixedBonusNames = fixedBonusList.map(b => (b.name || '').toLowerCase().trim());
                let b = (teacher.breakdown && teacher.breakdown.bonuses && teacher.breakdown.bonuses.length)
                    ? JSON.parse(JSON.stringify(teacher.breakdown.bonuses))
                    : [];

                // Filter out any fixed bonuses that may match
                b = b.filter(item => !fixedBonusNames.includes((item.name || '').toLowerCase().trim()));

                this.bonusForm = {
                    userId: teacher.id,
                    teacherName: teacher.name,
                    designation: teacher.designation || 'Faculty Member',
                    baseSalary: parseFloat(teacher.base_salary) || 0,
                    fixedBonusesList: fixedBonusList,
                    teacherRaw: teacher,
                    month: this.month,
                    year: this.year,
                    list: b,
                    total: 0
                };
                this.recalcBonus();
                this.isBonusModalOpen = true;
                this.openActionMenuId = null;
            },

            addBonus(name = '', amount = '') {
                this.bonusForm.list.push({
                    id: Date.now() + Math.random(),
                    name: name,
                    amount: amount !== '' ? parseFloat(amount) : ''
                });
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

            closeBonusModal() {
                this.isBonusModalOpen = false;
            },

            // 3. MONTHLY VARIABLE DEDUCTIONS MODAL (ONLY TEMPORARY / VARIABLE DEDUCTIONS FOR THIS MONTH)
            deductionForm: {
                userId: '',
                teacherName: '',
                designation: '',
                baseSalary: 0,
                fixedDeductionsList: [],
                teacherRaw: null,
                month: window.teacherPayrollData.month,
                year: window.teacherPayrollData.year,
                list: [],
                total: 0
            },

            openDeductionModal(teacher) {
                // Fixed deductions (PF, ESI) are recurring monthly and managed under Salary Structure.
                // This popup strictly manages temporary / monthly variable deductions (leaves, fines, advance recovery).
                const fixedDedList = teacher.fixed_deductions && teacher.fixed_deductions.length
                    ? JSON.parse(JSON.stringify(teacher.fixed_deductions))
                    : [];
                const fixedNames = fixedDedList.map(f => (f.name || '').toLowerCase().trim());
                let d = (teacher.breakdown && teacher.breakdown.deductions && teacher.breakdown.deductions.length)
                    ? JSON.parse(JSON.stringify(teacher.breakdown.deductions))
                    : [];

                // Filter out any fixed deductions so they never appear in this popup
                d = d.filter(item => !fixedNames.includes((item.name || '').toLowerCase().trim()));

                this.deductionForm = {
                    userId: teacher.id,
                    teacherName: teacher.name,
                    designation: teacher.designation || 'Faculty Member',
                    baseSalary: parseFloat(teacher.base_salary) || 0,
                    fixedDeductionsList: fixedDedList,
                    teacherRaw: teacher,
                    month: this.month,
                    year: this.year,
                    list: d,
                    total: 0
                };
                this.recalcDeduction();
                this.isDeductionModalOpen = true;
                this.openActionMenuId = null;
            },

            addDeduction(name = '', amount = '') {
                this.deductionForm.list.push({
                    id: Date.now() + Math.random(),
                    name: name,
                    amount: amount !== '' ? parseFloat(amount) : ''
                });
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

            closeDeductionModal() {
                this.isDeductionModalOpen = false;
            },

            // 4. MONTHLY EXPENSE REIMBURSEMENTS MODAL
            reimbursementForm: {
                userId: '',
                teacherName: '',
                month: window.teacherPayrollData.month,
                year: window.teacherPayrollData.year,
                list: [],
                total: 0
            },

            openReimbursementModal(teacher) {
                const r = (teacher.breakdown && teacher.breakdown.reimbursements && teacher.breakdown.reimbursements.length)
                    ? JSON.parse(JSON.stringify(teacher.breakdown.reimbursements))
                    : (parseFloat(teacher.reimbursements) > 0 ? [{ id: Date.now(), name: 'Expense Claim', amount: parseFloat(teacher.reimbursements) }] : []);

                this.reimbursementForm = {
                    userId: teacher.id,
                    teacherName: teacher.name,
                    month: this.month,
                    year: this.year,
                    list: r,
                    total: 0
                };
                this.recalcReimbursement();
                this.isReimbursementModalOpen = true;
                this.openActionMenuId = null;
            },

            addReimbursement(name = '', amount = '') {
                this.reimbursementForm.list.push({
                    id: Date.now() + Math.random(),
                    name: name,
                    amount: amount !== '' ? parseFloat(amount) : ''
                });
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

            closeReimbursementModal() {
                this.isReimbursementModalOpen = false;
            },

            // 5. FINAL PAYOUT & SALARY DISBURSEMENT MODAL (Full Review & In-Place Editing)
            payoutForm: {
                userId: '',
                teacherName: '',
                designation: '',
                month: window.teacherPayrollData.month,
                year: window.teacherPayrollData.year,
                baseSalary: 0,
                deductionsList: [],
                bonusesList: [],
                reimbursementsList: [],
                deductionsTotal: 0,
                bonusesTotal: 0,
                reimbursementsTotal: 0,
                netSalary: 0,
                paymentMethod: 'bank_transfer',
                transactionReference: '',
                notes: '',
                updateBaseSalary: false
            },

            openPayoutModal(teacher) {
                const base = parseFloat(teacher.base_salary) || 0;
                const brk = teacher.breakdown || {};

                // 1. Fixed Deductions (From Salary Structure: PF, ESI, etc.)
                const fixedDeds = (teacher.fixed_deductions && Array.isArray(teacher.fixed_deductions)) 
                    ? JSON.parse(JSON.stringify(teacher.fixed_deductions)) 
                    : [];
                const fixedDedsTotal = fixedDeds.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                const fixedNames = fixedDeds.map(f => (f.name || '').trim().toLowerCase());

                // 2. All deductions in breakdown
                let allDeds = (brk.deductions && brk.deductions.length)
                    ? JSON.parse(JSON.stringify(brk.deductions))
                    : (parseFloat(teacher.deductions) > 0 ? [{ id: Date.now() + 1, name: 'Deductions', amount: parseFloat(teacher.deductions) }] : []);

                // 3. Separate Temporary Deductions (fines, leaves, variable deductions that are not fixed structure)
                let tempDeds = allDeds.filter(d => !fixedNames.includes((d.name || '').trim().toLowerCase()));
                const tempDedsTotal = tempDeds.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);

                // Combined Deductions Total
                const totalDeductions = fixedDedsTotal + tempDedsTotal;

                // 4. Bonuses & Claims
                let bons = (brk.bonuses && brk.bonuses.length)
                    ? JSON.parse(JSON.stringify(brk.bonuses))
                    : (parseFloat(teacher.bonuses) > 0 ? [{ id: Date.now() + 2, name: 'Bonus', amount: parseFloat(teacher.bonuses) }] : []);
                const bonsTotal = bons.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);

                let reims = (brk.reimbursements && brk.reimbursements.length)
                    ? JSON.parse(JSON.stringify(brk.reimbursements))
                    : (parseFloat(teacher.reimbursements) > 0 ? [{ id: Date.now() + 3, name: 'Expense Claim', amount: parseFloat(teacher.reimbursements) }] : []);
                const reimsTotal = reims.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);

                // 5. Net Payable
                const net = Math.max(0, base - totalDeductions + bonsTotal + reimsTotal);

                this.payoutForm = {
                    userId: teacher.id,
                    teacherName: teacher.name,
                    designation: teacher.designation,
                    month: this.month,
                    year: this.year,
                    baseSalary: base,
                    fixedDeductions: fixedDeds,
                    fixedDeductionsTotal: Math.round(fixedDedsTotal * 100) / 100,
                    temporaryDeductions: tempDeds,
                    temporaryDeductionsTotal: Math.round(tempDedsTotal * 100) / 100,
                    deductionsList: allDeds,
                    deductionsTotal: Math.round(totalDeductions * 100) / 100,
                    bonusesList: bons,
                    bonusesTotal: Math.round(bonsTotal * 100) / 100,
                    reimbursementsList: reims,
                    reimbursementsTotal: Math.round(reimsTotal * 100) / 100,
                    netSalary: Math.round(net * 100) / 100,
                    paymentMethod: teacher.payment_method_raw || 'bank_transfer',
                    transactionReference: teacher.transaction_reference || '',
                    notes: teacher.notes || '',
                    updateBaseSalary: false
                };

                this.isPayoutModalOpen = true;
                this.openActionMenuId = null;
            },

            closePayoutModal() {
                this.isPayoutModalOpen = false;
            },

            // 6. OFFICIAL PAYSLIP MODAL
            activeSlip: null,
            openSlipModal(teacher) {
                this.activeSlip = teacher;
                this.isSlipModalOpen = true;
                this.openActionMenuId = null;
            },
            closeSlipModal() {
                this.isSlipModalOpen = false;
                this.activeSlip = null;
            },
            printPayslip() {
                window.print();
            },

            get filteredTeachers() {
                const q = this.searchQuery.trim().toLowerCase();
                return this.teachers.filter(t => {
                    const matchesSearch = !q || (
                        t.name.toLowerCase().includes(q) ||
                        t.username.toLowerCase().includes(q) ||
                        t.designation.toLowerCase().includes(q)
                    );

                    let matchesStatus = true;
                    if (this.statusFilter === 'paid') {
                        matchesStatus = t.is_paid === true;
                    } else if (this.statusFilter === 'pending') {
                        matchesStatus = t.is_paid === false;
                    }

                    return matchesSearch && matchesStatus;
                });
            }
        };
    };
</script>

<div x-data="teacherPayrollManager()" x-cloak class="space-y-6">

    <!-- PAGE HEADER BANNER -->
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 no-print">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Faculty Salary &amp; Monthly Payroll
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Configure salary structures, fixed deductions, add monthly bonuses/claims, and disburse payouts.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.teachers') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Teachers Directory</span>
            </a>
            <a href="{{ route('admin.teachers.attendance') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-[11px] text-brand"></i>
                <span>Attendance Cockpit</span>
            </a>
            <a href="{{ route('admin.teachers.allocations') }}"
               class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-[11px]"></i>
                <span>Allocations</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI METRICS CARDS (FLAT, NO BORDERS, NO SHADOW, NO FONT-BOLD) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 no-print">
        <div class="rounded-lg bg-primary p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-theme-text-muted font-normal">Active Faculty</span>
                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold text-theme-text">{{ $totalTeachersCount }}</div>
            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Eligible for {{ $monthName }} salary</p>
        </div>

        <div class="rounded-lg bg-primary p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-theme-text-muted font-normal">Total Gross Base</span>
                <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold text-theme-text">₹{{ number_format($totalGross, 2) }}</div>
            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">Total contract base stipends</p>
        </div>

        <div class="rounded-lg bg-primary p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-theme-text-muted font-normal">Disbursed (Paid)</span>
                <div class="w-8 h-8 rounded-full bg-success-bg text-success flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold text-success">₹{{ number_format($totalDisbursed, 2) }}</div>
            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">{{ $paidRecords->count() }} accounts settled</p>
        </div>

        <div class="rounded-lg bg-primary p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-theme-text-muted font-normal">Pending Disbursement</span>
                <div class="w-8 h-8 rounded-full bg-warning-bg text-warning flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="text-2xl font-semibold text-warning">₹{{ number_format($totalPending, 2) }}</div>
            <p class="text-[11px] text-theme-text-muted mt-1 font-normal">{{ $pendingRecords->count() }} pending payout</p>
        </div>
    </div>

    <!-- TOOLBAR: SEARCH & MONTH/YEAR (LEFT), STATUS FILTERS (RIGHT) -->
    <div class="rounded-lg bg-primary p-4 no-print flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-wrap">
            <div class="relative w-full sm:w-72 shrink-0">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs"></i>
                <input type="text" x-model="searchQuery" placeholder="Search teacher by name or role..."
                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs pl-10 pr-4 py-2.5 rounded-full focus:outline-none font-normal">
            </div>

            <form method="GET" action="{{ route('admin.teachers.payroll') }}" id="payrollFilterForm" class="flex items-center gap-1.5 shrink-0">
                <select name="month" onchange="document.getElementById('payrollFilterForm').submit()"
                        class="bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none cursor-pointer font-normal">
                    @foreach($monthNames as $num => $name)
                        <option value="{{ $num }}" {{ (int)$currentMonth === $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>

                <select name="year" onchange="document.getElementById('payrollFilterForm').submit()"
                        class="bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none cursor-pointer font-normal">
                    @for($y = 2024; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ (int)$currentYear === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="flex items-center gap-1 bg-secondary p-1 rounded-full shrink-0 self-start md:self-auto">
            <button type="button" @click="statusFilter = 'all'"
                    :class="statusFilter === 'all' ? 'bg-brand text-primary' : 'text-theme-text-muted hover:text-theme-text'"
                    class="px-4 py-1.5 rounded-full text-xs font-normal transition">
                All Faculty ({{ $totalTeachersCount }})
            </button>
            <button type="button" @click="statusFilter = 'pending'"
                    :class="statusFilter === 'pending' ? 'bg-warning-bg text-warning' : 'text-theme-text-muted hover:text-theme-text'"
                    class="px-4 py-1.5 rounded-full text-xs font-normal transition inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                <span>Pending ({{ $pendingRecords->count() }})</span>
            </button>
            <button type="button" @click="statusFilter = 'paid'"
                    :class="statusFilter === 'paid' ? 'bg-success-bg text-success' : 'text-theme-text-muted hover:text-theme-text'"
                    class="px-4 py-1.5 rounded-full text-xs font-normal transition inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                <span>Paid ({{ $paidRecords->count() }})</span>
            </button>
        </div>
    </div>

    <!-- DESKTOP PAYROLL TABLE -->
    <div class="rounded-lg bg-primary hidden md:block no-print">
        <div class="overflow-x-auto lg:overflow-visible min-h-[380px] pb-36">
            <table class="w-full text-left text-xs text-theme-text">
                <thead class="text-theme-text-muted uppercase text-[10px] tracking-wider font-semibold border-b border-subtle">
                    <tr>
                        <th class="px-5 py-4">Faculty Member</th>
                        <th class="px-5 py-4">Base Salary</th>
                        <th class="px-5 py-4">Attendance &amp; Days</th>
                        <th class="px-5 py-4">Deductions</th>
                        <th class="px-5 py-4">Bonuses &amp; Claims</th>
                        <th class="px-5 py-4">Net Payable</th>
                        <th class="px-5 py-4">Payout Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-subtle bg-primary">
                    <template x-for="teacher in filteredTeachers" :key="teacher.id">
                        <tr class="hover:bg-secondary/40 transition">
                            <!-- Teacher Profile -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold shrink-0">
                                        <span x-text="teacher.initials"></span>
                                    </div>
                                    <div>
                                        <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                                           class="font-semibold text-theme-text hover:text-brand transition block"
                                           x-text="teacher.name"
                                           title="Open Teacher 360° Profile"></a>
                                        <div class="text-[11px] text-theme-text-muted font-normal" x-text="teacher.designation"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Base Salary -->
                            <td class="px-5 py-3.5">
                                <div class="font-normal text-theme-text">₹<span x-text="Number(teacher.base_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                                <div class="text-[10px] text-theme-text-muted capitalize font-normal" x-text="teacher.salary_type.replace('_', ' ')"></div>
                            </td>

                            <!-- Attendance Breakdown & Accurate Percentage -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5 font-normal text-theme-text">
                                    <span x-text="teacher.present_days"></span> / <span x-text="teacher.working_days"></span> Working Days
                                </div>
                                <div class="text-[11px] mt-1 flex items-center gap-1.5 flex-wrap">
                                    <template x-if="teacher.working_days === 0">
                                        <span class="text-theme-text-muted text-[10px] font-normal">No working days recorded</span>
                                    </template>
                                    <template x-if="teacher.working_days > 0">
                                        <span>
                                            <template x-if="teacher.present_days === 0">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-danger-bg text-danger font-normal text-[11px]">
                                                    <i class="fa-solid fa-circle-xmark text-[9px]"></i> 0% Attendance (Absent)
                                                </span>
                                            </template>
                                            <template x-if="teacher.present_days > 0 && teacher.present_days < teacher.working_days">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-warning-bg text-warning font-normal text-[11px]">
                                                    <i class="fa-solid fa-chart-pie text-[9px]"></i>
                                                    <span x-text="Math.round((teacher.present_days / teacher.working_days) * 100) + '% Attendance'"></span>
                                                </span>
                                            </template>
                                            <template x-if="teacher.present_days >= teacher.working_days">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-success-bg text-success font-normal text-[11px]">
                                                    <i class="fa-solid fa-circle-check text-[9px]"></i> 100% Full Attendance
                                                </span>
                                            </template>
                                        </span>
                                    </template>
                                    <template x-if="teacher.holiday_count > 0">
                                        <span class="text-theme-text-muted text-[10px] font-normal" x-text="'• ' + teacher.holiday_count + ' holidays'"></span>
                                    </template>
                                </div>
                            </td>

                            <!-- Deductions -->
                            <td class="px-5 py-3.5">
                                <template x-if="teacher.deductions > 0">
                                    <div class="text-danger font-normal">
                                        - ₹<span x-text="Number(teacher.deductions).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </template>
                                <template x-if="teacher.deductions === 0">
                                    <div class="text-theme-text-muted font-normal">₹0.00</div>
                                </template>
                            </td>

                            <!-- Bonuses & Claims -->
                            <td class="px-5 py-3.5">
                                <template x-if="(teacher.bonuses + teacher.reimbursements) > 0">
                                    <div>
                                        <div class="text-success font-normal">
                                            + ₹<span x-text="Number(teacher.bonuses + teacher.reimbursements).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="text-[10px] text-theme-text-muted flex items-center gap-1 mt-0.5 font-normal">
                                            <span x-show="teacher.bonuses > 0" x-text="'Bonus: ₹' + Number(teacher.bonuses).toLocaleString('en-IN')"></span>
                                            <span x-show="teacher.bonuses > 0 && teacher.reimbursements > 0">•</span>
                                            <span x-show="teacher.reimbursements > 0" x-text="'Claim: ₹' + Number(teacher.reimbursements).toLocaleString('en-IN')"></span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="(teacher.bonuses + teacher.reimbursements) === 0">
                                    <div class="text-theme-text-muted font-normal">₹0.00</div>
                                </template>
                            </td>

                            <!-- Net Payable -->
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-theme-text text-xs">
                                    ₹<span x-text="Number(teacher.net_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                </div>
                            </td>

                            <!-- Status Pill -->
                            <td class="px-5 py-3.5">
                                <template x-if="teacher.is_paid">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                                        <span>Paid via <span x-text="teacher.payment_method"></span></span>
                                    </span>
                                </template>
                                <template x-if="!teacher.is_paid">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning">
                                        <i class="fa-solid fa-hourglass-half text-[9px]"></i>
                                        <span>Pending Payout</span>
                                    </span>
                                </template>
                            </td>

                            <!-- Action Column: Single Clean 3-Dots Action Menu (No clipping) -->
                            <td class="px-5 py-3.5 text-right">
                                <div x-data="{ dropUp: false }" class="relative inline-flex items-center justify-end" @click.away="if(openActionMenuId === teacher.id) openActionMenuId = null">
                                    <button type="button"
                                            @click="
                                                openActionMenuId = (openActionMenuId === teacher.id ? null : teacher.id);
                                                if (openActionMenuId === teacher.id) {
                                                    $nextTick(() => {
                                                        const rect = $el.getBoundingClientRect();
                                                        dropUp = (window.innerHeight - rect.bottom) < 270;
                                                    });
                                                }
                                            "
                                            class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center transition cursor-pointer"
                                            title="Payroll Actions">
                                        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                    </button>

                                    <!-- Dropdown Menu Body (Shadow Exception: shadow-lg, rounded-lg) -->
                                    <div x-show="openActionMenuId === teacher.id"
                                         x-transition
                                         style="display: none;"
                                         :class="dropUp ? 'bottom-full mb-1.5 origin-bottom-right' : 'top-full mt-1.5 origin-top-right'"
                                         class="absolute right-0 w-60 rounded-lg bg-primary shadow-lg p-2 z-50 text-left space-y-1">
                                        
                                        <!-- Primary Action: Disburse or View Slip -->
                                        <template x-if="!teacher.is_paid">
                                            <button type="button" @click="openPayoutModal(teacher); openActionMenuId = null"
                                                    class="w-full px-3.5 py-2.5 rounded-full bg-brand text-primary font-normal text-xs transition hover:opacity-90 flex items-center gap-2.5 text-left cursor-pointer">
                                                <i class="fa-solid fa-paper-plane text-[11px] w-4"></i>
                                                <span>Disburse Salary Payout</span>
                                            </button>
                                        </template>
                                        <template x-if="teacher.is_paid">
                                            <button type="button" @click="openSlipModal(teacher); openActionMenuId = null"
                                                    class="w-full px-3.5 py-2.5 rounded-full bg-secondary text-theme-text font-normal text-xs hover:bg-subtle transition flex items-center gap-2.5 text-left cursor-pointer">
                                                <i class="fa-solid fa-file-invoice text-brand text-[11px] w-4"></i>
                                                <span>View Official Payslip</span>
                                            </button>
                                        </template>

                                        <div class="border-b border-subtle/50 my-1"></div>

                                        <div class="px-3 py-1 text-[10px] uppercase text-theme-text-muted font-normal tracking-wider">
                                            Salary Settings &amp; Popups
                                        </div>

                                        <button type="button" @click="openSalaryModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-wallet text-brand text-[11px] w-4"></i>
                                            <span>Salary Structure &amp; Fixed</span>
                                        </button>

                                        <button type="button" @click="openBonusModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-award text-success text-[11px] w-4"></i>
                                            <span>Add / Edit Bonuses</span>
                                        </button>

                                        <button type="button" @click="openDeductionModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-shield-halved text-danger text-[11px] w-4"></i>
                                            <span>Add / Edit Deductions</span>
                                        </button>

                                        <button type="button" @click="openReimbursementModal(teacher); openActionMenuId = null"
                                                class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-receipt text-info text-[11px] w-4"></i>
                                            <span>Expense Claims</span>
                                        </button>

                                        <div class="border-b border-subtle/50 my-1"></div>

                                        <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                                           class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                            <i class="fa-solid fa-id-card-clip text-brand text-[11px] w-4"></i>
                                            <span>View 360° Profile</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <template x-if="filteredTeachers.length === 0">
            <div class="p-12 text-center text-theme-text-muted text-xs font-normal">
                <i class="fa-solid fa-wallet text-2xl mb-2 opacity-50 block"></i>
                No faculty payroll records matching your current filter.
            </div>
        </template>
    </div>

    <!-- MOBILE CARDS (FLAT, NO BORDERS, NO SHADOW, ACCURATE BADGES) -->
    <div class="block md:hidden space-y-3 no-print">
        <template x-for="teacher in filteredTeachers" :key="teacher.id">
            <div class="rounded-lg bg-primary p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold shrink-0">
                            <span x-text="teacher.initials"></span>
                        </div>
                        <div>
                            <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                               class="text-xs font-semibold text-theme-text hover:text-brand transition block"
                               x-text="teacher.name"
                               title="Open Teacher 360° Profile"></a>
                            <div class="text-[11px] text-theme-text-muted font-normal" x-text="teacher.designation"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <template x-if="teacher.is_paid">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
                                Paid
                            </span>
                        </template>
                        <template x-if="!teacher.is_paid">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-warning-bg text-warning">
                                Pending
                            </span>
                        </template>

                        <!-- Mobile Three-Dots Action Menu -->
                        <div x-data="{ dropUp: false }" class="relative" @click.away="if(openActionMenuId === 'mobile_' + teacher.id) openActionMenuId = null">
                            <button type="button"
                                    @click="
                                        openActionMenuId = (openActionMenuId === 'mobile_' + teacher.id ? null : 'mobile_' + teacher.id);
                                        if (openActionMenuId === 'mobile_' + teacher.id) {
                                            $nextTick(() => {
                                                const rect = $el.getBoundingClientRect();
                                                dropUp = (window.innerHeight - rect.bottom) < 260;
                                            });
                                        }
                                    "
                                    class="w-7 h-7 rounded-full bg-secondary text-theme-text hover:bg-subtle transition flex items-center justify-center text-xs cursor-pointer"
                                    title="More Options">
                                <i class="fa-solid fa-ellipsis-vertical text-[10px]"></i>
                            </button>

                            <div x-show="openActionMenuId === 'mobile_' + teacher.id"
                                 x-transition
                                 style="display: none;"
                                 :class="dropUp ? 'bottom-full mb-1.5 origin-bottom-right' : 'top-full mt-1.5 origin-top-right'"
                                 class="absolute right-0 w-56 rounded-lg bg-primary shadow-lg p-2 z-50 text-left space-y-1">
                                
                                <template x-if="!teacher.is_paid">
                                    <button type="button" @click="openPayoutModal(teacher); openActionMenuId = null"
                                            class="w-full px-3.5 py-2.5 rounded-full bg-brand text-primary font-normal text-xs transition hover:opacity-90 flex items-center gap-2.5 text-left cursor-pointer">
                                        <i class="fa-solid fa-paper-plane text-[11px] w-4"></i>
                                        <span>Disburse Salary Payout</span>
                                    </button>
                                </template>
                                <template x-if="teacher.is_paid">
                                    <button type="button" @click="openSlipModal(teacher); openActionMenuId = null"
                                            class="w-full px-3.5 py-2.5 rounded-full bg-secondary text-theme-text font-normal text-xs hover:bg-subtle transition flex items-center gap-2.5 text-left cursor-pointer">
                                        <i class="fa-solid fa-file-invoice text-brand text-[11px] w-4"></i>
                                        <span>View Official Payslip</span>
                                    </button>
                                </template>

                                <div class="border-b border-subtle/50 my-1"></div>

                                <div class="px-3 py-1 text-[10px] uppercase text-theme-text-muted font-normal tracking-wider">
                                    Salary Settings &amp; Popups
                                </div>

                                <button type="button" @click="openSalaryModal(teacher); openActionMenuId = null"
                                        class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-wallet text-brand text-[11px] w-4"></i>
                                    <span>Salary Structure &amp; Fixed</span>
                                </button>

                                <button type="button" @click="openBonusModal(teacher); openActionMenuId = null"
                                        class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-award text-success text-[11px] w-4"></i>
                                    <span>Add / Edit Bonuses</span>
                                </button>

                                <button type="button" @click="openDeductionModal(teacher); openActionMenuId = null"
                                        class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-shield-halved text-danger text-[11px] w-4"></i>
                                    <span>Add / Edit Deductions</span>
                                </button>

                                <button type="button" @click="openReimbursementModal(teacher); openActionMenuId = null"
                                        class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-receipt text-info text-[11px] w-4"></i>
                                    <span>Expense Claims</span>
                                </button>

                                <div class="border-b border-subtle/50 my-1"></div>

                                <a :href="'{{ url('admin/teachers/profile') }}/' + teacher.username"
                                   class="w-full px-3 py-2 rounded-full text-xs font-normal text-theme-text hover:bg-secondary flex items-center gap-2.5 transition text-left cursor-pointer">
                                    <i class="fa-solid fa-id-card-clip text-brand text-[11px] w-4"></i>
                                    <span>View 360° Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 bg-secondary p-3 rounded-lg text-xs font-normal">
                    <div>
                        <span class="text-[10px] text-theme-text-muted block">Base Stipend</span>
                        <span class="font-normal text-theme-text">₹<span x-text="Number(teacher.base_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-theme-text-muted block">Attendance</span>
                        <span class="font-normal text-theme-text"><span x-text="teacher.present_days"></span> / <span x-text="teacher.working_days"></span> Days</span>
                        <div class="text-[10px] text-theme-text-muted mt-0.5">
                            <span x-text="teacher.working_days > 0 ? (Math.round((teacher.present_days / teacher.working_days) * 100) + '%') : '0%'"></span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] text-theme-text-muted block">Deductions</span>
                        <span class="text-danger font-normal">- ₹<span x-text="Number(teacher.deductions).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-theme-text-muted block">Bonuses &amp; Claims</span>
                        <span class="text-success font-normal">+ ₹<span x-text="Number(teacher.bonuses + teacher.reimbursements).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                    </div>
                    <div class="col-span-2 pt-2 border-t border-subtle/50 flex items-center justify-between">
                        <span class="text-[11px] text-theme-text font-normal">Net Disbursed Payout</span>
                        <span class="font-semibold text-brand text-xs">₹<span x-text="Number(teacher.net_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: SALARY STRUCTURE & FIXED DEDUCTIONS / BONUSES (SEPARATE TABLE)   -->
    <!-- ========================================================================= -->
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
                            Base stipend, recurring fixed deductions &amp; allowances
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
                            <div class="text-[11px] text-theme-text-muted py-3 text-center bg-primary rounded-lg font-normal">
                                No recurring deductions added. Click presets above to add monthly PF, ESI, etc.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FIXED MONTHLY RECURRING BONUSES / ALLOWANCES -->
                <div class="bg-secondary p-4 rounded-lg space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-award text-success text-sm"></i>
                            <div>
                                <span class="text-xs font-semibold text-theme-text">Recurring Monthly Allowances</span>
                                <p class="text-[10px] text-theme-text-muted font-normal">Auto-added every month (HRA, Conveyance, etc.)</p>
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
                        <button type="button" @click="addFixedBonus('Conveyance & Fuel Allowance', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> Conveyance
                        </button>
                        <button type="button" @click="addFixedBonus('Dearness Allowance (DA)', '')"
                                class="px-3 py-1 bg-primary text-theme-text text-xs rounded-full hover:bg-subtle transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px] text-success"></i> DA Allowance
                        </button>
                        <button type="button" @click="addFixedBonus('', '')"
                                class="px-3 py-1 bg-brand/10 text-brand text-xs rounded-full hover:bg-brand/20 transition inline-flex items-center gap-1.5 font-normal cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Custom Fixed Allowance
                        </button>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-2">
                        <template x-for="(item, index) in salaryForm.fixedBonusesList" :key="item.id || index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.name" placeholder="Allowance title (e.g. HRA, Medical, Conveyance)"
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
                            <div class="text-[11px] text-theme-text-muted py-3 text-center bg-primary rounded-lg font-normal">
                                No recurring allowances configured. Click presets above to add HRA, fuel, etc.
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
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Salary Structure</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: MONTHLY BONUSES & INCENTIVES (FOCUSED POPUP FOR THIS MONTH)      -->
    <!-- ========================================================================= -->
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
                            For <span x-text="monthName"></span> <span x-text="year"></span> • Award performance, festival, or joining bonus
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
                <input type="hidden" name="user_id" :value="bonusForm.userId">
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
                            <div class="text-xs font-semibold text-theme-text" x-text="bonusForm.teacherName"></div>
                            <div class="text-[11px] text-theme-text-muted font-normal" x-text="bonusForm.designation"></div>
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
                            <span class="text-xs font-semibold text-theme-text">Recurring Fixed Allowances</span>
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
                                No recurring fixed allowances configured for this faculty.
                            </div>
                        </template>
                    </div>

                    <!-- Instruction Note & Action to Edit in Salary Structure -->
                    <div class="p-3 bg-primary rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 mt-2">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-brand text-xs mt-0.5 shrink-0"></i>
                            <p class="text-[11px] text-theme-text-muted leading-relaxed font-normal">
                                Fixed allowances are added automatically every month. To add, edit, or remove recurring allowances, update them in <span class="text-theme-text font-semibold">Salary Structure</span>.
                            </p>
                        </div>
                        <button type="button"
                                @click="closeBonusModal(); openSalaryModal(bonusForm.teacherRaw)"
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
                                <span>Monthly Variable Bonuses (<span x-text="monthName"></span> <span x-text="year"></span>)</span>
                            </div>
                            <p class="text-[10px] text-theme-text-muted font-normal mt-0.5">
                                Temporary one-off incentives for this month only (performance, festival, spot awards). Resets next month.
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
                        <span>Save Bonuses for {{ $monthName }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: MONTHLY DEDUCTIONS (FOCUSED POPUP FOR THIS MONTH)                -->
    <!-- ========================================================================= -->
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
                            For <span x-text="monthName"></span> <span x-text="year"></span> • Manage leaves, penalties, or advance deductions
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
                <input type="hidden" name="user_id" :value="deductionForm.userId">
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
                            <div class="text-xs font-semibold text-theme-text" x-text="deductionForm.teacherName"></div>
                            <div class="text-[11px] text-theme-text-muted font-normal" x-text="deductionForm.designation"></div>
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
                                @click="closeDeductionModal(); openSalaryModal(deductionForm.teacherRaw)"
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
                                <span>Monthly Variable Deductions (<span x-text="monthName"></span> <span x-text="year"></span>)</span>
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
                        <span>Save Deductions for {{ $monthName }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: EXPENSE REIMBURSEMENTS (FOCUSED POPUP FOR THIS MONTH)             -->
    <!-- ========================================================================= -->
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
                <input type="hidden" name="user_id" :value="reimbursementForm.userId">
                <input type="hidden" name="month" :value="reimbursementForm.month">
                <input type="hidden" name="year" :value="reimbursementForm.year">
                <input type="hidden" name="type" value="reimbursement">
                <input type="hidden" name="items_json" :value="JSON.stringify(reimbursementForm.list)">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div class="text-xs font-semibold text-theme-text" x-text="reimbursementForm.teacherName"></div>
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
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Save Reimbursements</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 5: FINAL SALARY DISBURSEMENT (CLEAN OVERVIEW & MARK PAID)            -->
    <!-- ========================================================================= -->
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
                <input type="hidden" name="user_id" :value="payoutForm.userId">
                <input type="hidden" name="month" :value="payoutForm.month">
                <input type="hidden" name="year" :value="payoutForm.year">
                <input type="hidden" name="base_salary" :value="payoutForm.baseSalary">
                <input type="hidden" name="deductions" :value="payoutForm.deductionsTotal">
                <input type="hidden" name="bonuses" :value="payoutForm.bonusesTotal">
                <input type="hidden" name="reimbursements" :value="payoutForm.reimbursementsTotal">
                <input type="hidden" name="net_salary" :value="payoutForm.netSalary">
                <input type="hidden" name="breakdown_json" :value="JSON.stringify({ deductions: payoutForm.deductionsList, bonuses: payoutForm.bonusesList, reimbursements: payoutForm.reimbursementsList })">

                <!-- Faculty Banner -->
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-semibold">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-theme-text" x-text="payoutForm.teacherName"></div>
                            <div class="text-[11px] text-theme-text-muted font-normal" x-text="payoutForm.designation"></div>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-primary text-theme-text rounded-full text-xs font-normal">
                        {{ $monthName }} {{ $currentYear }}
                    </span>
                </div>

                <!-- 1. EARNINGS & COMPENSATION OVERVIEW (Kahan se kitna mil raha hai) -->
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

                        <!-- Individual Monthly Bonuses -->
                        <template x-for="b in payoutForm.bonusesList" :key="'bonus_' + (b.id || b.name)">
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
                        <template x-for="r in payoutForm.reimbursementsList" :key="'claim_' + (r.id || r.name)">
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

                <!-- 2. DEDUCTIONS OVERVIEW (Kitna kahan kat raha hai) -->
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
                        <!-- Individual Fixed Deductions (From Salary Structure: PF, ESI, etc.) -->
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

                        <!-- Individual Temporary Deductions (Fines, Leaves, Variable) -->
                        <template x-for="td in payoutForm.temporaryDeductions" :key="'temp_' + (td.id || td.name)">
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-subtle/30 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span>
                                    <span class="text-theme-text font-normal" x-text="td.name"></span>
                                    <span class="text-[10px] text-warning bg-warning-bg px-2 py-0.5 rounded-full font-normal">Temporary</span>
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
                            <input type="text" name="transaction_reference" x-model="payoutForm.transactionReference" placeholder="e.g. UTR-987410294 or Cheque #"
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
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span>Confirm &amp; Mark as Paid (₹<span x-text="Number(payoutForm.netSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 6: OFFICIAL FACULTY SALARY SLIP RECEIPT                              -->
    <!-- ========================================================================= -->
    <div x-show="isSlipModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="closeSlipModal()">

        <div class="bg-primary rounded-lg shadow-2xl w-full max-w-xl overflow-hidden flex flex-col max-h-[95vh]"
             @click.away="closeSlipModal()">

            <!-- Header (No Print) -->
            <div class="p-5 flex items-center justify-between border-b border-subtle/50 no-print">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-file-invoice text-brand text-sm"></i>
                    <h2 class="text-sm font-semibold text-theme-text">Faculty Salary Slip Receipt</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="printPayslip()"
                            class="px-4 py-2 bg-brand text-primary rounded-full text-xs font-normal hover:opacity-90 inline-flex items-center gap-1.5 transition cursor-pointer">
                        <i class="fa-solid fa-print text-[10px]"></i>
                        <span>Print</span>
                    </button>
                    <button type="button" @click="closeSlipModal()"
                            class="w-8 h-8 rounded-full bg-secondary hover:bg-subtle text-theme-text flex items-center justify-center text-xs transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto space-y-5 payslip-print-container" x-show="activeSlip">
                <!-- Academy Brand Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-subtle/50 gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-theme-text">{{ app('currentTenant')->name }}</h3>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Faculty Salary Disbursement Slip</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="px-3 py-1 bg-success-bg text-success rounded-full text-xs font-normal">
                            Status: Paid
                        </span>
                        <div class="text-[10px] text-theme-text-muted mt-1 font-normal" x-text="activeSlip?.paid_at"></div>
                    </div>
                </div>

                <!-- Details -->
                <div class="grid grid-cols-2 gap-4 bg-secondary p-4 rounded-lg text-xs font-normal">
                    <div>
                        <span class="text-[10px] text-theme-text-muted block font-normal">Faculty Name:</span>
                        <span class="font-semibold text-theme-text" x-text="activeSlip?.name"></span>
                        <span class="text-[11px] text-theme-text-muted block mt-0.5 font-normal" x-text="activeSlip?.designation"></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-theme-text-muted block font-normal">Payroll Period:</span>
                        <span class="font-semibold text-theme-text" x-text="monthName + ' ' + year"></span>
                        <span class="text-[11px] text-theme-text-muted block mt-0.5 font-normal" x-text="'Attendance: ' + (activeSlip?.present_days || 0) + ' / ' + (activeSlip?.working_days || workingDaysCount) + ' Days'"></span>
                    </div>
                </div>

                <!-- Table -->
                <div class="rounded-lg bg-secondary overflow-hidden text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-primary text-theme-text-muted text-[10px] uppercase font-semibold border-b border-subtle/50">
                            <tr>
                                <th class="px-4 py-2.5">Salary Component / Description</th>
                                <th class="px-4 py-2.5 text-right">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-subtle/40 text-theme-text">
                            <tr>
                                <td class="px-4 py-2.5 font-normal">Base Salary</td>
                                <td class="px-4 py-2.5 text-right font-normal">₹<span x-text="Number(activeSlip?.base_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                            </tr>

                            <!-- Bonuses -->
                            <template x-if="activeSlip?.breakdown?.bonuses && activeSlip.breakdown.bonuses.length > 0">
                                <template x-for="bon in activeSlip.breakdown.bonuses" :key="bon.id || bon.name">
                                    <tr class="text-success">
                                        <td class="px-4 py-2 font-normal">
                                            <span x-text="bon.name || 'Performance Bonus'"></span>
                                            <span class="text-[10px] opacity-75">(Bonus)</span>
                                        </td>
                                        <td class="px-4 py-2 text-right font-normal">+ ₹<span x-text="Number(bon.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="(!activeSlip?.breakdown?.bonuses || activeSlip.breakdown.bonuses.length === 0) && (activeSlip?.bonuses || 0) > 0">
                                <tr class="text-success">
                                    <td class="px-4 py-2 font-normal">Bonus / Incentives</td>
                                    <td class="px-4 py-2 text-right font-normal">+ ₹<span x-text="Number(activeSlip?.bonuses || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                </tr>
                            </template>

                            <!-- Reimbursements -->
                            <template x-if="activeSlip?.breakdown?.reimbursements && activeSlip.breakdown.reimbursements.length > 0">
                                <template x-for="reim in activeSlip.breakdown.reimbursements" :key="reim.id || reim.name">
                                    <tr class="text-info">
                                        <td class="px-4 py-2 font-normal">
                                            <span x-text="reim.name || 'Expense Reimbursement'"></span>
                                            <span class="text-[10px] opacity-75">(Personal Reimbursement)</span>
                                        </td>
                                        <td class="px-4 py-2 text-right font-normal">+ ₹<span x-text="Number(reim.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="(!activeSlip?.breakdown?.reimbursements || activeSlip.breakdown.reimbursements.length === 0) && (activeSlip?.reimbursements || 0) > 0">
                                <tr class="text-info">
                                    <td class="px-4 py-2 font-normal">Expense Reimbursement</td>
                                    <td class="px-4 py-2 text-right font-normal">+ ₹<span x-text="Number(activeSlip?.reimbursements || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                </tr>
                            </template>

                            <!-- Deductions -->
                            <template x-if="activeSlip?.breakdown?.deductions && activeSlip.breakdown.deductions.length > 0">
                                <template x-for="ded in activeSlip.breakdown.deductions" :key="ded.id || ded.name">
                                    <tr class="text-danger">
                                        <td class="px-4 py-2 font-normal">
                                            <span x-text="ded.name || 'Deduction'"></span>
                                            <span class="text-[10px] opacity-75">(Deduction)</span>
                                        </td>
                                        <td class="px-4 py-2 text-right font-normal">- ₹<span x-text="Number(ded.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="(!activeSlip?.breakdown?.deductions || activeSlip.breakdown.deductions.length === 0) && (activeSlip?.deductions || 0) > 0">
                                <tr class="text-danger">
                                    <td class="px-4 py-2 font-normal">Deductions (Leaves/PF)</td>
                                    <td class="px-4 py-2 text-right font-normal">- ₹<span x-text="Number(activeSlip?.deductions || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                                </tr>
                            </template>

                            <!-- Net Total -->
                            <tr class="bg-primary font-semibold text-sm">
                                <td class="px-4 py-3">Net Disbursed Amount</td>
                                <td class="px-4 py-3 text-right text-brand">₹<span x-text="Number(activeSlip?.net_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Meta -->
                <div class="grid grid-cols-2 gap-3 text-xs text-theme-text-muted font-normal">
                    <div>
                        <span>Payment Mode:</span>
                        <span class="font-semibold text-theme-text ml-1" x-text="activeSlip?.payment_method"></span>
                    </div>
                    <div>
                        <span>Reference / UTR:</span>
                        <span class="font-semibold text-theme-text ml-1" x-text="activeSlip?.transaction_reference || 'N/A'"></span>
                    </div>
                </div>

                <template x-if="activeSlip?.notes">
                    <div class="text-xs bg-secondary p-3.5 rounded-lg text-theme-text font-normal">
                        <span class="text-theme-text-muted">Remarks:</span>
                        <span x-text="activeSlip?.notes"></span>
                    </div>
                </template>

                <!-- Footer Signatures -->
                <div class="pt-8 grid grid-cols-2 gap-4 text-center text-xs text-theme-text-muted border-t border-subtle/50 mt-4 font-normal">
                    <div>
                        <div class="h-10"></div>
                        <div class="border-t border-theme-text-muted/30 pt-1">Authorized Administrator</div>
                    </div>
                    <div>
                        <div class="h-10"></div>
                        <div class="border-t border-theme-text-muted/30 pt-1">Faculty Member Signature</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
