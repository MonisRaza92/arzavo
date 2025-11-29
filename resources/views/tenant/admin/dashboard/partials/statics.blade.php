<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat border-rounded flex flex-col justify-between bg-primary border border-primary">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary">Total Users</h2>
                <p class="text-3xl font-bold text-primary">100</p>
            </div>
            <div class="bg-invert border-rounded p-4"><i class="fas fa-users text-3xl text-invert"></i></div>
        </div>
        <a class="px-4 py-2 text-sm relative border-top" href="#">
            View All Users <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>

    <div class="stat border-rounded flex flex-col justify-between bg-primary border border-primary">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary">Total Students</h2>
                <p class="text-3xl font-bold text-primary">{{ $students->count() }}</p>
            </div>
            <div class="bg-invert border-rounded p-4"><i class="fas fa-user-graduate text-3xl text-invert"></i></div>
        </div>
        <a class="px-4 py-2 text-sm relative border-top" href="">
            View All Students <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>

    <div class="stat border-rounded flex flex-col justify-between bg-primary border border-primary">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary">Total Teachers</h2>
                <p class="text-3xl font-bold text-primary">{{ $teachers->count() }}</p>
            </div>
            <div class="bg-invert border-rounded p-4"><i class="fas fa-chalkboard-teacher text-3xl text-invert"></i></div>
        </div>
        <a class="px-4 py-2 text-sm relative border-top" href="">
            View All Teachers <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>

    <div class="stat border-rounded flex flex-col justify-between bg-primary border border-primary">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary">Total Staff</h2>
                <p class="text-3xl font-bold text-primary">{{ $staff->count() }}</p>
            </div>
            <div class="bg-invert border-rounded p-4"><i class="fas fa-user-tie text-3xl text-invert"></i></div>
        </div>
        <a class="px-4 py-2 text-sm relative border-top" href="">
            View All Staff <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <!-- Course Overview -->
    <div class="bg-primary border-rounded border-primary">
        <div class="flex items-center justify-between p-4">
            <h3 class="text-2xl font-bold text-primary">Course Overview</h3>
            <i class="fa-solid fa-pie-chart text-primary text-xl"></i>
        </div>
        <div class="data grid grid-cols-2 px-4 gap-4">
            <div class="flex flex-col p-3 border-rounded border-primary">
                <h4 class="text-sm font-medium text-tertiary">Total Course</h4>
                <p class="text-2xl font-bold text-primary mt-1">{{ $courses->count() }}</p>
            </div>
            <div class="flex flex-col p-3 border-rounded border-primary">
                <h4 class="text-sm font-medium text-tertiary">Active Course</h4>
                <p class="text-2xl font-bold text-primary mt-1">{{ $courses->where('status', 'published')->count() }}</p>
            </div>
            <div class="flex flex-col p-3 border-rounded border-primary">
                <h4 class="text-sm font-medium text-tertiary">Inactive</h4>
                <p class="text-2xl font-bold text-primary mt-1">{{ $courses->whereNotIn('status', ['published'])->count() }}</p>
            </div>
            <div class="flex flex-col p-3 border-rounded border-primary">
                <h4 class="text-sm font-medium text-tertiary">Courses Revenue</h4>
                <p class="text-2xl font-bold text-primary mt-1">₹26,569</p>
            </div>
        </div>
        <a href="" class="relative block w-full px-4 py-2 mt-4 text-tertiary hover:text-primary border-top transition-all">
            View All Courses
            <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>

    <!-- Test & Quizz -->
    <div class="md:col-span-2 bg-primary border-rounded border-primary">
        <div class="flex items-center justify-between p-4">
            <h3 class="text-2xl font-bold text-primary">Books & Notes</h3>
            <i class="fa-solid fa-pie-chart text-primary text-xl"></i>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4">
            <div class="col-span-1">
                <div class="border-primary border-rounded p-3">
                    <h4 class="text-sm font-medium text-tertiary">Books</h4>
                    <p class="text-2xl font-bold text-primary mt-1">15</p>
                </div>
                <div class="border-primary border-rounded p-3 mt-4">
                    <h4 class="text-sm font-medium text-tertiary">Notes</h4>
                    <p class="text-2xl font-bold text-primary mt-1">10</p>
                </div>
            </div>
            <div class="course-revenue col-span-2 p-4 border-rounded relative border-primary">
                <h2 class="text-lg font-semibold text-tertiary mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-tertiary"></i> Books & Notes Revenue
                </h2>
                <p class="font-bold text-5xl mb-10 text-primary tracking-wide">₹23,456</p>
                <img src="{{ asset('images/growth-line.webp') }}" alt="Growth Line" class="absolute w-11/12 h-2/3 bottom-2 left-5">
            </div>
        </div>
        <a href="" class="relative block w-full px-4 py-2 mt-4 text-tertiary hover:text-primary border-top transition-all">
            View Books & Notes
            <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>
