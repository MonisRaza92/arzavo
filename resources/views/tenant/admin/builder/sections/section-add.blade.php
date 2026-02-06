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
                'Communication' => 27,
                'Footer' => 28
            ];
        @endphp

        <div class="w-1/4 border-right pt-13 h-full overflow-y-auto scrollbar">
            @foreach($mergedCategories->sortBy(fn($items, $key) => $categoryOrder[$key] ?? 999) as $category => $items)
                <div class="border-bottom section-category"
                    data-category="{{ strtolower(str_replace(' ', '-', $category)) }}"
                    data-original-category="{{ $category }}">

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
                                <form id="sectionAddForm{{ $item['data']['type'] }}" method="POST"
                                    action="{{ route('admin.builder.sections.store', ['theme' => $theme->theme_slug, 'page' => $page,]) }}">
                                    @csrf
                                    <input type="hidden" name="section_type" value="{{ $item['data']['type'] }}">
                                    <input type="hidden" name="section_name" value="{{ $item['data']['name'] }}">
                                    <input type="hidden" name="target" id="sectionTarget" value="page">
                                    <button type="button" onclick="SubmitSectionForm('{{ $item['data']['type'] }}')"
                                        id="sectionAddBtn{{ $item['data']['type'] }}"
                                        class="w-full font-semibold text-xs text-left p-4 pl-8 border-top bg-hover-secondary flex items-center gap-2">
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
                                    <button type="button"
                                        onclick="SubmitTemplateForm('{{ $item['data']['template_file'] ?? $item['data']['type'] }}')"
                                        id="templateAddbtn{{ $item['data']['template_file'] ?? $item['data']['type'] }}"
                                        class="w-full section-add-buttom font-semibold text-xs text-left p-4 pl-8 border-top bg-hover-secondary flex items-center gap-2">
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

<script>
    function filterSectionsByTarget(target) {
        document.querySelectorAll('.section-category').forEach(cat => {
            const category = cat.dataset.originalCategory;

            let show = false;

            if (target === 'header') {
                show = category === 'Header';
            }

            else if (target === 'footer') {
                show = category === 'Footer';
            }

            else {
                // page layout
                show = category !== 'Header' && category !== 'Footer';
            }

            cat.classList.toggle('hidden', !show);
        });
    }

    document.addEventListener("turbo:load", function () {
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

            cat.querySelector('.category-toggle').addEventListener("click", function () {
                content.classList.toggle('hidden');
                let isOpen = !content.classList.contains('hidden');
                savedState[key] = isOpen;
                icon.style.transform = isOpen ? "rotate(0deg)" : "rotate(-90deg)";
                localStorage.setItem('sectionCategoriesState', JSON.stringify(savedState));
            });
        });
    });

    async function SubmitSectionForm(sectionType) {
        const addSectionContainer = document.getElementById('addSectionContainer');
        const form = document.getElementById(`sectionAddForm${sectionType}`);
        const button = document.getElementById(`sectionAddBtn${sectionType}`);
        const originalHTML = button.innerHTML;

        const targetInput = form.querySelector('input[name="target"]');
        const targetValue = window._ARZAVO_SECTION_TARGET;
        targetInput.value = targetValue;

        button.disabled = true;
        button.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Adding...`;

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content'),
                },
            });

            if (!response.ok) {
                throw new Error('Request failed');
            }
            const html = await response.text();

            // 🔥 THIS IS THE IMPORTANT LINE
            document
                .getElementById(targetValue + '-section-list')
                .insertAdjacentHTML('beforeend', html);

            addSectionContainer.classList.add('hidden');
            reloadPreview();
        } catch (err) {
            console.error(err);
            button.textContent = 'Error! Try Again';
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }

    function SubmitTemplateForm(templateType) {
        const addSectionContainer = document.getElementById('addSectionContainer');
        const form = document.getElementById(`templateAddForm${templateType}`);
        const button = document.getElementById(`templateAddbtn${templateType}`);
        const originalHTML = button.innerHTML;

        button.disabled = true;
        button.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Adding...`;

        try {
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content'),
                },
            }).then(response => response.text()).then(html => {
                insertWhenReady(html);
                addSectionContainer.classList.add('hidden');
                reloadPreview();
            }).catch(err => {
                console.error(err);
                button.textContent = 'Error! Try Again';
            }).finally(() => {
                button.disabled = false;
                button.innerHTML = originalHTML;
            });
        } catch (err) {
            console.error(err);
            button.textContent = 'Error! Try Again';
        }
    }
    function insertWhenReady(html, retries = 5) {
        const sectionList = document.getElementById('page-section-list');

        if (sectionList) {
            sectionList.insertAdjacentHTML('beforeend', html);
            return;
        }

        if (retries > 0) {
            setTimeout(() => insertWhenReady(html, retries - 1), 100);
        } else {
            console.error('page-section-list never appeared');
        }
    }

</script>