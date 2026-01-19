<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-secondary">Course Structure</h3>
        <button @click="openAddModuleModal()" class="btn-primary">
            <i class="fa-solid fa-plus mr-1"></i> Add Section
        </button>
    </div>

    {{-- MODULES LIST --}}
    <div id="modules-container" class="space-y-4">
        @forelse($course->modules as $module)
        <div class="bg-secondary border-primary border-rounded p-4" data-module-id="{{ $module->id }}">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-grip-lines text-tertiary cursor-move"></i>
                    <h4 class="font-bold text-lg text-primary">{{ $module->title }}</h4>
                    <span class="text-xs text-tertiary bg-primary px-2 py-1 rounded">Section</span>
                </div>
                <div class="flex gap-2">
                    <button @click="editModule({{ $module->id }}, '{{ addslashes($module->title) }}')" class="text-tertiary hover:text-accent">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button @click="deleteModule({{ $module->id }})" class="text-tertiary hover:text-red-500">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>

            {{-- LESSONS LIST --}}
            <div class="pl-8 space-y-2">
                @forelse($module->lessons as $lesson)
                <div class="bg-primary border-primary border-rounded p-3 flex justify-between items-center" data-lesson-id="{{ $lesson->id }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-grip-lines-vertical text-tertiary cursor-move"></i>
                        <div class="flex flex-col">
                            <span class="font-medium text-primary">{{ $lesson->title }}</span>
                            <span class="text-xs text-tertiary uppercase">{{ $lesson->type }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="editLesson({{ $lesson }})" class="text-tertiary hover:text-accent"><i class="fa-solid fa-pen"></i></button>
                        <button @click="deleteLesson({{ $lesson->id }})" class="text-tertiary hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
                @empty
                <div class="text-sm text-tertiary italic p-2">No lessons in this section.</div>
                @endforelse

                {{-- Add Lesson Button --}}
                <button @click="openAddLessonModal({{ $module->id }})" class="w-full py-2 border-2 border-dashed border-primary text-tertiary hover:border-accent hover:text-accent rounded-md transition-colors text-sm mt-2">
                    <i class="fa-solid fa-plus mr-1"></i> Add Lesson
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-10 bg-secondary border-rounded">
            <i class="fa-solid fa-layer-group text-4xl text-tertiary mb-3"></i>
            <p class="text-tertiary">No sections created yet.</p>
            <button @click="openAddModuleModal()" class="text-accent hover:underline mt-2">Create your first section</button>
        </div>
        @endforelse
    </div>

    {{-- MODALS (Simple Implementation) --}}

    <!-- Add/Edit Module Modal -->
    <div x-show="showModuleModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-primary p-6 rounded-lg w-full max-w-md shadow-lg" @click.away="showModuleModal = false">
            <h3 class="text-lg font-bold mb-4" x-text="moduleMode === 'add' ? 'Add Section' : 'Edit Section'"></h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Section Title</label>
                    <x-input.text name="module_title" x-model="moduleForm.title" />
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="showModuleModal = false" class="btn-secondary">Cancel</button>
                    <button @click="submitModule()" class="btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Lesson Modal -->
    <div x-show="showLessonModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-primary p-6 rounded-lg w-full max-w-lg shadow-lg max-h-[90vh] overflow-y-auto" @click.away="showLessonModal = false">
            <h3 class="text-lg font-bold mb-4" x-text="lessonMode === 'add' ? 'Add Lesson' : 'Edit Lesson'"></h3>
            <div class="space-y-4">

                {{-- TITLE --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Lesson Title</label>
                    <x-input.text name="lesson_title" x-model="lessonForm.title" />
                </div>

                {{-- TYPE (only editable in Add mode for simplicity) --}}
                <div x-show="lessonMode === 'add'">
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <x-input.select name="lesson_type" x-model="lessonForm.type">
                        <option value="video">Video Lesson</option>
                        <option value="text">Article / Text</option>
                        {{-- <option value="document">PDF / Document</option> --}}
                        {{-- <option value="quiz">Quiz</option> --}}
                    </x-input.select>
                </div>

                {{-- DYNAMIC FIELDS --}}

                {{-- 1. VIDEO FIELDS --}}
                <div x-show="lessonForm.type === 'video'" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Video URL (YouTube/Vimeo)</label>
                        <x-input.url name="video_path" x-model="lessonForm.video_path" placeholder="https://youtube.com/..." />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Duration (minutes)</label>
                        <x-input.number name="duration" x-model="lessonForm.duration" placeholder="e.g. 10" />
                    </div>
                </div>

                {{-- 2. TEXT FIELDS --}}
                <div x-show="lessonForm.type === 'text'" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Content</label>
                        <textarea x-model="lessonForm.content" class="w-full p-2 bg-primary border-primary border-rounded h-32" placeholder="Write lesson content here..."></textarea>
                    </div>
                </div>

                {{-- COMMON TOGGLE --}}
                <div class="flex items-center gap-2 mt-2">
                    <x-input.toggle name="is_free" label="Free Preview" x-model="lessonForm.is_free" />
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-primary mt-4">
                    <button @click="showLessonModal = false" class="btn-secondary">Cancel</button>
                    <button @click="submitLesson()" class="btn-primary">
                        <span x-text="lessonMode === 'add' ? 'Create Lesson' : 'Save Changes'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>