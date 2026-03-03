@props(['name', 'label' => '', 'value' => 0, 'min' => 0, 'max' => 100, 'step' => 1, 'unit' => '', 'class' => ''])

<x-input.wrapper :label="$label">

    <div class="flex items-center gap-1 range-sync">

        <!-- RANGE -->
        <input type="range" name="{{ $name }}" min="{{ $min }}" max="{{ $max }}"
            step="{{ $step }}" value="{{ $value }}" class="w-full {{ $class }} range-input"
            style="accent-color:black">

        <!-- NUMBER -->
        <input type="number" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}"
            value="{{ $value }}" class="w-12 p-1 text-xs border-rounded border-primary number-input">

        @if ($unit)
            <p class="text-primary text-xs">{{ $unit }}</p>
        @endif

    </div>

</x-input.wrapper>


@once
    <script>
        document.addEventListener('input', function(e) {

            const wrapper = e.target.closest('.range-sync');
            if (!wrapper) return;

            const range = wrapper.querySelector('.range-input');
            const number = wrapper.querySelector('.number-input');

            /* RANGE → NUMBER */
            if (e.target === range) {
                number.value = range.value;
            }

            /* NUMBER → RANGE */
            if (e.target === number) {

                let v = parseFloat(number.value);
                if (isNaN(v)) return;

                const min = parseFloat(range.min);
                const max = parseFloat(range.max);

                if (v < min) v = min;
                if (v > max) v = max;

                number.value = v;
                range.value = v;
            }

        });
    </script>
@endonce
