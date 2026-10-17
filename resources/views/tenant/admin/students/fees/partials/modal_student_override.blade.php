{{-- ======================================================== --}}
{{-- 5. CUSTOM STUDENT FEE OVERRIDE MODAL                      --}}
{{-- ======================================================== --}}
<div x-cloak
     x-show="overrideModalOpen"
     @keydown.escape.window="overrideModalOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div @click.outside="overrideModalOpen = false"
         class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-5 bg-primary flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-base text-theme-text">Student Fee Override / Concession</h3>
                    <p class="text-xs text-theme-text-muted font-normal">Apply custom rate for scholarships or reset to curriculum hierarchy</p>
                </div>
            </div>
            <button type="button" @click="overrideModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <template x-if="selectedStudent">
            <form :action="'/admin/students/fee-plans/override/' + selectedStudent.id"
                  method="POST"
                  class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                @csrf

                {{-- Student Summary Card --}}
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="font-semibold text-theme-text" x-text="selectedStudent.fname + ' ' + (selectedStudent.lname || '')"></div>
                        <div class="text-[10px] text-theme-text-muted" x-text="(selectedStudent.class_name || 'No Class') + ' • ' + (selectedStudent.category_name || 'No Category')"></div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-theme-text-muted block">Current Resolution:</span>
                        <span class="font-semibold text-brand text-xs" x-text="selectedStudent.effective_plan.source_label"></span>
                    </div>
                </div>

                {{-- Action Hidden Input --}}
                <input type="hidden" name="action" :value="overrideForm.is_custom ? 'override' : 'reset'">

                {{-- Override Mode Toggles --}}
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            @click="overrideForm.is_custom = true"
                            class="px-4 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="overrideForm.is_custom ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        Custom Rate / Concession
                    </button>
                    <button type="button"
                            @click="overrideForm.is_custom = false"
                            class="px-4 py-2.5 rounded-full text-xs font-normal transition text-center cursor-pointer"
                            :class="!overrideForm.is_custom ? 'bg-brand text-primary font-semibold shadow-xs' : 'bg-secondary text-theme-text hover:bg-subtle'">
                        Inherit Hierarchy Plan
                    </button>
                </div>

                {{-- 1. Custom Override Form --}}
                <div x-show="overrideForm.is_custom" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Custom Fee (₹) <span class="text-danger font-semibold">*</span></label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="amount"
                                   x-model="overrideForm.amount"
                                   :required="overrideForm.is_custom"
                                   :disabled="!overrideForm.is_custom"
                                   placeholder="e.g. 0 for Free or 500"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                            <span class="text-[10px] text-theme-text-muted mt-1 block">Set ₹0 for 100% scholarship.</span>
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Cadence <span class="text-danger font-semibold">*</span></label>
                            <select name="plan_type"
                                    x-model="overrideForm.plan_type"
                                    :required="overrideForm.is_custom"
                                    :disabled="!overrideForm.is_custom"
                                    class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                                <option value="onetime">One-Time</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Monthly Due Day (1-31) <span class="text-danger font-semibold">*</span></label>
                            <input type="number"
                                   name="due_day"
                                   min="1"
                                   max="31"
                                   x-model="overrideForm.due_day"
                                   :required="overrideForm.is_custom"
                                   :disabled="!overrideForm.is_custom"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                        </div>
                        <div>
                            <label class="block text-xs font-normal text-theme-text mb-1.5">Start Date <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                            <input type="date"
                                   name="start_date"
                                   x-model="overrideForm.start_date"
                                   :disabled="!overrideForm.is_custom"
                                   class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Reason / Concession Note</label>
                        <input type="text"
                               name="override_reason"
                               x-model="overrideForm.reason"
                               :disabled="!overrideForm.is_custom"
                               placeholder="e.g. Merit Scholarship, Staff Child, Concession"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                </div>

                {{-- 2. Reset to Inherit Form --}}
                <div x-show="!overrideForm.is_custom" class="p-4 bg-secondary rounded-lg space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-brand font-semibold">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Inherit Dynamic Hierarchy Plan</span>
                    </div>
                    <p class="text-theme-text-muted font-normal leading-relaxed">
                        By resetting, this student will stop having a custom rate and will dynamically inherit whatever fee plan is set for their <strong>Category, Class, Subject, or Batch</strong>. If you later change the Class fee, this student will update automatically!
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="overrideModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check"></i>
                        <span x-text="overrideForm.is_custom ? 'Save Custom Override' : 'Confirm Reset to Inherited'"></span>
                    </button>
                </div>
            </form>
        </template>
    </div>
</div>
