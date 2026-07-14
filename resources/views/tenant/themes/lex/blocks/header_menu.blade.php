@if($block->menu)
    <div {!! $block->attributes() !!} class="w-auto" style="{{ $block->spacing }}">
        <ul class=" hidden md:flex font-{{ $block->font_weight }} items-center gap-6" style="{{ $block->flexStyle . $block->spacing }}">

            {{-- 🎓 DYNAMIC CURRICULUM MEGAMENU --}}
            @if(isset($categories) && $categories->isNotEmpty())
                <li class="relative group py-2">
                    <button type="button" class="inline-flex items-center gap-1.5 arz-link cursor-pointer font-semibold focus:outline-none" aria-haspopup="true">
                        <i class="fa-solid fa-graduation-cap text-indigo-500"></i>
                        Explore
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 group-hover:rotate-180"></i>
                    </button>
                    
                    {{-- Megamenu Dropdown Container --}}
                    <div class="absolute left-0 top-full hidden group-hover:flex w-[680px] bg-white border border-gray-100 rounded-xl shadow-xl p-5 mt-2 z-50 transition-all duration-300 transform scale-95 origin-top group-hover:scale-100 group-hover:opacity-100 opacity-0" style="color: var(--arz-heading);">
                        <div class="grid grid-cols-3 gap-6 w-full text-left">
                            
                            {{-- Categories Side List --}}
                            <div class="border-r border-gray-100 pr-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Categories</h4>
                                <ul class="space-y-1">
                                    @foreach($categories as $catIdx => $cat)
                                        <li>
                                            <button type="button" onclick="switchCategoryTab('{{ $cat->id }}', this)" 
                                                    class="cat-tab-btn w-full text-left px-3 py-2 rounded-lg text-sm transition-all duration-200 flex items-center justify-between {{ $catIdx === 0 ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                                                <span>{{ $cat->name }}</span>
                                                <i class="fa-solid fa-chevron-right text-[10px] opacity-60"></i>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            {{-- Classes & Subjects Area --}}
                            <div class="col-span-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Classes & Subjects</h4>
                                
                                @foreach($categories as $catIdx => $cat)
                                    <div id="cat-panel-{{ $cat->id }}" class="cat-content-panel {{ $catIdx === 0 ? 'block' : 'hidden' }} space-y-4">
                                        @if($cat->classCourses->isEmpty())
                                            <p class="text-xs text-gray-400 py-4">No classes available.</p>
                                        @else
                                            <div class="grid grid-cols-2 gap-4 max-h-[300px] overflow-y-auto scrollbar">
                                                @foreach($cat->classCourses as $cls)
                                                    <div class="bg-gray-50/50 p-3 rounded-lg border border-gray-100/50 hover:border-indigo-100 transition-all duration-200">
                                                        <a href="/courses?class_id={{ $cls->id }}" class="block font-semibold text-xs text-indigo-600 hover:underline mb-1.5">
                                                            <i class="fa-solid fa-chalkboard-user mr-1.5 text-indigo-400"></i>{{ $cls->name }}
                                                        </a>
                                                        
                                                        @if($cls->subjects->isEmpty())
                                                            <span class="text-[10px] text-gray-400 block">No subjects</span>
                                                        @else
                                                            <ul class="space-y-1">
                                                                @foreach($cls->subjects as $subj)
                                                                    <li>
                                                                        <a href="/courses?subject_id={{ $subj->id }}" class="text-[11px] text-gray-600 hover:text-indigo-600 hover:underline flex items-center gap-1">
                                                                            <i class="fa-regular fa-folder-open text-[9px] text-gray-400"></i>
                                                                            {{ $subj->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            @endif

            @foreach($block->menu->items as $item)
                <li class="relative group">
                    <a href="{{ $item->link }}" class="inline-flex items-center arz-link" @if ($item->children->count())
                    onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul class="absolute top-full left-0 hidden group-hover:block min-w-48 border-rounded mt-2 arz-background">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arz-link">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Script for Category Tab Switching --}}
    <script>
        function switchCategoryTab(catId, btnElement) {
            // Hide all panels
            document.querySelectorAll('.cat-content-panel').forEach(function(panel) {
                panel.classList.add('hidden');
                panel.classList.remove('block');
            });
            
            // Show target panel
            const activePanel = document.getElementById('cat-panel-' + catId);
            if (activePanel) {
                activePanel.classList.remove('hidden');
                activePanel.classList.add('block');
            }
            
            // Reset button states
            document.querySelectorAll('.cat-tab-btn').forEach(function(btn) {
                btn.classList.remove('bg-indigo-50', 'text-indigo-600', 'font-semibold');
                btn.classList.add('text-gray-600');
            });
            
            // Set active button state
            btnElement.classList.add('bg-indigo-50', 'text-indigo-600', 'font-semibold');
            btnElement.classList.remove('text-gray-600');
        }
    </script>
@endif
