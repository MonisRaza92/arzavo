@php
    $iconSize = $block->icon_size ?? 20;
    $gap = $block->gap ?? 12;
    $iconColor = $block->icon_color ?? 'heading';

    $colorVar = match($iconColor) {
        'paragraph' => 'var(--arz-paragraph)',
        'link' => 'var(--arz-link)',
        default => 'var(--arz-heading)',
    };

    $platforms = [
        'facebook'  => ['icon' => 'fa-brands fa-facebook-f',  'url' => $block->facebook ?? ''],
        'twitter'   => ['icon' => 'fa-brands fa-x-twitter',   'url' => $block->twitter ?? ''],
        'instagram' => ['icon' => 'fa-brands fa-instagram',   'url' => $block->instagram ?? ''],
        'linkedin'  => ['icon' => 'fa-brands fa-linkedin-in', 'url' => $block->linkedin ?? ''],
        'youtube'   => ['icon' => 'fa-brands fa-youtube',     'url' => $block->youtube ?? ''],
        'tiktok'    => ['icon' => 'fa-brands fa-tiktok',      'url' => $block->tiktok ?? ''],
        'whatsapp'  => ['icon' => 'fa-brands fa-whatsapp',    'url' => $block->whatsapp ?? ''],
    ];
@endphp

<div {!! $block->attributes() !!} class="social-icons" style="display:flex; align-items:center; gap:{{ $gap }}px; flex-wrap:wrap;">
    @foreach($platforms as $name => $platform)
        @if(!empty($platform['url']))
            <a href="{{ $platform['url'] }}" target="_blank" rel="noopener noreferrer"
               class="social-icon-link"
               style="color: {{ $colorVar }}; font-size: {{ $iconSize }}px;"
               aria-label="{{ ucfirst($name) }}">
                <i class="{{ $platform['icon'] }}"></i>
            </a>
        @endif
    @endforeach
</div>

<style>
    .social-icon-link {
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .social-icon-link:hover {
        opacity: 0.7;
    }
</style>
