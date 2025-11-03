<div id="addSectionContainer" class="h-10/12 mt-13 pb-2 hidden w-full absolute top-0 left-0 bg-primary inset-0 overflow-y-auto scrollbar">
    <h3 class="flex justify-between items-center font-bold p-4 text-primary border-bottom">
        <span><i class="fa-solid fa-layer-group"></i>&nbsp; Available Sections</span>
        <i class="fa-solid fa-xmark cursor-pointer" onclick="document.getElementById('addSectionContainer').classList.add('hidden')"></i>
    </h3>

    @php
    $groupedSections = collect($availableSections)->groupBy('category');
    $fixedOrder = [
    'Header Sections' => 1,
    'Body Sections' => 2,
    'Footer Sections' => 3
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
                class="flex justify-between items-center w-full text-left p-4 font-semibold hover:bg-gray-100 category-toggle">
                <span><i class="fa-solid fa-folder-open text-primary"></i>&nbsp; {{ $category }}</span>
                <i class="fa-solid fa-angle-down transition-all duration-300"></i>
            </button>

            <div class="category-items transition-all duration-300">
                @foreach($sections->sortBy('order') as $s)
                <form method="POST" action="{{ route('admin.builder.sections.store', $page->id) }}">
                    @csrf
                    <input type="hidden" name="section_type" value="{{ $s['type'] }}">
                    <input type="hidden" name="section_name" value="{{ $s['name'] }}">
                    <button type="submit"
                        class="w-full font-semibold text-sm text-left p-4 border-top hover:bg-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-code"></i>
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