<div id="addLessonForm" class="hidden mt-4 p-4 border-rounded border-primary bg-primary space-y-4">

    <h2 class="font-semibold mb-4"><i class="fa-solid fa-book-open text-sm mr-1"></i> Add New Lesson</h2>

    <x-input.text
        name="lessonTitle"
        label="Lesson title"
        placeholder="Enter lesson title" />

    <x-input.textarea
        name="lessonDescription"
        label="Lesson description"
        placeholder="Short description (optional)" />

    <x-input.select
        name="lessonType"
        label="Lesson type"
        :options="['video','pdf']" />

    {{-- CONDITIONAL FIELDS --}}
    <div class="flex flex-col md:flex-row gap-4 mt-4">
        <div class="w-full" id="lessonVideoContainer">
            <x-input.video
                name="lessonVideo"
                label="Select video"
                type="video" />
        </div>

        <div class="hidden w-full" id="lessonFileContainer">
            <x-input.content
                name="lessonFile"
                label="Select file (PDF)"
                type="pdf" />
        </div>

        <x-input.textarea
            name="lessonContent"
            label="Lesson content"
            placeholder="Enter lesson content" :rows="8" class="h-[calc(100%-1.7rem)]" />
    </div>

    <x-input.number
        name="lessonDuration"
        label="Duration (minutes)"
        placeholder="e.g. 10" />

    {{-- TOGGLES --}}
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="hidden">
            <x-input.toggle name="lessonFree" label="Free lesson" :loading="false" value="1" />
        </div>
        <x-input.toggle name="lessonMandatory" label="Mandatory" :loading="false" value="1" />
        <x-input.toggle name="lessonActive" label="Active" :value="1" :loading="false" />
    </div>

    <div class="flex gap-2 mt-4">
        <x-button id="saveLessonBtn" variant="primary" loadingText="Saving...">Save Lesson</x-button>
        <x-button id="cancelLessonBtn" variant="secondary" :loading="false">Cancel</x-button>
    </div>

</div>

<script>
    const lessonType = document.getElementById('lessonType');
    const lessonVideoContainer = document.getElementById('lessonVideoContainer');
    const lessonFileContainer = document.getElementById('lessonFileContainer');

    lessonType.addEventListener('change', function() {
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
</script>
