@php
    $s = $block['settings'] ?? [];

    $showHeading = filter_var($s['show_heading'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $heading = $s['heading'] ?? 'Overview';
    $headingType = $s['heading_type'] ?? 'heading-3';

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

    $rawText = $data->short_description ?? $data->text ?? $data->description ?? $data->content ?? null;
    if ($rawText) {
        $rawText = strip_tags(htmlspecialchars_decode(htmlspecialchars_decode($rawText, ENT_QUOTES), ENT_QUOTES));
    }

@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="w-full space-y-1.5 {{ $alignClass }}"
    style="padding: {{ $pt }}px {{ $pr }}px {{ $pb }}px {{ $pl }}px;">

    @if($showHeading && filled($heading))
        <div class="arz-{{ $headingType }} font-bold text-primary">
            {{ $heading }}
        </div>
    @endif

    @if ($rawText)
        <p class="arz-{{ $type }} text-secondary">
            {{ $enableLimit ? Str::limit($rawText, $textLimit) : $rawText }}
        </p>
    @elseif(isBuilder())
        <p class="arz-{{ $type }} text-secondary italic">
            Essential physics guide for JEE Main & Advanced aspirants with chapter-wise theory and solved examples.
        </p>
    @endif
</div>