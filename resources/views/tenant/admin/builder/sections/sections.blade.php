<div class="sections-tab absolute top-0 pt-29 bottom-0 left-0 right-0 overflow-auto scrollbar" id="section-tab-scroll">
    @include('tenant.admin.builder.sections.section-add')
    <ul id="sectionList" class="sortable-section-list p-2 space-y-1">
        @php
        $rules = collect($availableSections)->mapWithKeys(function($section) {
        return [
        $section['type'] => [
        'max_blocks' => $section['max_blocks'] ?? null,
        'allowed_blocks' => $section['allowed_blocks'] ?? [],
        'moveable' => $section['moveable'] ?? "allow",
        ]
        ];
        });
        @endphp
        @foreach($sections as $section)
        <li id="section-{{ $section->id }}" class="cursor-pointer select-none" data-id="{{ $section->id }}">
            <div class="flex justify-between items-center p-1 border-rounded relative group bg-hover-secondary">
                <div class="flex items-center grow">
                    @if (!empty($rules[$section->type]['allowed_blocks']))
                    <button id="section-btn-{{ $section->id }}" type="button"
                        class="text-primary bg-hover-secondary border-rounded pt-0.5 pb-1.5 px-2 transition-all">
                        <i class="fa-solid fa-chevron-right text-tertiary text-[10px]" id="arrow-{{ $section->id }}"></i>
                    </button>
                    @else
                    <span class="w-8"></span>
                    @endif
                    <h2 class="text-sm w-full">
                        <i class="fa-solid {{ $section->icon ?? 'fa-braille' }} text-tertiary text-xs mr-1"></i>{{ $section->name }}
                    </h2>
                </div>
                <div class="flex items-center opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
                    @php
                    $sectionRules = $rules[$section->type] ?? null;
                    $moveable = $sectionRules['moveable'] ?? "allow";
                    @endphp
                    @if ($moveable === "allow")
                    <button class="cursor-drag text-hover-primary text-tertiary text-xs py-2 px-1 border-rounded section-drag-handle">
                        <i class="fa-solid fa-up-down"></i>
                    </button>
                    @else
                    <i class="fa-solid fa-lock text-tertiary text-xs my-2 mx-1"></i>
                    @endif
                    <button type="button" class="toggle-active-btn text-tertiary text-[13px] text-hover-primary py-2 px-1 border-rounded" data-section-id="{{ $section->id }}">
                        @if($section->is_active)
                        <i class="fa-solid fa-eye"></i>
                        @else
                        <i class="fa-solid fa-eye-slash"></i>
                        @endif
                    </button>
                    <form class="delete-section-form" data-section-id="{{ $section->id }}" action="{{ route('admin.builder.sections.destroy', $section->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-btn text-tertiary text-hover-primary py-2 px-1 border-rounded text-xs">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            {{-- BLOCK LIST CONTAINER --}}
            <div id="blocks-{{ $section->id }}" class="ml-7 hidden">
                {{-- List of blocks --}}
                <ul class="block-list" id="block-list-{{ $section->id }}">
                    @foreach($section->blocks as $block)
                    @include('tenant.admin.builder.blocks.block-list', ['block' => $block])
                    @endforeach
                </ul>
                {{-- Add Block Button --}}
                @php
                $sectionRules = $rules[$section->type] ?? null;
                $maxBlocks = $sectionRules['max_blocks'] ?? null;
                $currentBlockCount = $section->blocks->count();
                @endphp
                @if(is_null($maxBlocks) || $currentBlockCount < $maxBlocks)
                    <button type="button"
                    class="text-blue-600 text-left text-sm bg-hover-secondary w-full mt-1 block p-2 border-rounded"
                    onclick="openAddBlock({{ $section->id }})">
                    <i class="fa-regular fa-square-plus mr-1 ml-4.5 text-[13px]"></i> Add Block
                    </button>
                    @endif

            </div>
            {{-- EDIT SECTION FORM --}}
            @include('tenant.admin.builder.blocks.block-add')
            @include('tenant.admin.builder.sections.section-edit')
        </li>
        @endforeach

        <li class="cursor-pointer border-rounded text-blue-600 bg-hover-secondary p-2"
            onclick="document.getElementById('addSectionContainer').classList.remove('hidden')">
            <i class="fa-regular fa-square-plus ml-5.5 text-sm"></i> Add Section
        </li>
    </ul>
</div>

