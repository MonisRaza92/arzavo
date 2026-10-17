{{-- SECTION 3: GROWTH TRENDS & ANALYTICS CHARTS --}}
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex bg-primary p-4 rounded-lg items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-brand text-sm"></i>
                Growth & Analytics Trends
            </h2>
            <p class="text-[11px] text-theme-text-muted mt-0.5">
                6-month historical tracking of student signups, user community expansion, and revenue collections
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-secondary text-xs text-theme-text font-normal">
                Last 6 Months
            </span>
        </div>
    </div>

    {{-- Row 1: Student & User Growth + Role Composition --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Left: Student & User Community Growth Dual-Line Chart --}}
        <div class="lg:col-span-2 rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                            <i class="fa-solid fa-users-line text-brand"></i>
                            Student & User Growth
                        </h3>
                        <p class="text-[11px] text-theme-text-muted mt-0.5">
                            Monthly registration trends of learners and overall accounts
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                            <span class="w-2 h-2 rounded-full bg-brand"></span> Students
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Total Users
                        </span>
                    </div>
                </div>

                <div class="relative w-full h-[250px]">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-4 pt-3">
                <div class="p-2.5 rounded-lg bg-secondary text-center">
                    <p class="text-[10px] text-theme-text-muted">Total Students</p>
                    <p class="text-base font-semibold text-theme-text mt-0.5">{{ $totalStudents }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-secondary text-center">
                    <p class="text-[10px] text-theme-text-muted">Active Ratio</p>
                    <p class="text-base font-semibold text-success mt-0.5">
                        {{ $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100) : 0 }}%
                    </p>
                </div>
                <div class="p-2.5 rounded-lg bg-secondary text-center">
                    <p class="text-[10px] text-theme-text-muted">Total Accounts</p>
                    <p class="text-base font-semibold text-theme-text mt-0.5">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        {{-- Right: Users Breakdown Doughnut Chart --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-brand"></i>
                        Role Composition
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text font-normal">
                        {{ $totalUsers }} Total
                    </span>
                </div>
                <p class="text-[11px] text-theme-text-muted mb-3">
                    Distribution of accounts across institute roles
                </p>

                <div class="relative w-full h-[180px] flex items-center justify-center">
                    <canvas id="roleCompositionChart"></canvas>
                </div>
            </div>

            {{-- Role Breakdown Grid --}}
            <div class="grid grid-cols-2 gap-2 mt-4 pt-3">
                <div class="p-2 rounded-lg bg-secondary flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-brand"></span>
                        <span class="text-theme-text-muted text-[11px]">Students</span>
                    </div>
                    <span class="font-medium text-theme-text">{{ $totalStudents }}</span>
                </div>
                <div class="p-2 rounded-lg bg-secondary flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-theme-text-muted text-[11px]">Teachers</span>
                    </div>
                    <span class="font-medium text-theme-text">{{ $totalTeachers }}</span>
                </div>
                <div class="p-2 rounded-lg bg-secondary flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span class="text-theme-text-muted text-[11px]">Staff</span>
                    </div>
                    <span class="font-medium text-theme-text">{{ $totalStaff }}</span>
                </div>
                <div class="p-2 rounded-lg bg-secondary flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span class="text-theme-text-muted text-[11px]">Normal Users</span>
                    </div>
                    <span class="font-medium text-theme-text">{{ $totalNormalUsers }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Row 2: Financial Inflows Bar Chart (Fee Collections vs Store Sales) --}}
    <div class="rounded-lg bg-primary p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2">
                    <i class="fa-solid fa-coins text-brand"></i>
                    Fee Collections & Store Sales Flow
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-0.5">
                    Monthly comparison of student fees collected vs digital store orders & publication sales
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Fee Collections (₹)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-[11px] text-theme-text font-normal">
                    <span class="w-2 h-2 rounded-full bg-brand"></span> Store Sales (₹)
                </span>
            </div>
        </div>

        <div class="relative w-full h-[230px]">
            <canvas id="financialFlowChart"></canvas>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-3">
            <div class="p-2.5 rounded-lg bg-secondary">
                <span class="text-[10px] text-theme-text-muted">Total Paid Fees</span>
                <p class="text-sm font-semibold text-success mt-0.5">₹{{ number_format($paidFeeSum ?? 0, 2) }}</p>
            </div>
            <div class="p-2.5 rounded-lg bg-secondary">
                <span class="text-[10px] text-theme-text-muted">Total Store Sales</span>
                <p class="text-sm font-semibold text-brand mt-0.5">₹{{ number_format($paidOrderSum ?? 0, 2) }}</p>
            </div>
            <div class="p-2.5 rounded-lg bg-secondary">
                <span class="text-[10px] text-theme-text-muted">Book Copies Sold</span>
                <p class="text-sm font-semibold text-theme-text mt-0.5">{{ $bookCopiesSold ?? 0 }} Copies</p>
            </div>
            <div class="p-2.5 rounded-lg bg-secondary">
                <span class="text-[10px] text-theme-text-muted">Course Seats Sold</span>
                <p class="text-sm font-semibold text-theme-text mt-0.5">{{ $courseSeatsSold ?? 0 }} Seats</p>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        // 1. Student & User Growth Chart (Dual Line)
        const growthCanvas = document.getElementById('userGrowthChart');
        if (growthCanvas) {
            const ctx = growthCanvas.getContext('2d');

            const brandGradient = ctx.createLinearGradient(0, 0, 0, 250);
            brandGradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
            brandGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            const userGradient = ctx.createLinearGradient(0, 0, 0, 250);
            userGradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
            userGradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartMonths) !!},
                    datasets: [
                        {
                            label: 'Students Enrolled',
                            data: {!! json_encode($chartStudentRegistrations) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: brandGradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Total Users',
                            data: {!! json_encode($chartUserGrowth) !!},
                            borderColor: '#6366f1',
                            backgroundColor: userGradient,
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#6366f1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // 2. Role Composition Doughnut Chart
        const roleCanvas = document.getElementById('roleCompositionChart');
        if (roleCanvas) {
            const ctxRole = roleCanvas.getContext('2d');
            new Chart(ctxRole, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($roleDistribution['labels'] ?? ['Students', 'Teachers', 'Staff', 'Admins', 'Normal Users']) !!},
                    datasets: [{
                        data: {!! json_encode($roleDistribution['data'] ?? [0, 0, 0, 0, 0]) !!},
                        backgroundColor: [
                            '#3b82f6', // Students (brand blue)
                            '#f59e0b', // Teachers (amber)
                            '#0ea5e9', // Staff (sky)
                            '#10b981', // Admins (emerald)
                            '#6366f1'  // Normal Users (indigo)
                        ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // 3. Fee Collections vs Store Sales Bar Chart
        const flowCanvas = document.getElementById('financialFlowChart');
        if (flowCanvas) {
            const ctxFlow = flowCanvas.getContext('2d');
            new Chart(ctxFlow, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartMonths) !!},
                    datasets: [
                        {
                            label: 'Fee Collections (₹)',
                            data: {!! json_encode($chartFeeCollections) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        },
                        {
                            label: 'Store Sales (₹)',
                            data: {!! json_encode($chartStoreSales) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₹' + Number(context.parsed.y).toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                callback: function(value) {
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
