@props([
'variant' => 'primary', // primary | secondary | icon
'id' => null,
'disabled' => false,
'url' => null,
'icon' => null,
'iconType' => 'solid',
'loading' => true,
'loadingText' => 'Loading...',
'padding' => 'px-4 py-2',
'class' => null
])

@php
$isIcon = $variant === 'icon';

$base = $isIcon
? 'flex items-center justify-center transition cursor-pointer'
: 'flex items-center justify-center border-rounded border-primary gap-2 w-fit ' . $padding . ' ' . $class . ' font-medium transition cursor-pointer';

$variants = [
'primary' => 'bg-invert text-invert hover:bg-transparent hover:text-black',
'secondary' => 'bg-transparent text-primary',
'accent' => 'bg-accent text-white hover:bg-transparent',
'accent-secondary' => 'bg-accent-secondary text-white hover:bg-transparent',
'icon' => 'md:text-lg lg:text-2xl',
];

$classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.$class;
@endphp

<a
    x-data="{ loading: false }"
    @click="if (!{{ $disabled ? 'true' : 'false' }}) loading = {{ $loading ? 'true' : 'false' }}"
    @button-reset.window="loading = false"
    @pageshow.window="loading = false"
    @if($url) href="{{ $url }}" @endif
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
    :class="{ 'opacity-60 pointer-events-none': loading || {{ $disabled ? 'true' : 'false' }} }">

    {{-- NORMAL --}}
    <template x-if="!loading">
        <span class="flex items-center gap-2">
            @if($isIcon && $icon)
            <i class="fa-{{ $iconType }} fa-{{ $icon }}"></i>
            @elseif(!$isIcon)
            {{ trim($slot) !== '' ? $slot : 'Loading...' }}
            @endif
        </span>
    </template>

    {{-- LOADING --}}
    <template x-if="loading">
        <span class="flex items-center gap-2">
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