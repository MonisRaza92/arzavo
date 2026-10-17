{{-- ======================================================== --}}
{{-- FEE REPORTS, METRICS, CHARTS & STREAM RECOVERY ANALYSIS   --}}
{{-- ======================================================== --}}
<div class="space-y-6">

    {{-- 1. STUDENT PAYMENT COMPLIANCE TIERS (PERCENTAGES & COUNTS) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Paid in Full --}}
        <div @click="setActiveFilter(activeFilter === 'paid' ? 'all' : 'paid')"
             class="rounded-lg bg-primary p-5 cursor-pointer transition hover:opacity-95 relative overflow-hidden"
             :class="activeFilter === 'paid' ? 'ring-2 ring-success' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Fee Paid in Full</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-semibold text-success">{{ $analytics['paid_percent'] }}%</span>
                        <span class="text-xs text-theme-text-muted font-normal">({{ $analytics['paid_count'] }}/{{ $analytics['total_students'] }})</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-success-bg text-success flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-secondary rounded-full h-1.5 overflow-hidden">
                    <div class="bg-success h-full rounded-full transition-all duration-500" style="width: {{ $analytics['paid_percent'] }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-theme-text-muted mt-1.5 font-normal">
                    <span>Zero balance</span>
                    <span class="text-success font-medium">100% Cleared</span>
                </div>
            </div>
        </div>

        {{-- Partial Paid --}}
        <div @click="setActiveFilter(activeFilter === 'partial' ? 'all' : 'partial')"
             class="rounded-lg bg-primary p-5 cursor-pointer transition hover:opacity-95 relative overflow-hidden"
             :class="activeFilter === 'partial' ? 'ring-2 ring-warning' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Partial Paid</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-semibold text-warning">{{ $analytics['partial_percent'] }}%</span>
                        <span class="text-xs text-theme-text-muted font-normal">({{ $analytics['partial_count'] }}/{{ $analytics['total_students'] }})</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-warning-bg text-warning flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-secondary rounded-full h-1.5 overflow-hidden">
                    <div class="bg-warning h-full rounded-full transition-all duration-500" style="width: {{ $analytics['partial_percent'] }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-theme-text-muted mt-1.5 font-normal">
                    <span>Active installments</span>
                    <span class="text-warning font-medium">Pending balance</span>
                </div>
            </div>
        </div>

        {{-- Unpaid / Overdue Defaulters --}}
        <div @click="setActiveFilter(activeFilter === 'unpaid' ? 'all' : 'unpaid')"
             class="rounded-lg bg-primary p-5 cursor-pointer transition hover:opacity-95 relative overflow-hidden"
             :class="activeFilter === 'unpaid' ? 'ring-2 ring-danger' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Defaulters / Unpaid</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-semibold text-danger">{{ $analytics['unpaid_percent'] }}%</span>
                        <span class="text-xs text-theme-text-muted font-normal">({{ $analytics['unpaid_count'] }}/{{ $analytics['total_students'] }})</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-danger-bg text-danger flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-secondary rounded-full h-1.5 overflow-hidden">
                    <div class="bg-danger h-full rounded-full transition-all duration-500" style="width: {{ $analytics['unpaid_percent'] }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-theme-text-muted mt-1.5 font-normal">
                    <span>Pending recovery</span>
                    <span class="text-danger font-medium">₹{{ number_format($analytics['total_defaulters_due'], 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Zero Dues / Free / Scholarship --}}
        <div @click="setActiveFilter(activeFilter === 'zero_dues' ? 'all' : 'zero_dues')"
             class="rounded-lg bg-primary p-5 cursor-pointer transition hover:opacity-95 relative overflow-hidden"
             :class="activeFilter === 'zero_dues' ? 'ring-2 ring-brand' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-normal text-theme-text-muted uppercase tracking-wider">Zero Dues / Scholarship</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-semibold text-theme-text">{{ $analytics['zero_dues_percent'] }}%</span>
                        <span class="text-xs text-theme-text-muted font-normal">({{ $analytics['zero_dues_count'] }}/{{ $analytics['total_students'] }})</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-secondary text-brand flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-gift"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-secondary rounded-full h-1.5 overflow-hidden">
                    <div class="bg-brand h-full rounded-full transition-all duration-500" style="width: {{ $analytics['zero_dues_percent'] }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-theme-text-muted mt-1.5 font-normal">
                    <span>100% Concession / Free</span>
                    <span class="text-theme-text font-medium">No liability</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. INTERACTIVE CHARTS ROW (DOUGHNUT + 6-MONTH TREND BAR CHART) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Chart A: Student Compliance Doughnut (4 cols) --}}
        <div class="lg:col-span-5 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text">Student Payment Compliance</h3>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Percentage distribution across student fee tiers</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] bg-secondary text-brand font-medium">
                        {{ $collectionRatio }}% Recovered
                    </span>
                </div>
            </div>

            <div class="relative flex items-center justify-center py-2">
                <div class="w-48 h-48 sm:w-56 sm:h-56 relative flex items-center justify-center">
                    <canvas id="feeComplianceDoughnut"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                        <span class="text-2xl font-bold text-theme-text">{{ $collectionRatio }}%</span>
                        <span class="text-[10px] text-theme-text-muted uppercase tracking-wider font-normal">Recovery</span>
                    </div>
                </div>
            </div>

            {{-- Legend Breakdown Pills --}}
            <div class="grid grid-cols-2 gap-2 pt-2 border-t-0">
                <div class="flex items-center gap-2 p-2 rounded-lg bg-secondary/50 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="block truncate text-theme-text font-medium">Paid ({{ $analytics['paid_count'] }})</span>
                        <span class="text-[10px] text-theme-text-muted font-normal">{{ $analytics['paid_percent'] }}%</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-secondary/50 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="block truncate text-theme-text font-medium">Partial ({{ $analytics['partial_count'] }})</span>
                        <span class="text-[10px] text-theme-text-muted font-normal">{{ $analytics['partial_percent'] }}%</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-secondary/50 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="block truncate text-theme-text font-medium">Unpaid ({{ $analytics['unpaid_count'] }})</span>
                        <span class="text-[10px] text-theme-text-muted font-normal">{{ $analytics['unpaid_percent'] }}%</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-secondary/50 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-400 shrink-0"></span>
                    <div class="min-w-0">
                        <span class="block truncate text-theme-text font-medium">Zero Dues ({{ $analytics['zero_dues_count'] }})</span>
                        <span class="text-[10px] text-theme-text-muted font-normal">{{ $analytics['zero_dues_percent'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart B: 6-Month Accrual vs Collections Trend (7 cols) --}}
        <div class="lg:col-span-7 rounded-lg bg-primary p-5 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text">Monthly Billing Accrual vs Collections</h3>
                        <p class="text-xs text-theme-text-muted mt-0.5 font-normal">Past 6 months comparison of invoiced fees vs verified collections</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-theme-text-muted font-normal">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Invoiced
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Collected
                        </span>
                    </div>
                </div>
            </div>

            <div class="relative w-full h-64">
                <canvas id="feeMonthlyTrendBar"></canvas>
            </div>

            <div class="flex items-center justify-between text-xs text-theme-text-muted p-3 bg-secondary rounded-lg font-normal">
                <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-line text-brand"></i>
                    <span>Avg Plan Rate: <strong class="text-theme-text font-semibold">₹{{ number_format($analytics['average_fee'], 2) }}</strong>/mo</span>
                </div>
                <div>
                    <span>Total Invoiced: <strong class="text-theme-text font-semibold">₹{{ number_format($totalPlanned, 2) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. ACADEMIC STREAMS & CATEGORIES PROGRESS METERS --}}
    @if(!empty($analytics['category_stats']) && count($analytics['category_stats']) > 0)
    <div class="rounded-lg bg-primary p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-semibold text-theme-text">Academic Stream &amp; Category Recovery Breakdown</h3>
                <p class="text-xs text-theme-text-muted mt-0.5 font-normal">
                    Tuition fee recovery rates across academic divisions, categories and specialized courses
                </p>
            </div>
            <span class="text-xs text-theme-text-muted font-normal">
                {{ count($analytics['category_stats']) }} Academic Categories
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($analytics['category_stats'] as $cat)
            <div class="p-4 rounded-lg bg-secondary space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h4 class="text-xs font-semibold text-theme-text">{{ $cat['name'] }}</h4>
                        <span class="text-[11px] text-theme-text-muted font-normal">{{ $cat['students_count'] }} {{ Str::plural('Student', $cat['students_count']) }} enrolled</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                        {{ $cat['ratio'] >= 80 ? 'bg-success-bg text-success' : ($cat['ratio'] >= 40 ? 'bg-warning-bg text-warning' : 'bg-secondary text-theme-text-muted') }}">
                        {{ $cat['ratio'] }}% Recovered
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-primary rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500
                        {{ $cat['ratio'] >= 80 ? 'bg-success' : ($cat['ratio'] >= 40 ? 'bg-warning' : 'bg-brand') }}"
                         style="width: {{ $cat['ratio'] }}%">
                    </div>
                </div>

                {{-- Metrics Breakdown --}}
                <div class="grid grid-cols-3 gap-2 pt-1 text-[11px]">
                    <div>
                        <span class="text-theme-text-muted block text-[10px]">Invoiced</span>
                        <span class="font-medium text-theme-text">₹{{ number_format($cat['billed'], 0) }}</span>
                    </div>
                    <div>
                        <span class="text-theme-text-muted block text-[10px]">Collected</span>
                        <span class="font-medium text-success">₹{{ number_format($cat['paid'], 0) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-theme-text-muted block text-[10px]">Balance Due</span>
                        <span class="font-medium {{ $cat['due'] > 0 ? 'text-warning' : 'text-success' }}">₹{{ number_format($cat['due'], 0) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
