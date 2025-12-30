{{-- Header --}}
<div class="bg-primary border-rounded border-primary overflow-hidden">
    <div class="flex justify-between items-center py-3 px-4">
        <div>
            <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-photo-film mr-1 text-base"></i>
                Content Management
            </h2>
            <p class="text-sm text-secondary hidden sm:block">Manage all study content for students and users at {{ strtolower(app('currentTenant')->name) }}</p>
        </div>

        <div class="right-content flex gap-2 items-center">
            <!-- Search Bar -->
            <input type="text"
                id="contentSearch"
                placeholder="Search content..."
                class="px-3 py-2 text-sm bg-primary border-primary border-rounded input-focus hidden sm:block">

            <!-- Select class -->
            <div class="md:flex border-primary border-rounded hidden pr-2">
                <form action="" method="get">
                    <select name="class_filter" id="classFilter"
                        class="px-3 py-2 text-sm border-rounded"
                        onchange="this.form.submit()">
                        <option value="">All Files</option>
                    </select>
                </form>
            </div>

            <!-- Add View Toggle Button Cards/Table -->
            <div class="border-primary border-rounded hidden sm:flex">
                <button class="p-2 text-sm bg-secondary border-rounded"
                    onclick="toggleView('classCourseContainer', 'grid')">
                    <i class="fa fa-th-large"></i>
                </button>
                <button class="p-2 text-sm bg-primary border-rounded"
                    onclick="toggleView('classCourseContainer', 'list')">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <button
                class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert"
                onclick="document.getElementById('contentAddPopup').classList.remove('hidden')">
                Add New
                <i class="fa fa-square-plus ml-1"></i>
            </button>
        </div>
    </div>
    @include('tenant.admin.contents.partials.content-stats')
    <div class="filter p-4 flex justify-between items-center">
        <div class="type-filter flex gap-2">
            <button class="bg-invert text-invert px-4 py-3 text-sm font-semibold border-rounded border-primary">All Files</button>
            <button class="bg-primary text-primary px-4 py-3 text-sm font-semibold border-rounded border-primary">Videos</button>
            <button class="bg-primary text-primary px-4 py-3 text-sm font-semibold border-rounded border-primary">Books & Notes</button>
        </div>
        <div class="sort items-center gap-3 hidden md:flex">

            <label class="text-sm text-tertiary font-medium">
                Sort by
            </label>

            <select
                class="bg-primary text-primary px-4 py-3 text-sm border-rounded border-primary focus:outline-none">

                <option value="latest">Latest Upload</option>
                <option value="oldest">Oldest Upload</option>
                <option value="name_asc">Name (A–Z)</option>
                <option value="name_desc">Name (Z–A)</option>
                <option value="size_desc">File Size (Large → Small)</option>
                <option value="size_asc">File Size (Small → Large)</option>

            </select>

        </div>

    </div>
</div>