<script>
    document.addEventListener("turbo:load", () => {

        // -------------------------------
        // SECTION → BLOCKS EXPAND/COLLAPSE + LOCALSTORAGE
        // -------------------------------

        const blocksState = JSON.parse(localStorage.getItem("SectionBlocksState") || "{}");

        // Restore saved state on page load
        document.querySelectorAll("[id^='blocks-']").forEach(el => {
            const sectionId = el.id.replace("blocks-", "");
            const arrow = document.getElementById("arrow-" + sectionId);

            if (blocksState[sectionId]) {
                el.classList.remove("hidden");
                arrow?.classList.add("rotate-90");
            }
        });

        // Toggle blocks inside section + save state
        document.querySelectorAll("[id^='section-btn-']").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.stopPropagation();

                const sectionId = this.id.replace("section-btn-", "");
                const container = document.getElementById(`blocks-${sectionId}`);
                const arrow = document.getElementById(`arrow-${sectionId}`);

                const isNowOpen = container.classList.toggle("hidden") === false;

                // update arrow
                arrow.classList.toggle("rotate-90", isNowOpen);

                // save state
                blocksState[sectionId] = isNowOpen;
                localStorage.setItem("SectionBlocksState", JSON.stringify(blocksState));
            });
        });


        // Open Add Block Modal
        function openAddBlock(sectionId) {
            const container = document.getElementById(`addBlockContainer${sectionId}`);
            if (container) {
                container.classList.remove("hidden");
            }
        }
    });


    document.addEventListener("turbo:load", () => {
        const sectionList = document.getElementById("sectionList");
        if (!sectionList) return;

        let isDragging = false;
        let dragTimer = null;

        const sortable = Sortable.create(sectionList, {
            animation: 150,
            ghostClass: "bg-gray-100",
            handle: ".section-drag-handle", // 👈 only this icon will drag
            onStart() {
                isDragging = true;
            },
            onEnd() {
                isDragging = false;

                // Save order to backend
                const order = {};
                sectionList.querySelectorAll("li[data-id]").forEach((el, index) => {
                    order[el.dataset.id] = index + 1;
                });

                fetch("{{ route('admin.builder.sections.reorder', $page->id) }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            order
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "ok" || data.status === "success") {
                            const iframe = document.getElementById("livePreviewContent");
                            if (iframe) iframe.contentWindow.location.reload();
                        }
                    })
                    .catch(console.error);
            }
        });

        sectionList.addEventListener("mousedown", e => {
            dragStartY = e.clientY;
            isDragging = false;
        });

        sectionList.addEventListener("mousemove", e => {
            if (Math.abs(e.clientY - dragStartY) > DRAG_THRESHOLD) {
                isDragging = true;
            }
        });

        sectionList.addEventListener("mouseup", e => {
            // Reset drag flag after click finishes
            setTimeout(() => isDragging = false, 0);
        });


        sectionList.addEventListener("click", e => {
            if (isDragging) return;

            if (e.target.closest(".block-item")) return;

            const li = e.target.closest("li[data-id]");
            if (!li) return;

            const sectionId = li.dataset.id;

            const editForm = document.getElementById(`edit-form-${sectionId}`);
            const blockContainer = document.getElementById(`blocks-${sectionId}`);
            const arrow = document.getElementById(`arrow-${sectionId}`);

            const isOpening = editForm.classList.contains("hidden");

            editForm.classList.toggle("hidden");

            if (isOpening) {
                blockContainer.classList.remove("hidden");
                arrow?.classList.add("rotate-90");

                blocksState[sectionId] = true;
                localStorage.setItem("SectionBlocksState", JSON.stringify(blocksState));
            }
        });



        // ✅ Toggle Active
        document.querySelectorAll(".toggle-active-btn").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.stopPropagation();
                const sectionId = this.dataset.sectionId;
                fetch(`/admin/builder/sections/${sectionId}/toggle-active`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            btn.innerHTML = data.is_active ?
                                '<i class="fa-solid fa-eye"></i>' :
                                '<i class="fa-solid fa-eye-slash"></i>';
                            const iframe = document.getElementById("livePreviewContent");
                            if (iframe) iframe.contentWindow.location.reload();
                        }
                    })
                    .catch(console.error);
            });
        });

        // ✅ Delete Section
        document.querySelectorAll(".delete-section-form .delete-btn").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.stopPropagation();
                const form = this.closest(".delete-section-form");
                const sectionId = form.dataset.sectionId;

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                            "X-HTTP-Method-Override": "DELETE",
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            const li = document.getElementById(`section-${sectionId}`);
                            if (li) {
                                li.style.transition = "opacity 0.3s ease";
                                li.style.opacity = "0";
                                setTimeout(() => li.remove(), 300);
                            }
                            const iframe = document.getElementById("livePreviewContent");
                            if (iframe) iframe.contentWindow.location.reload();

                        } else {
                            alert("Failed to delete section.");
                        }
                    })
                    .catch(console.error);
            });
        });
    });
</script>