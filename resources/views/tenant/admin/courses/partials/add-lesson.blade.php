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
        :options="['video','text','pdf']" />

    {{-- CONDITIONAL FIELDS --}}
    <div id="lessonVideoField" class="hidden">
        <x-input.content
            name="lessonVideo"
            label="Select video"
            type="video" />
    </div>

    <div id="lessonFileField" class="hidden">
        <x-input.content
            name="lessonFile"
            label="Select file (PDF)"
            type="pdf" />
    </div>

    <div id="lessonContentField" class="hidden">
        <x-input.textarea
            name="lessonContent"
            label="Lesson content"
            placeholder="Enter lesson content" />
    </div>

    <x-input.number
        name="lessonDuration"
        label="Duration (minutes)"
        placeholder="e.g. 10" />

    {{-- TOGGLES --}}
    <div class="grid grid-cols-2 gap-4 mt-2">
        <x-input.toggle name="lessonFree" label="Free lesson" :loading="false" value="1" />
        <x-input.toggle name="lessonMandatory" label="Mandatory" :loading="false" value="1" />
        <x-input.toggle name="lessonActive" label="Active" :value="1" :loading="false" />
    </div>

    <div class="flex gap-2 mt-4">
        <x-button id="saveLessonBtn" variant="primary" loadingText="Saving...">Save Lesson</x-button>
        <x-button id="cancelLessonBtn" variant="secondary" :loading="false">Cancel</x-button>
    </div>

</div>