@php
    $s = $block['settings'] ?? [];

    $type = $s['text_type'] ?? 'paragraph';
    $align = $s['alignment'] ?? 'left';

    $pt = $s['padding_top'] ?? 0;
    $pb = $s['padding_bottom'] ?? 0;
    $pl = $s['padding_left'] ?? 0;
    $pr = $s['padding_right'] ?? 0;

    $enableLimit = filter_var($s['enable_text_limit'] ?? $block->enable_text_limit ?? true, FILTER_VALIDATE_BOOLEAN);
    $textLimit = (int) ($s['text_limit'] ?? $block->text_limit ?? 80);

    $alignClass = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<p data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="text-{{ $block['id'] }} arz-{{ $type }} {{ $alignClass }}"
    style="padding: {{ $pt }}px {{ $pr }}px {{ $pb }}px {{ $pl }}px;">
    @php
        $rawText = $data->description ?? $data->text ?? null;
    @endphp
    @if ($rawText)
        {{ $enableLimit ? Str::limit(strip_tags($rawText), $textLimit) : $rawText }}
    @endif
</p>
