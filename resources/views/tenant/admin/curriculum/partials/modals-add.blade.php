{{-- ======================================================== --}}
{{-- MODAL 1: ADD CATEGORY (WITH x-input.image COMPONENT)     --}}
{{-- ======================================================== --}}
<div x-show="categoryAddModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) categoryAddModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center gap-2 justify-between pb-3 mb-4">
            <h3 class="text-sm font-semibold text-theme-text">Create Academic Category</h3>
            <button type="button" @click="categoryAddModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.academic-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-input.image name="image" label="Cover Banner" />
            <x-input.text name="name" label="Category Name" required placeholder="e.g. Higher Secondary (11th-12th), Engineering, Medical" />
            <x-input.textarea name="description" label="Description" rows="3" placeholder="Brief description of this academic tier..." />
            <x-input.toggle name="status" label="Active Status" hint="Visible to students & admissions" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="categoryAddModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 2: ADD CLASS (WITH x-input.image COMPONENT)        --}}
{{-- ======================================================== --}}
<div x-show="classAddModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) classAddModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center justify-between pb-3 mb-4">
            <h3 class="text-sm font-semibold text-theme-text">Create Class / Program</h3>
            <button type="button" @click="classAddModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.classes.courses.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Context Information: Selected Parent Category --}}
            <div class="p-3 rounded-lg bg-secondary flex items-center justify-between text-xs">
                <div>
                    <span class="text-theme-text-muted text-[10px] block">Target Academic Category</span>
                    <span class="font-semibold text-theme-text" x-text="selectedCategory ? selectedCategory.name : 'No category selected'"></span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-normal border border-brand/20">
                    Selected Parent
                </span>
            </div>
            <input type="hidden" name="academic_category_id" :value="selectedCategoryId">

            <x-input.image name="image" label="Class Cover Banner" />
            <x-input.text name="name" label="Class / Course Name" required placeholder="e.g. Class 11th - Science, BCA 1st Year" />
            
            {{-- Class Teacher / Mentor (Optional) --}}
            <div>
                <label class="block text-xs font-semibold text-theme-text mb-1">
                    Class Teacher / Mentor <span class="text-theme-text-muted font-normal">(Optional)</span>
                </label>
                <select name="class_teacher_id"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full border border-theme-border/60 focus:border-brand focus:outline-none">
                    <option value="">-- No Class Teacher Assigned --</option>
                    <template x-for="t in teachers" :key="t.id">
                        <option :value="t.id" x-text="t.fname + ' ' + (t.lname || '') + ' (' + t.email + ')'"></option>
                    </template>
                </select>
                <p class="text-[10px] text-theme-text-muted mt-1">
                    Class Teacher acts as the overall mentor and coordinator for students in this class.
                </p>
            </div>

            <x-input.textarea name="description" label="Description" rows="3" placeholder="Academic curriculum focus and objectives..." />
            <x-input.toggle name="status" label="Active Status" hint="Active for student registrations" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="classAddModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Save Class
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 3: ADD SUBJECT (WITH FACULTY & OPTIONAL TIMINGS)   --}}
{{-- ======================================================== --}}
<div x-show="subjectAddModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) subjectAddModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center justify-between pb-3 mb-4">
            <h3 class="text-sm font-semibold text-theme-text">Create Subject</h3>
            <button type="button" @click="subjectAddModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Context Information: Selected Parent Class & Category --}}
            <div class="p-3 rounded-lg bg-secondary flex items-center justify-between text-xs">
                <div>
                    <span class="text-theme-text-muted text-[10px] block">Target Class / Course</span>
                    <span class="font-semibold text-theme-text" x-text="selectedClass ? selectedClass.name : 'No class selected'"></span>
                </div>
                <div class="text-right">
                    <span class="text-theme-text-muted text-[10px] block">Academic Category</span>
                    <span class="text-theme-text text-[11px]" x-text="selectedCategory ? selectedCategory.name : ''"></span>
                </div>
            </div>
            <input type="hidden" name="class_course_name" :value="selectedClass ? selectedClass.name : ''">

            <x-input.image name="image" label="Subject Cover Banner" />
            <x-input.text name="name" label="Subject Name" required placeholder="e.g. Physics, Chemistry, Mathematics" />

            {{-- Subject Faculty / Teacher (Optional) --}}
            <div>
                <label class="block text-xs font-semibold text-theme-text mb-1">
                    Subject Faculty / Teacher <span class="text-theme-text-muted font-normal">(Optional)</span>
                </label>
                <select name="teacher_id"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full border border-theme-border/60 focus:border-brand focus:outline-none">
                    <option value="">-- No Teacher Assigned --</option>
                    <template x-for="t in teachers" :key="t.id">
                        <option :value="t.id" x-text="t.fname + ' ' + (t.lname || '') + ' (' + t.email + ')'"></option>
                    </template>
                </select>
                <p class="text-[10px] text-theme-text-muted mt-1">
                    Assigned faculty member for teaching this subject module.
                </p>
            </div>

            {{-- Schedule Timings (Optional) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <x-input.text type="time" name="start_time" label="Start Time (Optional)" />
                <x-input.text type="time" name="end_time" label="End Time (Optional)" />
            </div>

            {{-- Interactive Schedule Days Selector (S M T W T F S) --}}
            <x-input.days-selector name="days" label="Schedule Days (Optional)" hint="Select lecture days" />

            <x-input.textarea name="description" label="Description" rows="3" placeholder="Key topics and subject curriculum details..." />
            <x-input.toggle name="status" label="Active Status" hint="Active for student enrollments" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="subjectAddModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Save Subject
                </button>
            </div>
        </form>
    </div>
</div>
