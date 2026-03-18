{{-- Header --}}
<div class="bg-primary border-rounded border-primary overflow-hidden mb-4">
    <div class="flex justify-between items-center py-3 px-4">
        <div>
            <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i
                    class="fa-solid fa-credit-card mr-1 text-base"></i>
                Plans Management
            </h2>
            <p class="text-sm text-secondary hidden sm:block">Manage all plans Arzavo tenants</p>
        </div>

        <div class="right-content flex gap-2 items-center">
            <!-- Search Bar -->
            <input type="text" id="courseSearch" placeholder="Search tenant..."
                class="px-3 py-2 text-sm bg-primary border-primary border-rounded input-focus hidden sm:block">


            <!-- Add View Toggle Button Cards/Table -->
            <div class="border-primary border-rounded hidden sm:flex">
                <button class="p-2 text-sm bg-secondary border-rounded"
                    onclick="toggleView('subjectsContainer', 'grid')">
                    <i class="fa fa-th-large"></i>
                </button>
                <button class="p-2 text-sm bg-primary border-rounded" onclick="toggleView('subjectsContainer', 'list')">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <button class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert"
                onclick="document.getElementById('planModal').classList.remove('hidden')">
                Add New
                <i class="fa fa-square-plus ml-1"></i>
            </button>
        </div>
    </div>
</div>