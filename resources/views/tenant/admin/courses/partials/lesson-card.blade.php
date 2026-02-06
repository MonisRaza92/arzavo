<div id="lesson-{{ $lesson->id }}"
    class="bg-primary border-primary border-rounded p-4 flex gap-4 items-start justify-between">

    {{-- LEFT : MAIN INFO --}}
    <div class="flex gap-4 items-start w-full">

        @if ($lesson->video_path)
        <video src="{{ media($lesson->video_path) }}" onmouseenter="this.controls = true" onmouseleave="this.controls = false" class="h-20 aspect-video object-cover border-rounded"></video>
        @endif
        @if ($lesson->file_path)
        <a href="{{ media($lesson->file_path) }}" target="_blank" class="shrink-0">
            <iframe src="{{ media($lesson->file_path) }}#page=1&toolbar=0&navpanes=0&scrollbar=0&view=FitH" class="h-20 aspect-video border-rounded pointer-events-none overflow-hidden" frameborder="0"></iframe>
        </a>
        @endif

        {{-- CONTENT --}}
        <div class="flex-1 space-y-1">

            <div class="flex flex-col mb-4">
                <h4 class="font-semibold text-lg mb-1">
                    {{ $lesson->title }}
                </h4>
                <div class="flex gap-1">
                    <span class="text-xs px-2 py-0.5 border-rounded bg-tertiary">
                        {{ $lesson->type }}
                    </span>

                    <!-- @if($lesson->is_free)
                    <span class="text-xs px-2 py-0.5 border-rounded bg-green-600/10 text-green-500">
                        Free
                    </span>
                    @endif -->

                    @if($lesson->is_mandatory)
                    <span class="text-xs px-2 py-0.5 border-rounded bg-orange-600/10 text-orange-500">
                        Mandatory
                    </span>
                    @endif

                    @if(!$lesson->is_active)
                    <span class="text-xs px-2 py-0.5 border-rounded bg-red-600/10 text-red-500">
                        Inactive
                    </span>
                    @endif
                </div>
            </div>

            {{-- DESCRIPTION --}}
            @if($lesson->description)
            <p class="text-xs text-tertiary line-clamp-2">
                {{ $lesson->description }}
            </p>
            @endif

            {{-- META ROW --}}
            <div class="flex items-center gap-4 text-xs text-tertiary mt-1">

                @if($lesson->duration)
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-clock"></i>
                    {{ $lesson->duration }} min
                </span>
                @endif

                @if($lesson->video_path)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-video"></i>
                    Video attached
                </span>
                @endif

                @if($lesson->file_path)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-paperclip"></i>
                    File attached
                </span>
                @endif

            </div>
        </div>

    </div>

    {{-- RIGHT : ACTIONS --}}
    <div class="actions relative">
        <button onclick="toggleModel('lessonActions-{{ $lesson->id }}')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        <div id="lessonActions-{{ $lesson->id }}" class="menu absolute hidden right-0 bottom-full bg-primary w-42 z-50 border-primary border-rounded p-2">
            <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-edit mr-2"></i> Update</button>
            <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-up mr-2"></i> Move Up</button>
            <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-down mr-2"></i> Move Down</button>
            <button type="button" onclick="deleteLesson({{ $lesson->id }})" class="text-secondary block py-2 px-1 pt-3 border-top mt-2 text-left w-full text-sm"> <i class="fa-solid fa-trash mr-2 text-red-400"></i> Delete
            </button>
        </div>
    </div>
    <script>
        async function deleteLesson(lessonId) {
            if (!confirm('Delete this lesson?')) return;
    
            const res = await fetch(
                `/admin/courses/{{ $course->slug ?? $module->course_id }}/lessons/${lessonId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            );
    
            if (res.ok) {
                document.getElementById(`lesson-${lessonId}`)?.remove();
            }
        }
    </script>

</div>