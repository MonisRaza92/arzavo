{{-- ======================================================== --}}
{{-- 4. CREATE / EDIT MASTER FEE PLAN MODAL                    --}}
{{-- ======================================================== --}}
<div x-cloak
     x-show="createPlanModalOpen"
     @keydown.escape.window="createPlanModalOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div @click.outside="createPlanModalOpen = false"
         class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-5 bg-primary flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-base text-theme-text" x-text="editingPlanId ? 'Edit Master Fee Plan' : 'Create Master Fee Plan'"></h3>
                    <p class="text-xs text-theme-text-muted font-normal">Define recurring tuition rates at Category, Class, Subject or Batch tier</p>
                </div>
            </div>
            <button type="button" @click="createPlanModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <form :action="editingPlanId ? ('/admin/students/fee-plans/update/' + editingPlanId) : '{{ route('admin.students.fee-plans.store') }}'"
              method="POST"
              class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
            @csrf

            {{-- 1. Scope Level Selector (Category | Class | Subject | Batch) --}}
            <div>
                <label class="block text-xs font-normal text-theme-text mb-1.5">Hierarchy Scope Level <span class="text-danger font-semibold">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button"
                            @click="planForm.scope = 'category'; onScopeChange()"
                            class="px-3 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="planForm.scope === 'category' ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        1. Category
                    </button>
                    <button type="button"
                            @click="planForm.scope = 'class'; onScopeChange()"
                            class="px-3 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="planForm.scope === 'class' ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        2. Class
                    </button>
                    <button type="button"
                            @click="planForm.scope = 'subject'; onScopeChange()"
                            class="px-3 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="planForm.scope === 'subject' ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        3. Subject
                    </button>
                    <button type="button"
                            @click="planForm.scope = 'batch'; onScopeChange()"
                            class="px-3 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="planForm.scope === 'batch' ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        4. Batch
                    </button>
                </div>
                <input type="hidden" name="scope" :value="planForm.scope">
            </div>

            {{-- Explanatory Hint for selected scope --}}
            <div class="p-3 bg-secondary rounded-lg text-[11px] text-theme-text-muted font-normal leading-relaxed">
                <template x-if="planForm.scope === 'category'">
                    <span>Applied to <strong>all students in the selected category</strong>. Classes or batches with their own rates will override this.</span>
                </template>
                <template x-if="planForm.scope === 'class'">
                    <span>Applied to <strong>all students in the selected class</strong>, overriding the category rate for this class only.</span>
                </template>
                <template x-if="planForm.scope === 'subject'">
                    <span>Applied to students taking <strong>this specific subject</strong>, overriding the class rate.</span>
                </template>
                <template x-if="planForm.scope === 'batch'">
                    <span>Applied to students assigned to <strong>this specific batch</strong>, overriding subject and class rates.</span>
                </template>
            </div>

            {{-- 2. Scope Target Selectors --}}
            <div class="space-y-3">
                {{-- Category Selector (Always visible) --}}
                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Academic Category <span class="text-danger font-semibold">*</span></label>
                    <select name="academic_category_id"
                            x-model="planForm.category_id"
                            @change="onModalCategoryChange()"
                            required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                        <option value="">Select Category</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name" :selected="cat.id == planForm.category_id"></option>
                        </template>
                    </select>
                </div>

                {{-- Class Selector (Visible for Class, Subject, Batch) --}}
                <div x-show="planForm.scope === 'class' || planForm.scope === 'subject' || planForm.scope === 'batch'">
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Class / Course <span class="text-danger font-semibold">*</span></label>
                    <select name="class_id"
                            x-model="planForm.class_id"
                            @change="onModalClassChange()"
                            :disabled="!modalClasses.length"
                            :required="planForm.scope === 'class' || planForm.scope === 'subject' || planForm.scope === 'batch'"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                        <option value="" x-text="modalClasses.length ? 'Select Class' : 'Select Category first'"></option>
                        <template x-for="cls in modalClasses" :key="cls.id">
                            <option :value="cls.id" x-text="cls.name" :selected="cls.id == planForm.class_id"></option>
                        </template>
                    </select>
                </div>

                {{-- Subject Selector (Visible for Subject, Batch) --}}
                <div x-show="planForm.scope === 'subject' || planForm.scope === 'batch'">
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Subject <span class="text-danger font-semibold">*</span></label>
                    <select name="subject_id"
                            x-model="planForm.subject_id"
                            @change="onModalSubjectChange()"
                            :disabled="!modalSubjects.length"
                            :required="planForm.scope === 'subject' || planForm.scope === 'batch'"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                        <option value="" x-text="modalSubjects.length ? 'Select Subject' : 'Select Class first'"></option>
                        <template x-for="sub in modalSubjects" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name" :selected="sub.id == planForm.subject_id"></option>
                        </template>
                    </select>
                </div>

                {{-- Batch Selector (Visible for Batch only) --}}
                <div x-show="planForm.scope === 'batch'">
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Assign Batch <span class="text-danger font-semibold">*</span></label>
                    <select name="batch_id"
                            x-model="planForm.batch_id"
                            :disabled="!modalBatches.length"
                            :required="planForm.scope === 'batch'"
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer disabled:opacity-50">
                        <option value="" x-text="modalBatches.length ? 'Select Batch' : 'Select Subject first'"></option>
                        <template x-for="b in modalBatches" :key="b.id">
                            <option :value="b.id"
                                    x-text="b.name + ' (' + (b.teacher ? b.teacher.fname : 'No Mentor') + ')'"
                                    :selected="b.id == planForm.batch_id"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- 3. Plan Title (Optional label) --}}
            <div>
                <label class="block text-xs font-normal text-theme-text mb-1.5">Plan Title / Label <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                <input type="text"
                       name="title"
                       x-model="planForm.title"
                       placeholder="e.g. Class 10 Standard Tuition"
                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
            </div>

            {{-- 4. Billing Cadence, Amount, Due Day --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Billing Cadence <span class="text-danger font-semibold">*</span></label>
                    <select name="plan_type"
                            x-model="planForm.plan_type"
                            required
                            class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                        <option value="onetime">One-Time</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Amount (₹) <span class="text-danger font-semibold">*</span></label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           name="amount"
                           x-model="planForm.amount"
                           required
                           placeholder="e.g. 1200"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                </div>

                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Due Day (1-31) <span class="text-danger font-semibold">*</span></label>
                    <input type="number"
                           name="due_day"
                           min="1"
                           max="31"
                           x-model="planForm.due_day"
                           required
                           placeholder="e.g. 1"
                           class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                </div>
            </div>

            {{-- 5. Start Date (Optional) --}}
            <div>
                <label class="block text-xs font-normal text-theme-text mb-1.5">Effective Start Date <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                <input type="date"
                       name="start_date"
                       x-model="planForm.start_date"
                       class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
            </div>

            {{-- Modal Footer --}}
            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button"
                        @click="createPlanModalOpen = false"
                        class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-check"></i>
                    <span x-text="editingPlanId ? 'Save Changes' : 'Create Master Plan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
