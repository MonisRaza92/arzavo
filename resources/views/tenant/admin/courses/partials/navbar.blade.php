<header class="flex items-center justify-between w-full bg-primary px-4 py-2 border-bottom sticky top-0 z-100">
    <div class="flex items-center gap-2 md:flex-1">
        <a href="{{ route('admin.courses.index') }}" class="py-1 px-2 text-lg border-right group mr-2"><i class="fa fa-arrow-left group-hover:-translate-x-1 transition-all duration-300"></i></a>
        <div class="hidden md:block">
            <h2 class="text-primary font-semibold text-lg leading-none">{{ Str::limit($course->title, 20, '...') }}</h2>
            <span class="text-sm text-tertiary">Status: {{ $course->status }}</span>
        </div>
    </div>
    <div class="course-select flex-1 border-primary border-rounded pr-2">
        <select name="course_id" id="course_id" class="w-full outline-none p-2">
            @foreach ($courses as $course)
            <option value="{{ $course->id }}">{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1 flex gap-2 justify-end items-center">
        <x-button class="w-auto" variant="primary" onclick="submitCourseUpdateForm()" loadingText="Saving...">Save</x-button>
        <x-button class="w-auto" variant="secondary">Publish</x-button>
    </div>
</header>