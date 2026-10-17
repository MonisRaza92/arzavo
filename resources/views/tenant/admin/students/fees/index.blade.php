@extends('layouts.admin')
@section('title', 'Student Fees & Hierarchy Master Plans')

@section('content')
<div x-data="feeManagement()" class="space-y-6">

    {{-- TOP BANNER / HEADER (CONSISTENT CURRICULUM ARCHITECTURE HEADER) --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Student Fees &amp; Master Fee Plans
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Configure hierarchical tuition plans across Categories, Classes, Subjects &amp; Batches with automatic student inheritance and custom scholarship overrides.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.finance.fees') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-receipt text-[11px] text-brand"></i>
                <span>Fee Verification Center</span>
            </a>
            <button type="button"
                    @click="openCreatePlanModal()"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Create Master Fee Plan</span>
            </button>
        </div>
    </div>

    {{-- FLOATING PILL TABS BAR (DIRECTLY BELOW HEADER) --}}
    <div class="flex items-center gap-2 p-1.5 bg-secondary rounded-full w-full sm:w-fit overflow-x-auto scrollbar">
        <button type="button"
                @click="activeTab = 'reports'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'reports' 
                    ? 'bg-brand text-primary font-semibold shadow-xs' 
                    : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
            <i class="fa-solid fa-chart-pie text-[12px]"></i>
            <span>Fee Reports &amp; Student Directory</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]"
                  :class="activeTab === 'reports' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                {{ $students->count() }}
            </span>
        </button>

        <button type="button"
                @click="activeTab = 'plans'"
                class="px-5 py-2.5 rounded-full text-xs transition cursor-pointer whitespace-nowrap inline-flex items-center gap-2"
                :class="activeTab === 'plans' 
                    ? 'bg-brand text-primary font-semibold shadow-xs' 
                    : 'bg-primary text-theme-text hover:bg-subtle font-normal'">
            <i class="fa-solid fa-sitemap text-[12px]"></i>
            <span>Master Fee Plans &amp; Hierarchy</span>
            <span class="px-2 py-0.5 rounded-full text-[10px]"
                  :class="activeTab === 'plans' ? 'bg-primary/20 text-primary font-semibold' : 'bg-secondary text-theme-text-muted font-normal'">
                {{ $feeStructures->count() }}
            </span>
        </button>
    </div>

    {{-- TAB 1: FEE REPORTS, METRICS, CHARTS & STUDENT LEDGER --}}
    <div x-show="activeTab === 'reports'" class="space-y-6">
        {{-- TOP STATS SUMMARY CARDS --}}
        @include('tenant.admin.students.fees.partials.stats')

        {{-- VISUAL REPORTS & CHARTS --}}
        @include('tenant.admin.students.fees.partials.reports_charts')

        {{-- STUDENT FEE LEDGER DIRECTORY --}}
        @include('tenant.admin.students.fees.partials.student_ledger')
    </div>

    {{-- TAB 2: MASTER PLANS & HIERARCHY ACCORDION/TABLE --}}
    <div x-show="activeTab === 'plans'" x-cloak class="space-y-6">
        @include('tenant.admin.students.fees.partials.plans_hierarchy')
    </div>

    {{-- MODAL 1: CREATE / EDIT MASTER PLAN --}}
    @include('tenant.admin.students.fees.partials.modal_create_plan')

    {{-- MODAL 2: STUDENT CUSTOM OVERRIDE / CONCESSION --}}
    @include('tenant.admin.students.fees.partials.modal_student_override')

    {{-- MODAL 3: MANUAL PAYMENT COLLECTION --}}
    @include('tenant.admin.students.fees.partials.modal_collect_payment')

</div>

@push('scripts')
<script>
    function feeManagement() {
        return {
            activeTab: 'reports',
            createPlanModalOpen: false,
            overrideModalOpen: false,
            collectModalOpen: false,
            collectPaymentAmount: '',
            collectPaymentDate: '{{ date('Y-m-d') }}',
            activeActionMenu: null,
            isMenuDropUp: false,
            editingPlanId: null,

            // Unified Filter state
            searchQuery: '',
            activeFilter: 'all',

            // Data collections
            categories: @json($categories),
            students: @json($studentsData),
            analytics: @json($analytics),

            // Chart instances
            complianceChart: null,
            trendChart: null,

            init() {
                this.$nextTick(() => {
                    this.initCharts();
                });

                this.$watch('activeTab', (tab) => {
                    if (tab === 'reports') {
                        this.$nextTick(() => {
                            this.initCharts();
                        });
                    }
                });
            },

            setActiveFilter(filter) {
                this.activeFilter = filter;
            },

            // Plan Form State
            planForm: {
                scope: 'class',
                category_id: '',
                class_id: '',
                subject_id: '',
                batch_id: '',
                title: '',
                plan_type: 'monthly',
                amount: '',
                due_day: 1,
                start_date: '{{ date('Y-m-d') }}',
            },

            // Student Override Form State
            selectedStudent: null,
            overrideForm: {
                is_custom: true,
                amount: '',
                plan_type: 'monthly',
                due_day: 1,
                reason: '',
                start_date: '{{ date('Y-m-d') }}',
            },

            // Computed Dependent Dropdowns for Modal
            get modalCategory() {
                if (!this.planForm.category_id) return null;
                return this.categories.find(c => c.id == this.planForm.category_id) || null;
            },
            get modalClasses() {
                return this.modalCategory ? (this.modalCategory.class_courses || []) : [];
            },
            get modalClass() {
                if (!this.planForm.class_id) return null;
                return this.modalClasses.find(cls => cls.id == this.planForm.class_id) || null;
            },
            get modalSubjects() {
                return this.modalClass ? (this.modalClass.subjects || []) : [];
            },
            get modalSubject() {
                if (!this.planForm.subject_id) return null;
                return this.modalSubjects.find(s => s.id == this.planForm.subject_id) || null;
            },
            get modalBatches() {
                return this.modalSubject ? (this.modalSubject.batches || []) : [];
            },

            // Modal Dropdown Change Handlers
            onScopeChange() {
                if (this.planForm.scope === 'category') {
                    this.planForm.class_id = '';
                    this.planForm.subject_id = '';
                    this.planForm.batch_id = '';
                } else if (this.planForm.scope === 'class') {
                    this.planForm.subject_id = '';
                    this.planForm.batch_id = '';
                } else if (this.planForm.scope === 'subject') {
                    this.planForm.batch_id = '';
                }
            },
            onModalCategoryChange() {
                this.planForm.class_id = '';
                this.planForm.subject_id = '';
                this.planForm.batch_id = '';
                if (this.modalClasses.length > 0 && this.planForm.scope !== 'category') {
                    this.planForm.class_id = this.modalClasses[0].id;
                    this.onModalClassChange();
                }
            },
            onModalClassChange() {
                this.planForm.subject_id = '';
                this.planForm.batch_id = '';
                if (this.modalSubjects.length > 0 && (this.planForm.scope === 'subject' || this.planForm.scope === 'batch')) {
                    this.planForm.subject_id = this.modalSubjects[0].id;
                    this.onModalSubjectChange();
                }
            },
            onModalSubjectChange() {
                this.planForm.batch_id = '';
                if (this.modalBatches.length > 0 && this.planForm.scope === 'batch') {
                    this.planForm.batch_id = this.modalBatches[0].id;
                }
            },

            // Modal Openers
            openCreatePlanModal() {
                this.editingPlanId = null;
                this.planForm = {
                    scope: 'class',
                    category_id: this.categories.length ? this.categories[0].id : '',
                    class_id: '',
                    subject_id: '',
                    batch_id: '',
                    title: '',
                    plan_type: 'monthly',
                    amount: '',
                    due_day: 1,
                    start_date: '{{ date('Y-m-d') }}',
                };
                if (this.planForm.category_id) {
                    this.onModalCategoryChange();
                }
                this.createPlanModalOpen = true;
            },
            openEditPlanModal(plan) {
                this.editingPlanId = plan.id;
                this.planForm = {
                    scope: plan.scope,
                    category_id: plan.academic_category_id || '',
                    class_id: plan.class_id || '',
                    subject_id: plan.subject_id || '',
                    batch_id: plan.batch_id || '',
                    title: plan.title || '',
                    plan_type: plan.plan_type || 'monthly',
                    amount: plan.amount || '',
                    due_day: plan.due_day || 1,
                    start_date: plan.start_date ? plan.start_date.substring(0, 10) : '',
                };
                this.createPlanModalOpen = true;
            },
            openOverrideModal(student) {
                this.selectedStudent = student;
                const eff = student.effective_plan;
                this.overrideForm = {
                    is_custom: eff.is_override,
                    amount: eff.is_override ? eff.amount : '',
                    plan_type: eff.plan_type || 'monthly',
                    due_day: eff.due_day || 1,
                    reason: eff.override_reason || '',
                    start_date: '{{ date('Y-m-d') }}',
                };
                this.overrideModalOpen = true;
            },

            openCollectModal(st) {
                this.selectedStudent = st;
                this.collectPaymentAmount = (st.due_fee && st.due_fee > 0) ? st.due_fee : (st.effective_plan && st.effective_plan.amount > 0 ? st.effective_plan.amount : '');
                this.collectPaymentDate = new Date().toISOString().split('T')[0];
                this.collectModalOpen = true;
            },

            getWhatsAppReminderUrl(st) {
                const phone = (st.parent_phone || st.number || '').replace(/[^0-9]/g, '');
                const dueAmount = Number(st.due_fee || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const text = encodeURIComponent(`Dear Parent/Student, this is a reminder regarding ${st.fname}'s outstanding academy tuition fee of ₹${dueAmount}. Kindly arrange the payment at your earliest convenience. Thank you!`);
                return `https://wa.me/${phone}?text=${text}`;
            },

            toggleActionMenu(studentId, el) {
                if (this.activeActionMenu === studentId) {
                    this.activeActionMenu = null;
                    return;
                }
                this.activeActionMenu = studentId;
                if (el) {
                    const rect = el.getBoundingClientRect();
                    this.isMenuDropUp = (window.innerHeight - rect.bottom) < 220;
                }
            },

            // Filtered Student Computation
            get filteredStudents() {
                let list = this.students;

                if (this.activeFilter === 'paid') {
                    list = list.filter(s => s.payment_status === 'paid');
                } else if (this.activeFilter === 'partial') {
                    list = list.filter(s => s.payment_status === 'partial');
                } else if (this.activeFilter === 'unpaid') {
                    list = list.filter(s => s.payment_status === 'unpaid');
                } else if (this.activeFilter === 'zero_dues') {
                    list = list.filter(s => s.payment_status === 'zero_dues');
                } else if (this.activeFilter === 'override') {
                    list = list.filter(s => s.effective_plan.is_override === true);
                } else if (this.activeFilter === 'inherited') {
                    list = list.filter(s => s.effective_plan.is_override === false);
                }

                if (this.searchQuery && this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase().trim();
                    list = list.filter(s => {
                        const name = (s.fname + ' ' + (s.lname || '')).toLowerCase();
                        const username = (s.username || '').toLowerCase();
                        const roll = (s.roll_number || '').toLowerCase();
                        const email = (s.email || '').toLowerCase();
                        const cls = (s.class_name || '').toLowerCase();
                        const batch = (s.batch_name || '').toLowerCase();
                        const cat = (s.category_name || '').toLowerCase();
                        return name.includes(q) || username.includes(q) || roll.includes(q) || email.includes(q) || cls.includes(q) || batch.includes(q) || cat.includes(q);
                    });
                }

                return list;
            },

            // Chart.js initialization
            initCharts() {
                const doughnutEl = document.getElementById('feeComplianceDoughnut');
                if (doughnutEl && typeof Chart !== 'undefined') {
                    if (this.complianceChart) {
                        this.complianceChart.destroy();
                    }
                    this.complianceChart = new Chart(doughnutEl, {
                        type: 'doughnut',
                        data: {
                            labels: ['Paid in Full', 'Partial Paid', 'Defaulters/Unpaid', 'Zero Dues'],
                            datasets: [{
                                data: [
                                    this.analytics.paid_count,
                                    this.analytics.partial_count,
                                    this.analytics.unpaid_count,
                                    this.analytics.zero_dues_count
                                ],
                                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e', '#94a3b8'],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            cutout: '74%',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const value = context.raw;
                                            const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                            return ` ${context.label}: ${value} (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                const barEl = document.getElementById('feeMonthlyTrendBar');
                if (barEl && typeof Chart !== 'undefined') {
                    if (this.trendChart) {
                        this.trendChart.destroy();
                    }
                    const labels = this.analytics.monthly_trend.map(m => m.label);
                    const invoicedData = this.analytics.monthly_trend.map(m => m.invoiced);
                    const collectedData = this.analytics.monthly_trend.map(m => m.collected);

                    this.trendChart = new Chart(barEl, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Invoiced Fees',
                                    data: invoicedData,
                                    backgroundColor: '#6366f1',
                                    borderRadius: 6,
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.7
                                },
                                {
                                    label: 'Verified Collections',
                                    data: collectedData,
                                    backgroundColor: '#10b981',
                                    borderRadius: 6,
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.7
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return ` ${context.dataset.label}: ₹` + Number(context.raw).toLocaleString('en-IN', {minimumFractionDigits: 2});
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10 }, color: '#94a3b8' }
                                },
                                y: {
                                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                    ticks: {
                                        font: { size: 10 },
                                        color: '#94a3b8',
                                        callback: function(val) { return '₹' + Number(val).toLocaleString('en-IN'); }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        };
    }
</script>
@endpush
@endsection
