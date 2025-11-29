<div id="addSectionContainer" class="hidden z-20 fixed top-0 left-0 bottom-0 w-[299px] pt-29 bg-primary inset-0 overflow-y-auto scrollbar">
    <h3 class="flex text-sm justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>Available Sections</span>
        <i class="fa-solid fa-xmark cursor-pointer" onclick="document.getElementById('addSectionContainer').classList.add('hidden')"></i>
    </h3>

    @php
    $groupedSections = collect($availableSections)->groupBy('category');
    $fixedOrder = [
    'Header' => 1,
    'Sections' => 2,
    ];
    @endphp

    <div>
        @foreach(
        $groupedSections->sortBy(function($value, $key) use ($fixedOrder) {
        return $fixedOrder[$key] ?? 999;
        }) as $category => $sections
        )

        <div class="border-bottom section-category" data-category="{{ $category }}">

            <button type="button"
                class="flex justify-between items-center w-full text-left p-4 text-sm font-semibold bg-hover-secondary category-toggle">
                {{ $category }}
                <i class="fa-solid fa-angle-down transition-all duration-300"></i>
            </button>

            <div class="category-items transition-all duration-300">
                @foreach($sections->sortBy('order') as $s)
                <form method="POST" action="{{ route('admin.builder.sections.store', $page->id) }}">
                    @csrf
                    <input type="hidden" name="section_type" value="{{ $s['type'] }}">
                    <input type="hidden" name="section_name" value="{{ $s['name'] }}">
                    <button type="submit"
                        class="w-full font-semibold text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
                        <i class="fa-solid {{ $s['icon'] }}"></i>
                        {{ $s['name'] }}
                    </button>
                </form>
                @endforeach
            </div>

        </div>

        @endforeach
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const savedState = JSON.parse(localStorage.getItem('sectionCategoriesState')) || {};

        document.querySelectorAll('.section-category').forEach(cat => {
            let key = cat.dataset.category;
            let content = cat.querySelector('.category-items');
            let icon = cat.querySelector('.fa-angle-down');

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