@props([
    'name',
    'label' => '',
    'value' => null,
    'options' => [],
    'optionLabel' => null,
    'optionValue' => null,
    'class' => '',
])

<x-input.wrapper :label="$label">

    <select name="{{ $name }}" id="{{ Str::slug($name) . '-' . uniqid() }}"
        {{ $attributes->merge([
            'class' => "w-full p-2 border-rounded text-xs border-primary bg-transparent $class",
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
