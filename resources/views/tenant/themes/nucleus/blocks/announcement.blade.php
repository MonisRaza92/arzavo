@php
$s = $block->settings ?? [];

$textType = $s['text_type'] ?? 'paragraph';
@endphp
<p class=" w-full arzavo-{{ $textType }}">
    {{ $s['announcement'] ?? 'Welcome to our website!' }}
</p>