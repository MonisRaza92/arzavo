@php
$s = $block->settings ?? [];

$textType = $s['text_type'] ?? 'paragraph';
@endphp
<p class="w-fit arzavo-{{ $textType }}" data-block-id="{{ $block->id }}" data-name="{{ $block->name }}">
    {{ $s['announcement'] ?? 'Welcome to our website!' }}
</p>