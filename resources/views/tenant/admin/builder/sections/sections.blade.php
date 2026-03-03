<div class="sections-tab w-full overflow-auto scrollbar" id="section-tab-scroll">
    {{-- HEADER --}}
    @include('tenant.admin.builder.sections.section-group', [
        'title' => 'Header',
        'sections' => $globalLayout['header']['sections'] ?? [],
        'target' => 'header',
        'rules' => $sectionRules
    ])

    {{-- PAGE / LAYOUT --}}
    @include('tenant.admin.builder.sections.section-group', [
        'title' => 'Layout',
        'sections' => $layout['sections'] ?? [],
        'target' => 'page',
        'rules' => $sectionRules
    ])

    {{-- FOOTER --}}
    @include('tenant.admin.builder.sections.section-group', [
        'title' => 'Footer',
        'sections' => $globalLayout['footer']['sections'] ?? [],
        'target' => 'footer',
        'rules' => $sectionRules
    ])

</div>

@include('tenant.admin.builder.sections.section-add')

<script>
    window._ARZAVO_SECTION_TARGET = 'page';

    function openAddSection(target) {
        const modal = document.getElementById('addSectionContainer');
        window._ARZAVO_SECTION_TARGET = target;

        filterSectionsByTarget(target); // 🔥 MAGIC LINE

        modal.classList.remove('hidden');
    }


    window.csrfToken = "{{ csrf_token() }}";
    window.sectionReorderUrl = "{{ route('admin.builder.sections.reorder', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => 'section']) }}";

    document.addEventListener("turbo:load", initBuilder);
    document.addEventListener("turbo:render", initBuilder);

    function initBuilder() {
        document.querySelectorAll(".section-list").forEach(sectionList => {
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
        sectionList.addEventListener("click", (e) => {
            // -----------------------------
            // TOGGLE SECTION ACTIVE
            // -----------------------------
            const toggleBtn = e.target.closest(".toggle-active-btn");
            if (toggleBtn) {
                e.stopPropagation();

                const sectionId = toggleBtn.dataset.sectionId;
                const themeid = "{{ $theme->id }}";
                const pageId = "{{ $page->id }}";

                fetch(`/admin/builder/${themeid}/${pageId}/${sectionId}/toggle-active`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.csrfToken
                    }
                })
                    .then(res => res.json())
                    .then(d => {
                        toggleBtn.innerHTML = d.is_active
                            ? '<i class="fa-solid fa-eye"></i>'
                            : '<i class="fa-solid fa-eye-slash"></i>';

                        reloadPreview();
                    });

                return;
            }


            // -----------------------------
            // DELETE SECTION
            // -----------------------------
            const deleteBtn = e.target.closest(".delete-btn");
            if (deleteBtn) {
                e.stopPropagation();

                const form = deleteBtn.closest(".delete-section-form");
                const id = form.dataset.sectionId;

                if (!confirm("Delete this section?")) return;

                fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.csrfToken,
                        "X-HTTP-Method-Override": "DELETE"
                    }
                })
                    .then(r => r.json())
                    .then(d => {
                        if (d.status === "success") {
                            const li = document.getElementById("section-" + id);
                            li.style.opacity = 0;
                            setTimeout(() => li.remove(), 300);

                            reloadPreview();
                        }
                    });

                return;
            }
        });
    });


    
}


function reloadPreview() {
    const iframe = document.getElementById("livePreviewContent");
    if (!iframe || !iframe.contentWindow) return;

    iframe.contentWindow.location.reload();
}

window.reloadPreview = reloadPreview;

</script>