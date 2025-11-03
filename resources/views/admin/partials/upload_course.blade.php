<form id="upload-course-modal" class="hidden rounded-md p-4 mt-4 w-full" style="background-color:var(--secondary-background); border: 1px solid var(--border-color);" method="POST" action="{{ route('upload-course') }}" enctype="multipart/form-data">
    <h2 class="text-2xl font-bold mb-3 pb-3" style="color: var(--primary-color); border-bottom: 1px solid var(--border-color);">Upload New Course</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <!-- Title -->
        @csrf
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Title</label>
            <input required name="title" value="{{ old('title') }}" type="text" placeholder="Ex: Mastering Laravel 10" class="mt-2 block w-full rounded-md p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <!-- Slug -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Category</label>
            <select name="category_id" type="text" placeholder="Select category" class="mt-2 block w-full rounded-md p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="0" selected>Select Category</option>
                @foreach ($categories as $category )
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Subject</label>
            <select name="subject_id" type="text" placeholder="Ex: Web Development" class="mt-2 block w-full rounded-md p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="0">Select Subject</option>
                @foreach ($subjects as $subject )
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Class -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Class</label>
            <select name="class_id" class="mt-2 block w-full rounded-md p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="English">Select Class</option>
                @foreach ($classes as $class )
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Language -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Language</label>
            <select name="language" class="mt-2 block w-full rounded-md p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="English">English</option>
                <option value="Hindi">Hindi</option>
            </select>
        </div>

        <!-- Price -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Price (INR)</label>
            <input name="price" type="number" value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <!-- Discount Price -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Discount Price (optional)</label>
            <input name="discount_price" type="number" value="{{ old('discount_price') }}" step="0.01" min="0" placeholder="0.00" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <!-- Max Students -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Max Students (Optional)</label>
            <input name="max_students" type="number" value="{{ old('max_students') }}" min="1" placeholder="00" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <!-- Duration & Level -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Duration (minutes)</label>
            <input name="duration" type="number" value="{{ old('duration') }}" min="1" placeholder="Ex: 120" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Level</label>
            <select name="level" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="1">Beginner</option>
                <option value="2">Intermediate</option>
                <option value="3">Advanced</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Status</label>
            <select name="status" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <!-- Expire Date -->
        <div>
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Expire Date (optional)</label>
            <input name="expire_date" type="date" value="{{ old('expire_date') }}" class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);" />
        </div>

        <!-- Video Upload -->
        <div class="md:col-span-2">
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Video</label>
            <div class="mt-2 flex items-center gap-3">
                <input accept="video/*" name="video" id="videoInput" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-purple-600 file:to-cyan-400 file:text-white" />
                <div id="videoPreview" class="text-sm text-slate-500">No file chosen</div>
            </div>
        </div>

        <!-- Thumbnail Upload -->
        <div class="md:col-span-2">
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Thumbnail</label>
            <div class="mt-2 flex items-center gap-4">
                <input accept="image/*" name="thumbnail" id="thumbInput" type="file" class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white" />
                <img id="thumbPreview" class="h-20 w-32 object-cover rounded-md shadow-sm hidden" alt="thumb preview" />
            </div>
        </div>

        <!-- Description -->
        <div class="md:col-span-2">
            <label class="text-sm font-medium" style="color:var(--secondary-text-color);">Description</label>
            <textarea name="description" rows="5" placeholder="Write course description..." class="mt-2 block w-full rounded-lg border-gray-200 p-3" style="background-color:var(--background-color); border: 1px solid var(--border-color);">{{ old('description') }}</textarea>
        </div>

        <!-- Toggles -->
        <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #6f79c9);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">Featured</p>
                    <p class="text-xs text-slate-500">Show on homepage</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" class="sr-only peer" value="1">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-indigo-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #22d3ee);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">Popular</p>
                    <p class="text-xs text-slate-500">High enrollments</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="is_popular" value="0">
                    <input type="checkbox" name="is_popular" class="sr-only peer" value="1">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-cyan-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #fda4af);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">New</p>
                    <p class="text-xs text-slate-500">Recently added</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="is_new" value="0">
                    <input type="checkbox" name="is_new" class="sr-only peer" value="1" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-rose-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #84cc16);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">Recommended</p>
                    <p class="text-xs text-slate-500">Staff pick</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="is_recommended" value="0">
                    <input type="checkbox" name="is_recommended" class="sr-only peer" value="1">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-lime-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>

            <div class="col-span-2 flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #a3a3a3);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">Certified</p>
                    <p class="text-xs text-slate-500">Provide completion certificate</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="is_certified" value="0">
                    <input type="checkbox" name="is_certified" class="sr-only peer" value="1">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-zinc-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>

            <div class="col-span-2 flex items-center justify-between p-4 rounded-lg" style="background: linear-gradient(to right, var(--background-color), #a3a3a3);">
                <div>
                    <p class="text-sm font-medium" style="color:var(--secondary-text-color);">Allow Reviews</p>
                    <p class="text-xs text-slate-500">Enable students to review course</p>
                </div>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="hidden" name="allow_reviews" value="0">
                    <input type="checkbox" name="allow_reviews" class="sr-only peer" value="1" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-zinc-800"></div>
                    <span class="ml-3 text-sm font-medium text-slate-600"> </span>
                </label>
            </div>
        </div>

        <!-- User/Teacher selection -->
        <input name="user_id" type="number" value="{{ Auth::user()->id }}" placeholder="1" class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-300 p-3" hidden />
    </div>

    <!-- Submit -->
    <div class="mt-6 flex items-center justify-between gap-4">
        <div class="text-sm text-slate-500">All fields marked required will be validated on submit.</div>
        <div class="flex items-center gap-3">
            <button type="submit" class="default-button uppercase font-bold">Upload Course</button>
        </div>
    </div>
</form>
<script>
    // thumbnail preview
    document.getElementById('thumbInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const img = document.getElementById('thumbPreview');
        if (!file) {
            img.classList.add('hidden');
            return;
        }
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
    });

    // video filename preview
    document.getElementById('videoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const p = document.getElementById('videoPreview');
        p.textContent = file ? file.name + ' (' + Math.round(file.size / 1024) + ' KB)' : 'No file chosen';
    });

    // preview button: open a small modal with summary
    document.getElementById('previewBtn').addEventListener('click', function() {
        const form = document.getElementById('courseForm');
        const data = new FormData(form);
        const entries = {};
        for (const [k, v] of data.entries()) {
            if (k === 'thumbnail' || k === 'video') continue;
            entries[k] = v;
        }
        const summary = Object.entries(entries).map(([k, v]) => `<div><strong>${k}:</strong> ${v || '<em>—</em>'}</div>`).join('');
        const w = window.open('', 'preview', 'width=600,height=600');
        w.document.write(`<html><head><title>Preview</title><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width,initial-scale=1\"><style>body{font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial;margin:24px}</style></head><body><h2>Course Preview</h2>${summary}<p style=\"color:#666;font-size:13px;margin-top:12px\">(media files not shown)</p></body></html>`);
        w.document.close();
    });
</script>