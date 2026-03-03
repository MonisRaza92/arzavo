@props(['name', 'label' => '', 'options' => [], 'value' => null])

<x-input.wrapper :label="$label">

    <div class="flex border-primary border-rounded bg-secondary overflow-hidden">

        @foreach ($options as $key => $opt)
            @php
                // object / array / simple string handling
                if (is_object($opt)) {
                    $optionValue = $opt->value ?? ($opt->id ?? null);
                    $optionLabel = $opt->label ?? ($opt->name ?? $optionValue);
                } elseif (is_string($key)) {
                    $optionValue = $key;
                    $optionLabel = $opt;
                } else {
                    $optionValue = $opt;
                    $optionLabel = ucfirst(str_replace(['_', '-'], ' ', $opt));
                }
            @endphp

            <label class="flex-1 cursor-pointer relative group select-none">

                <input type="radio" name="{{ $name }}" value="{{ $optionValue }}" @checked((string) $optionValue === (string) old($name, $value))
                    {{ $attributes->merge([
                        'class' => 'peer absolute opacity-0 live-input',
                    ]) }}>

                <span
                    class="
                    block p-2 text-xs text-center capitalize
                    transition-all duration-200

                    text-primary bg-transparent
                    active:bg-zinc-200 border-rounded
                    peer-checked:bg-white
                    peer-checked:text-black
                    peer-checked:shadow
                ">
                    {{ $optionLabel }}
                </span>

            </label>
        @endforeach

    </div>

</x-input.wrapper>
