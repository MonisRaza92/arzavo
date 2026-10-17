{{-- ======================================================== --}}
{{-- 4. TIER 3: SUBJECTS (EXPANDED BENEATH CLASS)             --}}
{{-- ======================================================== --}}
<div id="tier-subjects" x-show="selectedClass" class="relative z-10">
    {{-- Subjects Row + Inline Add Card (Horizontal scroll if > 4 cards) --}}
    <div id="tier-subjects-row"
         class="flex items-stretch gap-4 overflow-x-auto pb-4 pt-1 px-1 scrollbar scroll-smooth"
         @scroll.passive="updateConnectors()">
        <template x-for="sub in (selectedClass ? (selectedClass.subjects || []) : [])" :key="sub.id">
            <div :id="'sub-card-' + sub.id"
                 :data-id="sub.id"
                 @click="if (!isDragging) selectSubject(sub)"
                 class="tier-card-item tier-3-card tier-card-draggable w-[290px] shrink-0 rounded-lg bg-primary border border-theme-border/60 hover:border-brand/60 hover:shadow-md cursor-pointer transition flex flex-col justify-between group relative select-none"
                 :class="selectedSubjectId === sub.id ? 'ring-2 ring-brand shadow-md' : ''">

                {{-- Top Center Receiver Pin --}}
                <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

                {{-- Top Banner / Cover Image --}}
                <div class="relative w-full aspect-video bg-subtle overflow-hidden rounded-t-lg">
                    <template x-if="sub.image">
                        <img :src="resolveMedia(sub.image)" :alt="sub.name"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-105 pointer-events-none">
                    </template>
                    <template x-if="!sub.image">
                        <div class="w-full h-full flex flex-col items-center justify-center text-theme-text-muted bg-brand/5">
                            <i class="fa-solid fa-book-reader text-3xl text-brand/30 mb-1"></i>
                            <span class="text-[10px] text-theme-text-muted">No Cover Image</span>
                        </div>
                    </template>

                    {{-- Status Pill Overlay --}}
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal"
                              :class="sub.status ? 'bg-success-bg text-success' : 'bg-primary text-theme-text-muted'"
                              x-text="sub.status ? 'Active' : 'Inactive'"></span>
                    </div>

                    {{-- Active Indicator Ribbon --}}
                    <div x-show="selectedSubjectId === sub.id"
                         class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                        <span>Selected Subject</span>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] text-brand uppercase tracking-wider font-medium"
                              x-text="selectedClass ? selectedClass.name : ''"></span>
                        <h3 class="font-semibold text-base text-theme-text group-hover:text-brand transition leading-tight mt-0.5"
                            x-text="sub.name"></h3>
                        <p class="text-xs text-theme-text-muted mt-1 line-clamp-2"
                           x-text="sub.description || 'Curriculum teaching module.'"></p>
                    </div>

                    {{-- Assigned Subject Faculty / Teacher --}}
                    <div class="mt-2.5 p-2 rounded-lg bg-secondary flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <template x-if="sub.teacher && sub.teacher.profile_picture">
                                <img :src="resolveMedia(sub.teacher.profile_picture)"
                                     class="w-6 h-6 rounded-full object-cover shrink-0 pointer-events-none">
                            </template>
                            <template x-if="!sub.teacher || !sub.teacher.profile_picture">
                                <div class="w-6 h-6 rounded-full bg-brand/10 text-brand font-semibold text-[10px] flex items-center justify-center shrink-0 uppercase select-none"
                                     x-text="sub.teacher ? sub.teacher.fname.charAt(0) : 'F'"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-theme-text truncate leading-tight"
                                   x-text="sub.teacher ? (sub.teacher.fname + ' ' + (sub.teacher.lname || '')) : 'Faculty: Unassigned'">
                                </p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[9px] bg-brand/10 text-brand font-normal shrink-0">
                            Faculty
                        </span>
                    </div>

                    {{-- Schedule & Days (If assigned) --}}
                    <template x-if="sub.start_time || sub.days">
                        <div class="mt-2 p-2 rounded-lg bg-secondary/80 flex items-center justify-between gap-1.5 text-[11px] text-theme-text-muted">
                            <div class="flex items-center gap-1.5 truncate">
                                <i class="fa-solid fa-clock text-[10px] text-brand"></i>
                                <span class="truncate" x-text="formatTime(sub.start_time) + (sub.end_time ? ' - ' + formatTime(sub.end_time) : '')"></span>
                            </div>
                            <template x-if="sub.days">
                                <span class="px-2 py-0.5 rounded-full bg-primary text-theme-text text-[9px] shrink-0 font-medium" x-text="sub.days"></span>
                            </template>
                        </div>
                    </template>

                    {{-- STATS PILL: Enrolled Students --}}
                    <div class="flex items-center justify-between mt-2.5 p-2 rounded-lg bg-secondary text-xs">
                        <span class="text-theme-text-muted text-[10px]">Enrolled Students</span>
                        <span class="font-semibold text-brand text-xs" x-text="(sub.students_count || 0) + ' Students'"></span>
                    </div>
                </div>

                {{-- Footer Action Bar --}}
                <div class="px-4 py-2.5 bg-secondary/50 flex items-center justify-between text-xs rounded-b-lg" @click.stop>
                    <span class="text-[11px] font-medium text-theme-text-muted">
                        Teaching Module
                    </span>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                class="drag-handle cursor-grab active:cursor-grabbing w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition"
                                title="Drag to reorder subject">
                            <i class="fa-solid fa-grip-vertical text-[11px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="openEditSubject(sub)"
                                title="Edit Subject"
                                class="w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="confirmDelete('Subject', sub.id, sub.name, '{{ url('admin/subjects') }}/' + sub.id, sub.students_count || 0)"
                                title="Delete Subject"
                                class="w-7 h-7 rounded-full hover:bg-danger-bg text-theme-text-muted hover:text-danger flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- INLINE ADD NEW SUBJECT CARD (Always at end of row) --}}
        <div id="add-sub-card"
             @click.stop="openAddSubject()"
             class="tier-card-item tier-3-card tier-add-card w-[290px] shrink-0 rounded-lg border-2 border-dashed border-brand/50 hover:border-brand bg-primary transition cursor-pointer p-6 flex flex-col items-center justify-center text-center min-h-[280px] group relative">
            {{-- Top Center Receiver Pin --}}
            <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

            <div class="w-12 h-12 rounded-full bg-secondary group-hover:bg-brand group-hover:text-primary text-theme-text transition flex items-center justify-center text-base mb-3 shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h4 class="font-semibold text-sm text-theme-text group-hover:text-brand transition">Add Subject</h4>
            <p class="text-[11px] text-theme-text-muted mt-1 max-w-[170px]">
                Create subject in <span class="font-medium text-brand" x-text="selectedClass ? selectedClass.name : ''"></span>
            </p>
        </div>
    </div>
</div>
