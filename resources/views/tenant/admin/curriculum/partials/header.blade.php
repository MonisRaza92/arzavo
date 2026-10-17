{{-- Top Explanation Banner & Active Path Trail --}}
<div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div>
            <h1 class="text-base font-semibold text-theme-text leading-tight">
                Curriculum Architecture & Academic Hierarchy
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                Interactive single-page academic flow. Drag cards by their grip handle to reorder priorities. Click on any <strong>Category</strong> to branch out its <strong>Classes</strong> (with Class Mentors), and select a <strong>Class</strong> to manage its <strong>Subjects</strong>, assigned Faculty, and class schedules.
            </p>
        </div>
    </div>

    {{-- Active Tree Path Trail --}}
    <div class="flex items-center gap-1.5 flex-wrap self-stretch md:self-auto p-2 rounded-lg bg-secondary text-xs shrink-0">
        <span class="px-2.5 py-1 rounded-full bg-brand text-primary font-medium text-[11px]" x-text="selectedCategory ? selectedCategory.name : 'Category'"></span>
        <i class="fa-solid fa-chevron-right text-[10px] text-theme-text-muted"></i>
        <span class="px-2.5 py-1 rounded-full bg-brand text-primary font-medium text-[11px]" x-text="selectedClass ? selectedClass.name : 'Class'"></span>
        <i class="fa-solid fa-chevron-right text-[10px] text-theme-text-muted"></i>
        <span class="px-2.5 py-1 rounded-full bg-brand text-primary font-medium text-[11px]" x-text="selectedSubject ? selectedSubject.name : 'Subject'"></span>
    </div>
</div>
