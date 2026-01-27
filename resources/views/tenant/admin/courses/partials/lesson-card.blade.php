<div id="lesson-{{ $lesson->id }}"
    class="p-3 border-rounded border-primary bg-primary flex justify-between items-center">

    <div>
        <h5 class="font-medium text-sm">{{ $lesson->title }}</h5>
        <p class="text-xs text-tertiary">
            {{ ucfirst($lesson->type) }} lesson
        </p>
    </div>

    <button
        onclick="deleteLesson({{ $lesson->id }})"
        class="text-red-400 text-sm">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>