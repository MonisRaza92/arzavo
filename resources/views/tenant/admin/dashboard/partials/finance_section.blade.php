{{-- SECTION 1: FINANCIAL & COMMERCIAL SALES --}}
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex bg-primary p-4 rounded-lg items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-wallet text-brand text-sm"></i>
                Financial & Sales Overview
            </h2>
            <p class="text-[11px] text-theme-text-muted mt-0.5">
                Real fee collections, pending fee balances, book sales, and course transactions
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.finance.fees') }}"
               class="px-3.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-xs text-theme-text font-normal transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-money-bill-wave text-[10px] text-brand"></i>
                <span>Fees</span>
            </a>
            <a href="{{ route('admin.finance.orders') }}"
               class="px-3.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-xs text-theme-text font-normal transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-cart-shopping text-[10px] text-brand"></i>
                <span>Orders</span>
            </a>
        </div>
    </div>

    {{-- Financial Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- 1. Total Revenue --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Total Revenue</span>
                    <div class="w-8 h-8 rounded-full bg-success-bg text-success flex items-center justify-center text-xs">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    ₹{{ number_format($totalRevenue ?? 0, 2) }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Combined fee payments and store sales
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="text-theme-text-muted text-[11px]">Fees:</span>
                    <span class="font-medium">₹{{ number_format($paidFeeSum ?? 0, 2) }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand"></span>
                    <span class="text-theme-text-muted text-[11px]">Store:</span>
                    <span class="font-medium">₹{{ number_format($paidOrderSum ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- 2. Fee Collection (Paid Fees) --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Fee Collections</span>
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    ₹{{ number_format($paidFeeSum ?? 0, 2) }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Total paid student fees received
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="font-medium text-success">Verified Payments</span>
                </div>
                <a href="{{ route('admin.finance.fees') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Fee Log <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 3. Pending Fees --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Pending Fees</span>
                    <div class="w-8 h-8 rounded-full bg-warning-bg text-warning flex items-center justify-center text-xs">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    ₹{{ number_format($pendingFeeSum ?? 0, 2) }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Outstanding student fee dues & arrears
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-warning"></span>
                    <span class="font-medium text-warning">Dues Pending</span>
                </div>
                <a href="{{ route('admin.finance.fees') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Collect Dues <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 4. Book Sales --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Books Sales</span>
                    <div class="w-8 h-8 rounded-full bg-info-bg text-info flex items-center justify-center text-xs">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    ₹{{ number_format($bookSalesSum ?? 0, 2) }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Revenue from library publications & notes
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand"></span>
                    <span class="font-medium">{{ $bookCopiesSold ?? 0 }} Copies Sold</span>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Library <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 5. Course Sales --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Courses Sales</span>
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    ₹{{ number_format($courseSalesSum ?? 0, 2) }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Revenue from purchased premium courses
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="font-medium">{{ $courseSeatsSold ?? 0 }} Seats Sold</span>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Courses <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 6. Store Orders --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Store Orders</span>
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalOrders ?? 0 }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    All digital & merchandise order purchases
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-2">
                    <span class="font-medium text-success">{{ $paidOrders ?? 0 }} Paid</span>
                    <span class="text-theme-text-muted/60">·</span>
                    <span class="text-theme-text-muted">{{ $unpaidOrders ?? 0 }} Unpaid</span>
                </div>
                <a href="{{ route('admin.finance.orders') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Orders <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

    </div>
</div>
