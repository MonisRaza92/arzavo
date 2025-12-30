<div id="classAddPopup" class="hidden fixed inset-0 z-40 bg-invert-secondary flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        <form action="{{ route('admin.classes.courses.store') }}" method="POST">
            @csrf
            <div class="p-4">

                {{-- Image --}}
                <div class="mb-3">

                    <label class="block text-tertiary text-xs mb-1" onclick="openImageMenu('classImageInput')">
                        Image (optional)

                        {{-- Upload Area (JS ISKO DHUNDHTA HAI) --}}
                        <div class="relative bg-secondary border-primary border-rounded mt-2 group cursor-pointer">

                            {{-- Placeholder (JS ISKO REMOVE KARTA HAI) --}}
                            <div class="flex flex-col items-center justify-center h-32 text-tertiary text-xs">
                                <i class="fa fa-image text-lg mb-1"></i>
                                Click to select image
                            </div>

                        </div>

                        {{-- HIDDEN INPUT (JS ISME VALUE DALTA HAI) --}}
                        <input type="text"
                            name="image"
                            id="classImageInput"
                            class="hidden">
                    </label>
                </div>


                {{-- Name --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Class / Course Name <span class="text-accent">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        required
                        placeholder="e.g. NEET 2026"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
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
            <div class="flex justify-end gap-2 border-top p-4">
                <button type="button"
                    onclick="document.getElementById('classAddPopup').classList.add('hidden')"
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