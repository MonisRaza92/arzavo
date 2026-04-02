@php($b = block($block))
<div {!! $b->attributes() !!} class="w-auto shrink-0" style="{{ $b->spacing }}">
    <a href="{{ route('tenant.home') }}" class="block relative">

        @if($customizes['logo'] ?? false)
            <img src="{{ media($customizes['logo']) }}" alt="Logo"
                class="arzavo-logo-normal w-auto transition-opacity duration-300 arz-logo-size">
        @endif

        @if($customizes['invert_Logo'] ?? false)
            <img src="{{ media($customizes['invert_Logo']) }}" alt="Invert Logo"
                class="arzavo-logo-invert w-auto absolute top-0 left-0 transition-opacity duration-300 opacity-0 arz-logo-size">
        @endif

        @if(!$customizes['logo'] && !$customizes['invert_Logo'])
            <h2 class="text-xl font-semibold arz-logo-size" style="color: var(--arzavo-heading-color);">
                {{ app('currentTenant')->name }}
            </h2>
        @endif
</div>