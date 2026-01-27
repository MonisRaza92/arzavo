{{-- HEADER SECTION --}}
<div class="p-4 border-primary bg-primary border-rounded mb-4">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-lg font-bold mb-1"><i class="fa-solid fa-chart-line mr-1"></i> Course Analytics Dashboard</h1>
            <p class="text-sm text-tertiary">Comprehensive overview of your course performance</p>
        </div>
        <div class="flex gap-3">
            <select class="px-4 py-2 border-rounded border-primary text-sm">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
                <option>All Time</option>
            </select>
        </div>
    </div>
</div>

{{-- MAIN STATS GRID --}}
<div class="data grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

    {{-- Total Courses Card --}}
    <div class="stat-card group relative overflow-hidden p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-tertiary mb-2">Total Courses</p>
                <h3 class="text-4xl font-bold mb-1 animate-count">{{ $courses->count() }}</h3>
                <p class="text-xs text-tertiary">All created courses</p>
            </div>
            <div class="text-3xl bg-invert text-invert border-rounded p-3">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="flex items-center text-xs text-green-500">
            <i class="fa-solid fa-arrow-up mr-1"></i>
            <span>12% last month</span>
        </div>
    </div>

    {{-- Active Courses Card --}}
    <div class="stat-card group relative overflow-hidden p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-tertiary mb-2">Active Courses</p>
                <h3 class="text-4xl font-bold mb-1 animate-count">{{ $courses->where('status', 'published')->count() }}</h3>
                <p class="text-xs text-tertiary">Visible to students</p>
            </div>
            <div class="text-3xl bg-invert text-invert border-rounded p-3">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="flex items-center text-xs text-green-500">
            <i class="fa-solid fa-arrow-up mr-1"></i>
            <span>8% last month</span>
        </div>
    </div>

    {{-- Draft Courses Card --}}
    <div class="stat-card group relative overflow-hidden p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-tertiary mb-2">Draft Courses</p>
                <h3 class="text-4xl font-bold mb-1 animate-count">{{ $courses->whereNotIn('status', ['published'])->count() }}</h3>
                <p class="text-xs text-tertiary">Draft / archived</p>
            </div>
            <div class="text-3xl bg-invert text-invert border-rounded p-3">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
        </div>
        <div class="flex items-center text-xs text-orange-500">
            <i class="fa-solid fa-minus mr-1"></i>
            <span>No change</span>
        </div>
    </div>

    {{-- Sold Courses Card --}}
    <div class="stat-card group relative overflow-hidden p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-tertiary mb-2">Sold Courses</p>
                <h3 class="text-4xl font-bold mb-1 animate-count">{{ $courses->where('status', 'sold')->count() }}</h3>
                <p class="text-xs text-tertiary">With purchases</p>
            </div>
            <div class="text-3xl bg-invert text-invert border-rounded p-3">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
        <div class="flex items-center text-xs text-green-500">
            <i class="fa-solid fa-arrow-up mr-1"></i>
            <span>24% last month</span>
        </div>
    </div>

</div>

{{-- REVENUE AND ENROLLMENT SECTION --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    {{-- Revenue Card with Chart --}}
    <div class="p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold mb-2">Total Revenue</h2>
                <p class="font-bold text-5xl">₹0</p>
                <p class="text-xs text-tertiary mt-2">From all paid enrollments</p>
            </div>
            <div class="text-4xl bg-invert text-invert border-rounded p-4">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        {{-- Mini Revenue Chart --}}
        <div class="mt-6 h-38 flex items-end gap-2">
            <div class="flex-1 bg-black/20 border-rounded animate-grow" style="height: 45%;"></div>
            <div class="flex-1 bg-black/30 border-rounded animate-grow" style="height: 60%;"></div>
            <div class="flex-1 bg-black/40 border-rounded animate-grow" style="height: 35%;"></div>
            <div class="flex-1 bg-black/50 border-rounded animate-grow" style="height: 75%;"></div>
            <div class="flex-1 bg-black/60 border-rounded animate-grow" style="height: 50%;"></div>
            <div class="flex-1 bg-black/70 border-rounded animate-grow" style="height: 85%;"></div>
            <div class="flex-1 bg-black border-rounded animate-grow" style="height: 100%;"></div>
        </div>

        <div class="flex justify-between mt-4 text-xs text-tertiary">
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
            <span>Sun</span>
        </div>
    </div>

    {{-- Enrollment Stats --}}
    <div class="p-4 border-rounded border-primary bg-primary">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold mb-2">Total Enrollments</h2>
                <p class="font-bold text-5xl">0</p>
                <p class="text-xs text-tertiary mt-2">Across all courses</p>
            </div>
            <div class="text-4xl bg-invert text-invert border-rounded p-4">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        {{-- Enrollment Progress Bars --}}
        <div class="space-y-4 mt-12">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-tertiary">Free Enrollments</span>
                    <span class="font-semibold">0</span>
                </div>
                <div class="h-2 bg-secondary border-rounded overflow-hidden">
                    <div class="h-full bg-invert border-rounded animate-progress" style="width: 20%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-tertiary">Paid Enrollments</span>
                    <span class="font-semibold">0</span>
                </div>
                <div class="h-2 bg-secondary border-rounded overflow-hidden">
                    <div class="h-full bg-invert border-rounded animate-progress" style="width: 80%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-tertiary">Active Students</span>
                    <span class="font-semibold">0</span>
                </div>
                <div class="h-2 bg-secondary border-rounded overflow-hidden">
                    <div class="h-full bg-invert border-rounded animate-progress" style="width: 50%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>