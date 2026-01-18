<form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
    @csrf

    @foreach($settingsConfig as $sectionKey => $section)

    <div class="bg-primary border-rounded border-primary mb-6">

        <h2 class="text-lg font-semibold p-4 border-bottom">
            {{ $section['title'] }}
        </h2>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">

            @foreach($section['fields'] as $key => $field)

            @php
            $type = $field['type'];
            $label = $field['label'];
            $default = $field['default'] ?? '';
            $value = $settings[$key] ?? $default;
            @endphp

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
            <x-input.textarea :name="$key" :label="$label" :value="$value" />
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

            @endforeach

        </div>

    </div>

    @endforeach

    <button class="default-button px-4 py-2 font-bold">
        Save Settings
    </button>

</form>
