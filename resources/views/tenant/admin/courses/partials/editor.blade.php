<div id="editor" class="tab-content hidden space-y-4" tab-content="editor">

    {{-- PAGE HEADER --}}
    <div class="bg-primary border-rounded flex items-center justify-between border-primary p-4">
        <div>
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-base"></i>
                Course Editor
            </h2>
            <p class="text-sm text-tertiary">
                Edit course content, structure, and settings from here.
            </p>
        </div>
        <button type="button" class="bg-invert text-invert font-semibold px-4 py-2 border-primary border-rounded">Create New Course</button>
    </div>
    <form action="{{ route('admin.courses.update', $course) }}"
        method="POST"
        id="courseUpdateForm"
        class="space-y-6">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- LEFT: CONTENT --}}
            <div class="lg:col-span-2 space-y-4">


                {{-- MEDIA --}}
                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-photo-film text-sm mr-1"></i> Media</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input.image name="thumbnail" :value="$course->thumbnail" label="Course thumbnail" />
                        <x-input.video name="video" :value="$course->video" label="Intro video (if lessons available)" />
                    </div>
                </div>

                {{-- BASIC INFO --}}
                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-align-left text-sm mr-1"></i> Basic Information</h3>

                    <x-input.text name="title" :value="$course->title" label="Course title" />
                    <x-input.textarea name="description" :value="$course->description" label="Course description" />
                </div>

                {{-- ACADEMICS --}}
                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-graduation-cap text-sm mr-1"></i> Academic Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input.select
                            name="class"
                            label="Classes"
                            :options="$classes"
                            :value="$course->class" />

                        <x-input.select
                            name="subjects"
                            label="Subjects"
                            :options="$subjects"
                            :value="$course->subjects" />
                    </div>
                </div>

                {{-- META --}}
                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-circle-info text-sm mr-1"></i> Course Meta</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input.select name="language" :value="$course->language"
                            label="Language" :options="['english','hindi']" />

                        <x-input.select name="level" :value="$course->level"
                            label="Level" :options="['beginner','intermediate','advanced']" />

                        <x-input.number name="duration" :value="$course->duration"
                            label="Duration" placeholder="e.g. 10 hours" />

                        <x-input.number name="max_students" :value="$course->max_students"
                            label="Max students (optional)" />
                    </div>
                </div>

            </div>

            {{-- RIGHT: SETTINGS --}}
            <div class="space-y-4">
                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-cog text-sm mr-1"></i> Course Settings</h3>
                    <div class="space-y-3">

                        <x-input.toggle
                            name="is_public"
                            :value="$course->is_public"
                            label="Make Course Public"
                            hint="Leave it off for only students enrolled in the course" />

                        <x-input.toggle
                            name="requires_enrollment"
                            :value="$course->requires_enrollment"
                            label="Require enrollment" />

                    </div>
                </div>

                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-sliders text-sm mr-1"></i> Enable Features</h3>

                    <div class="space-y-3">
                        <x-input.toggle name="enable_modules" :value="$course->enable_modules" label="Allow Modules" />
                        <x-input.toggle name="enable_lessons" :value="$course->enable_lessons" label="Allow Lessons" />
                        <x-input.toggle name="enable_quizzes" :value="$course->enable_quizzes" label="Allow Quizzes" />
                        <x-input.toggle name="enable_assignments" :value="$course->enable_assignments" label="Allow Assignments" />
                        <x-input.toggle name="enable_certificates" :value="$course->enable_certificates" label="Allow Certificates" />
                        <x-input.toggle name="enable_reviews" :value="$course->enable_reviews" label="Allow Reviews" />
                        <x-input.toggle name="allow_download" label="Allow Download" hint="Allow people to download course files and videos" />
                    </div>
                </div>

                <div class="p-4 bg-primary border-primary border-rounded">
                    <h3 class="font-medium mb-4"><i class="fa-solid fa-sliders text-sm mr-1"></i> Course Pricing</h3>

                    <div class="space-y-3">
                        <x-input.toggle name="is_paid" :value="$course->is_paid" label="Paid Course" hint="Turn off to make this course free" />
                        <x-input.number name="price" :value="$course->price" label="Price" />
                        <x-input.number name="discount_price" :value="$course->discount_price" label="Discount Price" />
                    </div>
                </div>

            </div>

        </div>
    </form>
    <div class="course-meterial p-4 bg-primary border-primary border-rounded flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-xl"><i class="fa-solid fa-list-check text-base mr-1"></i> Course Material</h3>
            <p class="text-sm text-tertiary">Here you can add course groups and their lessons like videos, documents, etc.</p>
        </div>
        <div class="div">
            @if ($course->enable_modules)
            <button type="button" class="bg-invert text-invert font-semibold px-4 py-2 border-primary border-rounded addModuleBtn">Add Group</button>
            @endif
            @if ($course->enable_lessons)
            <button type="button" class="bg-invert text-invert font-semibold px-4 py-2 border-primary border-rounded addLessonBtn">Add Lesson</button>
            @endif
        </div>
    </div>



    <div class="space-y-4" id="modulesContainer">
        @foreach($course->modules as $module)
        @include('tenant.admin.courses.partials.module-card', ['module' => $module, 'course' => $course])
        @endforeach
    </div>

    <div id="lessonsContainer" class="space-y-4">
        @foreach($course->directLessons as $lesson)
        @include('tenant.admin.courses.partials.lesson-card', ['lesson' => $lesson])
        @endforeach
    </div>



    @include('tenant.admin.courses.partials.add-module')
    @include('tenant.admin.courses.partials.add-lesson')


    <div class="add-btn flex gap-4">
        @if ($course->enable_modules)
        <div class="bg-primary p-4 border-primary border-rounded w-full">
            <button type="button" class="text-secondary font-semibold border-rounded p-4 w-full border-dashed addModuleBtn">Add Group <i class="fa-solid fa-plus text-xs ml-1"></i></button>
        </div>
        @endif
        @if ($course->enable_lessons)
        <div class="bg-primary p-4 border-primary border-rounded w-full">
            <button type="button" class="text-secondary font-semibold border-rounded p-4 w-full border-dashed addLessonBtn">Add Lesson <i class="fa-solid fa-plus text-xs ml-1"></i></button>
        </div>
        @endif
    </div>
