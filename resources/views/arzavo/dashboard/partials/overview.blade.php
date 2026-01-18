<!-- Overview Section -->
<section id="overview" class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-primary border-primary border-rounded p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-primary mb-2">
                    Welcome back, {{ Auth::user()->fname }}! 👋
                </h2>
                <p class="text-secondary">
                    Here's what's happening with your educational platforms today.
                </p>
            </div>
            <div class="hidden md:block">
                <div class="text-right">
                    <div class="text-tertiary text-sm">{{ now()->format('l, F j, Y') }}</div>
                    <div class="text-primary font-semibold">{{ now()->format('g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tenants -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-tertiary text-sm font-medium">Total Tenants</p>
                    <p class="text-3xl font-bold text-primary">{{ $tenants->count() }}</p>
                    <p class="text-accent text-sm">
                        <i class="fa-solid fa-arrow-up mr-1"></i>
                        +2 this month
                    </p>
                </div>
                <div class="bg-accent text-invert w-12 h-12 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-tertiary text-sm font-medium">Active Subscriptions</p>
                    <p class="text-3xl font-bold text-primary">{{ $tenants->where('status', 'active')->count() }}</p>
                    <p class="text-green-600 text-sm">
                        <i class="fa-solid fa-check mr-1"></i>
                        All up to date
                    </p>
                </div>
                <div class="bg-green-500 text-white w-12 h-12 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-credit-card text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-tertiary text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-primary">2,847</p>
                    <p class="text-accent text-sm">
                        <i class="fa-solid fa-arrow-up mr-1"></i>
                        +156 this week
                    </p>
                </div>
                <div class="bg-blue-500 text-white w-12 h-12 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-tertiary text-sm font-medium">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-primary">₹45,280</p>
                    <p class="text-accent text-sm">
                        <i class="fa-solid fa-arrow-up mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="bg-accent-secondary text-white w-12 h-12 border-rounded flex items-center justify-center">
                    <i class="fa-solid fa-rupee-sign text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-primary">Recent Activity</h3>
                <a href="#" class="text-accent text-sm font-medium hover:underline">View All</a>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="bg-green-100 text-green-600 w-8 h-8 border-rounded flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-primary font-medium">New tenant created</p>
                        <p class="text-tertiary text-sm">Delhi Public School joined your platform</p>
                        <p class="text-tertiary text-xs">2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-blue-100 text-blue-600 w-8 h-8 border-rounded flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-credit-card text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-primary font-medium">Payment received</p>
                        <p class="text-tertiary text-sm">₹2,499 from ABC Academy</p>
                        <p class="text-tertiary text-xs">5 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-accent-subtle text-accent w-8 h-8 border-rounded flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-globe text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-primary font-medium">Domain verified</p>
                        <p class="text-tertiary text-sm">school.example.com is now active</p>
                        <p class="text-tertiary text-xs">1 day ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-yellow-100 text-yellow-600 w-8 h-8 border-rounded flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-users text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-primary font-medium">New students enrolled</p>
                        <p class="text-tertiary text-sm">45 students joined this week</p>
                        <p class="text-tertiary text-xs">2 days ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="bg-primary border-primary border-rounded p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-primary">Performance Overview</h3>
                <select class="bg-secondary border-primary border-rounded px-3 py-1 text-sm">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>

            <!-- Simple Chart -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-tertiary text-sm">Student Enrollments</span>
                    <span class="text-primary font-semibold">+23%</span>
                </div>
                <div class="w-full bg-secondary border-rounded h-2">
                    <div class="bg-accent h-2 border-rounded" style="width: 75%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-tertiary text-sm">Course Completions</span>
                    <span class="text-primary font-semibold">+18%</span>
                </div>
                <div class="w-full bg-secondary border-rounded h-2">
                    <div class="bg-green-500 h-2 border-rounded" style="width: 68%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-tertiary text-sm">Revenue Growth</span>
                    <span class="text-primary font-semibold">+12%</span>
                </div>
                <div class="w-full bg-secondary border-rounded h-2">
                    <div class="bg-accent-secondary h-2 border-rounded" style="width: 82%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-tertiary text-sm">Platform Usage</span>
                    <span class="text-primary font-semibold">+31%</span>
                </div>
                <div class="w-full bg-secondary border-rounded h-2">
                    <div class="bg-blue-500 h-2 border-rounded" style="width: 91%"></div>
                </div>
            </div>

            <!-- Chart Legend -->
            <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-tertiary">
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent">156</div>
                    <div class="text-tertiary text-sm">New Students</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-500">89%</div>
                    <div class="text-tertiary text-sm">Satisfaction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="bg-accent-subtle border-accent border-rounded p-6">
        <div class="flex items-start space-x-4">
            <div class="bg-accent text-invert w-10 h-10 border-rounded flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-lightbulb"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-accent font-semibold mb-2">💡 Pro Tip</h4>
                <p class="text-accent text-sm mb-3">
                    Did you know you can customize your tenant's branding completely? Upload your logo, set custom colors, and create a unique experience for your students.
                </p>
                <a href="{{ route('documentation') }}" class="text-accent font-medium text-sm hover:underline">
                    Learn more about customization →
                </a>
            </div>
        </div>
    </div>
</section>
