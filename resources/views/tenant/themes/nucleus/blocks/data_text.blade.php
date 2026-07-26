@php
    $s = $block['settings'] ?? [];

    $type = $s['text_type'] ?? 'paragraph';


    $align = $s['alignment'] ?? 'left';

    $pt = $s['padding_top'] ?? 0;
    $pb = $s['padding_bottom'] ?? 0;
    $pl = $s['padding_left'] ?? 0;
    $pr = $s['padding_right'] ?? 0;



    $alignClass = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

@endphp

<p data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="
text-{{ $block['id'] }}
arz-{{ $type }}
{{ $alignClass }}
"
    style="
padding: {{ $pt }}px {{ $pr }}px {{ $pb }}px {{ $pl }}px;
">
@if ($data->description ?? $data->text ?? null)
{{ substr($data->description ?? $data->text, 0, 150) }}
@endif
</p>
