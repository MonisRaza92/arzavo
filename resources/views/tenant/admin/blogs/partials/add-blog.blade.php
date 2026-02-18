<div id="blogAddPopup" class="hidden fixed inset-0 z-100 bg-black/90 flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        
        <form action="{{ route('admin.blog.store') }}" method="POST">
            @csrf

            <div class="p-4">

                {{-- Featured Image --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Featured Image (optional)
                    </label>
                    <x-input.image name="featured_image" />
                </div>

                {{-- Title --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Blog Title <span class="text-accent">*</span>
                    </label>
                    <input type="text"
                        name="title"
                        required
                        placeholder="Enter blog title"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Heading --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Heading (optional)
                    </label>
                    <input type="text"
                        name="heading"
                        placeholder="Main heading inside blog"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Content --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Content
                    </label>
                    <textarea name="content"
                        rows="6"
                        placeholder="Write blog content..."
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm"></textarea>
                </div>

                {{-- Publish Date --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Publish Date (optional)
                    </label>
                    <input type="datetime-local"
                        name="published_at"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Status --}}
                <div class="mb-4 p-3 border-primary border-rounded block w-full">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-1">
                                Status
                            </label>
                            <span class="text-tertiary text-xs">
                                Enable to publish this blog
                            </span>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                name="status"
                                value="published"
                                checked
                                class="sr-only peer">

                            <div class="w-11 h-6 bg-gray-400 rounded-full
                                peer peer-checked:after:translate-x-full
                                peer-checked:after:border-white
                                after:content-['']
                                after:absolute
                                after:top-0.5
                                after:left-0.5
                                after:bg-white
                                after:rounded-full
                                after:h-5
                                after:w-5
                                after:transition-all
                                peer-checked:bg-black">
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Meta Title --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Meta Title (SEO)
                    </label>
                    <input type="text"
                        name="meta_title"
                        placeholder="SEO title"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Meta Description --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Meta Description
                    </label>
                    <textarea name="meta_description"
                        rows="2"
                        placeholder="SEO description"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm"></textarea>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-2 border-top p-4 sticky bottom-0 bg-primary">

                <button type="button"
                    onclick="document.getElementById('blogAddPopup').classList.add('hidden')"
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