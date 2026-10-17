{{-- ======================================================== --}}
{{-- 2. TIER 1: ACADEMIC CATEGORIES                           --}}
{{-- ======================================================== --}}
<div id="tier-categories" class="relative z-10">
    {{-- Categories Row + Inline Add Card (Horizontal scroll if > 4 cards) --}}
    <div id="tier-categories-row"
         class="flex items-stretch gap-4 overflow-x-auto pb-4 pt-1 px-1 scrollbar scroll-smooth"
         @scroll.passive="updateConnectors()">
        <template x-for="cat in categories" :key="cat.id">
            <div :id="'cat-card-' + cat.id"
                 :data-id="cat.id"
                 @click="if (!isDragging) selectCategory(cat)"
                 class="tier-card-item tier-1-card tier-card-draggable w-[290px] shrink-0 rounded-lg bg-primary border border-theme-border/60 hover:border-brand/60 hover:shadow-md cursor-pointer transition flex flex-col justify-between group relative select-none"
                 :class="selectedCategoryId === cat.id ? 'ring-2 ring-brand shadow-md' : ''">

                {{-- Bottom Center Connector Pin (when active) --}}
                <div x-show="selectedCategoryId === cat.id"
                     class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 w-5 h-5 rounded-full bg-brand text-primary flex items-center justify-center text-[9px] shadow-sm z-30">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>

                {{-- Top Banner / Cover Image --}}
                <div class="relative w-full aspect-video bg-subtle overflow-hidden rounded-t-lg">
                    <template x-if="cat.image">
                        <img :src="resolveMedia(cat.image)" :alt="cat.name"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-105 pointer-events-none">
                    </template>
                    <template x-if="!cat.image">
                        <div class="w-full h-full flex flex-col items-center justify-center text-theme-text-muted bg-brand/5">
                            <i class="fa-solid fa-layer-group text-3xl text-brand/30 mb-1"></i>
                            <span class="text-[10px] text-theme-text-muted">No Cover Image</span>
                        </div>
                    </template>

                    {{-- Status Pill Overlay --}}
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal"
                              :class="cat.status ? 'bg-success-bg text-success' : 'bg-primary text-theme-text-muted'"
                              x-text="cat.status ? 'Active' : 'Inactive'"></span>
                    </div>

                    {{-- Active Indicator Ribbon --}}
                    <div x-show="selectedCategoryId === cat.id"
                         class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                        <span>Active Tier</span>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-semibold text-base text-theme-text group-hover:text-brand transition leading-tight"
                            x-text="cat.name"></h3>
                        <p class="text-xs text-theme-text-muted mt-1 line-clamp-2"
                           x-text="cat.description || 'No description provided.'"></p>
                    </div>

                    {{-- STATS PILL: Shows Classes, Subjects & Enrolled Students --}}
                    <div class="grid grid-cols-3 gap-1.5 mt-3.5 p-2 rounded-lg bg-secondary text-center text-[10px]">
                        <div>
                            <span class="text-theme-text-muted block text-[9px]">Classes</span>
                            <span class="font-semibold text-theme-text" x-text="cat.classes_count || 0"></span>
                        </div>
                        <div>
                            <span class="text-theme-text-muted block text-[9px]">Subjects</span>
                            <span class="font-semibold text-theme-text" x-text="cat.subjects_count || 0"></span>
                        </div>
                        <div>
                            <span class="text-theme-text-muted block text-[9px]">Students</span>
                            <span class="font-semibold text-brand" x-text="cat.students_count || 0"></span>
                        </div>
                    </div>
                </div>

                {{-- Footer Action Bar --}}
                <div class="px-4 py-2.5 bg-secondary/50 flex items-center justify-between text-xs rounded-b-lg" @click.stop>
                    <span class="text-[11px] font-medium"
                          :class="selectedCategoryId === cat.id ? 'text-brand' : 'text-theme-text-muted'">
                        <span x-text="selectedCategoryId === cat.id ? 'Connected Below &darr;' : 'Click to Reveal &rarr;'"></span>
                    </span>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                class="drag-handle cursor-grab active:cursor-grabbing w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition"
                                title="Drag to reorder category">
                            <i class="fa-solid fa-grip-vertical text-[11px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="openEditCategory(cat)"
                                title="Edit Category"
                                class="w-7 h-7 rounded-full hover:bg-secondary text-theme-text-muted hover:text-brand flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                        <button type="button"
                                @click.stop.prevent="confirmDelete('Category', cat.id, cat.name, '{{ url('admin/academic-categories') }}/' + cat.id + '/delete', cat.students_count || 0)"
                                title="Delete Category"
                                class="w-7 h-7 rounded-full hover:bg-danger-bg text-theme-text-muted hover:text-danger flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- INLINE ADD NEW CATEGORY CARD (Always at end of row) --}}
        <div id="add-cat-card"
             @click.stop="openAddCategory()"
             class="tier-card-item tier-1-card tier-add-card w-[290px] shrink-0 rounded-lg border-2 border-dashed border-brand/50 hover:border-brand bg-primary transition cursor-pointer p-6 flex flex-col items-center justify-center text-center min-h-[280px] group relative">
            <div class="w-12 h-12 rounded-full bg-secondary group-hover:bg-brand group-hover:text-primary text-theme-text transition flex items-center justify-center text-base mb-3 shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h4 class="font-semibold text-sm text-theme-text group-hover:text-brand transition">Add Category</h4>
            <p class="text-[11px] text-theme-text-muted mt-1 max-w-[170px]">
                Create academic tier with cover image
            </p>
        </div>
    </div>
</div>
