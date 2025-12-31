<div class="hidden fixed z-100 top-0 left-0 w-full h-full bg-black/80 flex justify-center items-center" id="courseAddPopup">
    <div class="bg-primary border-primary border-rounded overflow-auto scrollbar h-11/12 max-w-2xl">
        {{-- Header --}}
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-101">
            <h3 class="text-lg font-bold text-primary">
                <i class="fa-solid fa-book-open"></i> Upload Course
            </h3>
            <button onclick="document.getElementById('courseAddPopup').classList.add('hidden')" class="text-xl text-primary">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.courses.store') }}" method="post">
            @csrf
            <div class="p-4">
                {{-- BASIC INFO --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-secondary mb-3 flex items-center gap-2">
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs font-medium text-tertiary">Title *</label>
                            <input name="title" required value="{{ old('title') }}" placeholder="e.g. NEET, IIT-JEE"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Language *</label>
                            <select name="language"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                                <option value="English">English</option>
                                <option value="Hindi" selected>Hindi</option>
                            </select>
                        </div>

                        <div class="relative">

                            <label class="text-xs font-medium text-tertiary mb-1 block">
                                Classes *
                            </label>

                            <div class="flex flex-wrap items-center gap-2 p-2.5 border-primary border-rounded bg-primary cursor-text"
                                onclick="toggleDropdown('classDropdown')">

                                <div id="classSelected" class="flex flex-wrap gap-2"></div>

                                <span class="text-sm text-tertiary">Select classes</span>
                            </div>

                            <div id="classHiddenInputs"></div>

                            <div id="classDropdown"
                                class="hidden absolute z-40 mt-1 w-full bg-primary border-primary border-rounded max-h-64 overflow-auto">

                                @forelse($classes as $class)
                                <div
                                    class="px-3 py-2 cursor-pointer hover-primary text-sm"
                                    onclick="selectItem('class', {{ $class->id }}, '{{ $class->name }}')">
                                    {{ $class->name }}
                                </div>
                                @empty
                                <div class="px-3 py-2 text-sm text-tertiary">No classes available</div>
                                @endforelse

                            </div>
                        </div>


                        <div class="relative">

                            <label class="text-xs font-medium text-tertiary mb-1 block">
                                Subjects *
                            </label>

                            {{-- Selected tags + input --}}
                            <div class="flex flex-wrap items-center gap-2 p-2.5 border-primary border-rounded bg-primary cursor-text"
                                onclick="toggleDropdown('subjectDropdown')">

                                <div id="subjectSelected" class="flex flex-wrap gap-2"></div>

                                <span class="text-sm text-tertiary">Select subjects</span>
                            </div>

                            {{-- Hidden real input --}}
                            <div id="subjectHiddenInputs"></div>

                            {{-- Dropdown --}}
                            <div id="subjectDropdown"
                                class="hidden absolute z-40 mt-1 w-full bg-primary border-primary border-rounded max-h-64 overflow-auto">

                                @forelse($subjects as $subject)
                                <div
                                    class="px-3 py-2 cursor-pointer hover-primary text-sm subject-option"
                                    data-class-id="{{ $subject->class_courses_id }}"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}"
                                    onclick="selectSubjectFromDropdown(this)">
                                    {{ $subject->name }}
                                </div>
                                @empty
                                <div class="px-3 py-2 text-sm text-tertiary">No subjects available</div>
                                @endforelse

                            </div>
                        </div>


                    </div>
                </div>

                {{-- PRICING --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-secondary mb-3 flex items-center gap-2">
                        Pricing & Limits
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        <div>
                            <label class="text-xs font-medium text-tertiary">Price (₹) *leave it blank for free</label>
                            <input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Discount Price (if price available)</label>
                            <input name="discount_price" type="number" min="0" step="0.01" value="{{ old('discount_price') }}"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Duration (minutes)</label>
                            <input name="duration" type="number" min="1"value="{{ old('duration') }}" placeholder="e.g. 120"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Max Students (*leave it blank for no limit)</label>
                            <input name="max_students" type="number" min="1"value="{{ old('max_students') }}"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                    </div>
                </div>

                {{-- MEDIA --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-secondary mb-3 flex items-center gap-2">
                        Media
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        <div data-content-wrapper>
                            <input type="hidden" name="thumbnail" id="courseThumbnail">

                            <div class="border-primary border-rounded bg-secondary p-3 aspect-video cursor-pointer group relative overflow-hidden"
                                onclick="openContentPicker('courseThumbnail', 'image')">

                                <img data-content-preview
                                    class="hidden w-full h-full object-contain border-rounded">

                                <div data-content-placeholder
                                    class="flex flex-col items-center text-tertiary h-full justify-center">
                                    <i class="fa-solid fa-image text-3xl mb-2"></i>
                                    Select Thumbnail
                                </div>
                                <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100">
                                    <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">Upload/Change image</span>
                                </div>
                            </div>
                        </div>

                        <div data-content-wrapper>
                            <input type="hidden" name="video" id="courseVideo">

                            <div onclick="openContentPicker('courseVideo', 'video')"
                                class="border-primary border-rounded bg-secondary p-4 aspect-video cursor-pointer relative overflow-hidden group">

                                <video data-content-preview
                                    muted
                                    preload="metadata"
                                    class="w-full object-cover hidden h-full"
                                    onmouseenter="this.play()"
                                    onmouseleave="this.pause(); this.currentTime = 0;">
                                </video>

                                <div data-content-placeholder class=" flex flex-col items-center justify-center text-center h-full text-tertiary">
                                    <i class="fa-solid fa-video text-3xl mb-2"></i>
                                    Select Video
                                </div>
                                <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100">
                                    <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">Upload/Change image</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-secondary mb-3 flex items-center gap-2">
                        Description
                    </h3>

                    <textarea name="description" rows="4"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus">{{ old('description') }}</textarea>
                </div>

                {{-- FEATURES --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-secondary mb-3 flex items-center gap-2">
                        Course Features
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        @php
                        $toggles = [
                        'is_public' => 'Public Course',
                        'requires_enrollment' => 'Requires Enrollment',
                        'enable_modules' => 'Enable Groups',
                        'enable_lessons' => 'Enable Lessons',
                        'enable_quizzes' => 'Enable Quizzes',
                        'enable_assignments' => 'Enable Assignments',
                        'enable_certificates' => 'Enable Certificates',
                        'enable_reviews' => 'Enable Reviews',
                        ];
                        $defaultEnabled = ['is_public', 'enable_reviews'];
                        @endphp

                        @foreach($toggles as $name => $label)
                        <div class="flex items-center justify-between p-3 border-primary border-rounded bg-hover-secondary">
                            <span class="text-sm text-tertiary">{{ $label }}</span>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="hidden" name="{{ $name }}" value="0">
                                <input type="checkbox" name="{{ $name }}" value="1" class="sr-only peer" {{ in_array($name, $defaultEnabled) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-black/20 rounded-full peer-checked:bg-black"></div>
                            </label>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>
            {{-- SUBMIT --}}
            <div class="p-4 border-top flex justify-between items-center">
                <p class="text-xs text-tertiary">
                    Fields with * are required
                </p>

                <div class="flex gap-2">
                    <button onclick="document.getElementById('courseAddPopup').classList.add('hidden')" type="button" class="bg-secondary border-rounded bg-hover-tertiary text-primary px-4 py-2 font-semibold">Cancel</button>
                    <button type="submit"
                        class="font-semibold bg-invert text-invert px-4 py-2 border-rounded">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
<script>
    let selectedClasses = new Set();

    /* -----------------------------
       DROPDOWN TOGGLE
    --------------------------------*/
    function toggleDropdown(id) {
        document.getElementById(id)?.classList.toggle('hidden');
    }

    /* -----------------------------
       CLASS SELECT
    --------------------------------*/
    function selectItem(type, id, name) {

        if (type === 'class') {
            if (selectedClasses.has(id)) return;

            selectedClasses.add(id);
            renderChip(type, id, name);
            filterSubjects();

            // close class dropdown after select
            document.getElementById('classDropdown')?.classList.add('hidden');
        }
    }

    /* -----------------------------
       SUBJECT SELECT
    --------------------------------*/
    function selectSubjectFromDropdown(el) {

        const subjectId = parseInt(el.dataset.id);
        const subjectName = el.dataset.name;
        const classId = parseInt(el.dataset.classId);

        if (!selectedClasses.has(classId)) {
            alert('Please select the related class first');
            return;
        }

        if (document.getElementById('subject-' + subjectId)) return;

        renderChip('subject', subjectId, subjectName);

        // close subject dropdown after select
        document.getElementById('subjectDropdown')?.classList.add('hidden');
    }

    /* -----------------------------
       CHIP RENDER
    --------------------------------*/
    function renderChip(type, id, name) {

        const selectedBox = document.getElementById(type + 'Selected');
        const hiddenBox = document.getElementById(type + 'HiddenInputs');

        const chip = document.createElement('div');
        chip.id = type + '-' + id;
        chip.className =
            "flex items-center gap-1 px-2 py-1 text-xs border-primary border-rounded bg-hover-secondary";

        chip.innerHTML = `
        <span>${name}</span>
        <button type="button"
            onclick="removeItem('${type}', ${id})"
            class="text-tertiary text-hover-primary">&times;</button>
    `;

        selectedBox.appendChild(chip);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = type === 'subject' ? 'subjects[]' : 'classes[]';
        input.value = id;
        input.id = type + '-input-' + id;

        hiddenBox.appendChild(input);
    }

    /* -----------------------------
       REMOVE ITEM
    --------------------------------*/
    function removeItem(type, id) {

        document.getElementById(type + '-' + id)?.remove();
        document.getElementById(type + '-input-' + id)?.remove();

        if (type === 'class') {
            selectedClasses.delete(id);
            filterSubjects();
        }
    }

    /* -----------------------------
       SUBJECT FILTERING
    --------------------------------*/
    function filterSubjects() {

        document.querySelectorAll('.subject-option').forEach(el => {
            const classId = parseInt(el.dataset.classId);
            const subjectId = el.dataset.id;

            if (selectedClasses.has(classId)) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');

                // remove invalid selected subjects
                document.getElementById('subject-' + subjectId)?.remove();
                document.getElementById('subject-input-' + subjectId)?.remove();
            }
        });
    }

    /* -----------------------------
       CLOSE DROPDOWN ON OUTSIDE CLICK
    --------------------------------*/
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.getElementById('classDropdown')?.classList.add('hidden');
            document.getElementById('subjectDropdown')?.classList.add('hidden');
        }
    });
</script>