<div class="sections-tab pb-10">
    @include('admin.customizes.sections.section-add')
    <ul id="sectionList" class="sortable-section-list">
        @foreach($sections as $section)
        <li id="section-{{ $section->id }}" class="border-bottom cursor-pointer select-none" data-id="{{ $section->id }}">
            <div class="flex justify-between items-center px-4 py-3">
                <span class="font-medium text-sm">
                    <i class="fa-solid fa-code"></i> &nbsp;{{ $section->name }}
                </span>
                <div class="flex gap-3">
                    <button type="button" class="toggle-active-btn" data-section-id="{{ $section->id }}">
                        @if($section->is_active)
                        <i class="fa-solid fa-toggle-on text-green-500"></i>
                        @else
                        <i class="fa-solid fa-toggle-off text-gray-400"></i>
                        @endif
                    </button>
                    <form class="delete-section-form" data-section-id="{{ $section->id }}" action="{{ route('admin.builder.sections.destroy', $section->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-btn text-tertiary">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @include('admin.customizes.sections.section-edit')
        </li>
        @endforeach

        <li class="cursor-pointer border-bottom text-primary hover:bg-gray-50 font-semibold text-center p-3"
            onclick="document.getElementById('addSectionContainer').classList.remove('hidden')">
            Add New Section <i class="fa-solid fa-plus"></i>
        </li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sectionList = document.getElementById("sectionList");
        if (!sectionList) return;

        let isDragging = false;
        let dragTimer = null;

        const sortable = Sortable.create(sectionList, {
            animation: 150,
            ghostClass: "bg-gray-100",
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

        // ✅ Detect drag vs click
        sectionList.addEventListener("mousedown", () => {
            dragTimer = setTimeout(() => {
                isDragging = true;
            }, 150); // short delay helps distinguish drag
        });
        sectionList.addEventListener("mouseup", () => {
            clearTimeout(dragTimer);
            setTimeout(() => {
                isDragging = false;
            }, 100);
        });

        // ✅ Open/close edit section on click (not during drag)
        sectionList.addEventListener("click", e => {
            if (isDragging) return;
            const li = e.target.closest("li[data-id]");
            if (!li) return;

            // Ignore buttons/forms
            if (e.target.closest(".toggle-active-btn, .delete-section-form")) return;
            if (e.target.closest("form, input, textarea, select, button, label")) return;

            const id = li.dataset.id;
            const editForm = document.getElementById(`edit-form-${id}`);
            if (editForm) editForm.classList.toggle("hidden");
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
                                '<i class="fa-solid fa-toggle-on text-green-500"></i>' :
                                '<i class="fa-solid fa-toggle-off text-gray-400"></i>';
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
                if (!confirm("Are you sure you want to delete this section?")) return;

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