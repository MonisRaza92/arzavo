@php
    $s = $block['settings'] ?? [];

    $text = $s['text'] ?? '';
    $textType = $s['text_type'] ?? '';
    $fontSize = $s['font_size'] ?? '';
    $fontWeight = $s['font_weight'] ?? '';
    $fontStyle = $s['font_style'] ?? '';
    $textDecoration = $s['text_decoration'] ?? '';
    $lineHeight = $s['line_height'] ?? '';
    $alignment = $s['alignment'] ?? '';
    $mAlignment = $s['mobile_alignment'] ?? '';
    $mt = $s['margin_top'] ?? 0;
    $mb = $s['margin_bottom'] ?? 0;
    $pt = $s['padding_top'] ?? 0;
    $pb = $s['padding_bottom'] ?? 0;
    $pl = $s['padding_left'] ?? 0;
    $pr = $s['padding_right'] ?? 0;


    $lineHeightClass = match ($lineHeight) {
        'tight' => 'leading-tight',
        'normal' => 'leading-normal',
        'relaxed' => 'leading-relaxed',
        'loose' => 'leading-loose',
        default => 'leading-normal'
    };

    $alignmentClass = match ($alignment) {
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
        'justify' => 'text-justify',
        default => 'text-left'
    };
@endphp


<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    " class="
        text-{{ $block['id'] }}
        arzavo-{{ $textType }}
        {{ $lineHeightClass }}
        {{ $alignmentClass }}
    ">
    <div class="flex items-center gap-2">
        @if ($course->author ?? null)
            @if ($user->profile_picture ?? null)
                <img src="{{ media($user->profile_picture) }}" class="border-rounded w-8 aspect-square object-cover"
                    alt="{{ $user->fname }}">
            @else
                <h2
                    class="font-bold border-rounded text-xl flex justify-center items-center w-8 aspect-square arzavo-background" style="filter: invert(1); color: var(--arzavo-heading-color);">
                    {{ strtoupper(substr($user->fname, 0, 1)) }}
                </h2>
            @endif
        @else
            <h2 class="font-bold border-rounded text-xl flex justify-center items-center w-8 aspect-square arzavo-background" style="filter: invert(1); color: var(--arzavo-heading-color);">
                A
            </h2>
        @endif
        <div>
            <p class="font-semibold" style="color: var(--arzavo-heading-color);">
                {{ $course->author->fname ?? 'Arzavo' }} {{ $course->author->lname ?? '' }}
            </p>
            <p class="text-tertiary" style="font-size: var(--arzavo-secondary-text-font-size); color: var(--arzavo-secondary-text-color);">
                {{ $course->author->email ?? 'arzavoems@gmail.com' }}
            </p>
        </div>
    </div>
</div>
