<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Total Students</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $students->count() }}</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-4"><i class="fas fa-users text-3xl text-blue-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Active Students</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $students->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-green-100 rounded-lg p-4"><i class="fas fa-user-check text-3xl text-green-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Inactive Students</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $students->where('status', 'inactive')->count() }}</p>
            </div>
            <div class="bg-yellow-100 rounded-lg p-4"><i class="fas fa-user-times text-3xl text-yellow-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Suspended</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $students->where('status', 'suspended')->count() }}</p>
            </div>
            <div class="bg-red-100 rounded-lg p-4"><i class="fas fa-user-slash text-3xl text-red-500"></i></div>
        </div>
    </div>
</div>
<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Total Fees</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹58,548</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-4"><i class="fas fa-money-bill-wave text-3xl text-blue-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Collected Fees</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹45,000</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-4"><i class="fas fa-money-bill-wave text-3xl text-blue-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Pending Fees</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹13,548</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-4"><i class="fas fa-money-bill-wave text-3xl text-blue-500"></i></div>
        </div>
    </div>
    <div class="stat rounded-md flex flex-col justify-between" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 style="color:var(--text-color);">Collection Ratio</h2>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">76.8%</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-4"><i class="fas fa-money-bill-wave text-3xl text-blue-500"></i></div>
        </div>
    </div>
</div>