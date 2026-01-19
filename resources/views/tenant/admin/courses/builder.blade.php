@extends('layouts.admin')

@section('content')
{{-- HEADER --}}
<x-header title="Course Builder" :sub-title="$course->title">
    <div class="flex gap-2">
        <a href="{{ route('admin.courses.index') }}" class="btn-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back
        </a>
        <a href="" target="_blank" class="btn-primary">
            Preview <i class="fa-solid fa-external-link-alt ml-1"></i>
        </a>
    </div>
</x-header>

@if (session('success'))
<x-alert type="success" :message="session('success')" />
@endif

{{-- TABS --}}
<div x-data="courseBuilder({{ $course->id }})" class="mt-6">

    {{-- Tab Headers --}}
    <div class="flex gap-4 border-b border-primary mb-6">
        <button @click="tab = 'curriculum'"
            :class="{ 'border-b-2 border-accent text-accent font-bold': tab === 'curriculum', 'text-tertiary': tab !== 'curriculum' }"
            class="pb-2 px-4 transition-colors">
            Curriculum
        </button>
        <button @click="tab = 'settings'"
            :class="{ 'border-b-2 border-accent text-accent font-bold': tab === 'settings', 'text-tertiary': tab !== 'settings' }"
            class="pb-2 px-4 transition-colors">
            Settings
        </button>
        <button @click="tab = 'pricing'"
            :class="{ 'border-b-2 border-accent text-accent font-bold': tab === 'pricing', 'text-tertiary': tab !== 'pricing' }"
            class="pb-2 px-4 transition-colors">
            Pricing
        </button>
    </div>


    {{-- CURRICULUM TAB --}}
    <div x-show="tab === 'curriculum'" x-transition>
        @include('tenant.admin.courses.partials.curriculum')
    </div>


    {{-- SETTINGS TAB --}}
    <div x-show="tab === 'settings'" style="display: none;" x-transition>
        <div class="p-6 bg-primary border-primary border-rounded max-w-4xl mx-auto space-y-6">
            <h3 class="text-xl font-bold mb-4">Course Settings</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <x-input.text name="title" x-model="settingsForm.title" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Slug</label>
                        <x-input.text name="slug" x-model="settingsForm.slug" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea x-model="settingsForm.description" rows="4" class="w-full p-2 bg-primary border-primary border-rounded"></textarea>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Thumbnail</label>
                        <input type="file" @change="handleThumbnailChange" class="block w-full text-sm text-tertiary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent file:text-invert hover:file:bg-accent-secondary" />
                        <div x-show="settingsForm.thumbnailPreview" class="mt-2">
                            <img :src="settingsForm.thumbnailPreview" class="w-full h-32 object-cover rounded-md border border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Level</label>
                        <x-input.select name="level" x-model="settingsForm.level">
                            <option value="">Select Level</option>
                            @foreach($levels as $level)
                            <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </x-input.select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Language</label>
                        <x-input.select name="language" x-model="settingsForm.language">
                            <option value="">Select Language</option>
                            @foreach($languages as $lang)
                            <option value="{{ $lang }}">{{ $lang }}</option>
                            @endforeach
                        </x-input.select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-primary">
                <button @click="updateSettings()" class="btn-primary">Save Changes</button>
            </div>
        </div>
    </div>


    {{-- PRICING TAB --}}
    <div x-show="tab === 'pricing'" style="display: none;" x-transition>
        <div class="p-6 bg-primary border-primary border-rounded max-w-2xl mx-auto space-y-6">
            <h3 class="text-xl font-bold mb-4">Pricing & Access</h3>

            <div class="space-y-4">
                {{-- Is Free Toggle --}}
                <div>
                    <x-input.toggle name="is_free" label="This course is Free" x-model="pricingForm.is_free" />
                </div>

                <div x-show="!pricingForm.is_free" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Price</label>
                        <x-input.number name="price" x-model="pricingForm.price" placeholder="e.g. 499" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Discount Price (Optional)</label>
                        <x-input.number name="discount_price" x-model="pricingForm.discount_price" placeholder="e.g. 299" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-primary">
                <button @click="updatePricing()" class="btn-primary">Update Pricing</button>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('courseBuilder', (courseId) => ({
            tab: 'curriculum',
            courseId: courseId,

            // CURRICULUM STATE
            showModuleModal: false,
            showLessonModal: false,
            moduleMode: 'add',
            currentModuleId: null,
            lessonMode: 'add',
            currentLessonId: null,
            moduleForm: {
                title: ''
            },
            lessonForm: {
                title: '',
                type: 'video',
                moduleId: null,
                video_path: '',
                content: '',
                duration: '',
                is_free: false
            },

            // SETTINGS STATE
            settingsForm: {
                title: '{{ addslashes($course->title) }}',
                slug: '{{ $course->slug }}',
                description: `{{ addslashes($course->description) }}`,
                level: '{{ $course->level }}',
                language: '{{ $course->language }}',
                thumbnail: null,
                thumbnailPreview: '{{ $course->thumbnail ? asset($course->thumbnail) : "" }}'
            },

            // PRICING STATE
            pricingForm: {
                price: '{{ $course->price }}',
                discount_price: '{{ $course->discount_price }}',
                is_free: {
                    {
                        $course - > is_free ? 'true' : 'false'
                    }
                }
            },

            // --- CURRICULUM METHODS ---
            openAddModuleModal() {
                this.moduleMode = 'add';
                this.moduleForm.title = '';
                this.showModuleModal = true;
            },

            editModule(id, title) {
                this.moduleMode = 'edit';
                this.currentModuleId = id;
                this.moduleForm.title = title;
                this.showModuleModal = true;
            },

            openAddLessonModal(moduleId) {
                this.lessonMode = 'add';
                this.lessonForm.moduleId = moduleId;
                this.lessonForm.title = '';
                // Reset defaults
                this.lessonForm.type = 'video';
                this.lessonForm.video_path = '';
                this.lessonForm.content = '';
                this.lessonForm.duration = '';
                this.lessonForm.is_free = false;
                this.showLessonModal = true;
            },

            editLesson(lesson) {
                this.lessonMode = 'edit';
                this.currentLessonId = lesson.id;
                this.lessonForm.title = lesson.title;
                this.lessonForm.type = lesson.type;
                this.lessonForm.video_path = lesson.video_path || '';
                this.lessonForm.content = lesson.content || '';
                this.lessonForm.duration = lesson.duration || '';
                this.lessonForm.is_free = Boolean(lesson.is_free);
                this.showLessonModal = true;
            },

            async submitModule() {
                if (!this.moduleForm.title) return alert('Title is required');
                try {
                    let url = this.moduleMode === 'add' ?
                        `{{ route('admin.courses.modules.store', ':id') }}`.replace(':id', this.courseId) :
                        `{{ route('admin.courses.modules.update', ':id') }}`.replace(':id', this.currentModuleId);
                    let method = this.moduleMode === 'add' ? 'POST' : 'PUT';
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            title: this.moduleForm.title
                        })
                    });
                    if (response.ok) window.location.reload();
                    else alert('Error saving section');
                } catch (e) {
                    console.error(e);
                    alert('An error occurred');
                }
            },

            async deleteModule(id) {
                if (!confirm('Are you sure? This will delete all lessons in this section.')) return;
                try {
                    const response = await fetch(`{{ route('admin.courses.modules.destroy', ':id') }}`.replace(':id', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (response.ok) window.location.reload();
                } catch (e) {
                    alert('Error deleting section');
                }
            },

            async submitLesson() {
                if (!this.lessonForm.title) return alert('Title is required');
                try {
                    let url = this.lessonMode === 'add' ?
                        `{{ route('admin.courses.lessons.store', ':id') }}`.replace(':id', this.courseId) :
                        `{{ route('admin.courses.lessons.update', ':id') }}`.replace(':id', this.currentLessonId);
                    let method = this.lessonMode === 'add' ? 'POST' : 'PUT';
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            title: this.lessonForm.title,
                            type: this.lessonForm.type,
                            course_module_id: this.lessonForm.moduleId,
                            video_path: this.lessonForm.video_path,
                            content: this.lessonForm.content,
                            duration: this.lessonForm.duration,
                            is_free: this.lessonForm.is_free
                        })
                    });
                    if (response.ok) window.location.reload();
                    else alert('Error saving lesson');
                } catch (e) {
                    console.error(e);
                    alert('An error occurred');
                }
            },

            async deleteLesson(id) {
                if (!confirm('Delete this lesson?')) return;
                try {
                    const response = await fetch(`{{ route('admin.courses.lessons.destroy', ':id') }}`.replace(':id', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (response.ok) window.location.reload();
                } catch (e) {
                    alert('Error deleting lesson');
                }
            },

            // --- SETTINGS METHODS ---
            handleThumbnailChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.settingsForm.thumbnail = file;
                    this.settingsForm.thumbnailPreview = URL.createObjectURL(file);
                }
            },

            async updateSettings() {
                const formData = new FormData();
                formData.append('title', this.settingsForm.title);
                formData.append('slug', this.settingsForm.slug);
                formData.append('description', this.settingsForm.description);
                formData.append('level', this.settingsForm.level);
                formData.append('language', this.settingsForm.language);
                if (this.settingsForm.thumbnail) {
                    formData.append('thumbnail', this.settingsForm.thumbnail);
                }

                try {
                    const response = await fetch(`{{ route('admin.courses.settings.update', ':id') }}`.replace(':id', this.courseId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) alert('Settings updated successfully');
                    else alert('Error updating settings');
                } catch (e) {
                    console.error(e);
                    alert('An error occurred');
                }
            },

            // --- PRICING METHODS ---
            async updatePricing() {
                try {
                    const response = await fetch(`{{ route('admin.courses.pricing.update', ':id') }}`.replace(':id', this.courseId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            price: this.pricingForm.price,
                            discount_price: this.pricingForm.discount_price,
                            is_free: this.pricingForm.is_free
                        })
                    });
                    const data = await response.json();
                    if (data.success) alert('Pricing updated successfully');
                    else alert('Error updating pricing');
                } catch (e) {
                    console.error(e);
                    alert('An error occurred');
                }
            }

        }));
    });
</script>
@endsection