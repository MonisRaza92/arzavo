<div id="addNestedBlockContainer{{ $block->id }}" class="hidden z-20 fixed top-0 left-0 bottom-0 w-[299px] pt-29 bg-primary inset-0 overflow-y-auto scrollbar">

    <h3 class="flex text-sm justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>Available Blocks</span>
        <i class="fa-solid fa-xmark cursor-pointer"
            onclick="document.getElementById('addNestedBlockContainer{{ $block->id }}').classList.add('hidden')"></i>
    </h3>

    @php
    $allowedNestedBlocks = $blockRule['allowed_blocks'] ?? [];
    $groupedNestedBlocks = collect($availableBlocks)->groupBy('category')->map(function ($nestedBlocks) use ($allowedNestedBlocks) {
    if (!empty($allowedNestedBlocks)) {
    return $nestedBlocks->filter(fn($block) => in_array($block['type'], $allowedNestedBlocks));
    }
    return $nestedBlocks;
    });
    @endphp

    <div>

        @foreach($groupedNestedBlocks as $category => $nestedBlocks)

        @if($nestedBlocks->count() > 0) {{-- Empty category hide --}}
        <div class="border-bottom Block-category" data-category="{{ $category }}">

            <button type="button"
                class="flex justify-between items-center w-full text-left p-4 text-sm font-semibold bg-hover-secondary category-toggle">
                {{ $category }}
                <i class="fa-solid fa-angle-down transition-all duration-300"></i>
            </button>

            <div class="category-items transition-all duration-300">
                @foreach($nestedBlocks->sortBy('order') as $s)

                <form class="blockAddForm"
                    method="POST"
                    action="{{ route('admin.builder.sections.blocks.nested.store', $block->id) }}">
                    @csrf

                    <input type="hidden" name="block_type" value="{{ $s['type'] }}">
                    <input type="hidden" name="block_name" value="{{ $s['name'] }}">

                    <button type="button"
                        class="blockAddBtn w-full font-semibold text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
                        <i class="fa-solid {{ $s['icon'] ?? 'fa-code' }}"></i>
                        {{ $s['name'] }}
                    </button>
                </form>

                @endforeach
            </div>

        </div>
        @endif

        @endforeach

    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.querySelectorAll('.blockAddBtn').forEach(btn => {

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('.blockAddForm');
                if (!form) return;

                // prevent double-submit
                if (this.dataset.submitting === "true") return;
                this.dataset.submitting = "true";

                const formData = new FormData(form);
                const btnRef = this;

                btnRef.disabled = true;
                btnRef.innerHTML = "Adding...";

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            location.reload();
                        } else {
                            btnRef.disabled = false;
                            btnRef.dataset.submitting = "false";
                            btnRef.innerHTML = "Add Block";
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        btnRef.disabled = false;
                        btnRef.dataset.submitting = "false";
                        btnRef.innerHTML = "Add Block";
                    });

            });

        });

    });





    document.addEventListener("DOMContentLoaded", function() {
        const savedState = JSON.parse(localStorage.getItem('BlockCategoriesState')) || {};

        document.querySelectorAll('.Block-category').forEach(cat => {
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
                localStorage.setItem('BlockCategoriesState', JSON.stringify(savedState));
            });
        });
    });
</script>
