<div class="chart-container rounded-md" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);">
    <h3 class="text-2xl p-4 font-bold relative" style="color: var(--primary-color);">Courses Revenue <span class="absolute right-4 top-3 default-button uppercase font-bold text-sm!" onclick="document.getElementById('upload-course-modal').classList.toggle('hidden');">Upload New <i class="fas fa-plus"></i></span></h3>
    <div class="data grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 px-4">
        <div class="course-stats grid grid-cols-2 gap-4">
            <div class="flex flex-col p-2 rounded-sm" style="border: 1px solid var(--border-color);">
                <h4 class="text-sm font-medium" style="color: var(--text-color);">Total Courses</h4>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $courses->count() }}</p>
            </div>
            <div class="flex flex-col p-2 rounded-sm" style="border: 1px solid var(--border-color);">
                <h4 class="text-sm font-medium" style="color: var(--text-color);">Active Courses</h4>
                <p class="text-3xl font-bold " style="color: var(--primary-color);">{{ $courses->where('status', 'published')->count() }}</p>
            </div>
            <div class="flex flex-col p-2 rounded-sm" style="border: 1px solid var(--border-color);">
                <h4 class="text-sm font-medium" style="color: var(--text-color);">Inactive Courses</h4>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $courses->whereNotIn('status', ['published'])->count() }}</p>
            </div>
            <div class="flex flex-col p-2 rounded-sm" style="border: 1px solid var(--border-color);">
                <h4 class="text-sm font-medium" style="color: var(--text-color);">Sold Courses</h4>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $courses->where('status', 'sold')->count() }}</p>
            </div>
        </div>
        <div class="course-revenue p-2 rounded-sm relative" style="border: 1px solid var(--border-color);">
            <h2 class="text-2xl font-medium" style="color: var(--text-color);">Courses Revenue</h2>
            <p class="font-bold text-6xl mt-2" style="color: var(--primary-color);">₹1,23,456</p>
            <p class="text-sm absolute bottom-2 left-2" style="color: var(--secondary-text-color);">Total Revenue from all courses</p>
        </div>
    </div>
</div>