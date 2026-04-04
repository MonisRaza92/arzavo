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

    $schemesUsed = $allSections
    ->pluck('color_scheme')
    ->filter()
    ->unique();
    
    foreach($allSections as $section){
    $schemeMap[$section['id']] = $section['color_scheme'] ?? 'scheme_1';
}
@endphp
<style>
@foreach($schemesUsed as $schemeKey)
.arz-{{ $schemeKey }} {
    {!! scheme($schemeKey) !!}
}
@endforeach
</style>

{{-- ========================= --}}
{{-- 🔵 GLOBAL HEADER --}}
{{-- ========================= --}}
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

document.querySelectorAll('[data-section-id]').forEach(el => {
    const id = el.getAttribute('data-section-id');

    // section data inject karna padega
    const schemeMap = @json($schemeMap);

    el.classList.add('arz-core');
    if (id) {
        el.classList.add('arz-' + id);
    }

    if (schemeMap[id]) {
        el.classList.add('arz-' + schemeMap[id]);
    }
});
</script>

@endsection
