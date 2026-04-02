@php($s = section($section))
<nav {!! $s->attributes() !!} style="border-bottom-width: {{ $s->divider }}px;"
    class="z-50 {{ $s->navbar_behavior === 'sticky' ? 'sticky top-0 left-0' : '' }} arz-core arz-border-b arzavo-background">
    <div class="{{ $s->width }} navbar flex gap-4 justify-between items-center w-full py-3">
        <div class="flex items-center justify-between gap-6 grow">
            {!! $s->blocks() !!}
        </div>
        <div class="right flex items-center gap-4">
            <a href="{{ arz_route('login') }}"><i class="fa-regular fa-user"
                    style="font-size: {{ $s->icon_size ?? '20' }}px;"></i></a>
            <!-- <a href=""><i class="fa-regular fa-camera"
                    style="font-size: {{ $s->icon_size ?? '20' }}px;"></i></a> -->
            <button onclick="toggleMobileMenu()" class="lg:hidden">
                <i class="fa-solid fa-bars" style="font-size: calc({{ $s->icon_size ?? '20' }}px + 5px);"></i>
            </button>
        </div>
    </div>
</nav>