</div>


<div class="charts flex flex-col lg:flex-row justify-between gap-4 mt-4">
    <div class="chart-container lg:w-2/3 border-rounded bg-primary border-primary">
        <div class="flex items-center justify-between p-4">
            <h3 class="text-2xl font-bold text-primary">Revenue Overview</h3>
            <i class="fa-solid fa-pie-chart text-primary text-xl"></i>
        </div>
        <div class="data grid grid-cols-1 md:grid-cols-2 gap-4 px-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col p-3 border-rounded border-primary">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-tertiary">Income</h4>
                        <i class="fa-solid fa-money-bill-wave text-tertiary"></i>
                    </div>
                    <p class="text-2xl font-bold text-primary mt-1">₹26,569</p>
                </div>

                <div class="flex flex-col p-3 border-rounded border-primary">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-tertiary">Expenses</h4>
                        <i class="fa-solid fa-receipt text-tertiary"></i>
                    </div>
                    <p class="text-2xl font-bold text-primary mt-1">₹12,235</p>
                </div>

                <div class="flex flex-col p-3 border-rounded border-primary">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-tertiary">Loan & EMIs</h4>
                        <i class="fa-solid fa-credit-card text-tertiary"></i>
                    </div>
                    <p class="text-2xl font-bold text-primary mt-1">0</p>
                </div>

                <div class="flex flex-col p-3 border-rounded border-primary">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-tertiary">Donations</h4>
                        <i class="fa-solid fa-coins text-tertiary"></i>
                    </div>
                    <p class="text-2xl font-bold text-primary mt-1">₹23,598</p>
                </div>
            </div>

            <div class="course-revenue p-4 border-rounded relative border border-primary">
                <h2 class="text-lg font-semibold text-tertiary mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-tertiary"></i> Total Profite
                </h2>
                <p class="font-bold text-5xl mb-10 text-primary tracking-wide">₹1,23,456</p>
                <img src="{{ asset('images/growth-line.webp') }}" alt="Growth Line" class="absolute w-11/12 h-2/3 bottom-2 left-3">
            </div>
        </div>
        <a href="" class="relative block w-full px-4 py-2 mt-4 text-tertiary border-top transition-all">
            View Reports & Analytics
            <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>

    <!-- FEES CHART -->
    <div class="chart-container border-rounded lg:w-1/3 bg-primary border-primary">
        <div class="flex items-center justify-between p-4">
            <h3 class="text-2xl font-bold text-primary">Fee Summary</h3>
            <i class="fas fa-chart-pie text-primary text-xl"></i>
        </div>

        <div class="data grid grid-cols-2 gap-4 mb-2 px-4">
            <div class="p-3 border-rounded border-primary">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-secondary">Collected</h4>
                </div>
                <p class="text-2xl font-bold text-primary mt-1">₹11,111</p>
            </div>

            <div class="p-3 border-rounded border-primary">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-secondary">Pending</h4>
                </div>
                <p class="text-2xl font-bold text-primary mt-1">₹12,345</p>
            </div>

            <div class="p-3 border-rounded border-primary">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-secondary">Total Fees</h4>
                </div>
                <p class="text-2xl font-bold text-primary mt-1">₹23,456</p>
            </div>

            <div class="p-3 border-rounded border-primary">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-secondary">Collection Ratio</h4>
                </div>
                <p class="text-2xl font-bold text-primary mt-1">90%</p>
            </div>
        </div>
        <a href="" class="relative block w-full px-4 py-2 mt-4 text-tertiary hover:text-primary border-top transition-all">
            View All Students
            <i class="fas fa-arrow-right text-xs absolute right-4 top-1/2 transform -translate-y-1/2"></i>
        </a>
    </div>
</div>