{{-- ======================================================== --}}
{{-- 3. TIER 2: CLASSES & COURSES (EXPANDED BENEATH CATEGORY) --}}
{{-- ======================================================== --}}
<div id="tier-classes" x-show="selectedCategory" class="relative z-10">
    {{-- Classes Row + Inline Add Card (Horizontal scroll if > 4 cards) --}}
    <div id="tier-classes-row"
         class="flex items-stretch gap-4 overflow-x-auto pb-4 pt-1 px-1 scrollbar scroll-smooth"
         @scroll.passive="updateConnectors()">
        <template x-for="cls in (selectedCategory ? (selectedCategory.class_courses || []) : [])" :key="cls.id">
            <div :id="'class-card-' + cls.id"
                 :data-id="cls.id"
                 @click="if (!isDragging) selectClass(cls)"
                 class="tier-card-item tier-2-card tier-card-draggable w-[290px] shrink-0 rounded-lg bg-primary border border-theme-border/60 hover:border-brand/60 hover:shadow-md cursor-pointer transition flex flex-col justify-between group relative select-none"
                 :class="selectedClassId === cls.id ? 'ring-2 ring-brand shadow-md' : ''">

                {{-- Top Center Receiver Pin --}}
                <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

                {{-- Bottom Center Connector Pin (when active) --}}
                <div x-show="selectedClassId === cls.id"
                     class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 w-5 h-5 rounded-full bg-brand text-primary flex items-center justify-center text-[9px] shadow-sm z-30">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>

                {{-- Top Banner / Cover Image --}}
                <div class="relative w-full aspect-video bg-subtle overflow-hidden rounded-t-lg">
                    <template x-if="cls.image">
                        <img :src="resolveMedia(cls.image)" :alt="cls.name"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-105 pointer-events-none">
                    </template>
                    <template x-if="!cls.image">
                        <div class="w-full h-full flex flex-col items-center justify-center text-theme-text-muted bg-brand/5">
                            <i class="fa-solid fa-book-open text-3xl text-brand/30 mb-1"></i>
                            <span class="text-[10px] text-theme-text-muted">No Cover Image</span>
                        </div>
                    </template>

                    {{-- Status Pill Overlay --}}
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal"
                              :class="cls.status ? 'bg-success-bg text-success' : 'bg-primary text-theme-text-muted'"
                              x-text="cls.status ? 'Active' : 'Inactive'"></span>
                    </div>

                    {{-- Active Indicator Ribbon --}}
                    <div x-show="selectedClassId === cls.id"
                         class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                        <span>Selected Class</span>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] text-brand uppercase tracking-wider font-medium"
                              x-text="selectedCategory ? selectedCategory.name : ''"></span>
                        <h3 class="font-semibold text-base text-theme-text group-hover:text-brand transition leading-tight mt-0.5"
                            x-text="cls.name"></h3>
                        <p class="text-xs text-theme-text-muted mt-1 line-clamp-2"
                            x-text="cls.description || 'Program curriculum details.'"></p>
                    </div>

                    {{-- Assigned Class Mentor / Teacher --}}
                    <div class="mt-2.5 p-2 rounded-lg bg-secondary flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <template x-if="cls.class_teacher && cls.class_teacher.profile_picture">
                                <img :src="resolveMedia(cls.class_teacher.profile_picture)"
                                     class="w-6 h-6 rounded-full object-cover shrink-0 pointer-events-none">
                            </template>
                            <template x-if="!cls.class_teacher || !cls.class_teacher.profile_picture">
                                <div class="w-6 h-6 rounded-full bg-brand/10 text-brand font-semibold text-[10px] flex items-center justify-center shrink-0 uppercase select-none"
                                     x-text="cls.class_teacher ? cls.class_teacher.fname.charAt(0) : 'M'"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-theme-text truncate leading-tight"
                                   x-text="cls.class_teacher ? (cls.class_teacher.fname + ' ' + (cls.class_teacher.lname || '')) : 'Mentor: Unassigned'">
                                </p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[9px] bg-brand/10 text-brand font-normal shrink-0">
                            Class Mentor
                        </span>
                    </div>

                    {{-- STATS PILL: Shows Subjects & Enrolled Students --}}
                    <div class="grid grid-cols-2 gap-2 mt-2.5 p-2 rounded-lg bg-secondary text-center text-[10px]">
                        <div>
                            <span class="text-theme-text-muted block text-[9px]">Subjects</span>
                            <span class="font-semibold text-theme-text" x-text="cls.subjects_count || 0"></span>
                        </div>
                        <div>
                            <span class="text-theme-text-muted block text-[9px]">Students</span>
                            <span class="font-semibold text-brand" x-text="cls.students_count || 0"></span>
                        </div>
                    </div>
                </div>

                {{-- Footer Action Bar --}}
                <div class="px-4 py-2.5 bg-secondary/50 flex items-center justify-between text-xs rounded-b-lg" @click.stop>
                    <span class="text-[11px] font-medium"
                          :class="selectedClassId === cls.id ? 'text-brand' : 'text-theme-text-muted'">
                        <span x-text="selectedClassId === cls.id ? 'Connected Below &darr;' : 'Click to Reveal &rarr;'"></span>
                    </span>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                class="drag-handle cursor-grab active:cursor-grabbing w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition"
                                title="Drag to reorder class">
                            <i class="fa-solid fa-grip-vertical text-[11px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="openEditClass(cls)"
                                title="Edit Class"
                                class="w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="confirmDelete('Class', cls.id, cls.name, '{{ url('admin/classes/courses') }}/' + cls.id + '/delete', cls.students_count || 0)"
                                title="Delete Class"
                                class="w-7 h-7 rounded-full hover:bg-danger-bg text-theme-text-muted hover:text-danger flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- INLINE ADD NEW CLASS CARD (Always at end of row) --}}
        <div id="add-class-card"
             @click.stop="openAddClass()"
             class="tier-card-item tier-2-card tier-add-card w-[290px] shrink-0 rounded-lg border-2 border-dashed border-brand/50 hover:border-brand bg-primary transition cursor-pointer p-6 flex flex-col items-center justify-center text-center min-h-[280px] group relative">
            {{-- Top Center Receiver Pin --}}
            <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-brand/20 border-2 border-brand z-20"></div>

            <div class="w-12 h-12 rounded-full bg-secondary group-hover:bg-brand group-hover:text-primary text-theme-text transition flex items-center justify-center text-base mb-3 shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h4 class="font-semibold text-sm text-theme-text group-hover:text-brand transition">Add Class</h4>
            <p class="text-[11px] text-theme-text-muted mt-1 max-w-[170px]">
                Create class in <span class="font-medium text-brand" x-text="selectedCategory ? selectedCategory.name : ''"></span>
            </p>
        </div>
    </div>
</div>
