@props(['name', 'label' => '', 'value' => '', 'default' => '<p></p>'])

@php
    $content = $value ?: $default;
    $uid = 'rt_' . md5($name); // unique id taaki multiple editors clash na kare
@endphp

<x-input.wrapper :label="$label">

    <div class="richtext-wrapper bg-primary border-primary border-rounded overflow-hidden"
        data-richtext-id="{{ $uid }}">

        {{-- Toolbar --}}
        <div class="flex gap-1 p-1 border-bottom">
            <button type="button" data-action="bold" class="border-rounded bg-secondary font-bold flex-1">B</button>
            <button type="button" data-action="italic" class="border-rounded bg-secondary italic flex-1">I</button>
            <button type="button" data-action="underline"
                class="border-rounded bg-secondary underline flex-1">U</button>
            <button type="button" data-action="link" class="border-rounded bg-secondary flex-1">🔗</button>
        </div>

        {{-- Link bar --}}
        <div class="link-bar hidden p-2 border-bottom bg-primary text-sm flex gap-1">
            <input type="text" class="link-url border-rounded px-2 py-1 w-full" placeholder="Enter URL">
            <button type="button" class="link-apply p-1 border-rounded bg-invert text-invert">Save</button>
            <button type="button" class="link-remove p-1 border-rounded bg-invert text-invert">Remove</button>
        </div>

        {{-- Editor --}}
        <div class="tiptap-editor p-3 w-full text-sm min-h-25" data-content="{!! $content !!}">
        </div>

        {{-- Hidden input --}}
        <input type="hidden" name="{{ $name }}" value="{{ $content }}">

    </div>

</x-input.wrapper>
