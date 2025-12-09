@php
$s = $block->settings ?? [];

$textType = $s['text_type'] ?? 'heading';
$fontSize = $s['font_size'] ?? 'small';
$fontWeight = $s['font_weight'] ?? 'normal';
@endphp
<h2 style="--arzavo-{{ $textType }}-color: {{ $colors->{$textType} ?? '#000000' }}" class=" w-full arzavo-{{ $textType  === 'heading' ? 'heading-1' : 'paragraph'}} {{ $fontSize === 'small' ? 'text-sm!' : ($fontSize === 'medium' ? 'text-base!' : 'text-lg!') }}" style="font-weight: {{ $fontWeight }};">
    {{ $s['announcement'] ?? 'Welcome to our website!' }}
</h2>