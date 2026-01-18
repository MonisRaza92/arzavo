@props([
'name',
'label' => '',
'value' => 0,
'min' => 0,
'max' => 100,
'step' => 1,
'unit' => '',
'class' => ''
])

<x-input.wrapper :label="$label">

    <div class="flex items-center gap-4">

        <input
            type="range"
            name="{{ $name }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            value="{{ $value }}"
            class="w-full {{ $class }}"
            oninput="this.nextElementSibling.innerText = this.value + '{{ $unit }}'">

        <span class="text-sm font-semibold min-w-15 text-right">
            {{ $value }}{{ $unit }}
        </span>

    </div>

</x-input.wrapper>
