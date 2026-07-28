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

                    {{-- Description --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Description / Syllabus Overview</label>
                        <textarea name="description" rows="4" placeholder="Brief summary of the book content..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">{{ $book->description }}</textarea>
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

            {{-- Access & Toggles --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Access Control & Visibility</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                    <div class="flex flex-col gap-3 justify-center">
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

        </div>

        {{-- RIGHT COLUMN: Uploads & Categorization --}}
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

                    {{-- Preview file --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Free Preview Sample PDF (optional)</label>
                        <x-input.content name="preview_file_path" type="pdf" :value="$book->preview_file_path" label="Preview PDF" icon="fa-file-pdf" />
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
                        </select>
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Linked Subject (optional)</label>
                        <select name="subject_id" id="subjectSelector" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                            <option value="">-- No Subject Linked --</option>
                        </select>
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

    function filterClasses(preselectedId = null) {
        const categoryId = document.getElementById('academicCategorySelector').value;
        const classSelector = document.getElementById('classCourseSelector');
        const subjectSelector = document.getElementById('subjectSelector');

        // Reset class and subject selectors
        classSelector.innerHTML = '<option value="">-- No Class Linked --</option>';
        subjectSelector.innerHTML = '<option value="">-- No Subject Linked --</option>';

        if (categoryId) {
            const filteredClasses = allClassCourses.filter(cls => cls.academic_category_id == categoryId);
            filteredClasses.forEach(cls => {
                const opt = document.createElement('option');
                opt.value = cls.id;
                opt.textContent = cls.name;
                if (preselectedId && cls.id == preselectedId) {
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
            const filteredSubjects = allSubjects.filter(sub => sub.class_courses_id == classId);
            filteredSubjects.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                if (preselectedId && sub.id == preselectedId) {
                    opt.selected = true;
                }
                subjectSelector.appendChild(opt);
            });
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('academicCategorySelector').value) {
            filterClasses(selectedClassId);
            if (selectedClassId) {
                filterSubjects(selectedSubjectId);
            }
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
