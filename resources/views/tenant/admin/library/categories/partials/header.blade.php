{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-tags mr-1 text-base"></i>
            Book Categories <span class="hidden sm:block">Management</span>
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Manage classifications of books & notes at {{ strtolower(app('currentTenant')->name) }}</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <!-- Add Category Button -->
        <button
            class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert"
            onclick="document.getElementById('categoryAddPopup').classList.remove('hidden')">
            Add New
            <i class="fa fa-square-plus ml-1"></i>
        </button>
    </div>
</div>
