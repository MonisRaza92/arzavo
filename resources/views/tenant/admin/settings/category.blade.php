@extends('layouts.admin')

@section('title', $section['title'])

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-6">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1">
            <i class="fa-solid fa-sliders mr-1 text-base"></i>
            {{ $section['title'] }}
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Configure and optimize your platform's {{ strtolower($section['title']) }} settings.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="bg-primary border-rounded border-primary mb-6">
        <h2 class="text-md font-semibold p-4 border-bottom text-primary flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-accent text-sm"></i>
            {{ $section['title'] }} Options
        </h2>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach($section['fields'] as $key => $field)

                @php
                    $type = $field['type'];
                    $label = $field['label'];
                    $default = $field['default'] ?? '';
                    $value = $settings[$key] ?? $default;
                    
                    // Specific fields like custom code should be full width and use code font
                    $isFullWidth = in_array($type, ['textarea', 'image']) || in_array($key, ['schema_org_json', 'custom_head_tags']);
                @endphp

                <div class="flex flex-col gap-2 {{ $isFullWidth ? 'md:col-span-2' : '' }}">
                    @switch($type)

                        @case('text')
                            <x-input.text :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('email')
                            <x-input.email :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('url')
                            <x-input.url :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('number')
                            <x-input.number :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('password')
                            <x-input.password :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('textarea')
                            <x-input.textarea :name="$key" :label="$label" :value="$value" rows="6" class="font-mono text-xs!" />
                            @break

                        @case('select')
                            <x-input.select
                                :name="$key"
                                :label="$label"
                                :value="$value"
                                :options="$field['options']" />
                            @break

                        @case('toggle')
                            <x-input.toggle :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('image')
                            <x-input.image :name="$key" :label="$label" :value="$value" />
                            @break

                        @case('color')
                            <x-input.color :name="$key" :label="$label" :value="$value" />
                            @break

                    @endswitch
                </div>

            @endforeach

        </div>
    </div>

    <div class="flex justify-end mb-6">
        <button class="default-button px-6 py-2.5 font-bold flex items-center gap-2 text-sm">
            <i class="fa-solid fa-floppy-disk text-base"></i>
            Save {{ $section['title'] }} Settings
        </button>
    </div>

</form>
@endsection
