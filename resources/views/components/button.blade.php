@props([
    'variant' => 'primary', // primary | secondary | danger | icon
    'type' => 'button',
    'id' => null,
    'disabled' => false,
    'url' => null,
    'icon' => null,
    'iconType' => 'solid',
    'loading' => false,
    'loadingText' => 'Loading...',
    'padding' => 'px-5 py-2.5',
    'class' => ''
])

@php
$isIcon = $variant === 'icon';

$base = $isIcon
? 'inline-flex items-center justify-center transition cursor-pointer rounded-full'
: 'inline-flex items-center justify-center rounded-full gap-2 w-fit ' . $padding . ' font-normal text-xs transition cursor-pointer';

$variants = [
    'primary' => 'bg-brand text-primary hover:opacity-90 shadow-sm',
    'secondary' => 'bg-secondary text-theme-text hover:bg-subtle',
    'danger' => 'bg-danger text-white hover:opacity-90 shadow-sm',
    'accent' => 'bg-brand text-primary hover:opacity-90 shadow-sm',
    'accent-secondary' => 'bg-secondary text-theme-text hover:bg-subtle',
    'icon' => 'w-8 h-8 bg-secondary hover:bg-subtle text-theme-text-muted hover:text-theme-text',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . $class;
@endphp

@if($url)
<a
    x-data="{ loading: false }"
    @click="if (!{{ $disabled ? 'true' : 'false' }}) loading = {{ $loading ? 'true' : 'false' }}"
    @button-reset.window="loading = false"
    @pageshow.window="loading = false"
    href="{{ $url }}"
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
    :class="{ 'opacity-60 pointer-events-none': loading || {{ $disabled ? 'true' : 'false' }} }">

    {{-- NORMAL --}}
    <template x-if="!loading">
        <span class="inline-flex items-center gap-2">
            @if($isIcon && $icon)
            <i class="fa-{{ $iconType }} fa-{{ $icon }}"></i>
            @elseif(!$isIcon)
                @if($icon) <i class="fa-{{ $iconType }} fa-{{ $icon }}"></i> @endif
                {{ trim($slot) !== '' ? $slot : 'Action' }}
            @endif
        </span>
    </template>

    {{-- LOADING --}}
    <template x-if="loading">
        <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" opacity="0.3" />
                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" />
            </svg>
            @if(!$isIcon)
            {{ $loadingText }}
            @endif
        </span>
    </template>
</a>
@else
<button
    type="{{ $type }}"
    x-data="{ loading: false }"
    @click="if (!{{ $disabled ? 'true' : 'false' }}) loading = {{ $loading ? 'true' : 'false' }}"
    @button-reset.window="loading = false"
    @pageshow.window="loading = false"
    @if($id) id="{{ $id }}" @endif
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
    :class="{ 'opacity-60 pointer-events-none': loading || {{ $disabled ? 'true' : 'false' }} }">

    {{-- NORMAL --}}
    <template x-if="!loading">
        <span class="inline-flex items-center gap-2">
            @if($isIcon && $icon)
            <i class="fa-{{ $iconType }} fa-{{ $icon }}"></i>
            @elseif(!$isIcon)
                @if($icon) <i class="fa-{{ $iconType }} fa-{{ $icon }}"></i> @endif
                {{ trim($slot) !== '' ? $slot : 'Action' }}
            @endif
        </span>
    </template>

    {{-- LOADING --}}
    <template x-if="loading">
        <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" opacity="0.3" />
                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" />
            </svg>
            @if(!$isIcon)
            {{ $loadingText }}
            @endif
        </span>
    </template>
</button>
@endif