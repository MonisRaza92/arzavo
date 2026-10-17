{{-- ======================================================== --}}
{{-- MODAL 5: DELETE CONFIRMATION DIALOG / STUDENT SAFEGUARD  --}}
{{-- ======================================================== --}}
<div x-show="deleteModalOpen"
     x-transition
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/75"
         @click="deleteModalOpen = false"></div>

    {{-- Dialog --}}
    <div class="relative w-full max-w-md bg-primary rounded-lg shadow-2xl p-6 z-10 text-center"
         @click.stop>

        {{-- CASE 1: ENROLLED STUDENTS FOUND (DELETION LOCKED) --}}
        <template x-if="deleteEnrolledCount > 0">
            <div>
                <div class="w-14 h-14 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm">
                    <i class="fa-solid fa-user-lock"></i>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[11px] font-semibold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Deletion Blocked
                </span>

                <h3 class="text-base font-bold text-theme-text mb-2">Active Students Enrolled</h3>

                <div class="p-3.5 rounded-lg bg-secondary text-left mb-4 border border-theme-border/40">
                    <p class="text-xs text-theme-text leading-relaxed">
                        Cannot delete this <strong class="text-brand font-semibold" x-text="deleteItemType"></strong>:
                        <span class="font-semibold text-theme-text" x-text="deleteItemName"></span>.
                    </p>
                    <p class="text-xs text-theme-text-muted mt-2 leading-relaxed">
                        There <span x-text="deleteEnrolledCount === 1 ? 'is currently' : 'are currently'"></span>
                        <span class="font-bold text-danger text-sm" x-text="deleteEnrolledCount"></span>
                        <span x-text="deleteEnrolledCount === 1 ? 'active student' : 'active students'"></span> assigned to this <span x-text="deleteItemType.toLowerCase()"></span>.
                    </p>
                    <p class="text-[11px] text-theme-text-muted mt-2 pt-2 border-t border-theme-border/30">
                        To preserve student academic profiles, attendance records, and enrollment integrity, please remove or reassign all enrolled students before attempting to delete this <span x-text="deleteItemType.toLowerCase()"></span>.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button type="button" @click="deleteModalOpen = false"
                            class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                        Close
                    </button>
                    <a href="{{ route('admin.students') }}"
                       class="px-5 py-2 rounded-full bg-brand text-primary text-xs hover:opacity-90 transition font-normal shadow-sm inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-[11px]"></i>
                        <span>Manage Students</span>
                    </a>
                </div>
            </div>
        </template>

        {{-- CASE 2: NO STUDENTS ENROLLED (SAFE TO DELETE) --}}
        <template x-if="deleteEnrolledCount === 0">
            <div>
                <div class="w-14 h-14 rounded-full bg-danger-bg text-danger flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm">
                    <i class="fa-solid fa-trash-can"></i>
                </div>

                <h3 class="text-base font-bold text-theme-text mb-1">Confirm Deletion</h3>
                <p class="text-xs text-theme-text-muted mb-4 leading-relaxed">
                    Are you sure you want to permanently delete
                    <span class="font-semibold text-theme-text" x-text="deleteItemName"></span> (<span x-text="deleteItemType"></span>)?
                    Any subordinate curriculum tiers and connections will also be removed.
                </p>

                <form :action="deleteActionUrl" method="POST" class="flex items-center justify-center gap-2.5 pt-1">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" @click="deleteModalOpen = false"
                            class="px-4 py-2 rounded-full bg-secondary text-theme-text text-xs hover:bg-subtle transition font-normal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-full bg-danger text-white text-xs hover:opacity-90 transition font-normal shadow-sm">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </template>
    </div>
</div>
