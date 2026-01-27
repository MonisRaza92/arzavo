{{-- PREMIUM BLACK DASHBOARD STATS --}}
<div class="dashboard-container">
    {{-- MAIN STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        {{-- Total Users Card --}}
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-tertiary text-sm font-medium mb-2">Total Users</p>
                        <h3 class="text-4xl font-bold text-primary counter" data-target="1248">0</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-400 text-xs font-medium">
                                <i class="fas fa-arrow-up mr-1"></i> +12.5%
                            </span>
                            <span class="text-xs text-primary">last month</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>

                {{-- Mini Chart --}}
                <div class="flex items-end gap-1 h-12 mt-4">
                    <div class="flex-1 bg-invert border-rounded opacity-40 chart-bar" style="height: 45%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-50 chart-bar" style="height: 60%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-60 chart-bar" style="height: 35%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-70 chart-bar" style="height: 75%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-80 chart-bar" style="height: 55%;"></div>
                    <div class="flex-1 bg-invert border-rounded opacity-90 chart-bar" style="height: 85%;"></div>
                    <div class="flex-1 bg-invert border-rounded chart-bar" style="height: 100%;"></div>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Users List</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>

        {{-- Total Students Card --}}
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-tertiary text-sm font-medium mb-2">Total Students</p>
                        <h3 class="text-4xl font-bold text-primary counter" data-target="{{ $students->count() }}">0</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-400 text-xs font-medium">
                                <i class="fas fa-arrow-up mr-1"></i> +8.3%
                            </span>
                            <span class="text-xs text-gray-500">last month</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>

                {{-- Progress Rings --}}
                <div class="mt-8">
                    <div class="flex items-center justify-between text-xs text-secondary mb-2">
                        <span>Active Students</span>
                        <span class="font-medium">85%</span>
                    </div>
                    <div class="w-full h-2 bg-secondary rounded-full overflow-hidden">
                        <div class="h-full bg-invert rounded-full progress-bar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Students List</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>

        {{-- Total Teachers Card --}}
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-400 text-sm font-medium mb-2">Total Teachers</p>
                        <h3 class="text-4xl font-bold text-primary counter" data-target="{{ $teachers->count() }}">0</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 text-green-400 text-xs font-medium">
                                <i class="fas fa-arrow-up mr-1"></i> +5.2%
                            </span>
                            <span class="text-xs text-gray-500">last month</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>

                {{-- Star Rating Display --}}
                <div class="mt-12 flex items-center gap-1">
                    <i class="fas fa-star text-primary text-sm"></i>
                    <i class="fas fa-star text-primary text-sm"></i>
                    <i class="fas fa-star text-primary text-sm"></i>
                    <i class="fas fa-star text-primary text-sm"></i>
                    <i class="fas fa-star text-primary text-sm"></i>
                    <span class="text-xs text-primary ml-2">4.9 Avg Rating</span>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Teachers List</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>

        {{-- Total Courses Card --}}
        <div class="stat-card group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-secondary text-sm font-medium mb-2">Total Courses</p>
                        <h3 class="text-4xl font-bold text-primary counter" data-target="{{ $courses->count() }}">0</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 text-green-400 text-xs font-medium">
                                <i class="fas fa-arrow-up mr-1"></i> +15.8%
                            </span>
                            <span class="text-xs text-gray-500">last month</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                </div>

                {{-- Course Status Pills --}}
                <div class="mt-6 flex gap-2">
                    <span class="px-2 py-1 bg-invert text-invert text-xs border-rounded">{{ $courses->where('status', 'published')->count() }} Active</span>
                    <span class="px-2 py-1 bg-invert text-invert text-xs border-rounded">{{ $courses->where('status', 'draft')->count() }} Draft</span>
                    <span class="px-2 py-1 bg-invert text-invert text-xs border-rounded">{{ $courses->where('status', 'inactive')->count() }} Inactive</span>
                    <span class="px-2 py-1 bg-invert text-invert text-xs border-rounded">{{ $courses->where('status', 'review')->count() }} Review</span>
                </div>
            </div>
            <div class="px-4 py-2 border-top flex items-center justify-between">
                <span class="text-sm text-tertiary font-medium">Courses List</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>
    </div>

    {{-- REVENUE & ANALYTICS SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Revenue Card --}}
        <div class="lg:col-span-2 group relative overflow-hidden border-rounded bg-primary border-primary">
            <div class="relative p-4">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-primary mb-2">Total Revenue</h3>
                        <p class="text-sm text-tertiary">All time earnings</p>
                    </div>
                    <div class="p-3 rounded-xl bg-invert text-invert">
                        <i class="fas fa-bars-progress text-2xl"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-5xl font-bold text-transparent bg-clip-text text-primary counter" data-target="{{ $revenue }}">0</h2>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-100 text-green-400 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> +32.5%
                        </span>
                        <span class="text-sm text-gray-400">from last month</span>
                    </div>
                </div>

                {{-- Revenue Chart --}}
                <div class="grid grid-cols-7 gap-2 h-32 md:h-75 items-end">
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 45%;"></div>
                        <span class="text-xs text-gray-500">Mon</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 65%;"></div>
                        <span class="text-xs text-gray-500">Tue</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 50%;"></div>
                        <span class="text-xs text-gray-500">Wed</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 85%;"></div>
                        <span class="text-xs text-gray-500">Thu</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 70%;"></div>
                        <span class="text-xs text-gray-500">Fri</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 100%;"></div>
                        <span class="text-xs text-gray-500">Sat</span>
                    </div>
                    <div class="flex flex-col items-center justify-end gap-1 h-full">
                        <div class="w-full bg-invert rounded-3xl chart-bar" style="height: 90%;"></div>
                        <span class="text-xs text-gray-500">Sun</span>
                    </div>
                </div>
            </div>
            <div class="border-top p-4 flex gap-4">
                View Revenue Analytics
            </div>
        </div>

        {{-- Fee Collection Stats --}}
        <div class="border-rounded bg-primary flex flex-col">
            <div class="p-4 flex items-center justify-between">
                <h3 class="font-bold text-xl">Fee Collection</h3>
                <div class="p-3 rounded-lg bg-invert text-invert">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col items-center justify-center">
                <div class="relative w-75 aspect-square rounded-full flex items-center justify-center" style="background: conic-gradient(var(--bg-invert) 0% 60%, var(--bg-secondary) 60% 100%);">
                    <div class="w-36 h-36 bg-primary rounded-full flex flex-col items-center justify-center shadow-inner">
                        <span class="text-3xl font-black">60%</span>
                        <span class="text-[10px] text-tertiary font-bold uppercase tracking-wider">Collected</span>
                    </div>
                </div>
                
                <div class="mt-8 grid grid-cols-3 gap-4 w-full">
                    <div class="text-center border-primary border-rounded p-2 bg-invert text-invert">
                        <div class="text-[10px] uppercase font-bold mb-1">Paid</div>
                        <div class="font-bold">514</div>
                    </div>
                    <div class="text-center border-primary border-rounded p-2 bg-invert text-invert">
                        <div class="text-[10px] uppercase font-bold mb-1">Unpaid</div>
                        <div class="font-bold">342</div>
                    </div>
                    <div class="text-center border-primary border-rounded p-2 bg-invert text-invert">
                        <div class="text-[10px] uppercase font-bold mb-1">Pending</div>
                        <div class="font-bold">728</div>
                    </div>
                </div>
            </div>
            <div class="border-top p-4">
                <span class="text-sm text-gray-500 font-medium">Total Students</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Counter Animation */
    .counter {
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Chart Bar Animation */
    .chart-bar {
        animation: growUp 1.5s ease-out forwards;
        transform-origin: bottom;
    }

    @keyframes growUp {
        from {
            transform: scaleY(0);
            opacity: 0;
        }

        to {
            transform: scaleY(1);
            opacity: 1;
        }
    }

    /* Progress Bar Animation */
    .progress-bar {
        animation: fillBar 2s ease-out forwards;
    }

    @keyframes fillBar {
        from {
            width: 0%;
        }

        to {
            width: 100%;
        }
    }

    /* Custom Scrollbar for Dark Theme */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }

    ::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #444;
    }

    /* Counter Animation with JavaScript */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }
</style>

<script>
    // Counter Animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            // Start animation when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(counter);
        });
    });
</script>