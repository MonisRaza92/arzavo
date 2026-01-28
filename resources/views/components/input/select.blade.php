@props([
'name',
'label' => '',
'value' => null,
'options' => [],
'optionLabel' => null,
'optionValue' => null,
'class' => ''
])

<x-input.wrapper :label="$label">

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>

        @foreach($options as $key => $opt)

        @php
        // Case 1: Collection / Object
        if (is_object($opt)) {
        $optionValue = $optionValue
        ? data_get($opt, $optionValue)
        : $opt->id;

        $optionLabel = $optionLabel
        ? data_get($opt, $optionLabel)
        : ($opt->title ?? $opt->name ?? $optionValue);
        }
        // Case 2: Associative array
        elseif (is_string($key)) {
        $optionValue = $key;
        $optionLabel = $opt;
        }
        // Case 3: Simple array
        else {
        $optionValue = $opt;
        $optionLabel = ucfirst($opt);
        }
        @endphp

        <option
            value="{{ $optionValue }}"
            @selected((string)$optionValue===(string)$value)>
            {{ $optionLabel }}
        </option>

        @endforeach

    </select>

</x-input.wrapper>