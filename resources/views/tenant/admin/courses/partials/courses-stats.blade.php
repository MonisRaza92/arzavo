<div class="chart-container border-top bg-primary">
    <div class="data grid grid-cols-1 md:grid-cols-2 gap-4 p-4">

        {{-- LEFT : COURSE STATS --}}
        <div class="course-stats grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Total Courses --}}
            <div class="flex justify-between items-center p-4 border-rounded border-primary">
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Total Courses</h4>
                    <p class="text-4xl font-bold">{{ $courses->count() }}</p>
                    <p class="text-xs text-tertiary mt-1">
                        All created courses
                    </p>
                </div>
                <div class="text-3xl text-invert bg-invert border-rounded p-4">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>

            {{-- Active Courses --}}
            <div class="flex justify-between items-center p-4 border-rounded border-primary">
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Active Courses</h4>
                    <p class="text-4xl font-bold">
                        {{ $courses->where('status', 'published')->count() }}
                    </p>
                    <p class="text-xs text-tertiary mt-1">
                        Visible to students
                    </p>
                </div>
                <div class="text-3xl text-invert bg-invert border-rounded p-4">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            {{-- Inactive Courses --}}
            <div class="flex justify-between items-center p-4 border-rounded border-primary">
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Inactive Courses</h4>
                    <p class="text-4xl font-bold">
                        {{ $courses->whereNotIn('status', ['published'])->count() }}
                    </p>
                    <p class="text-xs text-tertiary mt-1">
                        Draft / archived
                    </p>
                </div>
                <div class="text-3xl text-invert bg-invert border-rounded p-4">
                    <i class="fa-solid fa-circle-pause"></i>
                </div>
            </div>

            {{-- Sold Courses --}}
            <div class="flex justify-between items-center p-4 border-rounded border-primary">
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Sold Courses</h4>
                    <p class="text-4xl font-bold">
                        {{ $courses->where('status', 'sold')->count() }}
                    </p>
                    <p class="text-xs text-tertiary mt-1">
                        Purchased at least once
                    </p>
                </div>
                <div class="text-3xl text-invert bg-invert border-rounded p-4">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>

        </div>

        {{-- RIGHT : COURSE REVENUE --}}
        <div class="p-4 border-rounded border-primary flex flex-col relative overflow-hidden">

            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-medium">Courses Revenue</h2>
                    <p class="font-bold text-4xl md:text-6xl">
                        ₹0
                    </p>
                </div>
                <div class="text-4xl text-invert bg-invert border-rounded p-4">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>

            <div class="absolute bottom-4 left-4 hidden md:block">
                <p class="text-xs text-tertiary mt-1">
                    From all paid course enrollments, Updated automatically with each sale
                </p>
            </div>
        </div>

    </div>

</div>