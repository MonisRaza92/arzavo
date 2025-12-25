<div id="addSectionContainer" class="hidden z-20 fixed top-0 left-0 bottom-0 w-[299px] pt-29 bg-primary inset-0 overflow-y-auto scrollbar">
    <h3 class="flex text-sm sticky top-0 bg-primary justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>Available Sections</span>
        <i class="fa-solid fa-xmark cursor-pointer" onclick="document.getElementById('addSectionContainer').classList.add('hidden')"></i>
    </h3>

    @php
    // Group sections by category
    $groupedSections = collect($availableSections)->groupBy('category');
    
    // Group templates by category  
    $groupedTemplates = collect($availableTemplates)->groupBy('category');
    
    // Merge sections and templates by category
    $mergedCategories = collect();
    
    // Get all unique categories from both sections and templates
    $allCategories = $groupedSections->keys()->merge($groupedTemplates->keys())->unique();
    
    foreach($allCategories as $category) {
        $items = collect();
        
        // Add sections from this category
        if ($groupedSections->has($category)) {
            foreach($groupedSections[$category] as $section) {
                $items->push([
                    'type' => 'section',
                    'data' => $section,
                    'order' => $section['order'] ?? 999
                ]);
            }
        }
        
        // Add templates from this category
        if ($groupedTemplates->has($category)) {
            foreach($groupedTemplates[$category] as $template) {
                $items->push([
                    'type' => 'template',
                    'data' => $template,
                    'order' => $template['order'] ?? 999
                ]);
            }
        }
        
        // Sort items by order
        $mergedCategories[$category] = $items->sortBy('order');
    }
    
    // Define category order
    $categoryOrder = [
        'Header' => 1,
        'Hero' => 2,
        'Features' => 3,
        'About' => 4,
        'Testimonials' => 5,
        'Call to Action' => 6,
        'Pricing' => 7,
        'Contact' => 8,
        'Gallery' => 9,
        'Blog' => 10,
        'Services' => 11,
        'FAQ' => 12,
        'Newsletter' => 13,
        'Process' => 14,
        'Portfolio' => 15,
        'Clients' => 16,
        'Video' => 17,
        'Countdown' => 18,
        'Social' => 19,
        'Statistics' => 20,
        'Team' => 21,
        'Forms' => 22,
        'Content' => 23,
        'Layout' => 24,
        'Basic' => 25,
        'Media' => 26,
        'Interactive' => 27,
        'Footer' => 28
    ];
    @endphp

    <div>
        @foreach($mergedCategories->sortBy(fn($items, $key) => $categoryOrder[$key] ?? 999) as $category => $items)
        <div class="border-bottom section-category" data-category="{{ strtolower(str_replace(' ', '-', $category)) }}">
            <button type="button"
                class="flex justify-between items-center w-full text-left p-4 text-sm font-semibold bg-hover-secondary category-toggle">
                <span>
                    {{ $category }}
                </span>
                <i class="fa-solid fa-angle-down transition-all duration-300"></i>
            </button>

            <div class="category-items transition-all duration-300">
                @foreach($items as $item)
                    @if($item['type'] === 'section')
                        <form method="POST" action="{{ route('admin.builder.sections.store', $page->id) }}">
                            @csrf
                            <input type="hidden" name="section_type" value="{{ $item['data']['type'] }}">
                            <input type="hidden" name="section_name" value="{{ $item['data']['name'] }}">
                            <button type="submit"
                                class="w-full font-semibold text-xs text-left p-4 pl-8 border-top bg-hover-secondary flex items-center gap-2">
                                <i class="fa-solid {{ $item['data']['icon'] ?? 'fa-cube' }}"></i>
                                {{ $item['data']['name'] }}
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.builder.sections.store.template', $page->id) }}">
                            @csrf
                            <input type="hidden" name="template_type" value="{{ $item['data']['template_file'] ?? $item['data']['type'] }}">
                            <input type="hidden" name="section_name" value="{{ $item['data']['name'] }}">
                            <button type="submit"
                                class="w-full font-semibold text-xs text-left p-4 pl-8 border-top bg-hover-secondary flex items-center gap-2">
                                <i class="fa-solid {{ $item['data']['icon'] ?? 'fa-puzzle-piece' }}"></i>
                                {{ $item['data']['name'] }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener("turbo:load", function() {
        const savedState = JSON.parse(localStorage.getItem('sectionCategoriesState')) || {};

        document.querySelectorAll('.section-category').forEach(cat => {
            let key = cat.dataset.category;
            let content = cat.querySelector('.category-items');
            let icon = cat.querySelector('.fa-angle-down');

            // Default to open for main categories
            if (savedState[key] === undefined) {
                savedState[key] = true;
            }

            if (savedState[key] === false) {
                content.classList.add('hidden');
                icon.style.transform = "rotate(-90deg)";
            }

            cat.querySelector('.category-toggle').addEventListener("click", function() {
                content.classList.toggle('hidden');
                let isOpen = !content.classList.contains('hidden');
                savedState[key] = isOpen;
                icon.style.transform = isOpen ? "rotate(0deg)" : "rotate(-90deg)";
                localStorage.setItem('sectionCategoriesState', JSON.stringify(savedState));
            });
        });
    });
</script>