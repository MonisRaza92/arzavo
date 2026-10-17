@props([
    'name',
    'label' => '',
    'value' => null,
    'options' => [],
    'optionLabel' => null,
    'optionValue' => null,
    'class' => '',
    'hint' => null,
    'required' => false
])

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">

    <select name="{{ $name }}" id="{{ Str::slug($name) . '-' . uniqid() }}"
        @required($required)
        {{ $attributes->merge([
            'class' => "w-full bg-secondary text-theme-text text-xs px-4 py-2.5 rounded-full focus:outline-none focus:ring-1 focus:ring-brand font-normal cursor-pointer transition $class",
        ]) }}>

        <option value="">Select Value</option>

        @foreach ($options as $key => $opt)
            @php

                /* ---------- OBJECT ---------- */
                if (is_object($opt)) {
                    $val = $optionValue ? data_get($opt, $optionValue) : $opt->id ?? null;

                    $lab = $optionLabel ? data_get($opt, $optionLabel) : $opt->title ?? ($opt->name ?? $val);
                }
                /* ---------- ARRAY WITH VALUE/LABEL ---------- */ elseif (is_array($opt)) {
                    $val = $optionValue ? data_get($opt, $optionValue) : $opt['value'] ?? $key;

                    $lab = $optionLabel ? data_get($opt, $optionLabel) : $opt['label'] ?? $val;
                }
                /* ---------- ASSOCIATIVE STRING ARRAY ---------- */ elseif (is_string($key)) {
                    $val = $key;
                    $lab = $opt;
                }
                /* ---------- SIMPLE VALUE ---------- */ else {
                    $val = $opt;
                    $lab = is_string($opt) ? ucfirst(str_replace(['_', '-'], ' ', $opt)) : $opt;
                }

            @endphp

            <option value="{{ $val }}" @selected((string) $val === (string) $value)>
                {{ $lab }}
            </option>
        @endforeach

    </select>

</x-input.wrapper>
