<div id="addModuleForm" class="hidden p-4 border-primary border-rounded bg-primary">
    <h2 class="font-semibold mb-4"><i class="fa-solid fa-layer-group text-sm mr-1"></i> Add New Group</h2>
        <input
            type="text"
            id="moduleTitle"
            placeholder="Group Name"
            class="w-full mb-2 p-2 border-primary border-rounded">

        <textarea
            id="moduleDescription"
            placeholder="Optional description"
            class="w-full p-2 border-primary border-rounded"></textarea>

        <div class="flex gap-2 mt-3">
            <x-button id="saveModuleBtn" variant="primary" loadingText="Saving...">Save</x-button>
            <x-button id="cancelModuleBtn" variant="secondary" :loading="false">Cancel</x-button>
        </div>
    </div>