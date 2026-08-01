@extends('layouts.admin')
@section('title', 'Edit Book Details')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-6">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-edit mr-1 text-base"></i>
            Edit Book & Notes Details
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Modify book details and adjust publishing constraints</p>
    </div>

    <a href="{{ route('admin.books.index') }}"
        class="px-3 py-2 text-sm bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1">
        <i class="fa fa-arrow-left"></i>
        Back to List
    </a>
</div>

{{-- Edit Form --}}
<form action="{{ route('admin.books.update', $book->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: Basic Info & Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Basic Info Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Basic Book Information</h3>
                
                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Book Title <span class="text-accent">*</span></label>
                        <input type="text" name="title" required value="{{ $book->title }}" placeholder="e.g. HC Verma Concepts of Physics Vol 1"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <x-input.textarea name="short_description" label="Short Description / Catchphrase" :value="$book->short_description" rows="2" placeholder="e.g. Essential physics guide for JEE Main & Advanced aspirants..." />
                    </div>

                    {{-- Author / Publisher --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-tertiary text-xs font-semibold mb-1">Author Name</label>
                            <input type="text" name="author" value="{{ $book->author }}" placeholder="e.g. Dr. H.C. Verma"
                                class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        </div>
                        <div>
                            <label class="block text-tertiary text-xs font-semibold mb-1">Publisher</label>
                            <input type="text" name="publisher" value="{{ $book->publisher }}" placeholder="e.g. Bharati Bhawan"
                                class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        </div>
                    </div>

                    {{-- Edition / ISBN / Pages --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-tertiary text-xs font-semibold mb-1">Edition / Year</label>
                            <input type="text" name="edition" value="{{ $book->edition }}" placeholder="e.g. 2026 Edition"
                                class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        </div>
                        <div>
                            <label class="block text-tertiary text-xs font-semibold mb-1">ISBN Number</label>
                            <input type="text" name="isbn" value="{{ $book->isbn }}" placeholder="e.g. 978-3-16-148410-0"
                                class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        </div>
                        <div>
                            <label class="block text-tertiary text-xs font-semibold mb-1">Pages Count</label>
                            <input type="number" name="pages_count" value="{{ $book->pages_count }}" min="0" placeholder="e.g. 350"
                                class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Description Standalone Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Full Book Description & Detailed Overview</h3>
                <x-input.rich-text name="description" label="" :value="$book->description" placeholder="Detailed summary, chapter breakdown, and syllabus overview..." />
            </div>

            {{-- Highlights Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <div class="flex justify-between items-center mb-4 border-bottom pb-2">
                    <h3 class="text-base font-bold text-primary">Key Highlights & Features</h3>
                    <button type="button" onclick="addHighlightRow()" class="px-2.5 py-1 text-xs bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1 font-medium">
                        <i class="fa fa-plus"></i> Add Point
                    </button>
                </div>
                <p class="text-xs text-secondary mb-3">Add bullet point highlights about key features or contents of this book.</p>
                <div id="highlightsContainer" class="space-y-2">
                    @if(!empty($book->highlights) && is_array($book->highlights))
                        @foreach($book->highlights as $highlight)
                            <div class="flex items-center gap-2 highlight-row">
                                <i class="fa fa-check-circle text-emerald-500 text-sm"></i>
                                <input type="text" name="highlights[]" value="{{ $highlight }}" placeholder="e.g. Key Highlight / Feature..." class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                                <button type="button" onclick="this.closest('.highlight-row').remove()" class="p-2 text-red-500 hover:bg-red-50 rounded">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Multiple Preview Images Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <div class="flex justify-between items-center mb-4 border-bottom pb-2">
                    <h3 class="text-base font-bold text-primary">Book Preview Images (Gallery)</h3>
                    <button type="button" onclick="addPreviewRow()" class="px-2.5 py-1 text-xs bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1 font-medium">
                        <i class="fa fa-plus"></i> Add Preview Image
                    </button>
                </div>
                <p class="text-xs text-secondary mb-3">Upload multiple sample page images or table of contents photos for students to view online.</p>
                <div id="previewsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($book->previews && $book->previews->count() > 0)
                        @foreach($book->previews as $idx => $preview)
                            @php $uid = 'prv_existing_' . $preview->id; @endphp
                            <div class="border-primary border-rounded p-3 bg-secondary relative group preview-row space-y-2">
                                <button type="button" onclick="this.closest('.preview-row').remove()" class="absolute top-2 right-2 z-10 p-1.5 bg-red-500 text-white rounded-full opacity-80 hover:opacity-100 shadow">
                                    <i class="fa fa-times text-xs"></i>
                                </button>
                                <x-input.image name="preview_images[]" :value="$preview->file_path" label="Preview Image" />
                                <input type="text" name="preview_titles[]" value="{{ $preview->title }}" placeholder="Image Title/Caption (optional)" class="w-full p-1.5 bg-primary border-primary border-rounded text-xs">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Pricing Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Pricing & Monetization</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Price Type</label>
                        <select name="price_type" id="priceTypeSelector" onchange="togglePriceFields()"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="free" {{ $book->price_type === 'free' ? 'selected' : '' }}>Free Access</option>
                            <option value="paid" {{ $book->price_type === 'paid' ? 'selected' : '' }}>Paid Purchase</option>
                        </select>
                    </div>

                    <div class="paid-fields {{ $book->price_type === 'paid' ? '' : 'hidden' }}">
                        <label class="block text-tertiary text-xs font-semibold mb-1">Regular Price (₹)</label>
                        <input type="number" step="0.01" name="price" value="{{ $book->price }}" placeholder="0.00"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    <div class="paid-fields {{ $book->price_type === 'paid' ? '' : 'hidden' }}">
                        <label class="block text-tertiary text-xs font-semibold mb-1">Offer Sale Price (₹)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ $book->sale_price }}" placeholder="0.00"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Uploads, Categorization & Access --}}
        <div class="space-y-6">

            {{-- Files & Cover Image --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Media & Document Upload</h3>
                
                <div class="space-y-4">
                    {{-- Cover Image --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Book Cover Thumbnail</label>
                        <x-input.image name="cover_image" :value="$book->cover_image" />
                    </div>

                    {{-- PDF file --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Full Book / Notes PDF <span class="text-accent">*</span></label>
                        <x-input.content name="file_path" type="pdf" :value="$book->file_path" label="Book PDF Document" icon="fa-file-pdf" />
                    </div>
                </div>
            </div>

            {{-- Categorization --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Library & Academic Category</h3>
                
                <div class="space-y-4">
                    {{-- Book Category --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Library Book Category <span class="text-accent">*</span></label>
                        <select name="book_category_id" required class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($bookCategories as $cat)
                                <option value="{{ $cat->id }}" {{ $book->book_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Academic Category --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Course Stream/Category (optional)</label>
                        <select name="academic_category_id" id="academicCategorySelector" onchange="filterClasses()" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="">-- General / Non-academic --</option>
                            @foreach($academicCategories as $acat)
                                <option value="{{ $acat->id }}" {{ $book->academic_category_id == $acat->id ? 'selected' : '' }}>{{ $acat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Linked Class/Course (optional)</label>
                        <select name="class_course_id" id="classCourseSelector" onchange="filterSubjects()" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="">-- No Class Linked --</option>
                            @foreach($classes as $cls)
                                @if(blank($book->academic_category_id) || $book->academic_category_id == $cls->academic_category_id)
                                    <option value="{{ $cls->id }}" {{ $book->class_course_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Linked Subject (optional)</label>
                        <select name="subject_id" id="subjectSelector" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="">-- No Subject Linked --</option>
                            @foreach($subjects as $sub)
                                @if(blank($book->class_course_id) || $book->class_course_id == $sub->class_courses_id)
                                    <option value="{{ $sub->id }}" {{ $book->subject_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Access & Toggles --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Access Control & Visibility</h3>

                <div class="space-y-4">
                    {{-- Access Type --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Who can view / download?</label>
                        <select name="access_type" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="public" {{ $book->access_type === 'public' ? 'selected' : '' }}>Anyone (Public)</option>
                            <option value="students_only" {{ $book->access_type === 'students_only' ? 'selected' : '' }}>Registered Students Only</option>
                            <option value="enrolled_students_only" {{ $book->access_type === 'enrolled_students_only' ? 'selected' : '' }}>Enrolled Students Only (Linked Courses)</option>
                        </select>
                    </div>

                    {{-- Switches --}}
                    <div class="flex flex-col gap-3 justify-center border-top pt-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-sm font-semibold text-primary">Publish Status</span>
                                <p class="text-xs text-secondary">Visible in library directories</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $book->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                            </label>
                        </div>

                        <div class="flex justify-between items-center border-top pt-2">
                            <div>
                                <span class="text-sm font-semibold text-primary">Featured Material</span>
                                <p class="text-xs text-secondary">Pin to homepage/featured section</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" {{ $book->is_featured ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Submission --}}
            <div class="bg-primary border-primary border-rounded p-4 flex gap-2">
                <button type="submit" class="w-full py-3 bg-invert text-invert border-rounded font-bold text-center hover-invert text-sm">
                    Save Book Changes
                </button>
            </div>

        </div>

    </div>
</form>

<script>
    const allClassCourses = @json($classes);
    const allSubjects = @json($subjects);

    // Existing values
    const selectedClassId = @json($book->class_course_id);
    const selectedSubjectId = @json($book->subject_id);

    function addHighlightRow() {
        const container = document.getElementById('highlightsContainer');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 highlight-row';
        row.innerHTML = `
            <i class="fa fa-check-circle text-emerald-500 text-sm"></i>
            <input type="text" name="highlights[]" placeholder="e.g. Key Highlight / Feature..." class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
            <button type="button" onclick="this.closest('.highlight-row').remove()" class="p-2 text-red-500 hover:bg-red-50 rounded">
                <i class="fa fa-trash"></i>
            </button>
        `;
        container.appendChild(row);
    }

    let previewCounter = 0;
    function addPreviewRow() {
        previewCounter++;
        const container = document.getElementById('previewsContainer');
        const uid = 'prv_' + Date.now() + '_' + previewCounter;
        const col = document.createElement('div');
        col.className = 'border-primary border-rounded p-3 bg-secondary relative group preview-row space-y-2';
        col.innerHTML = `
            <button type="button" onclick="this.closest('.preview-row').remove()" class="absolute top-2 right-2 z-10 p-1.5 bg-red-500 text-white rounded-full opacity-80 hover:opacity-100 shadow">
                <i class="fa fa-times text-xs"></i>
            </button>
            <div class="image-field-${uid} relative group border-primary border-rounded overflow-hidden">
                <div data-content-wrapper>
                    <input type="hidden" name="preview_images[]" id="${uid}">
                    <div class="border-primary border-rounded bg-secondary flex flex-col justify-center items-center aspect-video cursor-pointer group relative overflow-hidden" style="border-width:2px;border-style:dashed" onclick="openContentPicker('${uid}','image')">
                        <img data-content-preview class="hidden object-contain border-rounded">
                        <div data-content-placeholder class="flex flex-col items-center text-tertiary h-full justify-center">
                            <i class="fa-solid fa-image text-2xl mb-2"></i>
                            Select Preview Image
                        </div>
                    </div>
                </div>
            </div>
            <input type="text" name="preview_titles[]" placeholder="Image Title/Caption (optional)" class="w-full p-1.5 bg-primary border-primary border-rounded text-xs">
        `;
        container.appendChild(col);
    }

    function filterClasses(preselectedId = null) {
        const categoryId = document.getElementById('academicCategorySelector').value;
        const classSelector = document.getElementById('classCourseSelector');
        const subjectSelector = document.getElementById('subjectSelector');

        // Reset class and subject selectors
        classSelector.innerHTML = '<option value="">-- No Class Linked --</option>';
        subjectSelector.innerHTML = '<option value="">-- No Subject Linked --</option>';

        if (categoryId) {
            const filteredClasses = allClassCourses.filter(cls => String(cls.academic_category_id) === String(categoryId));
            filteredClasses.forEach(cls => {
                const opt = document.createElement('option');
                opt.value = cls.id;
                opt.textContent = cls.name;
                if (preselectedId && String(cls.id) === String(preselectedId)) {
                    opt.selected = true;
                }
                classSelector.appendChild(opt);
            });
        }
    }

    function filterSubjects(preselectedId = null) {
        const classId = document.getElementById('classCourseSelector').value;
        const subjectSelector = document.getElementById('subjectSelector');

        // Reset subject selector
        subjectSelector.innerHTML = '<option value="">-- No Subject Linked --</option>';

        if (classId) {
            const filteredSubjects = allSubjects.filter(sub => String(sub.class_courses_id) === String(classId));
            filteredSubjects.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                if (preselectedId && String(sub.id) === String(preselectedId)) {
                    opt.selected = true;
                }
                subjectSelector.appendChild(opt);
            });
        }
    }

    // Run on page load if needed
    document.addEventListener('DOMContentLoaded', function() {
        if (selectedClassId && (!document.getElementById('classCourseSelector').value || document.getElementById('classCourseSelector').value == "")) {
            filterClasses(selectedClassId);
        }
        if (selectedSubjectId && (!document.getElementById('subjectSelector').value || document.getElementById('subjectSelector').value == "")) {
            filterSubjects(selectedSubjectId);
        }
    });

    function togglePriceFields() {
        const selector = document.getElementById('priceTypeSelector');
        const paidFields = document.querySelectorAll('.paid-fields');
        if (selector.value === 'paid') {
            paidFields.forEach(el => el.classList.remove('hidden'));
        } else {
            paidFields.forEach(el => el.classList.add('hidden'));
        }
    }
</script>
@endsection
