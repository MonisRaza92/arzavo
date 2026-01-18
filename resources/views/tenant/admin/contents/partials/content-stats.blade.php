<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 bg-primary mt-2 border-top border-bottom p-4 gap-4">
    <div class="card border-rounded border-primary p-4 flex justify-between items-center">
        <div class="content">
            <h3 class="text-secondary font-semibold">Total Files</h3>
            <p class="text-4xl font-bold text-primary">{{ $contents->count() }}</p>
            <p class="text-xs text-tertiary mt-1">All uploaded media assets</p>
        </div>
        <div class="icon bg-invert text-invert border-rounded p-4">
            <i class="fa-solid fa-photo-film text-3xl"></i>
        </div>
    </div>
    <div class="card border-rounded border-primary p-4 flex justify-between items-center">
        <div class="content">
            <h3 class="text-secondary font-semibold">Videos</h3>
            <p class="text-4xl font-bold text-primary">{{ $contents->where('type' , 'video')->count() }}</p>
            <p class="text-xs text-tertiary mt-1">Lectures, Lessons & intro videos</p>
        </div>
        <div class="icon bg-invert text-invert border-rounded p-4">
            <i class="fa-solid fa-video text-3xl"></i>
        </div>
    </div>
    <div class="card border-rounded border-primary p-4 flex justify-between items-center">
        <div class="content">
            <h3 class="text-secondary font-semibold">Books & Notes</h3>
            <p class="text-4xl font-bold text-primary">{{ $contents->where('type' , 'pdf')->count() }}</p>
            <p class="text-xs text-tertiary mt-1">PDFs, notes & documents</p>
        </div>
        <div class="icon bg-invert text-invert border-rounded p-4">
            <i class="fa-solid fa-file-pdf text-3xl"></i>
        </div>
    </div>
    <div class="card border-rounded border-primary p-4 flex justify-between items-center">
        <div class="content">
            <h3 class="text-secondary font-semibold">Storage Used</h3>
            <p class="text-4xl font-bold text-primary">N/A</p>
            <p class="text-xs text-tertiary mt-1">Out of allocated storage</p>
        </div>
        <div class="icon bg-invert text-invert border-rounded p-4">
            <i class="fa-solid fa-hard-drive text-3xl"></i>
        </div>
    </div>
</div>
