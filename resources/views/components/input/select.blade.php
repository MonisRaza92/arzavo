@props([
'name',
'label' => '',
'value' => '',
'options' => [],
'class' => ''
])

<x-input.wrapper :label="$label">

    <select
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>

        @foreach($options as $key => $opt)

        @php
        // Support both formats:
        // 1) ["razorpay","stripe"]
        // 2) ["razorpay"=>"Razorpay","stripe"=>"Stripe"]

        $optionValue = is_string($key) ? $key : $opt;
        $optionLabel = is_string($key) ? $opt : ucfirst($opt);
        @endphp

        <option value="{{ $optionValue }}" @selected($optionValue==$value)>
            {{ $optionLabel }}
        </option>

        @endforeach

    </select>

</x-input.wrapper>
