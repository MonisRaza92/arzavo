<div id="addSectionContainer"
    class="hidden z-200 fixed w-full max-w-5xl h-full md:h-10/12 border-rounded border-primary top-1/2 mt-4 left-1/2 transform -translate-y-1/2 -translate-x-1/2 bg-primary md:shadow-2xl overflow-y-auto scrollbar">
    <h3
        class="flex text-sm absolute w-full top-0 bg-primary justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>Available Sections</span>
        <i class="fa-solid fa-xmark cursor-pointer"
            onclick="document.getElementById('addSectionContainer').classList.add('hidden')"></i>
    </h3>
    <div class="flex h-full overflow-y-hidden">
        @php
            // Group sections by category
            $groupedSections = collect($availableSections)->groupBy('category');

            // Group templates by category
            $groupedTemplates = collect($availableTemplates)->groupBy('category');

            // Merge sections and templates by category
            $mergedCategories = collect();

            // Get all unique categories from both sections and templates
            $allCategories = $groupedSections->keys()->merge($groupedTemplates->keys())->unique();

            foreach ($allCategories as $category) {
                $items = collect();

                // Add sections from this category
                if ($groupedSections->has($category)) {
                    foreach ($groupedSections[$category] as $section) {
                        $items->push([
                            'type' => 'section',
                            'data' => $section,
                            'order' => $section['order'] ?? 999
                        ]);
                    }
                }

                // Add templates from this category
                if ($groupedTemplates->has($category)) {
                    foreach ($groupedTemplates[$category] as $template) {
                        $items->push([
                            'type' => 'template',
                            'data' => $template,
                            'order' => $template['order'] ?? 999
                        ]);
                    }
                }

                // Sort items by order
                $mergedCategories[$category] = $items
                    ->sortBy(fn($i) => (int) ($i['order'] ?? 999))
                    ->values();
            }
        @endphp

        <div class="w-1/4 border-right pt-13 h-full overflow-y-auto scrollbar">
            @foreach($mergedCategories->sortKeysUsing(fn($a, $b) => strcasecmp($a, $b)) as $category => $items)
                <div class="border-bottom section-category"
                    data-category="{{ strtolower(str_replace(' ', '-', $category)) }}"
                    data-original-category="{{ $category }}">

                    <button type="button"
                        class="flex justify-between items-center w-full text-left p-4 text-xs uppercase font-semibold bg-hover-secondary category-toggle">
                        <span>
                            {{ $category }}
                        </span>
                        <i class="fa-solid fa-angle-down transition-all duration-300"></i>
                    </button>

                    <div class="category-items transition-all duration-300">
                        @foreach($items as $item)
                            @if($item['type'] === 'section')
                                <form id="sectionAddForm{{  $item['data']['schema'] ?? $item['data']['type'] }}" method="POST"
                                    action="{{ route('admin.builder.sections.store', ['theme' => $theme->theme_slug, 'page' => $page,]) }}">
                                    @csrf
                                    <input type="hidden" name="section_type" value="{{ $item['data']['type'] }}">
                                    <input type="hidden" name="section_name" value="{{ $item['data']['name'] }}">
                                    <input type="hidden" name="schema" value="{{ $item['data']['schema'] ?? $item['data']['type'] }}">
                                    <input type="hidden" name="target" id="sectionTarget" value="page">
                                    <button type="button" onclick="SubmitSectionForm('{{ $item['data']['schema'] ?? $item['data']['type'] }}')"
                                        id="sectionAddBtn{{ $item['data']['schema'] ?? $item['data']['type'] }}"
                                        class="w-full text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
                                        <i class="fa-solid {{ $item['data']['icon'] ?? 'fa-cube' }}"></i>
                                        {{ $item['data']['name'] }}
                                    </button>
                                </form>
                            @else
                                <form method="POST"
                                    id="templateAddForm{{ $item['data']['template_file'] ?? $item['data']['type'] }}"
                                    action="{{ route('admin.builder.sections.store.template', ['theme' => $theme->theme_slug, 'page' => $page->id]) }}">
                                    @csrf
                                    <input type="hidden" name="template_type"
                                        value="{{ $item['data']['template_file'] ?? $item['data']['type'] }}">
                                    <input type="hidden" name="section_name" value="{{ $item['data']['name'] }}">
                                    <input type="hidden" name="schema" value="{{ $item['data']['schema'] ?? $item['data']['type'] }}">
                                    <button type="button"
                                        onclick="SubmitTemplateForm('{{ $item['data']['template_file'] ?? $item['data']['type'] }}')"
                                        id="templateAddbtn{{ $item['data']['template_file'] ?? $item['data']['type'] }}"
                                        class="w-full section-add-buttom text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
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
        <div class="preview w-3/4 p-4 flex justify-center items-center">
            <div class="text-center text-sm text-gray-200">
                <i class="fa-solid fa-photo-film text-9xl mb-2"></i>
                <p class="text-lg">Preview Not Available</p>
            </div>
        </div>
    </div>
</div>