</div>

<script>
    const addBtn = document.querySelectorAll('.addModuleBtn');
    const form = document.getElementById('addModuleForm');
    const saveBtn = document.getElementById('saveModuleBtn');
    const cancelBtn = document.getElementById('cancelModuleBtn');
    const container = document.getElementById('modulesContainer');

    addBtn.forEach(btn => {
        btn.onclick = () => {
            form.classList.remove('hidden');
        };
    });

    cancelBtn.onclick = () => {
        form.classList.add('hidden');
    };

    saveBtn.onclick = async () => {
        const title = document.getElementById('moduleTitle').value;
        const description = document.getElementById('moduleDescription').value;

        if (!title.trim()) {
            alert('Module title required');
            return;
        }

        const res = await fetch(
            "{{ route('admin.courses.modules.store', $course) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'text/html'
                },
                body: new URLSearchParams({
                    title,
                    description
                })
            }
        );

        const html = await res.text();

        container.insertAdjacentHTML('beforeend', html);

        window.dispatchEvent(new Event('button-reset'));
        // Reset & hide
        form.classList.add('hidden');
        document.getElementById('moduleTitle').value = '';
        document.getElementById('moduleDescription').value = '';
    };


    const addLessonBtn = document.querySelectorAll('.addLessonBtn');
    const lessonForm = document.getElementById('addLessonForm');
    const saveLessonBtn = document.getElementById('saveLessonBtn');
    const cancelLessonBtn = document.getElementById('cancelLessonBtn');
    const lessonsContainer = document.getElementById('lessonsContainer');

    addLessonBtn.forEach(btn => {
        btn.onclick = () => {
            lessonForm.classList.remove('hidden');
        };
    });

    cancelLessonBtn.onclick = () => {
        lessonForm.classList.add('hidden');
    };

    saveLessonBtn.onclick = async () => {

        const formData = new FormData();

        formData.append('title', lessonTitle.value);
        formData.append('description', lessonDescription.value);
        formData.append('type', lessonType.value);
        formData.append('duration', lessonDuration.value);
        formData.append('video_path', lessonVideo?.value ?? '');
        formData.append('content', lessonContent?.value ?? '');
        formData.append('is_free', lessonFree?.checked ? 1 : 0);
        formData.append('is_mandatory', lessonMandatory?.checked ? 1 : 0);
        formData.append('is_active', lessonActive?.checked ? 1 : 0);

        if (lessonFile?.files[0]) {
            formData.append('file', lessonFile.files[0]);
        }

        const res = await fetch(
            "{{ route('admin.courses.lessons.store', $course) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            }
        );

        const html = await res.text();
        lessonsContainer.insertAdjacentHTML('beforeend', html);

        window.dispatchEvent(new Event('button-reset'));
        addLessonForm.classList.add('hidden');
    };


    async function deleteLesson(lessonId) {
        if (!confirm('Delete this lesson?')) return;

        const res = await fetch(
            `/admin/courses/{{ $course->slug }}/lessons/${lessonId}`, {
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



    function submitCourseUpdateForm() {
        const form = document.getElementById('courseUpdateForm');
        const formData = new FormData(form);
        if (formData.get('title') == '') {
            showToast('Please enter course title', 'error');
            return;
        }
        if (formData.get('description') == '') {
            showToast('Please enter course description', 'error');
            return;
        }
        if (formData.get('language') == '') {
            showToast('Please select course language', 'error');
            return;
        }
        if (formData.get('level') == '') {
            showToast('Please select course level', 'error');
            return;
        }
        if (formData.get('duration') == '') {
            showToast('Please enter course duration', 'error');
            return;
        }
        if (form) {
            form.submit();
        }
    }
</script>