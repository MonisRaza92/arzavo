<form id="addModuleLessonForm{{ $moduleId }}" class="hidden mt-4 p-4 border-rounded border-primary bg-primary space-y-4">

    <h2 class="font-semibold mb-4"><i class="fa-solid fa-book-open text-sm mr-1"></i> Add New Lesson</h2>

    <x-input.text name="moduleLessonTitle{{ $moduleId }}" label="Lesson title" placeholder="Enter lesson title" />

    <x-input.textarea name="moduleLessonDescription{{ $moduleId }}" label="Lesson description"
        placeholder="Short description (optional)" />

    <x-input.select name="moduleLessonType{{ $moduleId }}" label="Lesson type" :options="['video', 'pdf']" />

    {{-- CONDITIONAL FIELDS --}}
    <div class="flex flex-col md:flex-row gap-4 mt-2">
        <div class="hidden w-full" id="moduleLessonVideoContainer{{ $moduleId }}">
            <x-input.video name="moduleLessonVideo{{ $moduleId }}" label="Select video" type="video" value="" />
        </div>
        <div class="hidden w-full" id="moduleLessonFileContainer{{ $moduleId }}">
            <x-input.content name="moduleLessonFile{{ $moduleId }}" label="Select file (PDF)" type="pdf" value="" />
        </div>

        <x-input.textarea name="moduleLessonContent{{ $moduleId }}" label="Lesson content"
            placeholder="Enter lesson content" :rows="8" class="h-[calc(100%-1.7rem)]" />
    </div>

    <x-input.number name="moduleLessonDuration{{ $moduleId }}" label="Duration (minutes)" placeholder="e.g. 10" />

    {{-- TOGGLES --}}
    <div class="grid grid-cols-2 gap-4 mt-2">
        <div class="hidden">
            <x-input.toggle name="moduleLessonFree{{ $moduleId }}" label="Free lesson" :loading="false" value="1" />
        </div>
        <x-input.toggle name="moduleLessonMandatory{{ $moduleId }}" label="Mandatory" :loading="false" value="1" />
        <x-input.toggle name="moduleLessonActive{{ $moduleId }}" label="Active" :value="1" :loading="false" />
    </div>

    <div class="flex gap-2 mt-4">
        <x-button id="saveModuleLessonBtn{{ $moduleId }}" variant="primary" loadingText="Saving...">Save
            Lesson</x-button>
        <x-button id="cancelModuleLessonBtn{{ $moduleId }}" variant="secondary" :loading="false">Cancel</x-button>
    </div>

</form>

<script>
    (function() {
        const lessonType = document.getElementById('moduleLessonType{{ $moduleId }}');
        const lessonVideoContainer = document.getElementById('moduleLessonVideoContainer{{ $moduleId }}');
        const lessonFileContainer = document.getElementById('moduleLessonFileContainer{{ $moduleId }}');

        lessonType.addEventListener('change', function () {
            if (this.value === 'video') {
                lessonVideoContainer.classList.remove('hidden');
                lessonFileContainer.classList.add('hidden');
            } else if (this.value === 'pdf') {
                lessonVideoContainer.classList.add('hidden');
                lessonFileContainer.classList.remove('hidden');
            } else {
                lessonVideoContainer.classList.remove('hidden');
                lessonFileContainer.classList.add('hidden');
            }
        });
    })();
</script>