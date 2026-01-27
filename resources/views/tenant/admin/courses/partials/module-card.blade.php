<div id="module-{{ $module->id }}" class="p-4 border-primary border-rounded bg-primary">
    <div class="header">
        <div class="flex justify-between items-center">
            <h4 class="font-medium">{{ $module->title }}</h4>
            <div class="actions relative">
                <button onclick="toggleModel('moduleActions-{{ $module->id }}')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div id="moduleActions-{{ $module->id }}" class="menu absolute hidden right-0 bottom-full bg-primary w-42 z-50 border-primary border-rounded p-2">
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-edit mr-2"></i> Update</button>
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-up mr-2"></i> Move Up</button>
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-down mr-2"></i> Move Down</button>
                    <button type="button" onclick="deleteModule({{ $module->id }})" class="text-secondary block py-2 px-1 pt-3 border-top mt-2 text-left w-full text-sm"> <i class="fa-solid fa-trash mr-2 text-red-400"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        @if($module->description)
        <p class="text-sm text-tertiary mt-1">
            {{ $module->description }}
        </p>
        @endif
    </div>
</div>
<script>
    async function deleteModule(moduleId) {

        if (!confirm('Are you sure you want to delete this module with lessons?')) {
            return;
        }

        try {
            const res = await fetch(
                `/admin/courses/{{ $course->slug }}/modules/${moduleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }
            );

            if (!res.ok) {
                alert('Failed to delete module');
                return;
            }

            // Remove module card from DOM
            const el = document.getElementById(`module-${moduleId}`);
            if (el) {
                el.remove();
            }
        } catch (error) {
            console.error('Error deleting module:', error);
            alert('An error occurred while deleting the module');
        }
    }
</script>