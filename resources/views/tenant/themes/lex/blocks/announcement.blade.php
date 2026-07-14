@php
    $text = $block->text ?? 'Announcement';
    $type = $block->text_type ?? 'paragraph';
    $url = $block->url ?? null;
    $new = ($block->open_new ?? '0') === '1';
@endphp

<div {!! $block->attributes() !!} class="px-3">

    @if($url)
        <a href="{{ $url }}" class="inline-block hover:opacity-80 transition-opacity" @if($new) target="_blank" rel="noopener" @endif>
    @endif

        <span class="arzavo-{{ $type }}">
            {!! $text !!}
        </span>

        @if($url)
            </a>
        @endif

</div>
