{{-- ======================================================== --}}
{{-- 5. TIER 4: BATCHES & ASSIGNED TEACHERS (EXPANDED BENEATH)--}}
{{-- ======================================================== --}}
<div id="tier-batches" x-show="selectedSubject" class="relative z-10">
    {{-- Batches Row + Inline Add Card (Horizontal scroll if > 4 cards) --}}
    <div id="tier-batches-row"
         class="flex items-stretch gap-4 overflow-x-auto pb-4 pt-1 px-1 scrollbar scroll-smooth"
         @scroll.passive="updateConnectors()">
        <template x-for="batch in (selectedSubject ? (selectedSubject.batches || []) : [])" :key="batch.id">
            <div :id="'batch-card-' + batch.id"
                 :data-id="batch.id"
                 class="tier-card-item tier-4-card tier-card-draggable w-[290px] shrink-0 rounded-lg bg-primary border border-theme-border/60 hover:border-brand/60 hover:shadow-md p-4 flex flex-col justify-between group transition relative select-none">

                {{-- Top Center Receiver Pin --}}
                <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

                <div>
                    {{-- Name, Code & Status --}}
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <h3 class="font-semibold text-base text-theme-text leading-tight" x-text="batch.name"></h3>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] bg-secondary text-theme-text-muted font-mono"
                                  x-text="batch.code || 'NO-CODE'"></span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal"
                              :class="batch.status ? 'bg-success-bg text-success' : 'bg-secondary text-theme-text-muted'"
                              x-text="batch.status ? 'Active' : 'Inactive'"></span>
                    </div>

                    {{-- PROMINENT ASSIGNED TEACHER CARD (MANDATORY) --}}
                    <div class="mt-3 p-2.5 rounded-lg bg-secondary flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <template x-if="batch.teacher && batch.teacher.profile_picture">
                                <img :src="resolveMedia(batch.teacher.profile_picture)"
                                     class="w-8 h-8 rounded-full object-cover shrink-0 pointer-events-none">
                            </template>
                            <template x-if="!batch.teacher || !batch.teacher.profile_picture">
                                <div class="w-8 h-8 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center shrink-0 uppercase select-none"
                                     x-text="batch.teacher ? batch.teacher.fname.charAt(0) : 'T'"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-theme-text truncate leading-tight"
                                   x-text="batch.teacher ? (batch.teacher.fname + ' ' + (batch.teacher.lname || '')) : 'Unassigned'">
                                </p>
                                <p class="text-[10px] text-theme-text-muted truncate mt-0.5"
                                   x-text="batch.teacher ? batch.teacher.email : 'No email'"></p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[9px] bg-brand/10 text-brand font-normal shrink-0">
                            Teachers
                        </span>
                    </div>

                    {{-- Schedule & Room Details --}}
                    <div class="grid grid-cols-2 gap-2 mt-3 text-xs text-theme-text-muted">
                        <div class="p-2 rounded-lg bg-secondary flex items-center gap-1.5 truncate">
                            <i class="fa-solid fa-clock text-[10px] text-brand"></i>
                            <span class="truncate text-[11px]" x-text="formatTime(batch.start_time) + ' - ' + formatTime(batch.end_time)"></span>
                        </div>
                        <div class="p-2 rounded-lg bg-secondary flex items-center gap-1.5 truncate">
                            <i class="fa-solid fa-location-dot text-[10px] text-brand"></i>
                            <span class="truncate text-[11px]" x-text="batch.room_number || 'Room TBD'"></span>
                        </div>
                    </div>
                    <div class="mt-2 p-2 rounded-lg bg-secondary flex items-center gap-1.5 text-xs text-theme-text-muted">
                        <i class="fa-solid fa-calendar-days text-[10px] text-brand"></i>
                        <span class="text-[11px]" x-text="batch.days || 'All Weekdays'"></span>
                    </div>
                </div>

                {{-- Footer: Capacity & Actions --}}
                <div class="mt-4 pt-3 flex items-center justify-between text-xs border-t-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-theme-text text-[11px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                        <span x-text="(batch.students_count || 0) + ' / ' + (batch.max_students || 35) + ' Students'"></span>
                    </span>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                class="drag-handle cursor-grab active:cursor-grabbing w-7 h-7 rounded-full bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition"
                                title="Drag to reorder batch">
                            <i class="fa-solid fa-grip-vertical text-[11px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="openEditBatch(batch)"
                                title="Edit Batch"
                                class="w-7 h-7 rounded-full bg-secondary hover:bg-subtle text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="confirmDelete('Batch', batch.id, batch.name, '{{ url('admin/batches') }}/' + batch.id + '/delete', batch.students_count || 0)"
                                title="Delete Batch"
                                class="w-7 h-7 rounded-full bg-secondary hover:bg-danger-bg text-theme-text-muted hover:text-danger flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- INLINE ADD NEW BATCH CARD (Always at end of row) --}}
        <div id="add-batch-card"
             @click.stop="openAddBatch()"
             class="tier-card-item tier-4-card tier-add-card w-[290px] shrink-0 rounded-lg border-2 border-dashed border-brand/50 hover:border-brand bg-primary transition cursor-pointer p-6 flex flex-col items-center justify-center text-center min-h-[280px] group relative">
            {{-- Top Center Receiver Pin --}}
            <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

            <div class="w-12 h-12 rounded-full bg-secondary group-hover:bg-brand group-hover:text-primary text-theme-text transition flex items-center justify-center text-base mb-3 shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h4 class="font-semibold text-sm text-theme-text group-hover:text-brand transition">Create Batch</h4>
            <p class="text-[11px] text-theme-text-muted mt-1 max-w-[190px]">
                Assign teacher, timing &amp; student capacity in <span class="font-medium text-brand" x-text="selectedSubject ? selectedSubject.name : ''"></span>
            </p>
        </div>
    </div>
</div>
