@extends('layouts.website')

@section('title', $page->meta_title)

@section('content')

@php
    $globalDesign = globalThemeDesign($themeId)->layout;

    $allSections = collect();

    // header
    if (!empty($globalDesign['header']['sections'])) {
        $allSections = $allSections->merge($globalDesign['header']['sections']);
    }

    // page
    if (!empty($layout['sections'])) {
        $allSections = $allSections->merge($layout['sections']);
    }

    // footer
    if (!empty($globalDesign['footer']['sections'])) {
        $allSections = $allSections->merge($globalDesign['footer']['sections']);
    }

    // Extract separate schemes from blocks recursively
    $blockSchemeMap = [];
    $collectBlocks = function($blocks) use (&$collectBlocks, &$blockSchemeMap) {
        foreach ($blocks as $block) {
            $settings = $block['settings'] ?? [];
            if (($settings['scheme_mode'] ?? 'inherit') === 'separate' && !empty($block['color_scheme'])) {
                $blockSchemeMap[$block['id']] = $block['color_scheme'];
            }
            if (!empty($block['blocks'])) {
                $collectBlocks($block['blocks']);
            }
        }
    };
    
    foreach ($allSections as $section) {
        if (!empty($section['blocks'])) {
            $collectBlocks($section['blocks']);
        }
    }

    $schemesUsed = $allSections
        ->pluck('color_scheme')
        ->merge(collect($blockSchemeMap)->values())
        ->filter()
        ->unique();
    
    $schemeMap = [];
    foreach($allSections as $section){
        $schemeMap[$section['id']] = $section['color_scheme'] ?? 'scheme_1';
    }
@endphp

{{-- ========================= --}}
{{-- 🎨 COLOR SCHEMES --}}
{{-- ========================= --}}
<style data-theme="{{ $theme }}">
@foreach($schemesUsed as $schemeKey)
.arz-{{ $schemeKey }} {
    {!! scheme($schemeKey) !!}
}
@endforeach
</style>

{{-- ========================= --}}
{{-- 📦 THEME ASSETS (CSS) --}}
{{-- ========================= --}}
@php
    $themeCss = \App\Services\Theme\ThemeAssetResolver::allCss($theme);
@endphp
@if($themeCss)
<style data-theme-assets="{{ $theme }}">
{!! $themeCss !!}
</style>
@endif


{{-- ========================= --}}
{{-- 🔵 GLOBAL HEADER --}}
{{-- ========================= --}}
<div data-theme="{{ $theme }}" data-theme-region="content">

@if(!empty($globalDesign['header']['sections']))
    @foreach($globalDesign['header']['sections'] as $section)
        @if(empty($section['is_active']))
            @continue
        @endif

        @php
            $viewPath = 'tenant.themes.' . $theme . '.sections.' . ($section['type'] ?? '');
        @endphp

        @if(!empty($section['type']) && View::exists($viewPath))
            @includeIf($viewPath, [
                'section' => section($section),
                'theme' => $theme,
                'context' => 'global-header'
            ])
        @endif
    @endforeach
@endif


{{-- ========================= --}}
{{-- 🟢 PAGE CONTENT --}}
{{-- ========================= --}}
@if(empty($layout['sections']))
    <p class="text-center py-10 text-gray-500">
        No content available for this page.
    </p>
@else
    @foreach($layout['sections'] as $section)
        @if(empty($section['is_active']))
            @continue
        @endif

        @php
            $viewPath = 'tenant.themes.' . $theme . '.sections.' . ($section['type'] ?? '');
        @endphp

        @if(!empty($section['type']) && View::exists($viewPath))
            @includeIf($viewPath, [
                'section' => section($section),
                'theme' => $theme,
                'context' => 'page'
            ])
        @endif
    @endforeach
@endif


{{-- ========================= --}}
{{-- 🔴 GLOBAL FOOTER --}}
{{-- ========================= --}}
@if(!empty($globalDesign['footer']['sections']))
    @foreach($globalDesign['footer']['sections'] as $section)
        @if(empty($section['is_active']))
            @continue
        @endif

        @php
            $viewPath = 'tenant.themes.' . $theme . '.sections.' . ($section['type'] ?? '');
        @endphp

        @if(!empty($section['type']) && View::exists($viewPath))
            @includeIf($viewPath, [
                'section' => section($section),
                'theme' => $theme,
                'context' => 'global-footer'
            ])
        @endif
    @endforeach
@endif

</div>


{{-- ========================= --}}
{{-- 🛠️ SECTION & BLOCK CLASS INJECTION --}}
{{-- ========================= --}}
<script>
(function() {
    const schemeMap = @json($schemeMap);
    const blockSchemeMap = @json($blockSchemeMap);

    document.querySelectorAll('[data-section-id]').forEach(function(el) {
        const id = el.getAttribute('data-section-id');

        el.classList.add('arz-core');

        if (id) {
            el.classList.add('arz-sec' + id);
        }

        if (schemeMap[id]) {
            el.classList.add('arz-' + schemeMap[id]);
        }
    });

    document.querySelectorAll('[data-block-id]').forEach(function(el) {
        const id = el.getAttribute('data-block-id');
        el.classList.add('arz-block');

        if (id) {
            el.classList.add('arz-blk' + id);
        }

        if (id && blockSchemeMap[id]) {
            el.classList.add('arz-' + blockSchemeMap[id]);
        }
    });
})();
</script>

{{-- ========================= --}}
{{-- 📦 THEME ASSETS (JS) --}}
{{-- ========================= --}}
@php
    $themeJs = \App\Services\Theme\ThemeAssetResolver::allJs($theme);
@endphp
@if($themeJs)
<script data-theme-js="{{ $theme }}">
{!! $themeJs !!}
</script>
@endif

{{-- ========================= --}}
{{-- 🛠️ EDITOR MODE HANDSHAKE --}}
{{-- ========================= --}}
<script>
    window.ARZAVO_EDITOR_MODE = false;

    window.addEventListener("message", function (e) {
        if (e.data && e.data.type === "ARZAVO_EDITOR_MODE") {
            window.ARZAVO_EDITOR_MODE = e.data.enabled === true;
        }
    });
</script>

@endsection
