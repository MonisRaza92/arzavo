<div {!! $block->attributes() !!} class="w-auto shrink-0" style="{{ $block->padding }}">
    <a href="{{ route_to('home') }}" class="block relative">
        @if (logo())
            @if(render_logo())
                <img src="{{ render_logo() }}" alt="Logo"
                    class="w-auto transition-opacity duration-300 arz-logo">
            @endif

            @if(render_invert_logo())
                <img src="{{ render_invert_logo() }}" alt="Invert Logo"
                    class="w-auto absolute top-0 left-0 transition-opacity duration-300 opacity-0 arz-logo">
            @endif

        @else
            <h2 class="font-semibold tenant-name">
                {{ tenant_name() }}
            </h2>
        @endif
</div>