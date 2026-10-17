{{-- ======================================================== --}}
{{-- MODAL 6: EDIT CATEGORY MODAL                             --}}
{{-- ======================================================== --}}
<div x-show="categoryEditModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) categoryEditModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-theme-border/30">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <h3 class="text-sm font-semibold text-theme-text">Edit Academic Category</h3>
            </div>
            <button type="button" @click="categoryEditModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form :action="'{{ url('admin/academic-categories') }}/' + editingCategory.id + '/update'" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input.image id="edit_cat_img" name="image" label="Cover Banner" />
            <x-input.text id="edit_cat_name" name="name" label="Category Name" required x-model="editingCategory.name" placeholder="e.g. Higher Secondary (11th-12th)" />
            <x-input.textarea id="edit_cat_desc" name="description" label="Description" rows="3" x-model="editingCategory.description" placeholder="Brief description of this academic tier..." />
            <x-input.toggle id="edit_cat_status" name="status" label="Active Status" hint="Visible to students & admissions" x-model="editingCategory.status" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-theme-border/30">
                <button type="button" @click="categoryEditModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 7: EDIT CLASS MODAL                                --}}
{{-- ======================================================== --}}
<div x-show="classEditModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) classEditModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-theme-border/30">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <h3 class="text-sm font-semibold text-theme-text">Edit Class / Program</h3>
            </div>
            <button type="button" @click="classEditModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form :action="'{{ url('admin/classes/courses') }}/' + editingClass.id + '/update'" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Parent Category hidden binding --}}
            <input type="hidden" name="academic_category_id" :value="editingClass.academic_category_id">

            {{-- Context Information: Selected Parent Category --}}
            <div class="p-3 rounded-lg bg-secondary flex items-center justify-between text-xs border border-theme-border/40">
                <div>
                    <span class="text-theme-text-muted block text-[10px]">Academic Category Tier</span>
                    <span class="font-semibold text-theme-text" x-text="selectedCategory ? selectedCategory.name : 'Category Tier'"></span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal">Parent</span>
            </div>

            <x-input.image id="edit_cls_img" name="image" label="Class Cover Banner" />
            <x-input.text id="edit_cls_name" name="name" label="Class / Course Name" required x-model="editingClass.name" placeholder="e.g. Class 11th - Science (Pre-Med)" />

            {{-- Class Teacher / Mentor (Optional) --}}
            <div>
                <label class="block text-xs font-semibold text-theme-text mb-1">
                    Class Teacher / Mentor <span class="text-theme-text-muted font-normal">(Optional)</span>
                </label>
                <select name="class_teacher_id" x-model="editingClass.class_teacher_id"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full border border-theme-border/60 focus:border-brand focus:outline-none">
                    <option value="">-- No Class Teacher Assigned --</option>
                    <template x-for="t in teachers" :key="t.id">
                        <option :value="t.id" :selected="editingClass.class_teacher_id == t.id" x-text="t.fname + ' ' + (t.lname || '') + ' (' + t.email + ')'"></option>
                    </template>
                </select>
                <p class="text-[10px] text-theme-text-muted mt-1">
                    Class Teacher acts as the overall mentor and coordinator for students in this class.
                </p>
            </div>

            <x-input.textarea id="edit_cls_desc" name="description" label="Description" rows="3" x-model="editingClass.description" placeholder="Curriculum overview and objectives..." />
            <x-input.toggle id="edit_cls_status" name="status" label="Active Status" hint="Open for curriculum modules" x-model="editingClass.status" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-theme-border/30">
                <button type="button" @click="classEditModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 8: EDIT SUBJECT MODAL (WITH FACULTY & TIMINGS)     --}}
{{-- ======================================================== --}}
<div x-show="subjectEditModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="if (typeof window.isContentPickerActive !== 'function' || !window.isContentPickerActive()) subjectEditModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-5 z-10 max-h-[90vh] overflow-y-auto scrollbar"
         @click.stop>
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-theme-border/30">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <h3 class="text-sm font-semibold text-theme-text">Edit Subject</h3>
            </div>
            <button type="button" @click="subjectEditModalOpen = false" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form :action="'{{ url('admin/subjects') }}/' + editingSubject.id + '/update'" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Parent Class hidden binding --}}
            <input type="hidden" name="class_course_id" :value="editingSubject.class_courses_id">

            {{-- Context Information: Selected Parent Class --}}
            <div class="p-3 rounded-lg bg-secondary flex items-center justify-between text-xs border border-theme-border/40">
                <div>
                    <span class="text-theme-text-muted block text-[10px]">Belongs To Class</span>
                    <span class="font-semibold text-theme-text" x-text="selectedClass ? selectedClass.name : 'Selected Class'"></span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal">Parent</span>
            </div>

            <x-input.image id="edit_sub_img" name="image" label="Subject Cover Banner" />
            <x-input.text id="edit_sub_name" name="name" label="Subject Name" required x-model="editingSubject.name" placeholder="e.g. Physics Core, Advanced Mathematics" />

            {{-- Subject Faculty / Teacher (Optional) --}}
            <div>
                <label class="block text-xs font-semibold text-theme-text mb-1">
                    Subject Faculty / Teacher <span class="text-theme-text-muted font-normal">(Optional)</span>
                </label>
                <select name="teacher_id" x-model="editingSubject.teacher_id"
                        class="w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full border border-theme-border/60 focus:border-brand focus:outline-none">
                    <option value="">-- No Teacher Assigned --</option>
                    <template x-for="t in teachers" :key="t.id">
                        <option :value="t.id" :selected="editingSubject.teacher_id == t.id" x-text="t.fname + ' ' + (t.lname || '') + ' (' + t.email + ')'"></option>
                    </template>
                </select>
                <p class="text-[10px] text-theme-text-muted mt-1">
                    Assigned faculty member for teaching this subject module.
                </p>
            </div>

            {{-- Schedule Timings (Optional) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <x-input.text id="edit_sub_start" type="time" name="start_time" label="Start Time (Optional)" x-model="editingSubject.start_time" />
                <x-input.text id="edit_sub_end" type="time" name="end_time" label="End Time (Optional)" x-model="editingSubject.end_time" />
            </div>

            {{-- Interactive Schedule Days Selector (S M T W T F S) --}}
            <x-input.days-selector id="edit_sub_days" name="days" label="Schedule Days (Optional)" hint="Select lecture days" x-model="editingSubject.days" />

            <x-input.textarea id="edit_sub_desc" name="description" label="Description" rows="3" x-model="editingSubject.description" placeholder="Subject syllabus, units and topics..." />
            <x-input.toggle id="edit_sub_status" name="status" label="Active Status" hint="Available for students & curriculum" x-model="editingSubject.status" :value="true" />

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-theme-border/30">
                <button type="button" @click="subjectEditModalOpen = false"
                        class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm">
                    Update Subject
                </button>
            </div>
        </form>
    </div>
</div>
