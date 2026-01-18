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
                <div class="flex items-center grow section-items">
                    @if (!empty($rules[$section->type]['allowed_blocks']))
                    <button id="section-btn-{{ $section->id }}" type="button"
                        class="text-primary bg-hover-secondary border-rounded pt-0.5 pb-1.5 px-2 transition-all">
                        <i class="fa-solid fa-chevron-right text-tertiary text-[10px]" id="arrow-{{ $section->id }}"></i>
                    </button>
                    @else
                    <span class="w-8"></span>
                    @endif
                    <h2 class="text-sm w-full section-header" data-id="{{ $section->id }}">
                        <i class="fa-solid {{ $section->icon ?? 'fa-braille' }} text-xs text-tertiary mr-2"></i>{{ $section->name }}
                    </h2>
                </div>
                <div class="flex items-center section-items opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
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
                    <i class="fa-regular fa-square-plus mr-1 ml-6 text-[13px]"></i> Add Block
                    </button>
                    @endif

            </div>
            {{-- EDIT SECTION FORM --}}
            @include('tenant.admin.builder.blocks.block-add')
            @include('tenant.admin.builder.sections.section-edit')
        </li>
        @endforeach

        <li class="cursor-pointer border-rounded text-blue-600 text-sm bg-hover-secondary p-3"
            onclick="document.getElementById('addSectionContainer').classList.remove('hidden')">
            <i class="fa-regular fa-square-plus ml-5 mr-1"></i> Add Section
        </li>
    </ul>
</div>

<script>
    function openAddBlock(id){
        const blockAddForm = document.getElementById('addBlockContainer' + id);
        blockAddForm.classList.remove('hidden');
    }
    window.csrfToken = "{{ csrf_token() }}";
    window.sectionReorderUrl = "{{ route('admin.builder.sections.reorder', $page->id) }}";


    document.addEventListener("turbo:load", initBuilder);
    document.addEventListener("turbo:render", initBuilder);

    function initBuilder() {
        const sectionList = document.getElementById("sectionList");
        if (!sectionList) return;

        // Avoid double initialization
        if (sectionList.dataset.initialized === "1") return;
        sectionList.dataset.initialized = "1";

        // -----------------------------
        // LOCAL STORAGE FOR BLOCKS
        // -----------------------------
        const blocksState = JSON.parse(localStorage.getItem("SectionBlocksState") || "{}");

        document.querySelectorAll("[id^='blocks-']").forEach(el => {
            const id = el.id.replace("blocks-", "");
            if (blocksState[id]) {
                el.classList.remove("hidden");
                document.getElementById("arrow-" + id)?.classList.add("rotate-90");
            }
        });

        // -----------------------------
        // SORTABLE (ONLY ONCE)
        // -----------------------------
        Sortable.create(sectionList, {
            animation: 150,
            ghostClass: "bg-gray-100",
            handle: ".section-drag-handle",
            onStart() {
                window._dragging = true
            },
            onEnd() {
                window._dragging = false;

                const order = {};
                sectionList.querySelectorAll("li[data-id]").forEach((el, index) => {
                    order[el.dataset.id] = index + 1;
                });

                fetch(window.sectionReorderUrl, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.csrfToken,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        order
                    })
                }).then(() => {
                    reloadPreview();
                });
            }

        });

        // -----------------------------
        // CLICK HANDLER (ONLY ONE)
        // -----------------------------
        sectionList.addEventListener("click", (e) => {
            if (window._dragging) return;

            const arrowBtn = e.target.closest("[id^='section-btn-']");
            const header = e.target.closest(".section-header");

            // 1) ----------------- ARROW CLICK → OPEN/CLOSE BLOCKS ---------------------
            if (arrowBtn) {
                e.stopPropagation();

                const id = arrowBtn.id.replace("section-btn-", "");
                const blocks = document.getElementById("blocks-" + id);
                const arrow = document.getElementById("arrow-" + id);

                const open = !blocks.classList.toggle("hidden");
                arrow.classList.toggle("rotate-90", open);

                blocksState[id] = open;
                localStorage.setItem("SectionBlocksState", JSON.stringify(blocksState));
                return;
            }

            // 2) ----------------- HEADER CLICK → OPEN EDIT FORM ---------------------
            if (header) {
                e.stopPropagation();

                const li = header.closest("li[data-id]");
                const id = li.dataset.id;

                const editForm = document.getElementById("edit-form-" + id);
                const blocks = document.getElementById("blocks-" + id);
                const arrow = document.getElementById("arrow-" + id);

                // 🔴 CLOSE ALL BLOCK EDIT FORMS
                document.querySelectorAll(".edit-block-form").forEach(f => {
                    f.classList.add("hidden");
                });
                window.currentOpenBlockId = null;

                const isOpening = editForm.classList.contains("hidden");

                // 🔹 SAME SECTION → CLOSE
                if (!isOpening && window.currentOpenSectionId === id) {
                    editForm.classList.add("hidden");

                    clearPreviewHighlights(); // ✅ FIX

                    window.currentOpenSectionId = null;
                    return;
                }

                // 🔹 OPEN NEW SECTION

                // close all section forms
                document.querySelectorAll(".section-edit-form").forEach(f => {
                    f.classList.add("hidden");
                });

                clearPreviewHighlights(); // ✅ FIX

                editForm.classList.remove("hidden");

                const previewEl =
                    document.getElementById("livePreviewContent")
                    ?.contentWindow
                    ?.document
                    ?.querySelector(`[data-section-id="${id}"]`);

                previewEl?.classList.add("preview-active");

                blocks?.classList.remove("hidden");
                arrow?.classList.add("rotate-90");

                window.currentOpenSectionId = id;
            }
        });

        // -----------------------------
        // TOGGLE ACTIVE
        // -----------------------------
        document.querySelectorAll(".toggle-active-btn").forEach(btn => {
            btn.onclick = (e) => {
                e.stopPropagation();
                const id = btn.dataset.sectionId;

                fetch(`/admin/builder/sections/${id}/toggle-active`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": window.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(d => {
                        btn.innerHTML = d.is_active ?
                            '<i class="fa-solid fa-eye"></i>' :
                            '<i class="fa-solid fa-eye-slash"></i>';

                        document.getElementById("livePreviewContent")?.contentWindow.location.reload();
                    });
            };
        });

        // -----------------------------
        // DELETE SECTION
        // -----------------------------
        document.querySelectorAll(".delete-section-form .delete-btn").forEach(btn => {
            btn.onclick = (e) => {
                e.stopPropagation();
                const form = btn.closest(".delete-section-form");
                const id = form.dataset.sectionId;

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": window.csrfToken,
                            "X-HTTP-Method-Override": "DELETE",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.status === "success") {
                            const li = document.getElementById("section-" + id);
                            li.style.opacity = 0;
                            setTimeout(() => li.remove(), 300);

                            document.getElementById("livePreviewContent")?.contentWindow.location.reload();
                        }
                    });
            };
        });

        function reloadPreview() {
            const iframe = document.getElementById("livePreviewContent");
            if (!iframe) return;
            iframe.src = iframe.src.split("?")[0] + "?v=" + Date.now();
        }

    }
</script>
