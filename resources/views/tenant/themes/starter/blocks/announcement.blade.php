@php
    $s = $block['settings'] ?? [];

    $text = $s['text'] ?? 'Announcement';
    $type = $s['text_type'] ?? 'paragraph';
    $size = $s['font_size'] ?? 14;

    $url = $s['url'] ?? null;
    $new = ($s['open_new'] ?? '0') === '1';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="px-3 whitespace-nowrap">

    @if($url)
        <a href="{{ $url }}" class="inline-block" @if($new) target="_blank" @endif>
    @endif

        <span class="arzavo-{{ $type }}">
            {!! $text !!}
        </span>

        @if($url)
            </a>
        @endif

</div>