@extends('layouts.admin')
@section('title', 'Classes & Courses Manage')

@section('content')
{{-- Header --}}
@include('tenant.admin.classes_courses.partials.header')
{{-- Cards Grid --}}
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

    @forelse($classCourses as $class)

    <div class="border-primary border-rounded bg-primary hover-primary transition-all overflow-hidden">

        {{-- Image --}}
        <div class="mb-2 relative">
            @if($class->image)
            <img
                src="{{ asset($class->image) }}"
                alt="{{ $class->name }}"
                class="w-full aspect-video object-cover border-bottom">
            @else
            <div
                class="w-full aspect-video border-bottom flex items-center justify-center text-tertiary text-xs">
                No Image
            </div>
            @endif
            <div class="flex items-center justify-between left-2 gap-2 absolute bottom-2 right-2">
                {{-- Status --}}
                @if($class->status)
                <span class="text-[11px] text-invert bg-invert shadow shadow-amber-50/30 bg-opacity-20 px-4 py-1 rounded-full">
                    Active
                </span>
                @else
                <span class="text-[11px] text-primary bg-tertiary shadow shadow-amber-50/30 bg-opacity-20 px-4 py-1 rounded-full">
                    Inactive
                </span>
                @endif
                <!-- subjects -->
                <a href="{{ route('admin.subjects.index') }}?class_filter={{ $class->name }}" class="text-[11px] text-invert bg-accent shadow shadow-amber-50/30 bg-opacity-20 px-4 py-1 rounded-full cursor-pointer" title="Click to add Subject">
                    {{ $class->subjects->count() }} &nbsp; Subjects
                </a>
            </div>
        </div>

        <div class="px-3">
            {{-- Name --}}
            <h3 class="text-primary font-semibold text-xl mb-1">
                {{ $class->name }}
            </h3>

            {{-- Description --}}
            <p class="text-tertiary text-sm mb-3 line-clamp-3">
                {{ $class->description ?? 'No description available.' }}
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center border-top text-sm px-3 py-2">
            <!-- Created at -->
            <span class="text-tertiary">
                {{ $class->created_at->format('d M, Y') }}
            </span>

            <div class="flex gap-4 items-center">
                {{-- Edit --}}
                <button
                    onclick="openEditClassCoursePopup({{ $class->id }})"
                    class="text-tertiary text-hover-primary">
                    <i class="fa fa-edit"></i>
                </button>

                {{-- Delete --}}
                <form action="{{ route('admin.classes.courses.destroy', $class->id) }}"
                    method="POST"
                    onsubmit="return confirm('Delete this class/course?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-tertiary text-hover-primary">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>

    @empty
    <div class="col-span-full text-center text-tertiary py-6">
        No classes / courses found.
    </div>
    @endforelse

</div>

@include('tenant.admin.classes_courses.partials.class-edit-form')
@include('tenant.admin.classes_courses.partials.class-add-form')

<script>
    function openEditClassCoursePopup(classId) {
        functionGetClassCourseDetails(classId);
        document.getElementById('classEditPopup').classList.remove('hidden');
    }
</script>
@endsection
