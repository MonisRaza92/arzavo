<div class="hidden fixed z-100 top-0 left-0 w-full h-full bg-black/90 flex justify-center items-center"
    id="courseAddPopup">
    <div class="bg-primary border-primary border-rounded overflow-auto scrollbar h-full md:h-11/12 w-full md:max-w-2xl">
        {{-- Header --}}
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-101">
            <h3 class="text-lg font-bold text-primary">
                <i class="fa-solid fa-book-open"></i> Upload Course
            </h3>
            <button onclick="document.getElementById('courseAddPopup').classList.add('hidden')"
                class="text-xl text-primary">
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
                            <x-input.select :options="$classes" optionLabel="name" optionValue="id" name="class_id" />

                        </div>


                        <div class="relative">

                            <label class="text-xs font-medium text-tertiary mb-1 block">
                                Subjects *
                            </label>
                            <script>
                                window.ALL_SUBJECTS = {!! json_encode(
    $subjects->map(function ($s) {
        return [
            'id' => $s->id,
            'name' => $s->name,
            'class_id' => $s->class_courses_id,
        ];
    })->values()->toArray()
) !!};
                            </script>


                            <x-input.multiselect :options="[]" optionLabel="name" optionValue="id" name="subjects" />
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
                            <input name="discount_price" type="number" min="0" step="0.01"
                                value="{{ old('discount_price') }}"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Duration (minutes)</label>
                            <input name="duration" type="number" min="1" value="{{ old('duration') }}"
                                placeholder="e.g. 120"
                                class="mt-1 w-full p-2 bg-primary border-primary border-rounded input-focus">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-tertiary">Max Students (*leave it blank for no
                                limit)</label>
                            <input name="max_students" type="number" min="1" value="{{ old('max_students') }}"
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

                                <img data-content-preview class="hidden w-full h-full object-contain border-rounded">

                                <div data-content-placeholder
                                    class="flex flex-col items-center text-tertiary h-full justify-center">
                                    <i class="fa-solid fa-image text-3xl mb-2"></i>
                                    Select Thumbnail
                                </div>
                                <div
                                    class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100">
                                    <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">Upload/Change
                                        image</span>
                                </div>
                            </div>
                        </div>

                        <div data-content-wrapper>
                            <input type="hidden" name="video" id="courseVideo">

                            <div onclick="openContentPicker('courseVideo', 'video')"
                                class="border-primary border-rounded bg-secondary p-4 aspect-video cursor-pointer relative overflow-hidden group">

                                <video data-content-preview muted preload="metadata"
                                    class="w-full object-cover hidden h-full" onmouseenter="this.play()"
                                    onmouseleave="this.pause(); this.currentTime = 0;">
                                </video>

                                <div data-content-placeholder
                                    class=" flex flex-col items-center justify-center text-center h-full text-tertiary">
                                    <i class="fa-solid fa-video text-3xl mb-2"></i>
                                    Select Video
                                </div>
                                <div
                                    class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100">
                                    <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">Upload/Change
                                        image</span>
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
                            <div
                                class="flex items-center justify-between p-3 border-primary border-rounded bg-hover-secondary">
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
                    <button onclick="document.getElementById('courseAddPopup').classList.add('hidden')" type="button"
                        class="bg-secondary border-rounded bg-hover-tertiary text-primary px-4 py-2 font-semibold">Cancel</button>
                    <button type="submit" class="font-semibold bg-invert text-invert px-4 py-2 border-rounded">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
<script>
    document.addEventListener('turbo:load', function () {

        const classSelect = document.querySelector('select[name="class_id"]');
        const subjectRoot = document.querySelector('[id^="ms_"]'); // multiselect root
        if (!classSelect || !subjectRoot) return;

        const dropdown = subjectRoot.querySelector('.ms-dropdown');
        const selectedBox = subjectRoot.querySelector('.ms-selected');
        const inputsBox = subjectRoot.nextElementSibling;

        function resetSubjects() {
            dropdown.innerHTML = '';
            updateSelectedPills();
            inputsBox.innerHTML = '';
        }

        function updateSelectedPills() {
            const selected = Array.from(inputsBox.querySelectorAll('input[name="subjects[]"]'))
                .map(input => {
                    const subject = window.ALL_SUBJECTS.find(s => s.id == input.value);
                    return subject ? { id: subject.id, name: subject.name } : null;
                })
                .filter(Boolean);

            if (selected.length === 0) {
                selectedBox.innerHTML = '<span class="text-base text-gray-400">Select subjects…</span>';
            } else {
                selectedBox.innerHTML = selected.map(subject => 
                    `<span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 border-rounded text-xs">
                        ${subject.name}
                        <button type="button" class="remove-pill ml-1 hover:text-blue-900 font-bold" data-subject-id="${subject.id}">×</button>
                    </span>`
                ).join('');

                // Add click listeners to remove buttons
                selectedBox.querySelectorAll('.remove-pill').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const subjectId = btn.dataset.subjectId;
                        const input = inputsBox.querySelector(`input[value="${subjectId}"]`);
                        if (input) input.remove();
                        const option = dropdown.querySelector(`[data-value="${subjectId}"] .check`);
                        if (option) option.classList.add('hidden');
                        updateSelectedPills();
                    });
                });
            }
        }

        classSelect.addEventListener('change', function () {
            const selectedClassId = this.value;

            resetSubjects();

            if (!selectedClassId) return;

            const filtered = window.ALL_SUBJECTS.filter(
                s => String(s.class_id) === String(selectedClassId)
            );

            if (filtered.length === 0) {
                dropdown.innerHTML =
                    '<div class="px-3 py-2 text-base text-gray-400">No subjects found</div>';
                return;
            }

            filtered.forEach(sub => {
                const div = document.createElement('div');
                div.className = 'ms-option px-3 py-2 cursor-pointer hover:bg-gray-100 flex justify-between';
                div.dataset.value = sub.id;
                div.dataset.label = sub.name;
                div.innerHTML = `
                <span>${sub.name}</span>
                <span class="check hidden">✔</span>
            `;

                div.addEventListener('click', () => {
                    const exists = inputsBox.querySelector(`input[value="${sub.id}"]`);

                    if (exists) {
                        exists.remove();
                        div.querySelector('.check').classList.add('hidden');
                    } else {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'subjects[]';
                        input.value = sub.id;
                        inputsBox.appendChild(input);

                        div.querySelector('.check').classList.remove('hidden');
                    }

                    updateSelectedPills();
                });

                dropdown.appendChild(div);
            });
        });

    });
</script>