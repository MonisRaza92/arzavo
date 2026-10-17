{{-- Top Curriculum Stats Cards Row --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
    {{-- Total Categories --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-theme-text-muted font-normal">Categories</span>
            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-theme-text tracking-tight">{{ $totalCategories }}</h3>
        <p class="text-[10px] text-theme-text-muted mt-1">Academic Wings</p>
    </div>

    {{-- Total Classes --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-theme-text-muted font-normal">Classes</span>
            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-theme-text tracking-tight">{{ $totalClasses }}</h3>
        <p class="text-[10px] text-theme-text-muted mt-1">Curriculum Programs</p>
    </div>

    {{-- Total Subjects --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-theme-text-muted font-normal">Subjects</span>
            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-book-reader"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-theme-text tracking-tight">{{ $totalSubjects }}</h3>
        <p class="text-[10px] text-theme-text-muted mt-1">Teaching Modules</p>
    </div>

    {{-- Total Teachers & Mentors --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-theme-text-muted font-normal">Teachers</span>
            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-theme-text tracking-tight">{{ $totalTeachers }}</h3>
        <p class="text-[10px] text-theme-text-muted mt-1">Faculty & Mentors</p>
    </div>

    {{-- Total Enrolled Students (Separate Card) --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col justify-between col-span-2 sm:col-span-1">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-theme-text-muted font-normal">Students</span>
            <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-theme-text tracking-tight">{{ $totalStudents }}</h3>
        <p class="text-[10px] text-theme-text-muted mt-1">Enrolled Learners</p>
    </div>
</div>
