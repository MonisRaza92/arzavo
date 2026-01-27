<div id="subjectAddPopup" class="hidden fixed inset-0 z-100 bg-black/90 flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <div class="p-4">

                {{-- Image --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Image (optional)
                    </label>
                    <x-input.image name="image" />
                </div>


                {{-- Name --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Subject Name <span class="text-accent">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        required
                        placeholder="e.g. Science, Mathematics"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Select Class --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Select Class <span class="text-accent">*</span>
                    </label>
                    <select name="class_course_name"
                        required
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        <option value="">Select a class</option>
                        @foreach ($classCourses as $class)
                            <option value="{{ $class->name }}" {{ request('class_filter') == $class->name ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Description
                    </label>
                    <textarea name="description"
                        rows="3"
                        placeholder="Short description"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm"></textarea>
                </div>

                {{-- Status --}}
                <div class="mb-4 p-3 border-primary border-rounded block w-full">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-1">
                                Status
                            </label>
                            <span class="text-tertiary text-xs">
                                Enable this class to make it visible to students
                            </span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                name="status"
                                value="1"
                                checked
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex justify-end gap-2 border-top p-4 sticky bottom-0 bg-primary">
                <button type="button"
                    onclick="document.getElementById('subjectAddPopup').classList.add('hidden')"
                    class="px-4 py-2 text-xs bg-secondary text-secondary bg-hover-tertiary border-rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-invert text-invert border-rounded hover-invert">
                    Save
                </button>
            </div>
        </form>

    </div>
</div>
